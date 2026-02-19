# Mehatxu Inteligentzia Politika (Threat Intelligence)

**Helburua:** Mehatxuen inguruko informazioa bildu eta aztertzea defentsak hobetzeko.

## 1. Informazio Iturriak

### 1.1 Instituzio Publikoak
- **INCIBE-CERT:** Alertak eta segurtasun buletinak.
- **CCN-CERT:** Ahultasunen oharrak eta gomendioak.
- **Europol EC3:** Ziberdelitu mehatxu txostenak.
- **CISA (EEB):** Zibersegurtasun alertak (ICS-CERT OT sektoreari begira).

### 1.2 Fabrikatzaileak eta Hornitzaileak
- **Software eguneraketak:** Patch oharrak eta CVE (Common Vulnerabilities and Exposures) iragarkiak.
- **Microsoft Security Response Center (MSRC)**
- **Siemens CERT (OT teknologiarentzat)**
- **AWS, Azure, Google Cloud segurtasun buletinak**

### 1.3 Open Source Intelligence (OSINT)
- **Albisteak:** Zibersegurtasun blog-ak eta hedabideak.
- **Foro espezializatuak:** Reddit r/netsec, Stack Overflow.
- **GitHub:** Exploit kodeak eta ahultasun txostenak (Proof of Concept).
- **Twitter/X:** Segurtasun ikertzaileen jarraipena (@threat_intel, @bad_packets).

### 1.4 Komertziala Threat Intelligence Feeds
- **AlienVault OTX:** Mehatxu datu-base irekia.
- **MISP (Malware Information Sharing Platform):** Mehatxu elkarbanatze plataforma.
- **VirusTotal:** Fitxategi eta URL malwarea analizatzeko.

## 2. Threat Intelligence Prozesua

### 2.1 Bilaketa (Collection)
- **Automatizatua:** RSS feeds, API integrazioak, SIEM konektoreak.
- **Manuala:** Buletin berrikuspena, blog-en irakurketa, foro azterketa.

### 2.2 Analisia (Analysis)
- Mehatxua gure azpiegituran eragina izan dezakeen ebaluatu:
  - **Garrantzitsua:** Gure sistemetan zuzenean eragiten duen ahultasuna.
  - **Moderatua:** Gure sektorean edo antzeko azpiegituran eragiten duen mehatxua.
  - **Baxua:** Urruneko edo ez-aplikagarria.

### 2.3 Prioriz azioa (Prioritization)
- **Kritikoak (P1):** Berehala ekin behar zaio (<24 ordu).
- **Altua (P2):** Aste batean konpondu behar da (<7 egun).
- **Ertaina (P3):** Hilabete batean konpondu behar da (<30 egun).
- **Baxua (P4):** Hurrengo mantentzean konpondu.

### 2.4 Ekintza (Action)
- **Patch kritikoak aplikatu:** Ahultasunak konpondu segurtasun eguneraketak aplikatuz.
- **Firewall arauak eguneratu:** IoC (Indicators of Compromise) blokeatu.
- **Langileak abisatu:** Phishing kanpainak, social engineering teknikak jakinarazi.
- **Monitorizazio indartu:** Log-etan mehatxu espezifikoak bilatu.

### 2.5 Zabaltzea (Dissemination)
- Mehatxu kritikoak berehala jakinarazi CISOari eta IT taldeari.
- Asteko mehatxu laburpena idatzi talde teknikoarentzat.
- Hileko mehatxu txostena zuzendaritzarentzat.

## 3. IoC Kudeaketa (Indicators of Compromise)

### 3.1 IoC Motak
- **IP helbideak:** Mehatxu IP zerrenda (C&C zerbitzariak, botnet-ak).
- **Domeinuak:** Phishing edo malware banatzen duten domeinuak.
- **Hash-ak (MD5, SHA-256):** Malware fitxategien sinadura digitalak.
- **URL-ak:** Malware deskarga edo Exploit Kit URL-ak.
- **Email helbideak:** Phishing kanpainetako igorle helbideak.

### 3.2 IoC Blokeaketa
- Firewall eta IDS/IPS sistemetan IoC zerrendak inportatu.
- Email gateway-ean spam zerrendak eguneratu.
- DNS mailako blokeaketa (DNS sinkhole).

### 3.3 IoC Berrikuste Maiztasuna
- **Kritikoak:** Egunero eguneratu.
- **Altua:** Astero eguneratu.
- **Bestelakoak:** Hilero eguneratu.

## 4. Komunikazioa

### 4.1 Barne Komunikazioa
- **CISO eta IT Taldea:** Berehala jakinarazi mehatxu kritikoak.
- **Langile Guztiak:** Phishing kanpaina aktiboak emailez jakinarazi.
- **Zuzendaritza:** Mehatxu txosten hilekoa aurkeztu.

### 4.2 Kanpoko Komunikazioa
- **INCIBE-CERT:** Intzidente larriak jakinarazi (NIS2 betetzeko).
- **Sektoreko Elkarteak:** Mehatxu informazioa elkarbanatu sektoreko enpreseekin.
- **Hornitzaileak:** Ahultasun kritikoak jakinarazi gure sistemetan aurkitzen badira.

## 5. Threat Intelligence Plataforma

### 5.1 Tresnak
- **MISP:** Mehatxu elkarbanatze plataforma (instalatua lokalean).
- **TheHive:** Intzidente erantzuna integratzeko.
- **Elastic Stack:** SIEM integrazioa IoC bilaketa automatizatzeko.

### 5.2 Integrazio Automatikazioa
- API bidez IoC automatikoki inportatu Firewall eta IDS/IPS sistemetan.
- SIEM-ean alert-ak sortu IoC bat detektatzen denean.

## 6. Prestakuntza eta Kontzientziazioa

### 6.1 IT Taldearen Prestakuntza
- Urtero threat intelligence prestakuntza eman IT taldeari.
- Mehatxu berriak identifikatzeko eta analizatzeko gaitasuna garatu.

### 6.2 Langile Orokorraren Kontzientziazioa
- Phishing kanpaina simulatuak egin hiruhilero.
- Social engineering teknikak ezagutzeko prestakuntza eman.

## 7. Erantzukizunak
- **CISO:** Threat Intelligence programa kudeatu, mehatxu kritikoen analisia.
- **Segurtasun Analistak:** Mehatxu iturriak monitorizatu, IoC kudeatu.
- **IT Sailak:** Patch-ak eta blokeaketa neurriak aplikatu.
- **Langile Guztiak:** Mehatxu susmagarriak jakinarazi.

## 8. Metrikak eta KPI-ak

### 8.1 Jarraipenerako Metrikak
- **IoC detektatuak:** Zenbat IoC identifikatu dira hilean.
- **Patch aplikazio-denbora:** Batez besteko denbora ahultasun bat konpontzeko.
- **Mehatxu jakinarazpen-denbora:** Mehatxu bat detektatu eta taldea jakinarazteko denbora.
- **False positive tasa:** Zenbat false positive sortzen dituzten alertek.

### 8.2 Helburuak
- Mehatxu kritikoak <24 orduan konpondu.
- IoC blokeaketa tasa >95%.
- Langile phishing simulazioan klik-tasa <10%.

## 9. Berrikuste Plana
- Threat Intelligence politika urtero berrikusi.
- Mehatxu iturrien eraginkortasuna ebaluatu hiruhilero.
- Berrikuntzak integratu (adib: AI-based threat detection).

---
**Lotutako Araudia:** ISO 27001:2022 A.5.7 (Threat Intelligence), NIS2 Zuzentaraua
**Erantzukizuna:** CISO + Segurtasun Analistak
**Berrikuste Maiztasuna:** Urtero
