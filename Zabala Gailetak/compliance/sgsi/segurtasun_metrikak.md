# KPI Segurtasun Metrikak

## Helburua
Informazio Segurtasunaren Kudeaketa Sistemaren (SGSI) eraginkortasuna neurtzeko KPI-ak (Key Performance Indicators) definitzea.

## KPIs Nahitaezkoak (ISO 27001:2022)

### 1. Intzidente Kudeaketa

| KPI | Helburua | Neurketa | Maiztasuna |
|-----|----------|----------|------------|
| Zibersegurtasun intzidente kopurua | <5 hileko | Intzidente kopurua hileko | Hilero |
| Erantzun-denbora batez bestekoa | <30 minutu (kritikoak) | SLA betetze-maila | Hilero |
| Intzidenteak konpontzeko denbora (MTTR) | <4 ordu (kritikoak) | Batez besteko ordutan | Hilero |
| Intzidente berriz gertatzea | <5% | Berriz gertatzen diren intzidenteak / Guztira | Hiruhilero |

### 2. Ahultasunak eta Patch-ak

| KPI | Helburua | Neurketa | Maiztasuna |
|-----|----------|----------|------------|
| Ahultasun kritikoen kopurua | 0 | Scan emaitzak | Astero |
| Patch aplikazio-tasa | >95% 30 egunetan | Patch aplikatuak / Guztira | Hilero |
| Zaharkitzeko denbora batez bestekoa | <15 egun | Batez besteko egun kopurua | Hilero |
| Ahultasun ireki kopurua | <10 (kritiko/altuak) | Ahultasun kopurua irekita | Astero |

### 3. Sarbide Kontrola

| KPI | Helburua | Neurketa | Maiztasuna |
|-----|----------|----------|------------|
| Autentifikazio huts egiteak | <100 eguneko | Failed logins | Egunero |
| MFA adopzio-tasa | 100% (admin/GB) | Erabiltzaileak MFA-rekin / Guztira | Hilero |
| Pribilegiatutako kontuen berrikuspena | 100% hiruhileko | Berrikusitak / Guztira | Hiruhilero |
| Baimenik gabeko sarbide saiakerak | <50 hileko | Blokeatutako saiakerak | Hilero |

### 4. Babeskopia eta BCP

| KPI | Helburua | Neurketa | Maiztasuna |
|-----|----------|----------|------------|
| Babeskopia arrakasta-tasa | >99% | Arrakastatsua / Guztira | Egunero |
| Babeskopia leheneratzeko probak | 1 hiruhileko | Probak osatu dira | Hiruhilero |
| RTO betetze-maila | <4 ordu | Egiazko RTO | DR proba bakoitzean |
| RPO betetze-maila | <1 ordu | Datuen galera denbora | DR proba bakoitzean |

### 5. Prestakuntza eta Kontzientziazioa

| KPI | Helburua | Neurketa | Maiztasuna |
|-----|----------|----------|------------|
| Prestakuntza osotzea | >90% | Osatu dute / Guztira | Urtero |
| Phishing simulazio arrakasta | <10% klik-tasa | Klik-ak / Guztira | Hiruhilero |
| Segurtasun gertakizunen jakinarazpena | >95% | Jakinaraziak / Guztira | Hilero |
| Segurtasun politiken ezagutza | >85% | Testen emaitzak | Urtero |

### 6. Betetzea (Compliance)

| KPI | Helburua | Neurketa | Maiztasuna |
|-----|----------|----------|------------|
| ISO 27001 kontrolen betetzea | 100% | Inplementatutako kontrolak / Guztira | Hiruhilero |
| GDPR aurkikuntza irekiak | 0 | Aurkikuntza irekiak | Hilero |
| Politiken eguneratzea | <1 urte zaharregoa | Zaharkitutako politikak | Hiruhilero |
| Auditoria gomendio itxita | >90% | Itxita / Guztira | Hiruhilero |

### 7. Sare Segurtasuna

| KPI | Helburua | Neurketa | Maiztasuna |
|-----|----------|----------|------------|
| Firewall arau berrikuspena | 100% urtero | Berrikusitak / Guztira | Urtero |
| IDS/IPS alert-a egiaztatuak | >90% | Egiaztatuak / Guztira | Astero |
| Malware detektatu eta blokeatuak | >99% | Blokeatuak / Guztira | Egunero |
| DDoS erasoen erantzun-denbora | <15 minutu | Batez besteko denbora | Gertaera bakoitzean |

### 8. Hobekuntza Jarraitu

| KPI | Helburua | Neurketa | Maiztasuna |
|-----|----------|----------|------------|
| Hobekuntza proiektu osatuta | >80% | Osatu dira / Guztira | Hiruhilero |
| Lesson learned dokumentatuak | >90% | Dokumentatuak / Intzidenteak | Hilero |
| SGSI berrikuste bilerak | 4 urteko | Bilerak osatu dira | Hiruhilero |

## KPIen Dashboard-a

### Erakusteko Formatua
KPIak dashboard batean erakutsi behar dira eguneratuta:
- **Egunero:** Intzidenteak, ahultasunak, babeskopiak
- **Astero:** Patch-ak, IDS/IPS alertak
- **Hilero:** Prestakuntza, MFA, betetzea
- **Hiruhilero:** Auditoriak, berrikuspena

### Tresnak
- **Grafana:** KPI dashboard-a
- **Elastic Kibana:** Log-ak eta segurtasun metrikak
- **Power BI:** Zuzendaritzarentzat txosten executiboa

## Txostenak

### Hileko Txostena (CISO → Zuzendaritza)
- Segurtasun intzidente laburpena
- KPI emaitza nagusiak
- Aste honetan hartutako neurri kritikoak
- Hurrengo hileko planifikazioa

### Hiruhileko Txostena (CISO → Zuzendaritza)
- KPI joera analisia
- ISO 27001 betetzea
- GDPR betetzea
- Hobekuntza proiektuak

### Urteko Txostena (CISO → Zuzendaritza + Batzorde)
- Urteko KPI laburpena
- Intzidente larrien analisia
- Hobekuntza plan berria
- Aurrekontua eta baliabideak

## Erantzukizunak
- **CISO:** KPIak definitu, txostenak prestatu, zuzendaritzari aurkeztu.
- **Segurtasun Analistak:** Datuak bildu, metrikak kalkulatu, dashboard-a eguneratu.
- **IT Kudeatzailea:** IT metrikak hornitu (patch-ak, ahultasunak, babeskopiak).
- **Giza Baliabideak:** Prestakuntza osotze-mailak hornitu.

## Berrikuste Plana
- KPIak hiruhilero berrikusi helburuak egokiak direla egiaztatzeko.
- KPI berriak gehitu beharrezkoa bada (adib: OT metriken, hodei metrikak).

---
**Lotutako Araudia:** ISO 27001:2022 Klausula 9 (Errendimenduaren Ebaluazioa)
**Erantzukizuna:** CISO + Segurtasun Analistak
**Berrikuste Maiztasuna:** Hiruhilero
