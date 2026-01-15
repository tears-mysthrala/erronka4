<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Auth\MFA;

use OTPHP\TOTP;
use Exception;

/**
 * TOTP Service para autenticación de dos factores
 * 
 * Implementa Time-based One-Time Password (RFC 6238)
 * para verificación MFA
 */
class TOTPService
{
    private string $issuer;
    private int $period = 30; // 30 segundos
    private int $digits = 6;
    private string $digest = 'sha1';
    
    public function __construct(array $config = [])
    {
        $this->issuer = $config['totp_issuer'] ?? 'Zabala Gailetak HR Portal';
        $this->period = $config['totp_period'] ?? 30;
        $this->digits = $config['totp_digits'] ?? 6;
    }
    
    /**
     * Genera un secreto TOTP para un nuevo usuario
     */
    public function generateSecret(): string
    {
        $totp = TOTP::generate();
        return $totp->getSecret();
    }
    
    /**
     * Genera la URI de configuración para QR code
     */
    public function getQrCodeUri(string $secret, string $userEmail): string
    {
        $totp = TOTP::createFromSecret($secret);
        $totp->setLabel($userEmail);
        $totp->setIssuer($this->issuer);
        $totp->setPeriod($this->period);
        $totp->setDigits($this->digits);
        
        return $totp->getProvisioningUri();
    }
    
    /**
     * Genera el código QR como imagen base64
     */
    public function generateQrCodeImage(string $secret, string $userEmail): string
    {
        $uri = $this->getQrCodeUri($secret, $userEmail);
        
        // Usar Google Charts API para generar QR
        $qrUrl = sprintf(
            'https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=%s',
            urlencode($uri)
        );
        
        // Obtener imagen y convertir a base64
        $imageData = @file_get_contents($qrUrl);
        
        if ($imageData === false) {
            // Si falla, devolver URL del QR en lugar de data URI
            return $qrUrl;
        }
        
        return 'data:image/png;base64,' . base64_encode($imageData);
    }
    
    /**
     * Verifica un código TOTP
     */
    public function verifyCode(string $secret, string $code): bool
    {
        try {
            $totp = TOTP::createFromSecret($secret);
            $totp->setPeriod($this->period);
            $totp->setDigits($this->digits);
            
            // Verificar código con ventana de tiempo de ±1 periodo
            return $totp->verify($code, null, 1);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Genera el código actual para un secreto (útil para testing)
     */
    public function getCurrentCode(string $secret): string
    {
        $totp = TOTP::createFromSecret($secret);
        $totp->setPeriod($this->period);
        $totp->setDigits($this->digits);
        
        return $totp->now();
    }
    
    /**
     * Verifica si un código ya fue usado recientemente (anti-replay)
     * @param \Redis $redis Redis instance
     */
    public function isCodeRecentlyUsed(string $userId, string $code, object $redis): bool
    {
        $key = "mfa_used:{$userId}:{$code}";
        $exists = $redis->exists($key);
        
        if ($exists) {
            return true;
        }
        
        // Marcar como usado durante el periodo del código
        $redis->setex($key, $this->period * 2, '1');
        
        return false;
    }
    
    /**
     * Genera códigos de respaldo para recuperación
     */
    public function generateBackupCodes(int $count = 10): array
    {
        $codes = [];
        
        for ($i = 0; $i < $count; $i++) {
            // Generar código de 8 caracteres alfanuméricos
            $code = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
            
            // Formato: XXXX-XXXX para mejor legibilidad
            $formatted = substr($code, 0, 4) . '-' . substr($code, 4, 4);
            $codes[] = $formatted;
        }
        
        return $codes;
    }
    
    /**
     * Hashea códigos de respaldo para almacenamiento seguro
     */
    public function hashBackupCodes(array $codes): array
    {
        return array_map(function($code) {
            // Remover guiones antes de hashear
            $cleanCode = str_replace('-', '', $code);
            return password_hash($cleanCode, PASSWORD_BCRYPT);
        }, $codes);
    }
    
    /**
     * Verifica un código de respaldo
     */
    public function verifyBackupCode(string $code, array $hashedCodes): ?int
    {
        // Remover guiones del código ingresado
        $cleanCode = str_replace('-', '', strtoupper($code));
        
        foreach ($hashedCodes as $index => $hashedCode) {
            if (password_verify($cleanCode, $hashedCode)) {
                return $index;
            }
        }
        
        return null;
    }
    
    /**
     * Valida el formato de un código TOTP
     */
    public function isValidCodeFormat(string $code): bool
    {
        // Debe ser numérico y tener la longitud correcta
        return ctype_digit($code) && strlen($code) === $this->digits;
    }
    
    /**
     * Obtiene el tiempo restante hasta el próximo código
     */
    public function getTimeRemaining(): int
    {
        return $this->period - (time() % $this->period);
    }
    
    /**
     * Genera información para mostrar al usuario sobre configuración MFA
     */
    public function getMfaSetupInfo(string $secret, string $userEmail): array
    {
        return [
            'secret' => $secret,
            'secret_formatted' => $this->formatSecretForDisplay($secret),
            'qr_code_uri' => $this->getQrCodeUri($secret, $userEmail),
            'qr_code_image' => $this->generateQrCodeImage($secret, $userEmail),
            'issuer' => $this->issuer,
            'account' => $userEmail,
            'period' => $this->period,
            'digits' => $this->digits,
            'algorithm' => strtoupper($this->digest)
        ];
    }
    
    /**
     * Formatea el secreto para visualización (grupos de 4 caracteres)
     */
    private function formatSecretForDisplay(string $secret): string
    {
        return implode(' ', str_split($secret, 4));
    }
}
