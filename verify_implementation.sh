#!/bin/bash
# Script de verificación de implementación ER4

echo "========================================"
echo "  Verificación Implementación ER4"
echo "========================================"
echo ""

PASSED=0
FAILED=0

# Test 1: CI/CD workflows
echo "[1/10] Verificando CI/CD workflows..."
if [ -f ".github/workflows/ci-complete.yml" ] && [ -f ".github/workflows/security-scan.yml" ]; then
    echo "  ✓ Workflows creados"
    ((PASSED++))
else
    echo "  ✗ Workflows faltantes"
    ((FAILED++))
fi

# Test 2: Composer configuration
echo "[2/10] Verificando configuración Composer..."
if [ -f "Zabala Gailetak/hr-portal/composer.json" ]; then
    echo "  ✓ composer.json creado"
    ((PASSED++))
else
    echo "  ✗ composer.json faltante"
    ((FAILED++))
fi

# Test 3: CSRF implementation
echo "[3/10] Verificando CSRF protection..."
if grep -q "validateToken" "Zabala Gailetak/hr-portal/src/Middleware/CSRFMiddleware.php"; then
    echo "  ✓ CSRF implementado"
    ((PASSED++))
else
    echo "  ✗ CSRF no implementado"
    ((FAILED++))
fi

# Test 4: CSP nonces
echo "[4/10] Verificando CSP nonces..."
if grep -q "generateNonce" "Zabala Gailetak/hr-portal/src/Security/SecurityHeaders.php"; then
    echo "  ✓ CSP con nonces"
    ((PASSED++))
else
    echo "  ✗ CSP sin nonces"
    ((FAILED++))
fi

# Test 5: Request getClientIp
echo "[5/10] Verificando Request::getClientIp()..."
if grep -q "getClientIp" "Zabala Gailetak/hr-portal/src/Http/Request.php"; then
    echo "  ✓ getClientIp() implementado"
    ((PASSED++))
else
    echo "  ✗ getClientIp() faltante"
    ((FAILED++))
fi

# Test 6: CSRF JavaScript
echo "[6/10] Verificando CSRF JavaScript..."
if [ -f "Zabala Gailetak/hr-portal/public/assets/js/csrf.js" ]; then
    echo "  ✓ csrf.js creado"
    ((PASSED++))
else
    echo "  ✗ csrf.js faltante"
    ((FAILED++))
fi

# Test 7: OT simulation scripts
echo "[7/10] Verificando scripts OT..."
OT_COUNT=$(ls -1 "Zabala Gailetak/infrastructure/ot/simulations/"*.{py,sh} 2>/dev/null | wc -l)
if [ "$OT_COUNT" -ge 5 ]; then
    echo "  ✓ Scripts OT presentes ($OT_COUNT archivos)"
    ((PASSED++))
else
    echo "  ✗ Scripts OT incompletos ($OT_COUNT/5)"
    ((FAILED++))
fi

# Test 8: Pentesting scripts
echo "[8/10] Verificando scripts pentesting..."
PENT_COUNT=$(ls -1 "Zabala Gailetak/security/pentesting/"*.{py,sh} 2>/dev/null | wc -l)
if [ "$PENT_COUNT" -ge 3 ]; then
    echo "  ✓ Scripts pentesting presentes ($PENT_COUNT archivos)"
    ((PASSED++))
else
    echo "  ✗ Scripts pentesting incompletos ($PENT_COUNT/3)"
    ((FAILED++))
fi

# Test 9: Documentation
echo "[9/10] Verificando documentación..."
if [ -f "Zabala Gailetak/IMPLEMENTATION_ER4.md" ] && [ -f "Zabala Gailetak/QUICKSTART_ER4.md" ]; then
    echo "  ✓ Documentación completa"
    ((PASSED++))
else
    echo "  ✗ Documentación incompleta"
    ((FAILED++))
fi

# Test 10: Requirements file
echo "[10/10] Verificando requirements.txt..."
if [ -f "Zabala Gailetak/requirements.txt" ]; then
    echo "  ✓ requirements.txt creado"
    ((PASSED++))
else
    echo "  ✗ requirements.txt faltante"
    ((FAILED++))
fi

echo ""
echo "========================================"
echo "  Resultados: $PASSED/10 tests pasados"
echo "========================================"

if [ $FAILED -eq 0 ]; then
    echo "✓ IMPLEMENTACIÓN COMPLETA"
    exit 0
else
    echo "✗ $FAILED tests fallaron"
    exit 1
fi
