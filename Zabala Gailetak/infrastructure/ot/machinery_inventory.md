# Makineria Inbentarioa - Zabala Gailetak (OT)

Fabrikako ekoizpen prozesua automatizatuta dago eta hainbat makina mota erabiltzen ditu. Segurtasun ikuspegitik, gailu hauek **OT Sarean** egon behar dute, IT saretik isolatuta.

## 1. Ekoizpen Lerroa

Prozesuaren faseen arabera:

### A. Nahasketa eta Prestaketa

* **Pisatzeko Sistemak:** Lehengaiak (irina, koipea, ura) dosifikatzeko sentsoreak eta kontroladoreak.
* **Oratzeko Makinak (Kneaders):**
  * *Mota:* PLC bidez kontrolatutako motor industrialak.
  * *Konektibitatea:* SCADA sistemara konektatuta parametroak monitorizatzeko.

### B. Formazioa

* **Laminazio Makinak:** Orea luzatzeko eta ijezteko arrabol motorizatuak.
* **Ebaketa Makinak:** Orea pieza txikietan zatitzeko trokelak (PLC bidez sinkronizatuta).

### C. Egostea

* **Labe Industrialak:**
  * *Kritikotasuna:* Oso altua. Tenperatura oker batek produktua hondatu edo sute arriskua sor dezake.
  * *Sentsoreak:* Tenperatura, hezetasuna, presioa.
  * *Aktuadoreak:* Erregailuak, aireztapen sistemak.

### D. Akabera

* **Bainatzeko Makinak:** Txokolatezko estaldura aplikatzeko.
  * *Kontrola:* Tenperatura (txokolatea likido mantentzeko) eta fluxua.
* **Enbalatzeko Robotak:** Amaitutako produktuak kaxetan sartzeko beso robotikoak.

## 2. Kontrol Sistemak eta Simulazioa

Proiektu honetan, ingurune erreala simulatzeko software espezifikoa erabiliko da:

* **Factory I/O:** Makina fisikoak (zinta garraiatzaileak, sentsoreak, beso robotikoak...) simulatzeko ingurune birtuala.
* **OpenPLC:** PLCak (Programmable Logic Controllers) simulatzeko softwarea. Factory I/O-ko gailuak kontrolatuko ditu Modbus edo antzeko protokoloen bidez.
* **HMI (Human Machine Interface):** Operadoreek makinak maneiatzeko panelak.
* **SCADA:** Fabrika osoaren ikuspegi orokorra eta datuen bilketa.

## 3. Segurtasun Oharrak (OT)

* **Isolamendua:** OT sarea ez da zuzenean Internetera konektatu behar.
* **Eguneraketak:** Firmware eguneraketak planifikatu eta probatu behar dira ekoizpena gelditu gabe.
* **Sarbide Fisikoa:** USB atakak eta armairuak fisikoki blokeatu behar dira.
