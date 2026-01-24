<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Tests\Controllers;

use PHPUnit\Framework\TestCase;
use ZabalaGailetak\HrPortal\Controllers\EmployeeController;
use ZabalaGailetak\HrPortal\Auth\AccessControl;
use ZabalaGailetak\HrPortal\Database\Database;
use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Services\AuditLogger;
use ZabalaGailetak\HrPortal\Validation\EmployeeValidator;
use PDO;
use PDOStatement;

class EmployeeControllerTest extends TestCase
{
    private EmployeeController $controller;
    /** @var Database&\PHPUnit\Framework\MockObject\MockObject */
    private Database $mockDb;
    /** @var AccessControl&\PHPUnit\Framework\MockObject\MockObject */
    private AccessControl $mockAccessControl;
    /** @var EmployeeValidator&\PHPUnit\Framework\MockObject\MockObject */
    private EmployeeValidator $mockValidator;
    /** @var AuditLogger&\PHPUnit\Framework\MockObject\MockObject */
    private AuditLogger $mockAuditLogger;

    protected function setUp(): void
    {
        // Mock Database
        $this->mockDb = $this->createMock(Database::class);

        // Mock AccessControl
        $this->mockAccessControl = $this->createMock(AccessControl::class);

        // Mock EmployeeValidator
        $this->mockValidator = $this->createMock(EmployeeValidator::class);

        // Mock AuditLogger
        $this->mockAuditLogger = $this->createMock(AuditLogger::class);

        // Create controller with mocks
        $this->controller = new EmployeeController(
            $this->mockDb,
            $this->mockAccessControl,
            $this->mockValidator,
            $this->mockAuditLogger
        );
    }

    /**
     * Helper para crear un Request mock con atributos de autenticación
     */
    private function createAuthenticatedRequest(
        string $role = 'admin',
        string $userId = 'test-user-id',
        array $query = [],
        array $body = []
    ): Request {
        $request = $this->createMock(Request::class);

        $request->method('getAttribute')
            ->willReturnCallback(function ($key) use ($role, $userId) {
                return match ($key) {
                    'user_role' => $role,
                    'user_id' => $userId,
                    default => null
                };
            });

        $request->method('getQuery')
            ->willReturnCallback(function ($key) use ($query) {
                return $query[$key] ?? null;
            });

        $request->method('getParsedBody')
            ->willReturn($body);

        return $request;
    }

    /**
     * Test: index() - Admin puede listar todos los empleados
     */
    public function testIndexAsAdmin(): void
    {
        $request = $this->createAuthenticatedRequest('admin', 'admin-id', [
            'page' => '1',
            'limit' => '20'
        ]);

        // Mock permission check
        $this->mockAccessControl->method('hasPermission')
            ->with('admin', 'employees.view')
            ->willReturn(true);

        // Mock database calls
        $mockStmt = $this->createMock(PDOStatement::class);

        // Count query
        $mockStmt->method('fetchColumn')
            ->willReturnOnConsecutiveCalls(5); // Total count

        // Data query
        $mockStmt->method('fetchAll')
            ->willReturn([
                [
                    'id' => 'emp-1',
                    'employee_number' => 'EMP20240001',
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john@test.com',
                    'active' => true
                ]
            ]);

        $this->mockDb->method('prepare')
            ->willReturn($mockStmt);

        $mockStmt->method('execute')
            ->willReturn(true);

        $response = $this->controller->index($request);

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('pagination', $data);
        $this->assertEquals(5, $data['pagination']['total']);
    }

    /**
     * Test: index() - Sin autenticación devuelve 401
     */
    public function testIndexWithoutAuth(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getAttribute')->willReturn(null);

        $response = $this->controller->index($request);

        $this->assertEquals(401, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('No autenticado', $data['error']);
    }

    /**
     * Test: index() - Sin permisos devuelve 403
     */
    public function testIndexWithoutPermission(): void
    {
        $request = $this->createAuthenticatedRequest('employee', 'emp-id');

        $this->mockAccessControl->method('hasPermission')
            ->with('employee', 'employees.view')
            ->willReturn(false);

        $response = $this->controller->index($request);

        $this->assertEquals(403, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Sin permisos', $data['error']);
    }

    /**
     * Test: show() - Obtener detalle de empleado
     */
    public function testShowEmployee(): void
    {
        $request = $this->createAuthenticatedRequest('admin', 'admin-id');

        $this->mockAccessControl->method('hasPermission')
            ->willReturn(true);

        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->method('fetch')
            ->willReturn([
                'id' => 'emp-1',
                'employee_number' => 'EMP20240001',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@test.com',
                'user_id' => 'user-1',
                'department_id' => 'dept-1'
            ]);

        $this->mockDb->method('prepare')->willReturn($mockStmt);
        $mockStmt->method('execute')->willReturn(true);

        $response = $this->controller->show($request, 'emp-1');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('employee', $data);
        $this->assertEquals('EMP20240001', $data['employee']['employee_number']);
    }

    /**
     * Test: show() - Empleado no encontrado devuelve 404
     */
    public function testShowEmployeeNotFound(): void
    {
        $request = $this->createAuthenticatedRequest('admin', 'admin-id');

        $this->mockAccessControl->method('hasPermission')
            ->willReturn(true);

        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->method('fetch')->willReturn(false);

        $this->mockDb->method('prepare')->willReturn($mockStmt);
        $mockStmt->method('execute')->willReturn(true);

        $response = $this->controller->show($request, 'non-existent-id');

        $this->assertEquals(404, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Empleado no encontrado', $data['error']);
    }

    /**
     * Test: create() - Crear nuevo empleado
     */
    public function testCreateEmployee(): void
    {
        $employeeData = [
            'email' => 'newuser@test.com',
            'password' => 'SecurePass123!',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'nif' => '12345678X',
            'position' => 'Developer'
        ];

        $request = $this->createAuthenticatedRequest(
            'admin',
            'admin-id',
            [],
            $employeeData
        );

        $this->mockAccessControl->method('hasPermission')
            ->with('admin', 'employees.create')
            ->willReturn(true);

        // Mock validator - sin errores
        $this->mockValidator->method('sanitizeData')
            ->willReturnArgument(0);
        $this->mockValidator->method('validate')
            ->willReturn([]);

        // Mock transaction methods (void return type)
        $this->mockDb->expects($this->once())->method('beginTransaction');
        $this->mockDb->expects($this->once())->method('commit');

        // Mock statement
        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->method('execute')->willReturn(true);
        $mockStmt->method('fetchColumn')
            ->willReturnOnConsecutiveCalls(
                false,          // generateEmployeeNumber: no hay empleados previos
                'new-user-id',  // User insert RETURNING id
                'new-emp-id'    // Employee insert RETURNING id
            );

        $this->mockDb->method('prepare')->willReturn($mockStmt);

        $response = $this->controller->create($request);

        $this->assertEquals(201, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('employee_id', $data);
        $this->assertArrayHasKey('user_id', $data);
    }

    /**
     * Test: create() - Fallo si faltan campos requeridos
     */
    public function testCreateEmployeeWithMissingFields(): void
    {
        $incompleteData = [
            'email' => 'test@test.com',
            'first_name' => 'John'
            // Faltan: password, last_name, nif, position
        ];

        $request = $this->createAuthenticatedRequest(
            'admin',
            'admin-id',
            [],
            $incompleteData
        );

        $this->mockAccessControl->method('hasPermission')
            ->willReturn(true);

        // Mock validator - con errores
        $this->mockValidator->method('sanitizeData')
            ->willReturnArgument(0);
        $this->mockValidator->method('validate')
            ->willReturn([
                'password' => 'La contraseña es requerida',
                'last_name' => 'El last_name es requerido',
                'nif' => 'El NIF/NIE es requerido',
                'position' => 'El cargo es requerido'
            ]);

        $response = $this->controller->create($request);

        $this->assertEquals(400, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('error', $data);
        $this->assertArrayHasKey('validation_errors', $data);
        $this->assertStringContainsString('validación', $data['error']);
    }

    /**
     * Test: update() - Actualizar empleado
     */
    public function testUpdateEmployee(): void
    {
        $updateData = [
            'first_name' => 'John Updated',
            'position' => 'Senior Developer'
        ];

        $request = $this->createAuthenticatedRequest(
            'admin',
            'admin-id',
            [],
            $updateData
        );

        $this->mockAccessControl->method('hasPermission')
            ->with('admin', 'employees.edit')
            ->willReturn(true);

        // Mock validator
        $this->mockValidator->method('sanitizeData')
            ->willReturnArgument(0);
        $this->mockValidator->method('validate')
            ->willReturn([]);

        // Mock para SELECT (obtener employee con todos los campos)
        $mockSelectStmt = $this->createMock(PDOStatement::class);
        $mockSelectStmt->method('execute')->willReturn(true);
        $mockSelectStmt->method('fetch')->willReturn([
            'id' => 'emp-1',
            'user_id' => 'user-1',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'nif' => '12345678Z',
            'position' => 'Developer',
            'employee_number' => 'EMP001',
            'phone' => '612345678',
            'address' => 'Calle Test',
            'city' => 'Madrid',
            'postal_code' => '28001',
            'country' => 'España',
            'hire_date' => '2023-01-01',
            'department_id' => null,
            'salary' => 30000,
            'bank_account' => 'ES9121000418450200051332',
            'email' => 'john@example.com',
            'role' => 'employee'
        ]);

        // Mock para UPDATE
        $mockUpdateStmt = $this->createMock(PDOStatement::class);
        $mockUpdateStmt->method('execute')->willReturn(true);

        // Devolver diferentes statements según la query
        $this->mockDb->method('prepare')
            ->willReturnCallback(function ($sql) use ($mockSelectStmt, $mockUpdateStmt) {
                if (str_contains($sql, 'SELECT')) {
                    return $mockSelectStmt;
                }
                return $mockUpdateStmt;
            });

        // Mock AuditLogger
        $this->mockAuditLogger->expects($this->once())
            ->method('logUpdate');

        $response = $this->controller->update($request, 'emp-1');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals('emp-1', $data['employee_id']);
    }

    /**
     * Test: delete() - Soft delete de empleado
     */
    public function testDeleteEmployee(): void
    {
        $request = $this->createAuthenticatedRequest('admin', 'admin-id');

        $this->mockAccessControl->method('hasPermission')
            ->with('admin', 'employees.delete')
            ->willReturn(true);

        // Mock para SELECT (obtener employee)
        $mockSelectStmt = $this->createMock(PDOStatement::class);
        $mockSelectStmt->method('execute')->willReturn(true);
        $mockSelectStmt->method('fetch')->willReturn([
            'id' => 'emp-1',
            'employee_number' => 'EMP001',
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan@example.com',
            'position' => 'Developer'
        ]);

        // Mock para UPDATE
        $mockUpdateStmt = $this->createMock(PDOStatement::class);
        $mockUpdateStmt->method('execute')->willReturn(true);

        // Devolver diferentes statements según la query
        $this->mockDb->method('prepare')
            ->willReturnCallback(function ($sql) use ($mockSelectStmt, $mockUpdateStmt) {
                if (str_contains($sql, 'SELECT')) {
                    return $mockSelectStmt;
                }
                return $mockUpdateStmt;
            });

        // Mock AuditLogger
        $this->mockAuditLogger->expects($this->once())
            ->method('logDelete');

        $response = $this->controller->delete($request, 'emp-1');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('message', $data);
        $this->assertStringContainsString('baja', $data['message']);
    }

    /**
     * Test: restore() - Reactivar empleado
     */
    public function testRestoreEmployee(): void
    {
        $request = $this->createAuthenticatedRequest('admin', 'admin-id');

        // Mock para SELECT (obtener employee)
        $mockSelectStmt = $this->createMock(PDOStatement::class);
        $mockSelectStmt->method('execute')->willReturn(true);
        $mockSelectStmt->method('fetch')->willReturn([
            'id' => 'emp-1',
            'employee_number' => 'EMP001',
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan@example.com',
            'position' => 'Developer'
        ]);

        // Mock para UPDATE
        $mockUpdateStmt = $this->createMock(PDOStatement::class);
        $mockUpdateStmt->method('execute')->willReturn(true);

        // Devolver diferentes statements según la query
        $this->mockDb->method('prepare')
            ->willReturnCallback(function ($sql) use ($mockSelectStmt, $mockUpdateStmt) {
                if (str_contains($sql, 'SELECT')) {
                    return $mockSelectStmt;
                }
                return $mockUpdateStmt;
            });

        // Mock AuditLogger
        $this->mockAuditLogger->expects($this->once())
            ->method('logRestore');

        $response = $this->controller->restore($request, 'emp-1');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('message', $data);
        $this->assertStringContainsString('reactivado', $data['message']);
    }

    /**
     * Test: restore() - Solo admin puede reactivar
     */
    public function testRestoreEmployeeOnlyAdmin(): void
    {
        $request = $this->createAuthenticatedRequest('hr_manager', 'hr-id');

        $response = $this->controller->restore($request, 'emp-1');

        $this->assertEquals(403, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Sin permisos', $data['error']);
    }
}
