#!/bin/bash
# =============================================================================
# ZABALA GAILETAK - PROXMOX DEPLOYMENT SCRIPT
# Ejecutar este script directamente en el servidor Proxmox
# =============================================================================

set -e

# Colores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuración
NODE="pve"  # Cambiar si tu nodo tiene otro nombre
STORAGE="local-lvm"  # Cambiar según tu almacenamiento

echo -e "${GREEN}"
echo "╔════════════════════════════════════════════════════════════════╗"
echo "║         🏭 ZABALA GAILETAK - PROXMOX DEPLOYMENT               ║"
echo "║              Infraestructura Completa de RRHH                  ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo -e "${NC}"
echo ""

# Verificar que estamos en Proxmox
if ! command -v qm &> /dev/null; then
    echo -e "${RED}❌ Error: Este script debe ejecutarse en un servidor Proxmox${NC}"
    exit 1
fi

echo -e "${BLUE}📋 Resumen de VMs a crear:${NC}"
echo ""
printf "%-15s %-6s %-6s %-8s %-10s %-20s\n" "NOMBRE" "VMID" "vCPUs" "RAM" "DISCO" "DESCRIPCIÓN"
echo "--------------------------------------------------------------------------------------"
printf "%-15s %-6s %-6s %-8s %-10s %-20s\n" "ZG-Gateway" "201" "1" "1GB" "10GB" "Router/Firewall"
printf "%-15s %-6s %-6s %-8s %-10s %-20s\n" "ZG-App" "202" "2" "4GB" "20GB" "PHP + Nginx"
printf "%-15s %-6s %-6s %-8s %-10s %-20s\n" "ZG-Data" "203" "2" "4GB" "20GB" "PostgreSQL + Redis"
printf "%-15s %-6s %-6s %-8s %-10s %-20s\n" "ZG-SecOps" "204" "4" "8GB" "40GB" "Wazuh + Honeypots"
printf "%-15s %-6s %-6s %-8s %-10s %-20s\n" "ZG-OT" "205" "1" "2GB" "10GB" "OpenPLC"
printf "%-15s %-6s %-6s %-8s %-10s %-20s\n" "ZG-Client" "206" "2" "4GB" "20GB" "Workstation"
echo ""

read -p "¿Continuar con el despliegue? (s/n): " confirm
if [[ $confirm != "s" && $confirm != "S" ]]; then
    echo -e "${YELLOW}⚠️  Despliegue cancelado${NC}"
    exit 0
fi

echo ""
echo -e "${BLUE}🖥️  Creando máquinas virtuales...${NC}"
echo ""

# Función para crear VM
create_vm() {
    local vmid=$1
    local name=$2
    local cores=$3
    local memory=$4
    local disk=$5
    local desc=$6
    
    # Verificar si la VM ya existe
    if qm status $vmid &>/dev/null; then
        echo -e "${YELLOW}⚠️  $name (VMID: $vmid) ya existe, omitiendo...${NC}"
        return 0
    fi
    
    echo -e "${BLUE}📦 Creando $name (VMID: $vmid)...${NC}"
    
    # Crear VM
    qm create $vmid \
        --name "$name" \
        --cores $cores \
        --memory $((memory * 1024)) \
        --net0 virtio,bridge=vmbr0 \
        --scsihw virtio-scsi-single \
        --ostype l26 \
        --description "$desc" \
        --agent 1
    
    # Agregar disco
    qm set $vmid --scsi0 ${STORAGE}:${disk},iothread=1
    
    # Agregar segunda interfaz de red para ZG-Gateway
    if [ "$name" == "ZG-Gateway" ]; then
        qm set $vmid --net1 virtio,bridge=vmbr1
        echo -e "   ${GREEN}✓ Segunda interfaz de red agregada${NC}"
    fi
    
    # Configurar boot desde red (para PXE) o disco
    qm set $vmid --boot order=scsi0
    
    echo -e "   ${GREEN}✓ VM $name creada${NC}"
}

# Crear todas las VMs
create_vm 201 "ZG-Gateway" 1 1024 10 "Router/Firewall - NFTables DHCP"
create_vm 202 "ZG-App" 2 4096 20 "PHP 8.4 + Nginx Application Server"
create_vm 203 "ZG-Data" 2 4096 20 "PostgreSQL 16 + Redis 7"
create_vm 204 "ZG-SecOps" 4 8192 40 "Wazuh SIEM + Honeypots"
create_vm 205 "ZG-OT" 1 2048 10 "OpenPLC Industrial Control"
create_vm 206 "ZG-Client" 2 4096 20 "Client Workstation"

echo ""
echo -e "${GREEN}✅ VMs creadas exitosamente!${NC}"
echo ""

# Mostrar resumen
echo -e "${BLUE}📊 Resumen de VMs:${NC}"
echo ""
qm list | grep -E "(VMID|201|202|203|204|205|206)"
echo ""

echo -e "${YELLOW}⚠️  SIGUIENTES PASOS:${NC}"
echo ""
echo "1. Descargar ISO de Debian 12:"
echo "   cd /var/lib/vz/template/iso/"
echo "   wget https://cdimage.debian.org/debian-cd/current/amd64/iso-cd/debian-12.x.x-amd64-netinst.iso"
echo ""
echo "2. Asignar ISO a cada VM y arrancar:"
echo "   qm set 201 --ide2 local:iso/debian-12.x.x-amd64-netinst.iso"
echo "   qm start 201"
echo ""
echo "3. Instalar Debian 12 en cada VM con la siguiente configuración:"
echo ""
echo "   ┌─────────────────┬─────────────────┬─────────────────┐"
echo "   │ VM              │ IP              │ Gateway         │"
echo "   ├─────────────────┼─────────────────┼─────────────────┤"
echo "   │ ZG-Gateway      │ DHCP (WAN)      │ -               │"
echo "   │                 │ 192.168.1.1/16  │ -               │"
echo "   ├─────────────────┼─────────────────┼─────────────────┤"
echo "   │ ZG-App          │ 192.168.20.10   │ 192.168.20.1    │"
echo "   ├─────────────────┼─────────────────┼─────────────────┤"
echo "   │ ZG-Data         │ 192.168.20.20   │ 192.168.20.1    │"
echo "   ├─────────────────┼─────────────────┼─────────────────┤"
echo "   │ ZG-SecOps       │ 192.168.10.10   │ 192.168.10.1    │"
echo "   ├─────────────────┼─────────────────┼─────────────────┤"
echo "   │ ZG-OT           │ 192.168.50.10   │ 192.168.50.1    │"
echo "   ├─────────────────┼─────────────────┼─────────────────┤"
echo "   │ ZG-Client       │ 192.168.200.10  │ 192.168.200.1   │"
echo "   └─────────────────┴─────────────────┴─────────────────┘"
echo ""
echo "4. Después de instalar Debian, ejecutar los scripts de configuración:"
echo "   - scp setup_gateway.sh root@192.168.1.1:/root/"
echo "   - scp setup_data.sh root@192.168.20.20:/root/"
echo "   - scp setup_app.sh root@192.168.20.10:/root/"
echo "   - scp setup_secops.sh root@192.168.10.10:/root/"
echo "   - scp setup_ot.sh root@192.168.50.10:/root/"
echo "   - scp setup_client.sh root@192.168.200.10:/root/"
echo ""
echo "5. Ejecutar cada script en su respectiva VM"
echo ""
echo -e "${GREEN}🎉 Despliegue de infraestructura completado!${NC}"
