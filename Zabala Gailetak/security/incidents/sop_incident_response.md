# Intzidentzien Erantzun Prozedura (SOP) - Zabala Gailetak

## 1. Helburua

Prozedura honen helburua segurtasun intzidentziak modu ordenatuan, eraginkorrean eta azkarrean
kudeatzea da, kalteak minimizatzeko eta negozioaren jarraitutasuna bermatzeko.

## 2. Faseak (NIST Ereduan oinarrituta)

### Fase 1: Prestaketa

- **Taldea:** Intzidentzien Erantzun Taldea (CSIRT) definituta egon behar da (IKT arduraduna,
  Segurtasun arduraduna, Zuzendaritza).
- **Tresnak:** Monitorizazio sistemak (SIEM), forentse tresnak, komunikazio bide alternatiboak.
- **Formakuntza:** Simulakroak aldian-aldian egitea.

### Fase 2: Detekzioa eta Analisia

- **Alerta:** SIEM, IDS, Antibirus edo erabiltzaile baten bidez jasotako abisua.
- **Triajea:**
  1. Egiaztatu: Benetako intzidentzia da ala positibo faltsua?
  2. Kategorizatu: (Adib. Ransomware, DDoS, Datu ihesa).
  3. Lehenetsi: Larritasunaren arabera (Kritikoa, Altua, Ertaina, Baxua).
- **Erregistroa:** Ireki intzidentzia berria `incident_log_template.md` erabiliz.

### Fase 3: Euste-neurriak (Containment)

- **Berehalakoa:** Isolatu kaltetutako sistemak saretik (kablea kendu edo VLAN isolatua).
  **Ez itzali ekipoa** RAM memoria galduko delako (forentserako garrantzitsua).
- **Epe laburrera:** Blokeatu erasotzaileen IPak suebakian, aldatu kaltetutako pasahitzak.

### Fase 4: Desagerraraztea (Eradication)

- Identifikatu malwarearen jatorria eta sarrera puntua.
- Garbitu sistemak: Antibirusa pasatu, rootkit-ak bilatu.
- Kasu larrietan: Formateatu eta hutsetik instalatu (baina ebidentziak gorde ondoren).
- Ahultasuna partxeatu (segurtasun eguneraketak).

### Fase 5: Berreskurapena (Recovery)

- Berrezarri sistemak babeskopietatik (ziurtatu babeskopia garbia dela).
- Monitorizatu sistema gertutik hurrengo ordu/egunetan, erasoa errepikatzen ez dela ziurtatzeko.
- Berrezarri zerbitzua erabiltzaileentzat.

### Fase 6: Ikasitako Lezioak (Post-Incident)

- Bilera intzidentzia itxi eta 2 asteko epean.
- Txostena idatzi: Zer funtzionatu du? Zer ez? Zer hobetu behar da?
- Eguneratu Segurtasun Plana eta prozedurak.

## 3. Komunikazio Plana

- **Barnekoa:** Langileei eta Zuzendaritzari informatu (informazio teknikoegia ekidin).
- **Kanpokoa:**
  - **Bezeroak:** Datu pertsonalak arriskuan egon badira (GDPR 72 orduko epea).
  - **Agintariak:** Datu Babeserako Bulegoa edo Zibersegurtasun Zentroa (Beharrezkoa bada).
  - **Prentsa:** Komunikazio arduradunaren bidez soilik.
