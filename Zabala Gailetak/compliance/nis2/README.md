# NIS2 Direktibaren Inplementazio Karpeta
# NIS2 Directive Compliance Directory

**Enpresa:** Zabala Gailetak, S.L.  
**Direktiba:** NIS2 (EU 2022/2555)  
**Transposizioa:** Real Decreto-ley (España) — Ley de Ciberseguridad  
**Deadline:** 2026-10-17  
**Entitate Mota:** Important Entity (Art. 3)  
**Status:** ⏳ IN PROGRESS  
**Jabea:** CISO (Mikel Etxebarria) + Legal Advisor  

---

## 1. Karpetaren Egitura / Directory Structure

```
compliance/nis2/
├── README.md                           # Dokumentu hau
├── nis2_controls_mapping.md            # NIS2 Art.21 → Kontrol Mapa
├── csirt_roster.md                     # CSIRT Taldea + Kontaktuak
├── vulnerability_disclosure_policy.md  # Ahultasunen jakinarazpen politikoa
├── supplier_security_register.md       # Hornitzaileen Segurtasun Erregistroa
├── training_exercises_plan.md          # Formakuntza eta Simulakroak
├── notifications/
│   ├── early_warning_24h_template.md   # 24h Alerta Goiztiarra Txantiloia
│   ├── full_report_72h_template.md     # 72h Txosten Osoa Txantiloia
│   └── final_report_template.md       # Azken Txostena Txantiloia
├── evidence-pack/
│   ├── README.md                       # Ebidentzien Katalogoa
│   └── .gitkeep                        # Ebidentzia artxiboak hemen gordeko dira
└── siem_soar/
    ├── nis2_correlation_rules.json     # SIEM Korrelazio Arauak
    └── nis2_soar_playbooks.md          # SOAR Playbook-ak
```

---

## 2. NIS2 Artikulu Aplikagarriak

| Artikulua | Gaia | Egoera | Ebidentzia |
|-----------|------|--------|------------|
| **Art. 3** | Entitate mota identifikazioa | ✅ Eginak | `nis2_implementation_plan.md` |
| **Art. 20** | Zuzendaritzaren gobernantza | ✅ Eginak | `sgsi/segurtasun_politika.md` |
| **Art. 21.2.a** | Ziber-higienea + Arriskuak | ⏳ Partzialki | MFA ✅, EDR ⏳, Patching 70% |
| **Art. 21.2.b** | Intzidentzien kudeaketa | ⏳ Partzialki | SOP ✅, CSIRT ⏳, 24h auto ⏳ |
| **Art. 21.2.c** | Negozio jarraitutasuna | ✅ Eginak | `sgsi/business_continuity_plan.md` |
| **Art. 21.2.d** | Hornidura-kate segurtasuna | ⏳ Partzialki | Inbentarioa ⏳, DPA ⏳ |
| **Art. 21.2.e** | Ahultasunen jakinarazpena | ❌ Falta | Politika sortu behar |
| **Art. 21.2.f** | Kriptografia | ✅ Eginak | TLS 1.3, AES-256-GCM |
| **Art. 21.2.g** | Segurtasun politikak | ✅ Eginak | SGSI dokumentazioa |
| **Art. 21.2.h** | Sarbide kontrola | ✅ Eginak | MFA + RBAC |
| **Art. 23** | Intzidentzien jakinarazpena | ⏳ Partzialki | Txantiloiak ⏳, Automat. ⏳ |
| **Art. 24** | ENISA erregistroa | ⏳ Pendiente | Erregistratu behar |
| **Art. 29** | Informazio trukaketa | ⏳ Pendiente | ISAC konexioa ⏳ |

---

## 3. Agintari Nazionalak eta Kontaktuak

| Entitatea | Kontaktua | Funtzioa |
|-----------|-----------|----------|
| **INCIBE-CERT** | incidencias@incibe-cert.es | CSIRT Nazionala (operadoreak) |
| **CCN-CERT** | ccn-cert@cni.es | CSIRT Gobernua |
| **AEPD** | internacional@aepd.es | Datu Babesa (GDPR + NIS2 overlap) |
| **ENISA** | info@enisa.europa.eu | EU Zibersegurtasun Agentzia |
| **BCSC** | contacto@basquecybersecurity.eus | Euskadiko Zibersegurtasun Zentroa |

---

## 4. Erreferentzia Dokumentuak

- [NIS2 Implementation Plan](../nis2_implementation_plan.md) — Plan orokorra
- [Industry Compliance Matrix](../industry_compliance_matrix.md) — Sektore matrizea
- [Regulatory Monitoring](../regulatory_monitoring_procedure.md) — Araudiaren jarraipena
- [Incident Response SOP](../../security/incidents/sop_incident_response.md) — Intzidentzia SOP
- [SIEM Strategy](../../security/siem/siem_strategy.md) — SIEM arkitektura
- [Business Continuity Plan](../sgsi/business_continuity_plan.md) — BCP
- [GDPR Breach Response](../gdpr/gdpr_breach_response_sop.md) — GDPR jakinarazpena

---

## 5. Azken Eguneraketak / Changelog

| Data | Aldaketa | Egileak |
|------|----------|---------|
| 2026-02-06 | Karpeta egitura sortu, txantiloiak gehitu | CISO + AI |
| 2026-02-05 | NIS2 Implementation Plan sortu | CISO |

---

*Dokumentu hau sortu da NIS2 (EU 2022/2555) betebeharrak betetzeko. Zabala Gailetak, S.L.*
