#!/bin/bash

# Verify Previews Script for Android App
# This script checks that all main UI files have proper @Preview annotations

APP_PATH="app/src/main/java/com/zabalagailetak/hrapp/presentation"

echo "🔍 Verificando configuración de Previews..."
echo ""

# Array de archivos a verificar
FILES=(
    "auth/LoginScreen.kt"
    "dashboard/DashboardScreen.kt"
    "documents/DocumentsScreen.kt"
    "payslips/PayslipsScreen.kt"
    "profile/ProfileScreen.kt"
    "vacation/VacationDashboardScreen.kt"
    "vacation/NewVacationRequestScreen.kt"
)

# Contadores
total=0
with_preview=0
without_preview=0

for file in "${FILES[@]}"; do
    total=$((total + 1))
    full_path="$APP_PATH/$file"
    
    if [ -f "$full_path" ]; then
        if grep -q "@Preview" "$full_path"; then
            preview_count=$(grep -c "@Preview" "$full_path")
            echo "✅ $file"
            echo "   → Encontrados $preview_count preview(s)"
            with_preview=$((with_preview + 1))
        else
            echo "❌ $file"
            echo "   → No tiene configurado @Preview"
            without_preview=$((without_preview + 1))
        fi
    else
        echo "⚠️  $file - Archivo no encontrado"
    fi
done

echo ""
echo "═══════════════════════════════════════════════════"
echo "📊 Resumen de Previews:"
echo "   Total de archivos: $total"
echo "   ✅ Con previews: $with_preview"
echo "   ❌ Sin previews: $without_preview"
echo "   Cobertura: $(echo "scale=1; $with_preview * 100 / $total" | bc)%"
echo "═══════════════════════════════════════════════════"

if [ $without_preview -eq 0 ]; then
    echo ""
    echo "🎉 ¡Excelente! Todos los archivos tienen previews configurados"
    exit 0
else
    echo ""
    echo "⚠️  Algunos archivos necesitan previews. Usa la guía PREVIEWS_GUIDE.md"
    exit 1
fi
