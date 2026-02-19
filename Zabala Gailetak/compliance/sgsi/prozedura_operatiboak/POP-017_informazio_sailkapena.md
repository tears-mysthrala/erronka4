# POP-017: Informazioaren Sailkapena

**Helburua:** Informazioa bere kritikotasunaren arabera babestea.
**Arduraduna:** Informazioaren Jabea

## 1. Sailkapen Mailak

### 1.1 Sailkapen Irizpideak
Informazioa bere konfidentzialtasunaren, osotasunaren eta eskuragarritasunaren arabera sailkatzen da.

### 🔴 OSO KONFIDENTZIALA (Confidential)

**Definizioa:** Enpresarentzat kalte oso larria izan daiteke kanpora zabaltzen bada.

**Adibideak:**
- Pasahitzak eta gako kriptografikoak
- Langileen eta bezeroen datu pertsonalak (PII)
- Estrategia planak eta konfidentzialtasun ituneak
- Errezeta nagusiak (formula kimikoak)
- Finantza datu sentikorra (bankuko kontuak, kreditu txartelak)

**Tratamendua:**
- ✅ Zifratu beti (geldirik eta mugimenduan)
- ✅ Sarbide mugatua (Need-to-know printzipioaren arabera)
- ✅ MFA autentifikazioa sarbidea emateko
- ✅ "OSO KONFIDENTZIALA" marka dokumentu guztietan
- ✅ Audit log-ak sarbide guztietarako
- ✅ DLP (Data Loss Prevention) aplikatu
- ✅ Email zifratu S/MIME edo PGP erabiliz
- ❌ Ez partekatu email bidez zifratzea gabe
- ❌ Ez gorde USB gailuetan zifratzea gabe
- ❌ Ez inprimatu beharrezkoa ez bada

### 🟡 KONFIDENTZIALA (Internal Confidential)

**Definizioa:** Enpresarentzat kalte esanguratsua izan daiteke ezagutarazten bada.

**Adibideak:**
- Bezero zerrendak
- Finantza txosten ez-kritikoak
- Proiektu planak eta aurrekontuak
- Kontratuen xehetasunak
- Errezeta sekundarioak

**Tratamendua:**
- ✅ Zifratu gomendagarria (gutxienez trantsitoan)
- ✅ Sarbide kontrola (rol-oinarritua)
- ✅ MFA gomendagarria
- ✅ "KONFIDENTZIALA" marka
- ✅ Audit log-ak
- ⚠️ Posta elektronikoa bakarrik barne domeinuetara
- ❌ Ez partekatu kanpoko pertsonerekin NDA gabe

### 🟢 BARNE ERABILERA (Internal Use)

**Definizioa:** Enpresa barruan partekatzeko, baina ez publikoa.

**Adibideak:**
- Barne politikak eta prozedurak
- Langile telefono zerrendak
- Barne txosten ez-sentikorrak
- Bilera oharrak

**Tratamendua:**
- ✅ Sarbide kontrola (langile orokorrek ikusi dezakete)
- ✅ "BARNE ERABILERA" marka
- ⚠️ Ez zabaldu kanpora baimenik gabe
- ⚠️ Zifratzea aukerako

### 🔵 PUBLIKOA (Public)

**Definizioa:** Kalterik gabe zabal daiteke.

**Adibideak:**
- Webguneko informazioa
- Prentsa oharrak eta komunikazio publikoak
- Produktuen katalogoak
- Argitaratutako dokumentuak

**Tratamendua:**
- ✅ Integritatea bermatu (gainidazketa babestea)
- ✅ "PUBLIKOA" marka (aukerako)
- ⚠️ Onarpen prozesua publikotzeko

## 2. Etiketadoa

### 2.1 Dokumentu Elektronikoak
- Dokumentu elektroniko guztiek sailkapen etiketa izan behar dute goiburuan edo oinean:
  - Word/PDF: "OSO KONFIDENTZIALA - Zabala Gailetak"
  - Email: "[OSO KONFIDENTZIALA]" gaiarekin
  - Fitxategiak: Fitxategi izenean sailkapena (adib: `errezetak_OSO_KONFIDENTZIALA.pdf`)

### 2.2 Dokumentu Fisikoak
- Paperezko dokumentuek etiketa edo zigilua izan behar dute:
  - Gorriz: OSO KONFIDENTZIALA
  - Laranjaz: KONFIDENTZIALA
  - Horiz: BARNE ERABILERA
  - Urdinez: PUBLIKOA

### 2.3 Email Etiketadoa
- Email kudeatzaileak (Outlook, Gmail) automatikoki sailkapen etiketa gehituko dute.
- Langileek eskuz ere gehitu dezakete emailaren gaiarekin: `[OSO KONFIDENTZIALA] Gaia`

## 3. Manipulazioa

### 3.1 Oso Konfidentziala

**Sorkuntza:**
- Sortzaileak sailkapena esleitu behar du sortu bezain laster.

**Partekatzea:**
- Bakarrik "Need-to-know" printzipioaren arabera.
- Email zifratu S/MIME edo PGP erabiliz.
- Kanpoko pertsonei NDA sinatu behar dute.

**Gordetzea:**
- Zifratu beti (AES-256).
- Ez gorde hodei publikoan zifratu gabe.
- Babeskopia zifratua eta leku seguruan.

**Ezabatzea:**
- Behin betiko ezabatu (data shredding).
- Paperak txikitzaile bat erabiliz (DIN P-4 gutxienez).

### 3.2 Konfidentziala

**Sorkuntza:**
- Sailkapena esleitu sortze unean.

**Partekatzea:**
- Barne langileei bakarrik (rol-oinarritua).
- Email enkriptazio gomendagarria.

**Gordetzea:**
- Trantsitoan zifratu (HTTPS, VPN).
- Hodeian zifratzea gomendagarria.

**Ezabatzea:**
- Ezabatu modu seguruan.

### 3.3 Barne Erabilera

**Sorkuntza:**
- Sailkapena esleitu.

**Partekatzea:**
- Langile orokorrei bakarrik.
- Email arrunta erabil daiteke.

**Gordetzea:**
- Sarbide kontrola aplikatu.

**Ezabatzea:**
- Ezabaketa arrunta.

### 3.4 Publikoa

**Sorkuntza:**
- Sailkapena esleitu.

**Partekatzea:**
- Integritatea bermatu.
- Onarpen prozesua publikotzeko.

**Gordetzea:**
- Sarbide publiko edo murriztua.

**Ezabatzea:**
- Ezabaketa arrunta.

## 4. Berrsailkapena

### 4.1 Berrsailkapen Abiarazleak
- Urteroko berrikuspena (dokumentu guztiak)
- Dokumentuaren edukia aldatzen denean
- Negozio testuingurua aldatzen denean
- Segurtasun intzidente bat gertatu ondoren

### 4.2 Berrsailkapen Prozesua
1. Informazioaren Jabeak berrsailkapena proposatu.
2. CISOk berrikusi eta onartu.
3. Dokumentuan sailkapen berria eguneratu.
4. Erabiltzaileei jakinarazi aldaketa.

## 5. Erantzukizunak

### 5.1 Informazioaren Jabea
- Informazioaren sailkapena definitu.
- Sarbide eskaerak onartu.
- Urtero berrikusi sailkapena.

### 5.2 Langile Guztiak
- Sailkapen arauak errespetatu.
- Informazioa sailkapen mailaren arabera kudeatu.
- Sailkapen okerrak CISOri jakinarazi.

### 5.3 CISO
- Sailkapen politika definitu.
- Berrsailkapenak onartu.
- Sailkapen betetze-maila auditatu.

## 6. Auditoria

### 6.1 Sailkapen Auditoria
- Hiruhilero auditoria egin sailkapenak egokiak direla egiaztatzeko.
- Sailkapen gabeko dokumentuak identifikatu.
- Sailkapen okerra detektatzen bada, jakinarazi Informazioaren Jabeari.

## 7. Arau-hausteak

### 7.1 Sailkapen Urraketa
- Informazio OSO KONFIDENTZIALA baimenik gabe partekatzea: Diziplina espedientea eta kaleratzea.
- Informazio KONFIDENTZIALA zifratu gabe bidali: Idatzizko ohartarazpena.
- Dokumentu sailkapenik gabea: Ahozko ohartarazpena eta zuzentzea.

---
**Lotutako Araudia:** ISO 27001:2022 A.5.12 (Informazio Sailkapena)
**Erantzukizuna:** CISO + Informazioaren Jabeak
**Berrikuste Maiztasuna:** Urtero
