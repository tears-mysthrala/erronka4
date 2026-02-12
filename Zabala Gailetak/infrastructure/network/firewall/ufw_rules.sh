#!/bin/bash
################################################################################
# UFW Firewall Rules - Zabala Gailetak
# Configuración simplificada para servidores Ubuntu
################################################################################

# Resetear UFW
ufw --force reset

# Política por defecto
ufw default deny incoming
ufw default allow outgoing

################################################################################
# REGLAS BÁSICAS
################################################################################

# SSH (solo desde red de gestión)
ufw allow from 10.10.10.0/24 to any port 22 proto tcp

# HTTP y HTTPS
ufw allow 80/tcp
ufw allow 443/tcp

################################################################################
# REGLAS APLICACIÓN HR PORTAL
################################################################################

# PHP-FPM (local only)
ufw allow from 127.0.0.1 to any port 9000 proto tcp

# PostgreSQL (red interna)
ufw allow from 10.10.20.0/24 to any port 5432 proto tcp

# Redis (local only)
ufw allow from 127.0.0.1 to any port 6379 proto tcp

################################################################################
# REGLAS MONITOREO
################################################################################

# Node Exporter (Prometheus)
ufw allow from 10.10.10.0/24 to any port 9100 proto tcp

################################################################################
# LIMITAR CONEXIONES (ANTI-BRUTEFORCE)
################################################################################

# Limitar intentos SSH
ufw limit 22/tcp

################################################################################
# HABILITAR LOGGING
################################################################################

ufw logging on

################################################################################
# ACTIVAR FIREWALL
################################################################################

echo "y" | ufw enable

################################################################################
# MOSTRAR ESTADO
################################################################################

ufw status verbose numbered

echo "[OK] UFW configured successfully"
