#!/bin/bash
################################################################################
# Network Reconnaissance Script
# Zabala Gailetak - Hacking Etikoa
# Script de reconocimiento de red para pentesting autorizado
################################################################################

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Variables
TARGET=""
OUTPUT_DIR="./recon_results"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

# Funciones de utilidad
print_banner() {
    echo -e "${GREEN}"
    echo "╔══════════════════════════════════════════════════════════╗"
    echo "║     Network Reconnaissance - Zabala Gailetak            ║"
    echo "║     Uso exclusivo para hacking ético autorizado         ║"
    echo "╚══════════════════════════════════════════════════════════╝"
    echo -e "${NC}"
}

print_status() {
    echo -e "${GREEN}[+]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[!]${NC} $1"
}

print_error() {
    echo -e "${RED}[-]${NC} $1"
}

# Verificar dependencias
check_dependencies() {
    local deps=("nmap" "whois" "dig" "curl")
    for dep in "${deps[@]}"; do
        if ! command -v "$dep" &> /dev/null; then
            print_error "$dep no está instalado"
            exit 1
        fi
    done
    print_status "Dependencias verificadas"
}

# Crear directorio de salida
setup_output() {
    mkdir -p "$OUTPUT_DIR/$TARGET/$TIMESTAMP"
    OUTPUT_PATH="$OUTPUT_DIR/$TARGET/$TIMESTAMP"
    print_status "Resultados guardados en: $OUTPUT_PATH"
}

################################################################################
# FASE 1: RECONOCIMIENTO PASIVO
################################################################################

passive_recon() {
    print_banner
    print_status "Iniciando FASE 1: Reconocimiento Pasivo"
    
    # WHOIS
    print_status "Obteniendo información WHOIS..."
    whois "$TARGET" > "$OUTPUT_PATH/whois.txt" 2>/dev/null
    
    # DNS Records
    print_status "Consultando registros DNS..."
    {
        echo "=== A Records ==="
        dig A "$TARGET" +short
        echo -e "\n=== MX Records ==="
        dig MX "$TARGET" +short
        echo -e "\n=== NS Records ==="
        dig NS "$TARGET" +short
        echo -e "\n=== TXT Records ==="
        dig TXT "$TARGET" +short
        echo -e "\n=== CNAME Records ==="
        dig CNAME "$TARGET" +short
        echo -e "\n=== SOA Records ==="
        dig SOA "$TARGET" +short
    } > "$OUTPUT_PATH/dns_records.txt"
    
    # DNS Zone Transfer attempt
    print_status "Probando transferencia de zona DNS..."
    NS=$(dig NS "$TARGET" +short | head -1)
    dig AXFR "$TARGET" "@$NS" > "$OUTPUT_PATH/zone_transfer.txt" 2>/dev/null
    
    if [ -s "$OUTPUT_PATH/zone_transfer.txt" ]; then
        print_warning "Transferencia de zona exitosa! Vulnerabilidad encontrada."
    else
        print_status "Transferencia de zona no permitida (seguro)"
    fi
    
    # Subdomain enumeration (usando certificados)
    print_status "Buscando subdominios via certificados..."
    curl -s "https://crt.sh/?q=%.$TARGET&output=json" 2>/dev/null | \
        jq -r '.[].name_value' 2>/dev/null | sort -u > "$OUTPUT_PATH/subdomains_cert.txt"
    
    print_status "Reconocimiento pasivo completado"
}

################################################################################
# FASE 2: RECONOCIMIENTO ACTIVO
################################################################################

active_recon() {
    print_status "Iniciando FASE 2: Reconocimiento Activo"
    
    # Host discovery
    print_status "Descubriendo hosts activos..."
    nmap -sn -PE -PP -PM "$TARGET/24" -oN "$OUTPUT_PATH/host_discovery.txt" 2>/dev/null
    
    # Quick port scan
    print_status "Escaneo rápido de puertos..."
    nmap -sS -T4 --top-ports 100 "$TARGET" -oN "$OUTPUT_PATH/quick_scan.txt"
    
    # Full port scan
    print_status "Escaneo completo de puertos (puede tardar)..."
    nmap -sS -p- -T4 -v "$TARGET" -oN "$OUTPUT_PATH/full_scan.txt"
    
    # Service detection
    print_status "Detectando servicios y versiones..."
    nmap -sV -sC -O -A -T4 "$TARGET" -oN "$OUTPUT_PATH/service_scan.txt"
    
    # Vulnerability scan
    print_status "Buscando vulnerabilidades conocidas..."
    nmap --script vuln -T4 "$TARGET" -oN "$OUTPUT_PATH/vuln_scan.txt"
    
    print_status "Reconocimiento activo completado"
}

################################################################################
# FASE 3: ENUMERACIÓN ESPECÍFICA
################################################################################

specific_enum() {
    print_status "Iniciando FASE 3: Enumeración específica"
    
    # HTTP/HTTPS enumeration
    if nmap -p80,443 --open "$TARGET" | grep -q "open"; then
        print_status "Enumerando servicios web..."
        
        # HTTP headers
        curl -I -s "http://$TARGET" > "$OUTPUT_PATH/http_headers.txt"
        curl -I -s -k "https://$TARGET" >> "$OUTPUT_PATH/http_headers.txt"
        
        # Technologies
        whatweb "$TARGET" > "$OUTPUT_PATH/whatweb.txt" 2>/dev/null
        
        # Directory enumeration (common)
        print_status "Enumerando directorios comunes..."
        nmap --script http-enum -p80,443 "$TARGET" -oN "$OUTPUT_PATH/http_enum.txt"
        
        # Robots.txt
        curl -s "http://$TARGET/robots.txt" > "$OUTPUT_PATH/robots.txt"
    fi
    
    # SMB enumeration
    if nmap -p445 --open "$TARGET" | grep -q "open"; then
        print_status "Enumerando SMB..."
        nmap --script smb-enum-shares,smb-enum-users -p445 "$TARGET" -oN "$OUTPUT_PATH/smb_enum.txt"
    fi
    
    # SNMP enumeration
    if nmap -p161 --open "$TARGET" | grep -q "open"; then
        print_status "Enumerando SNMP..."
        nmap --script snmp-info -p161 "$TARGET" -oN "$OUTPUT_PATH/snmp_enum.txt"
    fi
    
    # SSH enumeration
    if nmap -p22 --open "$TARGET" | grep -q "open"; then
        print_status "Enumerando SSH..."
        nmap --script ssh-hostkey,ssh-auth-methods -p22 "$TARGET" -oN "$OUTPUT_PATH/ssh_enum.txt"
    fi
    
    print_status "Enumeración específica completada"
}

################################################################################
# FASE 4: GENERAR REPORTE
################################################################################

generate_report() {
    print_status "Generando reporte consolidado..."
    
    REPORT="$OUTPUT_PATH/FINAL_REPORT.txt"
    
    cat > "$REPORT" << EOF
================================================================================
REPORTE DE RECONOCIMIENTO - ZABALA GAILETAK
================================================================================
Fecha: $(date)
Objetivo: $TARGET
Autorización: PENTEST AUTORIZADO - Erronka 4
================================================================================

RESUMEN EJECUTIVO
-----------------
EOF

    # Contar hallazgos
    OPEN_PORTS=$(grep -c "open" "$OUTPUT_PATH/full_scan.txt" 2>/dev/null || echo "0")
    echo "Puertos abiertos encontrados: $OPEN_PORTS" >> "$REPORT"
    
    # Listar servicios
    echo -e "\nSERVICIOS DETECTADOS" >> "$REPORT"
    echo "--------------------" >> "$REPORT"
    grep -E "^[0-9]+/(tcp|udp)" "$OUTPUT_PATH/service_scan.txt" >> "$REPORT" 2>/dev/null
    
    # Vulnerabilidades
    echo -e "\nVULNERABILIDADES ENCONTRADAS" >> "$REPORT"
    echo "-----------------------------" >> "$REPORT"
    grep -E "(VULNERABLE|STATE: VULNERABLE)" "$OUTPUT_PATH/vuln_scan.txt" >> "$REPORT" 2>/dev/null || echo "Ninguna vulnerabilidad crítica detectada" >> "$REPORT"
    
    # Subdominios
    if [ -s "$OUTPUT_PATH/subdomains_cert.txt" ]; then
        echo -e "\nSUBDOMINIOS ENCONTRADOS" >> "$REPORT"
        echo "------------------------" >> "$REPORT"
        wc -l "$OUTPUT_PATH/subdomains_cert.txt" | awk '{print "Total: " $1}' >> "$REPORT"
    fi
    
    echo -e "\n================================================================================
FIN DEL REPORTE
================================================================================" >> "$REPORT"
    
    print_status "Reporte generado: $REPORT"
}

################################################################################
# MAIN
################################################################################

main() {
    # Verificar argumentos
    if [ $# -eq 0 ]; then
        echo "Uso: $0 <target>"
        echo "Ejemplo: $0 zabala-gailetak.eus"
        exit 1
    fi
    
    TARGET="$1"
    
    # Disclaimer
    echo -e "${YELLOW}"
    echo "ADVERTENCIA: Este script debe usarse solo para hacking ético autorizado."
    echo "El uso no autorizado de este script es ilegal."
    echo -e "${NC}"
    read -p "¿Tienes autorización escrita para escanear $TARGET? (s/N): " confirm
    
    if [[ ! "$confirm" =~ ^[Ss]$ ]]; then
        print_error "Operación cancelada. Se requiere autorización."
        exit 1
    fi
    
    # Verificar herramientas
    check_dependencies
    
    # Setup
    setup_output
    
    # Ejecutar fases
    passive_recon
    active_recon
    specific_enum
    generate_report
    
    # Resumen
    print_banner
    print_status "Reconocimiento completado exitosamente"
    print_status "Resultados en: $OUTPUT_PATH"
    print_status "Reporte final: $OUTPUT_PATH/FINAL_REPORT.txt"
}

main "$@"
