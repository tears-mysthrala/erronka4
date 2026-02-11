# Sare Topologia eta Mapa - Zabala Gailetak

## 1. Topologiaren Deskribapena

Enpresak sare segmentatu bat ezarri du, segurtasuna bermatzeko.

### Egitura Nagusia

* **DMZ (Gune Desmilitarizatua):**
  * Kanpotik eskuragarri egon behar diren zerbitzuak (Web zerbitzaria, Emaila, Honeypot).
  * Firewall bidez isolatuta barne aretik.
* **Barne Sarea (LAN):**
  * Erabiltzaileen ekipoak.
  * Inprimagailuak.
* **Sistemen Sarea (Server VLAN):**
  * Datu-baseak, Fitxategi zerbitzariak, Domeinu kontrolatzailea.
  * Sarbidea murriztuta (bakarrik beharrezko portuak).
* **OT Sarea (Industrial):**
  * PLC-ak (OpenPLC), HMI-ak, SCADA sistemak.
  * **Node-RED:** Datuen integrazioa eta fluxuen kudeaketa egiteko, sare berean kokatuta.
  * Makina fisikoen simulazioa (Factory I/O).
  * IT saretik guztiz isolatuta edo "Data Diode" / Firewall zorrotz baten bidez.

## 2. Sare Mapa (Irudiak)

Jarraian, dokumentaziotik ateratako sare mapak agertzen dira:

### image2.png

![image2.png](data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAdwAAALQCAYAAAA6icETAACAAElEQVR4XuydB1yV1RvHWa6ynOUoG5brX5mVWmo5MrWyMiutHJWWe1Wae+bMrbj3BEEEBRdDEMG9wC0ge4iKiIoTfP7v7+B7eXnfy+WC9wJyn9/n8/uA95x3gPfle59znvMcK2KxWCwWi2V2WalfYLFYLBaLZXoxcFksFovFygcxcFksFovFygcxcFksFovFygcxcFksFovFygcxcFksFovFygcxcFksFovFygcxcFksFovFygcxcFksFovFygcxcFksFovFygcxcFksFovFygcxcFmsXOjswUs05bfVNPq7xUZ53eSd6lPoFfqpjzW1J3VZqb6sXh3efUZzrDkceS5BfWmNLkclaY4zh/1dT6gvrVf4HaqPNbWNfc84z/HRHGsOG6P8eM90qDdLuON7s81uXCc67Jr6x3xiMXBZrFzoz09nUedaY4z2jF7r1afQK/RTH2tq96g/WX1ZvfJ2OKw51hy+cCxSfWmNoi9e1hxnDrsv3ae+tF7hd6g+1tQ29j2zcPBmzbHmsDHKj/eMDNz88ICvV9L5E7HqH/OJxcBlsXKh3P7BNfaPJwNXvxi42YuBaz4P6bCWgctiFbSUf3Bn9tpA7sv2GTSG2owR+qmPNbV3rTmgvqxeRZyN1xxrDiclpKgvrVFK0m3NceZwaFC0+tJ6hd+h+lhT29j3zHHfC5pjzWFjlB/vGXmoNz/MwGWxCoGUwDUmQmOxWKbRjx/M0YDRXGbgsliFQAxcFqtgxMBlsSxMDFwWq2DEwGWxLEwMXBarYMTAZbEsTAxcFqtgxMBlsSxMDFwWq2DEwGWxLEwMXOOVfsuT0mJ+Z7PpUdxworvn1W+RXImBy2JZmBi4xistsgPRmdpsdoYvz1K/RXIlBi6LZWFi4BovAPfR2ZoUn1iMbcFOuFyCgftYDFwWKxdi4BovBi4bZuBmioHLYuVCDFzjxcBlwwzcTDFwWaxciIFrvBi4bJiBmykGLouVC2Hf1D1OR4VvXL2lbmYpxMBlwwzcTDFwWSyWWcTAZcMM3EwxcFksllnEwGXDDNxMMXBZLJZZxMBlwwzcTDFwWSyWWcTAZcMM3EwxcFksllnEwGXDDNxMMXBZLJZZxMBlwwzcTDFwWSyWWcTAZcMM3EwxcFksllnEwGXDDNxMMXBZLJZZxMBlwwzcTDFwWaxcaFCLWdSzwRTh0KBodTNLIQYuG2bgZoqBy2LlQlxL2XgxcNkwAzdTDFwWKxdi4BovBi4bZuBmioHLYuVCDFzjxcBlwwzcTDFwWaxciIFrvBi4bJiBmykGLouVCzFwjVfhAm5xct/ehE6draqnjW1OM3AzxcBlsXIhBq7xKizA9Q/8hNq2bU1WVlZUqlQpGjuuFUXHPqvpxzaPGbiZYuCyWLkQA9d4FTRwz1+oTn36dKTixYtTmzZtyNHRkaZNm0bVqlWjN954ndatb6I5hm16M3AzxcBlsXIhBq7xKijgxsQ/SzNndaHKlStT7dq1acGCBeTt7a3zzp07acCAASLabdr0Awo4kP/3aElm4GaKgcti5UIMXONVEMDdsqU9vftuXSpbtiyNHTuWfHx8yNfXV6/d3Nyoffv2VKxYMerRoyWdu1hJcz72k5uBmykGLouVCzFwjVd+AvfwkYb0/fdtBTx//fVXEcXu3bvXKK9atUqC9LsSpMvQ9BktKTqulOb87LybgZspBi6LlQsxcI1XfgD3UkQV+uvvTmJ4+JNPPqHNmzdTQEBAru3v709TpkyhylUqU506NcjZ5UPNtdh5MwM3UwxcFisXYuAaL3MCNzbhGVq46LfHCVBvkL29Pe3fv/+JvWfPHurVq5dItPqybRM6eryB5trZ+fAkaypmZSWyoYWtrci2uBVVqGZNjb+xoXHr7OhCQvbHWZeypikn)

---

**Oharra:** Irudien edukia mantendu egin da jatorrizko formatuan, base64 kodeketa delako. Irudi honek sarearen topologia fisikoa erakusten du.

## 3. Sare Segmentazioa

### VLAN Banaketa

| VLAN ID | Izena | IP Tartea | Helburua |
|---------|-------|-----------|----------|
| 10 | Bulegoa | 192.168.10.0/24 | Langileen ekipoak |
| 20 | Zerbitzariak | 192.168.20.0/24 | Aplikazio eta datu-base zerbitzariak |
| 30 | DMZ | 192.168.30.0/24 | Publikoki eskuragarri dauden zerbitzuak |
| 40 | OT/SCADA | 192.168.40.0/24 | Industria kontrol sistemak |
| 99 | Honeypot | 172.16.99.0/24 | Isolatutako honeypot sarea |

### Firewall Arau Nagusiak

```bash
# Internet → DMZ (HTTP/HTTPS soilik)
allow tcp any to DMZ port 80,443

# DMZ → Barne Sarea (debekatua)
deny any DMZ to LAN

# DMZ → Zerbitzariak (mugatua)
allow tcp DMZ to SERVERS port 5432 (PostgreSQL)
allow tcp DMZ to SERVERS port 6379 (Redis)

# Barne Sarea → Internet (HTTP/HTTPS)
allow tcp LAN to any port 80,443

# OT Sarea → Internet (debekatua)
deny any OT to any

# OT Sarea → Barne Sarea (debekatua)
deny any OT to LAN

# Barne Sarea → OT Sarea (SCADA sarbide soilik)
allow tcp LAN to OT port 502 (Modbus TCP)
```

## 4. Segurtasun Neurriak

### Sare Mailan

* **Firewall:** Fortinet FortiGate 200F
* **IDS/IPS:** Suricata/Snort integratua
* **VLAN Isolamendua:** Sare segmentazio zorrotza
* **Port Security:** MAC helbideen eta portuen lotura

### Sistema Mailan

* **Antivirus:** ClamAV / Sophos
* **Host Firewall:** iptables/ufw gaituta
* **Logak:** Rsyslog bidez zentralizatuta SIEM-era
* **Monitorizazioa:** Nagios/Zabbix/Wazuh

### Aplikazio Mailan

* **HTTPS:** TLS 1.3 edo altuena
* **HSTS:** HTTP Strict Transport Security gaituta
* **CSP:** Content Security Policy konfiguratuta
* **CSRF Token-ak:** Cross-Site Request Forgery babesa

## 5. Kontaktua eta Laguntza

Segurtasun gertaera bat detektatzen bada, jarri harremanetan:

* **Segurtasun Taldea:** security@zabala.gailetak.eus
* **24/7 Larrialdiak:** +34 600 XXX XXX
* **SIEM Alertak:** SOC@zabala.gailetak.eus
