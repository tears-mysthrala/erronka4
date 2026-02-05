# Quick Start Guide - ER4 Implementation

## 🚀 Setup Rápido

### 1. Instalar Dependencias

```bash
# Python dependencies para simulaciones OT y pentesting
pip3 install -r requirements.txt

# Composer dependencies (si no están instaladas)
cd "Zabala Gailetak/hr-portal"
composer install
```

### 2. Verificar CI/CD Pipeline

```bash
# Los workflows se ejecutan automáticamente en GitHub Actions
# Ver: https://github.com/[usuario]/erronka4/actions

# Para ejecutar tests localmente:
cd "Zabala Gailetak/hr-portal"
composer test          # PHPUnit tests
composer analyse       # PHPStan SAST
composer audit         # Security audit
```

### 3. Ejecutar Simulación OT

```bash
cd "Zabala Gailetak/infrastructure/ot/simulations"

# Ejecutar todas las simulaciones
./run_all_simulations.sh

# O ejecutar individualmente:
python3 modbus_attack.py
bash unauthorized_plc_access.sh
python3 verify_detection.py
```

**Verificar resultados en Kibana:**
- URL: http://localhost:5601
- Index: zabala-alerts-*
- Buscar: `alert.id:(AUTH-001 OR SCAN-001 OR RATE-001)`

### 4. Ejecutar Pentesting

```bash
cd "Zabala Gailetak/security/pentesting"

# Ejecutar suite completa
./automated_pentest.sh

# Ver reportes
ls -lh reports/
cat reports/pentest_report_*.md
```

### 5. Verificar CSRF Protection

```bash
# Test 1: POST sin token (debe fallar con 403)
curl -X POST http://localhost:8080/api/employees \
  -H "Content-Type: application/json" \
  -d '{"first_name": "Test"}'

# Test 2: Verificar que GET funciona sin token
curl http://localhost:8080/api/employees
```

### 6. Verificar CSP Headers

```bash
# Verificar headers en respuesta
curl -I http://localhost:8080/dashboard | grep Content-Security-Policy

# Debe mostrar:
# Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-...'; ...
# (sin 'unsafe-inline')
```

## 📊 Verificación Rápida

### Checklist de 5 minutos

```bash
# 1. CI/CD activo
echo "✓ GitHub Actions: Ver https://github.com/[usuario]/erronka4/actions"

# 2. CSRF funciona
curl -X POST http://localhost:8080/api/test && echo "✗ CSRF no funciona" || echo "✓ CSRF funciona"

# 3. CSP con nonces
curl -I http://localhost:8080 | grep -q "nonce-" && echo "✓ CSP con nonces" || echo "✗ Sin nonces"

# 4. Scripts OT presentes
ls -1 "Zabala Gailetak/infrastructure/ot/simulations/" | wc -l
# Debe mostrar: 5 archivos

# 5. Scripts pentesting presentes
ls -1 "Zabala Gailetak/security/pentesting/" | grep -v reports | wc -l
# Debe mostrar: 3 archivos
```

## 🔧 Troubleshooting

### Problema: Tests fallan en CI/CD

**Solución:**
```bash
# Verificar PHP syntax localmente
find . -name "*.php" -exec php -l {} \;

# Ejecutar tests con más verbose
cd "Zabala Gailetak/hr-portal"
vendor/bin/phpunit --verbose
```

### Problema: Simulación OT falla

**Solución:**
```bash
# Verificar que entorno OT está activo
docker-compose -f docker-compose.ot.yml ps

# Si no está activo:
docker-compose -f docker-compose.ot.yml up -d

# Verificar conectividad
nc -zv 192.168.50.10 502
```

### Problema: Pentesting falla

**Solución:**
```bash
# Verificar Docker para ZAP
docker ps | grep zap

# Pull imagen si necesario
docker pull ghcr.io/zaproxy/zaproxy:stable

# Verificar servidor web activo
curl -I http://localhost:8080
```

### Problema: CSRF siempre falla

**Solución:**
```bash
# Verificar session iniciada
php -r "session_start(); echo session_id() . PHP_EOL;"

# Verificar CSRFProtection class
cd "Zabala Gailetak/hr-portal"
php -l src/Security/CSRFProtection.php
```

## 📝 Comandos Útiles

```bash
# Ver logs de seguridad
tail -f "Zabala Gailetak/hr-portal/logs/security.log"

# Ver alertas SIEM recientes (últimas 10)
curl -s "http://localhost:9200/zabala-alerts-*/_search?size=10&sort=@timestamp:desc" | jq

# Ejecutar todos los tests de seguridad
cd "Zabala Gailetak"
python3 security/pentesting/test_auth.py
cd infrastructure/ot/simulations
./run_all_simulations.sh

# Generar reporte completo
cd security/pentesting
./automated_pentest.sh
```

## 🎯 Objetivos ER4 - Status

| Objetivo | Script/Test | Comando | Status |
|----------|-------------|---------|--------|
| CI/CD Tests | ci-complete.yml | GitHub Actions | ✅ |
| CSRF Protection | CSRFMiddleware.php | curl test | ✅ |
| CSP Nonces | SecurityHeaders.php | curl -I | ✅ |
| OT Simulation | run_all_simulations.sh | ./run_all_simulations.sh | ✅ |
| Pentesting | automated_pentest.sh | ./automated_pentest.sh | ✅ |

## 📚 Documentación Completa

Ver `IMPLEMENTATION_ER4.md` para detalles completos de implementación.

---

**¿Necesitas ayuda?**
- Email: soporte@zabala.eus
- Wiki: [Link interno]
- GitHub Issues: [Link al repo]
