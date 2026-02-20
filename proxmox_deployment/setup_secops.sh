#!/bin/bash
# ZG-SecOps Configuration Script
# Wazuh SIEM + Honeypots

set -e

echo "=========================================="
echo "🔧 CONFIGURANDO ZG-SECOPS"
echo "=========================================="

# Configurar IP estática
cat > /etc/network/interfaces <<'EOF'
source /etc/network/interfaces.d/*

auto lo
iface lo inet loopback

allow-hotplug ens18
iface ens18 inet static
    address 192.168.10.10
    netmask 255.255.255.0
    gateway 192.168.10.1
    dns-nameservers 8.8.8.8 1.1.1.1
EOF

systemctl restart networking || true

# Instalar Docker
echo "📦 Instalando Docker..."
apt update
apt install -y ca-certificates curl gnupg git
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh

# Configurar sysctl para Elasticsearch
sysctl -w vm.max_map_count=262144
echo "vm.max_map_count=262144" >> /etc/sysctl.conf

# Crear directorio para Wazuh
mkdir -p /opt/wazuh
cd /opt/wazuh

# Descargar Wazuh Docker
git clone https://github.com/wazuh/wazuh-docker.git -b v4.9.2 --depth 1 .

# Generar certificados
docker compose -f generate-indexer-certs.yml run --rm generator

# Iniciar Wazuh
docker compose up -d

# Crear directorio para Honeypots
mkdir -p /opt/honeypots
cd /opt/honeypots

# Docker Compose para Honeypots
cat > docker-compose.yml <<'EOF'
version: '3'

services:
  # Conpot - Industrial Control System Honeypot
  conpot:
    image: honeytrek/conpot:latest
    container_name: honeypot-conpot
    restart: always
    ports:
      - "5020:502"      # Modbus
      - "1610:161/udp"  # SNMP
      - "1020:102"      # S7comm
      - "44818:44818"   # Ethernet/IP
    volumes:
      - conpot_data:/opt/conpot/conpot
    networks:
      - honeynet

  # Dionaea - Captura malware
  dionaea:
    image: dinotools/dionaea:latest
    container_name: honeypot-dionaea
    restart: always
    ports:
      - "21:21"
      - "23:23"
      - "445:445"
      - "1433:1433"
      - "3306:3306"
      - "5432:5432"
    volumes:
      - dionaea_data:/opt/dionaea
    cap_add:
      - NET_ADMIN
    networks:
      - honeynet

  # Cowrie - SSH/Telnet Honeypot
  cowrie:
    image: cowrie/cowrie:latest
    container_name: honeypot-cowrie
    restart: always
    ports:
      - "2222:2222"
      - "2223:2223"
    volumes:
      - cowrie_data:/cowrie/cowrie-git/var
    networks:
      - honeynet

volumes:
  conpot_data:
  dionaea_data:
  cowrie_data:

networks:
  honeynet:
    driver: bridge
EOF

# Iniciar Honeypots
docker compose up -d

echo ""
echo "=========================================="
echo "✅ ZG-SECOPS CONFIGURADO"
echo "=========================================="
echo "Wazuh SIEM:"
echo "  - Dashboard: https://192.168.10.10"
echo "  - Usuario: admin"
echo "  - Password: (ver en /opt/wazuh)")
echo ""
echo "Honeypots:"
echo "  - Conpot (Modbus/SNMP): 192.168.10.10:5020, 1610"
echo "  - Dionaea (SMB/SQL): 192.168.10.10:21, 445, 1433, 3306"
echo "  - Cowrie (SSH/Telnet): 192.168.10.10:2222, 2223"
echo ""
echo "Logs:"
echo "  - Wazuh: /opt/wazuh"
echo "  - Honeypots: /opt/honeypots"
