# Privacy by Design - Diseinuz Pribatutasuna

## RRHH Portalean Aplikatutako Printzipioak

### 1. Datuen Minimizazioa
**Inplementazioa:**
- Inprimakietan soilik eremu ezinbestekoak
- Ez da eskatzen: erlijioa, orientazio sexuala, sindikatu kidetza
- Aukerako eremuak argi markatuta

**Adibidea:**
```php
class ErabiltzaileaSortu {
    // ✅ Beharrezkoak
    public string $izena;
    public string $eposta;
    
    // ❌ EZ beharrezkoak - EZABATU
    // public string $erlijoa;  // KENDUTA
    // public string $jatorria; // KENDUTA
}
```

### 2. Zifraketa Lehenetsiak
- TLS 1.3 garraiorako
- AES-256-GCM geldirik dauden datuetarako
- bcrypt pasahitzetarako

### 3. Pseudonimizazioa
- Erabiltzaile IDak log-etan (izenak ez)
- Masking ekoizpen-ingurune ez direnetan

### 4. Datuen Banaketa
- Datu pertsonalak taula banatuetan
- Datu sentikorrak (osasuna) zifraketa gehigarriarekin

### 5. Sarbide Kontrola
- RBAC inplementatuta
- Pribilegio txikienaren printzipioa
- MFA beharrezkoa

### 6. Audit Trail Osoa
- Sarrera guztiak erregistratuta
- Atxikipena 2 urte

## Ezaugarri Berrien Egiaztapen Zerrenda

Ezaugarri berriak garatu aurretik:
- [ ] Beharrezkoa al da datu hau?
- [ ] Zein da oinarri juridikoa?
- [ ] Pseudonimiza daiteke?
- [ ] Zifra daiteke?
- [ ] DPIA behar al du?
- [ ] Zein da atxikipen epea?

---

**Dokumentuaren Jabea:** Datuen Babeserako Ordezkaria  
**Azken Berrikuspena:** 2026ko Urtarrilaren 8a  
**Hurrengo Berrikuspena:** 2027ko Urtarrilaren 8a
