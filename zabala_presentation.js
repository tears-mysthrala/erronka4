"use strict";
const pptxgen = require("pptxgenjs");

const pres = new pptxgen();
pres.layout = "LAYOUT_16x9";
pres.title = "Zabala Gailetak - Zibersegurtasun Entrega Nagusia 2026";
pres.author = "Zibersegurtasun Aholkularitza Taldea";

// ===== KOLORE PALETA =====
const C = {
  navy:     "0F2D4A",
  navyMid:  "1A3F63",
  navyDark: "0A1E30",
  gold:     "C8A96E",
  white:    "FFFFFF",
  offWhite: "F8FAFC",
  lightGray:"E2E8F0",
  midGray:  "64748B",
  darkText: "1E293B",
  red:      "DC2626",
  green:    "16A34A",
  orange:   "F97316",
  skyBlue:  "0EA5E9",
  slate:    "1E293B",
  bodyText: "B0C4D8",
  darkBg:   "0D1B2A",
  cardBg:   "1A2B3C",
};

// ===== HELPERS (fresh objects for each shape) =====
const mkShadow = () => ({ type: "outer", blur: 8, offset: 3, angle: 135, color: "000000", opacity: 0.15 });
const mkCardShadow = () => ({ type: "outer", blur: 5, offset: 2, angle: 135, color: "000000", opacity: 0.10 });

// Helper: add consistent footer to light slides
function addLightFooter(s, num, total) {
  s.addShape(pres.shapes.RECTANGLE, { x: 0, y: 5.4, w: 10, h: 0.225, fill: { color: C.navy }, line: { color: C.navy } });
  s.addText("Zabala Gailetak \u00b7 Zibersegurtasun Entrega 2026", {
    x: 0.3, y: 5.41, w: 9.4, h: 0.2, fontSize: 9, fontFace: "Calibri", color: C.gold,
  });
  s.addText(num + " / " + total, {
    x: 0.3, y: 5.41, w: 9.4, h: 0.2, fontSize: 9, fontFace: "Calibri", color: C.midGray, align: "right",
  });
}

// Helper: add dark footer (for dark slides)
function addDarkFooter(s, num, total) {
  s.addShape(pres.shapes.RECTANGLE, { x: 0, y: 5.4, w: 10, h: 0.225, fill: { color: C.navyDark }, line: { color: C.navyDark } });
  s.addText("Zabala Gailetak \u00b7 Zibersegurtasun Entrega 2026", {
    x: 0.3, y: 5.41, w: 9.4, h: 0.2, fontSize: 9, fontFace: "Calibri", color: C.gold,
  });
  s.addText(num + " / " + total, {
    x: 0.3, y: 5.41, w: 9.4, h: 0.2, fontSize: 9, fontFace: "Calibri", color: C.midGray, align: "right",
  });
}

// Helper: add light content header band
function addLightHeader(s, sectionLabel, title) {
  s.addShape(pres.shapes.RECTANGLE, { x: 0, y: 0, w: 10, h: 1.2, fill: { color: C.navy }, line: { color: C.navy } });
  s.addText(sectionLabel, {
    x: 0.5, y: 0.08, w: 9, h: 0.26, fontSize: 10, fontFace: "Calibri", color: C.gold, bold: true, charSpacing: 2,
  });
  s.addText(title, {
    x: 0.5, y: 0.38, w: 9, h: 0.75, fontSize: 24, fontFace: "Georgia", color: C.white, bold: true,
  });
}

const TOTAL = 14;

// ====================================================================
// SLIDE 1: PORTADA
// ====================================================================
{
  let s = pres.addSlide();
  s.background = { color: C.navy };

  // Right column dark band + gold edge
  s.addShape(pres.shapes.RECTANGLE, { x: 7.5, y: 0, w: 2.5, h: 5.625, fill: { color: C.navyMid }, line: { color: C.navyMid } });
  s.addShape(pres.shapes.RECTANGLE, { x: 9.65, y: 0, w: 0.35, h: 5.625, fill: { color: C.gold }, line: { color: C.gold } });

  // Bottom gold stripe
  s.addShape(pres.shapes.RECTANGLE, { x: 0, y: 5.12, w: 7.5, h: 0.06, fill: { color: C.gold }, line: { color: C.gold } });

  // Tag
  s.addText("ZIBERSEGURTASUN AHOLKULARITZA", {
    x: 0.5, y: 0.65, w: 6.7, h: 0.3, fontSize: 10, fontFace: "Calibri",
    color: C.gold, bold: true, charSpacing: 4,
  });

  // Main titles
  s.addText("Zabala Gailetak", {
    x: 0.5, y: 1.05, w: 6.7, h: 0.95, fontSize: 50, fontFace: "Georgia",
    color: C.white, bold: true,
  });
  s.addText("Digital Segurtatuta", {
    x: 0.5, y: 2.0, w: 6.7, h: 0.75, fontSize: 36, fontFace: "Georgia",
    color: C.gold, italic: true,
  });

  // Divider
  s.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 2.88, w: 2.2, h: 0.05, fill: { color: C.gold }, line: { color: C.gold } });

  // Subtitles
  s.addText("Proiektuaren Entrega Exekutiboa", {
    x: 0.5, y: 3.05, w: 6.7, h: 0.35, fontSize: 16, fontFace: "Calibri", color: C.bodyText,
  });
  s.addText("2026ko Otsaila  \u00b7  Zuzendaritza Batzordeari Aurkezpena", {
    x: 0.5, y: 3.45, w: 6.7, h: 0.3, fontSize: 12, fontFace: "Calibri", color: C.midGray,
  });

  // Right column: key metrics
  s.addText("PROIEKTU\nLABURPENA", {
    x: 7.65, y: 0.55, w: 1.85, h: 0.75, fontSize: 10, fontFace: "Calibri",
    color: C.gold, bold: true, charSpacing: 1, align: "center",
  });

  const metrics = [
    ["6",   "Modulu\nSeguritate"],
    ["14",  "Zerbitzari\nVM"],
    ["0",   "Arazo\nKritiko"],
    ["93%", "ISO 27001\nBetetzea"],
  ];
  metrics.forEach(([num, label], i) => {
    const y = 1.5 + i * 0.98;
    s.addText(num, {
      x: 7.65, y, w: 1.85, h: 0.52, fontSize: 28, fontFace: "Georgia",
      color: C.white, bold: true, align: "center",
    });
    s.addText(label, {
      x: 7.65, y: y + 0.52, w: 1.85, h: 0.38, fontSize: 9, fontFace: "Calibri",
      color: C.bodyText, align: "center",
    });
  });

  s.addNotes(
    "HIZLARIAREN GIDOIA - 1. DIAPOSITIBA (1 min)\n\n" +
    "Egun on, jaun-andreok. Zabala Gailetak-eko Zuzendaritza Batzordearen eta " +
    "CEO jaunaren aurrean egotea ohorea da guretzat.\n\n" +
    "Gaur, hilabete hauetan elkarrekin eraiki dugun zerbait aurkeztuko dizuet: " +
    "zuen enpresaren segurtasun digital osoa. Ez berba hutsetan, baizik eta datu " +
    "zehatzetan, emaitza errealekin eta etorkizunerako plan sendoarekin.\n\n" +
    "Galletagintzako negozioa ezagutzen duzuen bezala, segurtasuna ere ezagutu " +
    "behar duzue: funtsezkotzat jo, ikusezin funtzionatzen denean, eta " +
    "ezinbestekotzat gertatzen denean. Hasi gaitezen."
  );
}

// ====================================================================
// SLIDE 2: INDUSTRIA 4.0 + TESTUINGURUA
// ====================================================================
{
  let s = pres.addSlide();
  s.background = { color: C.offWhite };

  addLightHeader(s,
    "01 \u00b7 TESTUINGURUA ETA ERRONKA",
    "Zabala Gailetak eta Industria 4.0: Transformazioaren Argia eta Itzala"
  );

  // Three evolution steps (left column)
  const steps = [
    { num: "01", title: "Ekoizpen Tradizionala", desc: "Fabrika isolatua, makineria analogikoa, paper-erregistroak eta prozesu manuala" },
    { num: "02", title: "Digitalizazio Hasiera", desc: "OpenPLC kontrolagailua, SCADA HMI sistema eta bulegoko sare konektatu berria" },
    { num: "03", title: "Industria 4.0 Gaur", desc: "Cloud zerbitzuak, app mugikorra, RRHH portala eta denbora errealeko datu-elkartrukea" },
  ];

  steps.forEach(({ num, title, desc }, i) => {
    const y = 1.42 + i * 1.18;
    // Circle
    s.addShape(pres.shapes.OVAL, {
      x: 0.38, y: y, w: 0.5, h: 0.5, fill: { color: C.navy }, line: { color: C.navy },
    });
    s.addText(num, {
      x: 0.38, y: y + 0.01, w: 0.5, h: 0.48, fontSize: 13, fontFace: "Calibri",
      color: C.gold, bold: true, align: "center",
    });
    // Connector line (not last)
    if (i < 2) {
      s.addShape(pres.shapes.LINE, {
        x: 0.63, y: y + 0.5, w: 0, h: 0.68,
        line: { color: C.gold, width: 2, dashType: "dash" },
      });
    }
    s.addText(title, {
      x: 1.02, y: y, w: 3.95, h: 0.32, fontSize: 14, fontFace: "Calibri",
      color: C.darkText, bold: true,
    });
    s.addText(desc, {
      x: 1.02, y: y + 0.33, w: 3.95, h: 0.48, fontSize: 11, fontFace: "Calibri",
      color: C.midGray,
    });
  });

  // Right column: stats box
  s.addShape(pres.shapes.RECTANGLE, {
    x: 5.55, y: 1.38, w: 4.1, h: 3.85,
    fill: { color: C.navy }, line: { color: C.navy }, shadow: mkShadow(),
  });
  s.addText("ZABALA GAILETAK\nEN DATUAK", {
    x: 5.7, y: 1.52, w: 3.8, h: 0.58, fontSize: 11, fontFace: "Calibri",
    color: C.gold, bold: true, charSpacing: 2, align: "center",
  });

  const stats = [
    ["120", "Langile"],
    ["3",   "Txanda / Egun"],
    ["5",   "VLAN Sare"],
    ["6",   "VM Zerbitzari"],
  ];
  stats.forEach(([val, label], i) => {
    const col = i % 2, row = Math.floor(i / 2);
    const sx = 5.7 + col * 1.9;
    const sy = 2.22 + row * 1.42;
    s.addShape(pres.shapes.RECTANGLE, {
      x: sx, y: sy, w: 1.75, h: 1.2,
      fill: { color: C.navyMid }, line: { color: C.navyMid },
    });
    s.addText(val, {
      x: sx, y: sy + 0.07, w: 1.75, h: 0.65, fontSize: 34, fontFace: "Georgia",
      color: C.white, bold: true, align: "center",
    });
    s.addText(label, {
      x: sx, y: sy + 0.73, w: 1.75, h: 0.35, fontSize: 11, fontFace: "Calibri",
      color: C.gold, align: "center",
    });
  });

  addLightFooter(s, 2, TOTAL);

  s.addNotes(
    "HIZLARIAREN GIDOIA - 2. DIAPOSITIBA (2 min)\n\n" +
    "Zabala Gailetak ez da soilik galleta-fabrika bat. Azken urteetan, " +
    "transformazio digital garrantzitsu baten erdian dago. Hirurogeita hamar urte " +
    "baino gehiago daramate Euskal Herriko mahaietan eta orain, hiru txandatan eta " +
    "ehun eta hogei langilerekin, ekoizpena digitalizatzearen bidean daude.\n\n" +
    "Hau oso positiboa da. Baina aldi berean, arrisku berri bat sortzen du: orain, " +
    "bulegoko ordenagailua eta galletagintzako makineria sare berean bizi dira.\n\n" +
    "Eta horixe da guk ikusten dugun lehen erronka. Industria 4.0 aukera paregabea " +
    "da, baina ate berria da ere erasotzaileentzat. Jarraian ikusiko duzuenez, " +
    "arrisku hori zehatz neurtu dugu."
  );
}

// ====================================================================
// SLIDE 3: IT / OT ARRISKU KRITIKOA (ilun, gorriarekin)
// ====================================================================
{
  let s = pres.addSlide();
  s.background = { color: C.darkBg };

  // Red left accent bar
  s.addShape(pres.shapes.RECTANGLE, {
    x: 0, y: 0, w: 0.1, h: 5.625, fill: { color: C.red }, line: { color: C.red },
  });

  s.addText("01 \u00b7 TESTUINGURUA ETA ERRONKA", {
    x: 0.28, y: 0.13, w: 9.4, h: 0.26, fontSize: 10, fontFace: "Calibri",
    color: C.red, bold: true, charSpacing: 2,
  });
  s.addText("IT eta OT Konektatzen Direnean: Arrisku Kritikoa", {
    x: 0.28, y: 0.44, w: 9.4, h: 0.72, fontSize: 26, fontFace: "Georgia",
    color: C.white, bold: true,
  });

  // IT box (left)
  s.addShape(pres.shapes.RECTANGLE, {
    x: 0.28, y: 1.28, w: 4.25, h: 3.65, fill: { color: C.cardBg }, line: { color: C.skyBlue, width: 1 },
  });
  s.addShape(pres.shapes.RECTANGLE, {
    x: 0.28, y: 1.28, w: 4.25, h: 0.48, fill: { color: C.skyBlue }, line: { color: C.skyBlue },
  });
  s.addText("BULEGOA \u2014 IT SAREA", {
    x: 0.28, y: 1.28, w: 4.25, h: 0.48, fontSize: 13, fontFace: "Calibri",
    color: C.white, bold: true, align: "center",
  });

  const itItems = [
    "Posta elektronikoa (phishing bektore #1)",
    "Langile-estazio pertsonalak (100+)",
    "Internet sarbidea zuzenekoa",
    "Cloud zerbitzuak: ERP, Office 365",
  ];
  itItems.forEach((txt, i) => {
    s.addText(txt, {
      x: 0.45, y: 1.88 + i * 0.6, w: 3.92, h: 0.48, fontSize: 12, fontFace: "Calibri",
      color: "CBD5E1",
    });
  });

  // Center danger symbol (gap between boxes: 4.53 to 5.47, center at 5.0)
  s.addShape(pres.shapes.OVAL, {
    x: 4.65, y: 1.95, w: 0.7, h: 0.7, fill: { color: C.red }, line: { color: C.red },
  });
  s.addText("!", {
    x: 4.65, y: 1.97, w: 0.7, h: 0.65, fontSize: 28, fontFace: "Georgia",
    color: C.white, bold: true, align: "center",
  });
  s.addShape(pres.shapes.LINE, {
    x: 4.68, y: 3.1, w: 0, h: 1.8, line: { color: C.red, width: 2, dashType: "dash" },
  });

  // OT box (right)
  s.addShape(pres.shapes.RECTANGLE, {
    x: 5.47, y: 1.28, w: 4.25, h: 3.65, fill: { color: "1A2B1A" }, line: { color: C.orange, width: 1 },
  });
  s.addShape(pres.shapes.RECTANGLE, {
    x: 5.47, y: 1.28, w: 4.25, h: 0.48, fill: { color: C.orange }, line: { color: C.orange },
  });
  s.addText("FABRIKA \u2014 OT/SCADA SAREA", {
    x: 5.47, y: 1.28, w: 4.25, h: 0.48, fontSize: 13, fontFace: "Calibri",
    color: C.white, bold: true, align: "center",
  });

  const otItems = [
    { txt: "OpenPLC kontrolagailua (ekoizpen-lerro osoa)", alert: false },
    { txt: "SCADA HMI sistema (ScadaBR, port 9090)", alert: false },
    { txt: "Tenperatura-sentsoreak (Modbus TCP 502)", alert: false },
    { txt: "PORT 502 / OPC-UA: INTERNETI IREKITA!", alert: true },
  ];
  otItems.forEach(({ txt, alert }, i) => {
    s.addText(txt, {
      x: 5.62, y: 1.88 + i * 0.6, w: 3.92, h: 0.48, fontSize: 12, fontFace: "Calibri",
      color: alert ? C.red : "CBD5E1", bold: alert,
    });
  });

  // Bottom warning banner
  s.addShape(pres.shapes.RECTANGLE, {
    x: 0.28, y: 5.02, w: 9.44, h: 0.38, fill: { color: C.red, transparency: 80 }, line: { color: C.red, width: 1 },
  });
  s.addText(
    "Auditorian aurkitutako arrisku kritikoa: OT sistemak Interneti zuzenean irekita zeuden. " +
    "Ekoizpen-lerro osoa urrunetik geldiarazi zitekeen.",
    {
      x: 0.35, y: 5.04, w: 9.3, h: 0.34, fontSize: 10, fontFace: "Calibri",
      color: C.white, bold: true,
    }
  );

  s.addNotes(
    "HIZLARIAREN GIDOIA - 3. DIAPOSITIBA (2 min)\n\n" +
    "Eta hemen dago arazo nagusia, zeina nik zuzenean bistaratzen nahi dudan.\n\n" +
    "Eskuinaldean ikusten duzun OT sarea da fabrikako bihotza: OpenPLC " +
    "kontrolagailua, galletagintzako lerro osoa gidatzen duena: labearen " +
    "tenperatura, irabiatze-makinen abiadura, ontziratzea...\n\n" +
    "Eta auditorian aurkitu genuena? Port 502, OT sistemaren atebide nagusia, " +
    "Interneti irekita zegoen. Nork nahi bazuen, kanpotik, zure ekoizpen-lerro " +
    "osoa geldiarazi zezakeen, edo are gehiago, manipulatu.\n\n" +
    "Hau ez da hipotesia. Hau aurkikuntza erreal bat da. Eta horixe da lehenengo " +
    "konpondu genuena. Arkitektura berri batek bermatzen du hau ez dela berriro " +
    "gertatuko."
  );
}

// ====================================================================
// SLIDE 4: ZERO TRUST ESTRATEGIA (iluna, urrearekin)
// ====================================================================
{
  let s = pres.addSlide();
  s.background = { color: C.navy };

  // Top section strip
  s.addShape(pres.shapes.RECTANGLE, {
    x: 0, y: 0, w: 10, h: 0.58, fill: { color: C.navyMid }, line: { color: C.navyMid },
  });
  s.addText("02 \u00b7 GURE ESTRATEGIA: ZERO TRUST ETA ARKITEKTURA SEGURUA", {
    x: 0.45, y: 0.1, w: 9.1, h: 0.37, fontSize: 10, fontFace: "Calibri",
    color: C.gold, bold: true, charSpacing: 2,
  });

  s.addText("\"Ez Fidatu, Beti Egiaztatu\"\nZero Trust Segurtasun Arkitektura", {
    x: 0.45, y: 0.68, w: 5.6, h: 1.05, fontSize: 26, fontFace: "Georgia",
    color: C.white, bold: true,
  });

  // Three principle cards
  const principles = [
    {
      icon: "1", title: "Inoiz Ez Fidatu",
      desc: "Sare barnekoa izanda ere, erabiltzaile eta gailu oro susmagarri jotzen da autentifikazio arrakastatsu baten arte",
    },
    {
      icon: "2", title: "Beti Egiaztatu",
      desc: "MFA, JWT tokenak eta RBAC (5 rol) erabiliz, sarbide bakoitza indibidualizatuta egiaztatzen da banan-banan",
    },
    {
      icon: "3", title: "Pribilegio Minimoa",
      desc: "Langile bakoitzak beharrezko datutara soilik du sarbidea. Ez gehiago, ez gutxiago. Kalte posiblea mugatzen da",
    },
  ];

  principles.forEach(({ icon, title, desc }, i) => {
    const x = 0.3 + i * 3.23;
    const y = 1.88;
    s.addShape(pres.shapes.RECTANGLE, {
      x, y, w: 3.05, h: 2.9, fill: { color: C.navyMid }, line: { color: C.gold, width: 1 }, shadow: mkShadow(),
    });
    // Icon circle
    s.addShape(pres.shapes.OVAL, {
      x: x + 1.08, y: y + 0.18, w: 0.88, h: 0.88, fill: { color: C.gold }, line: { color: C.gold },
    });
    s.addText(icon, {
      x: x + 1.08, y: y + 0.18, w: 0.88, h: 0.88, fontSize: 22, fontFace: "Georgia",
      color: C.navy, bold: true, align: "center",
    });
    s.addText(title, {
      x: x + 0.12, y: y + 1.18, w: 2.82, h: 0.4, fontSize: 15, fontFace: "Calibri",
      color: C.gold, bold: true, align: "center",
    });
    s.addText(desc, {
      x: x + 0.12, y: y + 1.62, w: 2.82, h: 1.1, fontSize: 11, fontFace: "Calibri",
      color: C.bodyText, align: "center",
    });
  });

  // Right callout (top-right)
  s.addShape(pres.shapes.RECTANGLE, {
    x: 6.2, y: 0.68, w: 3.5, h: 1.05,
    fill: { color: C.gold, transparency: 88 }, line: { color: C.gold, width: 1 },
  });
  s.addText(
    "Emaitza: Erasotzaileak sare barnera sartu arren, ezin du mugitu lateralki sistema batetik bestera. Datu kritikoak babestuta daude.",
    { x: 6.3, y: 0.72, w: 3.3, h: 0.98, fontSize: 11, fontFace: "Calibri", color: C.white, italic: true }
  );

  addDarkFooter(s, 4, TOTAL);

  s.addNotes(
    "HIZLARIAREN GIDOIA - 4. DIAPOSITIBA (2 min)\n\n" +
    "Arazo handi bat ikusi dugu. Orain nola konpondu dugun ikusiko dugu.\n\n" +
    "Gure estrategiaren oinarria Zero Trust filosofia da. Printzipio honen arabera, " +
    "ez dugu inoiz 'barruko' edo 'kanpoko' bereizten. Dena egiaztatu behar da: " +
    "erabiltzaile bat, makina bat, zerbitzu bat... denak autentifikatu eta baimendu " +
    "behar dira banan-banan.\n\n" +
    "Honek esan nahi du: nahiz eta hacker bat zuen sarean sartu, ezingo du " +
    "sistematik sistemara mugitu. Ate bat apurtu arren, gainerako ateak itxita daude.\n\n" +
    "Jarraian, hau nola gauzatu dugun ikusiko duzue: sare segmentazioa VLANen bidez."
  );
}

// ====================================================================
// SLIDE 5: SARE SEGMENTAZIOA - VLANak
// ====================================================================
{
  let s = pres.addSlide();
  s.background = { color: C.offWhite };

  addLightHeader(s,
    "02 \u00b7 GURE ESTRATEGIA",
    "Sare Segmentazioa: Etxe Bakoitza Bere Aterekin (VLAN Arkitektura)"
  );

  // Table header
  s.addShape(pres.shapes.RECTANGLE, {
    x: 0.3, y: 1.25, w: 9.4, h: 0.38, fill: { color: C.navy }, line: { color: C.navy },
  });
  const hdrs = ["VLAN", "IZENA", "IP TARTEA", "HELBURUA / OHARRA"];
  const hx   = [0.35, 1.6, 4.05, 6.15];
  const hw   = [1.2, 2.4, 2.05, 3.5];
  hdrs.forEach((h, i) => {
    s.addText(h, {
      x: hx[i], y: 1.28, w: hw[i], h: 0.32, fontSize: 10, fontFace: "Calibri",
      color: C.gold, bold: true, charSpacing: 1,
    });
  });

  const vlans = [
    { id: "VLAN 10", name: "Bulegoa / Kudeaketa", ip: "192.168.10.0/24",  note: "Langile-estazioak (100+)",             accent: C.skyBlue, alert: false },
    { id: "VLAN 20", name: "IT / Zerbitzariak",   ip: "192.168.20.0/24",  note: "Web, PHP 8.4, PostgreSQL 16, Redis",   accent: "7C3AED",  alert: false },
    { id: "VLAN 30", name: "DMZ (Ingurune Desmilitarizatua)", ip: "192.168.30.0/24", note: "Internet zerbitzuak: web, posta", accent: C.orange, alert: false },
    { id: "VLAN 40", name: "OT / SCADA (ISOLATUA)", ip: "192.168.40.0/24", note: "OpenPLC + ScadaBR — Data Diode bidez soilik",  accent: C.red, alert: true },
    { id: "VLAN 99", name: "Honeypot Sarea",       ip: "172.16.99.0/24",  note: "Erasotzaileak erakartzeko tranpa aktibo",accent: C.green, alert: false },
  ];

  vlans.forEach(({ id, name, ip, note, accent, alert }, i) => {
    const y = 1.65 + i * 0.62;
    const bg = i % 2 === 0 ? "EFF6FF" : C.white;
    s.addShape(pres.shapes.RECTANGLE, {
      x: 0.3, y, w: 9.4, h: 0.58, fill: { color: bg }, line: { color: C.lightGray, width: 0.5 },
    });
    s.addShape(pres.shapes.RECTANGLE, {
      x: 0.3, y, w: 0.14, h: 0.58, fill: { color: accent }, line: { color: accent },
    });
    s.addText(id, {
      x: 0.5, y, w: 1.08, h: 0.58, fontSize: 11, fontFace: "Calibri", color: C.darkText, bold: true,
    });
    s.addText(name, {
      x: 1.6, y, w: 2.38, h: 0.58, fontSize: 11, fontFace: "Calibri",
      color: alert ? C.red : C.darkText, bold: alert,
    });
    s.addText(ip, {
      x: 4.05, y, w: 2.05, h: 0.58, fontSize: 11, fontFace: "Calibri", color: C.midGray,
    });
    s.addText(note, {
      x: 6.15, y, w: 3.5, h: 0.58, fontSize: 10, fontFace: "Calibri",
      color: alert ? C.red : C.darkText, bold: alert,
    });
  });

  // Key message
  s.addShape(pres.shapes.RECTANGLE, {
    x: 0.3, y: 4.76, w: 9.4, h: 0.38, fill: { color: C.navy, transparency: 92 }, line: { color: C.navy, width: 0.5 },
  });
  s.addText(
    "VLAN 40 (OT/SCADA) erabat isolatua dago. Bulegotik ezin da zuzenean komunikatu. " +
    "Datuen elkartrukea Data Diode baten bidez bakarrik, norabide bakarrean (fabrikatik bulegora).",
    { x: 0.4, y: 4.78, w: 9.2, h: 0.34, fontSize: 10, fontFace: "Calibri", color: C.navy, bold: true }
  );

  addLightFooter(s, 5, TOTAL);

  s.addNotes(
    "HIZLARIAREN GIDOIA - 5. DIAPOSITIBA (2 min)\n\n" +
    "Lagin hau Zabala Gailetak-en sare arkitektura berriaren bihotza da.\n\n" +
    "Orain, bost VLAN bereizi dituzu. Bakoitzak bere sareak, bere arauak eta bere " +
    "mugak ditu. VLAN 10 bulegoko langileentzat, VLAN 20 zerbitzarientzat, VLAN 30 " +
    "Interneti begira dauden zerbitzu publikoentzat, VLAN 40 fabrikako makineria " +
    "industrial osorentzat, eta VLAN 99 berezi bat: gure honepotentzat, alegia, " +
    "erasotzaileak erakarriko dituen sare bat.\n\n" +
    "Garrantzitsuena: VLAN 40, OT sarea, erabat isolatua dago. Langile batek bere " +
    "ordenagailutik ezin du zuzenean komunikatu PLCarekin. Datuen transmisioa " +
    "data diode baten bidez bakarrik gertatzen da, eta uni-norabidekoa da: " +
    "fabrikatik bulegora, inoiz ez itzuli.\n\n" +
    "Hau da segurtasun fisikoa digitalizatua."
  );
}

// ====================================================================
// SLIDE 6: AZPIEGITURA - Proxmox, Ansible, IsardVDI
// ====================================================================
{
  let s = pres.addSlide();
  s.background = { color: C.offWhite };

  addLightHeader(s,
    "02 \u00b7 GURE ESTRATEGIA",
    "Azpiegitura Adimentsua: Proxmox, Ansible eta IsardVDI"
  );

  // 6 VM cards: 3x2 grid
  const vms = [
    { name: "ZG-Gateway",  role: "Router / Suebaki",            spec: "1 vCPU \u00b7 1 GB RAM",  accent: C.orange  },
    { name: "ZG-App",      role: "PHP 8.4 \u00b7 Nginx \u00b7 Redis",     spec: "2 vCPU \u00b7 4 GB RAM",  accent: C.skyBlue },
    { name: "ZG-Data",     role: "PostgreSQL 16 BBDD",          spec: "2 vCPU \u00b7 4 GB RAM",  accent: "7C3AED"  },
    { name: "ZG-SecOps",   role: "Wazuh SIEM \u00b7 Honeypot",        spec: "4 vCPU \u00b7 8 GB RAM",  accent: C.red     },
    { name: "ZG-OT",       role: "OpenPLC \u00b7 ScadaBR",            spec: "1 vCPU \u00b7 2 GB RAM",  accent: C.green   },
    { name: "ZG-Client",   role: "Langile-Estazioa (IsardVDI)", spec: "2 vCPU \u00b7 4 GB RAM",  accent: C.midGray },
  ];

  vms.forEach(({ name, role, spec, accent }, i) => {
    const col = i % 3, row = Math.floor(i / 3);
    const cx = 0.3 + col * 3.15;
    const cy = 1.33 + row * 1.78;
    s.addShape(pres.shapes.RECTANGLE, {
      x: cx, y: cy, w: 3.0, h: 1.6, fill: { color: C.white }, line: { color: C.lightGray }, shadow: mkCardShadow(),
    });
    s.addShape(pres.shapes.RECTANGLE, {
      x: cx, y: cy, w: 3.0, h: 0.12, fill: { color: accent }, line: { color: accent },
    });
    s.addText(name, {
      x: cx + 0.15, y: cy + 0.2, w: 2.72, h: 0.38, fontSize: 15, fontFace: "Calibri",
      color: C.darkText, bold: true,
    });
    s.addText(role, {
      x: cx + 0.15, y: cy + 0.61, w: 2.72, h: 0.38, fontSize: 11, fontFace: "Calibri", color: C.midGray,
    });
    s.addText(spec, {
      x: cx + 0.15, y: cy + 1.15, w: 2.72, h: 0.3, fontSize: 10, fontFace: "Calibri",
      color: accent, bold: true,
    });
  });

  // Bottom tool badge
  s.addShape(pres.shapes.RECTANGLE, {
    x: 0.3, y: 4.92, w: 9.4, h: 0.38, fill: { color: C.navy }, line: { color: C.navy },
  });
  s.addText(
    "Ansible bidez automatizatutako konfigurazioa  \u00b7  Proxmox hiperbisorearen gainean  \u00b7  IsardVDI ikasgela birtual gisa erabilita",
    { x: 0.45, y: 4.94, w: 9.1, h: 0.34, fontSize: 11, fontFace: "Calibri", color: C.gold, bold: true }
  );

  addLightFooter(s, 6, TOTAL);

  s.addNotes(
    "HIZLARIAREN GIDOIA - 6. DIAPOSITIBA (2 min)\n\n" +
    "Sare arkitektura ikusita, esan behar dut orain azpiegituraz. " +
    "Proxmox hiperbisorearen gainean sei makina birtual exekutatzen ditugu. " +
    "Bakoitzak funtzio zehatz bat du, isolatua eta indibiduala.\n\n" +
    "ZG-Gateway gure suebaki nagusia da. ZG-App, PHP aplikazioa eta Redis cachea. " +
    "ZG-Data, PostgreSQL 16ko datu-basea. ZG-SecOps, segurtasun-zentroa: " +
    "Wazuh SIEM eta honepotak. ZG-OT, ekoizpen-lerroko PLC sistema. " +
    "Eta ZG-Client, langileek erabiltzen duten estazioa IsardVDI bidez.\n\n" +
    "Guztia, azpian, Ansible-rekin automatizatuta dago. Konfigurazio-fitxategi " +
    "bakarrean aldaketa bat eginez, azpiegitura osoa eguneratzen da minutu " +
    "gutxitan. Erroreak gutxitzen dira, denbora aurrezten da, eta auditoria " +
    "errazten da.\n\n" +
    "Horixe da segurtasun industriala: ez soilik teknika berriak, baizik eta " +
    "prozesu sendoak."
  );
}

// ====================================================================
// SLIDE 7: RRHH PORTALA eta APP MUGIKORRA
// ====================================================================
{
  let s = pres.addSlide();
  s.background = { color: C.offWhite };

  addLightHeader(s,
    "03 \u00b7 BERRIKUNTZA SEGURUA ETA EGUNEROKO OPERAZIOAK",
    "Langile-Tresnak: RRHH Portala eta App Mugikorra"
  );

  // LEFT: RRHH Portal
  s.addText("RRHH WEB PORTALA", {
    x: 0.3, y: 1.28, w: 4.5, h: 0.3, fontSize: 12, fontFace: "Calibri",
    color: C.navy, bold: true, charSpacing: 2,
  });
  s.addShape(pres.shapes.RECTANGLE, {
    x: 0.3, y: 1.6, w: 0.06, h: 3.4, fill: { color: C.navy }, line: { color: C.navy },
  });

  const portalFeatures = [
    { title: "Autentifikazio Anizkoitza (3 geruza)",    desc: "JWT + TOTP MFA (RFC 6238) + WebAuthn pasahitz fisikoak \u2014 Erasotzailearentzat hiru hormak" },
    { title: "RBAC 5 Rolen Sistema",                    desc: "ADMIN, RRHH_MGR, JEFE_SECCION, EMPLEADO, AUDITOR \u2014 Pribilegio minimoa bermatu" },
    { title: "Funtzionalitateak",                       desc: "Oporrak, nomina ikustea, dokumentu zifratu deskargak, txat WebSocket denbora errealekoa" },
    { title: "Segurtasun-goiburuak (OWASP)",            desc: "HSTS, CSP, X-Frame-Options, TLS 1.3 \u2014 OWASP Top 10 zerrenda osoa kontuan hartuta" },
  ];
  portalFeatures.forEach(({ title, desc }, i) => {
    const y = 1.62 + i * 0.85;
    s.addText(title, { x: 0.48, y, w: 4.2, h: 0.32, fontSize: 13, fontFace: "Calibri", color: C.darkText, bold: true });
    s.addText(desc,  { x: 0.48, y: y + 0.33, w: 4.2, h: 0.38, fontSize: 10, fontFace: "Calibri", color: C.midGray });
  });

  // Divider
  s.addShape(pres.shapes.LINE, {
    x: 5.0, y: 1.26, w: 0, h: 3.85, line: { color: C.lightGray, width: 1 },
  });

  // RIGHT: Mobile App
  s.addText("APP MUGIKORRA (ANDROID)", {
    x: 5.2, y: 1.28, w: 4.5, h: 0.3, fontSize: 12, fontFace: "Calibri",
    color: C.navy, bold: true, charSpacing: 2,
  });
  s.addShape(pres.shapes.RECTANGLE, {
    x: 5.2, y: 1.6, w: 0.06, h: 3.4, fill: { color: C.green }, line: { color: C.green },
  });

  const appFeatures = [
    { title: "Kotlin 2.0 + Jetpack Compose",   desc: "Material 3 diseinua, Clean Architecture + MVI eredua, Hilt dependency injection" },
    { title: "Certificate Pinning aktibo",      desc: "TLS ziurtagiriak aplikazioan kodetuta \u2014 Man-in-the-Middle erasoak ezinezko" },
    { title: "CI/CD automatikoa",               desc: "GitHub Actions bidez eraikia eta sinatua \u2014 SHA-256 egiaztatzapena banatzerakoan" },
    { title: "App Transport Security (HTTPS)",  desc: "Cleartext konexiorik ez baimentzen da inoiz \u2014 Sare guztiak enkriptatuta" },
  ];
  appFeatures.forEach(({ title, desc }, i) => {
    const y = 1.62 + i * 0.85;
    s.addText(title, { x: 5.35, y, w: 4.3, h: 0.32, fontSize: 13, fontFace: "Calibri", color: C.darkText, bold: true });
    s.addText(desc,  { x: 5.35, y: y + 0.33, w: 4.3, h: 0.38, fontSize: 10, fontFace: "Calibri", color: C.midGray });
  });

  addLightFooter(s, 7, TOTAL);

  s.addNotes(
    "HIZLARIAREN GIDOIA - 7. DIAPOSITIBA (3 min)\n\n" +
    "Segurtasun arkitektura aztertu dugu. Baina segurtasuna ez da soilik " +
    "suebakiak eta VLANak. Eguneroko langileak erabiltzen dituzten tresnak " +
    "ere babestuta egon behar dira.\n\n" +
    "Ezkerrean ikusten duzuen RRHH portala zuen ehun eta hogei langileek " +
    "erabiliko dute: oporrak eskatzeko, nomina ikusteko, dokumentuak " +
    "deskargatzeko. Baina ez da edozein web-orri. Barruan hiru autentifikazio-" +
    "geruza ditu: pasahitza, bigarren faktore bat telefonoan, eta WebAuthn " +
    "gakoa. Erabiltzaile bakoitzak soilik bere informaziora du sarbidea, " +
    "RBAC sisteman definitutako bost rolen arabera.\n\n" +
    "Eskuinean, app mugikorra. Android-erako, Kotlin-en idatzia, segurtasun-" +
    "protokolo sendoekin: certificate pinning aktibo, hau da, konexio orok " +
    "ziurtagiri zehatz bat egiaztatu behar du. Man-in-the-Middle erasoak " +
    "ezinezko egiten da horrela.\n\n" +
    "Gure printzipioa garbia da: erabiltzaile-esperientzia ona eta segurtasuna " +
    "ez dira kontrajarriak. Biak batera lor daitezke."
  );
}

// ====================================================================
// SLIDE 8: DevSecOps PIPELINE (garapen-zikloa)
// ====================================================================
{
  let s = pres.addSlide();
  s.background = { color: C.offWhite };

  addLightHeader(s,
    "03 \u00b7 BERRIKUNTZA SEGURUA ETA EGUNEROKO OPERAZIOAK",
    "Segurtasuna Hasieratik: DevSecOps Pipeline eta Edukiontzi Estrategia"
  );

  // 5-step pipeline cards
  const pipeline = [
    { step: "01", label: "Kodea\nIdatzi",      detail: "PHP 8.4\nKotlin 2.0\nReact 18",   color: C.skyBlue },
    { step: "02", label: "SAST\nAnalisi",       detail: "Estatiko\nkode analisi\nautomatikoa", color: "7C3AED" },
    { step: "03", label: "Docker\nEraiki",      detail: "Irudiak sinatu\neta ziurtagiri\nbertifikatua", color: C.orange },
    { step: "04", label: "DAST\nProbatu",       detail: "OWASP ZAP\neraso-\nsimulakroa",   color: C.red    },
    { step: "05", label: "Produkziora\nBidali", detail: "TLS 1.3\nJWT zifratu\nMonitorizazioa", color: C.green  },
  ];

  pipeline.forEach(({ step, label, detail, color }, i) => {
    const cx = 0.28 + i * 1.9;
    // Arrow connector (not last)
    if (i < 4) {
      s.addShape(pres.shapes.RECTANGLE, {
        x: cx + 1.55, y: 2.35, w: 0.35, h: 0.07, fill: { color: C.midGray }, line: { color: C.midGray },
      });
    }
    // Card
    s.addShape(pres.shapes.RECTANGLE, {
      x: cx, y: 1.32, w: 1.55, h: 3.55, fill: { color: C.white }, line: { color: color, width: 2 }, shadow: mkCardShadow(),
    });
    s.addShape(pres.shapes.RECTANGLE, {
      x: cx, y: 1.32, w: 1.55, h: 0.52, fill: { color }, line: { color },
    });
    s.addText(step, {
      x: cx, y: 1.32, w: 1.55, h: 0.52, fontSize: 22, fontFace: "Georgia",
      color: C.white, bold: true, align: "center",
    });
    s.addText(label, {
      x: cx + 0.07, y: 1.94, w: 1.42, h: 0.72, fontSize: 13, fontFace: "Calibri",
      color: C.darkText, bold: true, align: "center",
    });
    s.addText(detail, {
      x: cx + 0.07, y: 2.75, w: 1.42, h: 1.0, fontSize: 10, fontFace: "Calibri",
      color: C.midGray, align: "center",
    });
  });

  // Bottom principle
  s.addShape(pres.shapes.RECTANGLE, {
    x: 0.28, y: 4.97, w: 9.44, h: 0.35, fill: { color: C.navy, transparency: 92 }, line: { color: C.navy, width: 0.5 },
  });
  s.addText(
    "\"Shift Left\" printzipioa: segurtasun-arazoak kodean aurkitzea produkzioan konpontzea baino 100x merkeagoa da.",
    { x: 0.38, y: 4.99, w: 9.24, h: 0.31, fontSize: 11, fontFace: "Calibri", color: C.navy, bold: true, italic: true }
  );

  addLightFooter(s, 8, TOTAL);

  s.addNotes(
    "HIZLARIAREN GIDOIA - 8. DIAPOSITIBA (2 min)\n\n" +
    "Badago printzipio bat zibersegurtasunean garrantzitsua dena: 'Shift Left' " +
    "deitzen dena. Hau da, segurtasun-arazoak denboran ahalik eta lehenago " +
    "aurkitzea. Kode-lerro bat idazten den unean, ez produkzioan dagoenean.\n\n" +
    "Hori da gure garapen-bidearen muina. Bost urrats ditu:\n" +
    "Lehenengoa, kodea idatzi. Bigarrena, automatikoki aztertu (SAST). " +
    "Hirugarrena, Docker edukiontzian eraiki eta sinatu. Laugarrena, DAST " +
    "eraso-simulakroarekin probatu. Bosgarrena, produkziora bidali, zifratu " +
    "eta babestuta.\n\n" +
    "Emaitza: Arazo bat produkzioan konpontzeak 100 aldiz gehiago kostatzen " +
    "du kodean konpontzeak baino. Gure pipeline-ak gastu hori aurreratzen du, " +
    "eta zuen negozioari diru eta denbora aurrezten dio."
  );
}

// ====================================================================
// SLIDE 9: PENTESTING EMAITZAK (iluna, berdearekin)
// ====================================================================
{
  let s = pres.addSlide();
  s.background = { color: C.darkBg };

  // Green left accent
  s.addShape(pres.shapes.RECTANGLE, {
    x: 0, y: 0, w: 0.1, h: 5.625, fill: { color: C.green }, line: { color: C.green },
  });

  s.addText("04 \u00b7 ERASOEN AURKAKO PREBENTZIOA ETA ERANTZUNA", {
    x: 0.28, y: 0.12, w: 9.4, h: 0.26, fontSize: 10, fontFace: "Calibri",
    color: C.green, bold: true, charSpacing: 2,
  });
  s.addText("Gure Defentsak Probatu Ditugu:\nPentesting eta WiFi Auditoriaren Emaitzak", {
    x: 0.28, y: 0.42, w: 9.4, h: 0.82, fontSize: 25, fontFace: "Georgia",
    color: C.white, bold: true,
  });

  // 4 stats (2x2 grid)
  const stats = [
    { val: "0",  label: "Arazo Kritiko",      sub: "CVSS 9.0+ \u2014 Sektorearen gailurrean",  color: C.green  },
    { val: "2",  label: "Arazo Garrantzitsu", sub: "CVSS 7.5-7.8 \u2014 Biok konponduta",      color: C.orange },
    { val: "4",  label: "Arazo Ertain",       sub: "CVSS 5.3-6.5 \u2014 Guztiak kontrolpean",  color: C.skyBlue},
    { val: "3",  label: "Arazo Txiki",        sub: "CVSS 3.1-4.3 \u2014 Dokumentatuta",        color: C.midGray},
  ];

  stats.forEach(({ val, label, sub, color }, i) => {
    const col = i % 2, row = Math.floor(i / 2);
    const sx = 0.28 + col * 4.72;
    const sy = 1.4 + row * 1.88;
    s.addShape(pres.shapes.RECTANGLE, {
      x: sx, y: sy, w: 4.45, h: 1.7, fill: { color: C.cardBg }, line: { color, width: 1 }, shadow: mkShadow(),
    });
    s.addShape(pres.shapes.RECTANGLE, {
      x: sx, y: sy, w: 0.1, h: 1.7, fill: { color }, line: { color },
    });
    s.addText(val, {
      x: sx + 0.18, y: sy + 0.12, w: 1.1, h: 1.0, fontSize: 56, fontFace: "Georgia",
      color, bold: true,
    });
    s.addText(label, {
      x: sx + 1.38, y: sy + 0.2, w: 2.95, h: 0.55, fontSize: 17, fontFace: "Calibri",
      color: C.white, bold: true,
    });
    s.addText(sub, {
      x: sx + 1.38, y: sy + 0.85, w: 2.95, h: 0.55, fontSize: 11, fontFace: "Calibri",
      color: "CBD5E1",
    });
  });

  // WiFi info banner
  s.addShape(pres.shapes.RECTANGLE, {
    x: 0.28, y: 5.03, w: 9.44, h: 0.42, fill: { color: C.cardBg }, line: { color: C.gold, width: 0.5 },
  });
  s.addText(
    "WiFi Auditoria (PTES metodologia): 4 eszénario probatu (WPA2-PSK, WPA3-SAE, Open, Hidden SSID). " +
    "Gomendio nagusia: WPA3-Enterprise + 802.1X + RADIUS zerbitzaria.",
    { x: 0.4, y: 5.06, w: 9.2, h: 0.36, fontSize: 10, fontFace: "Calibri", color: C.gold }
  );

  s.addNotes(
    "HIZLARIAREN GIDOIA - 9. DIAPOSITIBA (3 min)\n\n" +
    "Segurtasun-talde batek eraikitako sistema probatu beharra dago. Guk geuk " +
    "probatu dugu. Pentesting profesional bat egin dugu, PTES metodologia erabiliz.\n\n" +
    "Eta emaitzak? Arazo kritikoa, CVSS 9.0 puntutik gora: ZERO. Hau " +
    "sektorearen batez bestekoa baino askoz hobea da. Arazo garrantzitsu bi " +
    "aurkitu genituen, biak jadanik konponduta. Lau ertain eta hiru txiki, " +
    "denak kontrolpean.\n\n" +
    "Aurkikuntza interesgarriena: dev.zabala-gailetak.com eta " +
    "staging.zabala-gailetak.com azpi-domeinu publikoak Interneten ikusgai " +
    "zeuden. Informazio pertsonala ez zegoen arriskuan, baina hacker batek " +
    "informazio baliotsuak ateratzen zituen han. Konponduta dago.\n\n" +
    "WiFi auditorian, lau sareen eszénario probatu genituen. Gure gomendio " +
    "nagusia WPA3-Enterprise inplementatzea da, 802.1X autentifikazioarekin. " +
    "Enpresa-mailako babes estandarra lortuko litzateke horrela."
  );
}

// ====================================================================
// SLIDE 10: WAZUH SIEM ETA MONITORIZAZIOA
// ====================================================================
{
  let s = pres.addSlide();
  s.background = { color: C.offWhite };

  addLightHeader(s,
    "04 \u00b7 ERASOEN AURKAKO PREBENTZIOA",
    "Begiak Beti Irekita: Wazuh SIEM eta Denbora Errealeko Monitorizazioa"
  );

  // LEFT: SIEM description
  s.addText("SIEM SISTEMAREN BALIOA", {
    x: 0.3, y: 1.28, w: 4.55, h: 0.3, fontSize: 12, fontFace: "Calibri",
    color: C.navy, bold: true, charSpacing: 2,
  });

  const siemPoints = [
    { title: "Denbora errealeko monitorizazioa",   desc: "Suebakia, zerbitzariak, web-aplikazioa eta OT gailuak aldi berean ikusten ditu" },
    { title: "Alerta automatikoak",                 desc: "5 saiakera huts / minutu: blokeo automatikoa. Eskaner berria: jakinarazpen berehalakoa" },
    { title: "FIM (File Integrity Monitoring)",     desc: "Edozein fitxategi kritikoren aldaketa detektatzen eta jakinarazten du segundotan" },
    { title: "Kibana panel bisuala (port 5601)",    desc: "Incidenteak kolore bidez sailkatuta, joerak grafikoetan, erantzuteko pista argiak" },
  ];
  siemPoints.forEach(({ title, desc }, i) => {
    const y = 1.68 + i * 0.83;
    s.addShape(pres.shapes.OVAL, {
      x: 0.3, y: y + 0.06, w: 0.38, h: 0.38, fill: { color: C.navy }, line: { color: C.navy },
    });
    s.addText((i + 1).toString(), {
      x: 0.3, y: y + 0.04, w: 0.38, h: 0.38, fontSize: 13, fontFace: "Georgia",
      color: C.gold, bold: true, align: "center",
    });
    s.addText(title, { x: 0.8, y, w: 4.0, h: 0.3, fontSize: 13, fontFace: "Calibri", color: C.darkText, bold: true });
    s.addText(desc,  { x: 0.8, y: y + 0.3, w: 4.0, h: 0.42, fontSize: 10, fontFace: "Calibri", color: C.midGray });
  });

  // Divider
  s.addShape(pres.shapes.LINE, {
    x: 5.05, y: 1.26, w: 0, h: 3.85, line: { color: C.lightGray, width: 1 },
  });

  // RIGHT: Alert rules
  s.addText("ALERTA ARAUAK (ADIBIDEAK)", {
    x: 5.2, y: 1.28, w: 4.5, h: 0.3, fontSize: 12, fontFace: "Calibri",
    color: C.navy, bold: true, charSpacing: 2,
  });

  const alerts = [
    { level: "KRITIKOA",  rule: "Pribilegioaren eskalada detektatua",         color: C.red    },
    { level: "ALTUA",     rule: "Brute-force erasoa (5 huts. / minutu)",       color: C.orange },
    { level: "ERTAINA",   rule: "Eskaner berria OT sarean detektatua",          color: C.skyBlue},
    { level: "BAXUA",     rule: "Port-eskanerrak kanpotik detektatuta",         color: C.green  },
  ];
  alerts.forEach(({ level, rule, color }, i) => {
    const y = 1.68 + i * 0.87;
    s.addShape(pres.shapes.RECTANGLE, {
      x: 5.2, y, w: 4.5, h: 0.73, fill: { color: C.white }, line: { color: C.lightGray }, shadow: mkCardShadow(),
    });
    s.addShape(pres.shapes.RECTANGLE, {
      x: 5.2, y, w: 0.1, h: 0.73, fill: { color }, line: { color },
    });
    s.addText(level, {
      x: 5.35, y: y + 0.06, w: 1.6, h: 0.28, fontSize: 9, fontFace: "Calibri",
      color, bold: true, charSpacing: 1,
    });
    s.addText(rule, {
      x: 5.35, y: y + 0.36, w: 4.25, h: 0.28, fontSize: 12, fontFace: "Calibri", color: C.darkText,
    });
  });

  addLightFooter(s, 10, TOTAL);

  s.addNotes(
    "HIZLARIAREN GIDOIA - 10. DIAPOSITIBA (2 min)\n\n" +
    "Defentsak finkatu ditugu. Baina segurtasunean ez dago 'konfiguratu eta " +
    "ahaztu' soluzioa. Etengabe ikusi behar da zer gertatzen den.\n\n" +
    "Horretarako, Wazuh SIEM sisteman inbertitu dugu, ELK Stack-arekin " +
    "(Elasticsearch, Logstash, Kibana) integratuta. Zer egiten du honek? " +
    "Zuen azpiegituran gertatzen den dena ikusten du: suebakia, zerbitzariak, " +
    "web aplikazioa, OT gailuak... dena aldi berean.\n\n" +
    "Alerta-sistema automatikoa dago. Erabiltzaile batek bost aldiz pasahitz " +
    "okerra sartzen badu minutu batean, automatikoki blokeatzen da. Fitxategi " +
    "kritiko bat aldatzen bada, jakinarazpena dator segundoak. OT sarean " +
    "eskaner bat detektatzen bada, alarma jotzen du.\n\n" +
    "Kibana panelaren bidez, segurtasun-arduradunak pantaila batetik ikusten " +
    "du dena. Ez da soilik ikustea: azkar ikustea da gakoa."
  );
}

// ====================================================================
// SLIDE 11: HONEYPOTS + INCIDENT RESPONSE
// ====================================================================
{
  let s = pres.addSlide();
  s.background = { color: C.offWhite };

  addLightHeader(s,
    "04 \u00b7 ERASOEN AURKAKO PREBENTZIOA",
    "Tranpak Erasotzaileentzat eta Plan Aktiboa Erantzuteko"
  );

  // LEFT: Honeypot
  s.addText("HONEYPOT ESTRATEGIA", {
    x: 0.3, y: 1.28, w: 4.55, h: 0.3, fontSize: 12, fontFace: "Calibri",
    color: C.navy, bold: true, charSpacing: 2,
  });
  s.addShape(pres.shapes.RECTANGLE, {
    x: 0.3, y: 1.65, w: 4.55, h: 2.95, fill: { color: C.cardBg }, line: { color: C.orange, width: 1 }, shadow: mkShadow(),
  });

  const honeypots = [
    { name: "Cowrie",   desc: "SSH/Telnet tranpa. Saiakera guztiak grabatu,\nerabiltzaile+pasahitz patroiak analizatu" },
    { name: "Conpot",   desc: "OT/ICS simulakroa. Modbus TCP baimendua.\nIndustria-erasotzaileak erakarri eta detektatu" },
    { name: "T-Pot",    desc: "Plataforma orokor. GeoIP jatorria, malware-\nlaginen bilketa eta analisi automatikoa" },
  ];
  honeypots.forEach(({ name, desc }, i) => {
    const y = 1.82 + i * 0.88;
    s.addShape(pres.shapes.RECTANGLE, {
      x: 0.45, y, w: 1.15, h: 0.62, fill: { color: C.orange }, line: { color: C.orange },
    });
    s.addText(name, {
      x: 0.45, y, w: 1.15, h: 0.62, fontSize: 12, fontFace: "Calibri",
      color: C.white, bold: true, align: "center",
    });
    s.addText(desc, {
      x: 1.72, y, w: 3.0, h: 0.62, fontSize: 10, fontFace: "Calibri", color: "CBD5E1",
    });
  });

  s.addText(
    "Sare isolatua: 172.16.99.0/24  |  Produktioan eraginik ez",
    { x: 0.45, y: 4.52, w: 4.25, h: 0.28, fontSize: 10, fontFace: "Calibri", color: C.gold, italic: true }
  );

  // Divider
  s.addShape(pres.shapes.LINE, {
    x: 5.05, y: 1.26, w: 0, h: 3.85, line: { color: C.lightGray, width: 1 },
  });

  // RIGHT: Incident Response
  s.addText("INCIDENT RESPONSE PLANA", {
    x: 5.2, y: 1.28, w: 4.5, h: 0.3, fontSize: 12, fontFace: "Calibri",
    color: C.navy, bold: true, charSpacing: 2,
  });

  const irSteps = [
    { time: "0-24 h",  action: "Detekzioa eta lehenengo albistea",          color: C.red    },
    { time: "24-72 h", action: "Analisi zehatza + NIS2 txosten ofiziala",   color: C.orange },
    { time: "72 h+",   action: "Konponketa eta sistema-berreskurapena",     color: C.skyBlue},
    { time: "Post",    action: "Forentse-analisia + Ikaskuntzak dokumentatu",color: C.green  },
  ];
  irSteps.forEach(({ time, action, color }, i) => {
    const y = 1.68 + i * 0.87;
    s.addShape(pres.shapes.RECTANGLE, {
      x: 5.2, y, w: 4.5, h: 0.73, fill: { color: C.white }, line: { color: C.lightGray }, shadow: mkCardShadow(),
    });
    s.addShape(pres.shapes.RECTANGLE, {
      x: 5.2, y, w: 0.1, h: 0.73, fill: { color }, line: { color },
    });
    s.addText(time, {
      x: 5.35, y: y + 0.05, w: 1.2, h: 0.28, fontSize: 11, fontFace: "Calibri", color, bold: true,
    });
    s.addText(action, {
      x: 5.35, y: y + 0.36, w: 4.25, h: 0.28, fontSize: 12, fontFace: "Calibri", color: C.darkText,
    });
  });
  s.addText("Forentse-analisia: log-ak, memoria-argazkiak eta artefaktuen azterketa", {
    x: 5.2, y: 5.16, w: 4.5, h: 0.28, fontSize: 9, fontFace: "Calibri", color: C.midGray, italic: true,
  });

  addLightFooter(s, 11, TOTAL);

  s.addNotes(
    "HIZLARIAREN GIDOIA - 11. DIAPOSITIBA (2 min)\n\n" +
    "Bi gauza gehiago ikusiko ditugu orain: honeypot tranpak eta gure " +
    "erantzun-plana.\n\n" +
    "Honeypot bat sistema faltsu bat da, erasotzailearentzat erakargarria " +
    "dirudiena, baina errealean tranpa bat. Cowrie-k SSH saio guztiak grabatzen " +
    "ditu: erasotzaileek zer komando sartzen dituzten ikusi dezakegu. Conpot-ek " +
    "PLC bat simulatzen du: hacker industrialak erakarriko ditu. T-Pot " +
    "plataformak analisi automatikoa egiten du: nondik etor diren, zer " +
    "pasahitz probatzen dituzten, malware-laginak biltzen.\n\n" +
    "Eta zerbait okerrera badoa? Erantzun-plana aktibo jartzen da. Lehen 24 " +
    "orduetan, jakinarazpena. 72 ordutan, NIS2 txosten ofiziala autoritate " +
    "eskudunari. Ondoren, berreskurapena eta forentse-analisia: zer gertatu " +
    "zen, nola, eta nola saihestu etorkizunean."
  );
}

// ====================================================================
// SLIDE 12: BETETZE LEGALA - NIS2, RGPD, IEC 62443
// ====================================================================
{
  let s = pres.addSlide();
  s.background = { color: C.offWhite };

  addLightHeader(s,
    "05 \u00b7 BETETZE LEGALA ETA ETORKIZUNA",
    "Lege-Betetzea: NIS2 Direktiba, RGPD eta IEC 62443"
  );

  const cols = [
    {
      title: "NIS2 DIREKTIBA",
      status: "60% \u00b7 Helburua: 2026ko Urria",
      statusColor: C.orange,
      points: [
        "Enpresa 'GARRANTZITSUA' gisa sailkatua (Art. 3)",
        "24 h: Lehenengo jakinarazpena autoritate eskudunari",
        "72 h: Txosten osoa incidente larriari buruz",
        "Hornidura-kate segurtasuna (Art. 21.2.e)",
        "Q4 2026: Betetze osoa planifikatua",
      ],
      color: C.skyBlue,
    },
    {
      title: "RGPD / GDPR",
      status: "100% \u00b7 Beteta",
      statusColor: C.green,
      points: [
        "DPIA ebaluazioak (RRHH portala + OT sistemak)",
        "Datu-tratamendu erregistroa (RoPA) osatuta",
        "Eskubide-prozedurak dokumentatuta (ARCO+)",
        "Datuen babesa diseinutik bertatik (Privacy by Design)",
        "Cookie politika eta uko egiteko aukerak aktibo",
      ],
      color: C.green,
    },
    {
      title: "IEC 62443",
      status: "70% \u00b7 SL2 Lortuta",
      statusColor: C.skyBlue,
      points: [
        "Purdue Eredua (0-5 mailak) ezarrita OT sisteman",
        "Zona / Konduit segmentazioa (IT/OT bereiztea)",
        "SL 2 lortua: autentifikazioa + auditoria aktibo",
        "Data Diode: telemetria uni-norabidekoa",
        "SL 3 bidea (sistema kritikoak): Q3 2026",
      ],
      color: C.orange,
    },
  ];

  cols.forEach(({ title, status, statusColor, points, color }, i) => {
    const cx = 0.3 + i * 3.18;
    s.addShape(pres.shapes.RECTANGLE, {
      x: cx, y: 1.26, w: 3.02, h: 3.98, fill: { color: C.white }, line: { color: C.lightGray }, shadow: mkCardShadow(),
    });
    s.addShape(pres.shapes.RECTANGLE, {
      x: cx, y: 1.26, w: 3.02, h: 0.48, fill: { color }, line: { color },
    });
    s.addText(title, {
      x: cx + 0.1, y: 1.27, w: 2.82, h: 0.46, fontSize: 13, fontFace: "Calibri",
      color: C.white, bold: true, charSpacing: 1,
    });
    // Status badge
    s.addShape(pres.shapes.RECTANGLE, {
      x: cx + 0.1, y: 1.82, w: 2.82, h: 0.33, fill: { color: statusColor, transparency: 86 }, line: { color: statusColor, width: 0.5 },
    });
    s.addText(status, {
      x: cx + 0.15, y: 1.84, w: 2.72, h: 0.28, fontSize: 10, fontFace: "Calibri", color: statusColor, bold: true,
    });
    // Points
    points.forEach((pt, j) => {
      s.addText(
        [{ text: "\u00b7  ", options: { color, bold: true } }, { text: pt, options: { color: C.darkText } }],
        { x: cx + 0.12, y: 2.24 + j * 0.56, w: 2.78, h: 0.48, fontSize: 10, fontFace: "Calibri" }
      );
    });
  });

  // ISO bar
  s.addShape(pres.shapes.RECTANGLE, {
    x: 0.3, y: 5.1, w: 9.4, h: 0.24, fill: { color: C.navy }, line: { color: C.navy },
  });
  s.addText(
    "ISO 27001:2022 \u2014 87 / 93 kontrol aktibo (93% betetzea)    \u00b7    OWASP Top 10 \u2014 A01-A09 mitigazioak dokumentatuta",
    { x: 0.4, y: 5.12, w: 9.2, h: 0.2, fontSize: 10, fontFace: "Calibri", color: C.gold, align: "center" }
  );

  addLightFooter(s, 12, TOTAL);

  s.addNotes(
    "HIZLARIAREN GIDOIA - 12. DIAPOSITIBA (3 min)\n\n" +
    "Orain hizkuntzaz aldatuko gara: teknikatik legegintzara. CEOrentzat eta " +
    "Zuzendaritzarentzat mezurik garrantzitsuena honakoa da: RGPD eta NIS2 " +
    "ez dira aukera. Araudia da. Ez betetzeak zigorrak ekar ditzake.\n\n" +
    "Hiru arau-esparru garrantzitsu ditu Zabala Gailetak:\n\n" +
    "Lehena, NIS2 Direktiba Europarra. Enpresa 'Garrantzitsu' gisa sailkatuta " +
    "dago. Honek esan nahi du: incidente larri bat gertatzen bada, 24 ordutan " +
    "jakinarazi behar duzue autoritate eskudunari, eta 72 ordutan txosten osoa. " +
    "Betetze-data? 2026ko urria. Gaur, %60etan gaude. Bide-orria argia da.\n\n" +
    "Bigarrena, RGPD/GDPR. %100eko betetzea dugu jadanik. " +
    "Langile eta bezeroen datuak babestuta daude.\n\n" +
    "Hirugarrena, IEC 62443. OT sarea Purdue Ereduarekin egituratuta. " +
    "Gaur SL2 lortu dugu. Hurrengo pausoa SL3 da sistema kritikoetarako.\n\n" +
    "Eta gainera, ISO 27001:2022 estandarrean 87 kontrol 93tatik aktibo ditugu. " +
    "Sektorearen gailurrean gaude."
  );
}

// ====================================================================
// SLIDE 13: BALIO EKONOMIKOA ETA BIDE-ORRIA
// ====================================================================
{
  let s = pres.addSlide();
  s.background = { color: C.navy };

  s.addShape(pres.shapes.RECTANGLE, {
    x: 0, y: 0, w: 10, h: 0.56, fill: { color: C.navyMid }, line: { color: C.navyMid },
  });
  s.addText("05 \u00b7 BETETZE LEGALA ETA ETORKIZUNA", {
    x: 0.45, y: 0.1, w: 9.1, h: 0.36, fontSize: 10, fontFace: "Calibri",
    color: C.gold, bold: true, charSpacing: 2,
  });
  s.addText("Balio Ekonomikoa eta Etorkizunerako Bide-orria", {
    x: 0.45, y: 0.66, w: 9.1, h: 0.7, fontSize: 26, fontFace: "Georgia",
    color: C.white, bold: true,
  });

  // LEFT: 4 value cards (2x2)
  s.addText("ZERGATIK MEREZI DU INBERTSIO HONEK?", {
    x: 0.45, y: 1.48, w: 4.25, h: 0.3, fontSize: 11, fontFace: "Calibri",
    color: C.gold, bold: true, charSpacing: 1,
  });
  const values = [
    { val: "10M\u20ac+",  label: "NIS2 zigor posibla (%2 fakturazioa)", color: C.red    },
    { val: "24/7",    label: "SIEM monitorizazioa aktibo",           color: C.skyBlue},
    { val: "100%",    label: "RGPD betetzea \u2014 isun-arriskua zero",  color: C.green  },
    { val: "0",       label: "Arazo kritiko aurkitua pentesting-ean", color: C.orange },
  ];
  values.forEach(({ val, label, color }, i) => {
    const col = i % 2, row = Math.floor(i / 2);
    const vx = 0.45 + col * 2.15;
    const vy = 1.88 + row * 1.5;
    s.addShape(pres.shapes.RECTANGLE, {
      x: vx, y: vy, w: 2.0, h: 1.35, fill: { color: C.navyMid }, line: { color, width: 1 },
    });
    s.addText(val, {
      x: vx, y: vy + 0.07, w: 2.0, h: 0.7, fontSize: 28, fontFace: "Georgia",
      color, bold: true, align: "center",
    });
    s.addText(label, {
      x: vx + 0.07, y: vy + 0.8, w: 1.87, h: 0.48, fontSize: 10, fontFace: "Calibri",
      color: C.bodyText, align: "center",
    });
  });

  // Divider
  s.addShape(pres.shapes.LINE, {
    x: 4.95, y: 1.45, w: 0, h: 3.65, line: { color: C.navyMid, width: 1 },
  });

  // RIGHT: Roadmap
  s.addText("BIDE-ORRIA 2026", {
    x: 5.15, y: 1.48, w: 4.5, h: 0.3, fontSize: 11, fontFace: "Calibri",
    color: C.gold, bold: true, charSpacing: 1,
  });
  const roadmap = [
    { q: "Q1 2026", items: "Gap analisia (done) \u00b7 MFA %100 \u00b7 NIS2 hasiera",           done: true  },
    { q: "Q2 2026", items: "EDR zabalkundea \u00b7 SIEM 24/7 \u00b7 CSIRT taldea \u00b7 Vendors",    done: false },
    { q: "Q3 2026", items: "IEC 62443 SL3 \u00b7 BCP probak \u00b7 Pentest urtekoa",             done: false },
    { q: "Q4 2026", items: "NIS2 BETETZE OSOA (Urria 17) \u00b7 ISO 27001 auditoria", done: false },
  ];
  roadmap.forEach(({ q, items, done }, i) => {
    const ry = 1.9 + i * 0.9;
    const dot = done ? C.green : C.gold;
    s.addShape(pres.shapes.OVAL, {
      x: 5.12, y: ry + 0.04, w: 0.34, h: 0.34, fill: { color: dot }, line: { color: dot },
    });
    s.addText(done ? "V" : (i + 1).toString(), {
      x: 5.12, y: ry + 0.02, w: 0.34, h: 0.34, fontSize: 11, fontFace: "Calibri",
      color: C.navy, bold: true, align: "center",
    });
    s.addText(q, {
      x: 5.55, y: ry, w: 1.3, h: 0.3, fontSize: 13, fontFace: "Calibri", color: dot, bold: true,
    });
    s.addText(items, {
      x: 5.55, y: ry + 0.32, w: 4.1, h: 0.46, fontSize: 10, fontFace: "Calibri", color: C.bodyText,
    });
  });

  addDarkFooter(s, 13, TOTAL);

  s.addNotes(
    "HIZLARIAREN GIDOIA - 13. DIAPOSITIBA (2 min)\n\n" +
    "Segurtasun-inbertsioak zergatik merezi duen ulertzeko, zifrak behar dira.\n\n" +
    "NIS2 Direktibak Zabala Gailetak bezalako enpresa bat isun-arriskuan " +
    "jartzen du: fakturazioa %2ra arte. Hori 10 milioi eurotik gora egon " +
    "daiteke. Incidente bakar batek ekar dezakeen kostua, isunaz gain, " +
    "erreputazioa, bezero galtzea eta prentsa negatiboa gehituz gero.\n\n" +
    "Gure inbertsioak arrisku hori kudeatzea du helburu. Ez da segurtasuna " +
    "soilik: da arrisku finantzarioa minimizatzea.\n\n" +
    "Bide-orriaren aldetik, Q1 berdea da: NIS2 hasierako auditoriak eta MFA " +
    "inplementazioa jadanik eginda. Q2an, EDR sistema, SIEM 24/7 eta CSIRT " +
    "barne-taldea. Q3an, SL3 IEC 62443rako eta pentest urtekoa. Q4an, " +
    "urriaren 17an, NIS2 betetze osoa.\n\n" +
    "Dena planifikatuta dago. Dena neurgarria da. Dena zuen negozioaren " +
    "etorkizunaren alde doa."
  );
}

// ====================================================================
// SLIDE 14: ONDORIOAK ETA GALDERAK (closing dark)
// ====================================================================
{
  let s = pres.addSlide();
  s.background = { color: C.navy };

  // Right band + gold edge
  s.addShape(pres.shapes.RECTANGLE, {
    x: 7.3, y: 0, w: 2.7, h: 5.625, fill: { color: C.navyMid }, line: { color: C.navyMid },
  });
  s.addShape(pres.shapes.RECTANGLE, {
    x: 9.68, y: 0, w: 0.32, h: 5.625, fill: { color: C.gold }, line: { color: C.gold },
  });
  s.addShape(pres.shapes.RECTANGLE, {
    x: 0, y: 5.12, w: 7.3, h: 0.06, fill: { color: C.gold }, line: { color: C.gold },
  });

  s.addText("06 \u00b7 ONDORIOAK ETA GALDERAK", {
    x: 0.5, y: 0.42, w: 6.6, h: 0.3, fontSize: 10, fontFace: "Calibri",
    color: C.gold, bold: true, charSpacing: 3,
  });
  s.addText("Zabala Gailetak,\nSeguru eta Etorkizunerako Prest", {
    x: 0.5, y: 0.82, w: 6.6, h: 1.22, fontSize: 34, fontFace: "Georgia",
    color: C.white, bold: true,
  });

  // Gold divider
  s.addShape(pres.shapes.RECTANGLE, {
    x: 0.5, y: 2.18, w: 1.8, h: 0.06, fill: { color: C.gold }, line: { color: C.gold },
  });

  // Summary bullets
  const summary = [
    "Zero Trust + 5 VLAN segmentazio \u2192 OT sarea erabat babestuta",
    "Pentesting: 0 arazo kritiko \u2014 Aurkikuntzak guztiak konponduta",
    "Wazuh SIEM + Honeypots: Monitorizazioa 24/7, incidenteekiko prest",
    "RGPD %100 \u00b7 ISO 27001 %93 \u00b7 NIS2 bidean (2026ko Urria)",
  ];
  summary.forEach((pt, i) => {
    s.addShape(pres.shapes.OVAL, {
      x: 0.5, y: 2.4 + i * 0.6, w: 0.22, h: 0.22, fill: { color: C.gold }, line: { color: C.gold },
    });
    s.addText(pt, {
      x: 0.88, y: 2.37 + i * 0.6, w: 6.22, h: 0.34, fontSize: 12, fontFace: "Calibri", color: "D4E4F7",
    });
  });

  // Credits
  s.addText("Zibersegurtasun Aholkularitza Taldea  \u00b7  2026ko Otsaila", {
    x: 0.5, y: 5.2, w: 6.6, h: 0.26, fontSize: 10, fontFace: "Calibri", color: C.midGray,
  });

  // Right panel: Q&A
  s.addText("GALDERAK?", {
    x: 7.42, y: 1.7, w: 2.15, h: 0.52, fontSize: 24, fontFace: "Georgia",
    color: C.gold, bold: true, align: "center",
  });
  s.addText(
    "Zuzendaritza\nBatzordeak eta\nCEO jaunak\nnahi dituzten\ngalderak\njasotzeko\nprest gaude.",
    {
      x: 7.42, y: 2.32, w: 2.15, h: 2.4, fontSize: 13, fontFace: "Calibri",
      color: C.bodyText, align: "center",
    }
  );

  s.addNotes(
    "HIZLARIAREN GIDOIA - 14. DIAPOSITIBA (3 min)\n\n" +
    "Laburbilduz, jaun-andreok.\n\n" +
    "Zabala Gailetak proiektu honetan lortu genuena ez da soilik teknika. " +
    "Negozio baten etorkizuna babestu dugu.\n\n" +
    "Industria 4.0rako saltoa lasaitasunez egin dezakezue orain: bulegoko " +
    "ordenagailua eta galletagintzako PLC isolatuta daude, baina elkar " +
    "komunikatzen dira era seguruan. Ehun eta hogei langileek tresna digitalak " +
    "dituzte, babestuta eta erabilterrazak. SIEM sistema 24/7 aktibo dago, " +
    "zaintzen eta alertatzen.\n\n" +
    "Legez, RGPD beteta dago. NIS2 bidean, urriaren 17rako prest. " +
    "IEC 62443n SL2 lortu dugu, SL3rako bidean.\n\n" +
    "Eta probatu dugu: pentestingak 0 arazo kritiko erakutsi du. Ez da " +
    "zorte kontua; planaren emaitza da.\n\n" +
    "Hemen gelditzen naiz. Galderak? Zabala Gailetak-en segurtasun-etorkizunaz " +
    "hitz egiteko prest gaude.\n\n" +
    "Eskerrik asko."
  );
}

// ====================================================================
// WRITE OUTPUT
// ====================================================================
pres.writeFile({ fileName: "Zabala_Gailetak_Zibersegurtasun_Entrega_2026.pptx" })
  .then(() => console.log("Presentazioa sortuta: Zabala_Gailetak_Zibersegurtasun_Entrega_2026.pptx"))
  .catch(err => { console.error("Errorea:", err); process.exit(1); });
