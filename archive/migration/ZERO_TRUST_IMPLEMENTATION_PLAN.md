# Plan de Implementación Zero Trust (Sin Dependencias Externas)

**Objetivo:** Eliminar todas las dependencias de terceros (`vendor/`, CDN) y sustituirlas por implementaciones nativas en PHP 8.4 para garantizar un entorno seguro y autocontenido.

## 1. Análisis de Dependencias a Eliminar

Del archivo `composer.json` actual:
- `vlucas/phpdotenv` -> **Reemplazo:** Clase `src/Core/DotEnv.php` nativa.
- `firebase/php-jwt` -> **Reemplazo:** Clase `src/Security/JwtUtil.php` usando `hash_hmac` y `base64_url`.
- `ramsey/uuid` (Implícito) -> **Reemplazo:** `bin2hex(random_bytes(16))` o implementación RFC 4122.
- `monolog/monolog` -> **Reemplazo:** `error_log()` nativo con formato estructurado.
- `bootstrap` (CDN) -> **Reemplazo:** Descarga local de assets CSS/JS.

## 2. Estrategia de Implementación

### Fase 1: Infraestructura Core (El "Motor")
Antes de borrar nada, debemos construir los cimientos.

1.  **Autoloader PSR-4 Nativo:**
    Crear `src/Core/Autoloader.php`. Este script sustituirá a `vendor/autoload.php`.
    - Mapeará `ZabalaGailetak\HrPortal\` a `src/`.

2.  **Gestor de Variables de Entorno (.env):**
    Crear `src/Core/DotEnv.php`.
    - Leerá el archivo `.env`.
    - Ignorará comentarios `#`.
    - Cargará variables en `$_ENV` y `putenv()`.

### Fase 2: Seguridad y Criptografía (La "Bóveda")

3.  **Implementación JWT Nativa:**
    Refactorizar `src/Auth/TokenManager.php` para eliminar `Firebase\JWT`.
    - Implementar funciones privadas para `base64UrlEncode` y firma `HMAC-SHA256`.
    - Mantener la misma interfaz pública para no romper el resto del código.

### Fase 3: Frontend "Air-gapped"

4.  **Localizar Assets:**
    - Descargar Bootstrap 5.3 CSS y JS a `public/assets/vendor/bootstrap/`.
    - Actualizar `views/layouts/header.php` y `footer.php` para apuntar a rutas locales.

### Fase 4: El "Switch" (El Cambio)

5.  **Actualizar Entry Point:**
    - Modificar `public/index.php` para requerir `src/Core/Autoloader.php` en lugar de `vendor/autoload.php`.
    - Inicializar el nuevo `DotEnv`.

6.  **Limpieza:**
    - Borrar `composer.json`, `composer.lock`, `vendor/`.

## 3. Plan de Ejecución

1.  Crear `src/Core/Autoloader.php`.
2.  Crear `src/Core/DotEnv.php`.
3.  Modificar `src/Auth/TokenManager.php` (Refactor completo).
4.  Descargar assets de Bootstrap.
5.  Actualizar Vistas.
6.  Actualizar `public/index.php`.
7.  Ejecutar script de limpieza.

---
**Autor:** Gemini Agent (Zero Trust Enforcer)
**Fecha:** 24 Enero 2026
