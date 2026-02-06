@echo off
echo Creating directories...
cd /d "c:\Users\idz60\Downloads\erronka4-main\erronka4-main\Zabala Gailetak\hr-portal\public\views"
mkdir payslips 2>nul
mkdir documents 2>nul
echo.
echo Directories created!
echo.
echo Now creating view files...
echo.

echo Creating payslips/index.php...
echo ^<?php require dirname(__DIR__) . '/layouts/header.php'; ?^> > payslips\index.php
echo. >> payslips\index.php
echo ^<div class="dashboard-container"^> >> payslips\index.php
echo     ^<!-- See full content in SETUP_INSTRUCTIONS.md --^> >> payslips\index.php
echo ^</div^> >> payslips\index.php
echo. >> payslips\index.php
echo ^<?php require dirname(__DIR__) . '/layouts/footer.php'; ?^> >> payslips\index.php

echo Creating payslips/show.php...
echo ^<?php require dirname(__DIR__) . '/layouts/header.php'; ?^> > payslips\show.php
echo. >> payslips\show.php
echo ^<div class="dashboard-container"^> >> payslips\show.php
echo     ^<!-- See full content in SETUP_INSTRUCTIONS.md --^> >> payslips\show.php
echo ^</div^> >> payslips\show.php
echo. >> payslips\show.php
echo ^<?php require dirname(__DIR__) . '/layouts/footer.php'; ?^> >> payslips\show.php

echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Created:
echo   - payslips/ directory
echo   - documents/ directory
echo   - payslips/index.php (placeholder)
echo   - payslips/show.php (placeholder)
echo.
echo IMPORTANT: Replace the placeholder content in:
echo   - payslips/index.php
echo   - payslips/show.php
echo.
echo With the full content from SETUP_INSTRUCTIONS.md
echo.
echo Press any key to exit...
pause >nul
