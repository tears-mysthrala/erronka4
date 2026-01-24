<?php

declare(strict_types=1);

namespace Tests\Auth;

use PHPUnit\Framework\TestCase;
use ZabalaGailetak\HrPortal\Auth\TokenManager;

class TokenManagerTest extends TestCase
{
    private TokenManager $tokenManager;

    protected function setUp(): void
    {
        $config = [
            'jwt_secret' => 'test_secret_key_for_testing_only_12345',
            'jwt_issuer' => 'hr-portal-test',
            'jwt_access_expiry' => 3600,
            'jwt_refresh_expiry' => 604800
        ];

        $this->tokenManager = new TokenManager($config);
    }

    public function testGenerateAccessToken(): void
    {
        $userData = [
            'id' => '123e4567-e89b-12d3-a456-426614174000',
            'email' => 'test@zabalagailetak.com',
            'role' => 'employee',
            'mfa_verified' => false
        ];

        $token = $this->tokenManager->generateAccessToken($userData);

        $this->assertIsString($token);
        $this->assertNotEmpty($token);
    }

    public function testValidateToken(): void
    {
        $userData = [
            'id' => '123e4567-e89b-12d3-a456-426614174000',
            'email' => 'test@zabalagailetak.com',
            'role' => 'employee',
            'mfa_verified' => false
        ];

        $token = $this->tokenManager->generateAccessToken($userData);
        $decoded = $this->tokenManager->validateToken($token);

        $this->assertIsObject($decoded);
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $decoded->sub);
        $this->assertEquals('access', $decoded->type);
    }

    public function testGenerateRefreshToken(): void
    {
        $userId = '123e4567-e89b-12d3-a456-426614174000';

        $token = $this->tokenManager->generateRefreshToken($userId);

        $this->assertIsString($token);
        $this->assertNotEmpty($token);

        $decoded = $this->tokenManager->validateToken($token);
        $this->assertEquals('refresh', $decoded->type);
    }

    public function testExtractTokenFromHeader(): void
    {
        $header = 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...';

        $token = $this->tokenManager->extractTokenFromHeader($header);

        $this->assertIsString($token);
        $this->assertEquals('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...', $token);
    }

    public function testExtractTokenFromHeaderWithoutBearer(): void
    {
        $header = 'InvalidFormat token123';

        $token = $this->tokenManager->extractTokenFromHeader($header);

        $this->assertNull($token);
    }

    public function testIsAccessToken(): void
    {
        $userData = [
            'id' => '123e4567-e89b-12d3-a456-426614174000',
            'email' => 'test@zabalagailetak.com',
            'role' => 'employee'
        ];

        $accessToken = $this->tokenManager->generateAccessToken($userData);
        $decoded = $this->tokenManager->validateToken($accessToken);

        $this->assertTrue($this->tokenManager->isAccessToken($decoded));
        $this->assertFalse($this->tokenManager->isRefreshToken($decoded));
    }

    public function testIsRefreshToken(): void
    {
        $userId = '123e4567-e89b-12d3-a456-426614174000';

        $refreshToken = $this->tokenManager->generateRefreshToken($userId);
        $decoded = $this->tokenManager->validateToken($refreshToken);

        $this->assertTrue($this->tokenManager->isRefreshToken($decoded));
        $this->assertFalse($this->tokenManager->isAccessToken($decoded));
    }

    public function testGetUserData(): void
    {
        $userData = [
            'id' => '123e4567-e89b-12d3-a456-426614174000',
            'email' => 'test@zabalagailetak.com',
            'role' => 'hr_manager',
            'mfa_verified' => true
        ];

        $token = $this->tokenManager->generateAccessToken($userData);
        $decoded = $this->tokenManager->validateToken($token);
        $data = $this->tokenManager->getUserData($decoded);

        $this->assertIsObject($data);
        $this->assertEquals('test@zabalagailetak.com', $data->email);
        $this->assertEquals('hr_manager', $data->role);
        $this->assertTrue($data->mfa_verified);
    }

    public function testIsMfaVerified(): void
    {
        $userData = [
            'id' => '123e4567-e89b-12d3-a456-426614174000',
            'email' => 'test@zabalagailetak.com',
            'role' => 'employee',
            'mfa_verified' => true
        ];

        $token = $this->tokenManager->generateAccessToken($userData);
        $decoded = $this->tokenManager->validateToken($token);

        $this->assertTrue($this->tokenManager->isMfaVerified($decoded));
    }

    public function testGenerateMfaToken(): void
    {
        $userId = '123e4567-e89b-12d3-a456-426614174000';

        $mfaToken = $this->tokenManager->generateMfaToken($userId);
        $decoded = $this->tokenManager->validateToken($mfaToken);

        $this->assertEquals('mfa_pending', $decoded->type);
        $this->assertEquals($userId, $decoded->sub);
    }

    public function testInvalidTokenThrowsException(): void
    {
        $this->expectException(\Exception::class);

        $this->tokenManager->validateToken('invalid_token_string');
    }
}
