# Software Instalazio Politika

**Helburua:** Ekipoetan instalatzen den softwarea kontrolatzea malwarea eta lizentzia arazoak saihesteko.

## 1. Baimendutako Softwarea

### 1.1 Zerrenda Zuria (Whitelist)
- IT Sailak onartutako "Zerrenda Zuria" (Whitelist) bakarrik instala daiteke.
- Zerrenda honek honako hauek ditu:
  - Sistema eragile onartuak (Windows 11, Ubuntu 22.04 LTS)
  - Produktibit ate tresnak (Microsoft Office 365, LibreOffice)
  - Nabigatzaileak (Chrome, Firefox, Edge)
  - Garapen tresnak (Visual Studio Code, Git, Node.js, PHP)
  - Segurtasun tresnak (Antivirus, VPN)

### 1.2 Erabiltzaile Eskubideak
- Erabiltzaile estandarrek ez dute administratzaile baimenik instalazioak egiteko.
- Software bat instalatu behar bada, IT Sailari eskatu behar zaio.

## 2. Eskaera Prozesua

### 2.1 Software Berria Eskatzea
1. Tiketa ireki IT sistema de tiketeetan (Jira, ServiceDesk).
2. Justifikatu zergatik behar den softwarea (lan eginkizunak, proiektua).
3. IT Sailak segurtasuna eta lizentzia ebaluatuko ditu.

### 2.2 Ebaluazio Irizpideak
IT Sailak honako hauek ebaluatuko ditu:
- **Segurtasuna:** Malwarea ez duela, ziurtagiria duen fabrikatzailea.
- **Lizentzia:** Lizentziak kostua eta baldintzak.
- **Alternatiba:** Dagoeneko baliokide bat badago zerrendan.
- **Bateragarritasuna:** Gure sistemarekin bateragarria den.

### 2.3 Onarpena
- Software arrunta: IT Kudeatzaileak onartzea nahikoa da.
- Software garestia edo arriskutsua: CISOren eta Finantzako Zuzendaritzaren onarpena behar du.

### 2.4 Instalazio Prozedura
- IT Sailak softwarea instalatuko du erabiltzailearen ordenagailuan.
- Softwarea inbentarioan erregistratuko da.
- Erabiltzaileari erabilpen jarraibide laburra emango zaio.

## 3. Debekatutako Softwarea

### 3.1 Erabat Debekatuta
- **P2P (Peer-to-Peer):** Torrent bezalako tresnak, eMule, uTorrent.
- **Hacking Tresnak:** Metasploit, Nmap, Wireshark (baimenik gabe).
- **Jokoak eta Aisialdiko Aplikazioak:** Laneko ekipoetan debekatuta.
- **Software Pirata edo Crack-eatua:** Lizentzia urraketa larria da.

### 3.2 Muga turik Erabiltzea
- **Garapen Tresnak:** Garatzaileentzat bakarrik (IDE, debugger-ak, compiler-ak).
- **Segurtasun Tresnak:** Segurtasun profesionalentzat bakarrik baimena emanda.

## 4. Auditoria

### 4.1 Software Inbentario Eskaneatzea
- Hilero software inbentario automatizatua egingo da.
- Baimenik gabeko softwarea detektatzen bada, berehala jakinaraziko zaio erabiltzaileari.

### 4.2 Arau-hausteak
- **Lehen aldiz:** Ohartarazpen idatzia eta softwarea desinstalatu.
- **Bigarren aldiz:** Diziplina espedientea eta pribilegiatutako sarbideak mugatu.
- **Hirugarren aldiz:** Kaleratzea (lizentzia urraketak izan badira, zigor penala ere).

## 5. Software Eguneraketak

### 5.1 Automatikoak
- Sistema eragilearen eguneraketak automatikoki instalatuko dira gauean.
- Antivirus eguneraketak egunero automatikoki.

### 5.2 Manuala (IT Sailak)
- Garrantzi handiko aplikazioak (Microsoft Office, Adobe, adibidez) hilero eguneratuko dira.
- Kritikoak diren patch-ak 7 eguneko epean aplikatuko dira.

## 6. Salbuespena (Bereziki Baimendua)

### 6.1 Garapen eta Test Ingurune
- Garatzaileek garapen ingurunetan software gehigarriak instalatu ahal dituzte, baina:
  - Produkzio ingurunean ezin dira instalatu baimenik gabe.
  - Segurtasun analisiak egin behar dira software bakoitzean.

### 6.2 OT (Teknologia Operatiboa) Softwarea
- SCADA, PLC programazio softwareak OT taldeari bakarrik.
- Instalazio guztiak IT Sailari jakinarazi behar zaizkio.

## 7. Erantzukizunak
- **IT Sailak:** Software zerrenda zuria mantendu, eskaera ebaluatu, instalatu.
- **CISO:** Segurtasun arriskuak ebaluatu, politika gainbegiratu.
- **Erabiltzaileak:** Politika errespetatu, softwarea ez instalatu baimenik gabe.

## 8. Berrikuste Plana
- Software politika urtero berrikusi.
- Zerrenda zuria hiruhilero eguneratu.

---
**Lotutako Araudia:** ISO 27001:2022 A.8.32 (Aldaketa Kudeaketa)
**Erantzukizuna:** IT Kudeatzailea + CISO
**Berrikuste Maiztasuna:** Urtero
