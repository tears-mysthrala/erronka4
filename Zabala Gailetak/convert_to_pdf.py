#!/usr/bin/env python3
"""
Script para convertir archivos MD a PDF y organizarlos por asignatura
"""
import os
import shutil
import subprocess
from pathlib import Path

# Mapeo de rutas/keywords a asignaturas
SUBJECT_MAPPING = {
    'Auzitegi-analisi informatikoa': [
        'forensics', 'forense', 'auzitegi', 'analisi'
    ],
    'Ekoizpen seguruan jartzea': [
        'android-app', 'hr-portal', 'ci', 'cd', 'pipeline',
        'deployment', 'production', 'ekoizpen'
    ],
    'Zibersegurtasunaren arloko araudia': [
        'compliance', 'gdpr', 'iso27001', 'sgsi', 'nis2',
        'araudia', 'policy', 'dpo', 'dpia'
    ],
    'Hacking etikoa': [
        'pentesting', 'pentest', 'ethical', 'hacking',
        'audits', 'audit', 'vulnerability'
    ],
    'Sareak eta sistemak gotortzea': [
        'infrastructure', 'network', 'systems', 'ot', 'siem',
        'hardening', 'server', 'firewall', 'vpn', 'sareak',
        'honeypot', 'machinery'
    ],
    'Zibersegurtasun-gorabeherak': [
        'incidents', 'incident', 'soar', 'response',
        'gorabehera', 'breach', 'sop_incident'
    ]
}

def classify_file(file_path, base_path):
    """Clasifica un archivo MD según su ruta"""
    rel_path = str(file_path.relative_to(base_path)).lower()

    scores = {}
    for subject, keywords in SUBJECT_MAPPING.items():
        score = sum(1 for kw in keywords if kw in rel_path)
        if score > 0:
            scores[subject] = score

    if scores:
        return max(scores.items(), key=lambda x: x[1])[0]

    # Archivos generales van a todas las asignaturas o a "General"
    return 'General'

def convert_md_to_pdf_libreoffice(md_file, output_dir):
    """Convierte MD a PDF usando LibreOffice"""
    try:
        # Primero convertir MD a HTML
        html_file = output_dir / f"{md_file.stem}.html"

        # Convertir MD a HTML usando pandoc
        cmd_html = [
            'pandoc', str(md_file),
            '-o', str(html_file),
            '--standalone',
            '--metadata', f'title={md_file.stem}'
        ]
        subprocess.run(cmd_html, check=True, capture_output=True)

        # Luego convertir HTML a PDF usando LibreOffice
        pdf_file = output_dir / f"{md_file.stem}.pdf"
        cmd_pdf = [
            'libreoffice', '--headless',
            '--convert-to', 'pdf',
            '--outdir', str(output_dir),
            str(html_file)
        ]
        result = subprocess.run(cmd_pdf, capture_output=True, text=True)

        # Limpiar archivo HTML temporal
        if html_file.exists():
            html_file.unlink()

        if pdf_file.exists():
            return pdf_file
        else:
            print(f"  ✗ Error: {result.stderr[:100]}")
            return None

    except Exception as e:
        print(f"  ✗ Error convirtiendo {md_file.name}: {e}")
        return None

def main():
    base_path = Path('/home/kalista/erronkak/erronka4/Zabala Gailetak')
    entregables_path = base_path / 'entregables'

    # Buscar todos los MD
    md_files = list(base_path.rglob('*.md'))
    md_files = [f for f in md_files if 'vendor' not in str(f) and 'node_modules' not in str(f) and 'entregables' not in str(f)]

    print(f"Convirtiendo {len(md_files)} archivos MD a PDF...")
    print("="*80)

    stats = {subject: 0 for subject in SUBJECT_MAPPING.keys()}
    stats['General'] = 0
    errors = []

    for md_file in sorted(md_files):
        subject = classify_file(md_file, base_path)
        subject_dir = entregables_path / subject

        if not subject_dir.exists():
            subject_dir.mkdir(parents=True, exist_ok=True)

        print(f"\n{md_file.relative_to(base_path)}")
        print(f"  → {subject}")

        pdf_file = convert_md_to_pdf_libreoffice(md_file, subject_dir)

        if pdf_file:
            print(f"  ✓ {pdf_file.name}")
            stats[subject] += 1
        else:
            errors.append((md_file, subject))

    # Resumen
    print("\n" + "="*80)
    print("RESUMEN DE CONVERSIÓN")
    print("="*80)

    for subject, count in sorted(stats.items()):
        if count > 0:
            print(f"{subject}: {count} archivos convertidos")

    if errors:
        print(f"\nErrores: {len(errors)} archivos no se pudieron convertir")
        for md_file, subject in errors[:5]:
            print(f"  • {md_file.relative_to(base_path)}")
        if len(errors) > 5:
            print(f"  ... y {len(errors) - 5} más")

    print(f"\nTotal: {sum(stats.values())} PDFs creados")

if __name__ == '__main__':
    main()
