################################################################################
# Dockerfile - HR Portal Seguro
# Zabala Gailetak
# Multi-stage build con seguridad hardened
################################################################################

# ============================================================================
# ETAPA 1: Dependencias PHP
# ============================================================================
FROM php:8.4-fpm-alpine AS php-deps

# Instalar dependencias de compilación
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    postgresql-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    && apk add --no-cache \
    postgresql-libs \
    libzip \
    libpng \
    libjpeg-turbo \
    freetype \
    icu-libs

# Instalar extensiones PHP necesarias
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    pgsql \
    zip \
    gd \
    intl \
    mbstring \
    opcache \
    bcmath

# Instalar Redis extension
RUN pecl install redis-7.0.0 \
    && docker-php-ext-enable redis

# Limpiar dependencias de compilación
RUN apk del .build-deps

# ============================================================================
# ETAPA 2: Composer
# ============================================================================
FROM composer:2.7 AS composer

# ============================================================================
# ETAPA 3: Node.js (para assets)
# ============================================================================
FROM node:20-alpine AS node-builder

WORKDIR /app
COPY package*.json ./
RUN npm ci --only=production

COPY . .
RUN npm run build

# ============================================================================
# ETAPA 4: Imagen final de producción
# ============================================================================
FROM php:8.4-fpm-alpine AS production

# Labels de seguridad y trazabilidad
LABEL maintainer="security@zabala-gailetak.com"
LABEL version="1.0"
LABEL description="HR Portal - Secure PHP Application"
LABEL security.scan="trivy"

# No ejecutar como root
ARG USER=www-data
ARG GROUP=www-data
ARG UID=1000
ARG GID=1000

# Crear usuario no-root
RUN addgroup -g ${GID} -S ${GROUP} \
    && adduser -u ${UID} -S ${USER} -G ${GROUP}

# Instalar dependencias runtime
RUN apk add --no-cache \
    postgresql-libs \
    libzip \
    libpng \
    libjpeg-turbo \
    freetype \
    icu-libs \
    libcap \
    ca-certificates \
    tzdata \
    && rm -rf /var/cache/apk/*

# Copiar extensiones PHP compiladas
COPY --from=php-deps /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=php-deps /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

# Copiar Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html

# Copiar código fuente
COPY --chown=${USER}:${GROUP} . .

# Copiar assets compilados
COPY --from=node-builder --chown=${USER}:${GROUP} /app/public/build ./public/build

# Instalar dependencias PHP (producción)
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    && rm -rf /root/.composer

# ============================================================================
# CONFIGURACIÓN DE SEGURIDAD PHP
# ============================================================================

# Configuración PHP segura
RUN { \
    echo "expose_php = Off"; \
    echo "display_errors = Off"; \
    echo "log_errors = On"; \
    echo "error_log = /var/log/php_errors.log"; \
    echo "allow_url_fopen = Off"; \
    echo "allow_url_include = Off"; \
    echo "disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source"; \
    echo "max_execution_time = 30"; \
    echo "max_input_time = 30"; \
    echo "max_input_vars = 1000"; \
    echo "memory_limit = 256M"; \
    echo "post_max_size = 10M"; \
    echo "upload_max_filesize = 10M"; \
    echo "session.cookie_httponly = 1"; \
    echo "session.cookie_secure = 1"; \
    echo "session.cookie_samesite = \"Strict\""; \
    echo "session.use_strict_mode = 1"; \
    echo "session.gc_maxlifetime = 3600"; \
    echo "opcache.enable = 1"; \
    echo "opcache.memory_consumption = 128"; \
    echo "opcache.max_accelerated_files = 10000"; \
} > /usr/local/etc/php/conf.d/security.ini

# Configuración OPcache para producción
RUN { \
    echo "opcache.validate_timestamps = 0"; \
    echo "opcache.revalidate_freq = 0"; \
    echo "opcache.interned_strings_buffer = 16"; \
    echo "opcache.fast_shutdown = 1"; \
} > /usr/local/etc/php/conf.d/opcache.ini

# ============================================================================
# PERMISOS Y DIRECTORIOS
# ============================================================================

# Crear directorios necesarios con permisos correctos
RUN mkdir -p /var/www/html/storage/logs \
    /var/www/html/storage/cache \
    /var/www/html/storage/uploads \
    /var/log \
    && chown -R ${USER}:${GROUP} /var/www/html \
    && chmod -R 750 /var/www/html \
    && chmod -R 770 /var/www/html/storage \
    && chmod -R 770 /var/www/html/storage/logs \
    && chmod -R 770 /var/www/html/storage/uploads

# ============================================================================
# HEALTHCHECK
# ============================================================================

HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD php-fpm-healthcheck || exit 1

# ============================================================================
# SEGURIDAD ADICIONAL
# ============================================================================

# Eliminar shells innecesarios
RUN rm -f /bin/sh /bin/ash /bin/bash 2>/dev/null || true

# Solo permitir puerto no-root
RUN setcap 'cap_net_bind_service=+ep' /usr/local/sbin/php-fpm 2>/dev/null || true

# ============================================================================
# CONFIGURACIÓN FINAL
# ============================================================================

USER ${USER}

EXPOSE 9000

CMD ["php-fpm", "-F"]
