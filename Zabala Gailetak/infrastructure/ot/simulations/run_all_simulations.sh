#!/bin/bash
set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPORT_FILE="$SCRIPT_DIR/simulation_report_$(date +%Y%m%d_%H%M%S).txt"

echo "========================================"
echo "  Simulación de Incidentes OT - ER4"
echo "  Timestamp: $(date)"
echo "========================================"
echo ""

# Verificar entorno OT activo
echo "[1/5] Verificando entorno OT..."
cd "$SCRIPT_DIR/../../"
if docker-compose -f docker-compose.ot.yml ps 2>/dev/null | grep -q "Up"; then
    echo "[✓] Entorno OT activo"
else
    echo "[!] Entorno OT no está activo"
    echo "[*] Nota: Las simulaciones se ejecutarán pero pueden fallar"
    echo "[*] Para activar: docker-compose -f docker-compose.ot.yml up -d"
fi

# Ejecutar simulaciones
echo ""
echo "[2/5] Ejecutando ataque Modbus TCP..."
cd "$SCRIPT_DIR"
if command -v python3 &> /dev/null; then
    python3 modbus_attack.py 2>&1 | tee -a "$REPORT_FILE" || echo "[!] Falló simulación Modbus"
else
    echo "[!] Python3 no disponible, saltando..." | tee -a "$REPORT_FILE"
fi

echo ""
echo "[3/5] Ejecutando brute force PLC..."
bash unauthorized_plc_access.sh 2>&1 | tee -a "$REPORT_FILE" || echo "[!] Falló brute force"

echo ""
echo "[4/5] Ejecutando DoS simulation..."
echo "no" | bash dos_ot_network.sh 2>&1 | tee -a "$REPORT_FILE" || echo "[!] DoS cancelado"

echo ""
echo "[5/5] Verificando detección SIEM..."
if command -v python3 &> /dev/null; then
    python3 verify_detection.py 2>&1 | tee -a "$REPORT_FILE" || echo "[!] Verificación SIEM falló"
else
    echo "[!] Python3 no disponible, saltando verificación" | tee -a "$REPORT_FILE"
fi

echo ""
echo "========================================"
echo "[✓] Todas las simulaciones completadas"
echo "[+] Reporte generado: $REPORT_FILE"
echo ""
echo "Verificar alertas en Kibana: http://localhost:5601"
echo "Alertas esperadas:"
echo "  - AUTH-001: Multiple Failed Login Attempts"
echo "  - SCAN-001: Security Scanner Detected"
echo "  - RATE-001: Rate Limit Exceeded"
echo "========================================"
