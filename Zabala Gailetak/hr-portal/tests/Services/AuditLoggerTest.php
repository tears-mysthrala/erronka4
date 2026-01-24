<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Tests\Services;

use PHPUnit\Framework\TestCase;
use ZabalaGailetak\HrPortal\Services\AuditLogger;
use ZabalaGailetak\HrPortal\Database\Database;
use PDO;
use PDOStatement;

class AuditLoggerTest extends TestCase
{
    private AuditLogger $logger;
    /** @var Database&\PHPUnit\Framework\MockObject\MockObject */
    private Database $mockDb;
    /** @var PDOStatement&\PHPUnit\Framework\MockObject\MockObject */
    private PDOStatement $mockStmt;

    protected function setUp(): void
    {
        $this->mockDb = $this->createMock(Database::class);
        $this->mockStmt = $this->createMock(PDOStatement::class);
        $this->logger = new AuditLogger($this->mockDb);
    }

    public function testLogCreate(): void
    {
        $newValues = [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'position' => 'developer'
        ];

        $this->mockDb->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with($this->callback(function ($params) use ($newValues) {
                return $params['entity_type'] === 'employee' &&
                       $params['entity_id'] === '123' &&
                       $params['action'] === 'create' &&
                       $params['old_values'] === null &&
                       $params['new_values'] === json_encode($newValues) &&
                       $params['changed_fields'] === '{name,email,position}';
            }))
            ->willReturn(true);

        $result = $this->logger->logCreate(
            entityType: 'employee',
            entityId: '123',
            newValues: $newValues,
            userId: 'user-1',
            userEmail: 'admin@example.com',
            userRole: 'admin',
            ipAddress: '192.168.1.1',
            userAgent: 'Mozilla/5.0',
            requestId: 'req-123'
        );

        $this->assertTrue($result);
    }

    public function testLogUpdate(): void
    {
        $oldValues = [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'position' => 'developer',
            'salary' => 30000
        ];

        $newValues = [
            'name' => 'Juan Pérez',  // Sin cambio
            'email' => 'juan.perez@example.com',  // Cambió
            'position' => 'senior developer',  // Cambió
            'salary' => 30000  // Sin cambio
        ];

        $this->mockDb->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with($this->callback(function ($params) {
                $oldVals = json_decode($params['old_values'], true);
                $newVals = json_decode($params['new_values'], true);

                // Verificar que solo incluye campos que cambiaron
                return $params['action'] === 'update' &&
                       count($oldVals) === 2 &&
                       count($newVals) === 2 &&
                       isset($oldVals['email']) &&
                       isset($oldVals['position']) &&
                       $params['changed_fields'] === '{email,position}';
            }))
            ->willReturn(true);

        $result = $this->logger->logUpdate(
            entityType: 'employee',
            entityId: '123',
            oldValues: $oldValues,
            newValues: $newValues,
            userId: 'user-1',
            userEmail: 'admin@example.com',
            userRole: 'admin'
        );

        $this->assertTrue($result);
    }

    public function testLogUpdateWithNoChanges(): void
    {
        $oldValues = ['name' => 'Juan Pérez', 'email' => 'juan@example.com'];
        $newValues = ['name' => 'Juan Pérez', 'email' => 'juan@example.com'];

        // No debe llamar a la base de datos si no hay cambios
        $this->mockDb->expects($this->never())
            ->method('prepare');

        $result = $this->logger->logUpdate(
            entityType: 'employee',
            entityId: '123',
            oldValues: $oldValues,
            newValues: $newValues,
            userId: 'user-1',
            userEmail: 'admin@example.com',
            userRole: 'admin'
        );

        $this->assertTrue($result);
    }

    public function testLogDelete(): void
    {
        $oldValues = [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'active' => true
        ];

        $this->mockDb->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with($this->callback(function ($params) use ($oldValues) {
                return $params['action'] === 'delete' &&
                       $params['old_values'] === json_encode($oldValues) &&
                       $params['new_values'] === null &&
                       $params['changed_fields'] === '{active}';
            }))
            ->willReturn(true);

        $result = $this->logger->logDelete(
            entityType: 'employee',
            entityId: '123',
            oldValues: $oldValues,
            userId: 'user-1',
            userEmail: 'admin@example.com',
            userRole: 'admin'
        );

        $this->assertTrue($result);
    }

    public function testLogRestore(): void
    {
        $newValues = [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'active' => true
        ];

        $this->mockDb->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with($this->callback(function ($params) {
                $oldVals = json_decode($params['old_values'], true);
                return $params['action'] === 'restore' &&
                       $oldVals['active'] === false &&
                       $params['changed_fields'] === '{active}';
            }))
            ->willReturn(true);

        $result = $this->logger->logRestore(
            entityType: 'employee',
            entityId: '123',
            newValues: $newValues,
            userId: 'user-1',
            userEmail: 'admin@example.com',
            userRole: 'admin'
        );

        $this->assertTrue($result);
    }

    public function testGetEntityHistory(): void
    {
        $logs = [
            [
                'id' => 'log-1',
                'user_email' => 'admin@example.com',
                'user_role' => 'admin',
                'action' => 'update',
                'old_values' => '{"email":"old@example.com"}',
                'new_values' => '{"email":"new@example.com"}',
                'changed_fields' => '{email}',
                'ip_address' => '192.168.1.1',
                'created_at' => '2024-01-15 10:00:00'
            ],
            [
                'id' => 'log-2',
                'user_email' => 'admin@example.com',
                'user_role' => 'admin',
                'action' => 'create',
                'old_values' => null,
                'new_values' => '{"name":"Juan Pérez"}',
                'changed_fields' => '{name,email}',
                'ip_address' => '192.168.1.1',
                'created_at' => '2024-01-14 09:00:00'
            ]
        ];

        $this->mockDb->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with([
                'entity_type' => 'employee',
                'entity_id' => '123',
                'limit' => 50
            ]);

        $this->mockStmt->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($logs);

        $result = $this->logger->getEntityHistory('employee', '123');

        $this->assertCount(2, $result);
        $this->assertEquals(['email' => 'old@example.com'], $result[0]['old_values']);
        $this->assertEquals(['email' => 'new@example.com'], $result[0]['new_values']);
        $this->assertEquals(['email'], $result[0]['changed_fields']);
    }

    public function testGetUserActivity(): void
    {
        $logs = [
            [
                'id' => 'log-1',
                'entity_type' => 'employee',
                'entity_id' => '123',
                'action' => 'update',
                'new_values' => '{"email":"new@example.com"}',
                'changed_fields' => '{email}',
                'created_at' => '2024-01-15 10:00:00'
            ]
        ];

        $this->mockDb->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with([
                'user_id' => 'user-1',
                'limit' => 50
            ]);

        $this->mockStmt->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($logs);

        $result = $this->logger->getUserActivity('user-1');

        $this->assertCount(1, $result);
        $this->assertEquals('employee', $result[0]['entity_type']);
    }

    public function testGetUserActivityWithEntityTypeFilter(): void
    {
        $this->mockDb->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with([
                'user_id' => 'user-1',
                'entity_type' => 'employee',
                'limit' => 25
            ]);

        $this->mockStmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([]);

        $this->logger->getUserActivity('user-1', 'employee', 25);
    }

    public function testGenerateRequestId(): void
    {
        $id1 = AuditLogger::generateRequestId();
        $id2 = AuditLogger::generateRequestId();

        $this->assertIsString($id1);
        $this->assertEquals(32, strlen($id1));  // 16 bytes = 32 hex chars
        $this->assertNotEquals($id1, $id2);
    }

    public function testGetClientIp(): void
    {
        // Simular diferentes headers
        $_SERVER['REMOTE_ADDR'] = '192.168.1.1';
        $ip = AuditLogger::getClientIp();
        $this->assertEquals('192.168.1.1', $ip);

        // Cloudflare
        $_SERVER['HTTP_CF_CONNECTING_IP'] = '10.0.0.1';
        $ip = AuditLogger::getClientIp();
        $this->assertEquals('10.0.0.1', $ip);

        // Lista de IPs
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '203.0.113.1, 198.51.100.1';
        unset($_SERVER['HTTP_CF_CONNECTING_IP']);
        $ip = AuditLogger::getClientIp();
        $this->assertEquals('203.0.113.1', $ip);
    }

    public function testGetUserAgent(): void
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 Test';
        $ua = AuditLogger::getUserAgent();
        $this->assertEquals('Mozilla/5.0 Test', $ua);

        unset($_SERVER['HTTP_USER_AGENT']);
        $ua = AuditLogger::getUserAgent();
        $this->assertNull($ua);
    }
}
