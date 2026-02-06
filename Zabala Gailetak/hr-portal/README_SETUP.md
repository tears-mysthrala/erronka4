# Quick Setup Script - Creates all necessary files and directories

## Instructions:
1. Open Command Prompt (cmd)
2. Navigate to: cd "c:\Users\idz60\Downloads\erronka4-main\erronka4-main\Zabala Gailetak\hr-portal"
3. Run: SETUP.bat

OR

1. Open PowerShell
2. Navigate to: cd "c:\Users\idz60\Downloads\erronka4-main\erronka4-main\Zabala Gailetak\hr-portal"
3. Run: powershell -ExecutionPolicy Bypass -File SETUP.ps1

The scripts will:
- Create payslips/ and documents/ directories
- Create the view files with full content
- Show success message

## Manual Alternative:

If scripts don't work, use Windows Explorer:

1. Navigate to: c:\Users\idz60\Downloads\erronka4-main\erronka4-main\Zabala Gailetak\hr-portal\public\views

2. Create two new folders:
   - Right-click → New → Folder → Name it "payslips"
   - Right-click → New → Folder → Name it "documents"

3. Inside the "payslips" folder, create two new text files:
   - Right-click → New → Text Document → Rename to "index.php"
   - Right-click → New → Text Document → Rename to "show.php"

4. Copy the content from SETUP_INSTRUCTIONS.md:
   - Open SETUP_INSTRUCTIONS.md
   - Find section "PAYSLIPS INDEX VIEW"
   - Copy all the PHP code
   - Paste into payslips/index.php
   - Repeat for "PAYSLIPS SHOW VIEW" → payslips/show.php

5. Save files and test:
   - Visit http://localhost:8080/payslips

## File Locations:
- SETUP.bat - Windows batch script (simple, no admin needed)
- SETUP.ps1 - PowerShell script (more advanced, extracts content automatically)
- SETUP_INSTRUCTIONS.md - Complete manual with all file contents

Choose whichever method works best for you!
