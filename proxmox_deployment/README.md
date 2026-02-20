# 🏭 Zabala Gailetak - Despliegue en Proxmox

Este directorio contiene todos los scripts necesarios para desplegar la infraestructura completa de Zabala Gailetak en Proxmox.

## 📋 Descripción de la Infraestructura

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         INFRAESTRUCTURA ZABALA GAILETAK                  │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  🌐 WAN (Internet)                                                       │
│       │                                                                  │
│       ▼                                                                  │
│  ┌─────────────┐                                                         │
│  │ ZG-Gateway  │  Router/Firewall (NFTables) + DHCP Server              │
│  │  VMID: 201  │  Interfaces: WAN (DHCP) + LAN (192.168.x.x)           │
│  │  1 vCPU     │                                                         │
│  │  1 GB RAM   │  VLANs:                                                 │
│  │  10 GB Disk │   - 192.168.10.0/24 (SecOps/Management)                │
│  └──────┬──────┘   - 192.168.20.0/24 (IT/Application)                   │
│         │          - 192.168.50.0/24 (OT/Industrial)                    │
│         │          - 192.168.200.0/24 (Clients)                         │
│         │                                                                │
│    ┌────┴────┬──────────┬──────────┬──────────┐                         │
│    │         │          │          │          │                         │
│    ▼         ▼          ▼          ▼          ▼                         │
│ ┌──────┐ ┌──────┐  ┌──────┐  ┌──────┐  ┌──────┐                        │
│ │ZG-   │ │ZG-   │  │ZG-   │  │ZG-   │  │ZG-   │                        │
│ │SecOps│ │Data  │  │App   │  │OT    │  │Client│                        │
│ │204   │ │203   │  │202   │  │205   │  │206   │                        │
│ ├──────┤ ├──────┤  ├──────┤  ├──────┤  ├──────┤                        │
│ │Wazuh │ │Postgr│  │PHP   │  │OpenP│  │Work- │                        │
│ │SIEM │ │eSQL  │  │8.4   │  │LC   │  │station│                        │
│ │Honey│ │Redis │  │Nginx │  │Scada│  │      │                        │
│ │pots │ │      │  │      │  │BR   │  │      │                        │
│ └──────┘ └──────┘  └──────┘  └──────┘  └──────┘                        │
│ 10.10    20.20      20.10     50.10     200.10                         │
│                                                                          │
└─────────────────────────────────────────────────────────────────────────┘
```

## 🚀 Guía de Despliegue

### Paso 1: Copiar scripts al Proxmox

```bash
# Desde tu máquina local, copiar todo el directorio al Proxmox
scp -r /home/kalista/erronkak/erronka4/proxmox_deployment root@192.168.74.54:/root/

# Conectar por SSH al Proxmox
ssh root@192.168.74.54

# Ir al directorio
cd /root/proxmox_deployment
```

### Paso 2: Ejecutar el script de creación de VMs

```bash
chmod +x DEPLOY_TO_PROXMOX.sh
./DEPLOY_TO_PROXMOX.sh
```

Este script creará 6 VMs en Proxmox con la configuración adecuada.

### Paso 3: Descargar e instalar Debian 12

1. Descargar la ISO de Debian 12:
```bash
cd /var/lib/vz/template/iso/
wget https://cdimage.debian.org/debian-cd/current/amd64/iso-cd/debian-12.9.0-amd64-netinst.iso
```

2. Montar la ISO en cada VM y arrancar:
```bash
# Para cada VM (201-206)
qm set 201 --ide2 local:iso/debian-12.9.0-amd64-netinst.iso
qm start 201
```

3. Instalar Debian 12 en cada VM siguiendo la configuración de red:

| VM | IP | Gateway | DNS |
|----|-----|---------|-----|
| ZG-Gateway | DHCP (WAN) / 192.168.1.1 | - | 8.8.8.8 |
| ZG-App | 192.168.20.10/24 | 192.168.20.1 | 8.8.8.8 |
| ZG-Data | 192.168.20.20/24 | 192.168.20.1 | 8.8.8.8 |
| ZG-SecOps | 192.168.10.10/24 | 192.168.10.1 | 8.8.8.8 |
| ZG-OT | 192.168.50.10/24 | 192.168.50.1 | 8.8.8.8 |
| ZG-Client | 192.168.200.10/24 | 192.168.200.1 | 8.8.8.8 |

### Paso 4: Configurar cada VM

Después de instalar Debian en cada VM, ejecutar los scripts de configuración:

#### ZG-Gateway (Router/Firewall)
```bash
# En ZG-Gateway
scp proxmox_deployment/setup_gateway.sh root@192.168.1.1:/root/
ssh root@192.168.1.1 "bash /root/setup_gateway.sh"
```

#### ZG-Data (PostgreSQL + Redis)
```bash
# En ZG-Data
scp proxmox_deployment/setup_data.sh root@192.168.20.20:/root/
ssh root@192.168.20.20 "bash /root/setup_data.sh"
```

#### ZG-App (PHP + Nginx)
```bash
# En ZG-App
scp proxmox_deployment/setup_app.sh root@192.168.20.10:/root/
ssh root@192.168.20.10 "bash /root/setup_app.sh"
```

#### ZG-SecOps (Wazuh + Honeypots)
```bash
# En ZG-SecOps
scp proxmox_deployment/setup_secops.sh root@192.168.10.10:/root/
ssh root@192.168.10.10 "bash /root/setup_secops.sh"
```

#### ZG-OT (OpenPLC)
```bash
# En ZG-OT
scp proxmox_deployment/setup_ot.sh root@192.168.50.10:/root/
ssh root@192.168.50.10 "bash /root/setup_ot.sh"
```

#### ZG-Client (Workstation)
```bash
# En ZG-Client
scp proxmox_deployment/setup_client.sh root@192.168.200.10:/root/
ssh root@192.168.200.10 "bash /root/setup_client.sh"
```

## 📁 Estructura de Archivos

```
proxmox_deployment/
├── README.md                    # Este archivo
├── DEPLOY_TO_PROXMOX.sh         # Script principal de despliegue
├── deploy_zabala.py             # Script Python alternativo (API)
├── setup_gateway.sh             # Configuración de ZG-Gateway
├── setup_data.sh                # Configuración de ZG-Data
├── setup_app.sh                 # Configuración de ZG-App
├── setup_secops.sh              # Configuración de ZG-SecOps
├── setup_ot.sh                  # Configuración de ZG-OT
└── setup_client.sh              # Configuración de ZG-Client
```

## 🔧 Servicios y Puertos

### ZG-Gateway (192.168.x.1)
- **Servicios**: Router, Firewall (NFTables), DHCP Server
- **Puertos**: 22 (SSH)
- **VLANs**: 10, 20, 50, 200

### ZG-Data (192.168.20.20)
- **PostgreSQL**: 5432
- **Redis**: 6379
- **Credenciales**: 
  - PostgreSQL: zabala_user / ZabalaSecure2026!
  - Redis: ZabalaRedis2026!

### ZG-App (192.168.20.10)
- **HTTP**: 80
- **HTTPS**: 443
- **PHP**: 8.4-FPM
- **Ruta**: /var/www/zabala

### ZG-SecOps (192.168.10.10)
- **Wazuh Dashboard**: https://192.168.10.10
- **Honeypots**:
  - Conpot (Modbus): 5020, 1610
  - Dionaea (SMB/SQL): 21, 445, 1433, 3306
  - Cowrie (SSH): 2222

### ZG-OT (192.168.50.10)
- **OpenPLC**: http://192.168.50.10:8080
- **Modbus TCP**: 502
- **ScadaBR**: http://192.168.50.10:9090

### ZG-Client (192.168.200.10)
- **Usuario**: zabala / zabala123
- **Herramientas**: Firefox, Wireshark, Nmap, SQLMap

## 🧪 Testing

Desde ZG-Client, ejecutar:
```bash
/home/zabala/test_services.sh
```

Esto verificará:
- Conectividad de red
- Servicios web
- Bases de datos
- Honeypots
- PLC

## 🔒 Seguridad

- **Firewall**: NFTables en ZG-Gateway con segmentación de VLANs
- **Segmentación**: 
  - VLAN 10: SecOps/Management
  - VLAN 20: IT/Application
  - VLAN 50: OT/Industrial (aislada)
  - VLAN 200: Clients
- **Honeypots**: Detección de intrusos en red
- **SIEM**: Wazuh para monitorización y alertas

## 📝 Notas

- Todas las contraseñas deben cambiarse en producción
- El acceso a VLAN 50 (OT) está restringido por seguridad
- Wazuh requiere ~8GB RAM para funcionar correctamente
- Las VMs pueden crearse con más recursos según necesidades

## 🆘 Troubleshooting

### Problemas de red
```bash
# En ZG-Gateway
nft list ruleset
systemctl status isc-dhcp-server
journalctl -u isc-dhcp-server -f
```

### Problemas con Docker
```bash
# En cualquier VM con Docker
systemctl status docker
docker compose logs
docker ps
```

### Problemas de base de datos
```bash
# En ZG-Data
docker compose exec postgres pg_isready -U zabala_user
docker compose logs postgres
```
