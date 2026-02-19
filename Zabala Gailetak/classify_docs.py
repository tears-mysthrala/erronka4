#!/usr/bin/env python3
"""
Script para clasificar documentos MD por asignatura y detectar idioma
"""
import os
import re
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

# Palabras comunes en euskera para detectar el idioma
EUSKERA_KEYWORDS = [
    'eta', 'da', 'dira', 'ditu', 'dituzte', 'den', 'diren',
    'baina', 'ere', 'oso', 'hau', 'hori', 'sistema', 'segurtasun',
    'bidez', 'prozedura', 'erabiliz', 'egiten', 'duela', 'garatu',
    'euskaraz', 'izan', 'behar', 'berri', 'bat', 'batzuk',
    'informatikoa', 'kudeaketa', 'eguneratzea', 'erabiltzailea'
]

def detect_language(content):
    """Detecta si el contenido está principalmente en euskera"""
    content_lower = content.lower()
    euskera_count = sum(1 for word in EUSKERA_KEYWORDS if f' {word} ' in content_lower)

    # Si hay más de 5 palabras vascas, consideramos que está en euskera
    return 'euskera' if euskera_count >= 5 else 'other'

def classify_file(file_path, base_path):
    """Clasifica un archivo MD según su ruta y contenido"""
    rel_path = str(file_path.relative_to(base_path)).lower()

    # Buscar coincidencia con keywords
    scores = {}
    for subject, keywords in SUBJECT_MAPPING.items():
        score = sum(1 for kw in keywords if kw in rel_path)
        if score > 0:
            scores[subject] = score

    if scores:
        return max(scores.items(), key=lambda x: x[1])[0]

    return None

def main():
    base_path = Path('/home/kalista/erronkak/erronka4/Zabala Gailetak')
    md_files = list(base_path.rglob('*.md'))
    md_files = [f for f in md_files if 'vendor' not in str(f) and 'node_modules' not in str(f)]

    classification = {subject: {'euskera': [], 'other': []}
                     for subject in SUBJECT_MAPPING.keys()}
    classification['sin_clasificar'] = {'euskera': [], 'other': []}

    print(f"Analizando {len(md_files)} archivos...")

    for md_file in md_files:
        try:
            with open(md_file, 'r', encoding='utf-8') as f:
                content = f.read()

            subject = classify_file(md_file, base_path)
            language = detect_language(content)

            if subject:
                classification[subject][language].append(md_file)
            else:
                classification['sin_clasificar'][language].append(md_file)

        except Exception as e:
            print(f"Error procesando {md_file}: {e}")

    # Imprimir resultados
    print("\n" + "="*80)
    print("CLASIFICACIÓN DE DOCUMENTOS")
    print("="*80)

    for subject, files in classification.items():
        total = len(files['euskera']) + len(files['other'])
        if total > 0:
            print(f"\n{subject}:")
            print(f"  - Euskera: {len(files['euskera'])} archivos")
            print(f"  - Otros idiomas: {len(files['other'])} archivos")

            if files['euskera']:
                print("    Euskera:")
                for f in sorted(files['euskera'])[:5]:
                    print(f"      • {f.relative_to(base_path)}")
                if len(files['euskera']) > 5:
                    print(f"      ... y {len(files['euskera']) - 5} más")

            if files['other']:
                print("    Otros idiomas:")
                for f in sorted(files['other'])[:5]:
                    print(f"      • {f.relative_to(base_path)}")
                if len(files['other']) > 5:
                    print(f"      ... y {len(files['other']) - 5} más")

if __name__ == '__main__':
    main()
