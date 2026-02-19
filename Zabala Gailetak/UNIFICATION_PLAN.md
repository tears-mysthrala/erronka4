# Zabala Gailetak - Aplikazio Bateratze Plana

## Laburpen Exekutiboa
Dokumentu honek Android aplikazioa eta web portala bateratzeko plana azaltzen du:
1. Android-etik falta diren ezaugarriak web-era gehituz
2. Android aplikazioaren diseinu bisualaren lerrokatzea web diseinu sistemarekin
3. Bi plataformetan ezaugarri parekotasuna ziurtatuz

**Egoera:** Plangintza Fasea
**Helburuko Amaiera:** 2026 Q2
**Lehentasuna:** Altua

---

## 1. Ezaugarri Hutsune Analisia

### 1.1 Web-en Falta diren Android Ezaugarriak

#### A. Nominen Modulua (LEHENTASUN ALTUA)
**Oraingo Egoera:**
- ❌ Web: Inplementatu gabe (TODO placeholder-ak errutetan)
- ✅ Android: Erabat funtzionala UI modernoarekin

**Beharrezko Inplementazioa:**
- [ ] Nominen taularentzako datu-base eskema
- [ ] Backend API amaiera-puntuak:
  - `GET /api/payroll` - Langilearen nominen zerrenda
  - `GET /api/payroll/{id}` - Nomina zehatzaren xehetasunak lortu
  - `GET /api/payroll/{id}/download` - PDF deskargatu
- [ ] Web UI Orriak:
  - Nominen zerrenda ikuspegia txartelekin
  - Nomina xehetasun ikuspegia xehapenarekin
  - PDF sortzea/deskarga funtzionalitatea
- [ ] Ezaugarriak:
  - Hilabete/urte iragazkia
  - Soldata xehapena (gordina, garbia, kenkariak, IRPF, gizarte segurantza)
  - Bonu bistaratzea
  - Datu historikoetarako sarbidea

**Android-etik Portutzeko UI Elementuak:**
- Gradiente laburpen txartelak
- Xehapen diseinu garbia
- Hilabete/urte nabigazioa
- Deskarga ikonoak eta ekintzak

---

#### B. Dokumentuen Modulua (LEHENTASUN ALTUA)
**Oraingo Egoera:**
- ❌ Web: Inplementatu gabe (TODO placeholder-ak)
- ✅ Android: Dokumentu kudeaketa osoa kategorizazioarekin

**Beharrezko Inplementazioa:**
- [ ] Dokumentu taularentzako datu-base eskema (modeloetan badago dagoeneko)
- [ ] Backend API amaiera-puntuak:
  - `GET /api/documents` - Dokumentu zerrenda
  - `GET /api/documents/{id}` - Dokumentu xehetasunak lortu
  - `POST /api/documents/upload` - Dokumentu berria igo
  - `GET /api/documents/{id}/download` - Dokumentua deskargatu
  - `DELETE /api/documents/{id}` - Dokumentua ezabatu (admin soilik)
- [ ] Web UI Orriak:
  - Dokumentu zerrenda ikuspegia fitxekin (Nire Dokumentuak / Dokumentu Publikoak)
  - Dokumentu igoera formularioa
  - Dokumentu ikusteko/aurrebista
- [ ] Dokumentu Kategoriak:
  - CONTRACT (Kontratuak)
  - PAYSLIP (Nominak)
  - CERTIFICATE (Ziurtagiriak)
  - POLICY (Politikak)
  - OTHER (Beste batzuk)
- [ ] Ezaugarriak:
  - Fitxategi mota ikonoak eta koloreak
  - Kategoria iragazkia
  - Bilaketa funtzionalitatea
  - Deskarga anitza aukera

**Android-etik Portutzeko UI Elementuak:**
- Fitxadun interfazea
- Kategoria badge-ak koloreekin
- Ikono-oinarritutako fitxategi mota bistaratzea
- Egoera hutsik diseinuak

---

#### C. Aginte-panel Hobetua (LEHENTASUN ERTAINA)
**Oraingo Egoera:**
- ⚠️ Web: Oinarrizko aginte-panela estatistikak
- ✅ Android: Aginte-panel aberatsa atal anitzez

**Beharrezko Hobekuntzak:**
- [ ] Ekintza Azkar Txartel Sekzioa:
  - Gradiente txartel diseinuak
  - Ikono-oinarritutako nabigazioa
  - Ekintza arruntetarako esteka zuzenak
- [ ] Estatistika Ikuspegi Orokorra:
  - Opor egunak geratzen direnak
  - Gaur lan egindako orduak
  - Eskaera zai kopurua
  - Dokumentu guztira kopurua
- [ ] Jarduera Berriaren Jarioa:
  - Jarduera berriaren denbora-lerroa
  - Egoera adierazleak (onartua, zai, baztertua)
  - Denbora-markak
- [ ] Pertsonalizazioa:
  - Denboraren araberako agurrak (euskara/gaztelania)
  - Erabiltzaile-espezifiko datu bistaratzea
  - Jakinarazpen badge-ak

**Android-etik Portutzeko UI Elementuak:**
- Gradiente ekintza azkar txartelak
- Estatistika txartelak ikonoekin
- Jarduera denbora-lerro osagaia
- Jakinarazpen badge-ak

---

#### D. Profila/Ezarpenak (LEHENTASUN ERTAINA)
**Oraingo Egoera:**
- ⚠️ Web: Oinarrizko profil edizioa
- ✅ Android: Profil kudeaketa integrala

**Beharrezko Hobekuntzak:**
- [ ] Profila ikusi/editatu
- [ ] Avatar/argazki igoera
- [ ] Pasahitz aldaketa funtzionalitatea
- [ ] MFA ezarpen aldaketa
- [ ] Hizkuntza lehentasuna
- [ ] Jakinarazpen lehentasunak
- [ ] Saioa itxi funtzionalitatea

---

### 1.2 Android-en Ez Dauden Web Ezaugarriak
- ❌ Langileen Zerrenda Kudeaketa (Admin soilik - mugikorreko aplikazioan ez da beharrezkoa)
- ❌ HR Onarpen Lan-fluxuak (Admin soilik - mugikorrak irakurtzeko-soilik ikuspegia du)

---

## 2. Diseinu Sistema Lerrokadura

### 2.1 Kolore Paleta Bateraketa

**Oraingo Web Koloreak (industrial-v2.php-tik):**
```css
--primary: #1D4ED8 (Urdin Sakona)
--accent: #0EA5E9 (Urdin Argia)
--success: #059669 (Berdea)
--warning: #D97706 (Anbarra)
--danger: #DC2626 (Gorria)
```

**Oraingo Android Koloreak (Color.kt-tik):**
```kotlin
PrimaryBlue = #2C3E95 (Erregearen Urdina)
SecondaryTeal = #06B6D4 (Teal/Zian)
AccentOrange = #FF6B35 (Laranja)
AccentPurple = #9333EA (Morea)
SuccessGreen = #10B981 (Berdea)
```

**⚠️ GATAZKA:** Urdin nagusi desberdinak

**Erabakia:** **Web Koloreak Estandar gisa Erabili** (dagoeneko produkzioan)

**Ekintza Elementuak:**
- [ ] Eguneratu Android Color.kt web paletarekin bat egiteko:
  ```kotlin
  PrimaryBlue = Color(0xFF1D4ED8)  // Web --primary bat
  AccentBlue = Color(0xFF0EA5E9)   // Web --accent bat
  SuccessGreen = Color(0xFF059669) // Web --success bat
  WarningAmber = Color(0xFFD97706) // Web --warning bat
  ErrorRed = Color(0xFFDC2626)     // Web --danger bat
  ```
- [ ] Mantendu gradiente koloreak hobetze gisa (oraindik ez web-en)
- [ ] Eguneratu Android UI osagai guztiak kolore berriak erabiltzeko
- [ ] Probatu kolore kontrastea arrazoi (WCAG AA betetze)

---

### 2.2 Tipografia Lerrokadura

**Web (Industrial v2):**
- Font-a: Sistema font-ak (-apple-system, Segoe UI, Roboto)
- Tamainak: xs(0.75rem) 5xl-ra(3rem)

**Android (Material 3):**
- Font-a: Defektuzko Material Tipografia
- Eskala: bodySmall displayLarge-ra

**Ekintza Elementuak:**
- [ ] Ziurtatu Android-ek sistema font-a erabiltzen duela (dagoeneko Material 3 bidez)
- [ ] Mapatu testu tamainak modu koherentean plataformen artean
- [ ] Erabili font pisu berberak (400, 600, 700, 800)

---

### 2.3 Osagai Estilo Lerrokadura

#### Botoiak
**Web:** Biribildua (--radius-md: 10px), kolore solidoak
**Android:** Biribildua (RoundedCornerShape(16.dp)), gradiente aukerak

**Ekintza:**
- [ ] Eguneratu Android botoiak web erradio bat egiteko (10dp)
- [ ] Kendu gradienteak botoietatik (mantendu txarteletarako)
- [ ] Erabili kolore solidoak web paletarekin bat

#### Txartelak
**Web:** Itzal finak (--shadow-md), ertz arginak
**Android:** Elebazioan oinarritutako itzalak, gradiente aukerak

**Ekintza:**
- [ ] Murriztu Android txartel elebazioa web-ekin bat egiteko
- [ ] Mantendu gradiente txartelak aginte-panel ekintza azkarretarako soilik
- [ ] Erabili ertz erradio koherentea (16dp / 16px)

#### Nabigazioa
**Web:** Goiko nabarra esteka horizontalekin
**Android:** Beheko nabigazio barra ikonoekin

**Ekintza:**
- [ ] Mantendu plataforma-espezifiko nabigazioa (espero den eredua)
- [ ] Ziurtatu kolore eskema berbera aplikatzen dela
- [ ] Erabili ikono multzo berbera (Material Icons)

---

### 2.4 Ikonografia

**Bi plataformetan:** Erabili Material Icons

**Ekintza:**
- [ ] Auditatu bi plataformetan erabilitako ikono guztiak
- [ ] Ziurtatu ikono erabilera koherentea (adib. biek BeachAccess erabiltzen dute oporretarako)
- [ ] Eguneratu ikono koloreak paleta berriarekin bat egiteko

---

## 3. Inplementazio Bide-orria

### 1. Fasea: Backend Oinarria (1-2. Astea)
**Lehentasuna:** ALTUA
**Ahalegina:** 40 ordu

- [ ] Diseinatu nominen datu-base taula
- [ ] Diseinatu dokumentuen taula (dagoeneko modeloetan, egiaztatu)
- [ ] Sortu migrazio scriptak
- [ ] Inplementatu PayrollController CRUD eragiketekin
- [ ] Inplementatu DocumentController igoera/deskargarekin
- [ ] Sortu API amaiera-puntuak bi moduluentzat
- [ ] Idatzi unitate probak kontroladoreetarako
- [ ] Gehitu auditoretza erregistroa eragiketa sentikorretan

**Entregagarriak:**
- Nominen API amaiera-puntu funtzionalak
- Dokumentuen API amaiera-puntu funtzionalak
- Datu-base eskema eguneratua
- API dokumentazioa eguneratua

---

### 2. Fasea: Web Frontend - Nominak (3-4. Astea)
**Lehentasuna:** ALTUA
**Ahalegina:** 35 ordu

- [ ] Sortu nominen zerrenda ikuspegia (PHP txantiloia)
- [ ] Sortu nomina xehetasun ikuspegia
- [ ] Inplementatu PDF sortzea TCPDF edo antzekoa erabiliz
- [ ] Gehitu iragazkia (hilabetea, urtea)
- [ ] Estilizatu industrial CSS existitzailea erabiliz
- [ ] Gehitu deskarga funtzionalitatea
- [ ] Inplementatu soldata xehapen bistaratzea
- [ ] Gehitu nabigazio esteka menu nagusian
- [ ] Probatu diseinu erantzulea

**Entregagarriak:**
- Nominen modulu funtzionala web-en
- PDF deskarga gaitasuna
- Diseinu sistemarekin bat datorren UI erantzulea

---

### 3. Fasea: Web Frontend - Dokumentuak (5-6. Astea)
**Lehentasuna:** ALTUA
**Ahalegina:** 35 ordu

- [ ] Sortu dokumentuen zerrenda ikuspegia fitxekin
- [ ] Inplementatu fitxategi igoera formularioa egiaztapenarekin
- [ ] Sortu dokumentu kategorizazio UI
- [ ] Gehitu deskarga funtzionalitatea
- [ ] Inplementatu kategoria badge-ak ikonoekin
- [ ] Gehitu bilaketa/iragazki funtzionalitatea
- [ ] Sortu egoera hutsaren diseinuak
- [ ] Gehitu nabigazio esteka menu nagusian
- [ ] Inplementatu fitxategi mota detekzioa eta ikonoak
- [ ] Probatu fitxategi igoera segurtasuna

**Entregagarriak:**
- Dokumentuen modulu funtzionala web-en
- Fitxategi igoera/deskarga funtzionatzen
- Kategoria-oinarritutako antolamendua
- UI erantzulea

---

### 4. Fasea: Web Frontend - Aginte-panel Hobetua (7. Astea)
**Lehentasuna:** ERTAINA
**Ahalegina:** 20 ordu

- [ ] Gehitu ekintza azkar txartel sekzioa
- [ ] Inplementatu estatistika ikuspegi orokor txartelak
- [ ] Sortu jarduera berriaren jarioa
- [ ] Gehitu denboraren araberako agurrak
- [ ] Estilizatu gradiente txartelekin (Android-ekin bat eginez)
- [ ] Gehitu jakinarazpen badge funtzionalitatea
- [ ] Inplementatu AJAX denbora errealeko eguneraketarako
- [ ] Probatu errendimendua datu biziarekin

**Entregagarriak:**
- Android diseinuarekin bat datorren aginte-panel hobetua
- Ekintza azkarrak funtzionatzen
- Denbora errealeko estatistikak

---

### 5. Fasea: Android Estilo Eguneraketak (8. Astea)
**Lehentasuna:** ERTAINA
**Ahalegina:** 25 ordu

- [ ] Eguneratu Color.kt web paletarekin
- [ ] Eguneratu pantaila guztiak kolore berriak erabiltzeko
- [ ] Doitu botoi estiloak (kendu gradienteak)
- [ ] Eguneratu txartel elebazioak
- [ ] Probatu pantaila guztiak diseinu berriarekin
- [ ] Eguneratu gai konfigurazioak
- [ ] Berreraikitzen eta probatu aplikazioa ondo
- [ ] Eguneratu pantaila-argazkiak eta marketing materialak

**Entregagarriak:**
- Android aplikazioa bisualki web-ekin lerrokatuta
- Kolore paleta koherentea plataformen artean
- Funtzional erregresioak gabe

---

### 6. Fasea: Probak eta Dokumentazioa (9-10. Astea)
**Lehentasuna:** ALTUA
**Ahalegina:** 30 ordu

- [ ] Plataforma-arteko ezaugarri probak
- [ ] Segurtasun auditoria (fitxategi igoerak, deskargak)
- [ ] Errendimendua probak (PDF sortzea)
- [ ] Irisgarritasun probak (WCAG AA)
- [ ] Eguneratu API dokumentazioa
- [ ] Eguneratu erabiltzaile gidak
- [ ] Sortu prestakuntza materialak
- [ ] Burutu erabiltzaile onarpen probak
- [ ] Konpondu identifikaturiko akatsak

**Entregagarriak:**
- Proba txostenak
- Dokumentazio eguneratua
- UAT sinadura
- Produkziorako prest kodea

---

## 4. Espezifikazio Teknikoak

### 4.1 Datu-base Eskema

#### Nominen Taula
```sql
CREATE TABLE payslips (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    month TINYINT NOT NULL, -- 1-12
    year SMALLINT NOT NULL,
    gross_salary DECIMAL(10,2) NOT NULL,
    net_salary DECIMAL(10,2) NOT NULL,
    bonuses DECIMAL(10,2) DEFAULT 0.00,
    overtime DECIMAL(10,2) DEFAULT 0.00,
    social_security DECIMAL(10,2) NOT NULL,
    irpf DECIMAL(10,2) NOT NULL,
    other_deductions DECIMAL(10,2) DEFAULT 0.00,
    pdf_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES users(id),
    UNIQUE KEY unique_payslip (employee_id, month, year),
    INDEX idx_employee (employee_id),
    INDEX idx_period (year, month)
);
```

#### Dokumentuen Taula (egiaztatu dagoen eskema)
```sql
CREATE TABLE documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NULL, -- NULL dokumentu publikoetarako
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category ENUM('CONTRACT', 'PAYSLIP', 'CERTIFICATE', 'POLICY', 'OTHER') NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_type VARCHAR(10) NOT NULL,
    file_size INT NOT NULL,
    is_public BOOLEAN DEFAULT FALSE,
    uploaded_by INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES users(id),
    FOREIGN KEY (uploaded_by) REFERENCES users(id),
    INDEX idx_employee (employee_id),
    INDEX idx_category (category),
    INDEX idx_public (is_public)
);
```

---

## 5. Kalitate Ziurtagiria

### 5.1 Proba Egiaztapen Zerrenda

#### Unitate Probak
- [ ] PayrollController metodoak
- [ ] DocumentController metodoak
- [ ] Fitxategi igoera egiaztapena
- [ ] PDF sortzea
- [ ] Sarbide kontrola logika

#### Integrazio Probak
- [ ] API amaiera-puntuen erantzunak
- [ ] Fitxategi igoera fluxua
- [ ] PDF deskarga fluxua
- [ ] Autentifikazio middleware
- [ ] Baimen egiaztapenak

#### E2E Probak
- [ ] Erabiltzaileak nominak ikusten ditu
- [ ] Erabiltzaileak nomina PDF deskargatzen du
- [ ] Erabiltzaileak dokumentua igotzen du
- [ ] Erabiltzaileak dokumentua deskargatzen du
- [ ] Admin-ek nomina sortzen du
- [ ] Sarbide ukatze agertokiak

#### Segurtasun Probak
- [ ] Fitxategi igoera ahultasunak
- [ ] Bidea zeharkaltzeko erasoak
- [ ] Baimenik gabeko sarbide saiakerak
- [ ] XSS dokumentu izenetan
- [ ] SQL injekzioa iragazkietan

#### Errendimendua Probak
- [ ] PDF sortzea denbora (< 3 segundo)
- [ ] Fitxategi igoera denbora (< 10 segundo 10MB-rako)
- [ ] Zerrenda kontsultak (< 500ms)
- [ ] Deskarga bazkideak (100 erabiltzaile)

---

## 6. Arriskuen Kudeaketa

### 6.1 Identifikaturiko Arriskuak

| Arriskua | Probabilitatea | Eragina | Murrizketa |
|------|-------------|--------|------------|
| PDF sortzea errendimendua arazoak | Ertaina | Altua | Inplementatu cache, async sortzea |
| Fitxategi igoera segurtasun ahultasunak | Altua | Kritikoa | Segurtasun egiaztapen integrala |
| Kolore aldaketak Android UI hausten ditu | Baxua | Ertaina | Proba zorrotza, atzera plana |
| Datu-base eskema aldaketak dagoen kodea hausten du | Ertaina | Altua | Migrazio proba integrala |
| Erabiltzaile nahasmena ezaugarri berriekin | Ertaina | Ertaina | Erabiltzaile prestakuntza, aplikazioko gidak |
| Epemuga gainkargua | Ertaina | Ertaina | Ezaugarriak lehenetsi, agile ikuspuntua |

---

## 7. Arrakasta Metrikak

### 7.1 Metriko Teknikoak
- [ ] API amaiera-puntuen %100 estaldura ezaugarri berrietarako
- [ ] Unitate proba estaldura %90+ kode berrirako
- [ ] < 3 segundo PDF sortzea denbora
- [ ] < 2 segundo orri karga denborak
- [ ] Zero segurtasun ahultasun kritiko
- [ ] WCAG AA irisgarritasun betetze

### 7.2 Erabiltzaile Esperientzia Metrikoak
- [ ] Erabiltzaile gogobetetze kalifikazioa %95+
- [ ] < %5 laguntza tiketak ezaugarri berriei lotuta
- [ ] Ezaugarri adopzioa %80+ lehen hilabetean
- [ ] Mugikorreko aplikazio kalifikazioa 4.5+ izarrak mantentzen
- [ ] Zero datu-haustea gertaerak

### 7.3 Negozio Metrikoak
- [ ] Murriztu HR administratibo denbora %30
- [ ] %100 nomina banaketa digitala
- [ ] Murriztu paper dokumentu biltegiratze kostuak
- [ ] Hobetu betetze auditoretza puntuazioak
- [ ] Handitu langile autozerbitzu erabilpena

---

## 8. Arrakasta Ondorengo Jarduerak

### 8.1 Monitorizazioa
- [ ] Konfiguratu monitorizazioa API amaiera-puntu berrietarako
- [ ] Monitorizatu fitxategi biltegiratze erabilpena
- [ ] Jarraitu PDF sortzea errendimendua
- [ ] Monitorizatu deskarga banda-zabalera
- [ ] Jarraitu ezaugarri erabilpen analitikak

### 8.2 Mantentzea
- [ ] Hileko segurtasun auditoriak
- [ ] Hiruhileko errendimendua berrikuspenak
- [ ] Erregularra babeskopia egiaztapena
- [ ] Fitxategi biltegiratze garbitzea (dokumentu zaharrak)
- [ ] PDF cache mantentzea

### 8.3 Etorkizuneko Hobekuntzak
- [ ] Nomina igoera masibo HR-rentzat
- [ ] Dokumentuentzako sinadura elektronikoa
- [ ] Dokumentu bertsiokatzea
- [ ] Dokumentu iraungitzeko jakinarazpenak
- [ ] Bilaketa aurreratua iragazkiekin
- [ ] Dokumentu aurrebista nabigatzailean
- [ ] Dokumentu eskaneraketa mugikorran

---

## 9. Ondorioa

Bateratze plan honek Android aplikazioa eta web portalaren artean ezaugarri parekotasuna ekarriko du, bi plataformetan diseinu bisual koherentea ziurtatzen den bitartean. Fase-zatitutako ikuspuntuak garapen iteratiboa ahalbidetzen du proba eta balioztapen erregularrekin.

**Hurrengo Urratsak:**
1. Berrikusi eta onartu plan hau
2. Esleitu baliabideak eta epemugua
3. Hasi 1. Fasea: Backend Oinarria
4. Konfiguratu proiektu jarraipena (Jira/GitHub Projects)
5. Programatu asteko aurrerapen berrikuspenak

**Sinadura Beharrezkoa:**
- [ ] Lider Teknikoa
- [ ] Produktu Jabea
- [ ] Segurtasun Taldea
- [ ] Interesdun-ak

---

**Dokumentu Bertsioa:** 1.0
**Azken Eguneraketa:** 2026-02-06
**Egilea:** AI Garapen Laguntzailea
**Egoera:** Onarpena Zain
