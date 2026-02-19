# Informazioaren Ezabatze Seguru Politika

**Helburua:** Informazioa modu seguruan suntsitzen dela bermatzea bere bizitza baliagarria amaitzean.

## 1. Paperezko Dokumentuak

### 1.1 Konfidentzialak
- Paper-txikitzailea (trituradora) erabili behar da gutxienez DIN P-4 mailarekoa.
- Gurutze-mozketaren txikitzaile bat gomendatzen da dokumentu oso sentikorretarako.
- Ez bota inoiz paperontzira dokumentu sentikorrik.

### 1.2 Suntsiketa Masiboa
- Kanpoko enpresa ziurtatu bat erabili daiteke suntsiketa maisuborako.
- Suntsiketa ziurtagiria eskatu behar da.

## 2. Euskarri Digitalak (USB, Diskoak, CD/DVD)

### 2.1 Berrerabili aurretik
- **Disko Gogorra (HDD):** Formateo segurua egin (low-level format edo 3-pass overwrite, NIST SP 800-88 arabera).
- **SSD/Flash Memory:** ATA Secure Erase komandoa erabili.
- **USB Gailuak:** Formateo segurua eta enkriptazio gakoen ezabatzea.

### 2.2 Baztertzean (Ez berrerabiltzeko kasuan)
- **Suntsiketa Fisikoa:** Taladroa, disolbatzailea edo erasoa-puntu fisikoak sortzea.
- **Desmagnetizazioa:** Disko gogorretan bakarrik (ez SSD-tan).
- Ziurtagiri bat lortu suntsiketa zerbitzuak egiten denean.

## 3. Hodeiko Datuak

### 3.1 Ezabaketa Logikoa
- "Soft delete" ondoren, egiaztatu behin betiko ezabatzea gertatzen dela hornitzailearen politiken arabera.
- Horrnitzailearen SLA berrikusi datuak noiz ezabatzen diren.

### 3.2 Kriptografia-bidezko Ezabatzea (Crypto-shredding)
- Gakoa suntsituz datuak ezin dira berreskuratu.
- Gomendatzen da datu oso sentikorrekin.

## 4. Datu-baseen Erregistroak
- Erregistroak ezabatu aurretik, babeskopia gordetzea araudian beharrezkoa den ala ez ebaluatu.
- SQL DELETE erabili, baina produkzio ingurunean TRUNCATE saihestu (auditoria ezean).
- Babeskopia zaharrak (atxikipen-epea amaitu ondoren) modu seguruan ezabatu.

## 5. Erantzukizunak
- **Informazioaren Jabea:** Noiz ezabatu behar den erabakitzen du.
- **IT Sailak:** Ezabaketa segurua exekutatzen du.
- **CISO:** Prozesua gainbegiratzen du eta GDPR betetzea bermatzen du.

## 6. Auditoria eta Erregistroa
- Ezabaketa sentikorren erregistro bat mantendu behar da:
  - Zer ezabatu zen
  - Noiz
  - Nork egin zuen
  - Zer metodo erabili zen
- Erregistroak 6 urtez gorde behar dira.

---
**Lotutako Araudia:** GDPR Artikulu 17 (Ezabatzeko Eskubidea), ISO 27001 A.8.10
**Erantzukizuna:** CISO + IT Kudeatzailea
**Berrikuste Maiztasuna:** Urtero
