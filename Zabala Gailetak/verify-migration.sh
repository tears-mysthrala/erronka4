#!/bin/bash

# ============================================================================
# Script de Verificación Post-Migración
# Zabala Gailetak HR Portal
# ============================================================================

GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}======================================${NC}"
echo -e "${BLUE}Verificación Post-Migración${NC}"
echo -e "${BLUE}HR Portal - Zabala Gailetak${NC}"
echo -e "${BLUE}======================================${NC}"
echo ""

ERRORS=0
WARNINGS=0

# Función para verificar
check() {
    local item="$1"
    local command="$2"
    
    echo -n "Verificando $item... "
    if eval "$command" > /dev/null 2>&1; then
        echo -e "${GREEN}✓${NC}"
        return 0
    else
        echo -e "${RED}✗${NC}"
        ((ERRORS++))
        return 1
    fi
}

check_warning() {
    local item="$1"
    local command="$2"
    
    echo -n "Verificando $item... "
    if eval "$command" > /dev/null 2>&1; then
        echo -e "${GREEN}✓${NC}"
    else
        echo -e "${YELLOW}⚠${NC}"
        ((WARNINGS++))
    fi
    return 0
}

echo -e "${BLUE}1. Estructura del Proyecto${NC}"
echo ""

check "Backend PHP existe" "[ -d 'Zabala Gailetak/hr-portal' ]"
check "Android app existe" "[ -d 'Zabala Gailetak/android-app' ]"
check "Docker compose existe" "[ -f 'Zabala Gailetak/docker-compose.hrportal.yml' ]"
check "Nginx config existe" "[ -f 'Zabala Gailetak/nginx/nginx-hrportal.conf' ]"

echo ""
echo -e "${BLUE}2. Archivos del Backend${NC}"
echo ""

check "composer.json" "[ -f 'Zabala Gailetak/hr-portal/composer.json' ]"
check "Dockerfile PHP" "[ -f 'Zabala Gailetak/hr-portal/Dockerfile' ]"
check "Front controller" "[ -f 'Zabala Gailetak/hr-portal/public/index.php' ]"
check "App.php principal" "[ -f 'Zabala Gailetak/hr-portal/src/App.php' ]"
check "Router" "[ -f 'Zabala Gailetak/hr-portal/src/Routing/Router.php' ]"
check "Database class" "[ -f 'Zabala Gailetak/hr-portal/src/Database/Database.php' ]"
check "Config principal" "[ -f 'Zabala Gailetak/hr-portal/config/config.php' ]"
check "Routes" "[ -f 'Zabala Gailetak/hr-portal/config/routes.php' ]"
check "Migration SQL" "[ -f 'Zabala Gailetak/hr-portal/migrations/001_init_schema.sql' ]"
check "Migrate script" "[ -f 'Zabala Gailetak/hr-portal/scripts/migrate.sh' ]"

echo ""
echo -e "${BLUE}3. Archivos de Android${NC}"
echo ""

check "build.gradle.kts" "[ -f 'Zabala Gailetak/android-app/build.gradle.kts' ]"
check "settings.gradle.kts" "[ -f 'Zabala Gailetak/android-app/settings.gradle.kts' ]"
check "app build.gradle.kts" "[ -f 'Zabala Gailetak/android-app/app/build.gradle.kts' ]"
check "AndroidManifest.xml" "[ -f 'Zabala Gailetak/android-app/app/src/main/AndroidManifest.xml' ]"
check "HrApplication.kt" "[ -f 'Zabala Gailetak/android-app/app/src/main/java/com/zabalagailetak/hrapp/HrApplication.kt' ]"
check "MainActivity.kt" "[ -f 'Zabala Gailetak/android-app/app/src/main/java/com/zabalagailetak/hrapp/presentation/MainActivity.kt' ]"
check "Theme.kt" "[ -f 'Zabala Gailetak/android-app/app/src/main/java/com/zabalagailetak/hrapp/presentation/ui/theme/Theme.kt' ]"
check "strings.xml" "[ -f 'Zabala Gailetak/android-app/app/src/main/res/values/strings.xml' ]"

echo ""
echo -e "${BLUE}4. Código Antiguo Eliminado${NC}"
echo ""

check "No existe src/api (Node.js)" "[ ! -d 'Zabala Gailetak/src/api' ]"
check "No existe src/web (React)" "[ ! -d 'Zabala Gailetak/src/web' ]"
check "No existe src/mobile (RN)" "[ ! -d 'Zabala Gailetak/src/mobile' ]"
check "No existe node_modules viejo" "[ ! -d node_modules ]"
check "No existe package.json raíz" "[ ! -f package.json ]"
check "No existe docker-compose.yml viejo" "[ ! -f docker-compose.yml ]"

echo ""
echo -e "${BLUE}5. Documentación${NC}"
echo ""

check "README.md actualizado" "[ -f README.md ]"
check "QUICK_START_GUIDE.md" "[ -f QUICK_START_GUIDE.md ]"
check "DOCUMENTATION_INDEX.md" "[ -f DOCUMENTATION_INDEX.md ]"
check "MIGRATION_PLAN.md" "[ -f MIGRATION_PLAN.md ]"
check "MIGRATION_SUMMARY.md" "[ -f MIGRATION_SUMMARY.md ]"
check "README Backend" "[ -f 'Zabala Gailetak/hr-portal/README.md' ]"
check "README Android" "[ -f 'Zabala Gailetak/android-app/README.md' ]"

echo ""
echo -e "${BLUE}6. Docker (opcional)${NC}"
echo ""

check_warning "Docker instalado" "command -v docker"
check_warning "Docker Compose instalado" "command -v docker-compose"

if command -v docker &> /dev/null; then
    check_warning "Docker daemon running" "docker ps"
fi

echo ""
echo -e "${BLUE}7. PHP (opcional)${NC}"
echo ""

check_warning "PHP 8.4+ instalado" "command -v php"
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -r 'echo PHP_VERSION;')
    echo "  → Versión PHP: $PHP_VERSION"
fi

check_warning "Composer instalado" "command -v composer"

echo ""
echo -e "${BLUE}======================================${NC}"
echo -e "${BLUE}Resumen de Verificación${NC}"
echo -e "${BLUE}======================================${NC}"
echo ""

if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}✓ Verificación completada sin errores${NC}"
    echo -e "  Advertencias: $WARNINGS"
    echo ""
    echo -e "${GREEN}La migración se ha completado exitosamente!${NC}"
    echo ""
    echo "Próximos pasos:"
    echo "1. cd \"Zabala Gailetak/hr-portal\""
    echo "2. make up          # Iniciar servicios Docker"
    echo "3. make migrate     # Ejecutar migraciones"
    echo "4. Abrir http://localhost:8080"
    echo ""
    exit 0
else
    echo -e "${RED}✗ Se encontraron $ERRORS errores${NC}"
    echo -e "  Advertencias: $WARNINGS"
    echo ""
    echo "Por favor, revisa los errores arriba y completa la migración."
    echo ""
    exit 1
fi
