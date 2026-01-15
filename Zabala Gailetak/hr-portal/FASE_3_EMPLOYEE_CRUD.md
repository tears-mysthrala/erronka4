# Fase 3: Employee CRUD - Completado

**Fecha**: 2026-01-15  
**Estado**: ✅ **Implementación con Validación Completada**

---

## Resumen

Se ha implementado exitosamente el sistema completo de gestión CRUD (Create, Read, Update, Delete) de empleados con:
- ✅ Controles de acceso basados en roles (RBAC)
- ✅ Validación avanzada de datos españoles (NIF/NIE, IBAN, teléfono, código postal)
- ✅ Sanitización de entrada
- ✅ 62 tests unitarios pasando

---

## Archivos Creados/Modificados

### 1. Controller Principal
**`src/Controllers/EmployeeController.php`** (510 líneas)

Métodos implementados:
- ✅ `index(Request): Response` - Lista empleados con paginación y filtros
- ✅ `show(Request, string $id): Response` - Detalle de un empleado
- ✅ `create(Request): Response` - Crear nuevo empleado (con transacción)
- ✅ `update(Request, string $id): Response` - Actualizar empleado
- ✅ `delete(Request, string $id): Response` - Baja lógica (soft delete)
- ✅ `restore(Request, string $id): Response` - Reactivar empleado
- ✅ `generateEmployeeNumber(): string` - Generar número de empleado único

**Características**:
- Autenticación mediante JWT (TokenManager)
- Autorización por roles (AccessControl)
- Transacciones de base de datos
- Soft delete en lugar de eliminación física
- Paginación con límite personalizable
- Búsqueda por nombre, email, employee_number
- Filtrado por department_id y estado activo
- Generación automática de employee_number (formato: EMP{YEAR}{####})

### 2. Rutas API
**`config/routes.php`** - Agregadas 6 nuevas rutas:
```
GET    /api/employees          -> EmployeeController::index
GET    /api/employees/{id}     -> EmployeeController::show
POST   /api/employees          -> EmployeeController::create
PUT    /api/employees/{id}     -> EmployeeController::update
DELETE /api/employees/{id}     -> EmployeeController::delete
POST   /api/employees/{id}/restore -> EmployeeController::restore
```

### 3. Validador de Datos
**`src/Validation/EmployeeValidator.php`** (431 líneas)

**Validaciones implementadas**:
- ✅ Email (formato RFC5322)
- ✅ Contraseña (mínimo 8 caracteres, mayúscula, minúscula, número)
- ✅ Nombres (2-100 caracteres, solo letras y espacios con acentos)
- ✅ NIF/NIE español (formato y letra correcta)
- ✅ Teléfono español (móvil/fijo con/sin +34)
- ✅ Código postal español (5 dígitos, 00000-52999)
- ✅ IBAN español (24 caracteres con checksum válido)
- ✅ Salario (numérico positivo)
- ✅ Fecha (formato YYYY-MM-DD, no futura, >= 1950)

**Métodos de sanitización**:
- `sanitize()` - Elimina espacios, caracteres de control, convierte HTML entities
- `sanitizeData()` - Sanitiza arrays completos

### 4. Tests Unitarios
**`tests/Controllers/EmployeeControllerTest.php`** (371 líneas) - 11 tests  
**`tests/Validation/EmployeeValidatorTest.php`** (388 líneas) - 40 tests

**62 tests implementados - Todos pasan ✅**:
**62 tests implementados - Todos pasan ✅**:

**EmployeeController (11 tests)**:
1. ✅ `testIndexAsAdmin` - Admin puede listar empleados
2. ✅ `testIndexWithoutAuth` - Sin auth devuelve 401
3. ✅ `testIndexWithoutPermission` - Sin permiso devuelve 403
4. ✅ `testShowEmployee` - Obtener detalle
5. ✅ `testShowEmployeeNotFound` - Empleado no encontrado devuelve 404
6. ✅ `testCreateEmployee` - Crear nuevo empleado
7. ✅ `testCreateEmployeeWithMissingFields` - Validación de campos requeridos
8. ✅ `testUpdateEmployee` - Actualizar empleado
9. ✅ `testDeleteEmployee` - Soft delete
10. ✅ `testRestoreEmployee` - Reactivar empleado
11. ✅ `testRestoreEmployeeOnlyAdmin` - Solo admin puede reactivar

**EmployeeValidator (40 tests)**:
12-14. ✅ Email (válido, vacío, inválido)
15-19. ✅ Contraseña (válida, corta, sin mayúscula, sin minúscula, sin número)
20-23. ✅ Nombre (válido, con acentos, vacío, muy corto)
24-29. ✅ NIF/NIE (válido, NIE, con espacios, vacío, formato inválido, letra incorrecta)
30-33. ✅ Teléfono (móvil, con prefijo, con espacios, inválido)
34-35. ✅ Código postal (válido, inválido)
36-39. ✅ IBAN (válido, con espacios, formato inválido, checksum inválido)
40-42. ✅ Salario (válido, negativo, no numérico)
43-45. ✅ Fecha (válida, formato inválido, futura)
46-48. ✅ Validación completa (datos completos, campos faltantes, actualización parcial)
49-51. ✅ Sanitización (espacios, HTML entities, array)

**TokenManager (11 tests)** - De Fase 2

**Resultado de tests**:
```
Tests: 62, Assertions: 117, PHPUnit Warnings: 1 (cache permission).

OK, but there were issues!
```

Todos los tests pasan correctamente.

---

## Permisos RBAC Utilizados

| Permiso | Admin | HR Manager | Dept Head | Employee |
|---------|-------|------------|-----------|----------|
| `employees.view` | ✅ | ✅ | ✅ (solo su dept) | ✅ (solo propio) |
| `employees.view_all` | ✅ | ✅ | ❌ | ❌ |
| `employees.view_department` | ✅ | ✅ | ✅ | ❌ |
| `employees.create` | ✅ | ✅ | ❌ | ❌ |
| `employees.edit` | ✅ | ✅ | ❌ | ❌ |
| `employees.delete` | ✅ | ❌ | ❌ | ❌ |

**Lógica de Acceso**:
- **Admin**: Acceso completo a todos los empleados
- **HR Manager**: Puede ver/crear/editar todos, no puede eliminar
- **Department Head**: Solo ve empleados de su departamento
- **Employee**: Solo ve su propio perfil

---

## Parámetros de API

### GET /api/employees (List)
**Query params**:
- `page` (int, default: 1) - Número de página
- `limit` (int, default: 20) - Resultados por página
- `search` (string) - Búsqueda en nombre, email, employee_number
- `department_id` (UUID) - Filtrar por departamento
- `active` (bool) - Filtrar por estado activo/inactivo

**Response**:
```json
{
  "data": [
    {
      "id": "uuid",
      "employee_number": "EMP20260001",
      "first_name": "John",
      "last_name": "Doe",
      "email": "john@test.com",
      "position": "Developer",
      "department_id": "uuid",
      "department_name": "IT",
      "active": true
    }
  ],
  "pagination": {
    "total": 100,
    "page": 1,
    "limit": 20,
    "pages": 5
  }
}
```

### POST /api/employees (Create)
**Required fields**:
- `email` (string) - Email único, formato válido
- `password` (string) - Mínimo 8 caracteres, debe contener mayúscula, minúscula y número
- `first_name` (string) - 2-100 caracteres, solo letras
- `last_name` (string) - 2-100 caracteres, solo letras
- `nif` (string) - NIF/NIE español válido (ej: 12345678Z, X1234567L)
- `position` (string) - Cargo, 2-100 caracteres

**Optional fields**:
- `role` (string, default: 'employee')
- `employee_number` (string, auto-generated if not provided)
- `phone` (string) - Teléfono español (ej: 612345678, +34612345678)
- `address`, `city`, `country` (string)
- `postal_code` (string) - Código postal español (ej: 28001)
- `hire_date` (date YYYY-MM-DD, default: today)
- `department_id` (UUID)
- `salary` (numeric, positive)
- `bank_account` (string) - IBAN español (ej: ES9121000418450200051332)

**Validaciones automáticas**:
- ✅ NIF/NIE: Formato y letra correcta
- ✅ Email: RFC5322 compliant
- ✅ Teléfono: Formato español con/sin prefijo
- ✅ IBAN: Checksum válido
- ✅ Código postal: Rango válido español
- ✅ Datos sanitizados automáticamente

**Response**: 201 Created
```json
{
  "message": "Empleado creado exitosamente",
  "employee_id": "uuid",
  "user_id": "uuid",
  "employee_number": "EMP20260001"
}
```

**Errores de validación**: 400 Bad Request
```json
{
  "error": "Errores de validación",
  "validation_errors": {
    "nif": "La letra del NIF/NIE no es correcta",
    "phone": "El teléfono debe ser un número español válido",
    "bank_account": "El IBAN no es válido (checksum incorrecto)"
  }
}
```

### PUT /api/employees/{id} (Update)
**Updateable fields**:
- `first_name`, `last_name`, `nif`, `position`
- `phone`, `address`, `city`, `postal_code`, `country`
- `hire_date`, `department_id`, `salary`, `bank_account`
- `active` (bool)
- `email` (actualiza en tabla users también)

**Response**: 200 OK
```json
{
  "message": "Empleado actualizado exitosamente",
  "employee_id": "uuid"
}
```

### DELETE /api/employees/{id} (Soft Delete)
**Efecto**: Marca `active = false`, no elimina el registro

**Response**: 200 OK
```json
{
  "message": "Empleado dado de baja exitosamente"
}
```

### POST /api/employees/{id}/restore (Restore)
**Requisito**: Solo admin puede ejecutar

**Response**: 200 OK
```json
{
  "message": "Empleado reactivado exitosamente"
}
```

---

## Seguridad Implementada

### 1. Autenticación
- JWT token requerido en todas las rutas
- Validación de token mediante `AuthenticationMiddleware`
- Extracción de `user_id` y `user_role` desde token

### 2. Autorización
- Control de permisos mediante `AccessControl::hasPermission()`
- Restricción por rol:
  - Department Head: solo ve empleados de su departamento
  - Employee: solo ve su propio perfil
  
### 3. Validación de Datos
- Campos requeridos verificados antes de crear
- Transacciones para operaciones multi-tabla
- Soft delete en lugar de DELETE físico

### 4. Gestión de Errores
- Try-catch en todos los métodos
- Mensajes genéricos en producción
- Logs detallados con `error_log()`
- Códigos HTTP apropiados (401, 403, 404, 409, 500)

### 5. Validación y Sanitización
- **Sanitización automática**: Eliminación de espacios, caracteres de control, HTML entities
- **Validación específica por campo**: NIF/NIE, IBAN, teléfono, código postal español
- **Mensajes de error descriptivos**: Cada campo con su propio mensaje de error
- **Validación diferenciada**: Creación (todos campos) vs Actualización (solo campos presentes)

---

## Ejemplos de Validación

### NIF/NIE Español
```php
// Válidos
12345678Z       // NIF
X1234567L       // NIE (X/Y/Z)
12345678 Z      // Con espacios (se limpia automáticamente)

// Inválidos
12345678A       // Letra incorrecta
123ABC          // Formato inválido
```

### Teléfono Español
```php
// Válidos
612345678       // Móvil
912345678       // Fijo
+34612345678    // Con prefijo
612 34 56 78    // Con espacios

// Inválidos
512345678       // No empieza por 6-9
123456          // Muy corto
```

### IBAN Español
```php
// Válido
ES9121000418450200051332
ES91 2100 0418 4502 0005 1332  // Con espacios

// Inválido
ES0000000000000000000000  // Checksum incorrecto
FR1234...                  // No español
```

### Código Postal
```php
// Válidos
28001           // Madrid
08001           // Barcelona
48001           // Vizcaya

// Inválidos
99999           // Fuera de rango (52999 máximo)
1234            // Muy corto
```

---

## Cobertura de Tests

**Coverage: 100% de métodos críticos**
- ✅ Autenticación (401 sin token)
- ✅ Autorización (403 sin permisos)
- ✅ Creación con validación completa
- ✅ Actualización de datos con validación
- ✅ Soft delete
- ✅ Restauración (solo admin)
- ✅ Not found (404)
- ✅ Listado con paginación
- ✅ Validación de NIF/NIE español
- ✅ Validación de IBAN con checksum
- ✅ Validación de teléfono español
- ✅ Validación de código postal
- ✅ Validación de contraseña fuerte
- ✅ Sanitización de entrada

---

## Pendiente para Completar Fase 3

### ✅ Completado (Semana 9-10)
- ✅ API CRUD empleados con PHP
- ✅ Paginación y filtros
- ✅ Validación completa de datos españoles
- ✅ Tests unitarios (62/62 passing)

### Semana 11
- [ ] Web: Pantalla lista de empleados con paginación
- [ ] Web: Formulario de creación/edición
- [ ] Android: Pantalla lista de empleados
- [ ] Android: Detalle de empleado
- [ ] Sistema de departamentos/secciones

### Semana 12
- [ ] Asignación de jefe de departamento
- [ ] Búsqueda avanzada multi-campo
- [ ] Exportar empleados (CSV/PDF)
- [ ] Estadísticas de empleados

### Semana 13
- [ ] Audit trail (historial de cambios)
- [ ] Android: CRUD completo
- [ ] Notificaciones de cambios
- [ ] Reactivación de empleados dados de baja

### Semana 14
- [ ] Tests E2E con Playwright
- [ ] Documentación API completa
- [ ] Pruebas de carga
- [ ] Tests de seguridad

---

## Comandos para Ejecutar Tests

```bash
# Desde /home/kalista/erronkak/erronka4/Zabala Gailetak/hr-portal

# Todos los tests
./vendor/bin/phpunit

# Solo tests de EmployeeController
./vendor/bin/phpunit tests/Controllers/EmployeeControllerTest.php

# Con coverage
./vendor/bin/phpunit --coverage-text
```

---

## Notas de Implementación

1. **Transacciones**: El método `create()` usa transacciones para garantizar atomicidad (crea user + employee en una sola operación)

2. **Employee Number**: Auto-generado en formato `EMP{YEAR}{SEQUENCE}` (ej: EMP20260001, EMP20260002...)

3. **Soft Delete**: Los empleados nunca se eliminan físicamente, solo se marcan como `active = false`

4. **Restauración**: Solo administradores pueden reactivar empleados

5. **Filtrado Automático**: Department Heads automáticamente ven solo su departamento, no requiere filtro manual

6. **Búsqueda ILIKE**: Búsqueda case-insensitive en PostgreSQL

---

## Próximos Pasos

1. ✅ **Completado**: CRUD básico funcional
2. ✅ **Completado**: Validación completa de datos españoles
3. ✅ **Completado**: Tests unitarios (62/62 pasando)
4. **Siguiente**: Implementar interfaz web (React)
5. **Después**: Implementar app móvil (React Native)
6. **Final**: Audit trail y exportación

---

**Completado por**: GitHub Copilot  
**Última actualización**: 2026-01-15 13:00:00  
**Tests**: 62/62 pasando ✅  
**Validaciones**: NIF/NIE, IBAN, Teléfono, CP, Email, Contraseña ✅
