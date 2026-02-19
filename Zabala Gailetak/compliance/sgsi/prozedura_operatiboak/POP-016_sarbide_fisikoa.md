# POP-016: Sarbide Fisikoaren Kontrola

**Helburua:** Baimenik gabeko sarbide fisikoak saihestea instalazioetan eta informazioan.
**Arduraduna:** Segurtasun Fisikoko Arduraduna

## 1. Perimetroko Segurtasuna

### 1.1 Sarbide Nagusiak
- **Txartel bidezko kontrola:** Fabrika eta bulego sarrerak txartel bidezko sarbide kontrola dute.
- **Horma eta Hesiak:** Fabrika instalazioak horma eta hesiz inguratuta daude.
- **Kamarak:** CCTV kamarak sarreretan eta estrategikoki kokatuta.

### 1.2 Bisitariak
- **Erregistroa:** Bisitari guztiak harrera gunean erregistratu behar dira.
- **Identifikazioa:** NAN edo beste identifikazio dokumentu bat erakutsi behar dute.
- **Laguntza:** Bisitariak beti langile baten laguntzaz egon behar dira.
- **Bisitari Txartela:** Ikusgai izan behar duten bisitari txartel bat emango zaie.

### 1.3 Zonalde Publikoa vs. Pribatua
- **Zonalde Publikoa:** Harrera, bilera gelak (sarbide librea).
- **Zonalde Pribatua:** Bulegoak, produkzio eremua (txartelarekin bakarrik).
- **Eremu Segurua:** CPD, Server Room, Kutxa Gordea (baimen berezia behar da).

## 2. Eremu Seguruak (CPD/Server Room)

### 2.1 Sarbidea
- **Baimendutako Langile Teknikoak Soilik:** IT eta OT langileen zerrenda eguneratua.
- **Zerrendaren Berrikuste Maiztasuna:** Hiruhilero berrikusi sarbide zerrenda.

### 2.2 Sarbide Mekanismoa
- **Txartela + PIN:** Bi faktoreko autentifikazioa (txartela eta PIN kodea).
- **Biometrikoa (Aukerako):** Hatz-marka edo begiko iris eskaneatzea.
- **Manualki Irekitzea Debekatuta:** Mekanikoki irekitzea (gako fisikoa) bakarrik urgentzietan (sute alarma, adibidez).

### 2.3 Erregistroa (Log)
- Sarbide guztiak log batean erregistratzen dira:
  - Nork sartu/irten
  - Noiz
  - Zein ate
- Log-ak 6 hilabetez gordetzen dira.
- Log-ak hilero berrikusi behar dira sarbide susmagarriak detektatzeko.

### 2.4 Eskorta Araua
- Bi pertsona baino gehiago ezin dira batera sartu CPD-an (tailgating saihesteko).
- Bisitariak beti IT langile baten laguntzaz sartu behar dira.

## 3. Mahai Garbia eta Pantaila Garbia (Clear Desk and Clear Screen)

### 3.1 Mahai Garbia Politika
- **Dokumentu Konfidentzialak:** Lanaldia amaitzean, dokumentu konfidentzial guztiak giltzapetik gorde behar dira.
- **USB Gailuak:** Ez utzi USB gailuak mahaiaren gainean, giltzapetik gorde.
- **Paper Hutsa:** Inpresora ondoan paperak ez uztea, berehala hartu.

### 3.2 Pantaila Garbia Politika
- **Ordenagailuak Blokeatu:** Mahaitik altxatzean, ordenagailuak blokeatu (Win+L Windows-en, Cmd+Ctrl+Q macOS-en).
- **Pantaila Itzaltzea Automatikoki:** 5 minutuz erabilera ez bada, pantaila automatikoki blokeatuko da.

### 3.3 Printerra eta Kopiaketaren Segurtasuna
- Inprimaturiko dokumentuak berehala hartu printerratik.
- Printerrak "pull printing" sistema bat izan behar du (erabiltzaileak txartela pasatu behar du inprimatzeko).

## 4. Ekipoen Segurtasuna

### 4.1 Zerbitzariak
- **Rack Itxiak:** Zerbitzariak rack itxietan egon behar dira giltzarekin.
- **Etiketa:** Zerbitzari guztiak etiketatuta izan behar dira (izena, funtzioa).

### 4.2 Kableatua
- **Babestuta Manipulazioaren Aurka:** Sare eta potentzia kableak babestuta egon behar dira.
- **Kable Kudeaketa:** Rack-etan kableak modu antolatu batean.

### 4.3 Ordenagailu Eramangarriak
- **Kensington Lock:** Ordenagailu eramangarriak mahaiari loturik egon behar dira (Kensington lock).
- **Lapurretaren Aurkako Alarma:** Ordenagailu eramangarri batzuetan lapurretaren aurkako alarma instalatu.

## 5. Segurtasun Monitoring

### 5.1 CCTV Kamarak
- **Kokapena:** Sarrerak, korridoreak, CPD sarrera, produkzio eremua.
- **Grabaketa:** 90 egunez grabazio iraungirik gorde.
- **Monitorizazioa:** Zaindari segurtasuna edo IT langilea erregularki monitorizatzen du.

### 5.2 Alarma Sistema
- **Intrusio Alarma:** Alarma sistema aktibatzen da gauean eta asteburuetan.
- **Ateak Irekiak:** Ate bat gehiegi denbora irekita badago, alarma soinu egiten du.
- **Sute Alarma:** Sute alarma sistema egiaztatu eta probatu urtean behin.

## 6. Urgentzia Prozedura

### 6.1 Sute Alarma
- Sute alarma aktibotzean, pertsona guztiek berehala ebakuatu behar dute.
- CPD-ean FM-200 sistema automatikoki aktibatzen da.

### 6.2 Segurtasun Intzidentea
- Segurtasun intzidente bat (lapurreta, sabotajea, adibidez) detektatzen bada:
  - Berehala Segurtasun Fisikoko Arduraduna eta CISO jakinarazi.
  - Ebidentziak gorde (grabaketa, log-ak).
  - Poliziari jakinarazi beharrezkoa bada.

## 7. Erantzukizunak

- **Segurtasun Fisikoko Arduraduna:** Sarbide kontrola kudeatu, txartelak eman eta deuseztatu, CCTV monitorizatu.
- **IT Kudeatzailea:** Sarbide log-ak berrikusi, sarbide sistema mantentzen.
- **Langile Guztiak:** Mahai Garbia eta Pantaila Garbia politika errespetatu, bisitari susmagarriak jakinarazi.

## 8. Berrikuste Plana

- Sarbide fisiko politika urtero berrikusi.
- Sarbide zerrendak hiruhilero eguneratu.
- CCTV sistemak eta alarmak hiruhilero probatu.

---
**Lotutako Araudia:** ISO 27001:2022 A.7.1, A.7.2, A.7.3 (Segurtasun Fisikoa)
**Erantzukizuna:** Segurtasun Fisikoko Arduraduna + IT Kudeatzailea
**Berrikuste Maiztasuna:** Urtero
