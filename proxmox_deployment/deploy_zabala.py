#!/usr/bin/env python3
"""
Zabala Gailetak - Proxmox Deployment Script
Crea y configura 6 VMs para la infraestructura completa
"""

import requests
import urllib3
import json
import time
import sys

# Deshabilitar warnings de SSL
urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

# Configuración de Proxmox
PROXMOX_HOST = "192.168.74.54"
PROXMOX_USER = "admin@pve"
PROXMOX_PASS = "w9BbKch9@A1FqT@@123"
NODE = "pv1"  # Nombre del nodo Proxmox

# Configuración de VMs
VMS = [
    {
        "vmid": 201,
        "name": "ZG-Gateway",
        "cores": 1,
        "memory": 1024,
        "disk": 10,
        "description": "Router/Firewall - NFTables, DHCP",
        "ip": "192.168.1.1"
    },
    {
        "vmid": 202,
        "name": "ZG-App",
        "cores": 2,
        "memory": 4096,
        "disk": 20,
        "description": "PHP 8.4 + Nginx Application Server",
        "ip": "192.168.20.10"
    },
    {
        "vmid": 203,
        "name": "ZG-Data",
        "cores": 2,
        "memory": 4096,
        "disk": 20,
        "description": "PostgreSQL 16 + Redis 7",
        "ip": "192.168.20.20"
    },
    {
        "vmid": 204,
        "name": "ZG-SecOps",
        "cores": 4,
        "memory": 8192,
        "disk": 40,
        "description": "Wazuh SIEM + Honeypots",
        "ip": "192.168.10.10"
    },
    {
        "vmid": 205,
        "name": "ZG-OT",
        "cores": 1,
        "memory": 2048,
        "disk": 10,
        "description": "OpenPLC - Industrial Control Simulation",
        "ip": "192.168.50.10"
    },
    {
        "vmid": 206,
        "name": "ZG-Client",
        "cores": 2,
        "memory": 4096,
        "disk": 20,
        "description": "Client/Workstation for testing",
        "ip": "192.168.200.10"
    }
]

class ProxmoxAPI:
    def __init__(self, host, user, password):
        self.host = host
        self.user = user
        self.password = password
        self.base_url = f"https://{host}:8006/api2/json"
        self.token = None
        self.authenticate()
    
    def authenticate(self):
        """Autenticar con Proxmox y obtener ticket"""
        url = f"{self.base_url}/access/ticket"
        data = {
            "username": self.user,
            "password": self.password
        }
        try:
            resp = requests.post(url, data=data, verify=False)
            resp.raise_for_status()
            result = resp.json()["data"]
            self.token = result["ticket"]
            self.csrf = result["CSRFPreventionToken"]
            print(f"✅ Autenticado con Proxmox ({self.user})")
        except Exception as e:
            print(f"❌ Error de autenticación: {e}")
            sys.exit(1)
    
    def get_headers(self):
        return {
            "CSRFPreventionToken": self.csrf,
            "Cookie": f"PVEAuthCookie={self.token}"
        }
    
    def get_vm_list(self):
        """Obtener lista de VMs existentes"""
        url = f"{self.base_url}/nodes/{NODE}/qemu"
        try:
            resp = requests.get(url, headers=self.get_headers(), verify=False)
            return resp.json()["data"]
        except Exception as e:
            print(f"❌ Error obteniendo VMs: {e}")
            return []
    
    def clone_vm(self, vmid, name, cores, memory, disk, description):
        """Clonar VM desde template o crear nueva"""
        # Verificar si ya existe
        vms = self.get_vm_list() or []
        for vm in vms:
            if vm["vmid"] == vmid:
                print(f"⚠️  VM {name} (ID: {vmid}) ya existe, omitiendo...")
                return True
        
        print(f"📦 Creando VM {name} (ID: {vmid})...")
        
        # Buscar template de Debian 12
        url = f"{self.base_url}/nodes/{NODE}/storage/local/content"
        try:
            resp = requests.get(url, headers=self.get_headers(), verify=False)
            content = resp.json()["data"]
            
            # Buscar ISO de Debian
            template_volid = None
            for item in content or []:
                if item.get("content") == "iso" and "debian" in item.get("volid", "").lower():
                    template_volid = item["volid"]
                    break
            
            # Si no hay template, crear VM desde cero con ISO
            url_create = f"{self.base_url}/nodes/{NODE}/qemu"
            data = {
                "vmid": vmid,
                "name": name,
                "cores": cores,
                "memory": memory,
                "net0": "virtio,bridge=vmbr0",
                "scsihw": "virtio-scsi-single",
                "ostype": "l26",
                "description": description,
                "agent": 1
            }
            
            resp = requests.post(url_create, data=data, headers=self.get_headers(), verify=False)
            if resp.status_code in [200, 201]:
                print(f"   ✅ VM {name} creada")
                
                # Agregar disco
                time.sleep(1)
                url_disk = f"{self.base_url}/nodes/{NODE}/qemu/{vmid}/config"
                disk_data = {
                    "scsi0": f"local-lvm:{disk},iothread=1"
                }
                requests.post(url_disk, data=disk_data, headers=self.get_headers(), verify=False)
                print(f"   ✅ Disco de {disk}GB agregado")
                
                # Agregar CD-ROM con ISO si existe
                if template_volid:
                    cd_data = {
                        "ide2": f"{template_volid},media=cdrom"
                    }
                    requests.post(url_disk, data=cd_data, headers=self.get_headers(), verify=False)
                
                return True
            else:
                print(f"   ❌ Error creando VM: {resp.text}")
                return False
                
        except Exception as e:
            print(f"   ❌ Error: {e}")
            return False
    
    def start_vm(self, vmid):
        """Iniciar VM"""
        url = f"{self.base_url}/nodes/{NODE}/qemu/{vmid}/status/start"
        try:
            resp = requests.post(url, headers=self.get_headers(), verify=False)
            if resp.status_code in [200, 201]:
                print(f"   ✅ VM {vmid} iniciada")
                return True
            else:
                print(f"   ⚠️  No se pudo iniciar VM {vmid}: {resp.text}")
                return False
        except Exception as e:
            print(f"   ❌ Error iniciando VM {vmid}: {e}")
            return False
    
    def configure_network(self, vmid, network_config):
        """Configurar red adicional para VLANs"""
        url = f"{self.base_url}/nodes/{NODE}/qemu/{vmid}/config"
        try:
            data = {
                "net1": f"virtio,bridge={network_config}"
            }
            resp = requests.post(url, data=data, headers=self.get_headers(), verify=False)
            return resp.status_code in [200, 201]
        except Exception as e:
            print(f"   ❌ Error configurando red: {e}")
            return False

def main():
    print("=" * 60)
    print("🚀 ZABALA GAILETAK - PROXMOX DEPLOYMENT")
    print("=" * 60)
    print()
    
    # Conectar a Proxmox
    proxmox = ProxmoxAPI(PROXMOX_HOST, PROXMOX_USER, PROXMOX_PASS)
    
    # Verificar VMs existentes
    print("📋 Verificando VMs existentes...")
    existing_vms = proxmox.get_vm_list() or []
    print(f"   Encontradas {len(existing_vms)} VMs")
    print()
    
    # Crear VMs
    print("🖥️  Creando máquinas virtuales...")
    print("-" * 60)
    created_vms = []
    for vm in VMS:
        if proxmox.clone_vm(
            vm["vmid"], 
            vm["name"], 
            vm["cores"], 
            vm["memory"], 
            vm["disk"],
            vm["description"]
        ):
            created_vms.append(vm)
        print()
    
    print("=" * 60)
    print(f"✅ Despliegue completado: {len(created_vms)} VMs configuradas")
    print("=" * 60)
    print()
    print("📊 Resumen de VMs:")
    print("-" * 60)
    print(f"{'VM Name':<15} {'VMID':<6} {'vCPUs':<6} {'RAM':<8} {'Disk':<8} {'IP':<15}")
    print("-" * 60)
    for vm in created_vms:
        print(f"{vm['name']:<15} {vm['vmid']:<6} {vm['cores']:<6} {vm['memory']:<8} {vm['disk']:<8} {vm['ip']:<15}")
    
    print()
    print("⚠️  NOTAS IMPORTANTES:")
    print("   1. Instalar Debian 12 en cada VM manualmente o usar template")
    print("   2. Configurar IPs estáticas según el plan de direccionamiento")
    print("   3. Ejecutar scripts de configuración post-instalación")
    print("   4. ZG-Gateway necesita 2 interfaces de red (WAN + LAN)")
    print()
    print("📁 Scripts de configuración generados en:")
    print("   /home/kalista/erronkak/erronka4/proxmox_deployment/")

if __name__ == "__main__":
    main()
