#!/bin/bash
# ZG-OT Configuration Script
# OpenPLC - Industrial Control Simulation

set -e

echo "=========================================="
echo "🔧 CONFIGURANDO ZG-OT"
echo "=========================================="

# Configurar IP estática
cat > /etc/network/interfaces <<'EOF'
source /etc/network/interfaces.d/*

auto lo
iface lo inet loopback

allow-hotplug ens18
iface ens18 inet static
    address 192.168.50.10
    netmask 255.255.255.0
    gateway 192.168.50.1
    dns-nameservers 8.8.8.8
EOF

systemctl restart networking || true

# Instalar Docker
echo "📦 Instalando Docker..."
apt update
apt install -y ca-certificates curl
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh

# Crear directorio para OpenPLC
mkdir -p /opt/openplc
cd /opt/openplc

# Docker Compose para OpenPLC
cat > docker-compose.yml <<'EOF'
version: '3.8'

services:
  openplc:
    image: thiagoralves/openplc:v3
    container_name: ot-openplc
    restart: always
    ports:
      - "8080:8080"   # Web Interface
      - "502:502"     # Modbus TCP
      - "20000:20000" # Modbus RTU over TCP
    privileged: true
    volumes:
      - ./st_files:/openplc/scripts
      - ./persistent:/openplc/persistent
    environment:
      - OpenPLC_RUNTIME=openplc
    networks:
      - ot_network

  # ScadaBR para HMI
  scadabr:
    image: scadabr/scadabr:latest
    container_name: ot-scadabr
    restart: always
    ports:
      - "9090:8080"
    environment:
      - DATABASE_URL=jdbc:h2:file:/opt/scadabr/database/scadabr
    volumes:
      - scadabr_data:/opt/scadabr/database
    networks:
      - ot_network

volumes:
  scadabr_data:

networks:
  ot_network:
    driver: bridge
EOF

# Crear directorios
mkdir -p st_files persistent

# Crear programa de ejemplo para línea de producción de galletas
cat > st_files/cookie_production.st <<'EOF'
(*
 * Zabala Gailetak - Línea de Producción de Galletas
 * Simulación PLC con OpenPLC
 *)

PROGRAM CookieProduction
  VAR
    (* Entradas *)
    StartButton : BOOL;
    StopButton : BOOL;
    EmergencyStop : BOOL;
    TempSensor : REAL;
    SpeedSensor : REAL;
    
    (* Salidas *)
    ConveyorMotor : BOOL;
    OvenHeater : BOOL;
    MixerMotor : BOOL;
    GreenLight : BOOL;
    RedLight : BOOL;
    
    (* Variables internas *)
    SystemRunning : BOOL := FALSE;
    OvenTemp : REAL := 25.0;
    TargetTemp : REAL := 180.0;
    ProductionCount : INT := 0;
  END_VAR

  (* Lógica principal *)
  IF EmergencyStop THEN
    SystemRunning := FALSE;
  END_IF;

  IF StartButton AND NOT EmergencyStop THEN
    SystemRunning := TRUE;
  END_IF;

  IF StopButton OR EmergencyStop THEN
    SystemRunning := FALSE;
  END_IF;

  (* Control del horno *)
  IF SystemRunning THEN
    IF OvenTemp < TargetTemp THEN
      OvenHeater := TRUE;
    ELSE
      OvenHeater := FALSE;
    END_IF;
    
    (* Control del transportador *)
    IF OvenTemp >= TargetTemp - 10.0 THEN
      ConveyorMotor := TRUE;
      MixerMotor := TRUE;
      ProductionCount := ProductionCount + 1;
    END_IF;
    
    GreenLight := TRUE;
    RedLight := FALSE;
  ELSE
    ConveyorMotor := FALSE;
    MixerMotor := FALSE;
    OvenHeater := FALSE;
    GreenLight := FALSE;
    RedLight := TRUE;
  END_IF;

  (* Simulación de temperatura *)
  IF OvenHeater THEN
    OvenTemp := OvenTemp + 0.5;
  ELSE
    OvenTemp := OvenTemp - 0.2;
  END_IF;

  (* Limitar temperatura *)
  IF OvenTemp < 25.0 THEN
    OvenTemp := 25.0;
  END_IF;

END_PROGRAM

CONFIG
  RESOURCE resource1 ON PLC
    TASK task1(INTERVAL := T#100ms, PRIORITY := 0);
    PROGRAM instance1 WITH task1 : CookieProduction;
  END_RESOURCE
END_CONFIG
EOF

# Iniciar OpenPLC
docker compose up -d

echo ""
echo "=========================================="
echo "✅ ZG-OT CONFIGURADO"
echo "=========================================="
echo "OpenPLC:"
echo "  - Web Interface: http://192.168.50.10:8080"
echo "  - Modbus TCP: 192.168.50.10:502"
echo "  - Default login: openplc / openplc"
echo ""
echo "ScadaBR HMI:"
echo "  - Web Interface: http://192.168.50.10:9090"
echo "  - Default login: admin / admin"
echo ""
echo "Archivos:"
echo "  - ST Programs: /opt/openplc/st_files/"
echo "  - Persistent data: /opt/openplc/persistent/"
echo ""
echo "⚠️  ADVERTENCIA: Red OT aislada (VLAN 50)"
echo "   Sin acceso directo a Internet por seguridad"
