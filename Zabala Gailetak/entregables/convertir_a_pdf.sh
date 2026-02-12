#!/bin/bash
# Script para convertir documentos Markdown a PDF
# Requisitos: pandoc, wkhtmltopdf o xelatex

set -e

echo "========================================="
echo "Conversión de Entregables a PDF"
echo "Zabala Gailetak - Erronka 4"
echo "========================================="
echo ""

# Colores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Directorio base
BASE_DIR="$(dirname "$0")"
cd "$BASE_DIR"

# Detectar motor PDF
if command -v xelatex &> /dev/null; then
    PDF_ENGINE="xelatex"
    ENGINE_NAME="XeLaTeX (calidad profesional)"
elif command -v wkhtmltopdf &> /dev/null; then
    PDF_ENGINE="wkhtmltopdf"
    ENGINE_NAME="wkhtmltopdf"
else
    echo -e "${RED}ERROR: No se encontró motor PDF${NC}"
    echo "Instala uno de los siguientes:"
    echo "  - texlive-xetex (para xelatex)"
    echo "  - wkhtmltopdf"
    exit 1
fi

echo -e "Motor detectado: ${GREEN}$ENGINE_NAME${NC}"
echo ""

# Opciones comunes de pandoc
PANDOC_OPTS="-V geometry:margin=2.5cm \
             -V fontsize=11pt \
             -V colorlinks=true \
             -V linkcolor=blue \
             -V urlcolor=blue \
             -V toc=true \
             -V toc-depth=3 \
             --highlight-style=tango"

# Función para convertir archivo
convertir_md() {
    local input_file="$1"
    local output_file="$2"
    local nombre="$3"
    
    echo -n "Convirtiendo $nombre... "
    
    if pandoc -o "$output_file" \
              --pdf-engine="$PDF_ENGINE" \
              $PANDOC_OPTS \
              "$input_file" 2>/dev/null; then
        echo -e "${GREEN}✓ OK${NC}"
        return 0
    else
        echo -e "${RED}✗ ERROR${NC}"
        return 1
    fi
}

# Contador
TOTAL=0
EXITOS=0
FALLIDOS=0

# Crear directorio de salida
mkdir -p PDF_Exportados

# Convertir cada asignatura
convertir_asignatura() {
    local dir="$1"
    local nombre="$2"
    
    echo ""
    echo -e "${YELLOW}>>> $nombre${NC}"
    echo "----------------------------------------"
    
    for md_file in "$dir"/*.md; do
        if [ -f "$md_file" ]; then
            filename=$(basename "$md_file" .md)
            pdf_file="PDF_Exportados/${filename}.pdf"
            
            if convertir_md "$md_file" "$pdf_file" "$filename"; then
                ((EXITOS++))
            else
                ((FALLIDOS++))
            fi
            ((TOTAL++))
        fi
    done
}

# Convertir cada asignatura
convertir_asignatura "AAI_Auzitegi_Analisia" "AAI - Auzitegi Analisia"
convertir_asignatura "ESJ_Ekoizpen_Segurua" "ESJ - Ekoizpen Segurua"
convertir_asignatura "Hacking_Etikoa" "Hacking Etikoa"
convertir_asignatura "Sareak_Sistemak" "Sareak eta Sistemak"
convertir_asignatura "ZAA_Araudia" "ZAA - Araudia"
convertir_asignatura "ZG_Gorabeherak" "ZG - Gorabeherak"
convertir_asignatura "." "Proiektu Orokorra"

echo ""
echo "========================================="
echo -e "Conversión completada:"
echo -e "  ${GREEN}Exitosos: $EXITOS${NC}"
echo -e "  ${RED}Fallidos: $FALLIDOS${NC}"
echo -e "  Total: $TOTAL"
echo "========================================="
echo ""
echo "PDFs exportados a: PDF_Exportados/"
echo ""

if [ $FALLIDOS -gt 0 ]; then
    exit 1
else
    echo -e "${GREEN}✓ Todos los documentos convertidos correctamente${NC}"
    exit 0
fi
