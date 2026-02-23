#!/bin/bash
################################################################################
# Zabala Gailetak - Markdown to PDF Converter
# Converts documentation files to professional PDF with company branding
################################################################################

set -e

# Colors for terminal output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Directories
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
TEMPLATE="$SCRIPT_DIR/templates/zabala-template.tex"
OUTPUT_DIR="$SCRIPT_DIR/pdf_exports"

# Ensure output directory exists
mkdir -p "$OUTPUT_DIR"

# Check dependencies
check_dependencies() {
    local deps=("pandoc" "xelatex" "lualatex")
    local missing=()
    
    for dep in "${deps[@]}"; do
        if ! command -v "$dep" &> /dev/null; then
            missing+=("$dep")
        fi
    done
    
    if [ ${#missing[@]} -ne 0 ]; then
        echo -e "${RED}Missing dependencies: ${missing[*]}${NC}"
        echo "Install with: sudo apt install pandoc texlive-xetex texlive-luatex texlive-fonts-recommended"
        exit 1
    fi
}

# Convert single file
convert_file() {
    local input_file="$1"
    local output_name="$2"
    local title="$3"
    
    local output_file="$OUTPUT_DIR/${output_name}.pdf"
    
    echo -e "${BLUE}Converting:${NC} $input_file"
    echo -e "${YELLOW}Output:${NC} $output_file"
    
    pandoc "$input_file" \
        --pdf-engine=xelatex \
        --template="$TEMPLATE" \
        --variable title="$title" \
        --variable toc=true \
        --variable fontsize=11pt \
        --variable geometry:margin=2.5cm \
        --highlight-style=tango \
        --listings \
        -o "$output_file"
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ Success:${NC} $output_file"
        return 0
    else
        echo -e "${RED}✗ Failed:${NC} $input_file"
        return 1
    fi
}

# Main documentation files to convert
main() {
    echo -e "${BLUE}========================================${NC}"
    echo -e "${BLUE}  Zabala Gailetak - MD to PDF Converter${NC}"
    echo -e "${BLUE}========================================${NC}"
    echo ""
    
    check_dependencies
    
    local success=0
    local failed=0
    
    # Core documentation
    echo -e "${YELLOW}Converting Core Documentation...${NC}"
    
    if [ -f "$SCRIPT_DIR/PROJECT_DOCUMENTATION.md" ]; then
        convert_file "$SCRIPT_DIR/PROJECT_DOCUMENTATION.md" \
            "ZG_01_Proiektu_Dokumentazioa" \
            "Proiektuaren Dokumentazio Osoa" && ((success++)) || ((failed++))
    fi
    
    if [ -f "$SCRIPT_DIR/security_plan.md" ]; then
        convert_file "$SCRIPT_DIR/security_plan.md" \
            "ZG_02_Segurtasun_Plana" \
            "Segurtasun Plana" && ((success++)) || ((failed++))
    fi
    
    if [ -f "$SCRIPT_DIR/sop_secure_development.md" ]; then
        convert_file "$SCRIPT_DIR/sop_secure_development.md" \
            "ZG_03_Garapen_Seguruaren_SOP" \
            "Garapen Seguruaren Prozedura" && ((success++)) || ((failed++))
    fi
    
    if [ -f "$SCRIPT_DIR/COSTES_RECURSOS_IMPLEMENTACION.md" ]; then
        convert_file "$SCRIPT_DIR/COSTES_RECURSOS_IMPLEMENTACION.md" \
            "ZG_04_Kostuak_eta_Baliabideak" \
            "Kostuen eta Baliabideen Analisia" && ((success++)) || ((failed++))
    fi
    
    # Security documentation
    echo ""
    echo -e "${YELLOW}Converting Security Documentation...${NC}"
    
    local SECURITY_DIR="$SCRIPT_DIR/../security"
    
    if [ -f "$SECURITY_DIR/pentesting/reports/penetration_test_report_2026.md" ]; then
        convert_file "$SECURITY_DIR/pentesting/reports/penetration_test_report_2026.md" \
            "ZG_05_Pentest_Txostena_2026" \
            "Penetration Testing Txostena 2026" && ((success++)) || ((failed++))
    fi
    
    if [ -f "$SECURITY_DIR/audits/sop_ethical_hacking.md" ]; then
        convert_file "$SECURITY_DIR/audits/sop_ethical_hacking.md" \
            "ZG_06_Hacking_Etikoa_SOP" \
            "Hacking Etikoaren Prozedura" && ((success++)) || ((failed++))
    fi
    
    if [ -f "$SECURITY_DIR/siem/siem_strategy.md" ]; then
        convert_file "$SECURITY_DIR/siem/siem_strategy.md" \
            "ZG_07_SIEM_Estrategia" \
            "SIEM Estrategia eta Implementazioa" && ((success++)) || ((failed++))
    fi
    
    if [ -f "$SECURITY_DIR/web_hardening_sop.md" ]; then
        convert_file "$SECURITY_DIR/web_hardening_sop.md" \
            "ZG_08_Web_Gotortze_SOP" \
            "Web Aplikazioen Gotortzea" && ((success++)) || ((failed++))
    fi
    
    if [ -f "$SECURITY_DIR/incidents/sop_incident_response.md" ]; then
        convert_file "$SECURITY_DIR/incidents/sop_incident_response.md" \
            "ZG_09_Gertaera_Erantzun_SOP" \
            "Gertaera Segurtasunaren Erantzuna" && ((success++)) || ((failed++))
    fi
    
    if [ -f "$SECURITY_DIR/forensics/sop_evidence_collection.md" ]; then
        convert_file "$SECURITY_DIR/forensics/sop_evidence_collection.md" \
            "ZG_10_Ebidentzia_Bilketa_SOP" \
            "Ebidentzia Digitalen Bilketa" && ((success++)) || ((failed++))
    fi
    
    # Compliance documentation
    echo ""
    echo -e "${YELLOW}Converting Compliance Documentation...${NC}"
    
    local COMPLIANCE_DIR="$SCRIPT_DIR/../compliance"
    
    if [ -f "$COMPLIANCE_DIR/compliance_plan.md" ]; then
        convert_file "$COMPLIANCE_DIR/compliance_plan.md" \
            "ZG_11_Betekuntza_Plana" \
            "Betekuntza Plana" && ((success++)) || ((failed++))
    fi
    
    if [ -f "$COMPLIANCE_DIR/gdpr/data_processing_register.md" ]; then
        convert_file "$COMPLIANCE_DIR/gdpr/data_processing_register.md" \
            "ZG_12_GDPR_Datu_Prozesamendu_Erregistroa" \
            "Datu Prozesamenduaren Erregistroa (GDPR)" && ((success++)) || ((failed++))
    fi
    
    if [ -f "$COMPLIANCE_DIR/gdpr/dpia_template.md" ]; then
        convert_file "$COMPLIANCE_DIR/gdpr/dpia_template.md" \
            "ZG_13_GDPR_DPIA_Txantiloia" \
            "Datuen Babesaren Eragin Ebaluazioa (DPIA)" && ((success++)) || ((failed++))
    fi
    
    # WiFi Pentest
    echo ""
    echo -e "${YELLOW}Converting WiFi Pentest Documentation...${NC}"
    
    if [ -f "$SECURITY_DIR/pentesting/wifi/README.md" ]; then
        convert_file "$SECURITY_DIR/pentesting/wifi/README.md" \
            "ZG_14_WiFi_Pentest_Gida" \
            "WiFi Pentesting Gida" && ((success++)) || ((failed++))
    fi
    
    # Summary
    echo ""
    echo -e "${BLUE}========================================${NC}"
    echo -e "${GREEN}Conversion Complete!${NC}"
    echo -e "${BLUE}========================================${NC}"
    echo -e "Successful: ${GREEN}$success${NC}"
    echo -e "Failed: ${RED}$failed${NC}"
    echo ""
    echo -e "PDF files saved to: ${YELLOW}$OUTPUT_DIR/${NC}"
    echo ""
    ls -lh "$OUTPUT_DIR/"/*.pdf 2>/dev/null | awk '{print $9, "(" $5 ")"}' || true
}

# Convert specific file
convert_single() {
    local input_file="$1"
    local title="${2:-$(basename "$input_file" .md)}"
    local output_name="$(basename "$input_file" .md)"
    
    if [ ! -f "$input_file" ]; then
        echo -e "${RED}File not found: $input_file${NC}"
        exit 1
    fi
    
    convert_file "$input_file" "$output_name" "$title"
}

# Show help
show_help() {
    echo "Usage: $0 [command] [options]"
    echo ""
    echo "Commands:"
    echo "  all              Convert all main documentation files (default)"
    echo "  single <file>    Convert a specific markdown file"
    echo "  list             List available markdown files"
    echo "  help             Show this help"
    echo ""
    echo "Examples:"
    echo "  $0                           # Convert all files"
    echo "  $0 single docs/security.md   # Convert specific file"
    echo "  $0 list                      # List available files"
}

# List available files
list_files() {
    echo -e "${BLUE}Available Markdown Files:${NC}"
    echo ""
    find "$SCRIPT_DIR/.." -name "*.md" -type f | grep -E "(docs|security|compliance)" | grep -v node_modules | sort | while read f; do
        echo "  - $(basename "$f")"
    done
}

# Main entry point
case "${1:-all}" in
    all)
        main
        ;;
    single)
        if [ -z "$2" ]; then
            echo -e "${RED}Error: Please specify a file${NC}"
            show_help
            exit 1
        fi
        check_dependencies
        convert_single "$2" "$3"
        ;;
    list)
        list_files
        ;;
    help|--help|-h)
        show_help
        ;;
    *)
        echo -e "${RED}Unknown command: $1${NC}"
        show_help
        exit 1
        ;;
esac
