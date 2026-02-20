#!/bin/bash
# Script para copiar archivos de configuración a las VMs
# Uso: ./deploy_to_vms.sh

set -e

SSH_KEY="/home/kalista/erronkak/erronka4/proxmox_deployment/ssh_keys/zabala_deploy_key"
SCRIPTS_DIR="/home/kalista/erronkak/erronka4/proxmox_deployment"

echo "=========================================="
echo "📦 DESPLIEGUE DE SCRIPTS A VMs"
echo "=========================================="
echo ""

# Verificar que existe la clave SSH
if [ ! -f "$SSH_KEY" ]; then
    echo "❌ Error: No se encuentra la clave SSH en $SSH_KEY"
    exit 1
fi

echo "Verificando conectividad a las VMs..."
echo ""

# IPs de las VMs
declare -A VMS
VMS=(
    [201]="192.168.1.1"
    [202]="192.168.20.10"
    [203]="192.168.20.20"
    [204]="192.168.10.10"
    [205]="192.168.50.10"
    [206]="192.168.200.10"
)

# Verificar qué VMs están accesibles
declare -A ACCESSIBLE_VMS
for vmid in "${!VMS[@]}"; do
    ip="${VMS[$vmid]}"
    echo -n "VM $vmid ($ip)... "
    
    if ping -c 1 -W 2 "$ip" > /dev/null 2>&1; then
        echo "✅ Accesible"
        ACCESSIBLE_VMS[$vmid]=$ip
    else
        echo "❌ No responde"
    fi
done

echo ""
echo "=========================================="
echo "🚀 Copiando scripts a VMs accesibles..."
echo "=========================================="
echo ""

# Función para copiar script a VM
copy_script() {
    local vmid=$1
    local ip=$2
    local script_name=$3
    local script_file="$SCRIPTS_DIR/$script_name"
    
    if [ ! -f "$script_file" ]; then
        echo "  ⚠️  Script no encontrado: $script_name"
        return
    fi
    
    echo -n "  Copiando $script_name a VM $vmid... "
    
    if scp -i "$SSH_KEY" -o StrictHostKeyChecking=no -o ConnectTimeout=10 \
        "$script_file" "zabala@$ip:/tmp/" > /dev/null 2>&1; then
        
        # Mover a /root/ con sudo
        ssh -i "$SSH_KEY" -o StrictHostKeyChecking=no -o ConnectTimeout=10 \
            "zabala@$ip" "sudo mv /tmp/$script_name /root/ && sudo chmod +x /root/$script_name" > /dev/null 2>&1
        
        echo "✅"
    else
        echo "❌ Falló"
    fi
}

# Copiar scripts a cada VM accesible
for vmid in "${!ACCESSIBLE_VMS[@]}"; do
    ip="${ACCESSIBLE_VMS[$vmid]}"
    
    echo "VM $vmid ($ip):"
    
    case $vmid in
        201)
            copy_script $vmid $ip "setup_gateway.sh"
            ;;
        202)
            copy_script $vmid $ip "setup_app.sh"
            ;;
        203)
            copy_script $vmid $ip "setup_data.sh"
            ;;
        204)
            copy_script $vmid $ip "setup_secops.sh"
            ;;
        205)
            copy_script $vmid $ip "setup_ot.sh"
            ;;
        206)
            copy_script $vmid $ip "setup_client.sh"
            ;;
    esac
    echo ""
done

echo "=========================================="
echo "✅ DESPLIEGUE COMPLETADO"
echo "=========================================="
echo ""
echo "Para ejecutar los scripts en cada VM:"
echo ""
echo "  ssh -i ssh_keys/zabala_deploy_key zabala@<IP>"
echo "  sudo bash /root/setup_<nombre>.sh"
echo ""
echo "IPs:"
echo "  ZG-Gateway:  192.168.1.1"
echo "  ZG-App:      192.168.20.10"
echo "  ZG-Data:     192.168.20.20"
echo "  ZG-SecOps:   192.168.10.10"
echo "  ZG-OT:       192.168.50.10"
echo "  ZG-Client:   192.168.200.10"
