#!/bin/bash
# Script para configurar cloud-init en las VMs de Zabala Gailetak
# Ejecutar este script en el servidor Proxmox

set -e

STORAGE="local"
SNIPPETS_PATH="/var/lib/vz/snippets"
SSH_KEY="ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIBCXuzD1iUrrIQq4SSuvR/z4G1UmJPmgtF9eFUm9PTfx zabala_deploy@proxmox"

echo "=========================================="
echo "🔧 CONFIGURANDO CLOUD-INIT PARA VMs"
echo "=========================================="

# Crear directorio de snippets si no existe
mkdir -p ${SNIPPETS_PATH}

# Configurar cada VM
for VMID in 201 202 203 204 205 206; do
    echo ""
    echo "📦 Configurando VM $VMID..."
    
    case $VMID in
        201)
            HOSTNAME="zg-gateway"
            IP="192.168.1.1/16"
            GW=""
            ;;
        202)
            HOSTNAME="zg-app"
            IP="192.168.20.10/24"
            GW="192.168.20.1"
            ;;
        203)
            HOSTNAME="zg-data"
            IP="192.168.20.20/24"
            GW="192.168.20.1"
            ;;
        204)
            HOSTNAME="zg-secops"
            IP="192.168.10.10/24"
            GW="192.168.10.1"
            ;;
        205)
            HOSTNAME="zg-ot"
            IP="192.168.50.10/24"
            GW="192.168.50.1"
            ;;
        206)
            HOSTNAME="zg-client"
            IP="192.168.200.10/24"
            GW="192.168.200.1"
            ;;
    esac
    
    # Crear archivo network-config
    if [ "$VMID" == "201" ]; then
        # ZG-Gateway tiene configuración especial con 2 interfaces
        cat > ${SNIPPETS_PATH}/network-${VMID}.yaml <<EOF
version: 2
ethernets:
  ens18:
    dhcp4: true
  ens19:
    addresses:
      - 192.168.1.1/16
    nameservers:
      addresses: [8.8.8.8, 1.1.1.1]
EOF
    else
        cat > ${SNIPPETS_PATH}/network-${VMID}.yaml <<EOF
version: 2
ethernets:
  ens18:
    addresses:
      - ${IP}
    gateway4: ${GW}
    nameservers:
      addresses: [8.8.8.8, 1.1.1.1]
EOF
    fi
    
    # Crear archivo user-data
    cat > ${SNIPPETS_PATH}/user-${VMID}.yaml <<EOF
#cloud-config
hostname: ${HOSTNAME}
fqdn: ${HOSTNAME}.zabala.local
manage_etc_hosts: true

users:
  - name: zabala
    gecos: Zabala Admin
    sudo: ALL=(ALL) NOPASSWD:ALL
    shell: /bin/bash
    lock_passwd: false
    # Password: Zabala2026!
    passwd: \\$6\\$rounds=4096\\$saltsalt\\$jHjE.KwJRJbEFCvEMk4Gm.E/0ocKq7FJIapn1mLjEVqtZqW8Qeo6nnKVOi1y9nPNZjJxtDpABP9wX5KylMSpj0
    ssh_authorized_keys:
      - ${SSH_KEY}

chpasswd:
  expire: false

package_update: true
package_upgrade: true

packages:
  - curl
  - wget
  - vim
  - net-tools
  - qemu-guest-agent
  - git

runcmd:
  - systemctl enable qemu-guest-agent
  - systemctl start qemu-guest-agent
  - echo "Configuración completada" > /etc/zabala-configured

final_message: "Zabala Gailetak - Sistema configurado correctamente"
EOF
    
    # Crear archivo meta-data
    cat > ${SNIPPETS_PATH}/meta-${VMID}.yaml <<EOF
instance-id: zabala-${VMID}
local-hostname: ${HOSTNAME}
EOF
    
    # Configurar cloud-init en la VM usando Proxmox
    qm set ${VMID} --cicustom "user=local:snippets/user-${VMID}.yaml,network=local:snippets/network-${VMID}.yaml,meta=local:snippets/meta-${VMID}.yaml"
    
    # Configurar IP adicional para cloud-init (legacy, por si acaso)
    if [ "$VMID" != "201" ]; then
        qm set ${VMID} --ipconfig0 "ip=${IP},gw=${GW}"
    else
        qm set ${VMID} --ipconfig0 "ip=dhcp"
    fi
    
    # Configurar usuario y SSH key
    qm set ${VMID} --ciuser zabala
    qm set ${VMID} --cipassword 'Zabala2026!'
    qm set ${VMID} --sshkeys "${SSH_KEY}"
    
    # Asegurar que el agente está habilitado
    qm set ${VMID} --agent enabled=1
    
    echo "  ✅ VM $VMID configurada: $HOSTNAME ($IP)"
done

echo ""
echo "=========================================="
echo "✅ CLOUD-INIT CONFIGURADO"
echo "=========================================="
echo ""
echo "📝 Credenciales de acceso:"
echo "   Usuario: zabala"
echo "   Password: Zabala2026!"
echo "   SSH Key: zabala_deploy_key"
echo ""
echo "⚠️  IMPORTANTE: Reiniciar las VMs para aplicar cloud-init:"
echo "   for vm in 201 202 203 204 205 206; do qm reboot \$vm; done"
echo ""
echo "🔄 Después del reinicio, las VMs estarán configuradas con:"
echo "   - Ubuntu 24.04 instalado automáticamente"
echo "   - IPs configuradas"
echo "   - SSH accesible con la clave privada"
echo "   - Usuario zabala con sudo sin password"
