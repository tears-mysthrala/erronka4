# Negozioaren Jarraitutasun Plana (BCP)

## Zabala Gailetak S.A

**Dokumentuaren IDa:** BCP-001  
**Bertsioa:** 1.0  
**Data:** 2026ko Urtarrilaren 8a  
**Sailkapena:** Oso Konfidentziala  
**Jabea:** Zuzendari Nagusia (CEO)  
**BCP Koordinatzailea:** Informazioaren Segurtasuneko Arduradun Nagusia (CISO)  
**Berrikuspen Maiztasuna:** Urterokoa (eta intzidente handien ondoren)  
**Hurrengo Berrikuspen Data:** 2027ko Urtarrilaren 8a

---

## 1. Dokumentuaren Kontrola

### 1.1 Bertsio Historia {#versio-historia}

| Bertsioa | Data | Egilea | Aldaketak |
|----------|------|--------|-----------|
| 1.0 | 2026-01-08 | CISO | Hasierako BCP sorrera |

### 1.2 Onarpena {#onarpena}

| Rola | Izena | Sinadura | Data |
|------|-------|----------|------|
| Zuzendari Nagusia (CEO) | [Izena] | | |
| Finantza Zuzendaria (CFO) | [Izena] | | |
| CISO | [Izena] | | |
| Eragiketa Kudeatzailea | [Izena] | | |

### 1.3 Banaketa eta Sarbidea

**Baimendutako Langileak:**

- Zuzendaritza Exekutiboko Taldea
- Negozioaren Jarraitutasun Taldeko kideak
- Departamentu Buruak
- Larrialdi Erantzun Taldea

**Biltegiratze Kokapenak:**

- Lehen mailakoa: Dokumentu kudeaketa sistema segurua
- Babeskopia: Inprimatutako kopiak larrialdi-kitetan
- Gunetik kanpo: Hodei biltegiratzea
- Larrialdi Kontaktuak: Mugikorretik eskuragarri den bertsioa

---

## 2. Laburpen Exekutiboa

Negozioaren Jarraitutasun Plan (BCP) honek Zabala Gailetak-i aukera ematen
dio negozio funtzio kritikoei eusteko gertaera disruptiboetan eta ondoren.
Planak IT eta OT sistemak jorratzen ditu.

**Helburu Nagusiak:**

- Langileen segurtasuna babestea
- Bezeroarentzako zerbitzua mantentzea
- Finantza eta ospe kalteak minimizatzea
- Lege eta kontratu betebeharrak betetzea

**Arrakasta Faktore Kritikoak:**

- Etenaldi Jasangarriaren Gehieneko Epea (MTPD): 24 ordu prozesu kritikoetarako
- Berreskuratze Denbora Helburua (RTO): 4 ordu IT sistemetarako
- Berreskuratze Puntu Helburua (RPO): Ordu 1 datu galerarako
- Negozio Jarraitutasun Helburu Minimoa (MBCO): %60eko produkzio gaitasuna

---

## 3. Esparrua eta Suposizioak

### 3.1 Esparrua {#esparrua}

BCP honek honako hauek hartzen ditu barne:

- **Negozio Prozesu Kritikoak:** Gaileta produkzioa, eskaera betetzea
- **IT Sistemak:** Web aplikazioa, datu-baseak, posta elektronikoa
- **OT Sistemak:** PLCak, SCADA, produkzio ekipamendua
- **Instalazioak:** Produkzio instalazio nagusia, administrazio bulegoak
- **Langileak:** Langile guztiak, kontratistak

### 3.2 Etenaldi Eszenatokiak {#etenaldi-eszenatokiak}

Plan honek honako hauek jorratzen ditu:

1. **Ziber Intzidenteak:** Ransomware, DDoS erasoak, datu-urraketak
2. **IT/OT Sistema Hutsegiteak:** Hardware hutsegitea, sare etena
3. **Hondamendi Naturalak:** Sutea, uholdea, eguraldi larria
4. **Pandemiak:** Lan-indarrari eragiten dion gaixotasun agerraldia
5. **Utilitate Etenak:** Argindar etena, ur hornidura etena
6. **Hornidura Kate Etenaldia:** Funtsezko hornitzailearen hutsegitea
7. **Segurtasun Fisikoa:** Terrorismoa, bandalismoa, lapurreta
8. **Langile Arazoak:** Funtsezko pertsonaren erabilgarritasun eza, grebak

### 3.3 Suposizioak {#suposizioak}

- Larrialdi zerbitzuak eskuragarri daude
- Aseguru estaldura mantentzen da
- Negozioaren jarraitutasun aurrekontua onartzen da
- Langileak prestatuta daude
- Hornitzaile kritikoek beren BCP planak dituzte
- Gunetik kanpoko babeskopia kokapena 2 orduko epean eskuragarri dago

---

## 4. Negozioaren Eraginaren Analisia (BIA)

### 4.1 Negozio Funtzio Kritikoak {#funtzio-kritikoak}

| Funtzioa | Deskribapena | MTPD | RTO | RPO | Eragina Eten Bada |
|----------|--------------|------|-----|-----|-------------------|
| **Gaileta Produkzioa** | Fabrikazio eragiketak | 24h | 8h | 4h | 20.000 €/egun |
| **Eskaera Kudeaketa** | Eskaera sarrera, prozesatzea | 12h | 4h | 1h | 10.000 €/egun |
| **Inbentario Kudeaketa** | Jarraitzea | 24h | 8h | 4h | Produkzio atzerapenak |
| **Kalitate Kontrola** | Produktu probak | 24h | 8h | 4h | Arau urraketak |
| **Bezeroarentzako Arreta** | Laguntza, kexak | 24h | 4h | N/A | Bezeroen gogobetetasun eza |
| **Finantza Eragiketak** | Nominak, kontuak | 48h | 12h | 24h | Ordainketa atzerapenak |
| **IT Azpiegitura** | Zerbitzariak, sarea | 8h | 4h | 1h | Sistema guztiak |
| **OT Azpiegitura** | PLCak, SCADA | 8h | 4h | 1h | Produkzio geldialdia |

**MTPD:** Etenaldi Jasangarriaren Gehieneko Epea  
**RTO:** Berreskuratze Denbora Helburua  
**RPO:** Berreskuratze Puntu Helburua

### 4.2 Baliabide Kritikoak {#baliabide-kritikoak}

**IT Sistemak (1. Lehentasuna):**

- Web aplikazio zerbitzaria (eskaera kudeaketa)
- Datu-base zerbitzaria (PostgreSQL/MongoDB)
- Posta elektroniko zerbitzaria
- Suebakia eta sare azpiegitura

**OT Sistemak (1. Lehentasuna):**

- Produkzio PLCak (nahasketa, labeketa, enbalatze lineak)
- SCADA sistema
- Tenperatura eta hezetasun kontrol sistemak

**Instalazioak (1. Lehentasuna):**

- Produkzio instalazio nagusia
- Energia hornitura (elektrizitatea)
- Ur hornidura

**Langileak (1. Lehentasuna):**

- Produkzio Kudeatzailea eta txanda begiraleak
- Mantentze teknikariak
- IT Administratzailea

### 4.3 Finantza Eraginaren Ebaluazioa {#finantza-eragina}

| Etenaldi Iraupena | Produkzio Galera | Diru-sarrera Eragina | Zeharkako Kostuak | Eragin Osoa |
|-------------------|------------------|----------------------|-------------------|-------------|
| 4 ordu | %16 | 3.000 € | 1.000 € | 4.000 € |
| 8 ordu | %33 | 6.000 € | 3.000 € | 9.000 € |
| 24 ordu | %100 | 20.000 € | 10.000 € | 30.000 € |
| 3 egun | %300 | 60.000 € | 40.000 € | 100.000 € |
| 1 aste | %700 | 140.000 € | 100.000 € | 240.000 € |

**Zeharkako Kostuak:** Kontratu zigorrak, aparteko orduak, ospe kaltea

---

## 5. Negozioaren Jarraitutasun Estrategia

### 5.1 Estrategia Orokorra {#estrategia-oroorra}

Zabala Gailetak-ek maila anitzeko jarraitutasun estrategia erabiltzen du:

1. **Prebentzioa:** Etenaldi probabilitatea minimizatu
2. **Arintzea:** Eragina murriztu
3. **Erantzuna:** Jarraitutasun prozedurak aktibatu
4. **Berreskuratzea:** Eragiketa normalak berrezarri
5. **Hobekuntza:** Intzidenteetatik ikasi

### 5.2 IT Sistemen Jarraitutasun Estrategia {#it-jarraitutasuna}

**Azpiegitura Erredundantzia:**

- Hodeian ostatututako sistema kritikoak (AWS/Azure eskualde anitz)
- Lokalean babeskopia zerbitzariak
- Internet konexio erredundanteak (2 ISP)
- Etenik Gabeko Elikatze Sistema (UPS) + sorgailua

**Hondamendi Berreskuratze Gunea (DR Site):**

- Hodeian oinarritutako DR azpiegitura (IaaS)
- RTO: 4 ordu (sistema kritikoak martxan jarri)
- RPO: 1 ordu (gehieneko datu galera)

**Urruneko Lan Gaitasuna:**

- VPN sarbidea bulegoko langile guztientzat
- Hodeian oinarritutako kolaborazio tresnak
- Enpresako ordenagailu eramangarriak

### 5.3 OT Sistemen Jarraitutasun Estrategia {#ot-jarraitutasuna}

**Ekipamendu Erredundantzia:**

- Ordezko pieza kritikoen inbentarioa (PLCak, sentsoreak)
- Backup PLC konfigurazioak gunetik kanpo
- Eskuzko eragiketa prozedurak ekipamendu kritikoentzat

**Ordezko Produkzioa:**

- Larrialdi produkziorako akordioak 2 bazkide fabrikatzailerekin
- Gaitasuna: Produkzio normalaren %40 24 orduko epean

### 5.4 Instalazioen Jarraitutasun Estrategia {#instalazio-jarraitutasuna}

**Ordezko Lan Kokapenak:**

- Bulegoko langileak: Etxetik urruneko lana
- Produkzio langileak: Bazkide fabrikazio instalazioak

**Instalazio Babesa:**

- Sute itzaltze sistema
- Ur ihes detekzioa
- Babeskopia sorgailua (diesela)
- Klima kontrol babeskopia

### 5.5 Hornidura Katearen Jarraitutasun Estrategia {#hornidura-jarraitutasuna}

**Hornitzaile Dibertsifikazioa:**

- Gutxienez 2 hornitzaile lehengai kritikoetarako
- 30 eguneko inbentario bufferra
- Aldizkako hornitzaile arrisku ebaluazioak

### 5.6 Komunikazio Estrategia {#komunikazio-estrategia}

**Barne Komunikazioa:**

- Larrialdi jakinarazpen sistema (SMS/email masiboa)
- Babeskopia kontaktu zerrenda
- Eguneroko informazio saioak intzidentean zehar

**Kanpo Komunikazioa:**

- Bezero jakinarazpena etenaldi esanguratsua gertatu eta 2 orduko epean
- Hornitzaile jakinarazpena beharrezkoa denean
- Hedabideekiko harremanak (CEO edo izendatutako bozeramailea)

---

## 6. Rolak eta Erantzukizunak

### 6.1 Negozioaren Jarraitutasun Taldea {#bct}

**BCP Koordinatzailea (CISO):**

- BCP mantentze eta proba orokorrak
- BCP aktibatu etenaldia gertatzen denean
- Erantzun taldeen arteko koordinazioa

**Larrialdi Erantzun Taldea:**

| Rola | Nagusia | Ordezkoa | Erantzukizunak |
|------|---------|----------|----------------|
| **Intzidente Komandantea** | CEO | CFO | Agintaritza orokorra |
| **Eragiketa Burua** | Eragiketa Kudeatzailea | Produkzio Begiralea | Produkzio berreskuratzea |
| **IT Berreskuratze Burua** | IT Kudeatzailea | Sistema Admin Nagusia | IT sistema berreskuratzea |
| **OT Berreskuratze Burua** | Mantentze Kudeatzailea | Teknikari Nagusia | OT sistema berreskuratzea |
| **Komunikazio Burua** | HR Kudeatzailea | Marketin Kudeatzailea | Komunikazioa |
| **Logistika Burua** | Biltegi Kudeatzailea | Hornidura Kudeatzailea | Hornitzaile koordinazioa |
| **Segurtasun Ofiziala** | O&S Kudeatzailea | Kalitate Kudeatzailea | Langile segurtasuna |
| **Finantza Burua** | CFO | Kontrolatzailea | Larrialdi finantzaketa |
| **Aholkulari Legala** | Aholkulari Juridikoa | Kanpo Abokatu Bulegoa | Kontratu betebeharrak |

### 6.2 Agintaritza eta Erabakiak Hartzea {#agintaritza}

**Erabaki Hierarkia:**

1. **Intzidente Komandantea (CEO):** Erabaki nagusi guztien azken agintaritza
2. **BCP Koordinatzailea (CISO):** Prozedurak aktibatzeko agintaritza
3. **Funtzio Buruak:** Agintaritza beren eremuan

**Larrialdi Baimena:**

- 50.000 € arte: BCP Koordinatzailearen onarpena
- 50.000 € - 200.000 €: Intzidente Komandantearen onarpena
- >200.000 €: Intzidente Komandantea + CFO onarpena

---

## 7. Aktibazio Prozedurak

### 7.1 Aktibazio Irizpideak {#aktibazio-irizpideak}

BCP aktibatzen da honako kasuetan:

- Sistema kritikoaren hutsegitea >30 minutu
- Instalazioa ez dago erabilgarri
- Ziber intzidentea sistema kritikoei eragiten
- Hondamendi naturala eragiketei eragiten
- Funtsezko langileak ez daude eskuragarri
- MTPD atalaseak mehatxatzen dituen edozein gertaera

### 7.2 Aktibazio Prozesua {#aktibazio-prozesua}

#### 1. Urratsa: Detekzioa eta Jakinarazpena (0-15 minutu)

1. Etenaldia detektatzen duen edozein langilek jakinarazi behar dio:
   - Zuzeneko kudeatzaileari
   - Segurtasun telefonoari: +34 XXX XXX XXX
   - Emaila: <emergency@zabalagailetak.com>
2. Segurtasun/IT taldeak larritasuna ebaluatzen du
3. Aktibazio irizpideak betetzen baditu, BCP Koordinatzailea jakinarazi

#### 2. Urratsa: Hasierako Ebaluazioa (15-30 minutu)

1. BCP Koordinatzaileak aktibazio erabakia berresten du
2. Intzidente Komandantea jakinarazi
3. Larrialdi Erantzun Taldea bildu
4. Eragin ebaluazio azkarra egin

#### 3. Urratsa: BCP Aktibazio Adierazpena (30-60 minutu)

1. Intzidente Komandanteak formalki BCP aktibazioa deklaratzen du
2. Komando Zentroa ezarri:
   - Nagusia: Bulego nagusiko konferentzia gela
   - Babeskopia: Birtuala (Zoom/Teams deia)
3. Langile guztiak jakinarazi larrialdi alerta sistemaren bidez
4. Larrialdi Erantzun Taldea informatu

#### 4. Urratsa: Berreskuratze Prozedurak Exekutatu

- Funtzio buruek berreskuratze prozedurak exekutatzen dituzte
- Ohiko egoera eguneraketak Intzidente Komandanteari
- Estrategia egokitu egoeraren arabera

---

## 8. Berreskuratze Prozedurak

### 8.1 IT Sistema Berreskuratzea {#it-berreskuratzea}

#### Sistema Kritikoen Lehentasun Ordena

1. Sare azpiegitura eta suebakiak
2. Autentifikazio sistemak (Active Directory/LDAP)
3. Datu-base zerbitzariak
4. Web aplikazioa (eskaera kudeaketa)
5. Posta elektroniko zerbitzaria
6. Fitxategi zerbitzaria
7. VPN urruneko sarbiderako

#### Hodeian Ostatututako Sistemetarako (AWS/Azure)

1. Egiaztatu hodei hornitzailearen egoera
2. Eskualdeko hutsegitea bada, failover bigarren eskualdera:

```bash
aws route53 change-resource-record-sets --hosted-zone-id ZXXXXX \
  --change-batch file://failover-dns.json
```

3. Egiaztatu datu-base erreplikazio egoera
4. Probatu aplikazioaren konektibitatea eta funtzionalitatea
5. Eguneratu DNS DR ingurunera

#### Lokaleko Sistemetarako

1. Kaltea ebaluatu:
   - Hardware hutsegitea? Ordeztu babeskopia zerbitzarira
   - Software ustelkeria? Berreskuratu babeskopiatik
   - Sare arazoa? Aldatu babeskopia ISP-ra
2. Hardware ordezkapena behar bada:
   - Eskuratu ordezko zerbitzaria
   - Instalatu babeskopia iruditik
   - Berreskuratu azken babeskopia
3. Datu zentroa erabilgarri ez badago:
   - Aktibatu hodeian oinarritutako DR ingurunea

#### Babeskopia Berreskuratze Prozedura

1. Identifikatu azken babeskopia garbia:

```bash
ls -lh /backup/mongodb/ | grep $(date +%Y-%m-%d)
```

2. Egiaztatu babeskopia osotasuna:

```bash
sha256sum -c backup-20260108.tar.gz.sha256
```

3. Berreskuratu datu-basea:

```bash
mongorestore --host localhost --port 27017 --gzip \
  --archive=/backup/mongodb/backup-20260108.tar.gz
```

4. Berreskuratu aplikazio fitxategiak:

```bash
rsync -avz /backup/application/ /var/www/zabalagailetak/
```

5. Egiaztatu datu osotasuna

#### Estimatutako Berreskuratze Denborak

- Hodei failover: 30-60 minutu
- Babeskopia berreskuratzea: 2-4 ordu
- DR gune aktibazio osoa: 4-6 ordu

### 8.2 OT Sistema Berreskuratzea {#ot-berreskuratzea}

#### OT Sistema Kritikoen Lehentasun Ordena

1. Segurtasun (Safety) sistemak (larrialdi gelditzeak, sute itzaltzea)
2. Produkzio PLC nagusia (nahasketa eta labeketa)
3. Enbalatze linea PLC
4. SCADA monitorizazio sistema
5. Tenperatura/hezetasun kontrola
6. Inbentario jarraipena

#### PLC Hutsegiterako

1. **Segurtasuna Lehenik:**
   - Ziurtatu produkzio linea guztiak segurtasunez gelditu direla
   - Lock out/tag out hornidura elektrikoa
2. **Diagnostikoa:**
   - Egiaztatu errore kodeak PLC pantailan
   - Egiaztatu energia hornidura eta konexioak
3. **Berreskuratze Aukerak:**
   - **A Aukera:** Berrabiarazi PLC, kargatu programa babeskopiatik
   - **B Aukera:** Ordeztu PLC ordezko inbentariotik
   - **C Aukera:** Aldatu eskuzko eragiketa prozeduretara
4. **PLC Ordezkapen Prozedura:**
   - Instalatu ordezko PLC
   - Kargatu babeskopia konfigurazioa USB unitatetik
   - Probatu simulazio moduan
   - Konektatu I/O eta egiaztatu sentsore irakurketa guztiak
5. **Eskuzko Eragiketa (PLC erabilgarri ez badago):**
   - Aktibatu eskuzko kontrol panelak
   - Jarraitu eskuzko eragiketa kontrol-zerrenda
   - Produkzio tasa: Normalaren %30

#### SCADA Sistema Hutsegiterako

1. Egiaztatu PLC kontrola oraindik funtzionala dela
2. Berreskuratu SCADA zerbitzaria babeskopiatik
3. Aldi baterako monitorizazioa: PLC interfaze zuzena

#### Instalazio Osoa Galtzen Bada

1. Aktibatu bazkide fabrikazio akordioak
2. Transferitu produkzio errezetak eta zehaztapenak
3. Hedatu kalitate kontrol kudeatzailea bazkide instalaziora
4. Koordinatu lehengaien entrega bazkide instalaziora
5. Estimatutako denbora-lerroa: 24 ordu

#### Estimatutako Berreskuratze Denborak

- PLC berrabiarazte/birkonfigurazioa: 1-2 ordu
- PLC ordezkapena: 4-6 ordu
- SCADA berreskuratzea: 2-4 ordu
- Eskuzko eragiketa aktibazioa: 30 minutu

### 8.3 Produkzio Eragiketen Berreskuratzea {#produkzio-berreskuratzea}

#### 1. Fasea: Berehalako Erantzuna (0-4 ordu)

**1. Langileen Segurtasuna:**
- Langile guztiak zenbatu
- Eman lehen sorospenak beharrezkoa bada
- Ebakuatu beharrezkoa bada

**2. Kalte Ebaluazioa:**
- Ikuskapen bisuala
- Dokumentatu argazki/bideoekin
- Identifikatu berehalako arriskuak

**3. Instalazioa Ziurtatu:**
- Itxi utilitateak arriskutsua bada
- Blokeatu kaltetutako eremuetarako sarrerak

**4. Bezero Jakinarazpena:**
- Produkzioa >4 ordu atzeratzen bada, jakinarazi bezeroei

#### 2. Fasea: Egonkortzea (4-8 ordu)

**1. Utilitateak Berrezartzea:**
- Koordinatu utilitate hornitzaileekin
- Aktibatu babeskopia sorgailua argindarra ez badago
- Egiaztatu ur kalitatea

**2. Ekipamendu Kritikoen Lehentasuna:**
- Zentratu lehentasun handieneko produkzio linean
- Probatu ekipamendu funtzionamendua produkturik gabe
- Kalitate kontrol egiaztapenak

**3. Langileen Mobilizazioa:**
- Deitu funtsezko produkzio langileei
- Informatu egoeraz

**4. Hornitzaile Koordinazioa:**
- Egiaztatu lehengaien eskuragarritasuna

#### 3. Fasea: Produkzioa Berrekitea (8-24 ordu)

**1. Produkzio Partziala Hasi:**
- Produkzio linea bakarra hasieran
- Kalitate egiaztapen hobetuak

**2. Inbentario Kudeaketa:**
- Egiaztatu amaitutako produktuen inbentarioa
- Lehenetsi eskaerak

**3. Komunikazio Eguneraketak:**
- Eguneratu bezeroak berreskuratze aurrerapenaz

#### 4. Fasea: Berrezarpen Osoa (24-72 ordu)

**1. Produkzioa Eskalatu:**
- Aktibatu produkzio linea gehigarriak
- Txanda luzeak edo aparteko orduak

**2. Eragiketa Normaletara Itzuli:**
- Berrekin kalitate kontrol maiztasun normala
- Produkzio programazio estandarra

**3. Finantza Berreskuratzea:**
- Aurkeztu aseguru erreklamazioa

### 8.4 Instalazio Berreskuratzea {#instalazio-berreskuratzea}

**Lehen Mailako Instalazioa >72 ordu Ez Bada Erabilgarri:**

**Berehalako Ekintzak (0-24 ordu):**

1. Aktibatu bazkide fabrikazio akordioak
2. Ezarri aldi baterako bulego espazioa
3. Jakinarazi interesdun guztiei

**Epe Laburreko Berreskuratzea (1-4 aste):**

1. Alokatu aldi baterako produkzio espazioa
2. Birlekutu funtsezko ekipamendua
3. Berreraiki azpiegitura kritikoa

**Epe Luzeko Berreskuratzea (1-6 hilabete):**

1. Instalazioaren berreraikuntza osoa
2. Berritu sistemak
3. Pixkanaka itzuli lehen mailako instalaziora

---

## 9. Komunikazio Plana

### 9.1 Barne Komunikazioa {#barne-komunikazioa}

**Larrialdi Jakinarazpen Sistema:**

```text
ZABALA GAILETAK LARRIALDI ALERTA
Data/Ordua: [AUTO]
Intzidentea: [Deskribapen laburra]
Egoera: [Seguru/Ebakaitu/Urruneko Lana/Itxaron]
Hurrengo Eguneraketa: [Ordua]
Kontaktua: [BCP Koordinatzaile zenbakia]
```

**Egoera Eguneraketak:**

- Intzidentean zehar: Gutxienez 2 orduro
- Ebazpenaren ondoren: Eguneroko eguneraketak

### 9.2 Kanpo Komunikazioa {#kanpo-komunikazioa}

**Bezero Komunikazioa:**

- **Jakinarazpen Muga:** Eskaerei >4 ordu eragiten dien edozein etenaldi
- **Denbora:** Intzidentetik 2 orduko epean
- **Metodoa:** Emaila kontu kontaktuei, telefono deiak bezero nagusiei

**Hedabideekiko Harremanak:**

- **Bozeramailea:** CEO bakarrik (ordezkoa: CFO)
- **Komentariorik Ez Politika:** Langileek hedabideen kontsulta guztiak CEOri bideratu
- **Prestatutako Adierazpena:** Zirriborroa intzidente handiaren 4 orduko epean

**Aseguru Komunikazioa:**

- Jakinarazi aseguru artekariari 24 orduko epean
- Dokumentatu kalte guztiak argazki, bideo, inbentario zerrendekin

---

## 10. Probak eta Mantentzea

### 10.1 Proba Egutegia {#proba-egutegia}

| Proba Mota | Maiztasuna | Parte-hartzaileak | Iraupena | Helburuak |
|------------|------------|-------------------|----------|-----------|
| **Mahai-gaineko Ariketa** | Hiruhilero | Larrialdi Erantzun Taldea | 2 ordu | BCP eszenatokiak landu |
| **IT DR Proba** | Hiruhilero | IT taldea | 4 ordu | Babeskopiak berreskuratu |
| **OT Eskuzko Eragiketa Simulazioa** | Sei Hilero | Produkzio taldea | 2 ordu | Eskuzko eragiketa praktikatu |
| **Eskala Osoko Ariketa** | Urtero | Langile guztiak | 4-8 ordu | Etenaldi handia simulatu |
| **Komunikazio Proba** | Hilero | HR/Komunikazioa | 30 min | Larrialdi jakinarazpena probatu |
| **Babeskopia Berreskuratze Proba** | Hilero | IT taldea | 2 ordu | Ausazko babeskopia berreskuratu |

### 10.2 Mahai-gaineko Ariketa Eszenatokiak {#mahai-gaineko-eszenatokiak}

**1. Eszenatokia: Ransomware Erasoa**

- Egoera: Astelehen goiza, zerbitzari guztiak enkriptatuta
- Eztabaida Puntuak: Infekzioa isolatu, babeskopietatik berreskuratu

**2. Eszenatokia: Instalazio Sutea**

- Egoera: Sutea produkzio eremuan
- Eztabaida Puntuak: Langileen segurtasuna, bazkide fabrikazio aktibazioa

**3. Eszenatokia: Funtsezko Langileak Ez Egotea**

- Egoera: IT Kudeatzailea eta ordezkoa ez daude eskuragarri
- Eztabaida Puntuak: Ondorengotza plangintza, dokumentazio eskuragarritasuna

**4. Eszenatokia: Hornidura Kate Etenaldia**

- Egoera: Lehen mailako irina hornitzailea porrot eginda
- Eztabaida Puntuak: Aktibatu bigarren mailako hornitzailea

### 10.3 Eskala Osoko Ariketa {#eskala-osoko-ariketa}

**Urteroko Ariketa (Adibidea: Azaroa):**

**Prestaketa (Hilabete 1 lehenago):**

- Hautatu eszenatokia
- Garatu ariketa gidoia
- Jakinarazi parte-hartzaileei

**Exekuzioa (Ariketa Eguna):**

1. **Hasiera (0900):** Eszenarioari buruzko informazio saioa
2. **1. Injekzioa (0915):** Lurrikara jakinarazi da
3. **2. Injekzioa (0945):** Egitura kalteak aurkitu dira
4. **3. Injekzioa (1015):** IT sistemak lineaz kanpo
5. **4. Injekzioa (1100):** Bazkide fabrikazioa kontaktatu
6. **5. Injekzioa (1130):** Bezeroen kontsultak iristen
7. **Amaiera (1300):** Hot wash

### 10.4 Plan Mantentzea {#plan-mantenimendua}

**Eguneratze Abiarazleak:**

- Urteroko programatutako berrikuspena
- BCP aktibazio baten ondoren
- Probak hutsuneak identifikatu ondoren
- Antolakuntza aldaketak
- Arau aldaketak

**Berrikuspen Prozesua:**

1. BCP Koordinatzaileak plan osoa berrikusten du
2. Atal jabeek beren eremuak berrikusten dituzte
3. Eguneratu kontaktu zerrendak
4. Egiaztatu hornitzaile akordioak
5. Eguneratu finantza eraginaren estimazioak
6. Berrikusi probetatik ikasitako ikasgaietan oinarrituta
7. Lortu exekutibo onarpena
8. Banatu plan eguneratua

---

## 11. Berreskuratze Metrikak eta Arrakasta Irizpideak

### 11.1 Errendimendu Adierazle Gakoak (KPIak) {#kpiak}

| KPI | Helburua | Neurketa |
|-----|----------|----------|
| Larrialdi Erantzun Taldea biltzeko denbora | <30 minutu | Aktibaziotik taldera |
| Hasierako bezero komunikaziorako denbora | <2 ordu | Intzidentetik |
| IT sistema RTO lorpena | <4 ordu | Berreskuratze denbora |
| OT sistema RTO lorpena | <8 ordu | Berreskuratze denbora |
| Datu galera (RPO lorpena) | <1 ordu | Babeskopia berreskuratzean |
| Produkzio gaitasuna 24 ordutan | >%60 | Produkzioaren ehunekoa |
| Eragiketa berrezarpen osoa | <72 ordu | Denbora |
| Langile segurtasun intzidenteak | 0 | Kopurua |

### 11.2 Arrakasta Irizpideak {#arrakasta-irizpideak}

**Berehalako Arrakasta (0-4 ordu):**

- Langile guztiak kontuan hartuta eta seguru
- Larrialdi Erantzun Taldea bilduta
- Kalteak ebaluatuta eta dokumentatuta
- Hasierako bezero komunikazioa bidalita

**Epe Laburreko Arrakasta (4-24 ordu):**

- IT sistema kritikoak berrezarrita
- Produkzioa berrekin (gutxienez %60ko gaitasuna)
- Aldizkako eguneraketak interesdunei emanda

**Epe Luzeko Arrakasta (1-7 egun):**

- Produkzio gaitasun osoa berrezarrita
- Atzeratutako eskaera guztiak beteta
- Intzidente osteko berrikuspena eginda

---

## 12. Eranskinak

### Eranskina A: Larrialdi Kontaktu Zerrenda {#eranskina-a}

**Funtsezko Langileak:**

| Izena | Rola | Mugikorra | Emaila |
|-------|------|-----------|--------|
| [CEO Izena] | Intzidente Komandantea | +34 XXX XXX XXX | <ceo@zabalagailetak.com> |
| [CISO Izena] | BCP Koordinatzailea | +34 XXX XXX XXX | <ciso@zabalagailetak.com> |
| [CFO Izena] | Finantza Burua | +34 XXX XXX XXX | <cfo@zabalagailetak.com> |
| [IT Kudeatzailea] | IT Berreskuratze Burua | +34 XXX XXX XXX | <it@zabalagailetak.com> |
| [Eragiketa Kudeatzailea] | Eragiketa Burua | +34 XXX XXX XXX | <ops@zabalagailetak.com> |

**Kanpo Larrialdi Zerbitzuak:**

| Zerbitzua | Kontaktua | Helburua |
|-----------|-----------|----------|
| Larrialdi Zerbitzuak | 112 | Suhiltzaileak, Polizia, Medikuak |
| Polizia (Nazionala) | 091 | Segurtasun intzidenteak |
| Suhiltzaileak | 080 | Sute larrialdiak |
| INCIBE | +34 017 | Zibersegurtasun intzidenteak |
| AEPD | +34 901 100 099 | Datu-urraketa jakinarazpena |

**Hornitzaile Kritikoak:**

| Hornitzailea | Produktua | Telefonoa |
|--------------|-----------|-----------|
| [Irina Hornitzailea A] | Gari irina | +34 XXX |
| [Irina Hornitzailea B] | Gari irina | +34 XXX |
| [Enbalatze Enpresa] | Kaxak, bilgarriak | +34 XXX |
| [Elektrizitate Konpainia] | Energia hornidura | +34 XXX |
| [ISP A] | Internet (nagusia) | +34 XXX |
| [ISP B] | Internet (babeskopia) | +34 XXX |
| [AWS/Azure] | Hodei ostalaritza | +34 XXX |

**Bazkide Fabrikatzaileak:**

| Enpresa | Gaitasuna | Telefonoa |
|---------|-----------|-----------|
| [Bazkidea A] | Gure bolumenaren %40 | +34 XXX |
| [Bazkidea B] | Gure bolumenaren %30 | +34 XXX |

**Asegurua eta Legala:**

| Zerbitzua | Enpresa | Telefonoa |
|-----------|---------|-----------|
| Negozio Asegurua | [Aseguratzailea] | +34 XXX |
| Ziber-asegurua | [Aseguratzailea] | +34 XXX |
| Aholkulari Juridikoa | [Abokatu Bulegoa] | +34 XXX |

### Eranskina B: IT Sistema Inbentarioa {#eranskina-b}

**IT Aktibo Kritikoak:**

| Sistema | Kokapena | Helburua | DR Estrategia |
|---------|----------|----------|---------------|
| Web Aplikazioa | AWS eu-west-1 | Eskaera kudeaketa | Eskualde anitzeko failover |
| Datu-basea (MongoDB) | AWS eu-west-1 | Bezero/eskaera datuak | Replika multzoa |
| Email Zerbitzaria | Microsoft 365 | Komunikazioa | Hodei erresilientea |
| Fitxategi Zerbitzaria | Lokala + OneDrive | Dokumentuak | Hodei sinkronizazioa |
| Suebakia | Lokala | Sare segurtasuna | Ordezko hardwarea |
| SCADA Zerbitzaria | Lokala | Produkzio monitorizazioa | Hot standby |

### Eranskina C: Eskuzko Eragiketa Prozedurak {#eranskina-c}

**Produkzio Linearen Eskuzko Eragiketa (PLC Hutsegitea):**

**Segurtasun Aurrebaldintzak:**

- Larrialdi gelditze sistema funtzionala
- Langile guztiak eskuzko prozeduretan trebatuta

**Prozedura:**

1. Aldatu kontrol panela "Eskuzko" modura
2. Eskuz hasi nahasgailua
3. Kargatu osagaiak errezetaren arabera
4. Monitorizatu nahasketa denbora kronometroarekin
5. Eskuz aktibatu laberako garraiatzailea
6. Monitorizatu labe tenperatura eskuz
7. Eskuz aurreratu labeketa zikloa
8. Eskuz aktibatu hozte garraiatzailea
9. Eskuz transferitu enbalatzera
10. Kalitate egiaztapena lote bakoitzean

**Mugak:**

- Produkzio tasa: Gaitasun normalaren %30
- Kalitate monitoreo hobetua beharrezkoa
- Gehieneko etengabeko eragiketa: 4 ordu

### Eranskina D: Babeskopia Egiaztapen Kontrol-zerrenda {#eranskina-d}

**Hileroko Babeskopia Proba (IT taldeak egina):**

- [ ] Hautatu ausazko babeskopia
- [ ] Egiaztatu babeskopia fitxategiaren osotasuna
- [ ] Berreskuratu proba ingurunera
- [ ] Egiaztatu datu-base konektibitatea
- [ ] Egiaztatu datu osotasuna
- [ ] Ausazko egiaztapena datu kalitatean
- [ ] Probatu aplikazio funtzionalitatea
- [ ] Neurtu berreskuratze denbora
- [ ] Dokumentatu emaitzak
- [ ] Jakinarazi edozein arazo

### Eranskina E: Intzidente Log Txantiloia {#eranskina-e}

**Negozio Jarraitutasun Intzidente Log-a:**

```
| Intzidente IDa: | BCP-20XX-XXX | Data/Ordua: | [Hasiera ordua] |
|-----------------|--------------|-------------|-----------------|
| **Intzidente Komandantea** | [Izena] | **BCP Koordinatzailea** | [Izena] |
| **Intzidente Mota** | [ ] Ziber [ ] IT [ ] OT [ ] Instalazioa | | |
| **Kaltetutako Sistemak** | | | |
| **Eragin Ebaluazioa** | [ ] Kritikoa [ ] Altua [ ] Ertaina [ ] Baxua | | |

**Denbora-lerroa:**

| Ordua | Gertaera | Hartutako Ekintza | Nork |
|-------|----------|-------------------|------|
| | | | |

**Berreskuratze Egoera:**

- [ ] BCP aktibatua (Ordua: ____)
- [ ] Larrialdi Erantzun Taldea bilduta (Ordua: ____)
- [ ] IT sistemak berrezarrita (Ordua: ____)
- [ ] OT sistemak berrezarrita (Ordua: ____)
- [ ] Produkzioa berrekin (Ordua: ____)
- [ ] BCP desaktibatua (Ordua: ____)
```

---

#### NEGOZIOAREN JARRAITUTASUN PLANAREN AMAIERA

---

**Dokumentu Banaketa:**

Inprimatutako kopiak larrialdi-kitetan gordeta hemen:

1. CEO bulegoa (A Eraikina, 301 Gela)
2. CISO bulegoa (A Eraikina, 201 Gela)
3. Produkzio begiralearen bulegoa (B Eraikina, 1. Solairua)
4. Segurtasun bulegoa (Sarrera nagusia)

Kopia digitalak eskuragarri honela:

- ISMS dokumentu biltegia (sarbide kontrolatua)
- Hodei biltegiratzea: [URL] (MFA beharrezkoa)
- Larrialdi Erantzun Taldeko kideen enpresako ordenagailu eramangarriak

**GARRANTZITSUA:** Dokumentu honek Zabala Gailetak-en berreskuratze
gaitasunei eta ahultasunei buruzko informazio sentikorra dauka.
Kudeatu konfidentzialtasun kontrol egokiekin.
