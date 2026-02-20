#!/bin/bash
# Script completo de setup para Zabala Gailetak en Proxmox
# Ejecutar en el servidor Proxmox

set -e

STORAGE="local-lvm"
SSH_KEY="ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIBCXuzD1iUrrIQq4SSuvR/z4G1UmJPmgtF9eFUm9PTfx zabala_deploy@proxmox"

echo "=========================================="
echo "🏭 ZABALA GAILETAK - SETUP COMPLETO"
echo "=========================================="
echo ""

# 1. Descargar Debian 12 Cloud Image
echo "📥 Descargando Debian 12 LTS Cloud Image..."
cd /var/lib/vz/template/iso/

if [ ! -f "debian-12-genericcloud-amd64.qcow2" ]; then
    wget -q --show-progress \
        https://cloud.debian.org/images/cloud/bookworm/latest/debian-12-genericcloud-amd64.qcow2
    echo "✅ Imagen descargada"
else
    echo "✅ Imagen ya existe, omitiendo descarga"
fi

# 2. Crear Template VM
echo ""
echo "📦 Creando Template VM..."

# Eliminar template anterior si existe
qm destroy 9000 --purge 2>/dev/null || true

# Crear VM template
qm create 9000 \
    --name debian-12-cloud-template \
    --memory 2048 \
    --cores 2 \
    --cpu x86-64-v2-AES \
    --net0 virtio,bridge=vmbr0 \
    --scsihw virtio-scsi-single \
    --agent enabled=1

# Importar disco
qm importdisk 9000 debian-12-genericcloud-amd64.qcow2 ${STORAGE}

# Configurar disco
qm set 9000 --scsi0 ${STORAGE}:vm-9000-disk-0,discard=on,ssd=1

# Configurar cloud-init drive
qm set 9000 --ide2 ${STORAGE}:cloudinit

# Configurar boot
qm set 9000 --boot order=scsi0

# Convertir a template
echo "📝 Convirtiendo a template..."
qm template 9000

echo "✅ Template 9000 creado"

# 3. Crear VMs desde template
echo ""
echo "🚀 Creando VMs desde template..."

declare -A VM_CONFIGS
VM_CONFIGS[201]="zg-gateway|1|1024|10|192.168.1.1/16|"
VM_CONFIGS[202]="zg-app|2|4096|20|192.168.20.10/24|192.168.20.1"
VM_CONFIGS[203]="zg-data|2|4096|20|192.168.20.20/24|192.168.20.1"
VM_CONFIGS[204]="zg-secops|4|8192|40|192.168.10.10/24|192.168.10.1"
VM_CONFIGS[205]="zg-ot|1|2048|10|192.168.50.10/24|192.168.50.1"
VM_CONFIGS[206]="zg-client|2|4096|20|192.168.200.10/24|192.168.200.1"

for VMID in 201 202 203 204 205 206; do
    IFS='|' read -r NAME CORES MEM DISK IP GW <<< "${VM_CONFIGS[$VMID]}"
    
    echo ""
    echo "📦 Creando VM $VMID - $NAME..."
    
    # Eliminar VM si existe
    qm destroy $VMID --purge 2>/dev/null || true
    
    # Clonar desde template
    qm clone 9000 $VMID --name $NAME --full
    
    # Configurar recursos
    qm set $VMID --cores $CORES --memory $MEM
    
    # Redimensionar disco
    qm disk resize $VMID scsi0 ${DISK}G
    
    # Configurar cloud-init
    qm set $VMID --ciuser zabala
    qm set $VMID --cipassword 'Zabala2026!'
    qm set $VMID --sshkeys "$SSH_KEY"
    
    # Configurar IP
    if [ -z "$GW" ]; then
        # Gateway usa DHCP en ens18 y estática en ens19
        qm set $VMID --ipconfig0 "ip=dhcp"
    else
        qm set $VMID --ipconfig0 "ip=${IP},gw=${GW}"
    fi
    
    # Configurar segund interfaz para ZG-Gateway
    if [ "$VMID" == "201" ]; then
        qm set $VMID --net1 virtio,bridge=vmbr1
        # La segunda IP se configura manualmente después
    fi
    
    echo "  ✅ VM $VMID creada: $NAME ($IP)"
done

echo ""
echo "=========================================="
echo "✅ SETUP COMPLETADO"
echo "=========================================="
echo ""
echo "📊 Resumen de VMs:"
qm list | grep -E "(9000|201|202|203|204|205|206)"
echo ""
echo "🚀 Iniciar VMs con:"
echo "   for vm in 201 202 203 204 205 206; do qm start \$vm; done"
echo ""
echo "⏳ Después de iniciar, esperar 1-2 minutos para que cloud-init configure las VMs"
echo ""
echo "🔐 Credenciales:"
echo "   Usuario: zabala"
echo "   Password: Zabala2026!"
echo "   SSH Key configurada"
