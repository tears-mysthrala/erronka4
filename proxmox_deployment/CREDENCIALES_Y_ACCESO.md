# 🔐 Zabala Gailetak - Credenciales y Acceso

## 📋 Resumen del Despliegue

### ✅ Estado Actual
- **6 VMs creadas** en Proxmox (192.168.74.54)
- **Cloud-init configurado** en todas las VMs
- **IPs asignadas** automáticamente
- **SSH Key generada** para acceso sin contraseña

---

## 🔑 Credenciales de Acceso

### Acceso al Proxmox
| Campo | Valor |
|-------|-------|
| URL | https://192.168.74.54:8006 |
| Usuario | `admin@pve` |
| Password | `w9BbKch9@A1FqT@@123` |

### Acceso a las VMs (todas iguales)
| Campo | Valor |
|-------|-------|
| Usuario | `zabala` |
| Password | `Zabala2026!` |
| Sudo | SIN CONTRASEÑA (`NOPASSWD:ALL`) |

### SSH Key para acceso automático
```
Archivo privado: /home/kalista/erronkak/erronka4/proxmox_deployment/ssh_keys/zabala_deploy_key
Archivo público: /home/kalista/erronkak/erronka4/proxmox_deployment/ssh_keys/zabala_deploy_key.pub
```

**Clave pública:**
```
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIBCXuzD1iUrrIQq4SSuvR/z4G1UmJPmgtF9eFUm9PTfx zabala_deploy@proxmox
```

---

## 🌐 Direcciones IP de las VMs

| VM | Hostname | IP | Gateway | Rol |
|----|----------|-----|---------|-----|
| VM 201 | zg-gateway | 192.168.1.1/16 | DHCP (WAN) | Router/Firewall |
| VM 202 | zg-app | 192.168.20.10/24 | 192.168.20.1 | PHP + Nginx |
| VM 203 | zg-data | 192.168.20.20/24 | 192.168.20.1 | PostgreSQL + Redis |
| VM 204 | zg-secops | 192.168.10.10/24 | 192.168.10.1 | Wazuh + Honeypots |
| VM 205 | zg-ot | 192.168.50.10/24 | 192.168.50.1 | OpenPLC |
| VM 206 | zg-client | 192.168.200.10/24 | 192.168.200.1 | Workstation |

---

## 🚀 Acceso SSH a las VMs

### Método 1: Usando la clave SSH (Recomendado)
```bash
# Agregar la clave al agente SSH
ssh-add /home/kalista/erronkak/erronka4/proxmox_deployment/ssh_keys/zabala_deploy_key

# Conectar a cualquier VM
ssh -i /home/kalista/erronkak/erronka4/proxmox_deployment/ssh_keys/zabala_deploy_key zabala@192.168.20.10
```

### Método 2: Usando usuario/password
```bash
ssh zabala@192.168.20.10
# Password: Zabala2026!
```

---

## ⚠️ IMPORTANTE: Estado de Instalación

### Situación Actual
Las VMs están arrancando desde la **ISO de Ubuntu 24.04 Desktop** y esperan instalación manual.

### Para Completar el Despliegue Automático

#### Opción 1: Instalación Manual (Más rápida ahora)
1. Acceder al Proxmox: https://192.168.74.54:8006
2. Ir a cada VM → "Console"
3. Instalar Ubuntu 24.04 con la configuración de red asignada
4. Una vez instalado, cloud-init aplicará la configuración automáticamente

#### Opción 2: Instalación Automática 100% (Requiere acceso SSH al Proxmox)
Ejecutar en el servidor Proxmox:

```bash
# Descargar imagen cloud de Ubuntu
cd /var/lib/vz/template/iso/
wget https://cloud-images.ubuntu.com/noble/current/noble-server-cloudimg-amd64.img

# Crear template
qm create 9000 --name ubuntu-24.04-cloud --memory 2048 --cores 2
qm importdisk 9000 noble-server-cloudimg-amd64.img local-lvm
qm set 9000 --scsihw virtio-scsi-pci --scsi0 local-lvm:vm-9000-disk-0
qm set 9000 --ide2 local-lvm:cloudinit
qm set 9000 --boot order=scsi0
qm template 9000

# Clonar template para cada VM
for vmid in 201 202 203 204 205 206; do
    qm clone 9000 $vmid --full
    qm start $vmid
done
```

---

## 📁 Scripts de Configuración Post-Instalación

Una vez que las VMs tengan Ubuntu instalado, ejecutar:

### ZG-Gateway (Router/Firewall)
```bash
ssh zabala@192.168.1.1
sudo bash /root/setup_gateway.sh
```

### ZG-Data (PostgreSQL + Redis)
```bash
ssh zabala@192.168.20.20
sudo bash /root/setup_data.sh
```

### ZG-App (PHP + Nginx)
```bash
ssh zabala@192.168.20.10
sudo bash /root/setup_app.sh
```

### ZG-SecOps (Wazuh + Honeypots)
```bash
ssh zabala@192.168.10.10
sudo bash /root/setup_secops.sh
```

### ZG-OT (OpenPLC)
```bash
ssh zabala@192.168.50.10
sudo bash /root/setup_ot.sh
```

### ZG-Client (Workstation)
```bash
ssh zabala@192.168.200.10
sudo bash /root/setup_client.sh
```

---

## 🔧 Comandos Útiles

### Copiar archivos a las VMs
```bash
# Usando SCP con la clave SSH
scp -i ssh_keys/zabala_deploy_key setup_app.sh zabala@192.168.20.10:/tmp/

# O usando rsync
rsync -avz -e "ssh -i ssh_keys/zabala_deploy_key" proxmox_deployment/ zabala@192.168.20.10:/home/zabala/
```

### Verificar conectividad a todas las VMs
```bash
for ip in 192.168.1.1 192.168.20.10 192.168.20.20 192.168.10.10 192.168.50.10 192.168.200.10; do
    echo -n "Probando $ip... "
    ping -c 1 -W 2 $ip > /dev/null 2>&1 && echo "✅ OK" || echo "❌ No responde"
done
```

### Acceso a todas las VMs con SSH
```bash
# Usando el script helper
ssh -i ssh_keys/zabala_deploy_key -o StrictHostKeyChecking=no zabala@192.168.20.10
```

---

## 📝 Notas Importantes

1. **ZG-Gateway** tiene 2 interfaces:
   - `ens18`: WAN (DHCP desde red física)
   - `ens19`: LAN (192.168.1.1/16)

2. **Todas las demás VMs** tienen 1 interfaz conectada a la red interna

3. **Cloud-init** está configurado para:
   - Crear usuario `zabala` con sudo
   - Configurar IPs estáticas
   - Instalar paquetes básicos
   - Agregar la clave SSH

4. **Seguridad**:
   - Cambiar las contraseñas en producción
   - La red OT (VLAN 50) está aislada por seguridad
   - Firewall NFTables en ZG-Gateway bloquea tráfico entre VLANs

---

## 🆘 Troubleshooting

### No puedo conectar por SSH
- Verificar que la VM tiene IP: `qm agent $VMID network-get-interfaces`
- Verificar que el servicio SSH está corriendo
- Intentar conectar desde Proxmox: `qm guest exec $VMID -- /bin/bash`

### Cloud-init no aplicó la configuración
- Reiniciar la VM: `qm reboot $VMID`
- Verificar logs: `qm guest exec $VMID -- cat /var/log/cloud-init.log`

### No hay conectividad de red
- Verificar en Proxmox: las VMs deben estar en vmbr0 (WAN) o vmbr1 (LAN)
- ZG-Gateway debe tener ambas interfaces

---

## 📞 Soporte

Si tienes problemas, puedes:
1. Acceder al Proxmox y usar la consola de las VMs
2. Verificar logs en `/var/log/` dentro de cada VM
3. Consultar la documentación en `/home/kalista/erronkak/erronka4/proxmox_deployment/README.md`
