#!/bin/bash
################################################################################
# Firewall Rules - Zabala Gailetak
# Segmentación IT/OT con iptables
# IEC 62443 / NIS2 Compliant
################################################################################

# Limpiar reglas existentes
iptables -F
iptables -X
iptables -Z

# Política por defecto: DENEGAR TODO
iptables -P INPUT DROP
iptables -P FORWARD DROP
iptables -P OUTPUT DROP

################################################################################
# CHAIN PERSONALIZADAS
################################################################################

iptables -N LOG_DROP
iptables -A LOG_DROP -j LOG --log-prefix "[FIREWALL DROP] " --log-level 4
iptables -A LOG_DROP -j DROP

iptables -N LOG_ACCEPT
iptables -A LOG_ACCEPT -j LOG --log-prefix "[FIREWALL ACCEPT] " --log-level 4
iptables -A LOG_ACCEPT -j ACCEPT

################################################################################
# LOOPBACK - Permitir todo tráfico local
################################################################################

iptables -A INPUT -i lo -j ACCEPT
iptables -A OUTPUT -o lo -j ACCEPT

################################################################################
# ESTABLISHED,RELATED - Permitir conexiones existentes
################################################################################

iptables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
iptables -A OUTPUT -m state --state ESTABLISHED,RELATED -j ACCEPT

################################################################################
# VLAN 10: MANAGEMENT (10.10.10.0/24)
# Acceso administrativo restringido
################################################################################

# SSH solo desde Management a Servers
iptables -A INPUT -s 10.10.10.0/24 -d 10.10.20.0/24 -p tcp --dport 22 -m state --state NEW -j ACCEPT
iptables -A OUTPUT -s 10.10.20.0/24 -d 10.10.10.0/24 -p tcp --sport 22 -m state --state ESTABLISHED -j ACCEPT

# SNMP para monitoreo
iptables -A INPUT -s 10.10.10.0/24 -p udp --dport 161 -j ACCEPT
iptables -A INPUT -s 10.10.10.0/24 -p udp --dport 162 -j ACCEPT

################################################################################
# VLAN 20: SERVERS (10.10.20.0/24)
# Servidores IT - Aplicaciones empresariales
################################################################################

# HTTP/HTTPS público
iptables -A INPUT -p tcp --dport 80 -m state --state NEW -j ACCEPT
iptables -A INPUT -p tcp --dport 443 -m state --state NEW -j ACCEPT

# PostgreSQL (solo interno)
iptables -A INPUT -s 10.10.20.0/24 -p tcp --dport 5432 -j ACCEPT
iptables -A INPUT -s 10.10.10.0/24 -p tcp --dport 5432 -j ACCEPT

# Redis (solo interno)
iptables -A INPUT -s 10.10.20.0/24 -p tcp --dport 6379 -j ACCEPT

# DNS
iptables -A OUTPUT -p udp --dport 53 -j ACCEPT
iptables -A OUTPUT -p tcp --dport 53 -j ACCEPT

# NTP
iptables -A OUTPUT -p udp --dport 123 -j ACCEPT

# HTTP/HTTPS saliente (updates, APIs)
iptables -A OUTPUT -p tcp --dport 80 -j ACCEPT
iptables -A OUTPUT -p tcp --dport 443 -j ACCEPT

################################################################################
# VLAN 50: OT (10.10.50.0/24) 
# RED INDUSTRIAL - Aislada de IT
################################################################################

# DENEGAR TODO TRÁFICO DIRECTO IT ↔ OT
iptables -A FORWARD -s 10.10.20.0/24 -d 10.10.50.0/24 -j LOG_DROP
iptables -A FORWARD -s 10.10.50.0/24 -d 10.10.20.0/24 -j LOG_DROP

# Permitir solo Modbus TCP proxy (puerto 1502) desde Gateway OT
iptables -A FORWARD -s 10.10.20.10/32 -d 10.10.50.0/24 -p tcp --dport 1502 -j ACCEPT
iptables -A FORWARD -s 10.10.50.0/24 -d 10.10.20.10/32 -p tcp --sport 1502 -j ACCEPT

# Permitir OPC-UA seguro
iptables -A FORWARD -s 10.10.20.10/32 -d 10.10.50.0/24 -p tcp --dport 4840 -j ACCEPT

################################################################################
# VLAN 100: DMZ (10.10.100.0/24)
# Servicios públicos expuestos
################################################################################

# Proxy inverso Nginx
iptables -A INPUT -s 10.10.100.0/24 -p tcp --dport 8080 -j ACCEPT

# VPN Gateway
iptables -A INPUT -p udp --dport 1194 -j ACCEPT

################################################################################
# PROTECCIÓN ANTI-SCANNING
################################################################################

# Detectar y bloquear port scanning
iptables -A INPUT -p tcp --tcp-flags ALL NONE -j LOG_DROP
iptables -A INPUT -p tcp --tcp-flags SYN,FIN SYN,FIN -j LOG_DROP
iptables -A INPUT -p tcp --tcp-flags SYN,RST SYN,RST -j LOG_DROP
iptables -A INPUT -p tcp --tcp-flags FIN,RST FIN,RST -j LOG_DROP
iptables -A INPUT -p tcp --tcp-flags ACK,FIN FIN -j LOG_DROP
iptables -A INPUT -p tcp --tcp-flags ACK,PSH PSH -j LOG_DROP
iptables -A INPUT -p tcp --tcp-flags ACK,URG URG -j LOG_DROP

################################################################################
# PROTECCIÓN DDoS BÁSICA
################################################################################

# Limitar conexiones SYN
iptables -A INPUT -p tcp --syn -m limit --limit 2/second --limit-burst 6 -j ACCEPT
iptables -A INPUT -p tcp --syn -j LOG_DROP

# Limitar ping (ICMP)
iptables -A INPUT -p icmp --icmp-type echo-request -m limit --limit 1/second -j ACCEPT
iptables -A INPUT -p icmp --icmp-type echo-request -j LOG_DROP

################################################################################
# BLOQUEAR IP MALICIOSAS CONOCIDAS (Ejemplo)
################################################################################

# Cargar lista de IPs bloqueadas
# iptables -A INPUT -s 192.168.1.100 -j DROP

################################################################################
# LOGGING DE TRÁFICO SOSPECHOSO
################################################################################

# Loggear todo lo demás antes de drop
iptables -A INPUT -j LOG --log-prefix "[FIREWALL INPUT] " --log-level 4
iptables -A FORWARD -j LOG --log-prefix "[FIREWALL FWD] " --log-level 4

################################################################################
# GUARDAR REGLAS
################################################################################

# Guardar configuración (Debian/Ubuntu)
iptables-save > /etc/iptables/rules.v4

# O usar netfilter-persistent
# netfilter-persistent save

echo "[OK] Firewall rules applied successfully"
echo "[INFO] IT/OT segmentation active"
echo "[INFO] Logging enabled to /var/log/kern.log"
