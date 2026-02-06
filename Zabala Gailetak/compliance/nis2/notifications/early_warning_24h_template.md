# Alerta Goiztiarra — Early Warning (≤ 24h)
# NIS2 Art. 23.4.a — Notificación Inicial

**Dokumentu Kodea:** NIS2-NOT-001  
**Txantiloi Mota:** Early Warning  
**Epemuga:** ≤ 24 ordu intzidentzia detektatutik  
**Hartzailea:** INCIBE-CERT (incidencias@incibe-cert.es)  
**CC:** BCSC, AEPD (datu pertsonalak inplikatzen badira)  

---

## FORMULARIOA / NOTIFICATION FORM

### 1. BIDALTZAILEAREN DATUAK

| Eremua | Edukia |
|--------|--------|
| **Enpresa:** | Zabala Gailetak, S.L. |
| **CIF:** | B-XXXXXXXX |
| **Sektorea:** | Elikagai fabrikazioa (NIS2 — Important Entity) |
| **Kontaktu pertsona:** | [CISO / Incident Commander izena] |
| **Telefonoa:** | +34 6XX XXX XXX |
| **Email:** | ciso@zabala-gailetak.eus |
| **PGP Fingerprint:** | [Insert fingerprint] |

### 2. INTZIDENTZIAREN DATUAK (AURRETIAZ / PRELIMINARY)

| Eremua | Edukia |
|--------|--------|
| **Intzidentzia ID:** | INC-YYYY-NNNN |
| **Detekzio data/ordua:** | YYYY-MM-DD HH:MM (UTC+1) |
| **Jakinarazpen data/ordua:** | YYYY-MM-DD HH:MM (UTC+1) |
| **Denbora detekziotik:** | XX ordu, XX minutu |

### 3. ERASOAREN DESKRIBAPEN LABURRA

| Eremua | Edukia |
|--------|--------|
| **Intzidentzia mota:** | [ ] Ransomware / [ ] DDoS / [ ] Datu ihesa / [ ] Sarbide ez-baimendua / [ ] Malware / [ ] OT erasoa / [ ] Beste: _______ |
| **Larritasuna (hasierako balorazioa):** | [ ] KRITIKOA / [ ] ALTUA / [ ] ERTAINA |
| **Deskribapen laburra:** | [Laburpen bat (2-3 esaldi) gertaeraren berri emanez] |
| **Susmo arrazoiak:** | [ ] SIEM alerta / [ ] Erabiltzaile txostena / [ ] Kanpo jakinarazpena / [ ] Anomalia detektatu |

### 4. INPAKTUA (HASIERAKO BALORAZIOA)

| Eremua | Edukia |
|--------|--------|
| **Zerbitzu kaltetuak:** | [Zehaztu: Web, Email, ERP, SCADA, PLC...] |
| **Erabiltzaile/sistema kopurua kaltetuta:** | Gutxi gorabehera: _____ |
| **Datu pertsonalak arriskuan?** | [ ] Bai / [ ] Ez / [ ] Aztertzen |
| **OT/ekoizpen etendura?** | [ ] Bai / [ ] Ez |
| **Zerbitzu esanguratsuetan inpaktua (NIS2)?** | [ ] Bai / [ ] Ez |
| **Mugatze neurriak hartu dira?** | [ ] Bai (deskribatu) / [ ] Ez (arrazoitu) |

### 5. HASIERAKO NEURRIAK

| Neurria | Egoera |
|---------|--------|
| Kaltetutako sistemak isolatu | [ ] Eginak / [ ] Prozesuan / [ ] Ez aplikagarri |
| IP susmagarriak blokeatu (firewall) | [ ] Eginak / [ ] Prozesuan / [ ] Ez aplikagarri |
| Kredentzialak aldatu | [ ] Eginak / [ ] Prozesuan / [ ] Ez aplikagarri |
| Forensic ebidentzien babesa | [ ] Eginak / [ ] Prozesuan / [ ] Ez aplikagarri |
| Backup integritatea egiaztatu | [ ] Eginak / [ ] Prozesuan / [ ] Ez aplikagarri |

### 6. HURRENGO URRATSAK

- [ ] Ikerketa zehatza martxan (72h txostena prestatzen)
- [ ] Kanpo laguntza eskatu: [ ] SOC / [ ] Forensic provider / [ ] Legal
- [ ] AEPD jakinaraztea beharrezkoa bada (GDPR Art.33 — 72h)
- [ ] Erabiltzaileei jakinarazpena beharrezkoa bada (GDPR Art.34)

### 7. MUGAZ GAINDIKO INPAKTUA

| Eremua | Edukia |
|--------|--------|
| **Beste EU estatu kideak kaltetuak?** | [ ] Bai (zehaztu) / [ ] Ez / [ ] Aztertzen |
| **Zein estatu?** | ________________ |

---

## BIDALKETA INSTRUKZIOAK

1. **Bete** formulario hau NIS2 intzidentzia bat detektatu eta 24 orduko epean.
2. **Bidali** INCIBE-CERT-era:
   - Web: https://www.incibe.es/incibe-cert/notificacion-incidentes
   - Email: incidencias@incibe-cert.es (PGP bidez enkriptatu ahal bada)
3. **Gorde** kopia bat `compliance/nis2/evidence-pack/` karpetan, timestamped.
4. **CC** bidali DPO-ari eta Legal-ari.
5. **Erregistratu** bidalketa SIEM-ean (ticket lotura).

---

## OHARRA GARRANTZITSUA

> **NIS2 Art. 23.4.a:** "Without undue delay, and in any event within 24 hours of becoming  
> aware of the significant incident, [entities shall] submit an early warning to the CSIRT  
> or, where applicable, the competent authority [...]"

**Zigorra ez betetzeagatik:** € 10M arte edo fakturazioaren %2 (Art. 34.4).

---

*Txantiloi hau: 2026-02-06 | Zabala Gailetak, S.L. — NIS2 Compliance*
