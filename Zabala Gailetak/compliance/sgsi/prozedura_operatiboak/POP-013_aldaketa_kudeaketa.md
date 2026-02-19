# POP-013: Aldaketa Kudeaketa Prozedura (Change Management)

**Helburua:** Informazioaren segurtasunean eragina izan dezaketen aldaketa guztiak modu kontrolatuan kudeatzea.
**Arduraduna:** IT Kudeatzailea

## 1. Aldaketa Eskaera (RFC - Request for Change)

Aldaketa guztiak RFC (Request for Change) inprimaki baten bidez eskatu behar dira, honako hauek zehaztuz:

### 1.1 RFC Edukia
- **Aldaketaren deskribapena:** Zer aldatu nahi den (zerbitzaria, softwarea, konfigurazioa).
- **Justifikazioa:** Zergatik beharrezkoa den aldaketa (ahultasuna konpondu, funtzionalitatea hobetu).
- **Inpaktuaren analisia:** Zein sistemi eragingo die, noiz egin behar den.
- **Atzera egiteko plana (Rollback plan):** Zer egin aldaketa huts egiten badu.
- **Proba plana:** Nola probatu aldaketa arrakastatsua izan dela.

### 1.2 RFC Maila
- **Aldaketa Estandarra:** Aurrez definitutako prozedura bat jarraitzen du (adib: patch aplikazioa).
- **Aldaketa Normala:** Planifikatu beharreko aldaketa (adib: software bertsio berri bat).
- **Aldaketa Urgentea:** Berehala egin behar da (adib: segurtasun ahultasun kritikoa).

## 2. Ebaluazioa eta Onarpena

### 2.1 CAB (Change Advisory Board)
CAB taldeak aldaketa ebaluatuko du:
- **Kideak:** IT Kudeatzailea, CISO, OT Kudeatzailea (OT aldaketetarako), Negozioko ordezkaria.
- **Bileren maiztasuna:** Astero astelehen goizean.

### 2.2 Onarpena Maila Arabera
- **Aldaketa Estandarra:** Automatikoki onartuta (aldez aurreko onarpena).
- **Aldaketa Normala:** CAB bileran onartuak.
- **Aldaketa Urgentea:** CISO edo IT Kudeatzailearen berehalako onarpena behar dute. CAB-ari geroago jakinaraziko zaio.

### 2.3 Ebaluazio Irizpideak
- **Arriskua:** Zein da aldaketaren arriskua sistemetan?
- **Kostua:** Zenbat balio du aldaketa (denbora, baliabideak)?
- **Onura:** Zein da aldaketaren onura?
- **Lehentasuna:** Zein aldaketa denbora motzean egin behar da?

## 3. Inplementazioa

### 3.1 Aldaketa Garapen eta Test Ingurunean
- Aldaketa lehenengo Garapen edo Test ingurunean probatu behar da.
- Aldaketa arrakastatsua bada, produkziora pasa daiteke.

### 3.2 Aldaketa Produkzio Ingurunean
- Aldaketa baimendutako leihoan inplementatu behar da:
  - **Lehen aukera:** Larunbata 02:00-06:00 (gutxieneko eragina erabiltzaileengan).
  - **Bigarren aukera:** Osteguna 20:00-22:00.
- Aldaketa kritikoak salbuetsita, edozein unetan egin daitezke.

### 3.3 Aldaketa Komunikazioa
- Erabiltzaileei jakinarazi aldaketa zerbitzuak etengo dituenean.
- Aldaketa aurretik 48 orduko abisua eman.
- Urgenteetan, behar bezain laster jakinarazi.

## 4. Berrikusketa (Post-Implementation Review)

### 4.1 Aldaketa Egiaztapena
Aldaketa egin ondoren, funtzionamendua egiaztatu behar da:
- Aldaketa arrakastatsua izan da?
- Esperotako emaitzak lortu dira?
- Arazorik gertatu da?

### 4.2 Rollback (Atzera Egitea)
Aldaketa huts egiten badu, Rollback plana exekutatu behar da:
- Sistemak aurreko egoerara itzuli.
- Erabiltzaileei jakinarazi arazorik badago.
- Aldaketa berriz planifikatu.

### 4.3 RFC Itxiera
- Aldaketa arrakastatsua bada, RFC itxi behar da.
- Aldaketa dokumentatu behar da erregistro batean (Jira, ServiceNow).

## 5. Erregistroa eta Auditoria

### 5.1 Aldaketa Erregistroa
- Aldaketa guztiak erregistro batean gorde behar dira:
  - Zein aldaketa egin zen
  - Noiz egin zen
  - Nork egin zuen
  - Emaitza (arrakasta, hutsa, rollback)
- Erregistroa 6 urtez gorde behar da.

### 5.2 Auditoria
- Aldaketa prozesua hilero berrikusi behar da.
- Aldaketa hutsak analizatu behar dira (root cause analysis).

## 6. Aldaketa Mota Berezi: OT Aldaketak

### 6.1 OT Sistema Kritikoetan Aldaketak
- OT sistemen aldaketak (PLC, SCADA) berehalako arriskua dute produkzioari.
- Aldaketa hauek CAB berezi batean ebaluatu behar dira:
  - **Kideak:** OT Kudeatzailea, Produkzio Kudeatzailea, CISO, IT Kudeatzailea.
- Aldaketa OT sistemen bakarrik produkzio geldialdietan egin daitezke.

### 6.2 OT Aldaketen Rollback
- OT sistemetan Rollback plana ezinbestekoa da.
- PLC backup-ak egin aldaketa baino lehen.

## 7. Erantzukizunak

- **IT Kudeatzailea:** Aldaketa prozesua kudeatu, CAB bilera antolatu.
- **CISO:** Aldaketa segurtasun eragina ebaluatu, onarpena eman.
- **Garatzaileak/Teknikariak:** Aldaketa inplementatu, probak egin.
- **Erabiltzaileak:** Aldaketak errespetatu, arazoak jakinarazi.

## 8. Berrikuste Plana

- Aldaketa kudeaketa prozesua urtero berrikusi.
- CAB bilerak astero egin.

---
**Erregistroak:** Jira Change Management / RFC Txartelak
**Lotutako Araudia:** ISO 27001:2022 A.8.32 (Aldaketa Kudeaketa)
**Erantzukizuna:** IT Kudeatzailea + CISO
**Berrikuste Maiztasuna:** Urtero
