<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Tests\Validation;

use PHPUnit\Framework\TestCase;
use ZabalaGailetak\HrPortal\Validation\EmployeeValidator;

class EmployeeValidatorTest extends TestCase
{
    private EmployeeValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new EmployeeValidator();
    }

    /**
     * Test: validateEmail - Email válido
     */
    public function testValidateEmailValid(): void
    {
        $result = $this->validator->validateEmail('test@example.com');
        $this->assertNull($result);
    }

    /**
     * Test: validateEmail - Email vacío
     */
    public function testValidateEmailEmpty(): void
    {
        $result = $this->validator->validateEmail('');
        $this->assertStringContainsString('requerido', $result);
    }

    /**
     * Test: validateEmail - Email inválido
     */
    public function testValidateEmailInvalid(): void
    {
        $result = $this->validator->validateEmail('invalid-email');
        $this->assertStringContainsString('válido', $result);
    }

    /**
     * Test: validatePassword - Contraseña válida
     */
    public function testValidatePasswordValid(): void
    {
        $result = $this->validator->validatePassword('Password123');
        $this->assertNull($result);
    }

    /**
     * Test: validatePassword - Contraseña corta
     */
    public function testValidatePasswordTooShort(): void
    {
        $result = $this->validator->validatePassword('Pass1');
        $this->assertStringContainsString('8 caracteres', $result);
    }

    /**
     * Test: validatePassword - Sin mayúscula
     */
    public function testValidatePasswordNoUppercase(): void
    {
        $result = $this->validator->validatePassword('password123');
        $this->assertStringContainsString('mayúscula', $result);
    }

    /**
     * Test: validatePassword - Sin minúscula
     */
    public function testValidatePasswordNoLowercase(): void
    {
        $result = $this->validator->validatePassword('PASSWORD123');
        $this->assertStringContainsString('minúscula', $result);
    }

    /**
     * Test: validatePassword - Sin número
     */
    public function testValidatePasswordNoNumber(): void
    {
        $result = $this->validator->validatePassword('Password');
        $this->assertStringContainsString('número', $result);
    }

    /**
     * Test: validateName - Nombre válido
     */
    public function testValidateNameValid(): void
    {
        $result = $this->validator->validateName('Juan García', 'nombre');
        $this->assertNull($result);
    }

    /**
     * Test: validateName - Nombre con acentos
     */
    public function testValidateNameWithAccents(): void
    {
        $result = $this->validator->validateName('María José', 'nombre');
        $this->assertNull($result);
    }

    /**
     * Test: validateName - Nombre vacío
     */
    public function testValidateNameEmpty(): void
    {
        $result = $this->validator->validateName('', 'nombre');
        $this->assertStringContainsString('requerido', $result);
    }

    /**
     * Test: validateName - Nombre muy corto
     */
    public function testValidateNameTooShort(): void
    {
        $result = $this->validator->validateName('A', 'nombre');
        $this->assertStringContainsString('2 caracteres', $result);
    }

    /**
     * Test: validateNIF - NIF válido
     */
    public function testValidateNIFValid(): void
    {
        $result = $this->validator->validateNIF('12345678Z');
        $this->assertNull($result);
    }

    /**
     * Test: validateNIF - NIE válido
     */
    public function testValidateNIEValid(): void
    {
        $result = $this->validator->validateNIF('X1234567L');
        $this->assertNull($result);
    }

    /**
     * Test: validateNIF - NIF con espacios
     */
    public function testValidateNIFWithSpaces(): void
    {
        $result = $this->validator->validateNIF('12345678 Z');
        $this->assertNull($result);
    }

    /**
     * Test: validateNIF - NIF vacío
     */
    public function testValidateNIFEmpty(): void
    {
        $result = $this->validator->validateNIF('');
        $this->assertStringContainsString('requerido', $result);
    }

    /**
     * Test: validateNIF - NIF formato inválido
     */
    public function testValidateNIFInvalidFormat(): void
    {
        $result = $this->validator->validateNIF('123ABC');
        $this->assertStringContainsString('formato válido', $result);
    }

    /**
     * Test: validateNIF - NIF letra incorrecta
     */
    public function testValidateNIFWrongLetter(): void
    {
        $result = $this->validator->validateNIF('12345678A');
        $this->assertStringContainsString('letra', $result);
    }

    /**
     * Test: validatePhone - Teléfono móvil válido
     */
    public function testValidatePhoneMobile(): void
    {
        $result = $this->validator->validatePhone('612345678');
        $this->assertNull($result);
    }

    /**
     * Test: validatePhone - Teléfono con prefijo
     */
    public function testValidatePhoneWithPrefix(): void
    {
        $result = $this->validator->validatePhone('+34612345678');
        $this->assertNull($result);
    }

    /**
     * Test: validatePhone - Teléfono con espacios
     */
    public function testValidatePhoneWithSpaces(): void
    {
        $result = $this->validator->validatePhone('612 34 56 78');
        $this->assertNull($result);
    }

    /**
     * Test: validatePhone - Teléfono inválido
     */
    public function testValidatePhoneInvalid(): void
    {
        $result = $this->validator->validatePhone('123456');
        $this->assertStringContainsString('válido', $result);
    }

    /**
     * Test: validatePostalCode - Código postal válido
     */
    public function testValidatePostalCodeValid(): void
    {
        $result = $this->validator->validatePostalCode('28001');
        $this->assertNull($result);
    }

    /**
     * Test: validatePostalCode - Código postal inválido
     */
    public function testValidatePostalCodeInvalid(): void
    {
        $result = $this->validator->validatePostalCode('99999');
        $this->assertStringContainsString('válido', $result);
    }

    /**
     * Test: validateIBAN - IBAN válido
     */
    public function testValidateIBANValid(): void
    {
        $result = $this->validator->validateIBAN('ES9121000418450200051332');
        $this->assertNull($result);
    }

    /**
     * Test: validateIBAN - IBAN con espacios
     */
    public function testValidateIBANWithSpaces(): void
    {
        $result = $this->validator->validateIBAN('ES91 2100 0418 4502 0005 1332');
        $this->assertNull($result);
    }

    /**
     * Test: validateIBAN - IBAN formato inválido
     */
    public function testValidateIBANInvalidFormat(): void
    {
        $result = $this->validator->validateIBAN('ES123');
        $this->assertStringContainsString('válido', $result);
    }

    /**
     * Test: validateIBAN - IBAN checksum inválido
     */
    public function testValidateIBANInvalidChecksum(): void
    {
        $result = $this->validator->validateIBAN('ES0000000000000000000000');
        $this->assertStringContainsString('válido', $result);
    }

    /**
     * Test: validateSalary - Salario válido
     */
    public function testValidateSalaryValid(): void
    {
        $result = $this->validator->validateSalary(30000.50);
        $this->assertNull($result);
    }

    /**
     * Test: validateSalary - Salario negativo
     */
    public function testValidateSalaryNegative(): void
    {
        $result = $this->validator->validateSalary(-100);
        $this->assertStringContainsString('negativo', $result);
    }

    /**
     * Test: validateSalary - Salario no numérico
     */
    public function testValidateSalaryNotNumeric(): void
    {
        $result = $this->validator->validateSalary('abc');
        $this->assertStringContainsString('número', $result);
    }

    /**
     * Test: validateDate - Fecha válida
     */
    public function testValidateDateValid(): void
    {
        $result = $this->validator->validateDate('2020-01-15');
        $this->assertNull($result);
    }

    /**
     * Test: validateDate - Fecha formato inválido
     */
    public function testValidateDateInvalidFormat(): void
    {
        $result = $this->validator->validateDate('15/01/2020');
        $this->assertStringContainsString('formato', $result);
    }

    /**
     * Test: validateDate - Fecha futura
     */
    public function testValidateDateFuture(): void
    {
        $result = $this->validator->validateDate('2030-01-01');
        $this->assertStringContainsString('futura', $result);
    }

    /**
     * Test: validate - Datos completos válidos (creación)
     */
    public function testValidateCompleteDataCreate(): void
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'Password123',
            'first_name' => 'Juan',
            'last_name' => 'García',
            'nif' => '12345678Z',
            'position' => 'Developer',
            'phone' => '612345678',
            'postal_code' => '28001',
            'bank_account' => 'ES9121000418450200051332',
            'salary' => 30000,
            'hire_date' => '2020-01-15'
        ];

        $errors = $this->validator->validate($data, false);
        $this->assertEmpty($errors);
    }

    /**
     * Test: validate - Datos faltantes (creación)
     */
    public function testValidateMissingFieldsCreate(): void
    {
        $data = [
            'email' => 'test@example.com'
            // Faltan campos requeridos
        ];

        $errors = $this->validator->validate($data, false);
        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('password', $errors);
        $this->assertArrayHasKey('first_name', $errors);
        $this->assertArrayHasKey('last_name', $errors);
        $this->assertArrayHasKey('nif', $errors);
        $this->assertArrayHasKey('position', $errors);
    }

    /**
     * Test: validate - Actualización parcial válida
     */
    public function testValidatePartialUpdate(): void
    {
        $data = [
            'phone' => '612345678'
        ];

        $errors = $this->validator->validate($data, true);
        $this->assertEmpty($errors);
    }

    /**
     * Test: sanitize - Elimina espacios
     */
    public function testSanitizeTrimsSpaces(): void
    {
        $result = $this->validator->sanitize('  test  ');
        $this->assertEquals('test', $result);
    }

    /**
     * Test: sanitize - Convierte HTML entities
     */
    public function testSanitizeHtmlEntities(): void
    {
        $result = $this->validator->sanitize('<script>alert("xss")</script>');
        $this->assertStringNotContainsString('<script>', $result);
    }

    /**
     * Test: sanitizeData - Sanitiza array
     */
    public function testSanitizeData(): void
    {
        $data = [
            'name' => '  John  ',
            'email' => 'test@example.com  ',
            'age' => 25
        ];

        $result = $this->validator->sanitizeData($data);

        $this->assertEquals('John', $result['name']);
        $this->assertEquals('test@example.com', $result['email']);
        $this->assertEquals(25, $result['age']);
    }
}
