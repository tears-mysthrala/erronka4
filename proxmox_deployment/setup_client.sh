#!/bin/bash
# ZG-Client Configuration Script
# Workstation para testing

set -e

echo "=========================================="
echo "🔧 CONFIGURANDO ZG-CLIENT"
echo "=========================================="

# Configurar IP estática
cat > /etc/network/interfaces <<'EOF'
source /etc/network/interfaces.d/*

auto lo
iface lo inet loopback

allow-hotplug ens18
iface ens18 inet static
    address 192.168.200.10
    netmask 255.255.255.0
    gateway 192.168.200.1
    dns-nameservers 8.8.8.8 1.1.1.1
EOF

systemctl restart networking || true

# Instalar herramientas de testing
echo "📦 Instalando herramientas..."
apt update
apt install -y \
    curl wget \
    firefox-esr \
    wireshark \
    nmap \
    netcat-openbsd \
    sqlmap \
    git \
    vim \
    htop \
    tcpdump \
    net-tools

# Crear usuario de trabajo
useradd -m -s /bin/bash zabala || true
echo "zabala:zabala123" | chpasswd
usermod -aG sudo zabala

# Script de ayuda para testing
cat > /home/zabala/test_services.sh <<'EOF'
#!/bin/bash
# Script de prueba de servicios Zabala Gailetak

echo "=========================================="
echo "🧪 TEST DE SERVICIOS ZABALA GAILETAK"
echo "=========================================="
echo ""

echo "1. Testing Gateway (192.168.200.1)..."
ping -c 1 192.168.200.1 > /dev/null 2>&1 && echo "   ✅ Gateway responde" || echo "   ❌ Gateway no responde"
echo ""

echo "2. Testing ZG-App (192.168.20.10)..."
ping -c 1 192.168.20.10 > /dev/null 2>&1 && echo "   ✅ App Server responde" || echo "   ❌ App Server no responde"
curl -s http://192.168.20.10 > /dev/null 2>&1 && echo "   ✅ HTTP 80 responde" || echo "   ❌ HTTP 80 no responde"
echo ""

echo "3. Testing ZG-Data (192.168.20.20)..."
ping -c 1 192.168.20.20 > /dev/null 2>&1 && echo "   ✅ Data Server responde" || echo "   ❌ Data Server no responde"
nc -z 192.168.20.20 5432 > /dev/null 2>&1 && echo "   ✅ PostgreSQL (5432) abierto" || echo "   ❌ PostgreSQL cerrado"
nc -z 192.168.20.20 6379 > /dev/null 2>&1 && echo "   ✅ Redis (6379) abierto" || echo "   ❌ Redis cerrado"
echo ""

echo "4. Testing ZG-SecOps (192.168.10.10)..."
ping -c 1 192.168.10.10 > /dev/null 2>&1 && echo "   ✅ SecOps responde" || echo "   ❌ SecOps no responde"
curl -sk https://192.168.10.10 > /dev/null 2>&1 && echo "   ✅ Wazuh Dashboard (443) responde" || echo "   ❌ Wazuh Dashboard no responde"
echo ""

echo "5. Testing ZG-OT (192.168.50.10)..."
ping -c 1 192.168.50.10 > /dev/null 2>&1 && echo "   ✅ OT Server responde" || echo "   ❌ OT Server no responde"
curl -s http://192.168.50.10:8080 > /dev/null 2>&1 && echo "   ✅ OpenPLC (8080) responde" || echo "   ❌ OpenPLC no responde"
nc -z 192.168.50.10 502 > /dev/null 2>&1 && echo "   ✅ Modbus TCP (502) abierto" || echo "   ❌ Modbus TCP cerrado"
echo ""

echo "6. Testing Internet..."
ping -c 1 8.8.8.8 > /dev/null 2>&1 && echo "   ✅ Internet (8.8.8.8) responde" || echo "   ❌ Sin acceso a Internet"
echo ""

echo "=========================================="
echo "✅ TEST COMPLETADO"
echo "=========================================="
EOF

chmod +x /home/zabala/test_services.sh
chown zabala:zabala /home/zabala/test_services.sh

# Configurar bookmark de Firefox para el portal
cat > /usr/share/firefox-esr/distribution/policies.json <<'EOF'
{
  "policies": {
    "Bookmarks": [
      {
        "Title": "Zabala Gailetak HR Portal",
        "URL": "http://192.168.20.10",
        "Placement": "toolbar"
      },
      {
        "Title": "Wazuh SIEM",
        "URL": "https://192.168.10.10",
        "Placement": "toolbar"
      },
      {
        "Title": "OpenPLC",
        "URL": "http://192.168.50.10:8080",
        "Placement": "toolbar"
      }
    ]
  }
}
EOF

echo ""
echo "=========================================="
echo "✅ ZG-CLIENT CONFIGURADO"
echo "=========================================="
echo "Usuario: zabala / zabala123"
echo "IP: 192.168.200.10"
echo ""
echo "Herramientas instaladas:"
echo "  - Firefox ESR"
echo "  - Wireshark"
echo "  - Nmap"
echo "  - SQLMap"
echo "  - Netcat"
echo "  - Tcpdump"
echo ""
echo "Script de test:"
echo "  /home/zabala/test_services.sh"
echo ""
echo "Bookmarks Firefox configurados:"
echo "  - Zabala Gailetak HR Portal"
echo "  - Wazuh SIEM"
echo "  - OpenPLC"
