#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Zabala Gailetak — Entregableen PDF Sorgailua
Asignatura bakoitzeko dokumentu profesionalak sortzen ditu euskaraz.
"""

import os
from datetime import date
from reportlab.lib.pagesizes import A4
from reportlab.lib import colors
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.units import cm
from reportlab.platypus import (
    SimpleDocTemplate,
    Paragraph,
    Spacer,
    Table,
    TableStyle,
    PageBreak,
    HRFlowable,
    KeepTogether,
)
from reportlab.lib.enums import TA_CENTER, TA_LEFT, TA_JUSTIFY, TA_RIGHT
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont

# ── Koloreak (Zabala Gailetak marka-koloreak) ───────────────────────────────
COLOR_PRIMARY = colors.HexColor("#1B3A6B")  # Urdin iluna
COLOR_SECONDARY = colors.HexColor("#2E86AB")  # Urdin argia
COLOR_ACCENT = colors.HexColor("#E84855")  # Gorria
COLOR_LIGHT = colors.HexColor("#F0F4F8")  # Gris oso argia
COLOR_DARK = colors.HexColor("#1A1A2E")  # Beltza
COLOR_SUCCESS = colors.HexColor("#10B981")  # Berdea
COLOR_WARNING = colors.HexColor("#F59E0B")  # Horia
COLOR_GOLD = colors.HexColor("#D4A017")  # Urrezko

BASE_DIR = "/home/kalista/erronkak/erronka4/Zabala Gailetak/entregables"
TODAY = date.today().strftime("%Y-%m-%d")
YEAR = "2026"


# ── Estilo-sistema ───────────────────────────────────────────────────────────
def build_styles():
    base = getSampleStyleSheet()
    styles = {}

    styles["DocTitle"] = ParagraphStyle(
        "DocTitle",
        parent=base["Title"],
        fontSize=22,
        textColor=COLOR_PRIMARY,
        spaceAfter=6,
        fontName="Helvetica-Bold",
        alignment=TA_CENTER,
    )
    styles["DocSubtitle"] = ParagraphStyle(
        "DocSubtitle",
        parent=base["Normal"],
        fontSize=13,
        textColor=COLOR_SECONDARY,
        spaceAfter=4,
        fontName="Helvetica",
        alignment=TA_CENTER,
    )
    styles["CoverMeta"] = ParagraphStyle(
        "CoverMeta",
        parent=base["Normal"],
        fontSize=10,
        textColor=colors.gray,
        spaceAfter=2,
        fontName="Helvetica",
        alignment=TA_CENTER,
    )
    styles["H1"] = ParagraphStyle(
        "H1",
        parent=base["Heading1"],
        fontSize=16,
        textColor=COLOR_PRIMARY,
        spaceBefore=18,
        spaceAfter=8,
        fontName="Helvetica-Bold",
        borderPad=4,
        leftIndent=0,
    )
    styles["H2"] = ParagraphStyle(
        "H2",
        parent=base["Heading2"],
        fontSize=13,
        textColor=COLOR_SECONDARY,
        spaceBefore=12,
        spaceAfter=6,
        fontName="Helvetica-Bold",
    )
    styles["H3"] = ParagraphStyle(
        "H3",
        parent=base["Heading3"],
        fontSize=11,
        textColor=COLOR_DARK,
        spaceBefore=8,
        spaceAfter=4,
        fontName="Helvetica-Bold",
    )
    styles["Body"] = ParagraphStyle(
        "Body",
        parent=base["Normal"],
        fontSize=10,
        textColor=COLOR_DARK,
        spaceAfter=6,
        fontName="Helvetica",
        alignment=TA_JUSTIFY,
        leading=15,
    )
    styles["Bullet"] = ParagraphStyle(
        "Bullet",
        parent=base["Normal"],
        fontSize=10,
        textColor=COLOR_DARK,
        spaceAfter=3,
        fontName="Helvetica",
        leftIndent=18,
        bulletIndent=8,
        leading=14,
    )
    styles["Code"] = ParagraphStyle(
        "Code",
        parent=base["Code"],
        fontSize=8.5,
        textColor=colors.HexColor("#1F2937"),
        backColor=colors.HexColor("#F3F4F6"),
        fontName="Courier",
        spaceAfter=6,
        spaceBefore=4,
        leftIndent=12,
        rightIndent=12,
        borderPad=6,
        leading=13,
    )
    styles["Note"] = ParagraphStyle(
        "Note",
        parent=base["Normal"],
        fontSize=9,
        textColor=colors.HexColor("#374151"),
        backColor=colors.HexColor("#EFF6FF"),
        fontName="Helvetica-Oblique",
        leftIndent=12,
        rightIndent=12,
        spaceAfter=8,
        borderPad=6,
        leading=13,
    )
    styles["Warning"] = ParagraphStyle(
        "Warning",
        parent=base["Normal"],
        fontSize=9,
        textColor=colors.HexColor("#7C2D12"),
        backColor=colors.HexColor("#FEF3C7"),
        fontName="Helvetica-Bold",
        leftIndent=12,
        rightIndent=12,
        spaceAfter=8,
        borderPad=6,
        leading=13,
    )
    styles["TableHeader"] = ParagraphStyle(
        "TableHeader",
        parent=base["Normal"],
        fontSize=9,
        textColor=colors.white,
        fontName="Helvetica-Bold",
        alignment=TA_CENTER,
    )
    styles["TableCell"] = ParagraphStyle(
        "TableCell",
        parent=base["Normal"],
        fontSize=9,
        textColor=COLOR_DARK,
        fontName="Helvetica",
        leading=12,
    )
    styles["Footer"] = ParagraphStyle(
        "Footer",
        parent=base["Normal"],
        fontSize=8,
        textColor=colors.gray,
        alignment=TA_CENTER,
        fontName="Helvetica",
    )
    return styles


STYLES = build_styles()


# ── Elementu-eraikitzaileak ──────────────────────────────────────────────────
def h1(text):
    return Paragraph(text, STYLES["H1"])


def h2(text):
    return Paragraph(text, STYLES["H2"])


def h3(text):
    return Paragraph(text, STYLES["H3"])


def p(text):
    return Paragraph(text, STYLES["Body"])


def note(text):
    return Paragraph(f"<i>&#9432; {text}</i>", STYLES["Note"])


def warn(text):
    return Paragraph(f"<b>&#9888; {text}</b>", STYLES["Warning"])


def code(text):
    return Paragraph(text, STYLES["Code"])


def sp(h=0.3):
    return Spacer(1, h * cm)


def hr():
    return HRFlowable(width="100%", thickness=1, color=COLOR_SECONDARY, spaceAfter=6)


def hr_thin():
    return HRFlowable(width="100%", thickness=0.5, color=colors.lightgrey, spaceAfter=4)


def bullet(items):
    """Bullet zerrenda sortu."""
    result = []
    for item in items:
        result.append(Paragraph(f"&#8226;  {item}", STYLES["Bullet"]))
    return result


def table(data, col_widths=None, header_row=True):
    """Taula profesionala sortu."""
    if col_widths is None:
        n_cols = len(data[0])
        col_widths = [15.5 * cm / n_cols] * n_cols

    # Goiburua formateatu
    formatted = []
    for r_idx, row in enumerate(data):
        fmt_row = []
        for cell in row:
            if r_idx == 0 and header_row:
                fmt_row.append(Paragraph(str(cell), STYLES["TableHeader"]))
            else:
                fmt_row.append(Paragraph(str(cell), STYLES["TableCell"]))
        formatted.append(fmt_row)

    t = Table(formatted, colWidths=col_widths, repeatRows=1 if header_row else 0)
    style = TableStyle(
        [
            ("BACKGROUND", (0, 0), (-1, 0), COLOR_PRIMARY),
            ("ROWBACKGROUNDS", (0, 1), (-1, -1), [colors.white, COLOR_LIGHT]),
            ("GRID", (0, 0), (-1, -1), 0.5, colors.HexColor("#D1D5DB")),
            ("FONTNAME", (0, 0), (-1, -1), "Helvetica"),
            ("FONTSIZE", (0, 0), (-1, -1), 9),
            ("ALIGN", (0, 0), (-1, -1), "LEFT"),
            ("VALIGN", (0, 0), (-1, -1), "MIDDLE"),
            ("TOPPADDING", (0, 0), (-1, -1), 4),
            ("BOTTOMPADDING", (0, 0), (-1, -1), 4),
            ("LEFTPADDING", (0, 0), (-1, -1), 6),
            ("RIGHTPADDING", (0, 0), (-1, -1), 6),
        ]
    )
    t.setStyle(style)
    return t


def cover_block(title, subtitle, subject, doc_code, version="1.0"):
    """Azaleko blokea sortu."""
    elements = []
    elements.append(sp(3))
    # Marra goikoa
    elements.append(HRFlowable(width="100%", thickness=8, color=COLOR_PRIMARY))
    elements.append(sp(0.5))
    elements.append(
        Paragraph(
            "ZABALA GAILETAK, S.A.",
            ParagraphStyle(
                "Co",
                parent=STYLES["CoverMeta"],
                fontSize=12,
                textColor=COLOR_SECONDARY,
                fontName="Helvetica-Bold",
                alignment=TA_CENTER,
            ),
        )
    )
    elements.append(sp(0.3))
    elements.append(
        Paragraph(
            subject.upper(),
            ParagraphStyle(
                "Sub",
                parent=STYLES["CoverMeta"],
                fontSize=10,
                textColor=COLOR_ACCENT,
                fontName="Helvetica-Bold",
                alignment=TA_CENTER,
            ),
        )
    )
    elements.append(sp(1.5))
    elements.append(Paragraph(title, STYLES["DocTitle"]))
    elements.append(sp(0.5))
    elements.append(Paragraph(subtitle, STYLES["DocSubtitle"]))
    elements.append(sp(2))
    elements.append(
        HRFlowable(width="60%", thickness=2, color=COLOR_ACCENT, hAlign="CENTER")
    )
    elements.append(sp(1.5))

    meta = [
        ["Dokumentu Kodea:", doc_code],
        ["Bertsioa:", version],
        ["Data:", TODAY],
        ["Ikasturtea:", YEAR],
        ["Sailkapena:", "Heziketa — Barne Erabilera"],
        ["Egilea:", "Zabala Gailetak Zibersegurtasun Taldea"],
    ]
    meta_table = Table(meta, colWidths=[5 * cm, 10 * cm])
    meta_table.setStyle(
        TableStyle(
            [
                ("FONTNAME", (0, 0), (-1, -1), "Helvetica"),
                ("FONTSIZE", (0, 0), (-1, -1), 10),
                ("FONTNAME", (0, 0), (0, -1), "Helvetica-Bold"),
                ("TEXTCOLOR", (0, 0), (0, -1), COLOR_PRIMARY),
                ("TEXTCOLOR", (1, 0), (1, -1), COLOR_DARK),
                ("ALIGN", (0, 0), (-1, -1), "LEFT"),
                ("TOPPADDING", (0, 0), (-1, -1), 4),
                ("BOTTOMPADDING", (0, 0), (-1, -1), 4),
                ("ROWBACKGROUNDS", (0, 0), (-1, -1), [colors.white, COLOR_LIGHT]),
            ]
        )
    )
    elements.append(meta_table)
    elements.append(sp(1))
    elements.append(HRFlowable(width="100%", thickness=4, color=COLOR_SECONDARY))
    elements.append(PageBreak())
    return elements


def make_pdf(filepath, title, subtitle, subject, doc_code, build_content_fn):
    """PDF orokorra sortu."""
    os.makedirs(os.path.dirname(filepath), exist_ok=True)

    def on_page(canvas, doc):
        canvas.saveState()
        # Goiburua
        canvas.setFillColor(COLOR_PRIMARY)
        canvas.rect(0, A4[1] - 1.2 * cm, A4[0], 1.2 * cm, fill=1, stroke=0)
        canvas.setFillColor(colors.white)
        canvas.setFont("Helvetica-Bold", 8)
        canvas.drawString(1.5 * cm, A4[1] - 0.8 * cm, "ZABALA GAILETAK, S.A.")
        canvas.setFont("Helvetica", 8)
        canvas.drawRightString(A4[0] - 1.5 * cm, A4[1] - 0.8 * cm, subject)
        # Oina
        canvas.setFillColor(colors.HexColor("#E5E7EB"))
        canvas.rect(0, 0, A4[0], 1 * cm, fill=1, stroke=0)
        canvas.setFillColor(COLOR_PRIMARY)
        canvas.setFont("Helvetica", 7.5)
        canvas.drawString(
            1.5 * cm, 0.35 * cm, f"{title}  |  {doc_code}  |  Bertsioa 1.0  |  {TODAY}"
        )
        canvas.drawRightString(A4[0] - 1.5 * cm, 0.35 * cm, f"Orria {doc.page}")
        canvas.restoreState()

    doc = SimpleDocTemplate(
        filepath,
        pagesize=A4,
        topMargin=1.8 * cm,
        bottomMargin=1.5 * cm,
        leftMargin=2 * cm,
        rightMargin=2 * cm,
        title=title,
        author="Zabala Gailetak",
        subject=subject,
    )
    story = cover_block(title, subtitle, subject, doc_code) + build_content_fn()
    doc.build(story, onFirstPage=on_page, onLaterPages=on_page)
    print(f"  ✔  {os.path.basename(filepath)}")


# ═══════════════════════════════════════════════════════════════════════════════
# 1. AUZITEGI-ANALISI INFORMATIKOA
# ═══════════════════════════════════════════════════════════════════════════════


def build_auzitegi_nagusia():
    e = []
    e.append(h1("1. Sarrera eta Helburua"))
    e.append(
        p(
            "Txosten honek Zabala Gailetak-en auzitegi informatikoki hartu diren neurriak, erabilitako metodologiak eta lortutako emaitzak biltzen ditu. DFIR (Digital Forensics and Incident Response) printzipioen arabera egina dago, RFC 3227 estandarra eta ISO/IEC 27037 jarraibideak aintzat hartuz."
        )
    )
    e.append(sp())
    e.append(
        note(
            "Txosten hau heziketa-helburuetarako idatzia dago eta ez du epaiketa-prozesu erreal baten ebidentzia balioa."
        )
    )
    e.append(sp(0.5))

    e.append(h1("2. DFIR Metodologia"))
    e.append(p("Auzitegi-analisi informatikoak fase hauek ditu:"))
    e.extend(
        bullet(
            [
                "<b>Prestakuntza:</b> Tresnak, eskubideak eta bilketa-plan dokumentatua.",
                "<b>Identifikazioa:</b> Ebidentziak kokatu eta inbentariatu.",
                "<b>Kontserbazioa:</b> Irudi bit-mailakoak egin idazketa-blokatzaileekin.",
                "<b>Analisia:</b> Autopsy, Volatility, Wireshark eta konfigurazioa.",
                "<b>Dokumentazioa:</b> Aurkikuntza guztiak zaintza-katearekin erregistratu.",
                "<b>Txostena:</b> Tekniko eta exekutibo maila biko emaitzak.",
            ]
        )
    )
    e.append(sp(0.5))

    e.append(h2("2.1 Tresna-multzoa"))
    e.append(
        table(
            [
                ["Tresna", "Kategoria", "Erabilera Nagusia"],
                [
                    "Autopsy / TSK",
                    "Disko Analisia",
                    "Fitxategi-sistema aztertu, ezabatutakoak berreskuratu",
                ],
                [
                    "Volatility 3",
                    "Memoria Forentsea",
                    "RAM-dump aztertu, prozesuak, konexioak, malwarea",
                ],
                [
                    "Wireshark",
                    "Sare Analisia",
                    "PCAP captureak aztertu, protokoloak ebaluatu",
                ],
                [
                    "dc3dd / Guymager",
                    "Irudigintza",
                    "Bit-mailako kopia forensea egin SHA-256 egiaztapenarekin",
                ],
                [
                    "LiME / WinPmem",
                    "Memoria Bilketa",
                    "RAM memoria modu seguruan atera sistema piztuta",
                ],
                [
                    "YARA",
                    "Malware Detekzioa",
                    "Arau-oinarridun bilaketa fitxategi eta prozesuetan",
                ],
                [
                    "Sleuth Kit",
                    "Linea Kronologikoa",
                    "Fitxategi-sistema kronologia berreraiki",
                ],
                [
                    "RegRipper",
                    "Erregistroa",
                    "Windows Erregistroa analizatu, artefaktuak atera",
                ],
            ],
            col_widths=[3.5 * cm, 3.5 * cm, 8.5 * cm],
        )
    )
    e.append(sp(0.5))

    e.append(h1("3. Ebidentzien Bilketa Prozedura"))
    e.append(
        p(
            "RFC 3227 printzipioak: Hegakortasun ordenaren arabera bildu (RAM > Swap > Diskoa > Sarea)."
        )
    )

    e.append(h2("3.1 1. Fasea: Eszena Babestea"))
    e.extend(
        bullet(
            [
                "Gailua ingurutik isolatu (sare-kablea atera, WiFi itzali), baina EZ itzali oraindik.",
                "Argazkiak atera pantailari, fisikal konexioei eta inguruari.",
                "Pertsonal baimena ez duenaren sarbidea eragotzi.",
                "Denboraren erregistroa hasi (data/ordua UTC-n).",
            ]
        )
    )

    e.append(h2("3.2 2. Fasea: Datu Hegakorren Bilketa (Live Response)"))
    e.append(
        code(
            "# Linux — Datu hegakorren bilketa\n"
            "date && hostname && uname -a              # Sistema identifikatu\n"
            "ifconfig -a && netstat -anp               # Sare egoera\n"
            "ps auxf && lsof -nP                       # Prozesuak eta fitxategiak\n"
            "last && who && w                          # Saio aktiboa\n"
            "# RAM memoria atera — LiME moduluarekin\n"
            "insmod /media/usb/lime.ko 'path=/media/usb/ram.lime format=lime'"
        )
    )

    e.append(h2("3.3 3. Fasea: Disko Irudigintza"))
    e.append(
        code(
            "# Idazketa-blokatzailea konektatu aurretik\n"
            "dc3dd if=/dev/sdb hash=sha256 hof=/media/forense/disco_imagen.dd\n"
            "# Hashak egiaztatu\n"
            "sha256sum /dev/sdb > hashes_orig.txt\n"
            "sha256sum /media/forense/disco_imagen.dd > hashes_kopia.txt\n"
            "diff hashes_orig.txt hashes_kopia.txt && echo 'Irudia egiaztatu: OK'"
        )
    )

    e.append(h1("4. Memoria Forensea — Analisi Praktikoa"))
    e.append(
        p("Volatility 3 tresnarekin RAM-dump bat aztertu da sistema susmagarri batean:")
    )
    e.append(
        code(
            "# Profilak identifikatu\n"
            "vol.py -f ram.lime banners.Banners\n"
            "# Prozesu zuhaitza ikusi\n"
            "vol.py -f ram.lime windows.pstree.PsTree\n"
            "# Sareko konexioak\n"
            "vol.py -f ram.lime windows.netstat.NetStat\n"
            "# Injektatutako kodea bilatu\n"
            "vol.py -f ram.lime windows.malfind.Malfind\n"
            "# DLL-ak aztertu\n"
            "vol.py -f ram.lime windows.dlllist.DllList"
        )
    )

    e.append(h1("5. Zaintza Katea (Chain of Custody)"))
    e.append(
        p(
            "Ebidentzia bakoitzak zaintza-kate dokumentua izan behar du. Honako informazioa jaso behar da:"
        )
    )
    e.append(
        table(
            [
                ["Eremu", "Edukia"],
                ["Ebidentzia ID", "EV-2026-001"],
                [
                    "Deskribapena",
                    "Dell Latitude 5520 ordenagailu eramangarria, S/N: ABC123",
                ],
                ["Bilketa Data/Ordua", "2026-02-10 09:32 UTC"],
                ["Biltzailea", "Jon Etxeberria (Auzitegi Analista)"],
                ["Bilketa Lekua", "3. solairua, 302 bulegoa"],
                ["Biltegiratze Lekua", "Ebidentzia gela — armairua A3"],
                ["SHA-256 Hash", "a3f4b2c9d1e0f7a6b5c4d3e2f1a0b9c8..."],
                ["Azken Manipulazioa", "2026-02-10 14:15 — Analista: Jon Etxeberria"],
            ],
            col_widths=[6 * cm, 9.5 * cm],
        )
    )

    e.append(sp(0.5))
    e.append(h1("6. Ondorioak eta Aurkikuntzak"))
    e.append(
        p(
            "Zabala Gailetak-en aztertu diren sistemen auzitegi-analisian, ebidentziak biltzearen, analizatzearen eta dokumentatzearen metodologia egokia frogatu da. Tresna nagusiak (Autopsy, Volatility) eraginkortasunez erabiltzen dira jarduera susmagarriak identifikatzeko."
        )
    )
    e.extend(
        bullet(
            [
                "Memoria-analisian prozesu susmagarriak identifikatzeko gaitasuna frogatu da.",
                "Disko-irudigintzarako SHA-256 hashak ezinbesteko osagaia dira ebidentzia-osotasuna bermatzeko.",
                "Zaintza-katea dokumentuzko prozesu argi eta jarraituarekin ezarri da.",
                "YARA arauek malware-familia ezagunak azkar detektatzeko aukera ematen dute.",
            ]
        )
    )
    return e


def build_ebidentzia_sop():
    e = []
    e.append(h1("1. Helburua eta Esparrua"))
    e.append(
        p(
            "Prozedura honek ebidentzia digitalen bilketa, kudeaketa eta analisi metodologia ezartzen du Zabala Gailetak-en, ISO/IEC 27037:2012 eta RFC 3227 estandarren arabera."
        )
    )
    e.append(sp(0.5))
    e.append(h1("2. Erantzukizun-matrizea"))
    e.append(
        table(
            [
                ["Rola", "Erantzukizuna", "Sailkapena"],
                ["Auzitegi Analista", "Bilketa, irudigintza, analisia", "DFIR Taldea"],
                ["CISO", "Analisiaren onarpena eta komunikazioa", "Kudeaketa"],
                ["IT Arduraduna", "Laguntzaile teknikoa", "IT Taldea"],
                ["Legal Aholkularia", "Legez beteko erabileran gidaritza", "Juridikoa"],
                [
                    "Zuzendari Nagusia",
                    "Beharrezko baliabideen onarpena",
                    "Zuzendaritza",
                ],
            ]
        )
    )
    e.append(sp(0.5))
    e.append(h1("3. Bilketa Protokoloa — Urrats Zehatza"))
    steps = [
        (
            "0",
            "Deia jaso",
            "CISO edo zerbitzu-mahaiak gertaera komunikatu eta DFIR analista aktibatu.",
        ),
        (
            "1",
            "Eszena segurtatu",
            "Gailua isolatu, argazkiak atera, inguruaren kontrola hartu.",
        ),
        (
            "2",
            "Hegakorren bilketa",
            "RAM, sare-konexioak, prozesuak USB-tik exekutatzen diren tresnekin.",
        ),
        (
            "3",
            "Irudigintza",
            "dc3dd edo Guymager erabiliz bit-mailako kopia egin idazketa-blokatzaileekin.",
        ),
        (
            "4",
            "Hash egiaztapena",
            "SHA-256 hash kalkuatu eta dokumentatu — jatorrizkoa eta kopian berdinak.",
        ),
        (
            "5",
            "Etiketatze fisikoa",
            "Ontziak, diskoak eta gailua ID unikoaz etiketatu.",
        ),
        ("6", "Biltegi segurua", "Ebidentzia gela itxian gorde sarbide-kontrolarekin."),
        (
            "7",
            "Analisia hastea",
            "Irudiaren gainean bakarrik lan egin, ez jatorrizkoan.",
        ),
    ]
    e.append(
        table(
            [["Urratsa", "Ekintza", "Zehaztasuna"]]
            + [[s[0], s[1], s[2]] for s in steps],
            col_widths=[1.5 * cm, 3 * cm, 11 * cm],
        )
    )
    e.append(sp(0.5))
    e.append(h1("4. Debeku Absolutuak"))
    e.append(
        warn(
            "DEBEKATUA: Sistemak INOIZ ez itzali ebidentziak bildu aurretik (RAM galdu egingo litzateke). Ez instalatu ezer sistema helburuan. Ez exekutatu antibirusik bertan."
        )
    )
    e.append(sp(0.3))
    e.append(h1("5. Tresnen Inbentarioa (USB Forensea)"))
    e.extend(
        bullet(
            [
                "LiME.ko — Linux kernel modulua RAM bilketarako",
                "WinPmem.exe — Windows RAM bilketarako",
                "dc3dd / Guymager — Irudigintza egiaztapenekin",
                "FTK Imager (Windows) — Disko irudigintza alternatiboa",
                "Volatility 3 — Memoria analisia",
                "Autopsy 4.x — Disko analisia GUI-rekin",
            ]
        )
    )
    return e


def gen_auzitegi():
    base = f"{BASE_DIR}/Auzitegi-analisi informatikoa"
    subject = "Auzitegi-analisi Informatikoa"
    make_pdf(
        f"{base}/ZG_Auzitegi_Analisi_Txosten_Nagusia.pdf",
        "Auzitegi-analisi Informatikoa — Txosten Nagusia",
        "DFIR Metodologia, Tresnak eta Emaitzak",
        subject,
        "DFIR-ZG-001",
        build_auzitegi_nagusia,
    )
    make_pdf(
        f"{base}/ZG_Ebidentzia_Bilketa_SOP.pdf",
        "Ebidentzia Digitalen Bilketa Prozedura (SOP)",
        "ISO/IEC 27037 eta RFC 3227 arabera",
        subject,
        "DFIR-ZG-002",
        build_ebidentzia_sop,
    )


# ═══════════════════════════════════════════════════════════════════════════════
# 2. EKOIZPEN SEGURUAN JARTZEA
# ═══════════════════════════════════════════════════════════════════════════════


def build_ssdlc():
    e = []
    e.append(h1("1. Sarrera — DevSecOps eta SSDLC"))
    e.append(
        p(
            "Software Garapen Bizi-ziklo Segurua (SSDLC) segurtasuna diseinutik kodearen hedapena arte integratzen dituen metodologia da. Zabala Gailetak-en DevSecOps kultura ezartzea da helburu nagusia: segurtasuna 'shift left' eginez, akatsak goizago eta merkeago konpontzeko."
        )
    )
    e.append(sp(0.3))

    e.append(h2("1.1 Formalki ezarritako faseak"))
    e.append(
        table(
            [
                ["Fasea", "Ekintza Nagusia", "Tresnak"],
                [
                    "Plangintza",
                    "Arriskuen analisia, eskakizun seguroak",
                    "Threat Modeling, STRIDE",
                ],
                [
                    "Diseinua",
                    "Arkitektura segurua, Defense in Depth",
                    "OWASP TM / LINDDUN",
                ],
                [
                    "Garapena",
                    "Coding seguruak, ez hardcoded credentials",
                    "SonarLint, ESLint, PHPStan",
                ],
                ["Testing", "SAST, DAST, SCA, Pentest", "SonarQube, OWASP ZAP, Snyk"],
                [
                    "Berrikuspena",
                    "Pull Request code review segurtasun-fokua",
                    "GitHub / GitLab PR checks",
                ],
                [
                    "Hedapena",
                    "IaC, konfigurazio gogortu, sekretu kudeaketa",
                    "Ansible, Vault, Docker Bench",
                ],
                [
                    "Eragiketak",
                    "SIEM, monitoreo, adabaki kudeaketa",
                    "ELK + Wazuh, Dependabot",
                ],
            ],
            col_widths=[3 * cm, 6.5 * cm, 6 * cm],
        )
    )
    e.append(sp(0.5))

    e.append(h1("2. Threat Modeling — STRIDE Metodologia"))
    e.append(
        p("Aplikazio berrirako STRIDE mehatxu-modelizazioa egin da diseinu fasean:")
    )
    e.append(
        table(
            [
                ["Mehatxu Mota", "Deskribapena", "Kontrol Neurria"],
                [
                    "S — Spoofing (Iruzurra)",
                    "Besteen identitatea hartu",
                    "MFA + JWT sinadura",
                ],
                ["T — Tampering (Manipulazioa)", "Datuak modifikatu", "HTTPS + HMAC"],
                ["R — Repudiation (Ukatzea)", "Ekintzak ukatu", "Audit log sinatu"],
                [
                    "I — Info Disclosure",
                    "Datu sentikorrak filtratu",
                    "Enkriptazioa AES-256",
                ],
                ["D — Denial of Service", "Zerbitzu etena", "Rate limiting + CDN"],
                [
                    "E — Elevation of Privilege",
                    "Baimenik gabeko pribilegioaren eskuratzea",
                    "RBAC + Least Privilege",
                ],
            ],
            col_widths=[4 * cm, 5.5 * cm, 6 * cm],
        )
    )
    e.append(sp(0.5))

    e.append(h1("3. CI/CD Pipeline Segurua"))
    e.append(
        p(
            "Zabala Gailetak-en CI/CD pipeline segurua GitHub Actions eta Docker-ekin ezarri da:"
        )
    )
    e.append(
        code(
            "# .github/workflows/security.yml (laburpena)\n"
            "jobs:\n"
            "  sast:\n"
            "    runs-on: ubuntu-latest\n"
            "    steps:\n"
            "      - uses: actions/checkout@v4\n"
            "      - name: SonarQube SAST Analisia\n"
            "        uses: SonarSource/sonarqube-scan-action@master\n"
            "  sca:\n"
            "    steps:\n"
            "      - name: Snyk SCA Dependentzia Azterketa\n"
            "        run: snyk test --severity-threshold=high\n"
            "  dast:\n"
            "    steps:\n"
            "      - name: OWASP ZAP DAST Eskaneatzea\n"
            "        uses: zaproxy/action-full-scan@v0.9.0"
        )
    )
    e.append(sp(0.5))

    e.append(h1("4. Docker Kontainer Segurtasuna"))
    e.append(h2("4.1 Dockerfile Zergatik Printzipioak"))
    e.extend(
        bullet(
            [
                "Oinarri-irudi minimoak erabili: <code>alpine</code> edo <code>distroless</code>.",
                "Root izateko EZ exekutatu: <code>USER appuser</code> ezarri.",
                "Gehitu <code>.dockerignore</code> fitxategia kode sentikorra kanpoan uzteko.",
                "Irudian sekreturik EZ gorde — erabili Docker Secrets edo Vault.",
                "Irudi guztiak Trivy-z eskaneatu push egin aurretik.",
            ]
        )
    )
    e.append(
        code(
            "# Dockerfile segurua — adibidea\n"
            "FROM php:8.4-fpm-alpine                    # Oinarri txikia\n"
            "RUN addgroup -S appgroup && adduser -S appuser -G appgroup\n"
            "COPY --chown=appuser:appgroup . /app\n"
            "USER appuser                                # Ez erabili root\n"
            "HEALTHCHECK CMD curl -f http://localhost/ || exit 1"
        )
    )
    e.append(sp(0.5))

    e.append(h1("5. Ekoizpeneko Konfigurazio Segurua"))
    e.append(h2("5.1 HTTPS eta TLS 1.3"))
    e.append(
        code(
            "# Nginx TLS konfigurazioa — zati segurua\n"
            "ssl_protocols TLSv1.3;                     # TLS 1.0/1.1/1.2 desgaitu\n"
            "ssl_ciphers 'TLS_AES_256_GCM_SHA384:TLS_CHACHA20_POLY1305_SHA256';\n"
            "ssl_prefer_server_ciphers off;\n"
            "add_header Strict-Transport-Security 'max-age=63072000; includeSubDomains; preload';\n"
            "add_header X-Content-Type-Options nosniff;\n"
            "add_header X-Frame-Options DENY;\n"
            "add_header Content-Security-Policy \"default-src 'self'\";"
        )
    )
    e.append(sp(0.5))
    e.append(h2("5.2 Sekretu Kudeaketa"))
    e.extend(
        bullet(
            [
                "HashiCorp Vault erabili sekretu guztiak (DB pasahitzak, API gakoak) gordetzeko.",
                ".env fitxategiak <b>INOIZ EZ</b> commit egin Git-era.",
                "CI/CD-n GitHub Secrets erabili ingurune-aldagaietarako.",
                "Sekretuak errotatu aldian-aldian (90 egunero gutxienez).",
            ]
        )
    )
    return e


def gen_ekoizpen():
    base = f"{BASE_DIR}/Ekoizpen seguruan jartzea"
    subject = "Ekoizpen Seguruan Jartzea"
    make_pdf(
        f"{base}/ZG_SSDLC_DevSecOps_Txostena.pdf",
        "SSDLC eta DevSecOps — Garapen Segurua",
        "Software Garapen Bizi-ziklo Seguruaren Inplementazioa",
        subject,
        "EKOK-ZG-001",
        build_ssdlc,
    )


# ═══════════════════════════════════════════════════════════════════════════════
# 3. ZIBERSEGURTASUNAREN ARLOKO ARAUDIA
# ═══════════════════════════════════════════════════════════════════════════════


def build_araudia_nagusia():
    e = []
    e.append(h1("1. Sarrera — Arauei-esparru Orokorra"))
    e.append(
        p(
            "Zabala Gailetak S.A. enpresak honako lege eta arau-esparru hauei jarraitu behar die: ISO/IEC 27001:2022 (SGSI), GDPR/DBLO (datu-babesa), NIS2 Direktiba (sare eta informazio-sistemen segurtasuna) eta IEC 62443 (industria-kontrol sistemen segurtasuna)."
        )
    )
    e.append(sp(0.3))

    e.append(h1("2. ISO/IEC 27001:2022 — SGSI"))
    e.append(
        p(
            "Informazioaren Segurtasuna Kudeatzeko Sisteman (SGSI) 93 kontrol daude A Eranskinean. Zabala Gailetak-ek 87 kontrol (%93) inplementatu ditu 2026ko otsailean:"
        )
    )
    e.append(
        table(
            [
                [
                    "Kontrol Arloa",
                    "Total",
                    "Inplementatua",
                    "Partzialki",
                    "Ez aplikag.",
                ],
                ["A.5 — Antolakuntza (37)", "37", "35", "2", "0"],
                ["A.6 — Pertsonak (8)", "8", "8", "0", "0"],
                ["A.7 — Fisikoa (14)", "14", "13", "1", "0"],
                ["A.8 — Teknologia (34)", "34", "30", "4", "0"],
                ["<b>GUZTIRA</b>", "<b>93</b>", "<b>86</b>", "<b>7</b>", "<b>0</b>"],
            ],
            col_widths=[6 * cm, 2 * cm, 3 * cm, 3 * cm, 1.5 * cm],
        )
    )
    e.append(sp(0.3))
    e.append(
        note(
            "Kontrol partzialak (7): A.5.12, A.5.13, A.7.7, A.8.11, A.8.12, A.8.14 — 2026 Q2rako osatzeko planifikatuta."
        )
    )
    e.append(sp(0.5))

    e.append(h2("2.1 PDCA Hobetzeko Ziklo Jarraitua"))
    e.extend(
        bullet(
            [
                "<b>Plan:</b> Arriskuen ebaluazioa, kontrol aukeraketa, tratamendu-plana.",
                "<b>Do:</b> Kontrolak inplementatu, langileak trebatu, dokumentatu.",
                "<b>Check:</b> Barne-auditoriak, SIEM metrikak, zuzendaritzaren berrikuspena.",
                "<b>Act:</b> Ez-betetzerako ekintza zuzentzaileak, etengabeko hobekuntza.",
            ]
        )
    )
    e.append(sp(0.5))

    e.append(h1("3. GDPR — Datu Babeseko Araudia"))
    e.append(
        p(
            "Arau Orokor Europarraren (2016/679) arabera, Zabala Gailetak-ek langile eta bezeroen datu pertsonalak prozesatzen ditu. Bete beharreko eskakizunak:"
        )
    )
    e.append(
        table(
            [
                ["GDPR Eskakizuna", "Egoera", "Ebidentzia"],
                [
                    "ROPA (Prozesatze Erregistroa)",
                    "✅ Egin",
                    "data_processing_register.md",
                ],
                ["DPIA — HR Portal", "✅ Egin", "dpia_rrhh_portal_completed.md"],
                ["DPIA — SCADA/OT", "✅ Egin", "dpia_scada_ot_completed.md"],
                ["DPO Izendapena", "✅ Egin", "dpo_izendapena.md"],
                ["Cookie Politika", "✅ Egin", "cookie_policy.md"],
                [
                    "Datu-subjektuen eskubideak SOP",
                    "✅ Egin",
                    "data_subject_rights_procedures.md",
                ],
                [
                    "72h Haustura Jakinarazpena SOP",
                    "✅ Egin",
                    "gdpr_breach_response_sop.md",
                ],
                ["Pribatutasun Oharra", "✅ Egin", "privacy_notice_web.md"],
                ["Diseinuzko Pribatutasuna", "✅ Egin", "privacy_by_design.md"],
            ],
            col_widths=[6.5 * cm, 2.5 * cm, 6.5 * cm],
        )
    )
    e.append(sp(0.5))

    e.append(h1("4. NIS2 Direktiba (EU 2022/2555)"))
    e.append(
        p(
            "NIS2k erakundeak 10 neurri-kategoria inplementatzera behartzen ditu. Zabala Gailetak-ek sektore elikagarrian aritzen denez, 'Beharrezko Erakunde' gisa sailkatu daiteke:"
        )
    )
    e.append(
        table(
            [
                ["NIS2 Art. 21 Kategoria", "Egoera", "Ohar"],
                [
                    "21.2.a — Arrisku-analisia eta politika",
                    "✅",
                    "risk_assessment.md + ISP-001",
                ],
                [
                    "21.2.b — Intzidentzien kudeaketa",
                    "⏳ %80",
                    "SOP + CSIRT roster eginda",
                ],
                [
                    "21.2.c — Negozio jarraitutasuna",
                    "✅",
                    "BCP + DR Plan (RTO 4h, RPO 1h)",
                ],
                [
                    "21.2.d — Hornidura-kate segurtasuna",
                    "✅",
                    "supplier_security_register.md",
                ],
                ["21.2.e — Erosketa segurua", "✅", "POP-015 SSDLC"],
                [
                    "21.2.f — Eraginkortasun neurketak",
                    "⏳ %60",
                    "KPI dashboard planifikatua",
                ],
                [
                    "21.2.g — Ziberhigiene eta trebakuntza",
                    "✅",
                    "sop_security_awareness.md",
                ],
                ["21.2.h — Kriptografia", "✅", "POP-014 + TLS 1.3 + AES-256"],
                [
                    "21.2.i — Giza baliabideen segurtasuna",
                    "✅",
                    "langile_hautaketa + HR SOPak",
                ],
                ["21.2.j — MFA eta SAO komunikazioak", "✅", "JWT + TOTP + WebAuthn"],
            ],
            col_widths=[7 * cm, 2 * cm, 6.5 * cm],
        )
    )
    e.append(sp(0.5))

    e.append(h1("5. IEC 62443 — Industria Kontrol Sistemen Segurtasuna"))
    e.append(
        p(
            "Zabala Gailetak-en OT (Teknologia Operatiboa) ingurune industrialean PLC, SCADA eta HMI sistemak daude. IEC 62443-3-3 estandarrak Security Level (SL) 2 eskatzen du:"
        )
    )
    e.append(
        table(
            [
                ["SR Eskakizuna", "Deskribapena", "Egoera"],
                ["SR 1.1", "Erabiltzaile autentifikazioa (IAC)", "✅ RBAC + 2FA"],
                ["SR 2.1", "Baimen betearazpena", "✅ Least Privilege"],
                ["SR 3.1", "Malware babesa", "⏳ Antivirus OTn"],
                ["SR 5.1", "Sare segmentazioa", "✅ VLAN 50 isolatua"],
                ["SR 5.2", "Gune segmentazioa", "✅ IT/OT banaketa"],
                ["SR 6.1", "Audit log eskuragarritasuna", "✅ SIEM — Wazuh"],
                ["SR 7.1", "DoS babesa", "⏳ Rate limiting"],
            ]
        )
    )
    return e


def gen_araudia():
    base = f"{BASE_DIR}/Zibersegurtasunaren arloko araudia"
    subject = "Zibersegurtasunaren Arloko Araudia"
    make_pdf(
        f"{base}/ZG_Araudia_Betekuntza_Txostena_Nagusia.pdf",
        "Zibersegurtasunaren Arloko Araudia — Betekuntza Txostena",
        "ISO 27001 · GDPR · NIS2 · IEC 62443",
        subject,
        "ARAU-ZG-001",
        build_araudia_nagusia,
    )


# ═══════════════════════════════════════════════════════════════════════════════
# 4. HACKING ETIKOA
# ═══════════════════════════════════════════════════════════════════════════════


def build_pentest():
    e = []
    e.append(h1("1. Txostenaren Laburpen Exekutiboa"))
    e.append(
        p(
            "Penetration test hau Zabala Gailetak-en IT azpiegituraren segurtasun-egoera ebaluatzeko egin da, erakunde berak emandako idatzizko baimenarekin (Rules of Engagement). Helburu nagusia IT-OT azpiegituran ahultasunak aurkitzea da, haiek ustiatu aurretik."
        )
    )
    e.append(
        table(
            [
                ["Parametroa", "Xehetasuna"],
                ["Test Mota", "Grisa (Gray Box) — sarbide partzialarekin"],
                ["Esparrua", "Web aplikazioa, VPN, WiFi korporatiboa, sare perimetroa"],
                ["Metodologia", "PTES + OWASP Testing Guide v4.2 + NIST SP 800-115"],
                ["Iraupena", "2026-01-20 / 2026-02-05 (2 aste)"],
                ["Taldearen nagusia", "Mikel Etxebarria (OSCP, CISSP)"],
                [
                    "Ahultzeen kopurua",
                    "Kritikoak: 2 | Altuak: 5 | Ertainak: 8 | Baxuak: 12",
                ],
            ],
            col_widths=[4 * cm, 11.5 * cm],
        )
    )
    e.append(sp(0.5))

    e.append(h1("2. Metodologia eta Fase Nagusiak"))
    e.append(
        table(
            [
                ["Fasea", "Ekintzak", "Iraupena"],
                [
                    "1. Errekonozimendu",
                    "OSINT, DNS enumeration, Shodan, LinkedIn",
                    "2 egun",
                ],
                ["2. Eskaneatzea", "Nmap, Nessus, Nikto, gobuster", "2 egun"],
                ["3. Ustiatzea", "Metasploit, Burp Suite, SQLmap, Hydra", "5 egun"],
                [
                    "4. Post-exploitation",
                    "Pribilegioaren igoera, lateral movement",
                    "2 egun",
                ],
                ["5. Txostena", "Aurkikuntzak, ebaluazioa, gomendioak", "3 egun"],
            ]
        )
    )
    e.append(sp(0.5))

    e.append(h1("3. Aurkikuntza Nagusiak"))
    e.append(h2("3.1 Ahultasun Kritikoak"))
    e.append(
        warn(
            "KRITIKOA: CVE-2025-1234 — SQL Injection web aplikazioan. Erabiltzaile taulara baimenik gabe sartu daiteke."
        )
    )
    e.append(
        table(
            [
                ["CVE / ID", "Deskribapena", "CVSS", "Egoera"],
                [
                    "CVE-2025-1234",
                    "SQL Injection — /api/employees/search",
                    "9.8",
                    "🔴 Konpondu",
                ],
                [
                    "CVE-2025-5678",
                    "Authenticated RCE — zahartu liburutegi",
                    "9.0",
                    "🔴 Konpondu",
                ],
                [
                    "PEN-007",
                    "Broken Access Control — admin panel",
                    "8.1",
                    "🟠 Konpontzen",
                ],
                [
                    "PEN-012",
                    "Insecure Direct Object Reference (IDOR)",
                    "7.5",
                    "🟠 Konpontzen",
                ],
                [
                    "PEN-018",
                    "Default credentials — WiFi AP admin",
                    "7.2",
                    "🟡 Planifikatua",
                ],
            ],
            col_widths=[3 * cm, 7 * cm, 1.5 * cm, 4 * cm],
        )
    )
    e.append(sp(0.5))

    e.append(h1("4. OWASP Top 10 Azterketa — Zabala Gailetak"))
    e.append(
        table(
            [
                ["OWASP Kategoria", "Egoera", "Kontrola"],
                [
                    "A01 — Broken Access Control",
                    "⚠️ AURKITU",
                    "RBAC indartu, IDOR konpondu",
                ],
                ["A02 — Cryptographic Failures", "✅ Ongi", "TLS 1.3, AES-256"],
                [
                    "A03 — Injection (SQLi, XSS, SSTI)",
                    "⚠️ AURKITU",
                    "Parameterized queries ezarri",
                ],
                ["A04 — Insecure Design", "✅ Ongi", "Threat Modeling eginda"],
                [
                    "A05 — Security Misconfiguration",
                    "⚠️ Partziala",
                    "Debug itzali, headers gehitu",
                ],
                [
                    "A06 — Vulnerable Components",
                    "⚠️ AURKITU",
                    "Snyk SCA — 3 lib zahartu",
                ],
                ["A07 — Auth & Session Failures", "✅ Ongi", "JWT + TOTP MFA"],
                ["A08 — Data Integrity Failures", "✅ Ongi", "SRI + npm audit"],
                ["A09 — Logging & Monitoring Failures", "✅ Ongi", "SIEM + Wazuh"],
                ["A10 — SSRF", "✅ Ongi", "URL filtraketa ezarrita"],
            ]
        )
    )
    e.append(sp(0.5))

    e.append(h1("5. Esplotazio Adibideak"))
    e.append(h2("5.1 SQL Injection — Aurkikuntza eta Konponketa"))
    e.append(
        code(
            "# Jatorrizko kode ahula (PHP)\n"
            "$query = \"SELECT * FROM employees WHERE id = \" . $_GET['id'];\n\n"
            "# Explotazioa (sqlmap)\n"
            "sqlmap -u 'https://hr.zabala-gailetak.eus/api/employees/search?id=1' --dbs\n\n"
            "# Konpondutako kodea — Prepared Statements\n"
            "$stmt = $pdo->prepare('SELECT * FROM employees WHERE id = ?');\n"
            "$stmt->execute([$_GET['id']]);"
        )
    )

    e.append(h1("6. Gomendioak eta Ekintza Plana"))
    e.append(p("Ahultasun kritikoak eta altuak konpontzeko prioritate-zerrenda:"))
    e.extend(
        bullet(
            [
                "<b>Berehala (&lt;7 egun):</b> SQL Injection patch-a —prepared statements erabili.",
                "<b>Berehala (&lt;7 egun):</b> RCE ahultasuna duen liburutegia eguneratu.",
                "<b>Epe laburra (&lt;30 egun):</b> Access control berrikusi eta IDOR konpondu.",
                "<b>Epe laburra (&lt;30 egun):</b> WiFi AP-en pasahitz lehenetsiak aldatu.",
                "<b>Epe ertaina (&lt;90 egun):</b> SCA tresna CI/CD-n integratu dependentzia zahartuak auto-detektatzeko.",
                "<b>Etengabe:</b> Urteroko penetration testing kanpoko auditoreekin.",
            ]
        )
    )
    return e


def gen_hacking():
    base = f"{BASE_DIR}/Hacking etikoa"
    subject = "Hacking Etikoa"
    make_pdf(
        f"{base}/ZG_Penetration_Test_Txostena_2026.pdf",
        "Penetration Testing Txostena 2026",
        "IT Azpiegituran Gray-Box Pentest Emaitzak",
        subject,
        "HACK-ZG-001",
        build_pentest,
    )


# ═══════════════════════════════════════════════════════════════════════════════
# 5. SAREAK ETA SISTEMAK GOTORTZEA
# ═══════════════════════════════════════════════════════════════════════════════


def build_sare_gotortze():
    e = []
    e.append(h1("1. Sare Arkitektura eta Segmentazioa"))
    e.append(
        p(
            "Zabala Gailetak-ek Zero Trust printzipioan oinarritutako sare-arkitektura du, lau VLAN nagusirekin eta IT/OT banaketa zorrotzekin:"
        )
    )
    e.append(
        table(
            [
                ["VLAN", "Izena", "Sareko Helbidea", "Helburua", "Babesbabesa"],
                [
                    "VLAN 10",
                    "Kudeaketa",
                    "10.10.10.0/24",
                    "Zerbitzari admin sarbidea",
                    "Restriczio gogorrak, VPN soilik",
                ],
                [
                    "VLAN 20",
                    "IT/Enpresa",
                    "10.10.20.0/24",
                    "Langile ordenagailuak, aplikazioak",
                    "Firewall arau zorrotzak",
                ],
                [
                    "VLAN 30",
                    "DMZ",
                    "10.10.30.0/24",
                    "Web zerbitzaria, DNS, HAProxy",
                    "Kanpotik ikusgai, baina mugatua",
                ],
                [
                    "VLAN 50",
                    "OT/Industriala",
                    "10.10.50.0/24",
                    "PLC, SCADA, HMI gailuak",
                    "AIR-GAP, IT-tik guztiz isolatua",
                ],
            ],
            col_widths=[1.5 * cm, 2.5 * cm, 3.5 * cm, 5 * cm, 4 * cm],
        )
    )
    e.append(sp(0.5))

    e.append(h2("1.1 Firewall Arau Politika (Zerowireless First)"))
    e.append(
        code(
            "# pfSense — Oinarrizko arau-zerrenda (laburpena)\n"
            "# VLAN 50 (OT) → VLAN 20 (IT): DENY ALL  [IT/OT bereiztzeko]\n"
            "# VLAN 20 (IT) → VLAN 30 (DMZ): ALLOW dport 443, 80\n"
            "# Kanpoa  → VLAN 30 (DMZ): ALLOW dport 443\n"
            "# VLAN 10 → GUZTIA: ALLOW (kudeaketa sarbidea)\n"
            "# Default: DENY ALL — ez onartutakoa blokeatu"
        )
    )
    e.append(sp(0.5))

    e.append(h1("2. Zerbitzari Gotortzea (Server Hardening)"))
    e.append(h2("2.1 Linux Zerbitzariak — CIS Benchmark L2"))
    e.extend(
        bullet(
            [
                "Sistema eguneratuak mantendu: <code>apt update &amp;&amp; apt upgrade -y</code> astero.",
                "SSH root sarbidea desgaitu: <code>PermitRootLogin no</code> sshd_config-en.",
                "SSH pasahitz autentifikazioa desgaitu: <code>PasswordAuthentication no</code> — gako publikoak soilik.",
                "fail2ban instalatu SSH brute force erasoen aurkako babeserako.",
                "Beharrezkoak ez diren zerbitzuak desgaitu: <code>systemctl disable</code>.",
                "UFW suebakia konfiguratu: beharrezko portuak soilik ireki.",
                "auditd ezarri sistema-deiak eta fitxategi kritikoen aldaketak erregistratzeko.",
            ]
        )
    )
    e.append(
        code(
            "# SSH konfigurazio segurua (/etc/ssh/sshd_config)\n"
            "PermitRootLogin no\n"
            "PasswordAuthentication no\n"
            "MaxAuthTries 3\n"
            "LoginGraceTime 30\n"
            "AllowUsers sysadmin deploy\n"
            "Protocol 2\n"
            "Ciphers chacha20-poly1305@openssh.com,aes256-gcm@openssh.com"
        )
    )
    e.append(sp(0.5))

    e.append(h1("3. Web Aplikazioaren Gotortzea"))
    e.append(h2("3.1 Nginx Segurtasun Goiburuak"))
    e.append(
        code(
            "# Nginx — Segurtasun goiburuak\n"
            "add_header Strict-Transport-Security 'max-age=63072000; includeSubDomains; preload' always;\n"
            "add_header X-Content-Type-Options 'nosniff' always;\n"
            "add_header X-Frame-Options 'DENY' always;\n"
            "add_header X-XSS-Protection '1; mode=block' always;\n"
            "add_header Referrer-Policy 'strict-origin-when-cross-origin' always;\n"
            "add_header Permissions-Policy 'geolocation=(), midi=(), camera=()' always;\n"
            "add_header Content-Security-Policy \"default-src 'self'; script-src 'self' 'nonce-{NONCE}'\" always;\n"
            "server_tokens off;  # Nginx bertsioa ezkutatu"
        )
    )
    e.append(sp(0.5))

    e.append(h1("4. SIEM — ELK + Wazuh Inplementazioa"))
    e.append(
        p(
            "Security Information and Event Management (SIEM) sistema zentralizatua ELK Stack eta Wazuh-ekin ezarri da:"
        )
    )
    e.append(
        table(
            [
                ["Osagaia", "Rola", "Portua"],
                ["Elasticsearch", "Log biltegiratze eta bilaketa", "9200, 9300"],
                ["Logstash", "Log bilketa eta eraldaketa (ETL)", "5044, 5000"],
                ["Kibana", "Dashboard eta bistaratze tresna", "5601"],
                ["Wazuh Manager", "IDS/IPS + FIM + aktibo monitoreoa", "1514, 1515"],
                ["Wazuh Agents", "Zerbitzari bakoitzean instalatua", "1514 UDP"],
                ["Filebeat", "Log fitxategien bidalketa", "5044"],
            ]
        )
    )
    e.append(sp(0.3))
    e.append(h2("4.1 Alerta Arau Nagusiak"))
    e.append(
        table(
            [
                ["Araua", "Trigerra", "Larritasuna"],
                [
                    "Brute Force SSH",
                    "5 huts saiakera &lt; 1 min IP beretik",
                    "🔴 Kritikoa",
                ],
                ["Privilege Escalation", "sudo root komandoak", "🔴 Kritikoa"],
                ["FIM — /etc/passwd", "Fitxategi aldaketa", "🔴 Kritikoa"],
                ["Port Scan", "&gt;50 portu &lt; 30 seg IP beretik", "🔴 Kritikoa"],
                ["SQLi saiakera", "Nginx access log — UNION SELECT", "🔴 Kritikoa"],
                ["New Admin User", "/etc/group aldaketa — sudo taldea", "🟠 Altua"],
                ["Zaharkitu softw.", "CVE datu-basea — CVSS &gt; 8.0", "🟠 Altua"],
            ]
        )
    )
    e.append(sp(0.5))

    e.append(h1("5. OT Segurtasuna — IEC 62443 Gotortze Neurriak"))
    e.append(p("IT/OT bereizpena derrigorrezkoa da fabrika ingurune seguruetarako:"))
    e.extend(
        bullet(
            [
                "VLAN 50 (OT) guztiz isolatua — IT sarea eta OT sarea zuzenki konektatuta egon gabe.",
                "Unidirectional Security Gateway (data diode) OT telemtria IT SIEM-era bidaltzeko.",
                "USB portuen blokeo fisikoa PLCetan — badminton-en bakarrik SSP prozedurak.",
                "PLC sarbidea Engineers Only MAC whitelist-arekin.",
                "Honeypot Conpot gailua OT sarean mehatxu detekziorako.",
                "OT gailuetarako adabaki-kudeaketa: ekoizpen-geldialdietan soilik, probatuta.",
            ]
        )
    )
    return e


def gen_sareak():
    base = f"{BASE_DIR}/Sareak eta sistemak gotortzea"
    subject = "Sareak eta Sistemak Gotortzea"
    make_pdf(
        f"{base}/ZG_Sare_Sistemen_Gotortze_Txostena.pdf",
        "Sareak eta Sistemak Gotortzea — Txosten Nagusia",
        "Sare Segmentazioa · Hardening · SIEM · OT Segurtasuna",
        subject,
        "HARD-ZG-001",
        build_sare_gotortze,
    )


# ═══════════════════════════════════════════════════════════════════════════════
# 6. ZIBERSEGURTASUN-GORABEHERAK
# ═══════════════════════════════════════════════════════════════════════════════


def build_gorabeherak():
    e = []
    e.append(h1("1. Intzidentziei Erantzuteko Plana (IEP)"))
    e.append(
        p(
            "NIST SP 800-61 eta ISO/IEC 27035 estandarren arabera, Zabala Gailetak-en Intzidentziei Erantzuteko Planak sei fase ditu:"
        )
    )
    e.append(
        table(
            [
                ["Fasea", "Ekintzak", "Erantzule"],
                [
                    "1. Prestakuntza",
                    "Tresnak, eskumenak, trebakuntza, plan dokumentatua",
                    "CISO + CSIRT",
                ],
                [
                    "2. Detekzioa",
                    "SIEM alerta, erabiltzaile txostena, honeypot",
                    "SOC / IT",
                ],
                [
                    "3. Mugatzea",
                    "Sistema isolatu, sarbideak eten, backup babestu",
                    "IT + CSIRT",
                ],
                [
                    "4. Desagerraraztea",
                    "Erro-kausa kendu, sistema garbitu, patch-ak",
                    "IT + CISO",
                ],
                [
                    "5. Berrestea",
                    "Sistema itzuli ekoizpenera, egiaztapenak egin",
                    "IT + Negozio",
                ],
                [
                    "6. Ikaskuntzak",
                    "Txostena, prozesua hobetu, agintariei jakinarazi",
                    "CISO + Leg.",
                ],
            ]
        )
    )
    e.append(sp(0.5))

    e.append(h1("2. Intzidentzien Sailkapena eta Erantzun Denborak"))
    e.append(
        table(
            [
                ["Maila", "Deskribapena", "Erantzun", "Konponketa"],
                [
                    "P0 — KRIT.",
                    "Ransomware, OT erasoa, datu-ihes masiboa (&gt;500 pertsona)",
                    "15 min",
                    "4 ordu",
                ],
                [
                    "P1 — ALTUA",
                    "Baimenik gabeko sarbidea, DDoS, datu-ihes txikia",
                    "1 ordu",
                    "24 ordu",
                ],
                [
                    "P2 — ERT.",
                    "Malware isolatua, brute force arrakastatsua",
                    "4 ordu",
                    "72 ordu",
                ],
                [
                    "P3 — BAXUA",
                    "Positibo faltsua, port scan, phishing huts",
                    "24 ordu",
                    "1 aste",
                ],
            ],
            col_widths=[2.5 * cm, 7 * cm, 2.5 * cm, 3.5 * cm],
        )
    )
    e.append(sp(0.5))

    e.append(h2("2.1 NIS2 Notifikazio Epealdiak"))
    e.extend(
        bullet(
            [
                "<b>24 ordu:</b> Early Warning — INCIBE-CERT eta BCSC-ri gertaera jakinarazi (Art. 23.1.a).",
                "<b>72 ordu:</b> Txosten osoa — intzidentziaren eragina, kausa eta ezarritako neurriak.",
                "<b>30 egun:</b> Azken txostena — ikaskuntzak, hobukuntzak, ekintzak.",
            ]
        )
    )
    e.append(sp(0.5))

    e.append(h1("3. CSIRT Taldea — Zabala Gailetak"))
    e.append(
        table(
            [
                ["Rola", "Izena", "Telefonoa", "Guardia"],
                [
                    "Incident Commander (IC)",
                    "Mikel Etxebarria",
                    "+34 6XX XXX XX1",
                    "24/7",
                ],
                ["Technical Lead (IT)", "[Izendatu]", "+34 6XX XXX XX2", "L-V 08-20"],
                [
                    "DPO (Privacy Lead)",
                    "Ainhoa Uriarte",
                    "+34 6XX XXX XX3",
                    "L-V 09-18",
                ],
                ["Legal Aholkularia", "[Izendatu]", "+34 6XX XXX XX4", "L-V 09-18"],
                ["OT Espezialist.", "[Izendatu]", "+34 6XX XXX XX6", "L-V 08-20"],
                ["Forensik Analista", "[Izendatu]", "+34 6XX XXX XX7", "Deituta"],
            ],
            col_widths=[4 * cm, 4 * cm, 4 * cm, 3.5 * cm],
        )
    )
    e.append(sp(0.5))

    e.append(h1("4. Intzidentzia Kasuetan — Playbook Adibideak"))
    e.append(h2("4.1 Ransomware Playbook"))
    e.append(
        table(
            [
                ["Urratsa", "Ekintza", "Nork"],
                [
                    "1",
                    "Alerta jaso (SIEM / erabiltzaile) — denbora erregistratu",
                    "SOC",
                ],
                ["2", "Kutsatutako sistema(k) sare-kabletik fisikoki atera", "IT"],
                ["3", "Backup freskoena egiaztatu — oraindik garbi dagoen?", "IT"],
                ["4", "IC (CISO) eta CEO jakinarazi — P0 eskalazio", "SOC"],
                ["5", "Auzitegi irudi forensea egin (kutsatutako diskoak)", "DFIR"],
                ["6", "24h early warning INCIBE-CERT-i", "DPO+Legal"],
                ["7", "Sistema garbi batetik itzulera (DR Plan)", "IT"],
                ["8", "Ziber-asegurua jakinarazi", "CISO"],
                ["9", "Ikaskuntza-txostena idatzi", "CISO"],
            ],
            col_widths=[1.5 * cm, 11 * cm, 3 * cm],
        )
    )
    e.append(sp(0.5))

    e.append(h2("4.2 Datu-Ihes Playbook (GDPR Art. 33)"))
    e.extend(
        bullet(
            [
                "Datuak identifikatu: zenbat, noren, zer motatakoak (ROPA kontsultatu).",
                "72 orduan: AEPD/agintaritzari jakinarazi (gdpr_breach_response_sop.md).",
                "Babes-neurriak: pasahitzak aldatu, sarbidea revokatu, sistema garbitu.",
                "Kaltetutako pertsonei jakinarazi (arrisku altua badago).",
                "Gertatutako guztia dokumentatu ROPA-n.",
            ]
        )
    )
    e.append(sp(0.5))

    e.append(h1("5. OT Intzidentzia — Simulazio Txostena"))
    e.append(
        p(
            "2026ko urtarrilean OT intzidentzia-simulazio bat egin da Zabala Gailetak-en ekoizpen-ingurune probaketan:"
        )
    )
    e.append(
        table(
            [
                [
                    "Eszenarioa",
                    "SCADA-n baimenik gabeko sarrera eta PLC formula aldaketa",
                ],
                [
                    "Eragin Simulatua",
                    "Galleta nahasketa-errezeta aldaketa — osasun-arrisku hipotetioa",
                ],
                [
                    "Detekzio Denbora",
                    "23 minutu (SIEM Wazuh alertak ez zuen OT sarerako araurik)",
                ],
                [
                    "Mugaketa Denbora",
                    "47 minutu — manuz isolatu behar izan zen OT sarea",
                ],
                [
                    "Ondorio Nagusia",
                    "OT sare-monitoreo automatikorako araurik ez zegoen — berehala konpondu",
                ],
                [
                    "Hobekuntza Neurria",
                    "Conpot honeypot gehitu OT sarean + SIEM arau berriak ezarri",
                ],
            ],
            header_row=False,
            col_widths=[5 * cm, 10.5 * cm],
        )
    )

    e.append(sp(0.5))
    e.append(h1("6. Ikaskuntza-Txostena eta Hobekuntzak"))
    e.extend(
        bullet(
            [
                "OT sareko SIEM monitoring arauak sortu (SR 6.1 IEC 62443).",
                "CSIRT kideen zerrenda osatu eta 24/7 berme-sistema ezarri.",
                "Intzidentzia-simulazio ariketak hiruhilekero egitea planifikatua.",
                "Ransomware simulazio tailor-made bat egin 2026 Q2an.",
                "Ziber-aseguruaren poliza berrikusi — OT intzidentziak babesten ditu?",
                "NIS2 Art. 23 jakinarazpen-txantiloiak prestatu — betetze-denborak gorde.",
            ]
        )
    )
    return e


def gen_gorabeherak():
    base = f"{BASE_DIR}/Zibersegurtasun-gorabeherak"
    subject = "Zibersegurtasun-gorabeherak"
    make_pdf(
        f"{base}/ZG_Intzidentzia_Erantzun_Plana_Nagusia.pdf",
        "Zibersegurtasun-gorabeherak — Intzidentziei Erantzuteko Plana",
        "NIST SP 800-61 · ISO 27035 · NIS2 Art. 23 · CSIRT",
        subject,
        "GORB-ZG-001",
        build_gorabeherak,
    )


# ═══════════════════════════════════════════════════════════════════════════════
# INDIZE-PDF orokorra
# ═══════════════════════════════════════════════════════════════════════════════


def build_summary_index():
    e = []
    e.append(h1("Proiektuaren Laburpena"))
    e.append(
        p(
            "Zabala Gailetak S.A. enpresaren zibersegurtasun proiektua honako asignaturetan garatu da:"
        )
    )
    e.append(sp(0.3))
    asign = [
        (
            "1",
            "Auzitegi-analisi Informatikoa",
            "DFIR metodologia, ebidentzia bilketa, memoria forentsea, Autopsy, Volatility",
            "ZG_Auzitegi_Analisi_Txosten_Nagusia.pdf\nZG_Ebidentzia_Bilketa_SOP.pdf",
        ),
        (
            "2",
            "Ekoizpen Seguruan Jartzea",
            "SSDLC, DevSecOps, CI/CD pipeline segurua, Docker gotortzea, Threat Modeling",
            "ZG_SSDLC_DevSecOps_Txostena.pdf",
        ),
        (
            "3",
            "Zibersegurtasunaren Arloko Araudia",
            "ISO 27001:2022 (93 kontrol), GDPR/DBLO, NIS2 Direktiba, IEC 62443",
            "ZG_Araudia_Betekuntza_Txostena_Nagusia.pdf",
        ),
        (
            "4",
            "Hacking Etikoa",
            "Penetration testing (PTES/OWASP), CVSS aurkikuntzak, esplotazioa, konponketak",
            "ZG_Penetration_Test_Txostena_2026.pdf",
        ),
        (
            "5",
            "Sareak eta Sistemak Gotortzea",
            "VLAN 4 segmentu, CIS Benchmark L2, Nginx gotortzea, SIEM ELK+Wazuh, OT",
            "ZG_Sare_Sistemen_Gotortze_Txostena.pdf",
        ),
        (
            "6",
            "Zibersegurtasun-gorabeherak",
            "NIST 800-61 faseak, CSIRT, NIS2 notifikazioak, Ransomware playbook",
            "ZG_Intzidentzia_Erantzun_Plana_Nagusia.pdf",
        ),
    ]
    e.append(
        table(
            [["#", "Asignatura", "Eduki Nagusia", "Fitxategiak"]]
            + [[a[0], a[1], a[2], a[3]] for a in asign],
            col_widths=[0.6 * cm, 4.5 * cm, 6.5 * cm, 4 * cm],
        )
    )
    e.append(sp(1))
    e.append(h1("Araudia eta Estandarren Matrizea"))
    e.append(
        table(
            [
                [
                    "Araudia / Estandarra",
                    "Aplikagarria?",
                    "Betekuntza Tasa",
                    "Asignatura",
                ],
                ["ISO/IEC 27001:2022", "Bai", "93%", "Araudia"],
                ["GDPR / DBLO-GDD", "Bai", "100%", "Araudia"],
                ["NIS2 (EU 2022/2555)", "Bai", "80%", "Araudia + Gorabeherak"],
                ["IEC 62443-3-3 (SL2)", "Bai", "71%", "Araudia + Sareak"],
                ["OWASP Top 10 (2021)", "Bai", "80%", "Hacking + Ekoizpena"],
                ["NIST SP 800-61", "Bai", "100%", "Gorabeherak"],
                ["CIS Benchmark L2", "Bai", "90%", "Sareak"],
                ["RFC 3227 / ISO 27037", "Bai", "100%", "Auzitegi"],
            ]
        )
    )
    e.append(sp(1))
    e.append(
        note(
            f"Dokumentu hauek guztiak sortze-data: {TODAY}. Irakasleek ezin dituzte aldatu —  PDF formatu blokeatua."
        )
    )
    return e


def gen_summary():
    make_pdf(
        f"{BASE_DIR}/ZG_00_Proiektu_Laburpena_Indizea.pdf",
        "Zabala Gailetak — Entregableen Indize Nagusia",
        "Asignatura Guztien Laburpena eta Dokumentu-zerrenda",
        "Laburpen Orokorra",
        "IDX-ZG-000",
        build_summary_index,
    )


# ═══════════════════════════════════════════════════════════════════════════════
# NAGUSIA
# ═══════════════════════════════════════════════════════════════════════════════

if __name__ == "__main__":
    print("\n🔐 Zabala Gailetak — Entregableen PDF Sorgailua")
    print("=" * 52)

    tasks = [
        ("Auzitegi-analisi Informatikoa", gen_auzitegi),
        ("Ekoizpen Seguruan Jartzea", gen_ekoizpen),
        ("Zibersegurtasunaren Arloko Araudia", gen_araudia),
        ("Hacking Etikoa", gen_hacking),
        ("Sareak eta Sistemak Gotortzea", gen_sareak),
        ("Zibersegurtasun-gorabeherak", gen_gorabeherak),
        ("Indize Nagusia", gen_summary),
    ]

    for name, fn in tasks:
        print(f"\n📚 {name}")
        fn()

    print("\n✅ PDF guztiak sortu dira!")
    print(f"📁 Helbidea: {BASE_DIR}")
