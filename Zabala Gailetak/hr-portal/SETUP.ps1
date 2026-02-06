# PowerShell Setup Script
# Run this with: powershell -ExecutionPolicy Bypass -File SETUP.ps1

Write-Host "Creating directories..." -ForegroundColor Cyan

$basePath = "c:\Users\idz60\Downloads\erronka4-main\erronka4-main\Zabala Gailetak\hr-portal\public\views"

# Create directories
New-Item -ItemType Directory -Path "$basePath\payslips" -Force | Out-Null
New-Item -ItemType Directory -Path "$basePath\documents" -Force | Out-Null

Write-Host "✓ Directories created" -ForegroundColor Green
Write-Host ""

# Read the full file contents from SETUP_INSTRUCTIONS.md
$setupInstructions = Get-Content ".\SETUP_INSTRUCTIONS.md" -Raw

# Extract the payslips index.php content
$indexStart = $setupInstructions.IndexOf("### PAYSLIPS INDEX VIEW")
$indexEnd = $setupInstructions.IndexOf("### PAYSLIPS SHOW VIEW")
if ($indexStart -gt 0 -and $indexEnd -gt $indexStart) {
    $indexContent = $setupInstructions.Substring($indexStart, $indexEnd - $indexStart)
    # Extract content between ```php and ``` markers
    $pattern = '```php\s*([\s\S]*?)\s*```'
    if ($indexContent -match $pattern) {
        $phpContent = $matches[1]
        Set-Content -Path "$basePath\payslips\index.php" -Value $phpContent -Encoding UTF8
        Write-Host "✓ Created payslips/index.php" -ForegroundColor Green
    }
}

# Extract the payslips show.php content
$showStart = $setupInstructions.IndexOf("### PAYSLIPS SHOW VIEW")
if ($showStart -gt 0) {
    $showContent = $setupInstructions.Substring($showStart)
    $pattern = '```php\s*([\s\S]*?)\s*```'
    if ($showContent -match $pattern) {
        $phpContent = $matches[1]
        Set-Content -Path "$basePath\payslips\show.php" -Value $phpContent -Encoding UTF8
        Write-Host "✓ Created payslips/show.php" -ForegroundColor Green
    }
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Yellow
Write-Host "Setup Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Yellow
Write-Host ""
Write-Host "Created:" -ForegroundColor Cyan
Write-Host "  - payslips/ directory"
Write-Host "  - documents/ directory"
Write-Host "  - payslips/index.php"
Write-Host "  - payslips/show.php"
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "  1. Start your server: php -S localhost:8080 -t public/"
Write-Host "  2. Visit: http://localhost:8080/payslips"
Write-Host ""
Write-Host "Press any key to continue..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
