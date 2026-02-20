# 🎉 Zabala Gailetak - Despliegue con Cloud Template

## ✅ Estado del Despliegue

### Template Creado
| VM ID | Nombre | Estado |
|-------|--------|--------|
| 9000 | ubuntu-cloud-template | 🟢 Template |

### VMs Creadas desde Template
| VM ID | Nombre | CPUs | RAM | Disco | IP Configurada | Estado |
|-------|--------|------|-----|-------|----------------|--------|
| 201 | ZG-Gateway | 1 | 1 GB | 10 GB | DHCP (ens18) + Manual (ens19) | 🟢 Running |
| 202 | ZG-App | 2 | 4 GB | 20 GB | 192.168.20.10/24 | 🟢 Running |
| 203 | ZG-Data | 2 | 4 GB | 20 GB | 192.168.20.20/24 | 🟢 Running |
| 204 | ZG-SecOps | 4 | 8 GB | 40 GB | 192.168.10.10/24 | 🟢 Running |
| 205 | ZG-OT | 1 | 2 GB | 10 GB | 192.168.50.10/24 | 🟢 Running |
| 206 | ZG-Client | 2 | 4 GB | 20 GB | 192.168.200.10/24 | 🟢 Running |

### Configuración de Red
- **ZG-Gateway**: 2 interfaces
  - ens18 (WAN): DHCP desde vmbr0
  - ens19 (LAN): Configurar manualmente 192.168.1.1/16
- **Otras VMs**: 1 interfaz con IP estática asignada por cloud-init

---

## 🔐 Credenciales

### Proxmox
```
URL: https://192.168.74.54:8006
Usuario: admin@pve
Password: w9BbKch9@A1FqT@@123
```

### Todas las VMs (Configurado por cloud-init)
```
Usuario: zabala
Password: Zabala2026!
Sudo: NOPASSWD (sin contraseña)
SSH Key: Configurada
```

### Clave SSH
Archivo: `ssh_keys/zabala_deploy_key`
```bash
ssh -i ssh_keys/zabala_deploy_key zabala@192.168.20.10
```

---

## 🚀 Próximos Pasos

### 1. Esperar a que cloud-init termine (2-3 minutos)
Las VMs están aplicando la configuración inicial.

### 2. Verificar IPs
Desde Proxmox, ir a cada VM y verificar la IP asignada:
```bash
# En consola de cada VM
ip addr show
```

### 3. Configurar ZG-Gateway Manualmente
La interfaz WAN (ens18) obtendrá IP por DHCP, pero necesitas configurar la LAN:

```bash
ssh zabala@<IP-de-gateway>
sudo nano /etc/netplan/00-installer-config.yaml
```

Agregar configuración para ens19:
```yaml
network:
  version: 2
  ethernets:
    ens18:
      dhcp4: true
    ens19:
      addresses:
        - 192.168.1.1/16
      nameservers:
        addresses: [8.8.8.8, 1.1.1.1]
```

```bash
sudo netplan apply
```

### 4. Ejecutar Scripts de Configuración
```bash
cd /home/kalista/erronkak/erronka4/proxmox_deployment/
./deploy_to_vms.sh

# O manualmente:
ssh -i ssh_keys/zabala_deploy_key zabala@192.168.20.10 "sudo bash /root/setup_app.sh"
ssh -i ssh_keys/zabala_deploy_key zabala@192.168.20.20 "sudo bash /root/setup_data.sh"
# ... etc
```

---

## 📝 Notas

### Ventajas de usar Cloud Template
✅ Instalación automática (sin ISO interactiva)
✅ Cloud-init aplica configuración en primer arranque
✅ IPs configuradas automáticamente
✅ Usuario y SSH key pre-configurados
✅ VMs idénticas y reproducibles

### Primer Arranque
El primer arranque de cada VM toma más tiempo porque:
1. Expande el disco al tamaño configurado
2. Aplica configuración de cloud-init
3. Genera claves SSH del host
4. Configura red con IPs estáticas

Esto es normal y solo ocurre en el primer arranque.

---

## 🆘 Troubleshooting

### No puedo conectar por SSH
1. Verificar que cloud-init terminó: `sudo cloud-init status`
2. Verificar IP: `ip addr show`
3. Verificar que el agente QEMU está corriendo

### La VM no tiene IP
1. Esperar unos minutos más (primer arranque es lento)
2. Verificar en Proxmox: VM → Summary → IP Address
3. Reiniciar la VM desde Proxmox

### Cloud-init falló
```bash
sudo cat /var/log/cloud-init.log
sudo cat /var/log/cloud-init-output.log
```

---

**Despliegue realizado:** $(date)
**Método:** Cloud Template con cloud-init
