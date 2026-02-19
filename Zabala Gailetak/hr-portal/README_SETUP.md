# Konfigurazio Script Azkarra - Sortzen ditu beharrezkoak diren fitxategi eta direktorio guztiak

## Argibideak:
1. Ireki Command Prompt (cmd)
2. Nabigatu hona: cd "c:\Users\idz60\Downloads\erronka4-main\erronka4-main\Zabala Gailetak\hr-portal"
3. Exekutatu: SETUP.bat

EDO

1. Ireki PowerShell
2. Nabigatu hona: cd "c:\Users\idz60\Downloads\erronka4-main\erronka4-main\Zabala Gailetak\hr-portal"
3. Exekutatu: powershell -ExecutionPolicy Bypass -File SETUP.ps1

Script-ek egingo dute:
- Sortu payslips/ eta documents/ direktorioak
- Sortu ikuspegi fitxategiak eduki osoarekin
- Erakutsi arrakasta mezua

## Eskuzko Aukera:

Script-ak ez badabil, erabili Windows Explorer:

1. Nabigatu hona: c:\Users\idz60\Downloads\erronka4-main\erronka4-main\Zabala Gailetak\hr-portal\public\views

2. Sortu bi karpeta berri:
   - Eskuineko klika → Berria → Karpeta → Izendatu "payslips"
   - Eskuineko klika → Berria → Karpeta → Izendatu "documents"

3. "payslips" karpetaren barruan, sortu bi testu fitxategi berri:
   - Eskuineko klika → Berria → Testu Dokumentua → Berrizendatu "index.php"
   - Eskuineko klika → Berria → Testu Dokumentua → Berrizendatu "show.php"

4. Kopiatu edukia SETUP_INSTRUCTIONS.md-tik:
   - Ireki SETUP_INSTRUCTIONS.md
   - Aurkitu "PAYSLIPS INDEX VIEW" atala
   - Kopiatu PHP kode guztia
   - Itsatsi payslips/index.php-n
   - Errepikatu "PAYSLIPS SHOW VIEW" → payslips/show.php-rako

5. Gorde fitxategiak eta probatu:
   - Bisitatu http://localhost:8080/payslips

## Fitxategi Kokapena:
- SETUP.bat - Windows batch script-a (sinplea, ez du admin behar)
- SETUP.ps1 - PowerShell script-a (aurreratuagoa, edukia automatikoki ateratzen du)
- SETUP_INSTRUCTIONS.md - Eskuliburu osoa fitxategi eduki guztiekin

Aukeratu zure beharretarako hobekien dabilena!
