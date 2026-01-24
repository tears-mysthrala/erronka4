# Baimena Kudeaketa Sistema
# Consent Management System

**Bertsioa:** 1.0  
**Data:** 2026ko urtarrilaren 23a  
**Erakunde:** Zabala Gailetak S.L.  
**GDPR:** Artikulu 7 - Baimenaren baldintzak  
**GDPR:** Artikulu 8 - Adingabearen baimena  
**Egoera:** Indarrean

---

## 1. Xedea eta Eremu

### 1.1 Xedea

Dokumentu honek baimena kudeatzeko sistema definitzen du:
- **Baimena lortu:** Datu subjektuaren baimena lortzeko prozedurak
- **Baimena erregistratu:** Baimena lortu denean erregistratzeko sistema
- **Baimena kendu:** Baimena kentzeko (atzera egin) prozedurak
- GDPR Artikulu 7 eta 8 betetze

### 1.2 GDPR Printzipioak

#### 1.2.1 Baimena Baliozkoa Izateko Baldintza (GDPR Artikulu 7)

Baimena baliozkoa izateko, honako baldintza hauek bete behar ditu:

| Baldintza | Deskribapena | Adibidea |
|-----------|--------------|----------|
| **Askea** | Hautaketa erreala, presioa edo ondorio negatiborik gabe | "Ez onartzea ez du zerbitzua mugatuko" |
| **Zehatza** | Xede zehatz bat edo batzuk | "Newsletter-ak jaso" vs "Guztia onartu" |
| **Informatua** | Erabiltzaileak dakizu zer onartzen duen | Informazio gardena eman tratamenduari buruz |
| **Anbiguotasunik gabea** | Ekintza argi eta garbia behar da | Checkbox bat markatu behar da (ez lehenetsia) |

#### 1.2.2 Baimena Frogatzeko Gaitasuna (GDPR Artikulu 7.1)

Kontrolatzaileak frogatu behar du datu subjektuak baimena eman duela. Horretarako:
- Baimena erregistratu behar da
- Noiz, nola, zer onartzen zuen erregistratu
- Baimena lortzeko erabiliko zen testua gorde
- Auditoria erregistroa mantendu

#### 1.2.3 Baimena Kentzeko Eskubidea (GDPR Artikulu 7.3)

Datu subjektuak baimena kentzeko eskubidea du:
- Baimena kentzea hain erraza izan behar da nola ematea
- Informazioa eman baimena kendu aurretik
- Baimena kentzeak ez du legaltasuna eragingo (baimena eman aurretik tratamendua)

---

## 2. Baimena Motak

### 2.1 Baimena Motak Zabala Gailetak-en

| Baimena Mota | Xedea | Derrigorrezkoa? | Kengarria? |
|--------------|-------|-----------------|------------|
| **Zerbitzu Kontratua** | Kontratu betetze (ez da baimena, legezko base-a "kontratua" da) | ✅ BAI | ❌ EZ (kontratua behar da) |
| **Marketing Emailak** | Newsletter-ak, berritasun emailak | ❌ EZ | ✅ BAI |
| **Cookie-ak (ez-ezinbestekoak)** | Analitika, publizitatea | ❌ EZ | ✅ BAI |
| **Datu Partekatzea** | Datu hirugarren taldeei partekatu | ❌ EZ | ✅ BAI |
| **Lanbide Eskaintza** | CV eta lanbide datuak tratatu | ✅ BAI (baldin aplikatzen bada) | ✅ BAI |

### 2.2 Baimena vs Legezko Base Besteak

GDPR-ak 6 legezko base ditu (Artikulu 6.1):

| Legezko Base | Erabilpena Zabala Gailetak-en | Baimena Behar? |
|--------------|-------------------------------|----------------|
| **a) Baimena** | Marketing, cookie-ak ez-ezinbestekoak | ✅ BAI |
| **b) Kontratua** | Zerbitzua eman (HR Portal erabiltzeko) | ❌ EZ (kontratua da base) |
| **c) Lege betebeharra** | Zerga datuak, soldata erregistroa | ❌ EZ (legeak eskatzen du) |
| **d) Interes bitalak** | Osasun larrialdiak | ❌ EZ (bizitza babestu) |
| **e) Interes publikoa** | - (ez aplikatzen Zabala Gailetak-en) | ❌ EZ |
| **f) Interes legitimoak** | Ustelkeria detektatzea, segurtasuna | ❌ EZ (balantze testa egin) |

**GARRANTZITSUA:** Ez nahastu "baimena" eta "kontratua". Langile baten datu pertsonalak tratatzea ez da "baimena" behar, baizik "kontratua" da legezko base.

---

## 3. Baimena Erregistratzeko Arkitektura

### 3.1 Datu-baseko Egitura

#### 3.1.1 Taula: `baimena_erregistroak`

```sql
CREATE TABLE baimena_erregistroak (
    id SERIAL PRIMARY KEY,
    
    -- Noren baimena?
    erabiltzaile_id INTEGER REFERENCES erabiltzaileak(id),
    eposta VARCHAR(255),  -- Ez erregistratu erabiltzaileentzat (newsletter)
    
    -- Baimena xedea
    baimena_mota VARCHAR(50) NOT NULL,  -- 'MARKETING', 'COOKIE_ANALITIKA', etab.
    xede_deskribapena TEXT NOT NULL,
    
    -- Baimena egoera
    onartua BOOLEAN NOT NULL,
    
    -- Noiz eta nola?
    baimena_data TIMESTAMP NOT NULL DEFAULT NOW(),
    baimena_metodoa VARCHAR(50) NOT NULL,  -- 'WEB_FORMULARIO', 'EMAIL_LINK', 'API', etab.
    ip_helbidea VARCHAR(45),
    user_agent TEXT,
    
    -- Baimena testuaren bertsioa
    pribatutasun_politika_bertsioa VARCHAR(20) NOT NULL,
    baimena_testua TEXT NOT NULL,  -- Baimena emateko erabilitako testua
    
    -- Baimena kentzeko
    kendua BOOLEAN DEFAULT FALSE,
    kentzeko_data TIMESTAMP,
    kentzeko_arrazoia TEXT,
    
    -- Auditoria
    sortu_data TIMESTAMP NOT NULL DEFAULT NOW(),
    eguneratu_data TIMESTAMP DEFAULT NOW(),
    
    -- Indexak
    INDEX idx_erabiltzaile (erabiltzaile_id),
    INDEX idx_eposta (eposta),
    INDEX idx_baimena_mota (baimena_mota),
    INDEX idx_onartua (onartua),
    INDEX idx_kendua (kendua)
);

-- Trigger eguneratu_data automatikoki eguneratzeko
CREATE TRIGGER eguneratu_baimena_erregistroak
    BEFORE UPDATE ON baimena_erregistroak
    FOR EACH ROW
    EXECUTE FUNCTION eguneratu_eguneratu_data();
```

#### 3.1.2 Taula: `baimena_motak`

```sql
CREATE TABLE baimena_motak (
    id SERIAL PRIMARY KEY,
    kodea VARCHAR(50) UNIQUE NOT NULL,  -- 'MARKETING', 'COOKIE_ANALITIKA'
    izena VARCHAR(255) NOT NULL,
    deskribapena TEXT NOT NULL,
    derrigorrezkoa BOOLEAN DEFAULT FALSE,
    aktiboa BOOLEAN DEFAULT TRUE,
    
    -- Auditoria
    sortu_data TIMESTAMP NOT NULL DEFAULT NOW(),
    eguneratu_data TIMESTAMP DEFAULT NOW()
);

-- Hasierako datuak txertatu
INSERT INTO baimena_motak (kodea, izena, deskribapena, derrigorrezkoa) VALUES
('MARKETING', 'Marketing Emailak', 'Newsletter-ak eta promozio emailak jaso', FALSE),
('COOKIE_ANALITIKA', 'Cookie Analitikak', 'Google Analytics eta analitika cookie-ak', FALSE),
('COOKIE_PUBLIZITATEA', 'Cookie Publizitatea', 'Publizitate pertsonalizatuak erakusteko', FALSE),
('DATU_PARTEKATZEA_HORNITZAILE', 'Datu Partekatzea Hornitzaileei', 'Datu hirugarren hornitzaileei partekatu', FALSE);
```

#### 3.1.3 Taula: `baimena_auditoria`

```sql
CREATE TABLE baimena_auditoria (
    id SERIAL PRIMARY KEY,
    baimena_erregistro_id INTEGER REFERENCES baimena_erregistroak(id),
    
    ekintza VARCHAR(50) NOT NULL,  -- 'SORTU', 'KENDU', 'BERRIKUSI'
    erabiltzailea VARCHAR(255),
    ip_helbidea VARCHAR(45),
    deskribapena TEXT,
    
    data TIMESTAMP NOT NULL DEFAULT NOW()
);
```

### 3.2 PHP Baimena Kudeaketa Zerbitzua

```php
<?php
/**
 * Baimena Kudeaketa Zerbitzua
 * GDPR Artikulu 7 - Baimenaren baldintzak
 * 
 * @package ZabalaGailetak\GDPR
 * @version 1.0
 */

namespace ZabalaGailetak\GDPR;

class BaimenaKudeaketaZerbitzua
{
    private $db;
    private $auditoria;
    
    public function __construct($db, $auditoria)
    {
        $this->db = $db;
        $this->auditoria = $auditoria;
    }
    
    /**
     * Baimena erregistratu
     * 
     * @param array $datuak Baimena datuak
     * @return int Baimena erregistro ID
     */
    public function baimena_erregistratu(array $datuak): int
    {
        // Balioztatu datuak
        $this->balioztatu_baimena_datuak($datuak);
        
        // IP eta User-Agent lortu
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        // Pribatutasun politikaren bertsioa lortu
        $politika_bertsioa = $this->lortu_politika_bertsioa();
        
        // Baimena testua lortu (gaur egungo bertsioa)
        $baimena_testua = $this->lortu_baimena_testua($datuak['baimena_mota']);
        
        // Datu-basean txertatu
        $id = $this->db->query(
            'INSERT INTO baimena_erregistroak (
                erabiltzaile_id, eposta, baimena_mota, xede_deskribapena,
                onartua, baimena_metodoa, ip_helbidea, user_agent,
                pribatutasun_politika_bertsioa, baimena_testua
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            RETURNING id',
            [
                $datuak['erabiltzaile_id'] ?? null,
                $datuak['eposta'] ?? null,
                $datuak['baimena_mota'],
                $datuak['xede_deskribapena'],
                $datuak['onartua'],
                $datuak['metodoa'] ?? 'WEB_FORMULARIO',
                $ip,
                $user_agent,
                $politika_bertsioa,
                $baimena_testua
            ]
        )['id'];
        
        // Auditoria erregistroa sortu
        $this->auditoria->erregistratu(
            'BAIMENA_SORTU',
            [
                'baimena_erregistro_id' => $id,
                'baimena_mota' => $datuak['baimena_mota'],
                'onartua' => $datuak['onartua']
            ]
        );
        
        return $id;
    }
    
    /**
     * Baimena kendu (atzera egin)
     * 
     * @param int $erabiltzaile_id Erabiltzaile ID
     * @param string $baimena_mota Baimena mota
     * @param string $arrazoia Kentzeko arrazoia (aukerazkoa)
     * @return bool
     */
    public function baimena_kendu(
        int $erabiltzaile_id, 
        string $baimena_mota, 
        ?string $arrazoia = null
    ): bool {
        // Baimena aurkitu
        $baimena = $this->db->query(
            'SELECT id FROM baimena_erregistroak
             WHERE erabiltzaile_id = ? 
               AND baimena_mota = ?
               AND onartua = TRUE
               AND kendua = FALSE
             ORDER BY baimena_data DESC
             LIMIT 1',
            [$erabiltzaile_id, $baimena_mota]
        );
        
        if (!$baimena) {
            throw new \Exception("Ez da baimena aurkitu");
        }
        
        // Baimena kendu markatu
        $this->db->query(
            'UPDATE baimena_erregistroak
             SET kendua = TRUE,
                 kentzeko_data = NOW(),
                 kentzeko_arrazoia = ?
             WHERE id = ?',
            [$arrazoia, $baimena['id']]
        );
        
        // Auditoria erregistroa sortu
        $this->auditoria->erregistratu(
            'BAIMENA_KENDU',
            [
                'baimena_erregistro_id' => $baimena['id'],
                'baimena_mota' => $baimena_mota,
                'arrazoia' => $arrazoia
            ]
        );
        
        return true;
    }
    
    /**
     * Baimena egiaztatu
     * 
     * @param int $erabiltzaile_id Erabiltzaile ID
     * @param string $baimena_mota Baimena mota
     * @return bool
     */
    public function baimena_egiaztatu(int $erabiltzaile_id, string $baimena_mota): bool
    {
        $baimena = $this->db->query(
            'SELECT onartua, kendua FROM baimena_erregistroak
             WHERE erabiltzaile_id = ? 
               AND baimena_mota = ?
             ORDER BY baimena_data DESC
             LIMIT 1',
            [$erabiltzaile_id, $baimena_mota]
        );
        
        if (!$baimena) {
            return false;  // Ez da baimena aurkitu
        }
        
        // Baimena onartua eta ez kendua?
        return $baimena['onartua'] && !$baimena['kendua'];
    }
    
    /**
     * Erabiltzaile baten baimena guztiak lortu
     * 
     * @param int $erabiltzaile_id
     * @return array
     */
    public function lortu_erabiltzaile_baimena_guztiak(int $erabiltzaile_id): array
    {
        return $this->db->query(
            'SELECT 
                be.baimena_mota,
                bm.izena,
                bm.deskribapena,
                be.onartua,
                be.kendua,
                be.baimena_data,
                be.kentzeko_data
             FROM baimena_erregistroak be
             INNER JOIN baimena_motak bm ON be.baimena_mota = bm.kodea
             WHERE be.erabiltzaile_id = ?
             ORDER BY be.baimena_data DESC',
            [$erabiltzaile_id],
            true  // fetchAll
        );
    }
    
    /**
     * Baimena eguneratu (berrikusi)
     * 
     * @param int $erabiltzaile_id
     * @param string $baimena_mota
     * @param bool $onartua
     * @return int Baimena erregistro berri ID
     */
    public function baimena_eguneratu(
        int $erabiltzaile_id, 
        string $baimena_mota, 
        bool $onartua
    ): int {
        // Baimena berri bat sortu (historia mantentzen da)
        return $this->baimena_erregistratu([
            'erabiltzaile_id' => $erabiltzaile_id,
            'baimena_mota' => $baimena_mota,
            'xede_deskribapena' => $this->lortu_xede_deskribapena($baimena_mota),
            'onartua' => $onartua,
            'metodoa' => 'EGUNERAKETA'
        ]);
    }
    
    /**
     * Baimena exportatu (datu eramangarritasuna - GDPR Artikulu 20)
     * 
     * @param int $erabiltzaile_id
     * @return array JSON format
     */
    public function baimena_exportatu(int $erabiltzaile_id): array
    {
        $baimena_guztiak = $this->db->query(
            'SELECT 
                baimena_mota,
                xede_deskribapena,
                onartua,
                baimena_data,
                baimena_metodoa,
                pribatutasun_politika_bertsioa,
                kendua,
                kentzeko_data,
                kentzeko_arrazoia
             FROM baimena_erregistroak
             WHERE erabiltzaile_id = ?
             ORDER BY baimena_data DESC',
            [$erabiltzaile_id],
            true
        );
        
        return [
            'erabiltzaile_id' => $erabiltzaile_id,
            'exportazio_data' => date('Y-m-d H:i:s'),
            'baimena_erregistroak' => $baimena_guztiak
        ];
    }
    
    /**
     * Balioztatu baimena datuak
     */
    private function balioztatu_baimena_datuak(array $datuak): void
    {
        // Erabiltzaile ID edo eposta behar da
        if (empty($datuak['erabiltzaile_id']) && empty($datuak['eposta'])) {
            throw new \InvalidArgumentException(
                "Erabiltzaile ID edo eposta behar da"
            );
        }
        
        // Baimena mota behar da
        if (empty($datuak['baimena_mota'])) {
            throw new \InvalidArgumentException("Baimena mota behar da");
        }
        
        // Baimena mota existitzen da?
        $mota = $this->db->query(
            'SELECT id FROM baimena_motak WHERE kodea = ? AND aktiboa = TRUE',
            [$datuak['baimena_mota']]
        );
        
        if (!$mota) {
            throw new \InvalidArgumentException(
                "Baimena mota ez da existitzen: {$datuak['baimena_mota']}"
            );
        }
        
        // Onartua boolean izan behar da
        if (!isset($datuak['onartua']) || !is_bool($datuak['onartua'])) {
            throw new \InvalidArgumentException("Onartua boolean izan behar da");
        }
    }
    
    /**
     * Pribatutasun politikaren bertsioa lortu
     */
    private function lortu_politika_bertsioa(): string
    {
        // Konfigurazioan definituta
        return config('app.pribatutasun_politika_bertsioa', '1.0');
    }
    
    /**
     * Baimena testua lortu
     */
    private function lortu_baimena_testua(string $baimena_mota): string
    {
        $testuak = [
            'MARKETING' => 'Onartzen dut Zabala Gailetak-ek nire eposta helbidea erabili dezala newsletter-ak eta promozio informazioa bidaltzeko.',
            'COOKIE_ANALITIKA' => 'Onartzen dut Zabala Gailetak-ek cookie analitikoak erabili ditzala (Google Analytics) webgunearen erabilpena hobetzeko.',
            'COOKIE_PUBLIZITATEA' => 'Onartzen dut Zabala Gailetak-ek cookie publizitatea erabili ditzala publizitate pertsonalizatuak erakusteko.',
            'DATU_PARTEKATZEA_HORNITZAILE' => 'Onartzen dut Zabala Gailetak-ek nire datuak hornitzaile konfiantzazkoekin partekatu ditzala zerbitzua hobeto emateko.'
        ];
        
        return $testuak[$baimena_mota] ?? '';
    }
    
    /**
     * Xede deskribapena lortu
     */
    private function lortu_xede_deskribapena(string $baimena_mota): string
    {
        $mota = $this->db->query(
            'SELECT deskribapena FROM baimena_motak WHERE kodea = ?',
            [$baimena_mota]
        );
        
        return $mota['deskribapena'] ?? '';
    }
}
```

---

## 4. API Endpoints

### 4.1 REST API Baimena Kudeatzeko

#### 4.1.1 POST /api/baimena/erregistratu

Baimena berri bat erregistratu:

```http
POST /api/baimena/erregistratu
Content-Type: application/json
Authorization: Bearer {token}

{
    "erabiltzaile_id": 42,
    "baimena_mota": "MARKETING",
    "onartua": true
}
```

**Erantzuna:**

```json
{
    "success": true,
    "baimena_id": 123,
    "mezua": "Baimena erregistratu da"
}
```

#### 4.1.2 DELETE /api/baimena/kendu

Baimena kendu (atzera egin):

```http
DELETE /api/baimena/kendu
Content-Type: application/json
Authorization: Bearer {token}

{
    "erabiltzaile_id": 42,
    "baimena_mota": "MARKETING",
    "arrazoia": "Ez dut gehiago newsletter-ak jaso nahi"
}
```

**Erantzuna:**

```json
{
    "success": true,
    "mezua": "Baimena kendu da"
}
```

#### 4.1.3 GET /api/baimena/egiaztatu

Baimena egiaztatu:

```http
GET /api/baimena/egiaztatu?erabiltzaile_id=42&baimena_mota=MARKETING
Authorization: Bearer {token}
```

**Erantzuna:**

```json
{
    "onartua": true,
    "baimena_data": "2026-01-23 10:30:00",
    "pribatutasun_politika_bertsioa": "1.0"
}
```

#### 4.1.4 GET /api/baimena/nire-baimena

Erabiltzaile baten baimena guztiak lortu:

```http
GET /api/baimena/nire-baimena
Authorization: Bearer {token}
```

**Erantzuna:**

```json
{
    "erabiltzaile_id": 42,
    "baimena_erregistroak": [
        {
            "baimena_mota": "MARKETING",
            "izena": "Marketing Emailak",
            "deskribapena": "Newsletter-ak eta promozio emailak jaso",
            "onartua": true,
            "kendua": false,
            "baimena_data": "2026-01-23 10:30:00",
            "kentzeko_data": null
        },
        {
            "baimena_mota": "COOKIE_ANALITIKA",
            "izena": "Cookie Analitikak",
            "deskribapena": "Google Analytics eta analitika cookie-ak",
            "onartua": true,
            "kendua": false,
            "baimena_data": "2026-01-23 10:30:00",
            "kentzeko_data": null
        }
    ]
}
```

---

## 5. Web Interfazea

### 5.1 Baimena Kudeaketa Panela (Erabiltzaileentzat)

Erabiltzaileek beren baimena kudeatu behar dute:

**URL:** `https://hr.zabalagailetak.com/nire-kontua/baimena`

**Interfaze diseinu:**

```
╔════════════════════════════════════════════════════════╗
║              NIRE BAIMENA KUDEAKETA                    ║
╠════════════════════════════════════════════════════════╣
║                                                        ║
║  Hemen kudea ditzakezu zure baimena:                  ║
║                                                        ║
║  ┌─────────────────────────────────────────────────┐  ║
║  │ 📧 Marketing Emailak                            │  ║
║  │                                                 │  ║
║  │ Newsletter-ak eta promozio emailak jaso        │  ║
║  │                                                 │  ║
║  │ Egoera: ✅ Onartua (2026-01-23)                 │  ║
║  │                                                 │  ║
║  │ [Baimena Kendu]                                 │  ║
║  └─────────────────────────────────────────────────┘  ║
║                                                        ║
║  ┌─────────────────────────────────────────────────┐  ║
║  │ 🍪 Cookie Analitikak                            │  ║
║  │                                                 │  ║
║  │ Google Analytics eta analitika cookie-ak       │  ║
║  │                                                 │  ║
║  │ Egoera: ❌ Ez onartua                            │  ║
║  │                                                 │  ║
║  │ [Onartu]                                        │  ║
║  └─────────────────────────────────────────────────┘  ║
║                                                        ║
║  ┌─────────────────────────────────────────────────┐  ║
║  │ 📊 Cookie Publizitatea                          │  ║
║  │                                                 │  ║
║  │ Publizitate pertsonalizatuak erakusteko        │  ║
║  │                                                 │  ║
║  │ Egoera: ✅ Onartua (2025-12-01)                 │  ║
║  │ ⚠️ Kendua (2026-01-15)                          │  ║
║  │                                                 │  ║
║  │ [Berriz Onartu]                                 │  ║
║  └─────────────────────────────────────────────────┘  ║
║                                                        ║
║  [Baimena Guztiak Exportatu (JSON)]                   ║
║                                                        ║
╚════════════════════════════════════════════════════════╝
```

### 5.2 Vue.js Osagai Adibidea

```vue
<template>
  <div class="baimena-kudeaketa">
    <h1>Nire Baimena Kudeaketa</h1>
    
    <p class="azalpena">
      Hemen kudea ditzakezu zure baimena. Baimena kendu edo eman dezakezu
      edozein momentutan.
    </p>
    
    <div 
      v-for="baimena in baimena_erregistroak" 
      :key="baimena.baimena_mota"
      class="baimena-karta"
    >
      <h3>{{ baimena.izena }}</h3>
      <p>{{ baimena.deskribapena }}</p>
      
      <div class="baimena-egoera">
        <span v-if="baimena.onartua && !baimena.kendua" class="badge success">
          ✅ Onartua ({{ formatuData(baimena.baimena_data) }})
        </span>
        <span v-else-if="baimena.kendua" class="badge warning">
          ⚠️ Kendua ({{ formatuData(baimena.kentzeko_data) }})
        </span>
        <span v-else class="badge danger">
          ❌ Ez onartua
        </span>
      </div>
      
      <div class="baimena-ekintzak">
        <button 
          v-if="baimena.onartua && !baimena.kendua"
          @click="baimenaKendu(baimena.baimena_mota)"
          class="btn btn-danger"
        >
          Baimena Kendu
        </button>
        
        <button 
          v-else
          @click="baimenaOnartu(baimena.baimena_mota)"
          class="btn btn-success"
        >
          {{ baimena.kendua ? 'Berriz Onartu' : 'Onartu' }}
        </button>
      </div>
    </div>
    
    <button @click="baimenaExportatu" class="btn btn-secondary">
      📥 Baimena Guztiak Exportatu (JSON)
    </button>
  </div>
</template>

<script>
export default {
  name: 'BaimenaKudeaketa',
  
  data() {
    return {
      baimena_erregistroak: []
    };
  },
  
  mounted() {
    this.kargatu();
  },
  
  methods: {
    async kargatu() {
      try {
        const response = await fetch('/api/baimena/nire-baimena', {
          headers: {
            'Authorization': `Bearer ${this.getToken()}`
          }
        });
        
        const data = await response.json();
        this.baimena_erregistroak = data.baimena_erregistroak;
      } catch (error) {
        console.error('Errorea baimena kargatzen:', error);
        alert('Errorea baimena kargatzen');
      }
    },
    
    async baimenaOnartu(baimena_mota) {
      if (!confirm(`Ziur zaude "${baimena_mota}" baimena onartu nahi duzula?`)) {
        return;
      }
      
      try {
        const response = await fetch('/api/baimena/erregistratu', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${this.getToken()}`
          },
          body: JSON.stringify({
            baimena_mota: baimena_mota,
            onartua: true
          })
        });
        
        const data = await response.json();
        
        if (data.success) {
          alert('Baimena onartua');
          this.kargatu();  // Berriz kargatu
        } else {
          alert('Errorea: ' + data.mezua);
        }
      } catch (error) {
        console.error('Errorea baimena onartzean:', error);
        alert('Errorea baimena onartzean');
      }
    },
    
    async baimenaKendu(baimena_mota) {
      const arrazoia = prompt('Zergatik kendu nahi duzu baimena? (aukerazkoa)');
      
      if (arrazoia === null) {
        return;  // Kantzela
      }
      
      try {
        const response = await fetch('/api/baimena/kendu', {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${this.getToken()}`
          },
          body: JSON.stringify({
            baimena_mota: baimena_mota,
            arrazoia: arrazoia
          })
        });
        
        const data = await response.json();
        
        if (data.success) {
          alert('Baimena kendua');
          this.kargatu();  // Berriz kargatu
        } else {
          alert('Errorea: ' + data.mezua);
        }
      } catch (error) {
        console.error('Errorea baimena kenduan:', error);
        alert('Errorea baimena kenduan');
      }
    },
    
    async baimenaExportatu() {
      try {
        const response = await fetch('/api/baimena/exportatu', {
          headers: {
            'Authorization': `Bearer ${this.getToken()}`
          }
        });
        
        const data = await response.json();
        
        // JSON fitxategia deskargatu
        const blob = new Blob([JSON.stringify(data, null, 2)], {
          type: 'application/json'
        });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `baimena_erregistroak_${Date.now()}.json`;
        a.click();
        URL.revokeObjectURL(url);
        
      } catch (error) {
        console.error('Errorea exportatzean:', error);
        alert('Errorea exportatzean');
      }
    },
    
    formatuData(data) {
      return new Date(data).toLocaleDateString('eu-ES');
    },
    
    getToken() {
      return localStorage.getItem('auth_token');
    }
  }
};
</script>

<style scoped>
.baimena-kudeaketa {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.baimena-karta {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
  background: #fff;
}

.badge {
  display: inline-block;
  padding: 5px 10px;
  border-radius: 4px;
  font-size: 14px;
}

.badge.success {
  background: #d4edda;
  color: #155724;
}

.badge.warning {
  background: #fff3cd;
  color: #856404;
}

.badge.danger {
  background: #f8d7da;
  color: #721c24;
}

.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  margin-right: 10px;
}

.btn-success {
  background: #28a745;
  color: white;
}

.btn-danger {
  background: #dc3545;
  color: white;
}

.btn-secondary {
  background: #6c757d;
  color: white;
  margin-top: 20px;
}
</style>
```

---

## 6. Baimena Kentzeko Automatizazioa

### 6.1 Email Unsubscribe Link

Newsletter-etan "unsubscribe" link bat jarri behar da:

```php
<?php
/**
 * Email Newsletter Unsubscribe
 */

// Unsubscribe token sortu
function sortu_unsubscribe_token(int $erabiltzaile_id, string $baimena_mota): string
{
    $payload = [
        'erabiltzaile_id' => $erabiltzaile_id,
        'baimena_mota' => $baimena_mota,
        'exp' => time() + (30 * 24 * 60 * 60)  // 30 egun
    ];
    
    return base64_encode(json_encode($payload)) . '.' . 
           hash_hmac('sha256', json_encode($payload), config('app.secret'));
}

// Unsubscribe token balioztatu
function balioztatu_unsubscribe_token(string $token): ?array
{
    [$payload_b64, $signature] = explode('.', $token);
    $payload = json_decode(base64_decode($payload_b64), true);
    
    // Sinadura egiaztatu
    $expected_signature = hash_hmac(
        'sha256', 
        json_encode($payload), 
        config('app.secret')
    );
    
    if (!hash_equals($expected_signature, $signature)) {
        return null;  // Sinadura okerra
    }
    
    // Iraungita?
    if ($payload['exp'] < time()) {
        return null;  // Iraungita
    }
    
    return $payload;
}

// Unsubscribe orrian (GET /unsubscribe?token=xxx)
$token = $_GET['token'] ?? '';
$payload = balioztatu_unsubscribe_token($token);

if (!$payload) {
    echo "Token baliogabea edo iraungita";
    exit;
}

// Baimena kendu
$baimena_zerbitzua = new BaimenaKudeaketaZerbitzua($db, $auditoria);
$baimena_zerbitzua->baimena_kendu(
    $payload['erabiltzaile_id'],
    $payload['baimena_mota'],
    'Email unsubscribe link bidez'
);

echo "Baimena kendu da. Ez duzu gehiago newsletter-ak jasoko.";
```

**Newsletter-ean link-a jarri:**

```html
<p style="font-size: 12px; color: #666;">
  Ez duzu gehiago emailak jaso nahi?
  <a href="https://hr.zabalagailetak.com/unsubscribe?token=<?= $unsubscribe_token ?>">
    Harpidetza kendu
  </a>
</p>
```

---

## 7. Baimena Re-Confirmation (Berrikuspena)

GDPR-ak ez du eskatzen baimena periodikoki berrikusteko, baina gomendatzen da.

### 7.1 Baimena Re-Confirmation Politika

Baimena berrikusi behar da:
- **2 urtean behin:** Marketing baimena aktibatu bada
- **Pribatutasun politika aldatzen denean:** Bertsio berri bat argitaratzen denean

### 7.2 Baimena Re-Confirmation Email

```php
<?php
/**
 * Baimena Re-Confirmation Email
 */

function bidali_baimena_berrikuspenerako_email(int $erabiltzaile_id)
{
    $erabiltzailea = lortu_erabiltzailea($erabiltzaile_id);
    
    // Confirmation token sortu
    $token = sortu_unsubscribe_token($erabiltzaile_id, 'MARKETING');
    $confirmation_url = "https://hr.zabalagailetak.com/baimena/berrikusi?token={$token}";
    
    $gaia = "Berrikusi zure baimena - Zabala Gailetak";
    
    $gorputza = "
        <html>
        <body>
            <h2>Kaixo {$erabiltzailea['izena']},</h2>
            
            <p>
                Duela 2 urte onartu zenuen gure newsletter-ak jasotzeko.
                Mesedez, berrikusi eta berretsi nahi duzun jarraitu nahi duzula.
            </p>
            
            <p>
                <a href='{$confirmation_url}' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>
                    BAI, jarraitu nahi dut
                </a>
            </p>
            
            <p>
                Edo mesedez, hemen klik egizu jarraitu nahi ez baduzu:
                <a href='https://hr.zabalagailetak.com/unsubscribe?token={$token}'>
                    Harpidetza kendu
                </a>
            </p>
            
            <p>
                Eskerrik asko,<br>
                Zabala Gailetak Taldea
            </p>
        </body>
        </html>
    ";
    
    bidali_email($erabiltzailea['eposta'], $gaia, $gorputza);
}
```

**Cron job konfiguratu (automatikoki bidali 2 urte ondoren):**

```sql
-- Baimena 2 urte baino zaharragoak aurkitu
SELECT DISTINCT erabiltzaile_id
FROM baimena_erregistroak
WHERE baimena_mota = 'MARKETING'
  AND onartua = TRUE
  AND kendua = FALSE
  AND baimena_data < NOW() - INTERVAL '2 years'
  AND erabiltzaile_id NOT IN (
      -- Ez bidali berrikuspena jada bidali bada azken hilabetean
      SELECT erabiltzaile_id 
      FROM baimena_berrikuspena_bidal
      WHERE bidali_data > NOW() - INTERVAL '1 month'
  );
```

---

## 8. Auditoria eta Txostenak

### 8.1 Baimena Auditoria Txostena

Hilean behin, baimena auditoria txosten bat sortu:

```sql
-- Baimena estatistikak
SELECT 
    bm.izena as baimena_mota,
    COUNT(*) FILTER (WHERE be.onartua = TRUE AND be.kendua = FALSE) as onartua_kopurua,
    COUNT(*) FILTER (WHERE be.kendua = TRUE) as kendua_kopurua,
    COUNT(*) as guztira
FROM baimena_erregistroak be
INNER JOIN baimena_motak bm ON be.baimena_mota = bm.kodea
WHERE be.baimena_data >= DATE_TRUNC('month', NOW() - INTERVAL '1 month')
  AND be.baimena_data < DATE_TRUNC('month', NOW())
GROUP BY bm.izena
ORDER BY onartua_kopurua DESC;
```

**Txosten txantiloia:**

```markdown
# BAIMENA KUDEAKETA TXOSTEN HILABETEKOA

**Hilabetea:** 2026ko Urtarrila  
**Sortu data:** 2026-02-01  
**Sortua:** DPO (Datu Babeseko Ordezkaria)

## 1. Baimena Estatistikak

| Baimena Mota | Onartua | Kendua | Guztira |
|--------------|---------|--------|---------|
| Marketing Emailak | 245 | 12 | 257 |
| Cookie Analitikak | 380 | 5 | 385 |
| Cookie Publizitatea | 120 | 45 | 165 |
| Datu Partekatzea | 200 | 8 | 208 |

## 2. Baimena Kentzeko Arrazoiak

| Arrazoia | Kopurua |
|----------|---------|
| Ez dut gehiago newsletter-ak jaso nahi | 8 |
| Gehiegizko emailak | 3 |
| Ez nago interesatua | 4 |
| Beste arrazoi bat | 2 |

## 3. Gomendioak

- Baimena kentzeko tasa (%): 4.8% (onartua: 945, kendua: 70)
- Tasa normala da (< 10% onartua)
- Ez dago ekintza berezirik behar

**Sinadura:** Ane Galdos (DPO)  
**Data:** 2026-02-01
```

---

## 9. Erreferentzia eta Loturak

### 9.1 GDPR Artikuluak

- **Artikulu 7 - Baimenaren baldintzak:** Baimena askea, zehatza, informatua eta anbiguotasunik gabea
- **Artikulu 8 - Adingabearen baimena:** 16 urte azpiko adingabeen baimena guraso edo tutoreen baimena behar du
- **Artikulu 20 - Datu eramangarritasuna:** Datu subjektuak eskubidea du bere datuak JSON edo CSV formatuan jasotzeko

### 9.2 Lotutako Dokumentuak

- `Zabala Gailetak/compliance/gdpr/privacy_notice_web.md` - Pribatutasun Politika
- `Zabala Gailetak/compliance/gdpr/data_subject_rights_procedures.md` - Datu Subjektuaren Eskubideak
- `Zabala Gailetak/compliance/gdpr/cookie_policy.md` - Cookie Politika

---

## 10. Berrikuste Historia

| Bertsioa | Data | Aldaketak | Egilea |
|----------|------|-----------|--------|
| 1.0 | 2026-01-23 | Hasierako bertsioa sortuta | OpenCode AI + DPO |

---

## 11. Onespena

**Onartua:**

___________________________  
Ane Galdos  
DPO (Datu Babeseko Ordezkaria)  
Data: 2026-01-23

___________________________  
Jon Azpiazu  
CISO (Informazio Segurtasuneko Buruordea)  
Data: 2026-01-23

___________________________  
Maite Etxeberria  
IT Zuzendaria  
Data: 2026-01-23

---

**Dokumentu amaiera**  
Zabala Gailetak S.L. - GDPR  
Konfidentzialtasun Maila: BARNEKOA
