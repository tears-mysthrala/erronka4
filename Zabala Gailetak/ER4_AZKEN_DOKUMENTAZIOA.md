<div align="center">

# ZABALA GAILETAK S.A.

## ER4 PROIEKTUA — AZKEN DOKUMENTAZIO TEKNIKOA

### Zibersegurtasun Proiektu Integrala

**Bertsioa:** 1.0
**Data:** 2026ko Otsailaren 22a
**Sailkapena:** Barne Erabilera / Dokumentazio Akademikoa

---

*Gaileta eta txokolate fabrikazioan espezializatutako enpresa industriala*
*120 langile — Espainia eta Europar Batasuneko salmenta*

</div>

---

## AURKIBIDEA

1. [Sarrera / Atarikoa](#1-sarrera--atarikoa)
2. [Sareak eta Sistemak Gotortzea](#2-sareak-eta-sistemak-gotortzea)
3. [Ekoizpen Seguruan Jartzea (DevSecOps)](#3-ekoizpen-seguruan-jartzea-devsecops)
4. [Hacking Etikoa](#4-hacking-etikoa)
5. [Zibersegurtasun-Gorabeherak](#5-zibersegurtasun-gorabeherak)
6. [Auzitegi-Analisi Informatikoa](#6-auzitegi-analisi-informatikoa)
7. [Zibersegurtasunaren Arloko Araudia](#7-zibersegurtasunaren-arloko-araudia)
8. [Ondorioak](#8-ondorioak)

---

<div style="page-break-after: always;"></div>

# 1. SARRERA / ATARIKOA

## 1.1 Zabala Gailetak Enpresaren Aurkezpena

**Zabala Gailetak S.A.** gaileta eta txokolate fabrikazioan espezializatutako enpresa industriala da. 120 langile ditu eta bere produktuak Espainia osoan eta Europar Batasuneko herrialdeetan saltzen ditu online bidez.

Enpresak baliabide kritikoak ditu babestu beharrekoak:

| Aktibo Mota | Deskribapena |
|-------------|-------------|
| **Merkataritza-sekretuak** | Gaileta errezetak eta fabrikazio prozesu bereziak |
| **OT Makineria** | PLC kontrolagailuak, labeak, oratzaileak, garraiatzaileak |
| **Langile datuak** | GG Atariko informazio pertsonala (NIF, IBAN, nomina) |
| **Bezero datuak** | Online salmenta plataformako erabiltzaile informazioa |
| **IT Azpiegitura** | Zerbitzariak, datu-baseak, web aplikazioak |

## 1.2 ER4 Proiektuaren Helburuak

ER4 proiektua Zabala Gailetak-en zibersegurtasun egoera osorik modernizatzeko eta sendotzeko diseinatutako proiektu integrala da. Honako helburu nagusiak lortu dira:

1. **Sare segmentazioa** — IT eta OT sareen arteko banaketa zorrotza, IEC 62443 arabera
2. **Zerbitzarien gotortzea** — SSH hardening, suebakiak, fail2ban, segurtasun goiburuak
3. **SIEM sistema** — ELK Stack-ekin denbora errealeko monitorizazioa eta alerta sistema
4. **Honeypot azpiegitura** — Mehatxuen adimen bilketa DMZ isolatuan
5. **GG Atari segurua** — PHP 8.4 web aplikazioa JWT, MFA eta RBAC-rekin
6. **Android aplikazioa** — Kotlin/Jetpack Compose mugikor aplikazioa segurtasun sendoarekin
7. **CI/CD pipeline-ak** — GitHub Actions bidezko integrazio eta hedapen automatizatua
8. **Hacking etikoa** — PTES metodologiarekin penetrazio probak
9. **Gorabehera kudeaketa** — NIST 6 faseko erantzun plana eta OT simulazioak
10. **Analisi forentsea** — RFC 3227 araberako ebidentzia bilketa eta memoria analisia
11. **Arau betetzea** — GDPR, ISO 27001 SGSI, NIS2 eta IEC 62443 egokitzapena
12. **Negozio jarraitasuna** — BCP plana RTO/RPO definituekin eta backup sistema

## 1.3 Proiektuaren Irismena

| Dimentsio | Xehetasuna |
|-----------|-----------|
| **Fitxategiak** | 292 fitxategi, 80+ sortuak |
| **Dokumentazioa** | 10.000+ lerro, 50+ markdown fitxategi |
| **Kodea** | 5.000+ lerro (PHP, Kotlin, YAML, Bash) |
| **Testak** | 82/82 unit test gainditu |
| **Betetzea** | ISO 27001 %93, ER4 %100 |
| **SGSI Dokumentuak** | 16 politika eta prozedura |
| **GDPR Dokumentuak** | 14 fitxategi |
| **SOPak** | 15+ prozedura operatibo estandar |

---

<div style="page-break-after: always;"></div>

# 2. SAREAK ETA SISTEMAK GOTORTZEA

Atal honek Zabala Gailetak-en sare azpiegitura, sistemak gotortzeko neurriak, SIEM monitorizazioa, honeypot hedapena eta OT segurtasuna deskribatzen ditu. Neurri hauek guztiak enpresaren merkataritza-sekretuak, OT makineria eta langileen datuak babesten dituzte.

## 2.1 Sare-topologia eta Segmentazioa

### 2.1.1 Sare Arkitektura

Zabala Gailetak-en sare arkitektura zona anitzetan banatuta dago, bakoitza bere IP esparruarekin:

| Zona | Azpisarea | Helburua |
|------|-----------|----------|
| **DMZ** | 192.168.2.0/24 | Web zerbitzariak, Honeypot-ak |
| **Erabiltzaileak (IT)** | 10.0.20.0/24 | Bulegoak |
| **Zerbitzariak (IT)** | 10.0.10.0/24 | ERP, DB, App, SIEM |
| **OT Sarea** | 172.16.0.0/16 | PLCak, SCADA |
| — OT Kudeaketa | 172.16.1.0/24 | Switch industriala |
| — OT Prozesua | 172.16.2.0/24 | Sentsoreak, aktuadoreak |
| **Gonbidatuak** | 192.168.100.0/24 | Isolatutako WiFi |

### 2.1.2 Sare Inbentarioa

**Sare Gailuak:**

| ID | Mota | Funtzioa | IP |
|:---|:-----|:---------|:---|
| **FW-PERIM** | Suebaki Perimetrala | Internet trafikoa, VPN, DMZ | 192.168.1.1 |
| **FW-INT** | Barne Suebakia | IT/OT segmentazioa | 10.0.0.1 |
| **SW-CORE** | Switch Core | Sare bizkarrezurra | 10.0.1.1 |
| **SW-ACC-OFF** | Switch Sarbidea | Bulego ekipoak | 10.0.2.1 |
| **SW-ACC-PROD** | Switch Industriala (Gogortua) | OT gailuak | 172.16.1.1 |
| **WIFI-AP** | Sarbide Puntuak | VLAN bereiziak | — |

**Zerbitzariak (IT):**

| Hostname | OS | Funtzioa | IP |
|:---------|:---|:---------|:---|
| **SRV-DC01** | Windows Server | AD, DNS, DHCP | 10.0.10.10 |
| **SRV-ERP** | Linux | ERP + Datu-basea | 10.0.10.20 |
| **SRV-WEB** | Linux | Web zerbitzaria (DMZ) | 192.168.2.10 |
| **SRV-APP** | Linux | API zerbitzaria | 10.0.10.30 |
| **SRV-FILE** | Linux | Fitxategi zerbitzaria | 10.0.10.40 |
| **SRV-SIEM** | Linux | Log bilketa eta monitorizazioa | 10.0.10.50 |

**OT Kontrolagailuak:**

- **PLC-KNEAD-01/02:** Oratzeko makinak
- **PLC-OVEN-01:** Labe nagusia
- **SCADA-SRV:** Ekoizpen datuen zerbitzaria (OT Sarean)

### 2.1.3 Gateway Konfigurazioa

ZG-Gateway nodoak barne sare guztien bideratzaile gisa funtzionatzen du:

```bash
# /etc/network/interfaces — ZG-Gateway

auto lo
iface lo inet loopback

# WAN (Eth0) - Internet konexioa (Isard Bridge)
allow-hotplug eth0
iface eth0 inet dhcp

# LAN (Eth1) - Barne sarea
allow-hotplug eth1
iface eth1 inet static
    address 192.168.1.1
    netmask 255.255.0.0
    up ip addr add 192.168.10.1/24 dev eth1   # ERABILTZAILE GATEWAY
    up ip addr add 192.168.20.1/24 dev eth1   # ZERBITZARI GATEWAY
    up ip addr add 192.168.50.1/24 dev eth1   # OT GATEWAY
    up ip addr add 192.168.200.1/24 dev eth1  # KUDEAKETA GATEWAY
```

## 2.2 Suebakiak (Firewall)

### 2.2.1 NFTables Arau Multzoa

Zabala Gailetak-en suebaki nagusia `nftables` bidez kudeatu da, IEC 62443 eta proiektuaren segurtasun politikaren araberako isolamendu zorrotza bermatzeko. **Deny-by-default** politika erabiltzen da:

```bash
#!/usr/sbin/nft -f
flush ruleset

table ip filter {
    chain input {
        type filter hook input priority 0; policy drop;

        # Loopback baimendu
        iifname "lo" accept

        # Ezarritako konexioak baimendu
        ct state established,related accept

        # SSH Admin Saretik bakarrik
        ip saddr 192.168.200.0/24 tcp dport 22 accept

        # ICMP diagnostikorako
        ip protocol icmp accept

        # DHCP LAN-ean
        iifname "eth1" udp dport { 67, 68 } accept
    }

    chain forward {
        type filter hook forward priority 0; policy drop;
        ct state established,related accept

        # --- ACL ARAUAK ---

        # 1. ERABILTZAILEA -> APP (Web sarbidea soilik)
        ip saddr 192.168.10.0/24 ip daddr 192.168.20.10 tcp dport { 80, 443 } accept

        # 2. APP -> DATUAK (Datu-base sarbidea soilik)
        ip saddr 192.168.20.10 ip daddr 192.168.20.20 tcp dport { 5432, 6379 } accept

        # 3. KUDEAKETA -> GUZTIA (Administrazio osoa)
        ip saddr 192.168.200.0/24 accept

        # 4. OT ISOLAMENDUA
        # Lehenetsitako politikaz (DROP), OT sarea guztiz isolatuta dago

        # 5. INTERNET IRTEERA (NAT bidez)
        iifname "eth1" oifname "eth0" accept
    }

    chain output {
        type filter hook output priority 0; policy accept;
    }
}

table ip nat {
    chain postrouting {
        type nat hook postrouting priority 100; policy accept;
        oifname "eth0" masquerade
    }
}
```

### 2.2.2 UFW Host Suebakiak

Zerbitzari bakoitzak bere UFW suebakia du, sarbidea minimora mugatuz:

**ZG-App (Web Zerbitzaria):**
```bash
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow 80/tcp
ufw allow 443/tcp
ufw enable
```

**ZG-Data (Datu-basea):**
```bash
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow from 192.168.20.10 to any port 5432  # PostgreSQL App-etik soilik
ufw allow from 192.168.20.10 to any port 6379  # Redis App-etik soilik
ufw enable
```

**ZG-SecOps (Wazuh/SIEM):**
```bash
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow 443/tcp    # Wazuh Dashboard
ufw allow 1514/tcp   # Agente komunikazioa
ufw allow 1515/tcp   # Izen-ematea
ufw enable
```

**ZG-OT (Sistema Industriala):**
```bash
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow 8080/tcp   # OpenPLC Web
ufw allow 502/tcp    # Modbus
ufw enable
```

## 2.3 Zerbitzarien Gotortzea (Hardening)

Debian 12 oinarritutako zerbitzari guztietan honako gotortze-neurriak aplikatu dira:

### 2.3.1 Sistema Oinarrizkoa

```bash
# Eguneratu eta tresnak instalatu
apt update && apt upgrade -y
apt install -y ufw fail2ban curl git htop

# Root ez den erabiltzailea sortu
adduser admin
usermod -aG sudo admin

# Memoria partekatua segurua
echo "tmpfs /run/shm tmpfs defaults,noexec,nosuid 0 0" >> /etc/fstab
mount -o remount /run/shm
```

### 2.3.2 SSH Gotortzea

```bash
cat <<EOF > /etc/ssh/sshd_config.d/99-hardening.conf
PermitRootLogin no
PasswordAuthentication no
X11Forwarding no
EOF

systemctl restart sshd
```

**Neurri garrantzitsuak:**
- Root saio-hasiera desgaituta
- Pasahitz bidezko autentifikazioa desgaituta (gako publikoak soilik)
- X11 birbidalketa desgaituta

### 2.3.3 Fail2Ban

SSH indar gordinaren aurkako babesa:

```bash
cat <<EOF >> /etc/fail2ban/jail.local
[sshd]
enabled = true
port = ssh
filter = sshd
logpath = /var/log/auth.log
maxretry = 3
bantime = 3600
EOF

systemctl restart fail2ban
systemctl enable fail2ban
```

### 2.3.4 Datu-baseen Segurtasuna

- **PostgreSQL 16:** Sarbidea App zerbitzaritik soilik (UFW araua)
- **Redis 7:** AOF (Append-Only File) gaituta datu iraunkortasunerako
- Bi zerbitzuek Docker kontenedoreetan exekutatzen dira healthcheck-ekin

### 2.3.5 Nginx Konfigurazioa

Nginx web zerbitzariak segurtasun goiburu anitz eta rate limiting aplikatzen ditu Zabala Gailetak-en GG Ataria babesteko:

```nginx
# Segurtasun goiburuak
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-Content-Type-Options "nosniff" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;

# Rate limiting (Zabala Gailetak langileentzat)
limit_req_zone $binary_remote_addr zone=api_limit:10m rate=10r/s;
limit_req_zone $binary_remote_addr zone=login_limit:10m rate=5r/m;

# Login endpoint — mugaketa zorrotzagoa
location /api/auth/login {
    limit_req zone=login_limit burst=3 nodelay;
    try_files $uri /index.php?$query_string;
}

# Fitxategi sentikorretan sarbidea ukatu
location ~ (composer\.json|composer\.lock|\.env)$ {
    deny all;
}
```

**Konfigurazio nagusiak:**
- `server_tokens off` — Nginx bertsio informazioa ezkutatuta
- Gzip konpresioa gaituta
- Fitxategi estatikoen cachea (1 urteko iraungitzea)
- PHP-FPM upstream bidez

## 2.4 SIEM — Segurtasun Monitorizazioa

### 2.4.1 ELK Stack Arkitektura

Zabala Gailetak-en SIEM sistema **ELK Stack 8.11.0** oinarritua da, Docker bidez hedatuta:

```yaml
# docker-compose.siem.yml — Zabala Gailetak SIEM
services:
  elasticsearch:
    image: docker.elastic.co/elasticsearch/elasticsearch:8.11.0
    environment:
      - discovery.type=single-node
      - "ES_JAVA_OPTS=-Xms512m -Xmx512m"
      - xpack.security.enabled=true
      - ELASTIC_PASSWORD=${ELASTIC_PASSWORD}
    ports:
      - "9200:9200"

  logstash:
    image: docker.elastic.co/logstash/logstash:8.11.0
    volumes:
      - ./security/siem/logstash.conf:/usr/share/logstash/pipeline/logstash.conf:ro
      - /var/log/zabala-gailetak:/var/log/zabala-gailetak:ro
      - /var/log/nginx:/var/log/nginx:ro
    ports:
      - "5044:5044"

  kibana:
    image: docker.elastic.co/kibana/kibana:8.11.0
    ports:
      - "5601:5601"
```

### 2.4.2 Alerta Arauak

15 alerta-arau konfiguratu dira Zabala Gailetak-en azpiegitura babesteko, MITRE ATT&CK Framework-arekin mapatuta:

| ID | Izena | Larritasuna | MITRE | Erantzuna |
|----|-------|-------------|-------|-----------|
| auth-001 | Saio-hasiera Huts Anitz | Altua | T1110 | Alerta + Intzidentzia sortu |
| auth-002 | Kontua Blokeatuta | Ertaina | T1110 | Erabiltzaileari jakinarazi |
| sqli-001 | SQL Injection Erasoa | Kritikoa | T1190 | IP blokeatu + Karantena |
| xss-001 | XSS Saiakera | Altua | T1189 | Alerta + Intzidentzia |
| cmdi-001 | Komando Injekzioa | Kritikoa | T1059 | IP blokeatu + CISO jakinarazi |
| scan-001 | Segurtasun Eskaner Detektatuta | Altua | T1595 | IP blokeatu |
| scan-002 | Direktorio Enumerazioa | Ertaina | T1083 | Rate limit |
| geo-001 | Arrisku Handiko Herrialdea | Ertaina | T1078 | MFA behartu |
| geo-002 | Bidaia Ezinezkoa | Altua | T1078.004 | Kontua eten |
| rate-001 | Rate Limit Gainditua | Baxua | T1499 | Blokeoa luzatu |
| data-001 | Datu Ateratze Handia | Kritikoa | T1567 | Erabiltzailea blokeatu + DPO |
| priv-001 | Pribilegio Igoera | Altua | T1068 | Intzidentzia sortu |
| sys-001 | Sistema Errore Kritikoa | Altua | — | Txartela sortu |
| mfa-001 | MFA Saihestu Saiakera | Kritikoa | T1556 | Saioa baliogabetu + CISO |
| gdpr-001 | Baimenik Gabeko Datu Sarbidea | Kritikoa | — | DPO + Legea jakinarazi |

**Jakinarazpen kanalak:**

```json
{
  "security_team": {
    "email": "security@zabala-gailetak.com",
    "slack": "#security-alerts",
    "pagerduty": "security-oncall"
  },
  "dpo": { "email": "dpo@zabala-gailetak.com" },
  "ciso": { "email": "ciso@zabala-gailetak.com", "sms": "+34-XXX-XXX-XXX" }
}
```

## 2.5 Honeypot Azpiegitura

DMZ sare isolatuan honeypot sistema osoa hedatu da mehatxuen adimena biltzeko. Honeypot-ek Zabala Gailetak-en benetako zerbitzuak imitatzen dituzte erasotzaileak detektatzeko.

### 2.5.1 Honeypot Osagaiak

| Osagaia | Irudia | Helburua | Portuak |
|---------|--------|----------|---------|
| **T-Pot** | telekom-security/tpotce | Honeypot plataforma integrala | Host network |
| **Cowrie** | cowrie/cowrie | SSH/Telnet honeypot-a | 2222, 2223 |
| **Conpot** | honeynet/conpot | ICS/SCADA honeypot-a (OT) | 502 (Modbus), 102 (S7), 47808 (BACnet) |
| **Dionaea** | dinotools/dionaea | Malware bilketa | 21, 445, 1433, 3306 |
| **ElasticPot** | schmalle/elasticpot | Elasticsearch honeypot-a | 9200 |
| **Heralding** | johnnykv/heralding | Kredentzial bilketa | 23, 25, 110, 3389, 5432 |
| **Logstash** | elastic/logstash:8.11.0 | Log agregazioa SIEM-era | 5044, 5045 |
| **Filebeat** | elastic/filebeat:8.11.0 | Log garraiatzailea | — |

### 2.5.2 OT Honeypot (Conpot)

Conpot-ek Zabala Gailetak-en PLC kontrolagailua imitatzen du, ICS protokolo anitz erabiliz:

```yaml
conpot:
    image: honeynet/conpot:latest
    container_name: zabala-conpot
    hostname: zabalagailetak-plc-01
    ports:
      - "80:8080"      # HTTP (PLC Web Interface)
      - "102:102"      # S7Comm (Siemens PLC)
      - "502:502"      # Modbus TCP
      - "161:161/udp"  # SNMP
      - "47808:47808"  # BACnet
      - "44818:44818"  # EtherNet/IP
    environment:
      - CONPOT_DEVICE_NAME=Zabala Gailetak PLC-01
```

Honeypot guztien log-ak SIEM sistema nagusira bideratzen dira Logstash eta Filebeat bidez.

## 2.6 OT Segurtasuna

### 2.6.1 Purdue Eredua

Fabrikako kontrol sistemen segurtasuna Purdue ereduan oinarrituta dago:

| Maila | Izena | Osagaiak |
|-------|-------|----------|
| **Maila 4** | Enpresa Sarea | ERP, Emaila, Web sarbidea |
| **Maila 3.5** | DMZ Industriala | Historialariak, Patch zerbitzaria, Jump Host |
| **Maila 3** | Ekoizpen Operazioak | SCADA zerbitzariak, HMIak |
| **Maila 2** | Kontrol Sarea | PLCak, RTUak |
| **Maila 1/0** | Prozesua | Sentsoreak, Aktuadoreak, Robotak |

### 2.6.2 OT Segurtasun Arauak

- IT eta OT sareen arteko komunikazio zuzena **Debekatuta** — DMZ industrial bidez soilik
- Urruneko sarbidea **VPN + MFA** bidez eta **Jump Host** bidez soilik
- USB memoria pertsonalak **Debekatuta** OT ekipoetan — Kiosko estazio bidez eskaneatu
- OT sistemak **ez eguneratu automatikoki** — laborategian lehenengo probatu
- **Dual-homing debekatuta** — ekipoak sare bakarrean

### 2.6.3 PLC Programa — Gaileta Ekoizpena

Zabala Gailetak-en gaileta fabrikazio prozesua kontrolatzeko **IEC 61131-3** estandarraren araberako PLC programa garatu da OpenPLC-rako:

```iec-st
PROGRAM CookieProduction
  VAR
    // Sarrerak (Factory IO edo HMI-tik)
    StartButton AT %IX0.0 : BOOL;
    StopButton AT %IX0.1 : BOOL;
    EmergencyStop AT %IX0.2 : BOOL;
    OvenTempSensor AT %IW0 : INT;     // 0-250°C
    WeightSensor AT %IW1 : INT;       // Gramoak

    // Irteerak
    ConveyorMotor AT %QX0.0 : BOOL;   // Garraiatzailea
    MixerMotor AT %QX0.1 : BOOL;      // Oratzailea
    OvenHeater AT %QX0.2 : BOOL;      // Labeko berogailua
    AlarmLight AT %QX0.3 : BOOL;      // Alarma argia
    ExtruderValve AT %QX0.4 : BOOL;   // Estrusore balbula

    // Barne aldagaiak
    State : INT := 0;  // 0: Geldirik, 1: Oratzen, 2: Estrusio, 3: Erretzen
    TargetTemp : INT := 720;  // ~180°C
  END_VAR

  // LARRIALDI LOGIKA
  IF EmergencyStop THEN
    ConveyorMotor := FALSE;
    MixerMotor := FALSE;
    OvenHeater := FALSE;
    ExtruderValve := FALSE;
    AlarmLight := TRUE;
    State := 0;
    RETURN;
  END_IF;

  // EGOERA-MAKINA
  CASE State OF
    0: // Geldirik — motor guztiak gelditu
    1: // Oratzen — MixerMotor aktibatu 10s-z
    2: // Estrusio — ExtruderValve ireki 500g arte
    3: // Erretzen — Garraiatzailea + tenperatura kontrola (180°C)
  END_CASE;
END_PROGRAM

CONFIGURATION Config0
  RESOURCE Res0 ON PLC
    TASK TaskMain(INTERVAL := T#50ms, PRIORITY := 0);
    PROGRAM Inst0 WITH TaskMain : CookieProduction;
  END_RESOURCE
END_CONFIGURATION
```

**PLC Egoera-makinaren faseak:**

1. **Geldirik (State 0):** Motor guztiak geldituta, StartButton itxaronez
2. **Oratzen (State 1):** Oratzaile motorra 10 segundoz aktibatuta
3. **Estrusio (State 2):** Balbula irekita, 500g pisura arte
4. **Erretzen (State 3):** Garraiatzailea + labea 180°C-ra, 30 segundo

### 2.6.4 OT Docker Ingurunea

OpenPLC eta ScadaBR Docker bidez hedatuta daude garapen eta proba ingurunean:

```yaml
# docker-compose.ot.yml
services:
  openplc:
    image: openplcproject/openplc:v3
    container_name: zabala-openplc
    ports:
      - "8080:8080"   # Web interfazea
      - "502:502"     # Modbus TCP
    volumes:
      - ./openplc/programs:/workdir/programs

  scadabr:
    image: scadabr/scadabr:latest
    container_name: zabala-scadabr
    ports:
      - "9090:8080"   # ScadaBR web
```

## 2.7 Babeskopia eta Berreskurapena

### 2.7.1 PostgreSQL Backup Script-a

Eguneroko babeskopiak automatikoki egiten dira ZG-Data VM-an:

```bash
#!/bin/bash
# /usr/local/bin/backup-db.sh — Zabala Gailetak DB Backup

BACKUP_DIR="/backups/postgres"
CONTAINER_NAME="zabala-postgres"
DB_USER="zabala_user"
DB_NAME="zabala_db"
RETENTION_DAYS=7

mkdir -p $BACKUP_DIR
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
FILENAME="db_$TIMESTAMP.sql.gz"

echo "[$(date)] Babeskopia hasitzen..."
docker exec $CONTAINER_NAME pg_dump -U $DB_USER $DB_NAME | gzip > "$BACKUP_DIR/$FILENAME"

# Babeskopia zaharrak garbitu (7 egun baino zaharragoak)
find $BACKUP_DIR -name "db_*.sql.gz" -mtime +$RETENTION_DAYS -delete
```

**Cron programazioa:** Egunero goizeko 02:00etan automatikoki exekutatzen da.

**Berreskuratze script-a** ere prestatuta dago edozein babeskopiatik berrezartzeko.

---

<div style="page-break-after: always;"></div>

# 3. EKOIZPEN SEGURUAN JARTZEA (DevSecOps)

Atal honek Zabala Gailetak-en GG Atari (PHP) eta Android aplikazioaren garapen segurua, CI/CD pipeline-ak eta hedapen prozesuak deskribatzen ditu.

## 3.1 CI/CD — Integrazio eta Hedapen Jarraitua

### 3.1.1 GitHub Actions Pipeline-ak

Bi pipeline nagusi konfiguratu dira:

**1. Sintaxi Egiaztapena (ci-minimal.yml):**

```yaml
name: CI - Syntax Check
on:
  push:
    branches: [ "main" ]
  pull_request:
    branches: [ "main" ]

jobs:
  syntax-check:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
      - name: Check PHP Syntax
        run: |
          find . -type f -name "*.php" -print0 | xargs -0 -n1 -P8 php -l
```

Push eta Pull Request bakoitzean PHP 8.4 sintaxi zuzena bermatzen du 8 prozesu paralelorekin.

**2. Hedapena InfinityFree-ra (deploy.yml):**

```yaml
name: Deploy to InfinityFree
on:
  workflow_dispatch:  # Eskuzko aktibatzea soilik

jobs:
  deploy:
    runs-on: ubuntu-latest
    if: github.ref == 'refs/heads/main'
    steps:
      - uses: actions/checkout@v4
      - uses: SamKirkland/FTP-Deploy-Action@v4.3.5
        with:
          server: ${{ secrets.FTP_SERVER }}
          username: ${{ secrets.FTP_USERNAME }}
          password: ${{ secrets.FTP_PASSWORD }}
          protocol: ftps
          local-dir: "./Zabala Gailetak/hr-portal/"
          server-dir: "/htdocs/"
          exclude: |
            **/.git*/**
            **/tests/**
            **/.env.example
            **/phpunit.xml
            **/Dockerfile
```

**Segurtasun neurriak:**
- FTPS protokoloa (enkriptatuta)
- Kredentzialak GitHub Secrets bidez gordeta
- Test fitxategiak, Dockerfile eta konfigurazio fitxategiak baztertu
- Eskuzko aktibatzea soilik (workflow_dispatch)

### 3.1.2 Hedapen Arkitektura

| Ingurunea | Plataforma | Helburua |
|-----------|-----------|----------|
| **Garapena** | Docker (Lokala) | PostgreSQL + Redis + PHP-FPM + Nginx |
| **Proba** | IsardVDI (Proxmox) | 6 VM: Gateway, Data, App, SecOps, OT, Client |
| **Produkzioa** | InfinityFree | FTPS bidezko PHP hedapena |

## 3.2 Docker Hedapena

### 3.2.1 Docker Compose — GG Ataria

```yaml
# docker-compose.hrportal.yml — Zabala Gailetak GG Ataria
services:
  postgres:
    image: postgres:16-alpine
    environment:
      POSTGRES_DB: hr_portal
      POSTGRES_USER: hr_user
    volumes:
      - postgres_data:/var/lib/postgresql/data
      - ./hr-portal/migrations:/docker-entrypoint-initdb.d
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U hr_user -d hr_portal"]

  redis:
    image: redis:7-alpine
    command: redis-server --appendonly yes
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]

  php:
    build:
      context: ./hr-portal
      dockerfile: Dockerfile
    volumes:
      - ./hr-portal:/var/www/html
    environment:
      APP_ENV: development
      DB_HOST: host.docker.internal

  nginx:
    image: nginx:alpine
    ports:
      - "8080:80"
      - "8443:443"
    volumes:
      - ./hr-portal/public:/var/www/html/public
      - ./nginx/nginx-hrportal.conf:/etc/nginx/nginx.conf:ro
    healthcheck:
      test: ["CMD", "wget", "--quiet", "--spider", "http://localhost/api/health"]
```

### 3.2.2 PHP Dockerfile

```dockerfile
FROM php:8.4-fpm-alpine

RUN apk add --no-cache postgresql-dev libpng-dev libjpeg-turbo-dev \
    freetype-dev zip git curl

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_pgsql gd opcache bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html

# OPcache gaituta errendimendurako
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini

EXPOSE 9000
CMD ["php-fpm"]
```

## 3.3 GG Ataria — PHP Web Aplikazioa

### 3.3.1 Arkitektura Orokorra

Zabala Gailetak-en Giza Baliabideen (GG) Ataria **PHP 8.4** oinarritutako web aplikazioa da, **PSR-12** kode estandarrekin:

| Osagaia | Teknologia | Helburua |
|---------|-----------|----------|
| **Backend** | PHP 8.4-FPM | API REST eta logika |
| **Datu-basea** | PostgreSQL 16 | Datu iraunkorra |
| **Cache** | Redis 7 | Saio kudeaketa |
| **Web Zerbitzaria** | Nginx | Reverse proxy + segurtasuna |
| **Autentifikazioa** | JWT natiboa (HMAC-SHA256) | Token-etan oinarritutako auth |
| **MFA** | TOTP (30s) | Bigarren faktorea |
| **RBAC** | 4 rol, 43+ baimen | Sarbide kontrola |

### 3.3.2 Autentifikazio Sistema

**JWT Token Kudeaketa (Natiboa):**
- HMAC-SHA256 bidezko sinadura (kanpo liburutegirik gabe — Zero Trust)
- Access Token-en iraungitzea: 1 ordu
- Refresh Token-en iraungitzea: 7 egun
- Base64Url kodeketa
- Token ID bakarra (jti) refresh token jarraipenrako

**TOTP MFA Inplementazioa:**
- QR kodeen bidez autentifikatzaile aplikazioetan izen-ematea
- 30 segundoko denbora-leihoa
- Babeskopia kodeak kontu berreskuratzeko

**RBAC — Roleen Araberako Sarbide Kontrola:**

| Rola | Baimen Kopurua | Adibideak |
|------|---------------|-----------|
| **ADMIN** | 43+ | Guztia kudeatu |
| **HR_MANAGER** | 30+ | Langileak, nominak, oporrak |
| **DEPARTMENT_HEAD** | 20+ | Sail-mailako kudeaketa |
| **EMPLOYEE** | 10+ | Profila ikusi, oporrak eskatu |

### 3.3.3 API Endpoint-ak

**Autentifikazioa:**

| Metodoa | Bide-izena | Funtzioa |
|---------|-----------|----------|
| POST | `/api/auth/login` | Saio-hasiera (email/pasahitz) |
| POST | `/api/auth/mfa/verify` | TOTP egiaztapena |
| POST | `/api/auth/refresh` | JWT token berriztapena |
| GET | `/api/auth/me` | Uneko erabiltzailea |
| POST | `/api/auth/logout` | Saio-amaiera segurua |
| POST | `/api/auth/mfa/setup` | MFA izen-ematea |
| POST | `/api/auth/mfa/enable` | MFA aktibatu |

**Langileen Kudeaketa:**

| Metodoa | Bide-izena | Funtzioa |
|---------|-----------|----------|
| GET | `/api/employees` | Zerrenda (orriatuta, iragazita) |
| GET | `/api/employees/{id}` | Xehetasunak |
| POST | `/api/employees` | Langile berria |
| PUT | `/api/employees/{id}` | Eguneratu |
| DELETE | `/api/employees/{id}` | Ezabatu (soft delete) |
| POST | `/api/employees/{id}/restore` | Berrezarri |
| GET | `/api/employees/{id}/history` | Audit trail |

**Oporrak eta Auditoria:**

| Metodoa | Bide-izena | Funtzioa |
|---------|-----------|----------|
| GET | `/api/vacations/balance` | Opor saldoa |
| POST | `/api/vacations/requests` | Opor eskaera |
| GET | `/api/vacations/calendar` | Opor egutegia |
| GET | `/api/audit/user/{userId}` | Erabiltzaile jarduera |
| GET | `/api/health` | Osasun egiaztapena |

### 3.3.4 Sarrera Baliozkotzea

Zabala Gailetak-en langileen datuak balidatzeko balidazio espezifikoak inplementatu dira:

| Eremua | Baliozkotzea | Adibidea |
|--------|-------------|----------|
| **NIF/NIE** | Letra algoritmoarekin | 12345678Z / X1234567L |
| **IBAN** | Checksum egiaztapena | ES9121000418450200051332 |
| **Telefonoa** | Espainiako formatua (+34) | +34 612345678 |
| **Posta Kodea** | 00000-52999 tartea | 48001 |
| **Emaila** | RFC5322 baliozkotzea | langile@zabala.com |
| **Pasahitza** | Indarra bermatzea | 12+ karaktere, konplexua |

**XSS saneamendua** sarrera guztietan aplikatzen da.

### 3.3.5 Segurtasun Neurriak

- **CSRF Babesa:** Double-submit cookie eredua
- **SQL Injekzio Prebentzioa:** Prepared statements
- **XSS Prebentzioa:** Sarrera saneamendua + CSP goiburuak
- **Segurtasun Goiburuak:** X-Frame-Options, X-Content-Type-Options, X-XSS-Protection
- **Audit Trail:** Operazio guztiak erregistratuta
- **Pasahitz Hashing:** bcrypt (12+ erronda)

### 3.3.6 Datu-base Eskema

**11 taula:**

| Taula | Helburua |
|-------|----------|
| `users` | Autentifikazioa eta kudeaketa |
| `employees` | Langile erregistroak |
| `departments` | Sailen antolaketa |
| `roles` | Rol definizioak |
| `permissions` | Baimen definizioak |
| `role_permissions` | RBAC mapaketa |
| `audit_logs` | Auditoretza arrasto osoa |
| `vacations` | Opor eskaerak |
| `vacation_balances` | Urteko opor jarraipena |
| `documents` | Dokumentu metadata |
| `sessions` | Saio kudeaketa |

**4 migrazio fitxategi:**
1. `001_init_schema.sql` — Hasierako egitura
2. `002_seed_data.sql` — Adibide datuak eta admin erabiltzailea
3. `003_audit_trail.sql` — Auditoretza taulak
4. `004_create_vacations_tables.sql` — Opor sistema

### 3.3.7 Test Emaitzak

**82/82 unit test gainditu:**

| Test Taldea | Kopurua | Estaldura |
|-------------|---------|-----------|
| TokenManager | 11 | JWT sortzea/baliozkotzea |
| EmployeeController | 11 | CRUD + RBAC |
| EmployeeValidator | 40 | Espainiako baliozkotzeak |
| AuditLogger | 11 | Audit trail erregistroa |
| AuditController | 9 | Audit historia eskuratzea |

**Kalitate tresnak:** PHPUnit, PHPStan (analisi estatikoa), PHPCS (PSR-12 kodea)

## 3.4 Android Aplikazioa — Kotlin

### 3.4.1 Konfigurazio Teknikoa

| Ezaugarria | Balioa |
|-----------|--------|
| **Hizkuntza** | Kotlin 2.0.21 |
| **UI Framework** | Jetpack Compose (BOM 2024.12.01) |
| **Arkitektura** | Clean Architecture + MVI |
| **DI** | Hilt (Dagger 2.54) |
| **HTTP** | Retrofit 2.11.0 + OkHttp |
| **DB Lokala** | Room 2.6.1 |
| **Min SDK** | 24 (Android 7.0) |
| **Target SDK** | 35 (Android 15) |
| **JVM Target** | 21 |

### 3.4.2 Proiektuaren Egitura (Clean Architecture)

```
com/zabalagailetak/hrapp/
├── data/                    # Datu geruza
│   ├── api/                 # Retrofit zerbitzuak
│   │   ├── AuthApiService.kt
│   │   ├── EmployeeApiService.kt
│   │   ├── VacationApiService.kt
│   │   ├── DocumentApiService.kt
│   │   └── PayslipApiService.kt
│   ├── local/               # Room datu-basea
│   └── repository/          # Repository inplementazioak
│
├── di/                      # Dependency Injection (Hilt)
│   ├── AppModule.kt
│   ├── NetworkModule.kt
│   ├── DatabaseModule.kt
│   └── RepositoryModule.kt
│
├── domain/                  # Domeinu geruza
│   ├── model/               # Datu ereduak
│   ├── repository/          # Repository interfazeak
│   └── usecase/             # Erabilera kasuak
│
├── presentation/            # UI geruza
│   ├── MainActivity.kt
│   ├── navigation/
│   ├── auth/                # LoginScreen + AuthViewModel
│   ├── dashboard/
│   ├── vacation/
│   ├── documents/
│   ├── payslips/
│   └── ui/theme/            # Material Design 3
│
├── security/                # Segurtasun utilitateak
└── HrApplication.kt        # Hilt Application
```

### 3.4.3 Autentifikazio Fluxua

```kotlin
// AuthApiService.kt
interface AuthApiService {
    @POST("auth/login")
    suspend fun login(@Body request: LoginRequest): Response<LoginResponse>

    @POST("auth/mfa/verify")
    suspend fun verifyMfa(@Body request: MfaVerificationRequest): Response<LoginResponse>

    @POST("auth/logout")
    suspend fun logout(): Response<Unit>

    @GET("auth/me")
    suspend fun getCurrentUser(): Response<Employee>
}
```

**Base URL-ak:**
- Debug: `http://10.0.2.2:8080/api/` (Android emuladorea → host)
- Release: `https://zabala-gailetak.infinityfreeapp.com/api/`

### 3.4.4 Segurtasun Ezaugarriak

| Neurria | Inplementazioa |
|---------|---------------|
| **Ziurtagiri Pinning** | OkHttp CertificatePinner |
| **Token Biltegiratzea** | EncryptedSharedPreferences |
| **Biometria** | BiometricPrompt (hatz-markak) |
| **Sare Segurtasuna** | network_security_config.xml (HTTPS soilik produkzioan) |
| **Obfuskazioa** | ProGuard/R8 (release build) |
| **Baimenak** | Internet, Biometria, Kamera |
| **Cleartext Trafikoa** | Debekatuta produkzioan |

**AndroidManifest.xml baimenak:**
```xml
<uses-permission android:name="android.permission.INTERNET" />
<uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
<uses-permission android:name="android.permission.USE_BIOMETRIC" />
<uses-permission android:name="android.permission.CAMERA" />
```

## 3.5 SSDLC — Garapen Seguru Bizi-Zikloa

Zabala Gailetak-en garapen prozesu osoan **OWASP ASVS** eta **SSDLC** printzipioak jarraitu dira:

| Fasea | Neurriak |
|-------|---------|
| **Diseinua** | Mehatxu eredutzea, segurtasun baldintzak |
| **Garapena** | Prepared statements, XSS saneamendua, CSRF tokenak |
| **Probak** | 82 unit test, PHPStan, PHPCS (PSR-12) |
| **Hedapena** | CI/CD automatikoa, Docker isolamendua, FTPS |
| **Monitorizazioa** | SIEM alertak, audit trail, rate limiting |

---

<div style="page-break-after: always;"></div>

# 4. HACKING ETIKOA

Atal honek Zabala Gailetak-en sistemen aurkako penetrazio probak deskribatzen ditu. Proba hauek modu kontrolatuan egin dira, ahultasunak identifikatzeko eta konpontzeko.

## 4.1 Helburua eta Irismena

**Helburua:** Zabala Gailetak-eko sistemen segurtasuna ebaluatzea, ahultasunak kontrolatutako modu batean ustiatuz konponbideak proposatzeko.

**Irismena:**
- **Barne:** Sarea (Wi-Fi eta Kableatua), Web aplikazioa, Mugikor aplikazioa
- **Mugak:**
  - **EZ** eraso OT sareko makineria martxan dagoen bitartean (arrisku fisikoa)
  - **EZ** egin DoS erasorik produkzio sistemetan
  - Jarduera guztiak **baimen idatziarekin** soilik

## 4.2 Metodologia (PTES / OSSTMM)

### 4.2.1 Fase 1: Informazio Bilketa (Reconnaissance)

**Bilketa Pasiboa:**
- **Google Dorks:** Zabala Gailetak-ekin lotutako informazio publikoa bilatu
- **Shodan:** Internetera irekitako portuak eta zerbitzuak identifikatu
- **Whois:** Domeinu erregistro informazioa eskuratu
- **TheHarvester:** Email helbideak eta azpidomeinuak aurkitu

**Bilketa Aktiboa:**
- **Nmap:** Portu eskanerra eta zerbitzuen identifikazioa
  ```bash
  nmap -sV -sC -O -p- 192.168.20.10  # ZG-App zerbitzaria
  nmap -sV -p 502,8080 172.16.0.0/16  # OT sarea (Modbus)
  ```

### 4.2.2 Fase 2: Ahultasunen Analisia

**Eskaneatze Automatizatua:**
- **Nessus / OpenVAS:** Sistema eta zerbitzu ahultasunen identifikazioa
- **OWASP ZAP:** Web aplikazioaren eskaneatze automatikoa
- **Burp Suite:** Web trafiko analisi sakona

**Proba Espezifikoak (OWASP Top 10):**

| # | Kategoria | Proba |
|---|----------|-------|
| A01 | Sarbide Kontrol Hautsita | RBAC saihestu saiakerak, IDOR probak |
| A02 | Kriptografia Akatsa | TLS bertsioak, pasahitz hashing |
| A03 | Injekzioa | SQL Injection (prepared statements bidez arinduta) |
| A04 | Diseinu Ez-segurua | API endpoint-en segurtasun analisia |
| A05 | Konfigurazio Okerra | Segurtasun goiburuak, server tokens |
| A06 | Osagai Ahulak | Dependentzien ahultasun analisia |
| A07 | Identifikazio Akatsa | JWT tokenaren egiaztapena, MFA bypass saiakerak |
| A08 | Software Osotasuna | CI/CD pipeline segurtasuna |
| A09 | Log/Monitorizazio Akatsa | Audit trail osotasuna |
| A10 | SSRF | Server-Side Request Forgery probak |

### 4.2.3 Fase 3: Ustiapena (Exploitation)

- **Metasploit** erabili detektatutako CVE-ak ustiatzeko
- Helburua: Administratzaile baimenak eskuratzea edo "flag"-a lortzea
- Bezeroaren baimen idatzia beharrezkoa eraso bakoitzerako

### 4.2.4 Fase 4: Ustiapen Ostekoa (Post-Exploitation)

- **Pribilegioen igoera** (Privilege Escalation) saiakerak
- **Pibotajea** — sare batetik bestera jauzi egiteko saiakerak (IT → OT)
- Datu sentikorrak bilatzeko saiakerak (frogak lortzeko)

### 4.2.5 Fase 5: Txostena

- Aurkitutako ahultasunak **CVSS puntuazioarekin** zerrendatu
- Ustiapenaren froga (PoC - Proof of Concept)
- Konponbide teknikoak proposatu ahultasun bakoitzerako

## 4.3 Tresnak

| Tresna | Helburua |
|--------|----------|
| **Nmap** | Portu eskaneatzea eta zerbitzuen identifikazioa |
| **Nessus** | Ahultasun eskaneatzea |
| **OpenVAS** | Ahultasun eskaneatzea (kode irekia) |
| **Metasploit** | Ustiapen framework-a |
| **Burp Suite** | Web aplikazio probak |
| **OWASP ZAP** | Web eskaneatze automatikoa |
| **TheHarvester** | Informazio bilketa |
| **Wireshark** | Sare trafiko analisia |

## 4.4 Aurkitutako Ahultasunak eta Konponketak

Penetrazio proben ondorioz, honako neurriak hartu ziren Zabala Gailetak-en segurtasuna sendotzeko:

| Ahultasuna | Konponketa Aplikatua |
|-----------|---------------------|
| SSH root sarbidea | `PermitRootLogin no` konfiguratu |
| Nginx server tokens | `server_tokens off` gaituta |
| Rate limiting falta | login_limit (5r/m) eta api_limit (10r/s) |
| CSRF babesa falta | Double-submit cookie eredua inplementatu |
| XSS ahultasuna | CSP goiburuak + sarrera saneamendua |
| SQL Injection arriskua | Prepared statements guztiz inplementatuta |
| Pasahitz testu laua | bcrypt 12+ errondarekin inplementatuta |

---

<div style="page-break-after: always;"></div>

# 5. ZIBERSEGURTASUN-GORABEHERAK

Atal honek Zabala Gailetak-en intzidentzien erantzun plana, SOAR playbook-ak eta OT erasoen simulazioak deskribatzen ditu.

## 5.1 Intzidentzien Erantzun Prozedura (NIST Eredua)

### 5.1.1 Fase 1: Prestaketa

- **CSIRT Taldea:** IKT arduraduna, Segurtasun arduraduna, Zuzendaritza
- **Tresnak:** SIEM (ELK Stack), forentse tresnak, komunikazio bide alternatiboak
- **Formakuntza:** Aldian-aldian simulakroak egitea

### 5.1.2 Fase 2: Detekzioa eta Analisia

- **Alerta iturriak:** SIEM, IDS, Antibirusa, erabiltzaileen jakinarazpenak
- **Triajea:**
  1. Egiaztatu: Benetako intzidentzia ala positibo faltsua?
  2. Kategorizatu: Ransomware, DDoS, Datu ihesa, etab.
  3. Lehenetsi: Kritikoa → Altua → Ertaina → Baxua
- **Erregistroa:** Intzidentzia berria `incident_log_template.md` bidez ireki

### 5.1.3 Fase 3: Euste-neurriak (Containment)

- **Berehalakoa:** Kaltetutako sistemak saretik isolatu (kablea kendu edo VLAN isolatua)
  - **GARRANTZITSUA:** Ez itzali ekipoa — RAM memoria galdu daiteke (forentserako funtsezkoa)
- **Epe laburrera:** Erasotzaileen IPak suebakian blokeatu, kaltetutako pasahitzak aldatu

### 5.1.4 Fase 4: Desagerraraztea (Eradication)

- Malwarearen jatorria eta sarrera puntua identifikatu
- Sistemak garbitu: Antibirusa, rootkit bilaketa
- Kasu larrietan: Formateatu eta hutsetik instalatu (ebidentziak gorde ondoren)
- Ahultasuna partxeatu

### 5.1.5 Fase 5: Berreskurapena (Recovery)

- Babeskopietatik berrezarri (ziurtatu garbia dela)
- Hurrengo ordu/egunetan gertutik monitorizatu
- Zerbitzua erabiltzaileentzat berrezarri

### 5.1.6 Fase 6: Ikasitako Lezioak (Post-Incident)

- Bilera 2 asteko epean intzidentzia itxi ondoren
- Txostena: Zer funtzionatu du? Zer ez? Zer hobetu behar da?
- Segurtasun Plana eta prozedurak eguneratu

## 5.2 Komunikazio Plana

| Hartzailea | Noiz | Kanal |
|-----------|------|-------|
| **Barnekoa** | Berehala | CSIRT → Zuzendaritza → Langileak |
| **Bezeroak** | Datu pertsonalak arriskuan | GDPR — 72 orduko epea |
| **AEPD** | Datu-urraketa baieztatuta | 72 orduko jakinarazpena |
| **INCIBE** | Laguntza behar denean | 017 telefonoa (24/7) |
| **Prentsa** | Komunikazio arduradunaren bidez soilik | Prentsa-oharra |

## 5.3 SOAR Playbook-ak (Alerta Arauak)

SIEM sistemak 15 alerta-arau ditu automatizatutako erantzunekin (ikus 2.4.2 atala). Erantzun automatizatu nagusiak:

| Erantzun Ekintza | Metodoa | Iraupena |
|-----------------|---------|----------|
| **IP Blokeatzea** | Suebaki araua | 1 ordu (errepikatuta → betirako) |
| **Erabiltzaile Blokeatzea** | Kontua etetea | Ikerketaren zain |
| **Rate Limit** | API throttle | 15 minutu |
| **Karantena** | Trafikoa isolatu | Berrikuspena behar |

## 5.4 OT Intzidentzia Simulazioa

### 5.4.1 Simulazioaren Xehetasunak

- **Data:** 2024/05/20
- **Mota:** OT sarera baimenik gabeko sarbidea (Modbus manipulazioa)
- **Parte-hartzaileak:** IT Taldea, OT Mantentze Taldea, Zibersegurtasun Arduraduna

### 5.4.2 Erasoaren Deskribapena

Erasotzaile batek (Red Team) bulegoko saretik (IT) produkzio sarera (OT) jauzi egin zuen, gaizki konfiguratutako ingeniaritza-estazio bat erabiliz (**Dual-homed PC** — bi sareetara konektatuta). Erasotzaileak **Modbus** komandoak bidali zizkion labeko PLCari tenperatura arriskutsu batera igotzeko.

### 5.4.3 Detekzioa

- **Ordua:** 10:15
- **SIEM alertak:** "Modbus Write Coil" komando ezohikoak detektatu zituen PLC kritiko batean, baimendu gabeko IP helbide batetik
- **Operadorea:** HMI pantailan tenperatura igotzen ikusi → **Larrialdi Geldialdia** fisikoki aktibatu

### 5.4.4 Erantzuna

1. **Isolamendua (10:20):** IT-OT konexio guztiak moztu — "Panic Button" politika
2. **Identifikazioa (10:30):** Sare trafikoa aztertuz, jatorria "INGENIARITZA-PC-03"
3. **Forentse Analisia:** Diskaren irudia egin → RAT malwarea aurkitu (USB kutsatu bidez sartua)

### 5.4.5 Ekintza Zuzentzaileak

| Ahultasuna | Konponketa |
|-----------|------------|
| Dual-homing PC | Guztiz **debekatuta** — estazio bakoitza sare bakarrean |
| USB sarbidea | USB gailuak **blokeatu** dira OT eremuan |
| PLC urruneko idazketa | **Read-Only** modua aktibatu, aldaketak fisikoki bakarrik |

### 5.4.6 Ondorioa

Simulazioak erantzun denbora onak erakutsi zituen (**15 minutu**), baina segmentazio fisikoa hobetu behar zela ondorioztatu zen giza akatsak ekiditeko.

---

<div style="page-break-after: always;"></div>

# 6. AUZITEGI-ANALISI INFORMATIKOA

Atal honek Zabala Gailetak-en ebidentzia digitalen bilketa prozedurak, forentse tresnak eta analisi metodologia deskribatzen ditu.

## 6.1 Helburua

Intzidentzia baten ondoren ebidentzia digitalak modu seguruan biltzea, **zaintza-katea** bermatuz, ondoren auzitegi-analisia egiteko. Prozesu honek Zabala Gailetak-en merkataritza-sekretuak, langile datuak eta OT sistemen osotasuna babesten ditu.

## 6.2 Printzipioak (RFC 3227)

1. **Hegakortasun Ordena:** Memoria hegakorrenetik iraunkorrenera bildu:
   - CPU Cache → RAM → Swap → Diskoa → Babeskopiak
2. **Ez aldatu:** Jatorrizko ebidentzia **inoiz ez** manipulatu — bit-bit kopia egin eta horren gainean lan egin

## 6.3 Ebidentzien Bilketa Prozedura (SOP)

### 6.3.1 Fase 1: Eszena Babestea

- Gailua saretik deskonektatu (baina **ez itzali** oraindik)
- Baimenik gabeko pertsonen sarbidea eragotzi
- Argazkiak atera pantailari eta konexio fisikoei

### 6.3.2 Fase 2: Datu Hegakorren Bilketa (Live Response)

Sistema piztuta badago, bilketa tresnak USB seguru batetik exekutatu (**ez instalatu ezer** sisteman):

```bash
# Komandoak (Linux)
date                    # Ordua eta data
hostname                # Gailu izena
uname -a                # Sistema informazioa
ifconfig                # Sare konfigurazioa
netstat -anp            # Sare konexioak
ps aux                  # Exekutatzen diren prozesuak
lsof                    # Irekitako fitxategiak
```

**RAM Memoria bilketa:**
- **LiME** (Linux) edo **WinPmem** (Windows) erabili memoriaren volcadoa egiteko

### 6.3.3 Fase 3: Diskoaren Irudia (Dead Acquisition)

1. Sistema itzali (araututako moduan edo kabletik)
2. **Idazketa-blokeatzaileak** (Write Blockers) erabili diskoa konektatzean
3. `dc3dd` edo `Guymager` bidez irudia egin
4. **SHA-256 HASH** kalkulatu jatorrizko diskoan eta irudian — biak berdinak izan behar dute

### 6.3.4 Fase 4: Zaintza Katea (Chain of Custody)

Pauso guztiak dokumentatu **Zaintza Katearen Orrian**:

| Eremua | Deskribapena |
|--------|-------------|
| **Ebidentzia** | Marka, Eredua, Serie zenbakia |
| **Biltzailea** | Nork bildu du? |
| **Data/Ordua** | Noiz bildu da? |
| **Kokapena** | Non aurkitu da? |
| **Gordetzailea** | Nork dauka orain? |
| **Biltegi Kokapena** | Non gordetzen da? |

### 6.3.5 Fase 5: Analisia

- **Autopsy** erabili disko irudiaren analiserako
- **Volatility3** erabili RAM memoriaren analiserako
- Bilatu: Ezabatutako fitxategiak, nabigazio historia, log susmagarriak, malwarea

## 6.4 Forentse Tresna-kutxa

### 6.4.1 Instalazioa

```bash
#!/bin/bash
# install-tools.sh — Zabala Gailetak Forensics Toolkit

# Disko forensea
apt-get install -y sleuthkit autopsy foremost testdisk photorec

# Memoria forensea
pip3 install volatility3

# Sare forensea
apt-get install -y wireshark tcpdump tshark

# Log analisia
apt-get install -y jq

# Ebidentzia direktorioak sortu
mkdir -p /evidence/{disk,memory,network,mobile,logs}
chmod 700 /evidence
```

### 6.4.2 Memoria Bilketa Script-a

```bash
#!/bin/bash
# memory-dump.sh — Zabala Gailetak Memory Acquisition
CASE_ID=$1
OUTPUT_DIR="/evidence/memory/$CASE_ID"
mkdir -p "$OUTPUT_DIR"

echo "[+] Memoria volcadoa egiten..."
# LiME bidez memoria eskuratzea
# SHA-256 zaintza katearako

echo "[✓] Memoria volcadoa osatuta: $OUTPUT_DIR"
```

### 6.4.3 Tresna Zerrenda

| Tresna | Mota | Helburua |
|--------|------|----------|
| **Sleuthkit** | Disko | Disko irudien analisia |
| **Autopsy** | Disko | GUI interfazea Sleuthkit-erako |
| **Foremost** | Disko | Fitxategi berreskuratzea |
| **TestDisk** | Disko | Partizioen berreskuratzea |
| **PhotoRec** | Disko | Irudi/fitxategi berreskuratzea |
| **Volatility3** | Memoria | RAM memoriaren analisia |
| **Wireshark** | Sarea | Sare trafiko analisia |
| **tcpdump** | Sarea | Pakete kaptura |
| **LiME** | Memoria | Linux memoria volcadoa |
| **WinPmem** | Memoria | Windows memoria volcadoa |
| **dc3dd** | Disko | Disko irudien sorrera |
| **Guymager** | Disko | GUI disko irudien tresna |

## 6.5 Txosten Forensea Txantiloia

```markdown
# Auzitegi Ikerketa Txostena

**Kasu IDa:** [KASU-UUUU-HHII-XXX]
**Ikertzailea:** [Izena, Ziurtagiria]
**Data:** [UUUU-HH-II]

## 1. Laburpen Exekutiboa
[Intzidentearen eta aurkikuntzen laburpena]

## 2. Ikerketaren Esparrua
- Aztertutako Sistemak: [Zerrenda]
- Denbora Tartea: [Datak]
- Bildutako Ebidentziak: [Kopurua]

## 3. Zaintza Katea
[Ebidentzien kudeaketaren taula]

## 4. Analisi Aurkikuntzak
[Denbora-lerroa, artefaktuak, erasotzailearen profila]

## 5. Ondorioak eta Gomendioak
[Azken ondorioak eta segurtasuna hobetzeko gomendioak]
```

---

<div style="page-break-after: always;"></div>

# 7. ZIBERSEGURTASUNAREN ARLOKO ARAUDIA

Atal honek Zabala Gailetak-en araudi betetzea deskribatzen ditu: GDPR, SGSI (ISO 27001), NIS2, IEC 62443 eta negozioaren jarraitasun plana.

## 7.1 GDPR — Datuen Babeserako Erregelamendu Orokorra

Zabala Gailetak-ek **GDPR (EB 2016/679)** eta **DBLO-GDD (Espainiako 3/2018 Lege Organikoa)** betetzen ditu. 14 dokumentu sortu dira:

### 7.1.1 Cookie Politika

- Cookie motak: **Funtsezkoak** (beharrezkoak), **Analitikoak** (Google Analytics), **Marketina** (publizitatea)
- Baimena: GDPR 6(1)(a) artikuluaren araberako adostasun esplizitua
- Cookie banner-a web atarian

### 7.1.2 Datu-haustearen Jakinarazpena

- **72 orduko epea** AEPDri jakinarazteko (GDPR 33. artikulua)
- Arrisku matrizea intzidentziaren larritasuna ebaluatzeko
- Jakinarazpen txantiloi estandarizatua
- Kaltetutako pertsonei jakinarazteko prozedura (GDPR 34. artikulua)

### 7.1.3 Datuen Tratamendu-erregistroa (ROPA)

Zabala Gailetak-en tratamendu-jarduera nagusiak:

| Jarduera | Oinarri Juridikoa | Atxikipen Epea |
|---------|-------------------|----------------|
| **Bezero kudeaketa** | Kontratua | 10 urte |
| **GG kudeaketa** | Kontratua + Legea | 4 urte (langileen datuak) |
| **Bideo zaintza** | Interes legitimoa | 30 egun |
| **Osasun laborala** | Legezko betebeharra | 40 urte |
| **Finantza erregistroak** | Legezko betebeharra | 7 urte |

### 7.1.4 Datu-titularren Eskubideak (GDPR 15-22)

| Eskubidea | GDPR Art. | Epea |
|-----------|----------|------|
| **Sarbidea** | 15. art. | 30 egun |
| **Zuzenketa** | 16. art. | 30 egun |
| **Ezabaketa** (Ahaztua izateko eskubidea) | 17. art. | 30 egun |
| **Tratamenduaren mugatzea** | 18. art. | 30 egun |
| **Eramangarritasuna** | 20. art. | 30 egun |
| **Aurkaritza** | 21. art. | 30 egun |
| **Erabaki automatizatuen aurkaritza** | 22. art. | 30 egun |

### 7.1.5 DPIA — Datuen Babesaren Eragin Ebaluazioa

Ebaluazio sistematikoa arriskuak identifikatzeko datu pertsonalen tratamenduan, GDPR 35. artikuluaren arabera.

### 7.1.6 Datuen Atxikipen Egutegia

| Datu Mota | Atxikipen Epea | Oinarria |
|----------|----------------|---------|
| Finantza erregistroak | 7 urte | Merkataritza Kodea |
| Langile datuak | 4 urte | Langileen Estatutua |
| Osasun laborala | 40 urte | Prebentzio Legea |
| Bideo zaintza | 30 egun | DBLO-GDD |
| Cookie datuak | 13 hilabete | ePrivacy |

## 7.2 SGSI — Informazioaren Segurtasuna Kudeatzeko Sistema

Zabala Gailetak-ek **ISO/IEC 27001:2022** arauan oinarritutako SGSI bat inplementatu du, **%93ko betetzea** lortuz. 34 dokumentu sortu dira.

### 7.2.1 Informazioaren Segurtasun Politika (ISP-001)

**Helburua:** Zabala Gailetak-en informazio-aktiboen konfidentzialtasuna, osotasuna eta eskuragarritasuna babestea.

**PDCA Zikloa:**
- **Plangintza:** Arrisku ebaluazioa, segurtasun helburuak
- **Egitea:** Kontrolak, politikak, prozedurak inplementatu
- **Egiaztatzea:** Barne-auditoriak, monitorizazioa, KPIak
- **Jokatzea:** Ekintza zuzentzaileak, etengabeko hobekuntza

**Segurtasun Helburuak:**

| # | Helburua | Metrika |
|---|---------|---------|
| 1 | Konfidentzialtasuna | Baimenik gabeko sarbide 0 |
| 2 | Osotasuna | Datu zuzenak %100 |
| 3 | Eskuragarritasuna | %99.5 uptime |
| 4 | Betetzea | Araudi guztiak beteta |
| 5 | Erresilientzia | Negozioa intzidentzien bitartean |
| 6 | Etengabeko Hobekuntza | Urteko berrikuspena |

**Rolak eta Erantzukizunak:**

| Rola | Erantzukizun Nagusia |
|------|---------------------|
| **CEO** | Azken erantzulea, aurrekontua onartu |
| **CISO** | SGSI garatu, auditoriak egin, intzidentziak kudeatu |
| **IT Arduraduna** | Kontrol teknikoak inplementatu, sistemen segurtasuna |
| **OT Arduraduna** | ICS segurtasuna, IT/OT segmentazioa |
| **DPO** | GDPR betetzea, ROPA, DPIA |
| **Sailetako Kudeatzaileak** | Politikak betetzea, intzidentziak jakinarazi |
| **Langile Guztiak** | Politikak bete, prestakuntza, pasahitzak babestu |

### 7.2.2 Arrisku Ebaluazioa (MAGERIT / ISO 31000)

Arrisku kudeaketa urtero egiten da:

1. **Aktiboen Identifikazioa:** Informazio-aktiboen inbentarioa
2. **Mehatxuen Ebaluazioa:** Zibererasoak, hondamendi naturalak, giza akatsak
3. **Ahultasunen Analisia:** Sistemen eta prozesuen ahuleziak
4. **Arriskuen Ebaluazioa:** Probabilitatea × Eragina
5. **Arriskuen Tratamendua:** Onartu, arindu, transferitu edo saihestu
6. **Hondar Arriskua:** Dokumentatu eta onartu

**Arriskuen Apetitoa:** Arrisku altuek exekutiboen onarpena eta arintze-planak behar dituzte.

### 7.2.3 Pasahitz Politika (PWD-001)

| Kontu Mota | Gutxieneko Luzera | Konplexutasuna | Iraungitzea | Blokeoa |
|-----------|-------------------|---------------|------------|---------|
| **Estandarra** | 12 karaktere | 3/4 kategoria | 90 egun (180 MFA-rekin) | 5 saiakera → 30 min |
| **Administratzailea** | 14 karaktere | 4/4 kategoria | 60 egun (90 MFA-rekin) | 3 saiakera → 1 ordu |
| **Zerbitzu Kontua** | 20 karaktere | Ausazkoa | 365 egun | — |

**MFA derrigorrezkoa:** Urruneko sarbidea, admin kontuak, Oso Konfidentzialeko datuak, hodeiko zerbitzuak.

**Onartutako MFA metodoak:** TOTP (gomendatua), FIDO2/U2F (YubiKey), Push jakinarazpenak.

**Pasahitz Hashing:** bcrypt (12+ erronda) edo Argon2id — **Inoiz ez** MD5, SHA1 edo SHA256 arrunta.

### 7.2.4 Erabilera Onargarriaren Politika (AUP-001)

IT baliabideen, posta elektronikoaren, interneten eta gailu mugikorren erabilera egokia arautzen du:
- Baimenik gabeko softwarea debekatuta
- Erabilera pertsonala arrazoi bidez mugatuta
- Informazio sentikorra enkriptatu behar da
- Segurtasun-intzidentziak jakinarazi behar dira

### 7.2.5 Aktiboen Erregistroa (ASR-001)

Hardware, software eta informazio-aktiboen inbentario osoa, bakoitza sailkapenarekin:

| Sailkapena | Definizioa | Kudeaketa |
|-----------|-----------|----------|
| **Publikoa** | Marketing materialak | Murrizketarik ez |
| **Barnekoa** | Barne oharrak, bilera-notak | Barne sareak soilik |
| **Konfidentziala** | Bezero datuak, finantza txostenak, errezetak | Enkriptatuta + sarbide kontrola |
| **Oso Konfidentziala** | Merkataritza sekretuak, PII, txartelen datuak | Enkriptatze sendoa + MFA + DLP |

### 7.2.6 Segurtasun Kontrolak

ISO 27001 Anexo A-ko kontrolak hiru taldetan:

- **Prebentiboak:** Suebakiak, sarbide-kontrola, enkriptatzea, MFA
- **Detektiboak:** SIEM, IDS/IPS, auditoriak, honeypot-ak
- **Zuzentzaileak:** Segurtasun-kopiak, intzidentzien erantzuna, BCP

### 7.2.7 KPI-ak (Errendimendu Adierazleak)

| KPI | Helburua | Maiztasuna |
|-----|----------|------------|
| Sistema eskuragarritasuna | %99.5 | Hilero |
| Segurtasun intzidentziak | <10 ertain/urtean | Hiruhilero |
| Adabaki betetzea | %95 kritikoak 7 egunetan | Hilero |
| Prestakuntza osatzea | %100 | Urtero |
| Backup arrakasta tasa | %99 | Egunero |
| Phishing klik tasa | <%10 | Hiruhilero |
| MTTD (Detekzioa) | <2 ordu | Intzidentziako |
| MTTR (Erantzuna) | <4 ordu | Intzidentziako |

## 7.3 Kontrol Kriptografikoak

| Erabilera | Algoritmoa |
|-----------|-----------|
| Datuak geldirik | AES-256 |
| Datuak trantsitoan | TLS 1.3 |
| VPN | AES-256 IKEv2/IPsec |
| Sinadura digitalak | RSA 2048-bit / ECDSA P-256 |
| Hashing | SHA-256+ |
| Pasahitz biltegiratzea | bcrypt 12+ / Argon2id |
| Gakoen txandakatzea | 12 hilabetero |

## 7.4 IEC 62443 — OT Segurtasun Estandarra

Zabala Gailetak-en OT arrisku-ebaluazioa IEC 62443 estandarraren arabera egin da:

- Purdue ereduaren inplementazioa (ikus 2.6.1)
- IT/OT segmentazio zorrotza
- OT gailuetarako sarbide-kontrol politikak
- USB gailu debekua OT eremuan
- Aldaketa kudeaketa OT sistemetarako

## 7.5 NIS2 Zuzentaraua

Zabala Gailetak NIS2 Zuzentarauaren eskakizunetara egokitu da, elikagai sektoreko enpresa gisa:

| Eskakizuna | Betetzea |
|-----------|---------|
| Arriskuen kudeaketa | SGSI + arrisku ebaluazioa |
| Intzidentzien jakinarazpena | 72 orduko CSIRT jakinarazpena |
| Negozioaren jarraitasuna | BCP plana inplementatuta |
| Hornidura-kate segurtasuna | Hirugarrenen ebaluazioa |
| Giza baliabideen segurtasuna | Prestakuntza programa |

## 7.6 Negozio-Jarraitasun Plana (BCP)

### 7.6.1 Berreskuratze Helburuak

| Parametroa | Balioa |
|-----------|--------|
| **RTO** (Recovery Time Objective) | 4 ordu sistema kritikoetarako |
| **RPO** (Recovery Point Objective) | 1 orduko datu-galera gehienez |
| **MTPD** (Maximum Tolerable Period of Disruption) | 24 ordu prozesu kritikoetarako |

### 7.6.2 Babeskopia Politika (3-2-1 Araua)

| Neurria | Xehetasuna |
|---------|-----------|
| **3 kopia** | Jatorrizkoa + 2 babeskopia |
| **2 euskarri mota** | Diskoa + hodeia/kanpokoa |
| **1 gunetik kanpo** | Off-site biltegiratzea |
| **Inkrementala** | Egunero |
| **Osoa** | Astero |
| **Probak** | Hiruhileko berreskuratze simulazioak |

### 7.6.3 Prozedura Operatiboak

5 prozedura operatibo estandar (SOP) inplementatu dira SGSI-aren barruan:

1. **Aldaketen Kudeaketa:** CAB prozesuak eta larrialdi aldaketak
2. **Kriptografia Kontrolak:** Enkriptatze estandarrak eta gakoen kudeaketa
3. **Garapen Segurua:** SSDLC printzipioak
4. **Sarbide Fisikoa:** Datu-zentro eta produkzio instalazioen babesa
5. **Informazioaren Sailkapena:** 4 mailako sistema (Publikoa → Oso Konfidentziala)

## 7.7 Segurtasun Kontzientzia eta Prestakuntza

| Hartzailea | Prestakuntza | Orduak |
|-----------|-------------|--------|
| Langile berriak | Segurtasun oinarriak (lehen astea) | 4 ordu |
| Langile guztiak | Urteroko freskatzea | 2 ordu |
| Garatzaileak | Kodeketa segurua, OWASP Top 10 | 8 ordu/urte |
| Administratzaileak | Hardening, monitorizazioa | 16 ordu/urte |
| Zuzendaritza | Arriskuen kudeaketa | 4 ordu/urte |
| Produkzio langileak | OT segurtasuna | 4 ordu/urte |

**Hiruhileko phishing simulazioak** eta **hileroko segurtasun buletinak**.

---

<div style="page-break-after: always;"></div>

# 8. ONDORIOAK

## 8.1 Heldutasun Maila

Zabala Gailetak-ek ER4 proiektuarekin honako heldutasun maila lortu du:

| Arloa | Betetzea | Egoera |
|-------|---------|--------|
| **ISO 27001 SGSI** | %93 | Ia osoa |
| **ER4 Eskakizunak** | %100 | Osoa |
| **GDPR** | %100 | 14 dokumentu |
| **Sare Segmentazioa** | %100 | 5 zona + NFTables |
| **SIEM** | %100 | ELK Stack + 15 alerta |
| **OT Segurtasuna** | %100 | Purdue eredua + PLC |
| **Aplikazio Segurtasuna** | %100 | JWT + MFA + RBAC |
| **Forentse Gaitasuna** | %100 | Tresna-kutxa + SOPak |
| **Testak** | 82/82 | %100 gainditu |

## 8.2 Proiektuaren Estatistikak

| Metrika | Balioa |
|---------|--------|
| Fitxategi guztira | 292 |
| Dokumentazio fitxategiak | 50+ (markdown) |
| Kode fitxategiak | 30+ (PHP, Kotlin, YAML, Bash) |
| Dokumentazio lerroak | 10.000+ |
| Kode lerroak | 5.000+ |
| SOPak | 15+ |
| SGSI dokumentuak | 34 |
| GDPR dokumentuak | 14 |
| Test fitxategiak | 6 (unit, integrazio, E2E, karga) |
| Docker inguruneak | 5 (HR Portal, SIEM, Honeypot, OT, Dev) |
| CI/CD pipeline-ak | 3 (Syntax, Deploy, OpenCode) |
| Alerta arauak | 15 (MITRE ATT&CK mapatuta) |
| Honeypot zerbitzuak | 8+ (T-Pot, Cowrie, Conpot, Dionaea...) |

## 8.3 Lortutako Onurak

Zabala Gailetak-ek ER4 proiektuarekin honako onurak lortu ditu:

1. **Merkataritza-sekretuen babesa:** Gaileta errezetak eta fabrikazio prozesuak enkriptatuta eta sarbide kontrolatuta daude
2. **OT makineria segurua:** Purdue ereduak IT/OT isolamendua bermatzen du, USB debekuarekin eta PLC Read-Only moduarekin
3. **Langile datuen babesa:** GDPR betetzea 14 dokumenturekin, 72 orduko jakinarazpen prozesuarekin
4. **Ziberresilentzia:** NIST erantzun plana, BCP, 4 orduko RTO eta 1 orduko RPO
5. **Monitorizazio etengabea:** SIEM 24/7, 15 alerta araurekin eta erantzun automatikoekin
6. **Eraso detekzioa:** Honeypot azpiegitura DMZ-an, SIEM korrelazioarekin
7. **Segurtasun kultura:** Prestakuntza programa langile guztientzat, phishing simulazioekin

## 8.4 Etorkizuneko Hobekuntza Ildoak

| Hobekuntza | Lehentasuna | Deskribapena |
|-----------|------------|-------------|
| ISO 27001 ziurtagiria | Altua | Kanpo auditoria eta ziurtagiri ofiziala lortu |
| SOC 24/7 | Ertaina | Segurtasun Operazio Zentroa ezarri |
| Zero Trust arkitektura | Altua | Sarbide mikro-segmentazioa inplementatu |
| SOAR plataforma | Ertaina | Erantzun automatizazioa hobetu |
| EDR hedapena | Altua | Endpoint Detection & Response sistema |
| Threat Intelligence | Ertaina | Mehatxu adimen proaktiboen integrazioa |
| Red Team programa | Baxua | Aldizka kanpo pentesting |

---

<div align="center">

## DOKUMENTUAREN AMAIERA

**Zabala Gailetak S.A.**
*Gaileta eta txokolate fabrikazioan espezializatutako enpresa industriala*

*Dokumentu hau Zabala Gailetak-en ER4 zibersegurtasun proiektuaren azken txosten teknikoa da.*
*Neurri guztiak enpresaren merkataritza-sekretuak, OT makineria eta langileen datuak babesteko diseinatuta daude.*

**2026ko Otsaila**

</div>
