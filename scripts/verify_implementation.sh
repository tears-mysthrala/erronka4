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
echo "  NIS2 Compliance Checks"
echo "========================================"
echo ""

NIS2_PASSED=0
NIS2_FAILED=0
NIS2_TOTAL=10

# NIS2 Test 1: NIS2 directory structure
echo "[NIS2-1/$NIS2_TOTAL] Verificando estructura NIS2..."
if [ -d "Zabala Gailetak/compliance/nis2" ] && [ -f "Zabala Gailetak/compliance/nis2/README.md" ]; then
    echo "  ✓ Directorio NIS2 creado"
    ((NIS2_PASSED++))
else
    echo "  ✗ Directorio NIS2 faltante"
    ((NIS2_FAILED++))
fi

# NIS2 Test 2: Controls mapping
echo "[NIS2-2/$NIS2_TOTAL] Verificando NIS2 controls mapping..."
if [ -f "Zabala Gailetak/compliance/nis2/nis2_controls_mapping.md" ]; then
    echo "  ✓ NIS2 controls mapping presente"
    ((NIS2_PASSED++))
else
    echo "  ✗ NIS2 controls mapping faltante"
    ((NIS2_FAILED++))
fi

# NIS2 Test 3: CSIRT Roster
echo "[NIS2-3/$NIS2_TOTAL] Verificando CSIRT roster..."
if [ -f "Zabala Gailetak/compliance/nis2/csirt_roster.md" ]; then
    echo "  ✓ CSIRT roster presente"
    ((NIS2_PASSED++))
else
    echo "  ✗ CSIRT roster faltante (NIS2 Art.21.2.b obligatorio)"
    ((NIS2_FAILED++))
fi

# NIS2 Test 4: Notification templates (24h, 72h, final)
echo "[NIS2-4/$NIS2_TOTAL] Verificando plantillas de notificación NIS2..."
NIS2_TEMPLATES=0
[ -f "Zabala Gailetak/compliance/nis2/notifications/early_warning_24h_template.md" ] && ((NIS2_TEMPLATES++))
[ -f "Zabala Gailetak/compliance/nis2/notifications/full_report_72h_template.md" ] && ((NIS2_TEMPLATES++))
[ -f "Zabala Gailetak/compliance/nis2/notifications/final_report_template.md" ] && ((NIS2_TEMPLATES++))
if [ "$NIS2_TEMPLATES" -ge 3 ]; then
    echo "  ✓ Plantillas NIS2 completas ($NIS2_TEMPLATES/3)"
    ((NIS2_PASSED++))
else
    echo "  ✗ Plantillas NIS2 incompletas ($NIS2_TEMPLATES/3 — Art.23 obligatorio)"
    ((NIS2_FAILED++))
fi

# NIS2 Test 5: Vulnerability Disclosure Policy
echo "[NIS2-5/$NIS2_TOTAL] Verificando Vulnerability Disclosure Policy..."
if [ -f "Zabala Gailetak/compliance/nis2/vulnerability_disclosure_policy.md" ]; then
    echo "  ✓ VDP presente (Art. 21.2.e)"
    ((NIS2_PASSED++))
else
    echo "  ✗ VDP faltante (NIS2 Art.21.2.e obligatorio)"
    ((NIS2_FAILED++))
fi

# NIS2 Test 6: Supplier Security Register
echo "[NIS2-6/$NIS2_TOTAL] Verificando registro de proveedores..."
if [ -f "Zabala Gailetak/compliance/nis2/supplier_security_register.md" ]; then
    echo "  ✓ Supplier register presente (Art. 21.2.d)"
    ((NIS2_PASSED++))
else
    echo "  ✗ Supplier register faltante (NIS2 Art.21.2.d obligatorio)"
    ((NIS2_FAILED++))
fi

# NIS2 Test 7: SIEM NIS2 correlation rules
echo "[NIS2-7/$NIS2_TOTAL] Verificando SIEM correlation rules NIS2..."
if [ -f "Zabala Gailetak/compliance/nis2/siem_soar/nis2_correlation_rules.json" ]; then
    echo "  ✓ SIEM NIS2 rules presentes"
    ((NIS2_PASSED++))
else
    echo "  ✗ SIEM NIS2 rules faltantes"
    ((NIS2_FAILED++))
fi

# NIS2 Test 8: SOAR Playbooks
echo "[NIS2-8/$NIS2_TOTAL] Verificando SOAR playbooks NIS2..."
if [ -f "Zabala Gailetak/compliance/nis2/siem_soar/nis2_soar_playbooks.md" ]; then
    echo "  ✓ SOAR playbooks presentes"
    ((NIS2_PASSED++))
else
    echo "  ✗ SOAR playbooks faltantes"
    ((NIS2_FAILED++))
fi

# NIS2 Test 9: Training & Exercises Plan
echo "[NIS2-9/$NIS2_TOTAL] Verificando plan de formación NIS2..."
if [ -f "Zabala Gailetak/compliance/nis2/training_exercises_plan.md" ]; then
    echo "  ✓ Training plan presente (Art. 20.2)"
    ((NIS2_PASSED++))
else
    echo "  ✗ Training plan faltante (NIS2 Art.20.2 obligatorio)"
    ((NIS2_FAILED++))
fi

# NIS2 Test 10: Evidence Pack
echo "[NIS2-10/$NIS2_TOTAL] Verificando evidence pack..."
if [ -d "Zabala Gailetak/compliance/nis2/evidence-pack" ] && [ -f "Zabala Gailetak/compliance/nis2/evidence-pack/README.md" ]; then
    echo "  ✓ Evidence pack estructura presente"
    ((NIS2_PASSED++))
else
    echo "  ✗ Evidence pack faltante"
    ((NIS2_FAILED++))
fi

TOTAL_PASSED=$((PASSED + NIS2_PASSED))
TOTAL_FAILED=$((FAILED + NIS2_FAILED))
TOTAL_TESTS=$((10 + NIS2_TOTAL))

echo ""
echo "========================================"
echo "  NIS2 Resultados: $NIS2_PASSED/$NIS2_TOTAL tests pasados"
echo "========================================"

echo ""
echo "========================================"
echo "  Resultados Globales: $TOTAL_PASSED/$TOTAL_TESTS tests pasados"
echo "  - ER4 Base: $PASSED/10"
echo "  - NIS2 Compliance: $NIS2_PASSED/$NIS2_TOTAL"
echo "========================================"

if [ $TOTAL_FAILED -eq 0 ]; then
    echo "✓ IMPLEMENTACIÓN COMPLETA (ER4 + NIS2)"
    exit 0
else
    echo "✗ $TOTAL_FAILED tests fallaron"
    exit 1
fi
