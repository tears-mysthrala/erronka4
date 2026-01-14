# Zerbitzarien Gotortze (Hardening) Prozedura - Zabala Gailetak

## 1. Helburua

Zerbitzari eta sistema eragileen eraso-azalera murriztea, segurtasun konfigurazio optimoak ezarriz.

## 2. Irismena

Linux eta Windows zerbitzari guztiak (Fisikoak eta Birtualak).

## 3. Prozedura Orokorra

### 3.1 Instalazioa

* Erabili bakarrik onartutako irudi ofizialak.
* Partizioak bereizi: `/boot`, `/home`, `/var`, `/tmp` partizio banatuetan egon behar dira (Linux).

### 3.2 Erabiltzaileak eta Sarbideak

* **Root/Admin sarbidea:** Desgaitu root bidezko zuzeneko saioa SSHn (`PermitRootLogin no`).
* **Pasahitzak:** Gutxienez 12 karaktere, konplexutasunarekin. Aldaketa behartu 90 egunean behin.
* **Erabiltzaileak:** Ezabatu edo desgaitu beharrezkoak ez diren kontuak (`guest`, etab.).
* **Sudo:** Erabili `sudo` pribilegioak igotzeko, ez sartu `root` gisa.

### 3.3 Zerbitzuak eta Sareak

* **Minimizazioa:** Desaktibatu beharrezkoak ez diren zerbitzuak (FTP, Telnet, Print Spooler...).
* **SSH:** Aldatu portu lehenetsia (aukerakoa), erabili gako bidezko autentikazioa (Key-based) eta desgaitu pasahitzak.
* **Suebakia:** Konfiguratu `ufw` (Linux) edo Windows Firewall sarrerako trafiko guztia ukatzeko, beharrezkoa dena izan ezik.

### 3.4 Eguneraketak (Patch Management)

* Konfiguratu eguneraketa automatikoak segurtasun partxeetarako.
* Egiaztatu eguneraketak astean behin gutxienez.

### 3.5 Monitorizazioa eta Logak

* Aktibatu auditoretza logak (`auditd` Linuxen).
* Birbideratu logak SIEM zerbitzarira (Syslog bidez).
* Sinkronizatu erlojua NTP bidez (logen koherentziarako).

## 4. Egiaztapena

* Erabili tresnak gotortze maila neurtzeko (adib. Lynis Linuxen).
