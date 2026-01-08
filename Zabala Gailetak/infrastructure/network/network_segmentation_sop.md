# Network Segmentation Implementation - SOP

## Helburua

Enpresako sare segmentatua ezartzeko eta kudeatzeko prozedura, IT eta OT sareen artean komunikazio seguruak bermatzeko.

## Aurrebaldintzak

- Sare topologia diseinua eskuragarria
- Firewall sare-gailuak (Palo Alto, Cisco, Fortigate, etab.)
- VLAN eta Subnet ezagutzak

## 1. Sare Segmentazioa Diseinua

### 1.1 Sare Zonen Definizioak

```
1. DMZ Zone (192.168.100.0/24)
   - Web zerbitzariak
   - Reverse Proxy
   - Load Balancer

2. User Network (192.168.10.0/24)
   - Langileen lan estazioak
   - WiFi sarbidea

3. Server Network (192.168.20.0/24)
   - Application Server
   - Database Server
   - File Server
   - Active Directory

4. OT Network (192.168.50.0/24)
   - PLC-ak
   - HMI-ak
   - SCADA
   - Sensor eta aktoreak

5. Management Network (192.168.200.0/24)
   - Admin工作站
   - Monitoring
   - SIEM
```

### 1.2 VLAN Konfigurazioa

```
VLAN 10: User Network
VLAN 20: Server Network
VLAN 50: OT Network
VLAN 100: DMZ
VLAN 200: Management
```

## 2. Firewall Arauak

### 2.1 DMZ-ra Sarrera (Inbound)

```bash
# Web zerbitzaria
allow tcp from any to 192.168.100.10 port 80
allow tcp from any to 192.168.100.10 port 443

# SMTP (if needed)
allow tcp from any to 192.168.100.20 port 25
allow tcp from any to 192.168.100.20 port 587

# Deny guztia
deny all
```

### 2.2 DMZtik Barrura (Inbound to Internal)

```bash
# Application Serverra soilik
allow tcp from 192.168.100.10 to 192.168.20.10 port 8080
allow tcp from 192.168.100.10 to 192.168.20.20 port 5432

# Deny beste guztiak
deny all
```

### 2.3 User Networktik Server Networkera

```bash
# Domain Controller (AD)
allow tcp from 192.168.10.0/24 to 192.168.20.5 port 88
allow tcp from 192.168.10.0/24 to 192.168.20.5 port 389
allow tcp from 192.168.10.0/24 to 192.168.20.5 port 445

# Application Server
allow tcp from 192.168.10.0/24 to 192.168.20.10 port 8080

# Deny direktuki database-ra
deny tcp from 192.168.10.0/24 to 192.168.20.20 port 5432
```

### 2.4 OT Network Segurua

```bash
# ITtik OTra EZ dago komunikazio zuzenik
deny all from 192.168.0.0/16 to 192.168.50.0/24

# Firewall berezi edo Data Diode erabili behar da
# soilik beharrezko komunikazioetarako
```

### 2.5 Management Network

```bash
# Soilik admin工作站-ek sar daiteke
allow from 192.168.200.0/24 to any
deny all
```

## 3. Access Control List (ACL) Ezarpenak

### 3.1 Router ACLak

```bash
# User Network
ip access-list extended USER_TO_SERVER
permit tcp 192.168.10.0 0.0.0.255 host 192.168.20.5 eq domain
permit tcp 192.168.10.0 0.0.0.255 host 192.168.20.5 eq 88
permit tcp 192.168.10.0 0.0.0.255 host 192.168.20.10 eq 8080
deny ip any any
```

### 3.2 Switch Port Security

```bash
interface Gi0/1
 description User PC
 switchport mode access
 switchport port-security
 switchport port-security maximum 1
 switchport port-security violation restrict
```

## 4. VPN Konfigurazioa

### 4.1 Remote Access VPN

```bash
# OpenVPN edo IPsec
# MFA beharrezkoa
# 2FA gaituta
```

### 4.2 Site-to-Site VPN

```bash
# Beste bulegoekin
# IPSec VPN
# Pre-shared key edo Certificate
```

## 5. OT Security Arauak

### 5.1 Purdue Model

```
Level 5: Enterprise Network
Level 4: Business Network
Level 3: Manufacturing Zone
Level 2: Supervisory Control
Level 1: Basic Control
Level 0: Physical Process
```

### 5.2 Data Diode

```bash
# Unidirectional communication
# OT -> IT soilik datuak bidaltzeko
# ITtik OTra ez dago komunikaziorik
```

### 5.3 OT Firewall Rules

```bash
# HMI to PLC
allow tcp from 192.168.50.100 to 192.168.50.10 port 102

# SCADA to PLC
allow tcp from 192.168.50.200 to 192.168.50.0/24 port 102

# Deny beste guztiak
deny all
```

## 6. Intrusion Detection System (IDS)

### 6.1 Snort Rules

```bash
# SQL Injection detekzioa
alert tcp $EXTERNAL_NET any -> $DMZ 80 (msg:"SQL Injection Attempt"; flow:established; content:"UNION SELECT"; nocase; sid:1000001;)

# OT Traffic anomalia
alert tcp $OT_NET any -> $OT_NET 102 (msg:"Unexpected PLC Traffic"; threshold: type both, track by_src, count 10, seconds 60; sid:2000001;)
```

## 7. Monitoring eta Logging

### 7.1 Syslog Konfigurazioa

```bash
# Firewall logs syslog-era bidali
logging host 192.168.200.10
logging trap informational
```

### 7.2 NetFlow/SFlow

```bash
# Sare trafikoa monitorizatu
flow exporter TO_SIEM
 destination 192.168.200.10
 source Vlan10
 transport udp 2055
```

## 8. Change Management

### 8.1 Firewall Rule Change Prozesua

1. Eskaera formulariua bete
2. Segurtasun taldeak berrikusi
3. Test environment-en proba
4. Change approval
5. Production deploy
6. Verification
7. Dokumentazio eguneratu

### 8.2 Rule Review

- Hiru hilabetero rule-ak berrikusi
- Ez erabilitako rule-ak kendu
- Obsolete rule-ak ezabatu

## 9. Disaster Recovery

### 9.1 Backup Konfigurazioak

```bash
# Firewall konfigurazioak backup
copy running-config startup-config
copy running-config tftp://192.168.200.50/firewall-backup.cfg
```

### 9.2 Failover Konfigurazioa

```bash
# High Availability
# Primary/Secondary firewall
# VRRP edo HSRP
```

## 10. Compliance eta Audit

### 10.1 Aldizkako Audit-ak

- Hiru hilabetero internal audit
- Urtero external audit
- Compliance check (ISO 27001, IEC 62443)

### 10.2 Documentation

- Sare diagramak eguneratu
- Firewall rules dokumentatu
- ACL-ak dokumentatu
- Network policies dokumentatu

## Erreferentziak

- NIST SP 800-41
- IEC 62443
- ISO 27001
- OWASP Network Security Guide