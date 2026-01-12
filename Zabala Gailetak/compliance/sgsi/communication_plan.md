# Komunikazio Plana - Segurtasun Gorabeherak - Zabala Gailetak

## 1. Helburua

Segurtasun intzidentzia baten aurrean barneko eta kanpoko komunikazio eraginkorra bermatzea.

## 2. Barne Komunikazioa

### 2.1. Jakinarazpen Protokoloa: Nork eta Nola

#### 2.1.1. Hasierako Detekzioa eta Jakinarazpena

**Detektatzailea** (edozein langile edo sistema automatizatua):
1. **Lehentasunezko bideratze kanal reserbatua:** helpdesk@zabalagailetak.eus edo **segurtasuna@zabalagailetak.eus**
2. **Telefono larrialdietarako:** +34 XXX XXX XXX (24/7 zaintza)
3. **Txat sistema barnekoa:** Slack #segurtasun-alertak kanalean jakinarazi

**Lehen erantzulea** (Helpdesk edo IT zaintzailea):
- Intzidentziaren hasierako sailkapena egiten du (Larritasun Matrizearen arabera: Baxua, Ertaina, Altua, Kritikoa)
- **15 minutu barruan** IT Arduradunari eta CISO-ri jakinarazi behar du
- Intzidentziaren erregistro txartela irekitzen du (Ticket ID sortu)

#### 2.1.2. Eskalada Prozesua Kudeaketa Mailaren Arabera

**MAILA 1 - Larritasun Baxua/Ertaina:**
- **Jakinarazpena:** IT Arduraduna + CISO
- **Erantzun epea:** 2 ordu
- **Erabakiak:** Segurtasun taldeak hartzen ditu
- **Eguneratze maiztasuna:** 8 orduro

**MAILA 2 - Larritasun Altua:**
- **Jakinarazpena:** CISO → Zuzendari Nagusia (CEO) + DPO + Aholkularitza Legala
- **Erantzun epea:** 30 minutu
- **Erabakiak:** Krisiaren Kudeaketa Taldea (CMT) aktibatzen da
- **Eguneratze maiztasuna:** 4 orduro
- **Komunikazio formatua:** Laburpen exekutiboa email + deialdia CMT bilerara

**MAILA 3 - Larritasun Kritikoa:**
- **Jakinarazpena:** CMT osoa + Kontseiluko kideak + Prentsa arduraduna
- **Erantzun epea:** Berehalakoa (15 minutu)
- **Erabakiak:** CMT osoak berehala biltzen da (fisikoki edo bideo deiaz)
- **Eguneratze maiztasuna:** Orduro
- **Komunikazio formatua:** Krisi txostena + Bilera jarraitua + War Room aktibatua

### 2.2. Erabakien eta Eguneratze Protokoloa

#### 2.2.1. Erabakien Hartzea

**Erabaki Organoa Larritasun Mailaren Arabera:**

| Larritasuna | Erabaki Organoa | Parte-hartzaileak | Erabaki Denbora |
|-------------|----------------|-------------------|------------------|
| Baxua | IT Arduraduna | Teknikariak | 2 ordu |
| Ertaina | CISO + IT | Segurtasun taldea | 1 ordu |
| Altua | CMT | CISO, CEO, DPO, Legala | 30 minutu |
| Kritikoa | CMT Osoa | Guzti goi-mailakoak | Berehalakoa |

#### 2.2.2. Eguneratzeen Partekatzea

**Komunikazio Tresnak:**
1. **Incidentziaren Kudeaketa Sistema (IMS):** Ticket-ak eta egoera eguneratzeak
2. **Slack/Teams Kanal Pribatua:** #incident-[ID] kanala sortzen da intzidentzia bakoitzeko
3. **Posta Elektronikoa:** Eguneratze formalak goi-mailako arduradunentzat
4. **War Room (krisi egoeretan):** Espazio fisiko/birtuala erabaki bizkorretarako

**Eguneratzeen Edukia (Estandarizatua):**
- **Intzidentzia ID eta Izenburua**
- **Egoera Oraingoa:** Ebazpenean, Gelditu, Konponduta, Itxita
- **Eragina:** Zer zerbitzu/datu/sistema kaltetuta
- **Ekintza Hartuak:** Azken eguneratzetik hona
- **Hurrengo Urratsak:** Planifikatutako ekintzak
- **Estimatutako Konponketa Denbora (ECD)**
- **Arriskuak eta Oztopoak**

**Eguneratzeen Template-a (Laburpena):**
```
INTZIDENTZIA ID: INC-2026-001
DATA/ORDUA: 2026-01-12 14:30
EGOERA: Ebazpenean (60% konponduta)
ERIAGINA: E-commerce webgunea mantentze lanetan (5000 erabiltzailek kaltetuta)
AZKEN EKINTZAK: 
- Trafikoa backup zerbitzarira bideratu
- Datu baseko ordezkoa kargatu
HURRENGO URRATSAK:
- Kaltetu gabeko sistema berriro aktibatu (1 ordu)
- Monitorizazio bikoiztua ezarri
ECD: 16:00
```

### 2.3. Kontaktu Zerrenda (Rolak eta Ardurak)

#### 2.3.1. Krisiaren Kudeaketa Taldea (CMT)

| Rola | Izena | Telefonoa | Email | Ardura |
|------|-------|-----------|-------|--------|
| **Krisi Koordinatzailea (CISO)** | [Izena] | +34 XXX XXX XXX | ciso@zabalagailetak.eus | Erabaki teknikoak, erantzun plana |
| **Erabaki Exekutiboa (CEO)** | [Izena] | +34 XXX XXX XXX | ceo@zabalagailetak.eus | Negozio erabakiak, baliabideak |
| **Datuen Babesa (DPO)** | [Izena] | +34 XXX XXX XXX | dpo@zabalagailetak.eus | GDPR konpromisoa, jakinarazpenak |
| **Aholkularitza Legala** | [Izena] | +34 XXX XXX XXX | legal@zabalagailetak.eus | Lege ondorioak, agintariak |
| **Komunikazio Bozeramailea** | [Izena] | +34 XXX XXX XXX | press@zabalagailetak.eus | Prentsa, kanpo mezularitza |
| **IT Arduraduna** | [Izena] | +34 XXX XXX XXX | it@zabalagailetak.eus | Ekintza teknikoak, konponketa |

#### 2.3.2. Erantzun Talde Teknikoa

| Rola | Kontaktua | Lehen Erantzuna |
|------|-----------|------------------|
| Sare Administratzailea | netadmin@zabalagailetak.eus | Firewall, Trafikoa |
| Sistema Administratzailea | sysadmin@zabalagailetak.eus | Zerbitzariak, VM-ak |
| Datu Baseko Arduraduna | dba@zabalagailetak.eus | MongoDB, Redis |
| Garatzaile Senior | dev-lead@zabalagailetak.eus | Aplikazioen kodea |
| Segurtasun Analistak | soc@zabalagailetak.eus | Forentsikoa, Analisia |

#### 2.3.3. Ordezkapen Katena (Eskuraezintasuna)

Rol bakoitzak ordezko bat izan behar du:
- **CISO ordezko:** IT Arduraduna
- **CEO ordezko:** CFO edo COO
- **DPO ordezko:** Aholkularitza Legala

## 3. Kanpo Komunikazioa

### 3.1. Jakinarazpen Protokoloa: Bezeroei

#### 3.1.1. Jakinarazpen Denbora Mugak

| Egoera | Jakinarazpen Epea | Bidea | Edukia |
|--------|-------------------|-------|--------|
| **Zerbitzu Etena (Handia)** | 30 minutu | Email + Web Banner + SMS | Zerbitzua ez dago erabilgarri, konponketa denbora |
| **Zerbitzu Etena (Txikia)** | 2 ordu | Email + Web Banner | Arazoak detektatu dira, lanean |
| **Datu Urraketa (Suspektua)** | 24 ordu | Email Pertsonalizatua | Aurreikuspena, kontu segurtasun gomendioak |
| **Datu Urraketa (Baieztatua)** | 72 ordu (GDPR)* | Email Legal + Gutuna Formalki | Urraketa xehetasunak, erabiltzaile bakoitzarentzako eragina |
| **Konponduta** | Berehalakoa | Email + Web Mezu Positiboa | Zerbitzua normaltasunean, esker onak |

*GDPR-ren arabera, 72 orduko gehienezko epea agintariei; bezeroei ahalik eta azkarren.

#### 3.1.2. Mezularitza Estandarizatua Bezeroei

**ZER EMAN:**
- ✅ Intzidentziaren deskribapen orokorra (ez teknikoa)
- ✅ Zer zerbitzuk kaltetuta
- ✅ Estimatutako konponketa denbora
- ✅ Erabiltzaileek har ditzaketen segurtasun neurriak
- ✅ Kontaktu informazioa laguntzarako
- ✅ Zer egiten ari garen konpontzeko

**ZER EZ EMAN:**
- ❌ Xehetasun teknikoak (zaurgarritasun espezifikoak)
- ❌ Zergatiak ezagutzen ez baditugu oraindik
- ❌ Errudun edo arduradunei buruzko aipamenak
- ❌ Sistema barneko konfigurazioak edo segurtasun neurriak
- ❌ Beste bezero edo datuen xehetasun pribatuak
- ❌ Kalteak minimizatzeko saiakerak ("ez da larria" - eztabaidagarria)

**Eguneratze Maiztasuna:**
- **Krisi egoera aktiboa:** 4-6 orduro
- **Ebazpen prozesua:** Egunero
- **Post-mortem:** 7 egun ostean, txosten osoagoa

### 3.2. Jakinarazpen Protokoloa: Hornitzaileak

#### 3.2.1. Jakinarazpen Kasuak

**Hornitzaileak jakinarazi behar zaizkie kasu hauetan:**
1. Haien zerbitzua intzidentziaren iturria den ala
2. Haien sistemak ere kaltetuta egon daitezkeela susmatzen da
3. Lankidetza teknikoa behar da intzidentzia ebazteko
4. Kontratu klausulek jakinaraztea eskatzen badute

#### 3.2.2. Komunikazio Formatua

**Mota:** Email formala edo telefono deia + email berrespena
**Hartzaileak:** Hornitzailearen segurtasun kontaktua (SOC/CSIRT)
**Edukia:**
- Enpresaren identifikazioa
- Intzidentziaren laburpena (haien zerbitzuaren partaidetzarekin)
- Beharrezko laguntza edo informazioa
- Konfidentzialtasun eskaera

**Denbora muga:** 24 ordu hornitzailearen partaidetza susmatzen denetik

### 3.3. Jakinarazpen Protokoloa: Araudi-Agintariak

#### 3.3.1. Agintari Zerrenda eta Kasu Motak

| Agintaria | Noiz Jakinarazi | Epea | Bidea | Arduraduna |
|-----------|-----------------|------|-------|------------|
| **AEPD / Datuak Babesteko Euskal Bulegoa** | Datu pertsonalen urraketa (arriskua pertsonentzat) | **72 ordu** | Formulariu elektronikoa [web plataformaren bidez] | DPO |
| **Zibersegurtasun Euskal Zentroa (BCSC)** | Zibersegurtasun intzidentzia larriak (azpiegitura kritikoa, eraso koordinatuak) | Ahalik eta azkarren | Email: incident@bcsc.eus + Telefono hotline | CISO |
| **INCIBE-CERT** | Zibersegurtasun intzidentzia nazioak mailan | Gomendatua, ez nahitaezkoa | https://www.incibe.es/incibe-cert | CISO |
| **Ertzaintza / Polizia Nazionala** | Ziberdelituak (hacking-a, sabotajea, datu lapurreta penala) | Berehalakoa delitua susmatzen denean | Salaketa ofizial fiska | CEO + Aholkularitza Legala |
| **Euskal Autonomia Erkidegoko Jarduera Ikuskaritza** | OT sistemetan eragina baldin badago (industria segurtasuna) | 24 ordu | Telefono: XXX XXX XXX | CEO |

#### 3.3.2. AEPD-rako Jakinarazpen Prozedura (GDPR)

**1. Hasierako Jakinarazpena (72 ordu):**
- DPO-k aurrez betetako formularioa betetzen du: https://www.aepd.es
- **Gutxieneko Edukia:**
  - Urraketa mota eta egoera
  - Datu kategoriak eta erabiltzaile kopurua kaltetuta
  - DPO-ren kontaktua
  - Ondorio posibleak eta hartutako neurriak
  - Erabiltzaileak jakinarazteko asmoa

**2. Eguneratze Txostena:**
- Informazio gehiago eskuragarri denean, eguneratu

**3. Erabiltzaileei Jakinaraztea:**
- Arriskua "handia" bada pertsonentzat, zuzenean jakinarazi behar zaie (email/gutuna)

#### 3.3.3. Delituaren Salaketa (Polizia/Ertzaintza)

**Prozedura:**
1. **Erabakia:** CEO + Aholkularitza Legalak erabakitzen dute salaketa jartzea
2. **Frogak Prestatu:** Forentsiko taldeak kopia bat prestatzen du (ISO kopia, log-ak, pantaila-argazkiak)
3. **Salaketa Jartzea:** Legelariak lagunduta, Ertzaintzan edo Polizian
4. **Jarraipena:** Salaketa zenbakia gorde eta agintariekin koordinatu

### 3.4. Prentsa eta Komunikabide Publikoak

#### 3.4.1. Noiz Prentsarekin Komunikatu

**Kasu hauetan bakarrik:**
- Intzidentzia publikoki ezaguna denean (sare sozialetan, albisteak)
- Bezero andana bat kaltetuta dago eta erreputazioa kudeatu behar da
- Agintariek edo lege betekizunek eskatzen dutenean
- Garapen proaktiboa egin nahi badugu ("gure segurtasuna azpimarratzeko")

#### 3.4.2. Prentsa Oharra Prozedura

**Arduraduna:** Komunikazio Bozeramailea (CMT-k onartuta)

**Pausuak:**
1. CMT-k prentsa oharraren edukia onartzen du
2. Aholkularitza legalak berrikusten du
3. CEO-k azken onarpena ematen du
4. Bozeramaileak publikatzen du enpresaren webgunean + email hedapenetan

**Prentsa Oharraren Egitura:**
- Izenburua: Laburra eta argia
- Sarrera: Zer gertatu da (faktikoa)
- Gorputza: Hartutako neurriak, egoera oraingoa
- Itxiera: Konpromisoa bezeroekiko, kontaktu informazioa
- Oharrak: Informazio teknikoa gehiago nahi izanez gero (DPO/CISO-ren kontaktua prentsakoei)

**Ez onartuak:**
- Langileek banakako deklarazioak egitea prentsara (bakarrik Bozeramaileak)
- Sare sozialetan ez-ofizialki argitaratzeak

## 4. Mezularitza Ereduak (Templates)

### 4.1. Barne Komunikazio Template-ak

#### 4.1.1. Email Eguneratzea (Intzidentzia Aktiboa)

**Gaia:** [URGENT] Intzidentzia INC-[ID] - Eguneratzea #[zenbakia]

```
Langile Maiteok,

Intzidentzia INC-[ID]-ri buruzko eguneratze berria partekatu nahi dizuegu:

📌 EGOERA ORAINGOA: [Ebazpenean / Gelditu / Konponduta]
📅 DATA/ORDUA: [UUUU-HH-EE HH:MM]
⚠️ ERAGINA: [Zer sistema/zerbitzu kaltetuta]

🔧 AZKEN EKINTZAK:
- [Ekintza 1]
- [Ekintza 2]

⏭️ HURRENGO URRATSAK:
- [Plana 1]
- [Plana 2]

⏱️ ESTIMATUTAKO KONPONKETA: [HH:MM edo "oraindik ezezaguna"]

📋 ZER EGIN LANGILEOK:
- [Gomendio espezifikoak, adib: "ez erabili email-a", "VPN konexioa kontrolatu"]

❓ ZALANTZAK: Jarri harremanetan: incident-response@zabalagailetak.eus

Eskerrik asko zuen lankidetzagatik.

Segurtasun Taldea
Zabala Gailetak
```

#### 4.1.2. Telefono Alerta Script-a (Krisi Kritikoa)

```
Kaixo [Izena], [Zure Izena] naiz Zabala Gailetak-etik.

Segurtasun intzidentzia kritiko bat detektatu dugu eta zure parte-hartzea behar dugu.

- Intzidentzia: [Laburpen bat esalditan]
- Larritasuna: KRITIKOA
- Eragina: [Sistema/Zerbitzu kaltetuta]
- Bilera: [Ordu] -tan, [Tokia: War Room / Zoom link]

Bai al duzu galderapik?

[Helburu berehalakoa eman, adib: "konponketa plana prestatu 30 minutuan"]

Eskerrik asko.
```

#### 4.1.3. Slack/Teams Ohar Azkarra

```
🚨 INTZIDENTZIA ALERTA 🚨

ID: INC-2026-XXX
Larritasuna: [KRITIKOA/ALTUA/ERTAINA]
Sistema: [Izena]
Egoera: [Ikerpenean/Ebazpenean]

Taldeak mugitzea → #incident-2026-XXX kanalera

CMT Bilera: [Ordua]

cc: @ciso @it-lead @ceo
```

### 4.2. Kanpo Komunikazio Template-ak: Bezeroei

#### 4.2.1. Email Template-a: Zerbitzu Etena (Hasiera)

**Gaia:** [Zabala Gailetak] Mantendu lanetan - Eragina zure zerbitzuan

```
Bezeroa Maite,

Jakinarazi nahi dizugu gaur, [Data], [HH:MM]-etan arazo tekniko bat detektatu dugula gure [zerbitzu izena] -en.

🔴 ZER GERTATZEN ARI DA:
[Deskribapen sinple eta argia, teknikoa ez]

🛠️ ZER EGITEN ARI GARA:
Gure talde teknikoa ahalegintzen ari da arazoa ahalik eta azkarren konpontzen. [Ekintza espezifikoak eman, adib: "zerbitzariak berrezarri", "datu basea leheneratu"].

⏱️ ESTIMATUTAKO KONPONKETA:
[Denbora aproximatua edo "ahalik eta azkarren eguneratuko dizugu"].

✉️ EGUNERATZEAK:
Egoeraren berri emango dizugu [maiztasuna, adib: "4 orduro"].

📞 LAGUNTZA:
Zalantzak badituzu, jarri harremanetan: support@zabalagailetak.eus edo +34 XXX XXX XXX

Barkatu eragozpenak. Zure pazientzia eskertzen dugu.

Zabala Gailetak Taldea
```

#### 4.2.2. Email Template-a: Datu Urraketa (GDPR Jakinarazpena)

**Gaia:** [GARRANTZITSUA] Zure datuak - Segurtasun jakinarazpena

```
Bezeroa Estimatua,

Idazten dizugu gure sistemetako segurtasun intzidentziaren baten inguruan informatzeko, zeinek zure datu pertsonalak kaltetuta jarri ditzakeen.

🔴 ZER GERTATU DA:
[UUUU-HH-EE] -n, detektatu genuen [deskribapen orokorra, adib: "sarbide ez-baimendua gure datu basera"].

📋 ZER DATU KALTETUTA:
- [Datu motak, adib: "Izena, Emaila, Telefonoa"]
- Zenbat erabiltzaile: [Kopurua edo "zuk barne"]
- Ez ziren kaltetuta: [Datu sentikorragoak babestu badira, adib: "kreditu txartelen zenbakiak"]

🛡️ HARTUTAKO NEURRIAK:
- Sistemak segurtatu, zaurgarritasuna konpondu
- Pasahitz aldaketa derrigortua [beharrezkoa bada]
- Datuak Babesteko Agintariari jakinarazi dugu

✅ ZER EGIN ZURE:
1. [Gomendio espezifikoak, adib: "Aldatu pasahitza web gune honetan"]
2. [Kontuz egon phishing emailekin]
3. [Monitorizatu zure banku kontuak]

📞 INFORMAZIO GEHIAGO:
Jarri harremanetan gure Datuak Babesteko Arduradunarengian (DPO):
- Email: dpo@zabalagailetak.eus
- Telefonoa: +34 XXX XXX XXX

Barkatu egoera hau. Zure pribazitatea da gure lehentasuna eta neurri guztiak hartzen ari gara hau berriro gerta ez dadin.

Zure eskura,
[Izena]
[Kargua]
Zabala Gailetak
```

#### 4.2.3. Web Banner Mezua (Zerbitzu Etena)

```html
⚠️ Oharrik Garrantzitsua: Mantendu lanak gauzatzen ari dira. Zerbitzu batzuk mugatuak izan daitezke. Eguneratze gehiago: [Link status page]
```

### 4.3. Kanpo Komunikazio Template-ak: Agintariei

#### 4.3.1. AEPD-rako Jakinarazpena (GDPR 72h)

**Formatua:** AEPD plataformaren formularioaren bidez

**Edukia (laburpena):**

```
1. URRAKETA DESKRIBAPENA:
   - Mota: [Konfidentzialtasun urraketa / Sarbide ez-baimendua / Galera / Suntsitzea]
   - Data detektatua: [UUUU-HH-EE]
   - Data gertatu zen (susmatua): [UUUU-HH-EE]

2. DATU KATEGORIAK:
   - Identifikazio datuak (Izen-abizenak, NAN, helbidea)
   - Kontaktu datuak (Email, telefonoa)
   - [Beste kategoriak]
   - Kopuru aproximatua: [Zenbakia] erabiltzaile

3. ERAGIN ONDORIOAK:
   - [Arriskuak deskribatu erabiltzaileentzat, adib: identitate lapurreta, spam-a]

4. HARTUTAKO NEURRIAK:
   - [Lehendabiziko erantzuna, adib: sistemak isolatu, pasahitzak aldatu]
   - [Erabiltzaileei jakinarazteko plana]

5. DPO KONTAKTUA:
   - Izena: [DPO Izena]
   - Email: dpo@zabalagailetak.eus
   - Telefonoa: +34 XXX XXX XXX
```

#### 4.3.2. Ertzaintzarako Salaketa Script-a

```
Salaketa Informala / Aurretiazko Deia:

"Kaixo, Zabala Gailetak enpresatik deitzen dut. Zibersegurtasun delitu bat salatu nahi dugu.

Laburpena:
- Enpresa: Zabala Gailetak S.L., [Helbidea]
- Gertaera: [Adib: Sistema informatikoetara sarbide ez-baimendua]
- Data: [UUUU-HH-EE]
- Kalte estimatua: [Zenbatekoa edo "zehazteko"]
- Froga materialak: Bai, gure forentsiko taldeak prestatu ditu

Nola jarraitu behar dugu salaketa formalki jartzeko?"

[Jarraian, arauetarako jarri formalki Ertzaintza bulego edo Polizia nazionalean]
```

### 4.4. Prentsa Oharraren Template-a

```markdown
# PRENTSA OHARRA

## [Izenburua: Laburra eta Faktikoa]

**Zabala Gailetak, [Data]**

[Sarrera Paragrafoa: 2-3 esaldi, zer gertatu da, noiz]

Zabala Gailetak-ek jakinarazi du [UUUU-HH-EE] -n [intzidentzia mota] bat detektatu duela bere [sistema/zerbitzu] -n. Intzidentziak [eragin deskribapena labur].

**Hartutako Neurriak:**

[Konpromiso erakustea, ekintza zerrenda]

- Gure segurtasun taldea berehalakoa erantzun du
- [Ekintza espezifikoak]
- Agintari eskudunekin koordinatuta lan egiten ari gara

**Egoera Oraingoa:**

[Zer egoeratan gaude, zerbitzua berreskuratuta ala oraindik lanean]

**Bezeroei mezua:**

[Laburpena zer egin behar duten bezeroek, nora joan informazio gehiagorako]

**Kontaktua informazio gehiagorako:**

- Media: press@zabalagailetak.eus
- Bezeroak: support@zabalagailetak.eus / +34 XXX XXX XXX

---

*Zabala Gailetak-en buruz: [Enpresa deskribapena labur]*
```

### 4.5. Bilera Berezien Protokoloa

#### 4.5.1. CMT Bilera (Krisi Kudeaketa Taldea)

**Noiz deitu:** Intzidentzia "Altua" edo "Kritikoa" denean

**Deia nola:** 
- Email + Telefono deia guzti kideei
- Slack #crisis-management kanalean ping @all

**Agenda (30 minutuko bilera):**

```
1. [5 min] Egoera azalpena (CISO)
   - Zer gertatu da
   - Zer eragin du
   - Zer arriskuak daude

2. [10 min] Ekintza plana (IT Lead)
   - Ekintza teknikoak hartuta
   - Hurrengo urratsak
   - Baliabide beharrak

3. [5 min] Lege eta Konformidade ikuspegia (DPO + Legala)
   - Jakinarazpen betebeharrak
   - Legezko ondorioak

4. [5 min] Komunikazio estrategia (Bozeramailea)
   - Bezeroei zer esango zaie, noiz
   - Prentsa estrategia (behar izanez gero)

5. [5 min] Erabakiak eta Jarraipena (CEO)
   - Onartutako ekintzak
   - Baliabide esleipenak
   - Hurrengo bilera (noiz)

📝 Bilera guztiak grabatzen dira (audio) eta akta idatziak sortzen dira.
```

#### 4.5.2. Bilera Oharren Template-a

```
INTZIDENTZIA KUDEAKETA BILERA - AKTA

Data: [UUUU-HH-EE]
Ordua: [HH:MM - HH:MM]
Intzidentzia ID: INC-[ID]

PARTE-HARTZAILEAK:
- [Rola]: [Izena]
- [Rola]: [Izena]

AGENDA PUNTUAK:

1. EGOERA EGUNERATZEA:
   [Laburpena]

2. EKINTZA HARTUAK:
   - [Ekintza 1] - [Arduraduna]
   - [Ekintza 2] - [Arduraduna]

3. ERABAKIAK:
   ✅ [Erabakia 1] - Onartuta [Arduradunaren izena]
   ✅ [Erabakia 2] - Onartuta [Arduradunaren izena]
   ⏸️ [Erabakia 3] - Atzeratuta, [Arrazoia]

4. EKINTZA ELEMENTUAK (Action Items):
   - [Ataza 1] - [Arduraduna] - [Epemuga]
   - [Ataza 2] - [Arduraduna] - [Epemuga]

5. HURRENGO BILERA:
   Data: [UUUU-HH-EE HH:MM]

Akta onartua: [Arduradunaren sinadura]
```

## 5. Krisi Komunikazio Komitea eta Bozeramailea

### 5.1. Krisi Komunikazio Komitearen Egitura

**Osaera:**

| Rola | Arduraduna | Funtzioa |
|------|------------|----------|
| **Komite Lehendakaria** | CEO | Erabaki estrategiko guziak onartzea |
| **Krisi Koordinatzailea** | CISO | Intzidentziaren kudeaketa teknikoa |
| **Komunikazio Zuzendaria / Bozeramailea** | Marketing/PR Director | Kanpo mezularitza guztiak kudeatu |
| **Lege Aholkularia** | Aholkularitza Legala | Lege konformitatea, jakinarazpenak |
| **DPO (Datuen Babesa)** | DPO | GDPR betebeharrak, datu babeseko kudeaketa |
| **HR Ordezkaria** | Giza Baliabideak | Langileen komunikazioa eta ongizatea |

### 5.2. Bozeramailearen Rola eta Ardurak

**Identifikazioa:**
- Izena: [Bozeramaile Izena]
- Kargua: [Adib: Komunikazio Zuzendaria]
- Kontaktua: press@zabalagailetak.eus / +34 XXX XXX XXX

**Ardura Nagusiak:**
1. **Mezularitza Bateratua:** Kanpo mezu guztiak kontrolatu (bakar bat hitz-emailea)
2. **Prentsa Kudeaketa:** Komunikabideen eskaerak erantzun
3. **Sare Sozialak:** Enpresaren sare sozialetan komunikatu (Twitter, LinkedIn, etc.)
4. **Barruan Koordinazio:** CMT-rekin lan egin mezularitza prestatzeko
5. **Entrenamendu:** Gainerako langileei prestatu "ez-komentatu" politika jarraitzeko

**Ez da bere ardura:**
- Erabaki teknikoak hartzea (CISO-ren ardura)
- Lege interpretazioa (Aholkularitza Legalaren ardura)

### 5.3. Bozeramailearen Entrenamendu Gakoak

**Komunikazio Printzipioak:**
1. **Honestasuna:** Ez gezurretan, baina ez ere gehiegi argitaratu
2. **Garbitasuna:** Hizkuntza teknikotik ihes egin, hizkuntza argi eta orokorra erabili
3. **Enpatia:** Erakutsi kezka bezero eta kaltedunekiko
4. **Kontrola:** Ez utzi emozioak agerian; mantenu tonua profesionala
5. **"Ez dakit" onartua:** Hobea da onartzea informazio bat ez dakigula, ez asmatu

**Frasa Gomendatuak:**
- ✅ "Gure lehentasuna da bezeroaren segurtasuna bermatzea."
- ✅ "Dagoeneko neurriak hartu ditugu."
- ✅ "Agintariekin lankidetzan lanean ari gara."
- ✅ "Informazio gehiago jakin ahala, eguneratuko dugu."
- ❌ "Ez da gure errua." (Salatzailea)
- ❌ "Larregi mintzatu da hau." (Minimizatzea)

## 6. Audit eta Jarraipena

### 6.1. Mezularitza Erregistratzea

#### 6.1.1. Erregistro Sistema

**Zer erregistratu:**
- ✅ Komunikazio elektroniko guztiak (emailak, Slack, Teams)
- ✅ Telefono deien laburpenak (data, ordua, parte-hartzaileak, gaiak)
- ✅ Bilera aktak (CMT bilerak, erabakiak)
- ✅ Kanpo jakinarazpen guztiak (bezeroei, agintariei, prentsa)
- ✅ Eguneratze denbora-lerroak (timeline)

**Non gordetzea:**
- **Intzidentziaren Kudeaketa Sistema (IMS):** Ticket barruan dokumentu guztiak erantsi
- **SharePoint / Dokumentu Kudeatzailea:** Karpeta pribatua `Incidents/INC-[ID]/Communications/`
- **SIEM (Wazuh):** Log-ak automatikoki ingestatu komunikazio sistemetatik

**Erretentzioa:**
- Gutxienez **3 urte** GDPR-ren arabera
- **7 urte** lege kasu potentzialekin

#### 6.1.2. Erregistro Template-a

```
KOMUNIKAZIO ERREGISTROA

Intzidentzia ID: INC-[ID]
Data/Ordua: [UUUU-HH-EE HH:MM:SS]
Mota: [Barne / Kanpo]
Bidea: [Email / Telefonoa / Bilera / Prentsa]

Igorlea: [Izena / Rola]
Hartzailea(k): [Izenak / Rolak / "Publiko orokorra"]

Gaia / Helburua: [Laburpena]
Edukia: [Mezuaren testua edo dokumentuaren esteka]

Onartzaileak (behar izanez gero): [CEO / CISO / CMT]

Jarraipena beharrezkoa: [Bai / Ez]
Jarraipena egina: [UUUU-HH-EE] - [Nork]
```

### 6.2. Erantzunaren Eraginkortasuna Ebaluatzea

#### 6.2.1. Metrikak (KPIs)

**Erantzun Denborak:**

| Metrika | Helburua | Neurtzeko Modua |
|---------|----------|------------------|
| **Lehen jakinarazpen denbora** | < 15 minutu | Detekziotik IT/CISO-ra |
| **Barne eguneratzeen maiztasuna** | 4-8h | Eguneratze kopurua / Intzidentzia iraupena |
| **Bezeroei lehen mezu denbora** | < 30 min (kritikoetan) | Intzidentziotik lehen email-a bezeroei |
| **AEPD jakinarazpen betetzea** | < 72 ordu | Data detekziotik jakinarazpena arte |
| **Konponketa komunikazio denbora** | < 2 ordu konponketa ostean | Konpontzea-tik "Konponduta" mezua arte |

**Kalitate Metrikak:**

| Metrika | Helburua | Neurketa |
|---------|----------|----------|
| **Mezu eduki zehaztasuna** | > 90% | Inkesta bezeroei: informazioa argi izan da? |
| **Langile ohar-hartzea** | > 95% | Konfirmazio emailen ehunekoa |
| **Prentsa mezu bat-etortzea** | 100% | Mezu desberdinak prentsak jaso (desiragarria: 0) |

#### 6.2.2. Post-Incident Review (PIR) - Komunikazio Atala

**Noiz egin:** 7-14 egun intzidentzia itxi ondoren

**Parte-hartzaileak:** CMT + Komunikazio talde osoa

**Galderak Ebaluatzeko:**

1. **Jakinarazpen Protokoloa:**
   - Jakinarazpen kateen azkarrak izan ziren?
   - Pertsona guztiak eskuragarri izan ziren?
   - Eskalada protokoloa behar bezala funtzionatu zuen?

2. **Mezu Kalitatea:**
   - Mezuak argi eta ulergarriak izan ziren?
   - Kontraesanenak edo nahaspilakakoak eraman ziren?
   - Mezuak garaiean bidali ziren?

3. **Kanal Eraginkortasuna:**
   - Email, Slack, telefono... zein funtzionatu zuen hobeto?
   - Zerbitzu etenetan, komunikazio sistemak funtzionatu zuten?

4. **Kanpo Harremanak:**
   - Bezeroek positiboki hartu zuten komunikazioa?
   - Prentsak zuzentasunez informatu zuen?
   - Agintariekin komunikazioa zilegia izan zen?

### 6.3. Feedback Jasotzea

#### 6.3.1. Barneko Feedback

**Metodoa:** Inkesta anonimoa langileei (SurveyMonkey, Google Forms)

**Galdera Adibideak:**
1. Informazio nahikoa jaso al zenuen intzidentziaren inguruan? (1-5)
2. Komunikazioa argi eta ulergarria izan zen? (1-5)
3. Eguneratzeen maiztasuna egokia izan zen? (Oso maiz / Egokia / Gutxiegi)
4. Zer hobekuntzak proposatuko zenituzke?

#### 6.3.2. Kanpoko Feedback (Bezeroak)

**Metodoa 1:** Email inkesta intzidentzia konponduta 48h ostean

**Galdera Adibideak:**
1. Zenbateraino gustura geratu zara gure komunikazioarekin? (1-5)
2. Informazio nahikoa eman genizun? (Bai / Ez / Neurriz gain)
3. Zer hobetu behar genuke?

**Metodoa 2:** NPS (Net Promoter Score) inguruan: "Zenbateraino gomendatuko zenuke Zabala Gailetak krisi hau kudeatu ondoren?"

### 6.4. Hobekuntza Jarraituaren Prozesua

#### 6.4.1. Lessons Learned Dokumentua

**Template-a:**

```markdown
# LESSONS LEARNED - Komunikazioa - INC-[ID]

Data PIR: [UUUU-HH-EE]
Parte-hartzaileak: [Izenak]

## 1. ZER FUNTZIONATU ZUEN ONDO
- [Puntu 1]
- [Puntu 2]

## 2. ZER EZ ZUEN FUNTZIONATU / ARAZOAK
- [Problema 1]: [Deskribapena]
- [Problema 2]: [Deskribapena]

## 3. HOBEKUNTZA EKINTZAK

| Ekintza | Arduraduna | Epemuga | Egoera |
|---------|------------|---------|--------|
| [Ekintza 1, adib: "Bozeramailea prestakuntza eman"] | [Izena] | [UUUU-HH-EE] | Pendiente |
| [Ekintza 2, adib: "Template-ak eguneratu bezeroei"] | [Izena] | [UUUU-HH-EE] | Eginda |

## 4. PLANEN EGUNERATZEA

- [ ] Komunikazio Plan hau eguneratu hobekuntzekin
- [ ] Incident Response Plan-a eguneratu
- [ ] Entrenamendu berria antolatu

## 5. METRIKEN LABURPENA

- Lehen jakinarazpen denbora: [X minutu] - ✅ Helburua bete / ❌ Ez bete
- Bezeroei lehen mezu: [X minutu] - ✅ / ❌
- Feedback NPS: [Puntuazioa]

---
Dokumentua onartu: [CEO Izena] - [Data]
```

#### 6.4.2. Hobekuntza Zikloa (PDCA)

```
1. PLAN (Planifikatu):
   - PIR ostean, hobekuntza ekintzak identifikatu
   - Ekintza Plan bat prestatu (arduradun, epemuga)

2. DO (Egin):
   - Hobekuntza ekintzak exekutatu
   - Planak eta template-ak eguneratu
   - Entrenamendu berriak eman

3. CHECK (Egiaztatu):
   - Hurrengo intzidentzian hobekuntzak neurtu
   - Metrikak alderatu (aurretik vs ondoren)

4. ACT (Jardun):
   - Hobekuntzak estandarizatu (normaldu)
   - Dokumentuak eguneratuta mantendu
   - Ziklo berria hasi
```

#### 6.4.3. Komunikazio Plan Berrikuspenaren Maiztasuna

- **Urtean behin:** Berrikuspen sistematikoa, hobekuntza txikiak
- **Intzidentzia lar ostean:** Berrikuspen berezi berehala (1 hilabetean)
- **Araudia aldaketa:** GDPR edo beste arau berrien arabera eguneratu

---

## ERANSKINA: Larritasun Matrizea Komunikaziorako

| Larritasuna | Barne Jakinarazpena | Kanpo Jakinarazpena | Agintariak | Prentsa |
|-------------|---------------------|---------------------|------------|--------|
| **Baxua** | IT + CISO | Ez beharrezkoa | Ez | Ez |
| **Ertaina** | IT + CISO + Kudeaketa | Bezeroei (eragina badago) | Ez normalean | Ez |
| **Altua** | CMT | Bezeroei + Hornitzaileei | BCSC (gomendatua) | Erabakigarria (CMT) |
| **Kritikoa** | CMT + Langileen osoa | Bezeroei + Agintariak + Prentsa | AEPD + BCSC + Polizia | Bai (nahitaezkoa) |

---

**Dokumentu Jabetzaren Jabea:** Zibersegurtasun Arduraduna (CISO)
**Azken Eguneratzea:** 2026-01-12
**Hurrengo Berrikuspen Data:** 2027-01-12
**Bertsioa:** 2.0 (Zabaldua)
