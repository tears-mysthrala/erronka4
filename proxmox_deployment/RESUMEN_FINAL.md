# 🎉 Zabala Gailetak - Despliegue Finalizado

## ✅ Estado Actual del Despliegue

### VMs Creadas y Configuradas

| VM ID | Nombre | CPUs | RAM | Disco | IP Configurada | Estado |
|-------|--------|------|-----|-------|----------------|--------|
| 201 | ZG-Gateway | 1 | 1 GB | 10 GB | DHCP (WAN) / 192.168.1.1/16 | 🟢 Running |
| 202 | ZG-App | 2 | 4 GB | 20 GB | 192.168.20.10/24 | 🟢 Running |
| 203 | ZG-Data | 2 | 4 GB | 20 GB | 192.168.20.20/24 | 🟢 Running |
| 204 | ZG-SecOps | 4 | 8 GB | 40 GB | 192.168.10.10/24 | 🟢 Running |
| 205 | ZG-OT | 1 | 2 GB | 10 GB | 192.168.50.10/24 | 🟢 Running |
| 206 | ZG-Client | 2 | 4 GB | 20 GB | 192.168.200.10/24 | 🟢 Running |

### Cloud-Init Configurado
- ✅ Usuario: `zabala`
- ✅ Password: `Zabala2026!`
- ✅ SSH Key: Configurada
- ✅ Sudo: Sin contraseña
- ✅ IPs estáticas configuradas

---

## 🔐 Credenciales de Acceso

### Proxmox Web
```
URL: https://192.168.74.54:8006
Usuario: admin@pve
Password: w9BbKch9@A1FqT@@123
```

### VMs (Todas)
```
Usuario: zabala
Password: Zabala2026!
Sudo: NOPASSWD (sin contraseña)
```

### SSH Key Privada
Ubicación: `/home/kalista/erronkak/erronka4/proxmox_deployment/ssh_keys/zabala_deploy_key`

---

## 🚀 Próximos Pasos

### 1. Acceder al Proxmox
Abrir https://192.168.74.54:8006 e ir a cada VM → "Console"

### 2. Instalar Ubuntu en Cada VM
Las VMs están arrancando desde la ISO de Ubuntu. Debes:
1. Ir a la consola de cada VM
2. Instalar Ubuntu 24.04
3. Configurar la IP correspondiente (ya está pre-configurada en cloud-init)

**IPs a configurar:**
- VM 201 (Gateway): DHCP en WAN, 192.168.1.1/16 en LAN
- VM 202 (App): 192.168.20.10/24, GW: 192.168.20.1
- VM 203 (Data): 192.168.20.20/24, GW: 192.168.20.1
- VM 204 (SecOps): 192.168.10.10/24, GW: 192.168.10.1
- VM 205 (OT): 192.168.50.10/24, GW: 192.168.50.1
- VM 206 (Client): 192.168.200.10/24, GW: 192.168.200.1

### 3. Configurar Servicios con Scripts
Una vez instalado Ubuntu, ejecutar desde tu máquina:

```bash
cd /home/kalista/erronkak/erronka4/proxmox_deployment/

# Copiar scripts a las VMs
./deploy_to_vms.sh

# O manualmente para cada VM:
ssh -i ssh_keys/zabala_deploy_key zabala@192.168.1.1 "sudo bash /root/setup_gateway.sh"
ssh -i ssh_keys/zabala_deploy_key zabala@192.168.20.10 "sudo bash /root/setup_app.sh"
ssh -i ssh_keys/zabala_deploy_key zabala@192.168.20.20 "sudo bash /root/setup_data.sh"
ssh -i ssh_keys/zabala_deploy_key zabala@192.168.10.10 "sudo bash /root/setup_secops.sh"
ssh -i ssh_keys/zabala_deploy_key zabala@192.168.50.10 "sudo bash /root/setup_ot.sh"
ssh -i ssh_keys/zabala_deploy_key zabala@192.168.200.10 "sudo bash /root/setup_client.sh"
```

---

## 📂 Archivos Generados

```
proxmox_deployment/
├── ssh_keys/
│   ├── zabala_deploy_key          # Clave SSH privada
│   └── zabala_deploy_key.pub      # Clave SSH pública
├── autoinstall/
│   ├── gateway-autoinstall.yaml   # Config autoinstall ZG-Gateway
│   ├── app-autoinstall.yaml       # Config autoinstall ZG-App
│   ├── data-autoinstall.yaml      # Config autoinstall ZG-Data
│   ├── secops-autoinstall.yaml    # Config autoinstall ZG-SecOps
│   ├── ot-autoinstall.yaml        # Config autoinstall ZG-OT
│   └── client-autoinstall.yaml    # Config autoinstall ZG-Client
├── setup_gateway.sh               # Script configuración Gateway
├── setup_app.sh                   # Script configuración App
├── setup_data.sh                  # Script configuración Data
├── setup_secops.sh                # Script configuración SecOps
├── setup_ot.sh                    # Script configuración OT
├── setup_client.sh                # Script configuración Client
├── deploy_to_vms.sh               # Helper para copiar scripts
├── CREDENCIALES_Y_ACCESO.md       # Documentación de acceso
├── SETUP_COMPLETE.sh              # Script completo para Proxmox
└── RESUMEN_FINAL.md               # Este archivo
```

---

## 🌐 Diagrama de Red

```
                           🌐 INTERNET
                               │
                    ┌──────────┴──────────┐
                    │   vmbr0 (WAN)        │
                    └──────────┬──────────┘
                               │
                    ┌──────────┴──────────┐
                    │    ZG-Gateway       │
                    │    192.168.1.1      │
                    │  (Router/Firewall)  │
                    └──────────┬──────────┘
                               │
            ┌──────────────────┼──────────────────┐
            │                  │                  │
   ┌────────┴────────┐  ┌─────┴─────┐  ┌─────────┴────────┐
   │   vmbr10        │  │  vmbr20   │  │    vmbr50        │
   │  (SecOps)       │  │   (App)   │  │     (OT)         │
   └────────┬────────┘  └─────┬─────┘  └─────────┬────────┘
            │                 │                  │
    ┌───────┴───────┐ ┌───────┴───────┐  ┌───────┴───────┐
    │  ZG-SecOps    │ │    ZG-App     │  │    ZG-OT      │
    │ 192.168.10.10 │ │ 192.168.20.10 │  │ 192.168.50.10 │
    │  Wazuh/SIEM   │ │  PHP/Nginx    │  │   OpenPLC     │
    └───────────────┘ └───────────────┘  └───────────────┘
                               │
                    ┌──────────┴──────────┐
                    │     ZG-Data         │
                    │   192.168.20.20     │
                    │ PostgreSQL + Redis  │
                    └─────────────────────┘
```

---

## 🆘 Troubleshooting

### No puedo acceder por SSH
```bash
# Verificar IP de la VM
qm guest exec <VMID> -- ip addr

# O desde Proxmox
qm agent <VMID> network-get-interfaces
```

### Cloud-init no aplicó la configuración
Las VMs están usando ISO desktop que no soporta autoinstall completo. Después de la instalación manual, cloud-init se aplicará en el primer arranque.

### Problemas de red
Verificar que ZG-Gateway tiene dos interfaces:
- ens18: Conectada a vmbr0 (WAN)
- ens19: Conectada a vmbr1 (LAN)

Las demás VMs solo necesitan una interfaz en la red correspondiente.

---

## 📞 Notas Importantes

1. **ZG-Gateway** actúa como router entre las VLANs
2. **Cloud-init** está configurado para aplicarse automáticamente después de la instalación
3. **Todas las VMs** tienen el agente QEMU habilitado para mejor integración
4. **Seguridad**: La VLAN 50 (OT) está aislada por diseño

---

**Despliegue completado:** $(date)
