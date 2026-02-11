# 💰 Kostuen, Baliabideen eta Soldaten Analisia - Zabala Gailetak

## Plataforma E-komertzio Segurua SIEM, OT/PLC eta Honeypot Azpiegiturarekin

**Bertsioa**: 1.0  
**Data**: 2026ko urtarrilaren 12a  
**Enpresa**: Zabala Gailetak Security Solutions  

---

## 📊 LABURPEN EXEKUTIBOA

Dokumentu honek Zabala Gailetak sistema inplementatzeko kostu osoak zehazten ditu, honakoak barne:

- Hardware/cloud azpiegitura
- Software lizentziak
- Giza baliabideak (soldatak)
- Eragiketa kostuak
- Enpresa bezeroarentzako eskaintza komertziala

**Inbertsio Totala (Inplementazioa)**: €187,950 - €257,250  
**Eragiketa Kostu Urterokoa**: €348,000 - €456,000  
**Bezeroarentzako Salmenta Prezioa**: €425,000 - €650,000 (lehen instalazioa + 1. urtea)

---

## 🖥️ 1. ATALA: AZPIEGITURA KOSTUAK

### Aukera A: On-Premise Azpiegitura (CAPEX)

#### Hardware - 4 Zerbitzari Konfigurazioa

| Zerbitzaria | Espezifikazioak | Unitate Prezioa | Kantitatea | Guztira |
|-------------|-----------------|-----------------|------------|---------|
| **Produkzio Zerbitzaria** | Dell PowerEdge R750, 8 nukleo, 32 GB RAM, 2x 960GB SSD RAID1 | €4,200 | 2 (HA) | €8,400 |
| **SIEM Zerbitzaria** | Dell PowerEdge R750, 16 nukleo, 64 GB RAM, 4x 960GB SSD RAID10 | €7,800 | 2 (clusterra) | €15,600 |
| **OT/PLC Zerbitzaria** | Dell PowerEdge R650, 6 nukleo, 16 GB RAM, 2x 480GB SSD RAID1 | €3,200 | 1 | €3,200 |
| **Honeypot Zerbitzaria** | Dell PowerEdge R650, 12 nukleo, 32 GB RAM, 4x 480GB SSD RAID10 | €5,400 | 1 | €5,400 |
| **Firewall/Router** | Fortinet FortiGate 200F, 20 Gbps, SSL inspection, IPS/IDS | €8,500 | 2 (HA bikotea) | €17,000 |
| **Switch Core** | Cisco Catalyst 9300-48U, 48 portu, VLAN, ACL, QoS, PoE+ | €6,500 | 2 (stack) | €13,000 |
| **UPS** | APC Smart-UPS SRT 6kVA, 15 min autonomia, double-conversion | €3,200 | 2 | €6,400 |
| **Rack 42U** | PDU-ak, erretiluak, ventilazioa barne | €1,200 | 1 | €1,200 |
| **Kableatua** | Cat6a, latiguillo-ak, patch panel | €800 | 1 | €800 |

**Hardware Azpitotala**: **€71,000**

#### Biltegiratze Gehigarria

| Elementua | Espezifikazioak | Prezioa |
|-----------|-----------------|---------|
| **NAS Babespena/Artxiboa** | Synology RS2421RP+, 12 bainu, 12x 8TB SATA, RAID6, 10Gbe | €8,500 |
| **Cloud Babespena** | AWS S3 Glacier<br>- 100 TB/urte<br>- Off-site replikazioa | €2,400/urte |

**Biltegiratze Azpitotala**: **€8,500 + €2,400/urte**

#### Software eta Lizentziak (3 urte)

| Softwarea | Mota | Kostua |
|-----------|------|--------|
| **Red Hat Enterprise Linux** | 10 Standard harpidetzak (3 urte) | €15,000 |
| **MongoDB Enterprise** | Ezaugarri aurreratuak, HA, Laguntza (3 urte) | €18,000 |
| **Fortinet FortiCare** | 24x7 laguntza + eguneraketak (3 urte) | €12,000 |
| **Elastic Stack Enterprise** | Gold lizentzia SIEM (3 urte) | €24,000 |
| **SSL Ziurtagiriak** | Wildcard + EV (3 urte) | €1,800 |
| **Conpot/T-Pot** | Kode irekia (€0) | €0 |
| **OpenPLC** | Kode irekia (€0) | €0 |

**Lizentzia Azpitotala (3 urte)**: **€70,800** (€23,600/urte amortizatua)

#### Instalazioa eta Konfigurazioa

| Kontzeptua | Kostua |
|------------|--------|
| Instalazio fisikoa (rack, kableatua) | €2,500 |
| Hasierako zerbitzari konfigurazioa | €4,000 |
| Sare, firewall, VLAN konfigurazioa | €6,000 |
| Datu migrazioa (aplikagarria bada) | €3,000 |
| Onarpen probak (1 aste) | €5,000 |

**Instalazio Azpitotala**: **€20,500**

### **AUKERA A GUZTIRA (On-Premise)**

- **Hasierako CAPEX**: €170,800
- **Urteko OPEX**: €2,400 (cloud babespena)
- **Urteko lizentziak** (3 urte ondoren): €23,600/urte

---

### B Aukera: Cloud Azpiegitura (AWS) - OPEX

#### AWS Konfigurazioa (eu-west-1 eskualdea - Irlanda)

| Zerbitzua | Espezifikazioa | Hileko Kostua | Urteko Kostua |
|-----------|----------------|---------------|---------------|
| **EC2 - Produkzio API** | 2x c6i.2xlarge (8 vCPU, 16GB)<br>Application Load Balancer | €520 | €6,240 |
| **EC2 - MongoDB** | 3x r6i.xlarge (4 vCPU, 32GB)<br>Replica Set | €780 | €9,360 |
| **ElastiCache Redis** | cache.r6g.large (2 vCPU, 13GB)<br>Multi-AZ | €180 | €2,160 |
| **EC2 - SIEM Elasticsearch** | 3x r6i.2xlarge (8 vCPU, 64GB)<br>Clusterra | €1,560 | €18,720 |
| **EC2 - Logstash** | 2x c6i.xlarge (4 vCPU, 8GB) | €260 | €3,120 |
| **EC2 - Kibana** | 1x t3.large (2 vCPU, 8GB) | €65 | €780 |
| **EC2 - OT/PLC** | 1x t3.xlarge (4 vCPU, 16GB) | €120 | €1,440 |
| **EC2 - Honeypots** | 1x c6i.2xlarge (8 vCPU, 16GB) | €260 | €3,120 |
| **EBS Storage** | 2 TB gp3 SSD (Produkzioa)<br>6 TB gp3 SSD (SIEM)<br>500 GB gp3 (OT/Honeypot) | €520 | €6,240 |
| **S3 Storage** | 10 TB logs artxiboa<br>Lifecycle Glacier-era | €240 | €2,880 |
| **RDS Backup** | MongoDB babespen automatizatuak<br>30 egun atxikipena | €150 | €1,800 |
| **VPC, NAT Gateway** | 3 AZ, aniztasuna | €180 | €2,160 |
| **CloudWatch** | Log-ak, metrikak, alarmak | €120 | €1,440 |
| **WAF + Shield** | DDoS babesa, bot filtering | €350 | €4,200 |
| **Data Transfer OUT** | 5 TB/hile trafikoa irteera | €450 | €5,400 |

**AWS Hileko Azpitotala**: **€5,755**  
**AWS Urteko Azpitotala**: **€69,060**

#### AWS Kudeatutako Zerbitzuak (Aukera)

| Zerbitzua | Ordezkapena | Hileko Kostua | Urteko Kostua |
|-----------|-------------|---------------|---------------|
| **Amazon OpenSearch** | ELK Stack ordezkatzen du<br>3 nodo r6g.2xlarge.search | €2,100 | €25,200 |
| **DocumentDB** | MongoDB ordezkatzen du<br>3 nodo r6g.xlarge | €1,200 | €14,400 |
| **GuardDuty** | Mehatxu detekzio natiboa | €150 | €1,800 |
| **Security Hub** | Betetze egiaztapenak | €50 | €600 |

**Kudeatutako Zerbitzuen Azpitotala**: **+€3,500/hile** (€42,000/urte)

### **AUKERA B GUZTIRA (AWS Cloud)**

- **Self-Managed Azpiegitura**: €69,060/urte
- **Kudeatutako Zerbitzuen Azpiegitura**: €111,060/urte
- **CAPEX inicialik gabe** (migrazioa soilik ~€5,000)

---

### Aukera C: Hibridoa (On-Premise Produkzioa + Cloud SIEM/Babespena)

| Osagaia | Kokapena | Kostua |
|---------|----------|--------|
| Produkzio + OT Zerbitzariak | On-premise | €48,000 CAPEX |
| SIEM (OpenSearch Service) | AWS | €25,200/urte |
| Honeypots | AWS | €3,600/urte |
| Babespena/DR | AWS S3 + Glacier | €4,800/urte |
| AWS Direct Connect Konectibitatea | 1Gbps | €3,600/urte |

### **AUKERA C GUZTIRA (Hibridoa)**

- **Hasierako CAPEX**: €48,000
- **Urteko OPEX**: €37,200

---

## 👥 2. ATALA: GIZA BALIABIDEAK ETA SOLDATAK

### 1. Fasea: Inplementazioa (6 hilabete)

#### Proiektu Taldea

| Rola | Dedikazioa | Urteko Soldata Gordina | 6 Hilabeteko Kostua | Kantitatea | Guztira |
|------|------------|------------------------|---------------------|------------|---------|
| **Project Manager Senior** | 100% | €65,000 | €32,500 | 1 | €32,500 |
| **Segurtasun Arkitektoa** | 100% | €75,000 | €37,500 | 1 | €37,500 |
| **DevOps Engineer Senior** | 100% | €60,000 | €30,000 | 2 | €60,000 |
| **Backend Developer (Node.js)** | 100% | €50,000 | €25,000 | 2 | €50,000 |
| **Frontend Developer (React)** | 100% | €48,000 | €24,000 | 1 | €24,000 |
| **QA/Security Tester** | 100% | €45,000 | €22,500 | 1 | €22,500 |
| **DBA/Data Engineer** | 50% | €55,000 | €13,750 | 1 | €13,750 |
| **OT/SCADA Espezialista** | 50% | €70,000 | €17,500 | 1 | €17,500 |
| **SIEM Analista** | 75% | €52,000 | €19,500 | 1 | €19,500 |

**Soldaten Azpitotala (6 hilabete inplementazioa)**: **€277,250**

**+ Gizarte kargak (30%)**: **€83,175**

**Inplementazioko Giza Baliabideak Guztira**: **€360,425**

---

### 2. Fasea: Eragiketa Jarraia (Urterokoa)

#### Eragiketa Taldea

| Rola | Dedikazioa | Urteko Soldata Gordina | Kantitatea | Urteko Guztira |
|------|------------|------------------------|------------|----------------|
| **IT Manager/CISO** | 100% | €70,000 | 1 | €70,000 |
| **DevOps Engineer** | 100% | €55,000 | 2 | €110,000 |
| **Backend Developer** | 100% | €48,000 | 1 | €48,000 |
| **SOC Analista (SIEM)** | 100% | €45,000 | 2 | €90,000 |
| **SOC Analista (24x7 txandak)** | 100% | €42,000 | 2 | €84,000 |
| **DBA (MongoDB/Redis)** | 50% | €55,000 | 1 | €27,500 |
| **OT Segurtasun Ingeniaria** | 75% | €60,000 | 1 | €45,000 |
| **Gertaera Erantzulea** | On-call | €50,000 | 1 | €50,000 |

**Urteko Soldaten Azpitotala**: **€524,500**

**+ Gizarte kargak (30%)**: **€157,350**

**Eragiketa Urteko Giza Baliabideak Guztira**: **€681,850**

---

### Langileen Kostu Zeharkakoak

| Kontzeptua | Urteko Kostua |
|------------|---------------|
| Prestakuntza eta ziurtagiriak (CISSP, CEH, GIAC) | €15,000 |
| Garapen tresnak (IDE, lizentziak) | €5,000 |
| Langileen hardwarea (eramangarriak, monitoreak) | €25,000 (3 urte amortizatua = €8,333/urte) |
| Bidaiak eta desplazamenduak | €8,000 |
| Konferentziak eta gertaerak (Black Hat, RSA) | €12,000 |

**Zeharkakoen Azpitotala**: **€48,333/urte**

---

## 📈 3. ATALA: URTEKO ERAGIKETA KOSTUAK

### Eragiketa eta Mantentzea (1. Urtea+)

| Kontzeptua | Aukera A (On-Prem) | Aukera B (AWS) | Aukera C (Hibridoa) |
|------------|-------------------|----------------|---------------------|
| **Azpiegitura** | €2,400 | €69,060 | €37,200 |
| **Software lizentziak** | €23,600 | €0 (barne) | €11,800 |
| **Elektrizitatea** (30 kW, 24x7) | €18,000 | €0 | €9,000 |
| **Hozketa** | €6,000 | €0 | €3,000 |
| **Hardware mantentzea** | €7,100 (hardwarearen %10) | €0 | €4,800 |
| **Kanpo laguntza teknikoa** | €12,000 | €8,000 | €10,000 |
| **Segurtasun auditoretza** (hiruhilekoa) | €16,000 | €16,000 | €16,000 |
| **Penetration Testing** (urterokoa) | €8,000 | €8,000 | €8,000 |
| **Threat Intelligence feeds** | €12,000 | €12,000 | €12,000 |
| **Offsite babespena** | Goian barne | Barne | Barne |
| **Zibersegurtasun asegurua** | €15,000 | €15,000 | €15,000 |
| **Ziurtagiri/betetze berritzea** | €5,000 | €5,000 | €5,000 |

**OPEX Azpiegituraren Azpitotala**:

- **Aukera A**: €125,100/urte
- **Aukera B**: €133,060/urte
- **Aukera C**: €131,800/urte

---

## 💼 4. ATALA: KOSTU TOTALEN LABURPENA

### Inplementazio Kostuak (0. Urtea)

| Kontzeptua | Aukera A (On-Prem) | Aukera B (AWS) | Aukera C (Hibridoa) |
|------------|-------------------|----------------|---------------------|
| **Hardware CAPEX** | €79,500 | €0 | €48,000 |
| **Lizentziak (3 urte)** | €70,800 | €0 | €35,400 |
| **Instalazioa/Setup** | €20,500 | €5,000 | €12,500 |
| **Giza Baliabideak (6 hilabete)** | €360,425 | €360,425 | €360,425 |
| **0. URTEA GUZTIRA** | **€531,225** | **€365,425** | **€456,325** |

### Eragiketa Kostuak (1. Urtea aurrerantzean)

| Kontzeptua | Aukera A (On-Prem) | Aukera B (AWS) | Aukera C (Hibridoa) |
|------------|-------------------|----------------|---------------------|
| **Azpiegitura OPEX** | €125,100 | €133,060 | €131,800 |
| **Langileak (8 FTE)** | €681,850 | €681,850 | €681,850 |
| **Langileen Zeharkakoak** | €48,333 | €48,333 | €48,333 |
| **URTEKO GUZTIRA** | **€855,283** | **€863,243** | **€861,983** |

### Kostu Totala 3 Urteetan (TCO)

| Kontzeptua | Aukera A (On-Prem) | Aukera B (AWS) | Aukera C (Hibridoa) |
|------------|-------------------|----------------|---------------------|
| 0. Urtea (Inplementazioa) | €531,225 | €365,425 | €456,325 |
| 1. Urtea (Eragiketa) | €855,283 | €863,243 | €861,983 |
| 2. Urtea (Eragiketa) | €855,283 | €863,243 | €861,983 |
| 3. Urtea (Eragiketa) | €855,283 | €863,243 | €861,983 |
| **3 URTEKO TCO** | **€3,097,074** | **€2,955,154** | **€3,042,274** |

**TCO Ondorioa**: Cloud (Aukera B) **€141,920 merkeagoa** da 3 urteetan.

---

## 🎯 5. ATALA: BEZEROARENTZAKO ESKAINTZA KOMERTZIALA

### Negozio Eredua: Proiektu Txaketean + Urteko Laguntza

#### 1. Paketea: OINARRIZKOA (Aukera B - AWS Cloud)

**Barne:**

- ✅ E-komertzio plataforma osoa (API + Web + Mobile)
- ✅ SIEM zentralizatua (ELK Stack AWS-en)
- ✅ Honeypot threat intelligence-rako
- ✅ OT/PLC simulazio oinarrizkoa
- ✅ 6 hilabeteko inplementazioa
- ✅ Bezeroaren taldearen prestakuntza (40 ordu)
- ✅ Dokumentazio osoa
- ✅ Lanzamendu osteko 3 hilabeteko laguntza

**Prezioa**: **€425,000** (behin)

**Urteko Laguntza (aukerakoa)**: **€120,000/urte**

- 8x5 mantentzea
- Segurtasun eguneraketak
- SIEM monitorizazioa (lanegunetan)
- 2 auditoretza urteroko

---

#### 2. Paketea: PROFESIONALA (Aukera C - Hibridoa)

**Oinarrizko Pakete guztia +**

- ✅ Zerbitzari on-premise produkziorako (altu erabilgarritasuna)
- ✅ SIEM aurreratua erantzun automatizatuarekin
- ✅ Honeypot geruza anitzak (T-Pot osoa)
- ✅ OT/PLC simulazio aurreratua (OpenPLC + ScadaBR)
- ✅ IT/OT integrazio osoa Purdue Model-ekin
- ✅ Prestakuntza aurreratua (80 ordu)
- ✅ Lanzamendu osteko 6 hilabeteko laguntza

**Prezioa**: **€575,000** (behin)

**Urteko Laguntza (nahitaezkoa)**: **€180,000/urte**

- 24x7 mantentzea
- Kudeatutako SOC (ordutegi luzatua)
- Gertaera erantzuna (4 ordu SLA)
- 4 auditoretza urteroko + 1 pentest

---

#### 3. Paketea: ENPRESA (Aukera A - On-Premise Guztizkoa)

**Profesional Pakete guztia +**

- ✅ On-premise azpiegitura osoa (bezeroak hardwarea jabetzen du)
- ✅ Altua erabilgarritasuna osagai guztietan
- ✅ Zabala Gailetak-ek kudeatutako 24x7 SOC
- ✅ Bermeatutako gertaera erantzuna (2 ordu SLA)
- ✅ Industria planta simulazio osoa
- ✅ Bezeroaren sistema legacy-ekin integrazioa
- ✅ Bigarren Disaster Recovery gunea
- ✅ Prestakuntza intentsiboa (120 ordu)
- ✅ Barnean 12 hilabeteko laguntza

**Prezioa**: **€850,000** (behin)

**Urteko Laguntza (1. urtea barne, berritzea)**: **€240,000/urte**

- 24x7x365 SOC talde espezializatuarekin
- Proaktibo threat hunting
- Gertaera erantzun mugagabea (1 ordu SLA kritikoa)
- 6 auditoretza urteroko + 2 pentest
- Red team ariketak
- Hardware eguneraketak 3 urtero barne

---

### Marjinaren Desglosea (Adibide 2. Paketea - Profesionala)

| Kontzeptua | Kostu Erreala | Salmenta Prezioa | Marjina |
|------------|---------------|------------------|---------|
| **Inplementazioa** | €456,325 | €575,000 | **€118,675 (26%)** |
| **1. Urteko Laguntza** | €861,983 | €180,000 | **-€681,983** ⚠️ |

**Laguntzaren marjinari buruzko oharra**: Lehen urteko marjina negatiboa honengatik:

1. Bezeroak **ez du 8 FTE talde osoa** ordaintzen; guk taldea **bezero anitzetan** amortizatzen dugu
2. **5 bezero simultaneoekin**, langileen kostua banatzen da:
   - Benetako kostua bezeroko: €861,983 / 5 = **€172,397/urte**
   - Salmenta prezioa: **€180,000/urte**
   - **Benetako marjina: €7,603/bezero (4%)**

3. Benetako errentagarritasuna **bezero errepikakorren portfolia** izateagatik dator

---

### Moduluka Prezio Eredua (À la Carte)

Bezeroak osagaiak hautatu nahi baditu:

| Modulua | Prezioa |
|---------|---------|
| **Core E-komertzioa** (API + DB + Web) | €180,000 |
| **SIEM oinarrizkoa** (30 egun log) | €80,000 |
| **SIEM aurreratua** (90 egun log, alerta aurreratuak) | €150,000 |
| **Honeypot bakarra** (Cowrie SSH) | €25,000 |
| **Honeypot geruza anitzak** (T-Pot osoa) | €65,000 |
| **OT/PLC simulazio oinarrizkoa** | €45,000 |
| **OT/PLC simulazio aurreratua + IT/OT integrazioa** | €95,000 |
| **Mugikorrerako Aplikazioa (iOS + Android)** | €60,000 |
| **Disaster Recovery konfigurazioa** | €40,000 |
| **Prestakuntza (eguneko)** | €2,500/egun |

---

## 📊 6. ATALA: ERRENTAGARRITASUN ANALISIA (Zabala Gailetak-entzat)

### Eskenarioa: 5 Profesional Pakete Bezero (3 urte)

| Kontzeptua | 0. Urtea | 1. Urtea | 2. Urtea | 3. Urtea | 3 Urte Guztira |
|------------|----------|----------|----------|----------|----------------|
| **Sarrerak (5 bezero)** | €2,875,000 | €900,000 | €900,000 | €900,000 | €5,575,000 |
| **Langileen Kostuak (partekatua)** | €360,425 | €681,850 | €681,850 | €681,850 | €2,405,975 |
| **Azpiegitura Kostuak** | €456,325 x5 | €131,800 x5 | €131,800 x5 | €131,800 x5 | €3,260,625 |
| **KOSTUAK GUZTIRA** | €2,641,550 | €1,340,850 | €1,340,850 | €1,340,850 | €6,664,100 |
| **Irabazi Gordina** | €233,450 | -€440,850 | -€440,850 | -€440,850 | **-€1,089,100** ⚠️ |

**Arazoa**: Eredua ez da errentagarria azpiegitura **bezeroko espezifikoa** bada.

---

### Zuzendutako Eskenarioa: Multi-Tenant Azpiegitura

**Hipotesi errealista**:

- 1 AWS cloud azpiegitura **partekatua** 5 bezerorentzako (isolamendua VPC/tenant bidez)
- Eskalatzea erabilera arabera
- **Bakarrik** azpiegitura kostuak, ez x5

| Kontzeptua | 0. Urtea | 1. Urtea | 2. Urtea | 3. Urtea | 3 Urte Guztira |
|------------|----------|----------|----------|----------|----------------|
| **Sarrerak (5 bezero)** | €2,875,000 | €900,000 | €900,000 | €900,000 | €5,575,000 |
| **Langileen Kostuak** | €360,425 | €681,850 | €681,850 | €681,850 | €2,405,975 |
| **Azpiegitura Kostuak** (partekatua x1.5 kapazitatea) | €68,487 | €197,700 | €197,700 | €197,700 | €661,587 |
| **Overhead** (bulegoak, legalak, salmentak) | €100,000 | €150,000 | €150,000 | €150,000 | €550,000 |
| **KOSTUAK GUZTIRA** | €528,912 | €1,029,550 | €1,029,550 | €1,029,550 | €3,617,562 |
| **Irabazi Gordina** | €2,346,088 | -€129,550 | -€129,550 | -€129,550 | **€1,957,438** ✅ |
| **Marjina Gordina** | 81.6% | -14.4% | -14.4% | -14.4% | **35.1%** |

**Ondorioa**: Errentagarria **SaaS/Multi-Tenant** ereduarekin eta bezero bolumenarekin.

---

### Berdinketa Puntuaren Analisia

Multi-tenant ereduarekin:

| Bezero Kopurua | Urteko Sarrerak (laguntza) | Kostu Finkoak | Irabazi/Galerak |
|----------------|----------------------------|---------------|-----------------|
| 1 | €180,000 | €1,029,550 | **-€849,550** |
| 2 | €360,000 | €1,029,550 | **-€669,550** |
| 3 | €540,000 | €1,029,550 | **-€489,550** |
| 4 | €720,000 | €1,029,550 | **-€309,550** |
| 5 | €900,000 | €1,029,550 | **-€129,550** |
| 6 | €1,080,000 | €1,029,550 | **+€50,450** ✅ |
| 10 | €1,800,000 | €1,150,000 | **+€650,000** ✅ |

**Berdinketa Puntua**: **6 bezero aktibo** urteko laguntzan.

---

## 🎁 7. ATALA: LANZAMENDUKO PROMOZIO ESKAINTZA

### Kanpaina: "Hasierako Erabiltzaile Programa"

**Baldintza bereziak lehenengo 3 bezeroentzako:**

| Onurak | Balio Arrunta | Balio Promozionala | Aurrezkia |
|--------|---------------|--------------------|-----------|
| Profesional Paketea | €575,000 | €475,000 | **€100,000** |
| 1. Urteko Laguntza | €180,000/urte | €150,000/urte | **€30,000** |
| Prestakuntza gehigarria | €10,000 (4 egun) | **DOAN** | **€10,000** |
| Auditoretza hiruhileko gehigarria | €4,000 | **DOAN** | **€4,000** |
| **1. URTEKO AURREZKI TOTALA** | | | **€144,000** |

**Prezio Promozional Totala (0. Urtea + 1. Urtea)**: **€625,000** (vs €755,000 arrunta)

**Baldintzak**:

- Baliozkoa 2026/03/31 arte
- Gutxienez 3 urteko laguntza konpromisoa
- Bezeroak arrakasta kasu gisa jokatzen du (testimonial + logo)
- Webinar publikoan parte-hartzea (aukerakoa)

---

## 📝 8. ATALA: KONTRATU BALDINTZAK

### Ordainketa Egitura (Profesional Paketea €575,000)

| Hitoia | % | Zenbatekoa | Entregagarriak |
|--------|---|------------|----------------|
| **Kontratuaren sinadura** | 20% | €115,000 | Proiektuaren hasiera, abiatzea |
| **Diseinua onartua** | 15% | €86,250 | Arkitektura, UX/UI diseinua |
| **Garapena 50%** | 20% | €115,000 | Backend + Frontend funtzionala |
| **Pre-produkzioa (UAT)** | 20% | €115,000 | Proba osoa, staging |
| **Go-Live** | 15% | €86,250 | Produkzio aktiboa, eskuzko |
| **Bermea amaitzean (3 hilabete)** | 10% | €57,500 | Proiektuaren itxiera, dokumentazio finala |

### SLA (Zerbitzu Maila Akordioa) - Profesional Laguntza

| Lehentasuna | Deskribapena | Erantzun Denbora | Konponketa Denbora |
|-------------|--------------|------------------|--------------------|
| **P1 - Kritikoa** | Sistema behera, datu galera, segurtasun haustea | 1 ordu | 4 ordu |
| **P2 - Altua** | Funtzionalitate nagusia ez dago eskuragarri | 4 ordu | 24 ordu |
| **P3 - Ertaina** | Funtzionalitate arina kaltetua | 8 ordu | 3 egun |
| **P4 - Baxua** | Kontsultak, hobekuntzak | 24 ordu | 10 egun |

**SLA betetze ezaren penalizazioak**:

- P1: Hileko kuotaren %5 kreditua atzerapen ordu bakoitzeko
- P2: Hileko kuotaren %2 kreditua 4 orduko atzerapen bakoitzeko
- Gehienezko hileko penalizazioa: kuotaren %25

### Bermeak

- **Funtzionalitatea**: Go-live-tik 12 hilabete
- **Segurtasuna**: 6 hilabete ahultasun kritikorik gabe (CVSS >7.0)
- **Eskuragarritasuna**: %99.5 uptime hileko (mantentze programatua kanpo)
- **Babespena/Berrespena**: RTO 8 ordu, RPO 24 ordu

---

## 💡 9. ATALA: BEZEROARENTZAKO GOMENDIOAK

### Gomendatutako Aukera Profilaren Arabera

#### Bezero Txikia (50-200 langile, <€10M fakturazioa)

- **Gomendioa**: OINARRIZKO Paketea (AWS Cloud)
- **Justifikazioa**:
  - CAPEX hasierala txikia
  - Eskalagarritasun elastikoa
  - Ez IT talde handiaren beharra
- **1. Urteko Inbertsioa**: €425,000 + €120,000 = **€545,000**

#### Bezero Ertaina (200-1000 langile, €10-50M fakturazioa)

- **Gomendioa**: PROFESIONAL Paketea (Hibridoa)
- **Justifikazioa**:
  - Kostu/kontrol oreka
  - Datu sentsibleak on-premise
  - SIEM eta babespena cloud-en
  - Betetzea (GDPR, PCI-DSS)
- **1. Urteko Inbertsioa**: €575,000 + €180,000 = **€755,000**

#### Bezero Enterprise (>1000 langile, >€50M fakturazioa)

- **Gomendioa**: ENPRESA Paketea (On-Premise Guztizkoa)
- **Justifikazioa**:
  - Datuen kontrol osoa
  - Dagoen azpiegiturarekin integrazioa
  - Industria araudia (OT/ICS)
  - Datuen subiranotasuna
- **1. Urteko Inbertsioa**: €850,000 + €240,000 = **€1,090,000**

---

## 📞 10. ATALA: KONTAKTU INFORMAZIOA

### Zabala Gailetak Security Solutions

**Helbide Komertziala**:  
Polígono Industrial Garaia, Nave 12  
20140 Andoain, Gipuzkoa  
Euskal Herria, Espainia

**Kontaktuak**:

- **Salmentak**: <ventas@zabalagailetak.eus> | +34 943 XXX XXX
- **Laguntza**: <soporte@zabalagailetak.eus> | +34 943 XXX XXX
- **24/7 Larrialdiak**: +34 600 XXX XXX

**Web**: <https://www.zabalagailetak.eus>

**Ziurtagiriak**:

- ISO 27001 (Informazio Segurtasunaren Kudeaketa)
- ISO 22301 (Negozio Jarraitutasuna)
- ENS Altua (Segurtasun Eskema Nazionala)
- IEC 62443 (OT/ICS Segurtasuna)

**Lankideak**:

- AWS Advanced Consulting Partner
- MongoDB Enterprise Partner
- Elastic Gold Partner
- Fortinet Expert Partner

---

## 📄 ERANSKINAK

### A Eranskina: Lehiakideen Konparaketa

| Hornitzailea | Soluzio Antzekoa | Prezio Estimatua | Zabala Gailetak Diferentziatzaileak |
|--------------|------------------|------------------|-------------------------------------|
| Accenture Security | SIEM pertsonalizatua + ICS | €1.2M - €2M | **%50 merkeagoa**, OT espezializazioa |
| Indra Minsait | Industria segurtasun plataforma | €900K - €1.5M | **Flexibilitate handiagoa**, cloud-ready |
| S21sec | Kudeatutako SOC + plataforma | €500K - €800K | **Honeypot-ak barne**, PLC simulazio erreala |
| Atos Cybersecurity | Enpresa segurtasun suite | €1M - €1.8M | **Inplementazio azkarragoa** (6 vs 12 hilabete) |

### B Eranskina: Bezeroarentzako ROI (Arrakasta Kasua)

**Adibidez bezeroa**: Industria enpresa 500 langile, €30M/urte fakturazioa

**Zabala Gailetak aurretik**:

- 2 urtean 3 segurtasun gertaera (bakoitzeko kostu batez bestekoa: €500K)
- Downtime ez-planifikatua: 120 ordu/urte (€5K/orduko galerak)
- **Gertaeren kostu totala**: €2.1M 2 urtean

**Zabala Gailetak ondoren** (1-2. urtea):

- 0 segurtasun gertaera arrakastatsu (35 saiakera blokeatuak)
- Downtime 12 ordu/urtera murriztua
- **Aurrezpena**: €1.95M 2 urtean

**ROI**:

- Inbertsioa: €755K (1. urtea) + €180K (2. urtea) = €935K
- Aurrezpena: €1.95M
- **ROI netoa: +€1.015M (%108)**

### C Eranskina: Inplementazio Bide-orria (6 hilabete)

```text
1-2. Hilabetea: Diseinua eta Prestakuntza
├─ 1-2. Astea: Hasiera, eskakizunak, arkitektura
├─ 3-4. Astea: UX/UI diseinua, onarpena
├─ 5-6. Astea: Azpiegitura konfigurazioa (AWS/On-prem)
└─ 7-8. Astea: Sare konfigurazioa, VLAN-ak, firewall

3-4. Hilabetea: Garapena eta Integrazioa
├─ 9-12. Astea: Backend garapena (API)
├─ 13-14. Astea: Frontend garapena (Web)
├─ 15-16. Astea: MongoDB, Redis integrazioa
└─ 17. Astea: Sprint berrikuspena, egokitzapenak

5. Hilabetea: SIEM eta Segurtasuna
├─ 18-19. Astea: ELK Stack desplieguea
├─ 20. Astea: Alerta konfigurazioa, panelak
└─ 21. Astea: Honeypot-ak, OT/PLC desplieguea

6. Hilabetea: Probak eta Go-Live
├─ 22-23. Astea: QA osoa, pentest
├─ 24. Astea: Bezeroarekin UAT
├─ 25. Astea: Datu migrazioa, go-live
└─ 26. Astea: Lanzamendu osteko monitorizazioa
```

---

## ✅ LABURPEN FINALA

### Bezeroarentzat

| Paketea | 3 Urteko Inbertsio Totala | Gako Onurak |
|---------|---------------------------|-------------|
| **Oinarrizkoa** | €785,000 | E-komertzio segurua + SIEM oinarrizkoa |
| **Profesionala** | €1,115,000 | + OT/PLC + Honeypot-ak + HA |
| **Enpresa** | €1,570,000 | + 24x7 SOC + Kontrol osoa |

### Zabala Gailetak-entzat (Helburua: 10 bezero aktibo)

| Metrika | Balioa |
|---------|--------|
| **Urteko Sarrerak** (10 bezero laguntza) | €1,800,000 |
| **Eragiketa Kostuak** | €1,150,000 |
| **Irabazi Gordina** | **€650,000/urte (%36 marjina)** |
| **Berdinketa Puntua** | 6 bezero |

---

**Dokumentua prestatu du**: Zabala Gailetak Security Solutions  
**Baliozkoa arte**: 2026/03/31  
**Bertsioa**: 1.0 - 2026ko urtarrilaren 12a

---

*Dokumentu honek informazio konfidentziala dauka. Baimenik gabeko erreprodukzioa debekatuta dago.*
