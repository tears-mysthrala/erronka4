<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Validation;

/**
 * Validador de datos de empleados
 * 
 * Validaciones implementadas:
 * - NIF/NIE español
 * - Email
 * - Teléfono español
 * - Código postal español
 * - IBAN español
 * - Campos requeridos
 */
class EmployeeValidator
{
    /**
     * Validar datos completos de empleado
     */
    public function validate(array $data, bool $isUpdate = false): array
    {
        $errors = [];
        
        // Validar email
        if (!$isUpdate || isset($data['email'])) {
            $emailError = $this->validateEmail($data['email'] ?? '');
            if ($emailError) {
                $errors['email'] = $emailError;
            }
        }
        
        // Validar password (solo en creación)
        if (!$isUpdate) {
            $passwordError = $this->validatePassword($data['password'] ?? '');
            if ($passwordError) {
                $errors['password'] = $passwordError;
            }
        }
        
        // Validar first_name
        if (!$isUpdate || isset($data['first_name'])) {
            $firstNameError = $this->validateName($data['first_name'] ?? '', 'first_name');
            if ($firstNameError) {
                $errors['first_name'] = $firstNameError;
            }
        }
        
        // Validar last_name
        if (!$isUpdate || isset($data['last_name'])) {
            $lastNameError = $this->validateName($data['last_name'] ?? '', 'last_name');
            if ($lastNameError) {
                $errors['last_name'] = $lastNameError;
            }
        }
        
        // Validar NIF
        if (!$isUpdate || isset($data['nif'])) {
            $nifError = $this->validateNIF($data['nif'] ?? '');
            if ($nifError) {
                $errors['nif'] = $nifError;
            }
        }
        
        // Validar position
        if (!$isUpdate || isset($data['position'])) {
            $positionError = $this->validatePosition($data['position'] ?? '');
            if ($positionError) {
                $errors['position'] = $positionError;
            }
        }
        
        // Validar phone (opcional)
        if (isset($data['phone']) && !empty($data['phone'])) {
            $phoneError = $this->validatePhone($data['phone']);
            if ($phoneError) {
                $errors['phone'] = $phoneError;
            }
        }
        
        // Validar postal_code (opcional)
        if (isset($data['postal_code']) && !empty($data['postal_code'])) {
            $postalError = $this->validatePostalCode($data['postal_code']);
            if ($postalError) {
                $errors['postal_code'] = $postalError;
            }
        }
        
        // Validar bank_account (opcional)
        if (isset($data['bank_account']) && !empty($data['bank_account'])) {
            $ibanError = $this->validateIBAN($data['bank_account']);
            if ($ibanError) {
                $errors['bank_account'] = $ibanError;
            }
        }
        
        // Validar salary (opcional)
        if (isset($data['salary']) && !empty($data['salary'])) {
            $salaryError = $this->validateSalary($data['salary']);
            if ($salaryError) {
                $errors['salary'] = $salaryError;
            }
        }
        
        // Validar hire_date (opcional)
        if (isset($data['hire_date']) && !empty($data['hire_date'])) {
            $dateError = $this->validateDate($data['hire_date']);
            if ($dateError) {
                $errors['hire_date'] = $dateError;
            }
        }
        
        return $errors;
    }
    
    /**
     * Validar email
     */
    public function validateEmail(string $email): ?string
    {
        if (empty($email)) {
            return 'El email es requerido';
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'El email no es válido';
        }
        
        if (strlen($email) > 255) {
            return 'El email no puede exceder 255 caracteres';
        }
        
        return null;
    }
    
    /**
     * Validar contraseña
     */
    public function validatePassword(string $password): ?string
    {
        if (empty($password)) {
            return 'La contraseña es requerida';
        }
        
        if (strlen($password) < 8) {
            return 'La contraseña debe tener al menos 8 caracteres';
        }
        
        if (strlen($password) > 128) {
            return 'La contraseña no puede exceder 128 caracteres';
        }
        
        // Al menos una letra mayúscula
        if (!preg_match('/[A-Z]/', $password)) {
            return 'La contraseña debe contener al menos una letra mayúscula';
        }
        
        // Al menos una letra minúscula
        if (!preg_match('/[a-z]/', $password)) {
            return 'La contraseña debe contener al menos una letra minúscula';
        }
        
        // Al menos un número
        if (!preg_match('/[0-9]/', $password)) {
            return 'La contraseña debe contener al menos un número';
        }
        
        return null;
    }
    
    /**
     * Validar nombre
     */
    public function validateName(string $name, string $fieldName): ?string
    {
        if (empty($name)) {
            return "El {$fieldName} es requerido";
        }
        
        if (strlen($name) < 2) {
            return "El {$fieldName} debe tener al menos 2 caracteres";
        }
        
        if (strlen($name) > 100) {
            return "El {$fieldName} no puede exceder 100 caracteres";
        }
        
        // Solo letras, espacios, guiones y apóstrofes
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-']+$/u", $name)) {
            return "El {$fieldName} solo puede contener letras, espacios, guiones y apóstrofes";
        }
        
        return null;
    }
    
    /**
     * Validar NIF/NIE español
     */
    public function validateNIF(string $nif): ?string
    {
        if (empty($nif)) {
            return 'El NIF/NIE es requerido';
        }
        
        // Limpiar espacios y convertir a mayúsculas
        $nif = strtoupper(str_replace([' ', '-'], '', $nif));
        
        // Validar formato básico
        if (!preg_match('/^[XYZ0-9][0-9]{7}[A-Z]$/', $nif)) {
            return 'El NIF/NIE no tiene un formato válido (ej: 12345678A o X1234567A)';
        }
        
        // Validar letra del NIF/NIE
        $letters = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $letter = substr($nif, -1);
        $number = substr($nif, 0, -1);
        
        // Convertir NIE a NIF
        $number = str_replace(['X', 'Y', 'Z'], ['0', '1', '2'], $number);
        
        $expectedLetter = $letters[(int)$number % 23];
        
        if ($letter !== $expectedLetter) {
            return 'La letra del NIF/NIE no es correcta';
        }
        
        return null;
    }
    
    /**
     * Validar cargo/posición
     */
    public function validatePosition(string $position): ?string
    {
        if (empty($position)) {
            return 'El cargo es requerido';
        }
        
        if (strlen($position) < 2) {
            return 'El cargo debe tener al menos 2 caracteres';
        }
        
        if (strlen($position) > 100) {
            return 'El cargo no puede exceder 100 caracteres';
        }
        
        return null;
    }
    
    /**
     * Validar teléfono español
     */
    public function validatePhone(string $phone): ?string
    {
        // Limpiar espacios, guiones y paréntesis
        $cleanPhone = preg_replace('/[\s\-\(\)]/', '', $phone);
        
        // Teléfono fijo (9 dígitos empezando por 8 o 9)
        // Móvil (9 dígitos empezando por 6 o 7)
        // Con prefijo internacional (+34)
        if (!preg_match('/^(\+34)?[6-9][0-9]{8}$/', $cleanPhone)) {
            return 'El teléfono debe ser un número español válido (ej: 612345678 o +34612345678)';
        }
        
        return null;
    }
    
    /**
     * Validar código postal español
     */
    public function validatePostalCode(string $postalCode): ?string
    {
        $cleanPostal = str_replace(' ', '', $postalCode);
        
        if (!preg_match('/^[0-5][0-9]{4}$/', $cleanPostal)) {
            return 'El código postal debe ser un código español válido de 5 dígitos (ej: 28001)';
        }
        
        return null;
    }
    
    /**
     * Validar IBAN español
     */
    public function validateIBAN(string $iban): ?string
    {
        // Limpiar espacios
        $cleanIban = str_replace(' ', '', strtoupper($iban));
        
        // Validar formato español (ES + 2 dígitos + 20 caracteres)
        if (!preg_match('/^ES[0-9]{22}$/', $cleanIban)) {
            return 'El IBAN debe ser un número español válido (ej: ES1234567890123456789012)';
        }
        
        // Validar checksum del IBAN
        $ibanCheck = substr($cleanIban, 4) . substr($cleanIban, 0, 4);
        $ibanCheck = str_replace(
            ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'],
            ['10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35'],
            $ibanCheck
        );
        
        // Validar módulo 97
        if (bcmod($ibanCheck, '97') !== '1') {
            return 'El IBAN no es válido (checksum incorrecto)';
        }
        
        return null;
    }
    
    /**
     * Validar salario
     */
    public function validateSalary($salary): ?string
    {
        if (!is_numeric($salary)) {
            return 'El salario debe ser un número válido';
        }
        
        $salaryFloat = (float)$salary;
        
        if ($salaryFloat < 0) {
            return 'El salario no puede ser negativo';
        }
        
        if ($salaryFloat > 999999.99) {
            return 'El salario no puede exceder 999,999.99';
        }
        
        return null;
    }
    
    /**
     * Validar fecha
     */
    public function validateDate(string $date): ?string
    {
        $dateObj = \DateTime::createFromFormat('Y-m-d', $date);
        
        if (!$dateObj || $dateObj->format('Y-m-d') !== $date) {
            return 'La fecha debe tener el formato YYYY-MM-DD';
        }
        
        // No puede ser fecha futura
        if ($dateObj > new \DateTime()) {
            return 'La fecha no puede ser futura';
        }
        
        // No puede ser anterior a 1950
        if ($dateObj < new \DateTime('1950-01-01')) {
            return 'La fecha no puede ser anterior a 1950';
        }
        
        return null;
    }
    
    /**
     * Sanitizar string
     */
    public function sanitize(string $input): string
    {
        // Eliminar espacios al inicio y final
        $input = trim($input);
        
        // Eliminar caracteres de control
        $input = preg_replace('/[\x00-\x1F\x7F]/u', '', $input);
        
        // Convertir entidades HTML
        $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        return $input;
    }
    
    /**
     * Sanitizar array de datos
     */
    public function sanitizeData(array $data): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = $this->sanitize($value);
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }
}
