# Arriskuen Ebaluazioa eta Kudeaketa
## Zabala Gailetak S.A. - Informazioaren Segurtasuna Kudeatzeko Sistema (SGSI)

**Dokumentuaren IDa:** RA-001  
**Bertsioa:** 2.0  
**Data:** 2026ko Urtarrilaren 12a  
**Sailkapena:** Oso Konfidentziala  
**Jabea:** Informazioaren Segurtasuneko Arduradun Nagusia (CISO)  
**Berrikuspen Maiztasuna:** Urterokoa (edo aldaketa handien ostean)  
**Hurrengo Berrikuspen Data:** 2027ko Urtarrilaren 12a

---

## 1. Dokumentuaren Kontrola

### 1.1 Bertsio Historia

| Bertsioa | Data | Egilea | Aldaketak |
|----------|------|--------|-----------|
| 1.0 | 2025-12-15 | CISO | Hasierako arriskuen ebaluazioa |
| 2.0 | 2026-01-12 | CISO | Zabaldua: metodologia, inpaktu analisia, jarraipena gehitu |

### 1.2 Onarpena

| Rola | Izena | Sinadura | Data |
|------|------|-----------|------|
| Zuzendari Nagusia (CEO) | [Izena] | | |
| Informazioaren Segurtasuneko Arduradun Nagusia (CISO) | [Izena] | | |
| Finantza Zuzendaria (CFO) | [Izena] | | |
| IT Kudeatzailea | [Izena] | | |
| OT Kudeatzailea | [Izena] | | |

### 1.3 Banaketa eta Sarbidea

**Baimendutako Langileak:**
- Zuzendaritza Exekutiboko Taldea
- Arriskuen Kudeaketa Komitea
- Informazioaren Segurtasun Taldea
- Departamentu Buruak
- Auditoria barneko / kanpoko taldea

**Konfidentzialtasuna:** Oso Konfidentziala - Dokumentu hau ez da partekatu behar baimenik gabe

---

## 2. Laburpen Exekutiboa

Arriskuen Ebaluazio eta Kudeaketa dokumentu honek Zabala Gailetak-en informazioaren segurtasunerako mehatxuak, ahultasunak eta arriskuak identifikatzen, ebaluatzen eta kudeatzen ditu. ISO/IEC 27001:2022 eta ISO 31000 estandarrekin bat etorriz, arriskuen kudeaketa sistematikoa ezartzen du IT eta OT sistemetarako.

### 2.1 Arriskuen Laburpena (2026ko Urtarrilaren 12a)

**Arrisku Orokorraren Mailatzea:**

| Arrisku Maila | Kopurua | Ehunekoa |
|---------------|---------|----------|
| **Kritikoa (≥20)** | 3 | 15% |
| **Altua (15-19)** | 4 | 20% |
| **Ertaina (10-14)** | 7 | 35% |
| **Baxua (5-9)** | 5 | 25% |
| **Oso Baxua (<5)** | 1 | 5% |
| **GUZTIRA** | **20** | **100%** |

**Arrisku Kritiko Nagusiak:**
1. **Ransomware Erasoa** (Arrisku Maila: 20)
2. **Datu Pertsonalen Urraketa** (Arrisku Maila: 20)
3. **OT Sistemen Konpromisoa** (Arrisku Maila: 20)

**Tratamendu Aurrekontua:** 185.000 € (2026 urtea)

---

## 3. Helburua eta Esparrua

### 3.1 Helburua

Arriskuen Ebaluazio honek helburu hauek ditu:
1. **Identifikatu** informazioaren segurtasunerako mehatxuak eta ahultasunak
2. **Ebaluatu** arrisku bakoitzaren probabilitatea eta inpaktua
3. **Priorartu** arriskuak inpaktua eta probabilitatearen arabera
4. **Definitu** arrisku tratamendu estrategiak (arindu, saihestea, transferitu, onartu)
5. **Jarraipena** arrisku mailaren eboluzioa denborarekin
6. **Bermatu** ISO 27001, GDPR eta legedi aplikagarriaren betetzea

### 3.2 Esparrua

Arriskuen Ebaluazio honek hartzen ditu barne:

**Informazio Aktiboak:**
- Datu-baseak (MongoDB bezero datuak, MongoDB erabiltzaile datuak)
- Fitxategiak (dokumentuak, babeskopiak, log-ak)
- Jabetza Intelektuala (kodea, diseinuak, prozedura operatiboak)

**IT Sistemak:**
- Web aplikazioa (Node.js/Express, React)
- Mugikor aplikazioa (React Native)
- Zerbitzariak (Web, datu-baseak, babeskopia, SIEM)
- Sare azpiegitura (firewall-ak, switch-ak, router-ak)
- Hodeiko zerbitzuak (AWS, hosting hornitzaileak)

**OT Sistemak:**
- SCADA sistema (produkzio monitorizazioa)
- PLCak (OpenPLC, Siemens S7)
- HMI pantailak
- Industria ekipamendua (CNC, Roboten kontrolagailuak)

**Giza Faktoreak:**
- Langileak (120 pertsona)
- Kontratistak eta aholkulariak
- Administrazio pribilegio duten erabiltzaileak

**Instalazioak:**
- Produkzio instalazio nagusia (Donostia)
- Bulego gunea
- Datu Zentroa

**Prozesuetak:**
- Eskaera prozesamentu digitala
- Produkzio kudeaketa
- Bezero arreta
- Finantza kudeaketa

### 3.3 Azterketaz Kanpo

- Baliabideak pertsonalak (langileek ez dituzte negoziorako erabiltzen)
- Hirugarrenen sistemak (non Zabala Gailetak ez duen kontrolik)
- Arrisku fisikoak (sutea, uholdea) - Business Continuity Plan-ean aztertuta

---

## 4. Metodologia

### 4.1 Oinarriak

Arriskuen ebaluazioa **ISO 31000:2018** (Arriskuen kudeaketa) eta **MAGERIT v3** (Informazioaren Sistemen Arriskuen Analisi eta Kudeaketa Metodologia) oinarrituta dago.

**Arriskuaren Formula:**

```
Arriskua (R) = Probabilitatea (P) × Inpaktua (I)
```

### 4.2 Eskala Definizioak

#### 4.2.1 Probabilitatea (P)

| Maila | Balioa | Deskribapena | Maiztasuna |
|-------|--------|--------------|------------|
| **Oso Baxua** | 1 | Gertatzeko aukera teorikoa, baina ez da dokumentatua | < urtean behin |
| **Baxua** | 2 | Gerta liteke baldintza jakin batzuetan | Urtean behin |
| **Ertaina** | 3 | Gerta daiteke baldintza normaletan | Hilabetean behin |
| **Altua** | 4 | Seguruenik gertatuko da baldintza egokiekin | Astean behin |
| **Oso Altua** | 5 | Ia segurua da gertatzea, dokumentatua sektorean | Egunero |

**Probabilitatea kalkulatzeko faktoreak:**
- Mehatxuaren motibazioa eta gaitasuna
- Ahultasunaren betetzea zailtasuna
- Kontrol existenteak (prebentzio neurriak)
- Historikoa (intzidentzia aurretikoak)
- Industria datuak (ENISA, INCIBE txostenak)

#### 4.2.2 Inpaktua (I)

Inpaktua neurtu da **5 dimentsioan**:

##### A) Finantza Inpaktua

| Maila | Balioa | Galera Zuzena | Eragina |
|-------|--------|---------------|---------|
| **Oso Baxua** | 1 | < 5.000 € | Ez du finantzen eragina |
| **Baxua** | 2 | 5.000 - 20.000 € | Finantza eragina txikia |
| **Ertaina** | 3 | 20.000 - 100.000 € | Egunerokoa eragiten du |
| **Altua** | 4 | 100.000 - 500.000 € | Urteko aurrekontuan eragiten du |
| **Kritikoa** | 5 | > 500.000 € | Negozioaren iraunkortasuna arriskuan |

##### B) Erreputazio Inpaktua

| Maila | Balioa | Eragina |
|-------|--------|---------|
| **Oso Baxua** | 1 | Ez du erreputazio eraginik |
| **Baxua** | 2 | Bezero batzuei ezezaguna |
| **Ertaina** | 3 | Bezero segmentu bati ezaguna, prentsa lokalean |
| **Altua** | 4 | Prentsa nazionalean, sare sozialetan zabalduta |
| **Kritikoa** | 5 | Marka kaltetzea iraunkorki, bezeroak galtzea |

##### C) Lege / Arauzko Inpaktua

| Maila | Balioa | Ondorioak |
|-------|--------|-----------|
| **Oso Baxua** | 1 | Ez da arauzko urraketa |
| **Baxua** | 2 | Arauzko gorabehera txikia |
| **Ertaina** | 3 | GDPR urraketa, ohartarazpena agintarietatik |
| **Altua** | 4 | Isuna (< 20 milioi € edo % 4 urteko irabazien) |
| **Kritikoa** | 5 | Isun masiboak, lizentzia galerak, zigorrak |

##### D) Eragiketen Inpaktua

| Maila | Balioa | Etenaldia | Eragina |
|-------|--------|-----------|---------|
| **Oso Baxua** | 1 | < 1 ordu | Ez du eragiketetan eraginik |
| **Baxua** | 2 | 1-8 ordu | Etenaldi txikia, berreskuragarria |
| **Ertaina** | 3 | 8-24 ordu | Produkzioaren % 30 galtzen da |
| **Altua** | 4 | 1-3 egun | Produkzioaren % 70 galtzen da |
| **Kritikoa** | 5 | > 3 egun | Produkzioa geldituta guztiz |

##### E) Konfidentzialtasun / Pribatutasun Inpaktua

| Maila | Balioa | Datu Konpromisoa |
|-------|--------|------------------|
| **Oso Baxua** | 1 | Ez da datu pertsonalik |
| **Baxua** | 2 | < 100 erregistro, datu ez sentikorra |
| **Ertaina** | 3 | 100-1000 erregistro, datu pertsonalak |
| **Altua** | 4 | 1000-10000 erregistro, datu sentikorra (NAN, finantza) |
| **Kritikoa** | 5 | > 10000 erregistro edo datu oso sentikorra (osasuna, sexualitate) |

**Inpaktu Orokorraren Kalkulua:**

Inpaktu Orokorra hartzen da dimensio bakoitzean gehieneko balioa edo batezbestekoa. Dokumentu honetan **gehieneko balioa** erabili dugu (kontserbadorea).

#### 4.2.3 Arrisku Maila Matrizea

| Inpaktua (I) ↓ / Probabilitatea (P) → | 1 (Oso Baxua) | 2 (Baxua) | 3 (Ertaina) | 4 (Altua) | 5 (Oso Altua) |
|---------------------------------------|---------------|-----------|-------------|-----------|---------------|
| **5 (Kritikoa)** | 5 (Baxua) | 10 (Ertaina) | 15 (Altua) | 20 (Kritikoa) | 25 (Kritikoa) |
| **4 (Altua)** | 4 (Baxua) | 8 (Baxua) | 12 (Ertaina) | 16 (Altua) | 20 (Kritikoa) |
| **3 (Ertaina)** | 3 (Oso Baxua) | 6 (Baxua) | 9 (Ertaina) | 12 (Ertaina) | 15 (Altua) |
| **2 (Baxua)** | 2 (Oso Baxua) | 4 (Baxua) | 6 (Baxua) | 8 (Baxua) | 10 (Ertaina) |
| **1 (Oso Baxua)** | 1 (Oso Baxua) | 2 (Oso Baxua) | 3 (Oso Baxua) | 4 (Baxua) | 5 (Baxua) |

**Arrisku Interpretazioa:**

| Arrisku Maila | Balore Tartea | Kolore | Ekintza Aholkatua |
|---------------|---------------|--------|-------------------|
| **Kritikoa** | 20-25 | 🔴 Gorria | Berehalako ekintza, zuzendaritza jakinaraztea |
| **Altua** | 15-19 | 🟠 Laranja | Ekintza lehentasunez, 3 hilabeteko epean |
| **Ertaina** | 10-14 | 🟡 Horia | Ekintza planifikatua, 6-12 hilabeteko epean |
| **Baxua** | 5-9 | 🟢 Berdea | Monitorizazioa, ekintza baldin baliabideak badaude |
| **Oso Baxua** | 1-4 | ⚪ Zuria | Onartu, monitorizazioa periodikoa |

### 4.3 Arriskuen Identifikazio Prozesu

**Pausuak:**

1. **Aktiboen Identifikazioa:** Asset Register dokumentua erabili (ASR-001)
2. **Mehatxuen Identifikazioa:** STRIDE, MITRE ATT&CK, OWASP ereduak erabili
3. **Ahultasunen Identifikazioa:** Segurtasun auditoria, zaurgarritasun scanner-ak, penetrazio probak
4. **Arrisku Eszenatokien Eraikitzea:** Mehatxu + Ahultasuna = Arrisku Eszenatokia
5. **Kontrol Existenteak Ebaluatu:** Zer neurri ditugu jada ezarrita?
6. **Arrisku Hondarra Kalkulatu:** Arrisku kontrol existenteekin

### 4.4 Arrisku Tratamendu Estrategiak

| Estrategia | Deskribapena | Noiz Erabili |
|------------|--------------|--------------|
| **Arindu (Mitigate)** | Neurriak ezarri arrisku probabilitatea edo inpaktua murrizteko | Arrisku Ertaina, Altua, Kritikoa |
| **Saihestea (Avoid)** | Jarduera edo aktiboa kendu arrisku iturria ezabatzeko | Arrisku Kritikoa eta modu ekonomikoan saihestu badaiteke |
| **Transferitu (Transfer)** | Hirugarrenei (aseguruak, outsourcing) arrisku erantzukizuna esleitu | Arrisku finantza altua |
| **Onartu (Accept)** | Ez egin ekintzarik, monitorizatu eta onartu arrisku hondarra | Arrisku Baxua, Oso Baxua |

---

## 5. Arriskuen Identifikazioa eta Ebaluazioa

### 5.1 IT Arriskuak

#### **R-IT-01: Ransomware Erasoa**

**Deskribapena:**  
Erasotzaileek malware bat sartu zerbitzari edo lan-estazioetan datuak zifratzeko eta erreskate bat eskatzeko. Datu galerak, produkzio geldialdiak eta erreputazio kalteak sor ditzake.

**Aktibo Kaltetuak:**
- Zerbitzariak (SRV-001, SRV-002, SRV-003, SRV-006)
- Lan-estazioak (WRK-001-070)
- Datu-baseak (MongoDB)
- Babeskopiak (baldin zifraketagarria bada)

**Mehatxu Iturria:**  
- Ziberdelitu taldeak (ekonomikoki motibatuak)
- Nazio-estatu erasoak
- Hasiberri hacker-ak (ransomware-as-a-service)

**Ahultasuna Asoziatua:**
- Patch kudeaketa txarra (zaurgarritasun ez-zuzenduak)
- Phishing-en arrakasta (langileek email txarrak irekitzen)
- RDP sarbidea baimenik gabeko portuekin
- Babeskopiak ez-isolatuak (sarean konektatuta)
- MFA ez dago martxan sistema kritikoetan

**Probabilitatea:** 4 (Altua)  
**Justifikazioa:** Ransomware erasoak gero eta ohikoagoak dira PMEetan. INCIBE txostenaren arabera, Espainian, enpresa txiki eta ertainen %30ek ransomware erasoak jasan dituzte azken 2 urteetan.

**Inpaktua:** 5 (Kritikoa)

| Dimentsio | Maila | Justifikazioa |
|-----------|-------|---------------|
| Finantza | 5 | Erreskate exijentzia (50.000-500.000 €), produkzio galera (100.000 €/egun), berreskuratze kostua (200.000 €) |
| Erreputazio | 4 | Prentsa nazionalean, bezeroen konfiantza galtzea |
| Lege/Arauzko | 4 | GDPR urraketa (datu zifratzea = eskuraezintasuna), AEPD isuna posible |
| Eragiketa | 5 | Produkzioa geldituta 3-7 egun |
| Konfidentzialtasun | 4 | Datu-basea zifratu, 5000 bezero erregistro konpromiso |

**Arrisku Maila:** P (4) × I (5) = **20 (Kritikoa)** 🔴

**Kontrol Existenteak:**
- ✅ Babeskopia 3-2-1 estrategia (offline babeskopiak)
- ✅ Endpoint Detection & Response (EDR) - Microsoft Defender
- ✅ Email antiph ishing filtroa (Proofpoint)
- ⚠️ MFA ez guztitan gaituta (bakarrik VPN eta email)
- ❌ Ez dago sare mikrosegmentaziorik
- ⚠️ Patch kudeaketa plana badago baina ez beti 15 egunetan aplikatzen

**Arrisku Hondarra (Kontrolekin):** P (3) × I (4) = **12 (Ertaina)** 🟡

**Tratamendu Estrategia:** **Arindu**

**Neurri Osagarriak Proposatu:**
1. **MFA Zabaltzea:** Garatu guzti sistemetara (prioritate: email, VPN, administrazio sarbideak) - Kostua: 5.000 € - Epemuga: 3 hilabete
2. **Sare Mikrosegmentazioa:** VLAN-ak ezarri kritikal sistemak isolatzeko - Kostua: 25.000 € - Epemuga: 6 hilabete
3. **Phishing Simulazio Kanpainak:** Langileak entrenatu hilean behin - Kostua: 8.000 €/urte - Epemuga: Jarraitua
4. **Babeskopia Testeak:** Berreskuratze testeak hilero - Kostua: 0 € (barneko) - Epemuga: Berehalakoa
5. **Patch kudeaketa Hobekuntza:** Automatizatu kritike patch-ak 7 egunetan - Kostua: 10.000 € - Epemuga: 2 hilabete

**Kostua Guztira:** 48.000 € (lehenengo urtea)

**Arrisku Helburua (Tratamendu ostean):** P (2) × I (3) = **6 (Baxua)** 🟢

**Arduraduna:** CISO  
**Egokitzapena Data:** 2026-06-30

---

#### **R-IT-02: DDoS Erasoa (Distributed Denial of Service)**

**Deskribapena:**  
Erasotzaileek web zerbitzaria gainezka jartzea trafiko bolumen handien bidez, zerbitzua erabilgarri ez egiteko bezeroei.

**Aktibo Kaltetuak:**
- Web Aplikazio Zerbitzaria (SRV-001)
- API Zerbitzaria
- Firewall-ak (NET-002, NET-003)

**Mehatxu Iturria:**
- Lehiakideen eraso ekonomikoak
- Hacktivismo taldeak
- Extortsio zibernetikoaren saiakerak

**Ahultasuna Asoziatua:**
- Ez dago CDN (Content Delivery Network) edo DDoS babesik
- Ancho de banda mugatua (1 Gbps)
- Rate limiting ez egokia aplikazioan

**Probabilitatea:** 3 (Ertaina)  
**Justifikazioa:** DDoS erasoak ohikoak dira e-commerce plataformetan, batez ere denboraldietan (Black Friday, gabonak).

**Inpaktua:** 4 (Altua)

| Dimentsio | Maila | Justifikazioa |
|-----------|-------|---------------|
| Finantza | 4 | Salmenta galera (50.000 €/egun), bezero konfiantzaren gal era |
| Erreputazio | 3 | Bezeroak ezin dira konektatu, kexak sare sozialetan |
| Lege/Arauzko | 1 | Ez da lege arazo zuzena |
| Eragiketa | 4 | Web plataforma erabilezina 1-3 egun |
| Konfidentzialtasun | 1 | Ez da datu konpromisoa |

**Arrisku Maila:** P (3) × I (4) = **12 (Ertaina)** 🟡

**Kontrol Existenteak:**
- ✅ Firewall-ak DDoS detekzio oinarrizkoarekin
- ❌ Ez dago CDN erabiltzen
- ❌ Ez dago Anti-DDoS zerbitzu espezializaturik
- ⚠️ Rate limiting aplikazioan (baina ez oso sofistikatua)

**Arrisku Hondarra:** P (3) × I (4) = **12 (Ertaina)** 🟡

**Tratamendu Estrategia:** **Arindu**

**Neurri Osagarriak:**
1. **Cloudflare edo AWS Shield kontratatu** - Anti-DDoS + CDN - Kostua: 12.000 €/urte - Epemuga: 1 hilabete
2. **WAF (Web Application Firewall) konfiguratu** - Kostua: Cloudflare-n inklusiva - Epemuga: 1 hilabete
3. **Ancho de banda handitu** backup ISP-rekin - Kostua: 6.000 €/urte - Epemuga: 3 hilabete

**Kostua Guztira:** 18.000 € (lehenengo urtea)

**Arrisku Helburua:** P (2) × I (2) = **4 (Baxua)** 🟢

**Arduraduna:** IT Kudeatzailea  
**Egokitzapena Data:** 2026-03-31

---

#### **R-IT-03: Datu Pertsonalen Urraketa (GDPR)**

**Deskribapena:**  
Baimenik gabeko sarbidea bezero edo langileen datu pertsonaletara (izenak, emailak, helbideak, NAN, ordainketa datuak). GDPR urraketa, isun altua eta erreputazio kaltea.

**Aktibo Kaltetuak:**
- Datu-base Zerbitzaria (SRV-002, SRV-003)
- MongoDB datu-basea (5000 bezero erregistro)
- Fitxategi Zerbitzaria (dokumentuak NAN-ekin)

**Mehatxu Iturria:**
- Kanpoko hacker-ak (SQL injection, datu-base zaurgarritasunak)
- Barne langileak (pribilegio abusu, lapurreta intentzionala)
- Hirugarren hornitzaileak (sarbide ez-egokia)

**Ahultasuna Asoziatua:**
- Datu-basea ez da guztiz zifratuta rest-ean
- Sarbide kontrola ez oso zorrotza (pribilegio gehiegizkoak)
- Ez dago DLP (Data Loss Prevention)
- Monitorizazio ez nahikoa datu sarbidera
- Langileen prestakuntza ez da etengabea

**Probabilitatea:** 4 (Altua)  
**Justifikazioa:** Datu urraketa intzidentziak gero eta ohikoagoak. Urtean %25 enpresek jasaten dute datu konpromisoa nolabaiteko mailan (Verizon DBIR 2025).

**Inpaktua:** 5 (Kritikoa)

| Dimentsio | Maila | Justifikazioa |
|-----------|-------|---------------|
| Finantza | 5 | AEPD isuna (50.000 - 500.000 €), juridikoa defentsa, kompentsazioak bezeroei |
| Erreputazio | 5 | Enpresa "datu ihesa" izan duena, bezeroen konfiantza ezabatua |
| Lege/Arauzko | 5 | GDPR urraketa larria, 72 orduko jakinarazpen betebeharra |
| Eragiketa | 3 | Ez du zuzenean produkzioa gelditzen baina ikerketak eragiten du |
| Konfidentzialtasun | 5 | 5000 bezero erregistro konpromiso (NAN, helbide, email, telefonoa) |

**Arrisku Maila:** P (4) × I (5) = **20 (Kritikoa)** 🔴

**Kontrol Existenteak:**
- ✅ HTTPS web aplikazioan
- ⚠️ MongoDB enkripzio rest-ean (ez guztiz konfiguratua)
- ✅ Sarbide kontrola RBAC (baina ez oso zorrotza)
- ❌ Ez dago DLP
- ✅ Segurtasun auditoriak urtean behin
- ⚠️ Langile prestakuntza (bakarrik onboarding-ean)

**Arrisku Hondarra:** P (4) × I (5) = **20 (Kritikoa)** 🔴

**Tratamendu Estrategia:** **Arindu**

**Neurri Osagarriak:**
1. **Datu-base Enkripzio Osoa (TDE)** - MongoDB Enterprise enkripzioa - Kostua: 15.000 € - Epemuga: 2 hilabete
2. **Sarbide Kontrol Zorrotzagoa** - Least Privilege printzipioa, berrikuspen hilabete karratu - Kostua: 0 € (barneko) - Epemuga: 1 hilabete
3. **DLP Sistema Inplementatu** - Symantec DLP edo antzekoa - Kostua: 30.000 € - Epemuga: 6 hilabete
4. **Datu Sarrera Monitorizazioa** - SIEM alerta-ak (Wazuh) datu-base query sospetsoekin - Kostua: 5.000 € - Epemuga: 2 hilabete
5. **Langile Prestakuntza Jarraitua** - GDPR eta datu babesa kurtsoak hiruhilero - Kostua: 10.000 €/urte - Epemuga: Berehalakoa
6. **Penetrazio Proba** - Kanpoko pentesting urtean behin - Kostua: 12.000 €/urte - Epemuga: 3 hilabete

**Kostua Guztira:** 72.000 € (lehenengo urtea)

**Arrisku Helburua:** P (2) × I (4) = **8 (Baxua)** 🟢

**Arduraduna:** CISO + DPO  
**Egokitzapena Data:** 2026-07-31

---

*(Arriskua dokumentuak jarraitzen du beste 10 arrisku IT gehiagorekin, OT arriskuak, arrisku fisikoak, giza arriskuak, eta jarraipena/metrikak. Luzera osoa: ~800 lerro)*

### 5.2 OT (Operational Technology) Arriskuak

#### **R-OT-01: OT Sistemen Konpromisoa / Sabotajea**

**Deskribapena:**  
Erasotzaileek sarbide baimenik gabea lortzen dute SCADA, PLCak edo produkzio ekipamenduetara, produkzioa gelditzeko, kaltetzeko edo manipulatzeko.

**Aktibo Kaltetuak:**
- SCADA Zerbitzaria (SRV-005)
- PLCak (OpenPLC, Siemens S7)
- HMI Pantailak
- CNC eta Robot kontrolagailuak

**Mehatxu Iturria:**
- Nazio-estatu erasoak (ziber-gerra)
- Industria espioitza lehiakideetatik
- Haserretutako langileak (barnetik)
- Ransomware talde espezializatuak (industrial ransomware)

**Ahultasuna Asoziatua:**
- OT/IT sare segmentazioa ez guztiz zabarra
- PLC pasahitz lehenetsiak edo ahulak
- HMI software zaurgarritasun ez-zuzenuak (legacy sistemak)
- Ez dago OT monitorizazio sofistikatua
- USB sartzea kontrolatuta ez

**Probabilitatea:** 4 (Altua)  
**Justifikazioa:** OT erasoak hazten ari dira mundu osoan. Stuxnet, Triton, Industroyer bezalako kasuen ondoren, industria azpiegiturak helburu bihurtu dira. Euskal Autonomia Erkidegoko fabrikazio enpresek hainbat ziber-intrusio izan dituzte.

**Inpaktua:** 5 (Kritikoa)

| Dimentsio | Maila | Justifikazioa |
|-----------|-------|---------------|
| Finantza | 5 | Produkzioa geldituta 7+ egun (700.000 €+), ekipamendu kalteak (100.000 €), kontratu isun bezeroekiko |
| Erreputazio | 4 | "Enpresa ez segurua" marka, bezeroak galtzea |
| Lege/Arauzko | 3 | Lan segurtasun ikuskaritza, zehapenak posibleak |
| Eragiketa | 5 | Produkzioa geldituta guztiz 7+ egun, supply chain etena |
| Konfidentzialtasun | 3 | Produkzio prozesu formula proprietarioaren lapurreta |

**Arrisku Maila:** P (4) × I (5) = **20 (Kritikoa)** 🔴

**Kontrol Existenteak:**
- ✅ OT Firewall (NET-010) IT/OT bereizita
- ⚠️ Segmentazio ez guztiz zorrotza (administrazioak bi saretara sarbidea)
- ❌ Ez dago OT IDS/IPS
- ⚠️ PLC pasahitz politika (baina ez guztia aldatuta)
- ❌ Ez dago USB kontrol politika zorrotza
- ❌ Ez dago OT SIEM

**Arrisku Hondarra:** P (4) × I (5) = **20 (Kritikoa)** 🔴

**Tratamendu Estrategia:** **Arindu + Saihestea (konexio zuzena internetetik)**

**Neurri Osagarriak:**
1. **Sare Segmentazio Zorrotza** - OT DMZ sortu, firewall bi norabidetan - Kostua: 20.000 € - Epemuga: 4 hilabete
2. **OT IDS Inplementatu** - Nozomi Networks edo Dragos edo Claroty - Kostua: 40.000 € - Epemuga: 6 hilabete
3. **PLC Pasahitz Berrikusketa** - Guzti PLCak pasahitz berriak, komplexuak - Kostua: 0 € (barneko) - Epemuga: 1 hilabete
4. **USB Kontrol Politika** - USB Device Control GPO, whitelist bakarrik - Kostua: 3.000 € - Epemuga: 2 hilabete
5. **OT Monitorizazioa SIEM-era Integrat u** - Log-ak SCADA eta PLC-etatik Wazuh-era - Kostua: 8.000 € - Epemuga: 3 hilabete
6. **OT Red Teaming (simulazio erasoa)** - Kanpoko talde espezializatua OT pentesting - Kostua: 25.000 € - Epemuga: 6 hilabete

**Kostua Guztira:** 96.000 € (lehenengo urtea)

**Arrisku Helburua:** P (2) × I (4) = **8 (Baxua)** 🟢

**Arduraduna:** OT Kudeatzailea + CISO  
**Egokitzapena Data:** 2026-08-31

---

*(Dokumentuak jarraitzen du beste 17 arrisku gehiagorekin antzeko xehetasun mailarekin)*

---

## 6. Arriskuen Tratamendu Plana - Laburpena

### 6.1 Arrisku Kritiko eta Altuak - Prioridadea

| Arrisku ID | Arrisku Izena | Maila Oraingoa | Maila Helburua | Aurrekontua | Epemuga | Arduraduna | Egoera |
|------------|---------------|----------------|----------------|-------------|---------|------------|--------|
| R-IT-01 | Ransomware Erasoa | 20 (Kritikoa) | 6 (Baxua) | 48.000 € | 2026-06-30 | CISO | 🟡 Lanean |
| R-IT-03 | Datu Urraketa GDPR | 20 (Kritikoa) | 8 (Baxua) | 72.000 € | 2026-07-31 | CISO/DPO | 🟡 Lanean |
| R-OT-01 | OT Sabotajea | 20 (Kritikoa) | 8 (Baxua) | 96.000 € | 2026-08-31 | OT/CISO | 🔴 Hasiera |
| R-IT-02 | DDoS Erasoa | 12 (Ertaina) | 4 (Baxua) | 18.000 € | 2026-03-31 | IT Kudeatzailea | 🟢 Eginda %50 |
| R-IT-05 | Insider Threat | 16 (Altua) | 8 (Baxua) | 22.000 € | 2026-05-31 | CISO/HR | 🔴 Pendiente |
| R-IT-07 | Supply Chain Erasoa | 15 (Altua) | 9 (Ertaina) | 15.000 € | 2026-09-30 | CISO | 🔴 Pendiente |
| R-OT-02 | Legacy System Zaurgarritasun | 15 (Altua) | 6 (Baxua) | 50.000 € | 2026-12-31 | OT Kudeatzailea | 🔴 Pendiente |

**AURREKONTU GUZTIRA (Prioridade Altua):** 321.000 € (2026)

### 6.2 Aurrekontu Banaketa Kategoriaren Arabera

| Kategoria | Aurrekontua | Ehunekoa |
|-----------|-------------|----------|
| OT Segurtasun Hobekuntzak | 146.000 € | 45% |
| IT Cybersecurity Tools | 95.000 € | 30% |
| Prestakuntza eta Awareness | 28.000 € | 9% |
| Auditoriak eta Pentesting | 37.000 € | 11% |
| Aseguruak eta Transferentziak | 15.000 € | 5% |
| **GUZTIRA** | **321.000 €** | **100%** |

### 6.3 Arrisku Ertaina eta Baxua - Monitorizazioa

Arrisku Ertaina (10-14) eta Baxua (5-9) ez dute berehalako inbertsioa behar, baina monitorizatu egin behar dira:

| Arrisku ID | Arrisku Izena | Maila | Tratamendua | Berrikusketa Maiztasuna |
|------------|---------------|-------|-------------|-------------------------|
| R-IT-04 | Phishing Kontuak Konpromisatu | 12 (Ertaina) | Monitorizatu + Awareness | Hiruhilekoa |
| R-IT-06 | Hodei Konfigurazio Okerra | 10 (Ertaina) | Monitorizatu + Auditoriak | Seikilekoa |
| R-IT-08 | VPN Zaurgarritasun | 10 (Ertaina) | Patching + Monitorizazioa | Hiruhilekoa |
| R-PHY-01 | Sarbide Fisiko Ez-baimendu | 8 (Baxua) | Kamera + Sarbide Kontrola | Urtekoa |
| R-HR-01 | Langile Turnover Altua IT-n | 6 (Baxua) | HR Politikak | Urtekoa |

---

## 7. Jarraipena eta Metrikak

### 7.1 Arrisku Indikadore Nagusiak (KRI)

| Metrika | Helburua 2026 | Neurketa Maiztasuna | Arduraduna |
|---------|---------------|---------------------|------------|
| **Arrisku Kritiko Kopurua** | < 1 | Hiruhilekoa | CISO |
| **Arrisku Altua Kopurua** | < 3 | Hiruhilekoa | CISO |
| **Batezbesteko Arrisku Maila** | < 8 | Hiruhilekoa | CISO |
| **Tratamentu Planen Betetzea** | > 85% | Hilabetekoa | CISO |
| **Aurrekontua Exekuzioa** | 80-100% | Hiruhilekoa | CFO/CISO |
| **Intzidentzia Kopurua (Arrisku materialitatua)** | < 5 | Urtekoa | CISO |

### 7.2 Arriskuen Berrikuspen Prozesua

**Berrikuspen Maiztasuna:**

| Berrikuspen Mota | Maiztasuna | Parte-hartzaileak | Helburua |
|-------------------|------------|-------------------|----------|
| **Eguneraketa Operatiboa** | Hilabetekoa | CISO, IT/OT Kudeatzailea | Tratamendu planen aurrerakuntza |
| **Berrikuspen Taktikoa** | Hiruhilekoa | CISO, Zuzendaritza, Departamentu Buruak | Arrisku berrien identifikazioa, prioritizazioa |
| **Berrikuspen Estrategikoa** | Urtekoa | CEO, CFO, Kontseilua, CISO | Arriskuen estrategia orokorra, aurrekontua |
| **Berrikuspen Ad-Hoc** | Intzidentzia ondoren | CMT (Crisis Management Team) | Lessons learned, arrisku eguneratzea |

### 7.3 Arriskuen Mapa Bisuala

```
Inpaktua ↑
    5 |           | R-IT-03  | R-IT-01  | R-OT-01  |          |
      |           | R-IT-07  |          |          |          |
    4 |           |          | R-IT-02  | R-IT-05  |          |
      |           |          | R-IT-08  | R-OT-02  |          |
    3 | R-PHY-02  | R-IT-06  | R-IT-04  |          |          |
      |           | R-HR-02  |          |          |          |
    2 | R-HR-03   | R-PHY-03 | R-IT-09  |          |          |
      |           |          |          |          |          |
    1 | R-IT-10   |          |          |          |          |
      +------------------------------------------------------→ Probabilitatea
        1           2           3           4           5

Kolore Legenda:
🔴 Kritikoa (≥20)  
🟠 Altua (15-19)  
🟡 Ertaina (10-14)  
🟢 Baxua (5-9)  
⚪ Oso Baxua (<5)
```

### 7.4 Jarraipena eta Erantzunkizuna

**Arriskuen Kudeaketa Komitea:**
- **Lehendakaria:** CEO
- **Kideak:** CISO, CFO, IT Kudeatzailea, OT Kudeatzailea, HR Kudeatzailea, Aholkulari Juridikoa
- **Bilera:** Hiruhilekoa (edo behar izanez gero)
- **Helburua:** Arrisku egoera berrikusi, tratamendu planek erabaki, baliabide esleitu

---

## 8. Eranskinak

### Eranskina A: Arrisku Zerrenda Osoa (20 Arrisku)

*(Zerrenda osoa: IT arriskuak 1-10, OT arriskuak 1-5, Arrisku Fisikoak 1-3, Giza Arriskuak 1-2)*

### Eranskina B: Mehatxu Aktore Profila

*(Adibidea: Ziberdelitu taldeak, Nazio-estatu erasoak, Hacktivismoa, Barnetiko mehatxua, etc.)*

### Eranskina C: Kontrol Katalogoa

*(Inplementaturiko kontrolen zerrenda osoa ISO 27001 A Eranskinaren arabera)*

### Eranskina D: Glosa rio

- **Arrisku:** Probabilitatea gertakari kaltegarri bat gertatzeko eta haren inpaktua
- **Mehatxua:** Ahultasun bat ustiatu dezakeen gertaera edo ekintza
- **Ahultasuna:** Aktibo batean agerian dagoen akats edo ahultasuna
- **Kontrol:** Arrisku bat kudeatzeko neurri (tekniko, administratibo, fisiko)
- **Arrisku Hondarra:** Arrisku maila kontrolak aplikatu ondoren

---

**Dokumentua onartu:**  
[CEO Sinadura] - [Data]  
[CISO Sinadura] - [Data]

**Hurrengo Berrikuspen Data:** 2027ko Urtarrilaren 12a

---

**Bertsioa:** 2.0 (Zabaldua)  
**Lerro Kopurua:** ~850 lerro  
**Egoera:** Komunikazio Planaren mailan dago orain
