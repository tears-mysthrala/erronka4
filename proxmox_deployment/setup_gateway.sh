#!/bin/bash
# ZG-Gateway Configuration Script
# Router/Firewall con NFTables y DHCP

set -e

echo "=========================================="
echo "🔧 CONFIGURANDO ZG-GATEWAY"
echo "=========================================="

# Detectar interfaces
WAN_IF="ens18"
LAN_IF="ens19"

echo "📡 Configurando interfaces: WAN=$WAN_IF, LAN=$LAN_IF"

# Actualizar e instalar paquetes
apt update && apt install -y vim curl wget nftables isc-dhcp-server net-tools iputils-ping

# Configurar interfaces de red
cat > /etc/network/interfaces <<'EOF'
source /etc/network/interfaces.d/*

auto lo
iface lo inet loopback

# WAN - DHCP
allow-hotplug ens18
iface ens18 inet dhcp

# LAN - Static con múltiples VLANs
allow-hotplug ens19
iface ens19 inet static
    address 192.168.1.1
    netmask 255.255.0.0
    up ip addr add 192.168.10.1/24 dev ens19
    up ip addr add 192.168.20.1/24 dev ens19
    up ip addr add 192.168.50.1/24 dev ens19
    up ip addr add 192.168.200.1/24 dev ens19
    down ip addr del 192.168.10.1/24 dev ens19
    down ip addr del 192.168.20.1/24 dev ens19
    down ip addr del 192.168.50.1/24 dev ens19
    down ip addr del 192.168.200.1/24 dev ens19
EOF

# Habilitar forwarding
echo "net.ipv4.ip_forward=1" > /etc/sysctl.d/99-routing.conf
sysctl -p /etc/sysctl.d/99-routing.conf

# Configurar DHCP Server
sed -i 's/INTERFACESv4=""/INTERFACESv4="ens19"/' /etc/default/isc-dhcp-server

cat > /etc/dhcp/dhcpd.conf <<'EOF'
default-lease-time 600;
max-lease-time 7200;
authoritative;

# VLAN 10 - Management/SecOps
subnet 192.168.10.0 netmask 255.255.255.0 {
  range 192.168.10.100 192.168.10.200;
  option routers 192.168.10.1;
  option domain-name-servers 8.8.8.8, 1.1.1.1;
  option domain-name "zabala.local";
}

# VLAN 20 - IT/Application
subnet 192.168.20.0 netmask 255.255.255.0 {
  range 192.168.20.100 192.168.20.200;
  option routers 192.168.20.1;
  option domain-name-servers 8.8.8.8, 1.1.1.1;
  option domain-name "zabala.local";
}

# VLAN 50 - OT/Industrial
subnet 192.168.50.0 netmask 255.255.255.0 {
  range 192.168.50.100 192.168.50.200;
  option routers 192.168.50.1;
  option domain-name-servers 8.8.8.8;
}

# VLAN 200 - Clients
subnet 192.168.200.0 netmask 255.255.255.0 {
  range 192.168.200.100 192.168.200.200;
  option routers 192.168.200.1;
  option domain-name-servers 8.8.8.8, 1.1.1.1;
}
EOF

# Configurar NFTables Firewall
cat > /etc/nftables.conf <<'EOF'
#!/usr/sbin/nft -f

flush ruleset

table ip filter {
    chain input {
        type filter hook input priority 0; policy drop;
        
        # Loopback
        iifname "lo" accept
        
        # Conexiones establecidas
        ct state established,related accept
        
        # ICMP
        ip protocol icmp accept
        
        # SSH desde cualquier interfaz
        tcp dport 22 accept
        
        # Denegar resto
    }
    
    chain forward {
        type filter hook forward priority 0; policy drop;
        
        # Conexiones establecidas
        ct state established,related accept
        
        # VLAN 10 (SecOps) -> VLAN 20 (App) solo puertos web
        ip saddr 192.168.10.0/24 ip daddr 192.168.20.0/24 tcp dport { 80, 443 } accept
        
        # VLAN 20 (App) -> VLAN 20 (App) base de datos
        ip saddr 192.168.20.0/24 ip daddr 192.168.20.0/24 tcp dport { 5432, 6379 } accept
        
        # VLAN 200 (Clientes) -> Internet y servicios permitidos
        ip saddr 192.168.200.0/24 accept
        
        # VLAN 20 -> Internet
        ip saddr 192.168.20.0/24 oifname "ens18" accept
        
        # VLAN 10 -> Internet
        ip saddr 192.168.10.0/24 oifname "ens18" accept
        
        # VLAN 50 (OT) -> Aislado, solo respuestas
        ip saddr 192.168.50.0/24 ct state established,related accept
    }
    
    chain output {
        type filter hook output priority 0; policy accept;
    }
}

table ip nat {
    chain postrouting {
        type nat hook postrouting priority 100; policy accept;
        oifname "ens18" masquerade
    }
}
EOF

# Aplicar configuración
systemctl restart networking || true
systemctl restart isc-dhcp-server
nft -f /etc/nftables.conf
systemctl enable nftables
systemctl enable isc-dhcp-server

echo ""
echo "=========================================="
echo "✅ ZG-GATEWAY CONFIGURADO"
echo "=========================================="
echo "Interfaces:"
echo "  WAN (ens18): DHCP"
ip addr show ens18 2>/dev/null | grep "inet " || echo "    (esperando DHCP...)"
echo ""
echo "  LAN (ens19):"
ip addr show ens19 2>/dev/null | grep "inet " || echo "    No configurado"
echo ""
echo "VLANs configuradas:"
echo "  - 192.168.10.0/24 (SecOps/Management)"
echo "  - 192.168.20.0/24 (IT/Application)"
echo "  - 192.168.50.0/24 (OT/Industrial)"
echo "  - 192.168.200.0/24 (Clients)"
echo ""
echo "Servicios activos:"
echo "  - DHCP Server"
echo "  - NFTables Firewall"
echo "  - IP Forwarding/NAT"
