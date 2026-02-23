const pptxgen = require("pptxgenjs");

// Kolore paleta: Midnight Executive + urrezko azentua
const C_PRIMARY = "1E3A5F";    // Navy blue
const C_SECONDARY = "CADCFC";  // Ice blue
const C_ACCENT = "D4AF37";     // Gold
const C_WHITE = "FFFFFF";
const C_LIGHT = "F5F5F5";
const C_TEXT = "363636";
const C_SUCCESS = "2E7D32";    // Green
const C_WARNING = "FF8F00";    // Orange
const C_DANGER = "CC0000";     // Red

let pres = new pptxgen();
pres.layout = 'LAYOUT_16x9';
pres.author = 'Zabala Gailetak - Zibersegurtasun Taldea';
pres.title = 'Zibersegurtasun Proiektu Integrala';
pres.subject = 'Zuzendaritzari aurkezpena';

// ==================== SLIDE MASTER DEFINITIONS ====================
pres.defineSlideMaster({
  title: 'TITLE_SLIDE',
  background: { color: C_PRIMARY },
  objects: [
    { rect: { x: 0, y: 0, w: '100%', h: '100%', fill: { color: C_PRIMARY } } },
    { line: { x: 0.5, y: 4.5, w: 9, h: 0, line: { color: C_ACCENT, width: 3 } } }
  ]
});

pres.defineSlideMaster({
  title: 'SECTION_SLIDE',
  background: { color: C_PRIMARY },
  objects: [
    { rect: { x: 0, y: 0, w: '100%', h: '100%', fill: { color: C_PRIMARY } } },
    { rect: { x: 0, y: 0, w: 0.15, h: '100%', fill: { color: C_ACCENT } } }
  ]
});

pres.defineSlideMaster({
  title: 'CONTENT_SLIDE',
  background: { color: C_WHITE },
  objects: [
    { rect: { x: 0, y: 0, w: '100%', h: 0.08, fill: { color: C_PRIMARY } } },
    { rect: { x: 0, y: 5.55, w: '100%', h: 0.08, fill: { color: C_PRIMARY } } }
  ]
});

// ==================== FUNTZIO LAGUNTZAILEAK ====================
function addBulletPoint(slide, text, x, y, w, options = {}) {
  const color = options.color || C_TEXT;
  const fontSize = options.fontSize || 14;
  const bold = options.bold || false;
  
  slide.addText([
    { text: "•  ", options: { color: C_ACCENT, fontSize: fontSize, bold: true } },
    { text: text, options: { color: color, fontSize: fontSize, bold: bold } }
  ], { x: x, y: y, w: w, h: 0.4 });
}

function addNumberedPoint(slide, number, text, x, y, w) {
  slide.addText([
    { text: number + ". ", options: { color: C_ACCENT, fontSize: 16, bold: true } },
    { text: text, options: { color: C_TEXT, fontSize: 14 } }
  ], { x: x, y: y, w: w, h: 0.4 });
}

// ==================== 1. PORTADA ====================
let slide1 = pres.addSlide({ masterName: 'TITLE_SLIDE' });
slide1.addText("ZABALA GAILETAK", {
  x: 0.5, y: 1.5, w: 9, h: 1,
  fontSize: 48, bold: true, color: C_WHITE, align: 'center'
});
slide1.addText("Zibersegurtasun Proiektu Integrala", {
  x: 0.5, y: 2.6, w: 9, h: 0.8,
  fontSize: 28, color: C_ACCENT, align: 'center'
});
slide1.addText("Aurkezpena Zuzendaritzari eta Zuzendari Nagusiari", {
  x: 0.5, y: 3.5, w: 9, h: 0.6,
  fontSize: 18, color: C_SECONDARY, align: 'center'
});
slide1.addText("2026ko Otsaila", {
  x: 0.5, y: 5, w: 9, h: 0.5,
  fontSize: 16, color: C_SECONDARY, align: 'center'
});

// ==================== 2. AURKIBIDEA ====================
let slide2 = pres.addSlide({ masterName: 'CONTENT_SLIDE' });
slide2.addText("AURKIBIDEA", { x: 0.5, y: 0.3, w: 9, h: 0.6, fontSize: 32, bold: true, color: C_PRIMARY });

const sections = [
  { num: "1", title: "TESTUINGURUA ETA ERRONKA", time: "4 min", color: C_PRIMARY },
  { num: "2", title: "GURE ESTRATEGIA: ZERO TRUST", time: "6 min", color: C_PRIMARY },
  { num: "3", title: "BERRIKUNTZA SEGURUA ETA OPERAZIOAK", time: "5 min", color: C_PRIMARY },
  { num: "4", title: "ERASOEN AURKAKO PREBENTZIOA", time: "7 min", color: C_PRIMARY },
  { num: "5", title: "BETETZE LEGALA ETA ETORKIZUNA", time: "5 min", color: C_PRIMARY },
  { num: "6", title: "ONDORIOAK ETA GALDERAK", time: "3 min", color: C_ACCENT }
];

sections.forEach((sec, i) => {
  const y = 1.1 + (i * 0.7);
  slide2.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: y, w: 0.4, h: 0.4, fill: { color: sec.color } });
  slide2.addText(sec.num, { x: 0.5, y: y, w: 0.4, h: 0.4, fontSize: 18, bold: true, color: C_WHITE, align: 'center', valign: 'middle' });
  slide2.addText(sec.title, { x: 1.1, y: y, w: 6, h: 0.4, fontSize: 16, color: C_TEXT, valign: 'middle' });
  slide2.addText(sec.time, { x: 7.5, y: y, w: 1.5, h: 0.4, fontSize: 14, color: "666666", align: 'right', valign: 'middle' });
});

// ==================== 3. ATALA 1: TESTUINGURUA ====================
let slide3 = pres.addSlide({ masterName: 'SECTION_SLIDE' });
slide3.addText("1. TESTUINGURUA", { x: 0.7, y: 2, w: 9, h: 1, fontSize: 44, bold: true, color: C_WHITE });
slide3.addText("ETA ERRONKA", { x: 0.7, y: 3, w: 9, h: 0.8, fontSize: 36, color: C_ACCENT });
slide3.addText("(4 minutu)", { x: 0.7, y: 4, w: 9, h: 0.5, fontSize: 18, color: C_SECONDARY });

// ==================== 4. ZABALA GAILETAK ====================
let slide4 = pres.addSlide({ masterName: 'CONTENT_SLIDE' });
slide4.addText("Zabala Gailetak: Tradizioa eta Etorkizuna", { x: 0.5, y: 0.3, w: 9, h: 0.6, fontSize: 28, bold: true, color: C_PRIMARY });

// Kardak
slide4.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 1.1, w: 2.8, h: 1.8, fill: { color: C_LIGHT }, shadow: { type: "outer", blur: 6, offset: 2, angle: 135, opacity: 0.15, color: "000000" } });
slide4.addText("120", { x: 0.5, y: 1.3, w: 2.8, h: 0.8, fontSize: 48, bold: true, color: C_PRIMARY, align: 'center' });
slide4.addText("Langile", { x: 0.5, y: 2.2, w: 2.8, h: 0.5, fontSize: 14, color: C_TEXT, align: 'center' });

slide4.addShape(pres.shapes.RECTANGLE, { x: 3.6, y: 1.1, w: 2.8, h: 1.8, fill: { color: C_LIGHT }, shadow: { type: "outer", blur: 6, offset: 2, angle: 135, opacity: 0.15, color: "000000" } });
slide4.addText("6", { x: 3.6, y: 1.3, w: 2.8, h: 0.8, fontSize: 48, bold: true, color: C_PRIMARY, align: 'center' });
slide4.addText("Zuzendaritza", { x: 3.6, y: 2.2, w: 2.8, h: 0.5, fontSize: 14, color: C_TEXT, align: 'center' });

slide4.addShape(pres.shapes.RECTANGLE, { x: 6.7, y: 1.1, w: 2.8, h: 1.8, fill: { color: C_LIGHT }, shadow: { type: "outer", blur: 6, offset: 2, angle: 135, opacity: 0.15, color: "000000" } });
slide4.addText("5", { x: 6.7, y: 1.3, w: 2.8, h: 0.8, fontSize: 48, bold: true, color: C_PRIMARY, align: 'center' });
slide4.addText("IKT Taldea", { x: 6.7, y: 2.2, w: 2.8, h: 0.5, fontSize: 14, color: C_TEXT, align: 'center' });

addBulletPoint(slide4, "Euskal Herriko gaileta tradizioa Europako merkatuetara", 0.5, 3.2, 9);
addBulletPoint(slide4, "Industria 4.0-ra igarotzea: digitalizazioa + segurtasuna", 0.5, 3.7, 9);
addBulletPoint(slide4, "Erronka: ekoizpena babestu, baina modernizatu", 0.5, 4.2, 9, { bold: true });

// ==================== 5. ARRISKU ESTRATEGIKOA ====================
let slide5 = pres.addSlide({ masterName: 'CONTENT_SLIDE' });
slide5.addText("Arrisku Estrategikoa: IT eta OT Sareen Batzea", { x: 0.5, y: 0.3, w: 9, h: 0.6, fontSize: 26, bold: true, color: C_PRIMARY });

slide5.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 1.1, w: 4.2, h: 2.2, fill: { color: "FFF3E0" } });
slide5.addText("OT = Operational Technology", { x: 0.6, y: 1.2, w: 4, h: 0.4, fontSize: 14, bold: true, color: C_WARNING });
slide5.addText("• PLC-ak (Programmable Logic Controllers)", { x: 0.6, y: 1.6, w: 4, h: 0.35, fontSize: 12 });
slide5.addText("• SCADA sistemak", { x: 0.6, y: 1.95, w: 4, h: 0.35, fontSize: 12 });
slide5.addText("• Ekoizpen-makineria", { x: 0.6, y: 2.3, w: 4, h: 0.35, fontSize: 12 });

slide5.addShape(pres.shapes.RECTANGLE, { x: 5.2, y: 1.1, w: 4.3, h: 2.2, fill: { color: "FFEBEE" } });
slide5.addText("Arrisku Kritikoa", { x: 5.3, y: 1.2, w: 4, h: 0.4, fontSize: 14, bold: true, color: C_DANGER });
slide5.addText("Erasotzaile batek sartuz gero...", { x: 5.3, y: 1.6, w: 4, h: 0.35, fontSize: 12 });
slide5.addText("120 langileen SEGURTASUN FISIKOA arriskuan", { x: 5.3, y: 2, w: 4, h: 0.35, fontSize: 12, bold: true, color: C_DANGER });

addBulletPoint(slide5, "Aurkikuntza: PLC-ak Internet zabalean espostuta zeuden!", 0.5, 3.5, 9, { color: C_DANGER, bold: true });
addBulletPoint(slide5, "Adibideak: Colonial Pipeline (4,4M$), Norsk Hydro", 0.5, 4, 9);
addBulletPoint(slide5, "Modbus TCP portua (502) irekita kanpoarekin", 0.5, 4.5, 9);

// ==================== 6. ATALA 2: ESTRATEGIA ====================
let slide6 = pres.addSlide({ masterName: 'SECTION_SLIDE' });
slide6.addText("2. GURE ESTRATEGIA", { x: 0.7, y: 1.8, w: 9, h: 1, fontSize: 40, bold: true, color: C_WHITE });
slide6.addText("ZERO TRUST", { x: 0.7, y: 2.7, w: 9, h: 0.9, fontSize: 48, bold: true, color: C_ACCENT });
slide6.addText("ETA ARKITEKTURA SEGURUA", { x: 0.7, y: 3.6, w: 9, h: 0.6, fontSize: 28, color: C_SECONDARY });
slide6.addText("(6 minutu)", { x: 0.7, y: 4.3, w: 9, h: 0.5, fontSize: 18, color: C_SECONDARY });

// ==================== 7. ZERO TRUST ====================
let slide7 = pres.addSlide({ masterName: 'CONTENT_SLIDE' });
slide7.addText("Zero Trust Printzipioa: Inork ez du fidatzen", { x: 0.5, y: 0.3, w: 9, h: 0.6, fontSize: 26, bold: true, color: C_PRIMARY });

slide7.addText("\"Ez dago sare 'barne segurrik'\"", { x: 0.5, y: 1.1, w: 9, h: 0.6, fontSize: 24, italic: true, color: C_ACCENT, align: 'center' });

// VLAN diagrama simplificado
slide7.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 1.9, w: 1.6, h: 1, fill: { color: "E3F2FD" } });
slide7.addText("VLAN 10", { x: 0.5, y: 2, w: 1.6, h: 0.4, fontSize: 12, bold: true, align: 'center' });
slide7.addText("Bulegoa", { x: 0.5, y: 2.4, w: 1.6, h: 0.4, fontSize: 11, align: 'center' });

slide7.addShape(pres.shapes.RECTANGLE, { x: 2.4, y: 1.9, w: 1.6, h: 1, fill: { color: "F3E5F5" } });
slide7.addText("VLAN 20", { x: 2.4, y: 2, w: 1.6, h: 0.4, fontSize: 12, bold: true, align: 'center' });
slide7.addText("Zerbitzariak", { x: 2.4, y: 2.4, w: 1.6, h: 0.4, fontSize: 11, align: 'center' });

slide7.addShape(pres.shapes.RECTANGLE, { x: 4.3, y: 1.9, w: 1.6, h: 1, fill: { color: "FFF3E0" } });
slide7.addText("VLAN 30", { x: 4.3, y: 2, w: 1.6, h: 0.4, fontSize: 12, bold: true, align: 'center' });
slide7.addText("DMZ", { x: 4.3, y: 2.4, w: 1.6, h: 0.4, fontSize: 11, align: 'center' });

slide7.addShape(pres.shapes.RECTANGLE, { x: 6.2, y: 1.9, w: 1.6, h: 1, fill: { color: "FFEBEE" } });
slide7.addText("VLAN 50", { x: 6.2, y: 2, w: 1.6, h: 0.4, fontSize: 12, bold: true, align: 'center' });
slide7.addText("OT/SCADA", { x: 6.2, y: 2.4, w: 1.6, h: 0.4, fontSize: 11, align: 'center' });

slide7.addShape(pres.shapes.RECTANGLE, { x: 8.1, y: 1.9, w: 1.4, h: 1, fill: { color: "E8F5E9" } });
slide7.addText("VLAN 99", { x: 8.1, y: 2, w: 1.4, h: 0.4, fontSize: 12, bold: true, align: 'center' });
slide7.addText("Honeypot", { x: 8.1, y: 2.4, w: 1.4, h: 0.4, fontSize: 11, align: 'center' });

addBulletPoint(slide7, "Fortinet FortiGate 200F: trafiko guztia kontrolatuta", 0.5, 3.2, 9);
addBulletPoint(slide7, "IT eta OT artean ez dago zubi zuzenik", 0.5, 3.7, 9, { bold: true });
addBulletPoint(slide7, "Europako Banku Zentralak eta BBVA-k erabiltzen duten estrategia bera", 0.5, 4.2, 9);

// ==================== 8. PURDUE EREDUA ====================
let slide8 = pres.addSlide({ masterName: 'CONTENT_SLIDE' });
slide8.addText("Purdue Eredua: Fabrikaren Babesa Geruzaka", { x: 0.5, y: 0.3, w: 9, h: 0.6, fontSize: 26, bold: true, color: C_PRIMARY });

// Niveles Purdue
const levels = [
  { num: "4", name: "Enpresa Sarea", desc: "ERP, Emaila, Web", y: 1, color: "E3F2FD" },
  { num: "3.5", name: "DMZ Industriala", desc: "Historialariak, Patch", y: 1.6, color: "FFF8E1" },
  { num: "3", name: "Ekoizpen Operazioak", desc: "SCADA, HMI", y: 2.2, color: "FFF3E0" },
  { num: "2", name: "Kontrol Sarea", desc: "PLC-ak, RTU-ak", y: 2.8, color: "FFEBEE" },
  { num: "1-0", name: "Prozesua", desc: "Sentsoreak, Aktuadoreak", y: 3.4, color: "FCE4EC" }
];

levels.forEach(l => {
  slide8.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: l.y, w: 3, h: 0.5, fill: { color: l.color } });
  slide8.addText("L" + l.num, { x: 0.5, y: l.y, w: 0.5, h: 0.5, fontSize: 12, bold: true, align: 'center', valign: 'middle' });
  slide8.addText(l.name, { x: 1.1, y: l.y, w: 1.5, h: 0.5, fontSize: 11, bold: true, valign: 'middle' });
  slide8.addText(l.desc, { x: 2.7, y: l.y, w: 1.5, h: 0.5, fontSize: 10, color: "666666", valign: 'middle' });
});

slide8.addShape(pres.shapes.RECTANGLE, { x: 4, y: 1, w: 5.5, h: 3, fill: { color: C_LIGHT } });
slide8.addText("Tresna Nagusiak:", { x: 4.2, y: 1.1, w: 5, h: 0.4, fontSize: 14, bold: true, color: C_PRIMARY });
addBulletPoint(slide8, "OpenPLC: PLC-ak simulatzeko", 4.2, 1.6, 5);
addBulletPoint(slide8, "ScadaBR: HMI interfazea", 4.2, 2.1, 5);
addBulletPoint(slide8, "Node-RED: Datuen integrazioa", 4.2, 2.6, 5);
addBulletPoint(slide8, "VPN + MFA: Sarbide segurua", 4.2, 3.1, 5);

addBulletPoint(slide8, "Irakurketa-soila modua: idazteko baimena prozesu formalarekin", 0.5, 4.3, 9, { bold: true });

// ==================== 9. AUTOMATIZAZIOA ====================
let slide9 = pres.addSlide({ masterName: 'CONTENT_SLIDE' });
slide9.addText("Zerbitzarien Bastionatzea eta Automatizazioa", { x: 0.5, y: 0.3, w: 9, h: 0.6, fontSize: 26, bold: true, color: C_PRIMARY });

slide9.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 1.1, w: 2.8, h: 2.5, fill: { color: "E8F5E9" } });
slide9.addText("Ansible", { x: 0.5, y: 1.2, w: 2.8, h: 0.5, fontSize: 18, bold: true, color: C_SUCCESS, align: 'center' });
slide9.addText("• 50+ zerbitzari", { x: 0.6, y: 1.8, w: 2.6, h: 0.35, fontSize: 12 });
slide9.addText("• Konfigurazio automatizatua", { x: 0.6, y: 2.15, w: 2.6, h: 0.35, fontSize: 12 });
slide9.addText("• Kode bidezko kudeaketa", { x: 0.6, y: 2.5, w: 2.6, h: 0.35, fontSize: 12 });
slide9.addText("• Ez dago 'ahaztutako' zerbitzaririk", { x: 0.6, y: 2.85, w: 2.6, h: 0.35, fontSize: 12, bold: true });

slide9.addShape(pres.shapes.RECTANGLE, { x: 3.6, y: 1.1, w: 2.8, h: 2.5, fill: { color: "E3F2FD" } });
slide9.addText("Proxmox/Isard", { x: 3.6, y: 1.2, w: 2.8, h: 0.5, fontSize: 18, bold: true, color: C_PRIMARY, align: 'center' });
slide9.addText("• Birtualizazioa", { x: 3.7, y: 1.8, w: 2.6, h: 0.35, fontSize: 12 });
slide9.addText("• Isolamendu segurua", { x: 3.7, y: 2.15, w: 2.6, h: 0.35, fontSize: 12 });
slide9.addText("• Erraz eskalatzeko", { x: 3.7, y: 2.5, w: 2.6, h: 0.35, fontSize: 12 });
slide9.addText("• Backup automatikoak", { x: 3.7, y: 2.85, w: 2.6, h: 0.35, fontSize: 12 });

slide9.addShape(pres.shapes.RECTANGLE, { x: 6.7, y: 1.1, w: 2.8, h: 2.5, fill: { color: "FFF3E0" } });
slide9.addText("Docker", { x: 6.7, y: 1.2, w: 2.8, h: 0.5, fontSize: 18, bold: true, color: C_WARNING, align: 'center' });
slide9.addText("• Kontenedore isolatuak", { x: 6.8, y: 1.8, w: 2.6, h: 0.35, fontSize: 12 });
slide9.addText("• Aplikazioak banatuta", { x: 6.8, y: 2.15, w: 2.6, h: 0.35, fontSize: 12 });
slide9.addText("• Garraiatze azkarra", { x: 6.8, y: 2.5, w: 2.6, h: 0.35, fontSize: 12 });
slide9.addText("• Segurtasuna handitua", { x: 6.8, y: 2.85, w: 2.6, h: 0.35, fontSize: 12 });

slide9.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 3.9, w: 9, h: 0.8, fill: { color: C_SUCCESS } });
slide9.addText("Onura: Errore konfigurazioak %90 murriztu dira", { x: 0.5, y: 4, w: 9, h: 0.6, fontSize: 18, bold: true, color: C_WHITE, align: 'center', valign: 'middle' });

// ==================== 10. ATALA 3: BERRIKUNTZA ====================
let slide10 = pres.addSlide({ masterName: 'SECTION_SLIDE' });
slide10.addText("3. BERRIKUNTZA SEGURUA", { x: 0.7, y: 1.8, w: 9, h: 1, fontSize: 40, bold: true, color: C_WHITE });
slide10.addText("ETA EGUNEROKO OPERAZIOAK", { x: 0.7, y: 2.8, w: 9, h: 0.8, fontSize: 32, color: C_SECONDARY });
slide10.addText("(5 minutu)", { x: 0.7, y: 3.7, w: 9, h: 0.5, fontSize: 18, color: C_SECONDARY });

// ==================== 11. HR PORTAL ====================
let slide11 = pres.addSlide({ masterName: 'CONTENT_SLIDE' });
slide11.addText("Langileen Atari Berria: Segurtasuna eta Erraztasuna", { x: 0.5, y: 0.3, w: 9, h: 0.6, fontSize: 24, bold: true, color: C_PRIMARY });

slide11.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 1.1, w: 4.2, h: 2.8, fill: { color: C_LIGHT } });
slide11.addText("HR Portal", { x: 0.5, y: 1.2, w: 4.2, h: 0.5, fontSize: 18, bold: true, color: C_PRIMARY, align: 'center' });
addBulletPoint(slide11, "120 langileen kudeaketa", 0.7, 1.8, 3.8);
addBulletPoint(slide11, "Nóminak ikusteko", 0.7, 2.2, 3.8);
addBulletPoint(slide11, "Oporrak eskatzeko", 0.7, 2.6, 3.8);
addBulletPoint(slide11, "Dokumentazioa kudeatzeko", 0.7, 3, 3.8);

slide11.addShape(pres.shapes.RECTANGLE, { x: 5, y: 1.1, w: 4.5, h: 2.8, fill: { color: "E3F2FD" } });
slide11.addText("Segurtasun Neurriak", { x: 5, y: 1.2, w: 4.5, h: 0.5, fontSize: 18, bold: true, color: C_PRIMARY, align: 'center' });
addBulletPoint(slide11, "JWT tokenak", 5.2, 1.8, 4);
addBulletPoint(slide11, "MFA (bi faktoreak)", 5.2, 2.2, 4);
addBulletPoint(slide11, "5 rol desberdin", 5.2, 2.6, 4);
addBulletPoint(slide11, "Pribilegio gutxienekoa", 5.2, 3, 4);

slide11.addText("5 ROL:", { x: 0.5, y: 4.2, w: 1, h: 0.4, fontSize: 12, bold: true, color: C_PRIMARY });
const roles = ["ADMIN", "RRHH MGR", "JEFE SECCIÓN", "EMPLEADO", "AUDITOR"];
roles.forEach((role, i) => {
  slide11.addShape(pres.shapes.RECTANGLE, { x: 1.5 + (i * 1.6), y: 4.1, w: 1.5, h: 0.5, fill: { color: C_PRIMARY } });
  slide11.addText(role, { x: 1.5 + (i * 1.6), y: 4.1, w: 1.5, h: 0.5, fontSize: 10, color: C_WHITE, align: 'center', valign: 'middle' });
});

// ==================== 12. MUGIKOR APP ====================
let slide12 = pres.addSlide({ masterName: 'CONTENT_SLIDE' });
slide12.addText("Mugikor Aplikazioa: Android Segurua", { x: 0.5, y: 0.3, w: 9, h: 0.6, fontSize: 26, bold: true, color: C_PRIMARY });

slide12.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 1.1, w: 2.8, h: 2.2, fill: { color: "E8F5E9" } });
slide12.addText("Teknologia", { x: 0.5, y: 1.2, w: 2.8, h: 0.4, fontSize: 14, bold: true, color: C_SUCCESS, align: 'center' });
slide12.addText("Kotlin 2.0", { x: 0.5, y: 1.7, w: 2.8, h: 0.4, fontSize: 16, bold: true, align: 'center' });
slide12.addText("Jetpack Compose", { x: 0.5, y: 2.1, w: 2.8, h: 0.4, fontSize: 14, align: 'center' });
slide12.addText("Material Design 3", { x: 0.5, y: 2.5, w: 2.8, h: 0.4, fontSize: 14, align: 'center' });

slide12.addShape(pres.shapes.RECTANGLE, { x: 3.6, y: 1.1, w: 2.8, h: 2.2, fill: { color: "FFF3E0" } });
slide12.addText("Certificate Pinning", { x: 3.6, y: 1.2, w: 2.8, h: 0.4, fontSize: 14, bold: true, color: C_WARNING, align: 'center' });
slide12.addText("Zerbitzaria", { x: 3.6, y: 1.7, w: 2.8, h: 0.4, fontSize: 14, align: 'center' });
slide12.addText("ezin daiteke", { x: 3.6, y: 2.1, w: 2.8, h: 0.4, fontSize: 14, align: 'center' });
slide12.addText("falsifikatu", { x: 3.6, y: 2.5, w: 2.8, h: 0.4, fontSize: 14, bold: true, color: C_WARNING, align: 'center' });

slide12.addShape(pres.shapes.RECTANGLE, { x: 6.7, y: 1.1, w: 2.8, h: 2.2, fill: { color: "E3F2FD" } });
slide12.addText("Biometria", { x: 6.7, y: 1.2, w: 2.8, h: 0.4, fontSize: 14, bold: true, color: C_PRIMARY, align: 'center' });
slide12.addText("• Hatz-marka", { x: 6.8, y: 1.7, w: 2.6, h: 0.35, fontSize: 12 });
slide12.addText("• Aurpegia", { x: 6.8, y: 2.1, w: 2.6, h: 0.35, fontSize: 12 });
slide12.addText("• PIN seguruak", { x: 6.8, y: 2.5, w: 2.6, h: 0.35, fontSize: 12 });

slide12.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 3.6, w: 9, h: 0.8, fill: { color: C_PRIMARY } });
slide12.addText("Datuak enkriptatuta gordetzen dira gailuan: telefonoa galdu arren, informazioa segurua", 
  { x: 0.5, y: 3.7, w: 9, h: 0.6, fontSize: 14, color: C_WHITE, align: 'center', valign: 'middle' });

// ==================== 13. ATALA 4: DEFENTSA ====================
let slide13 = pres.addSlide({ masterName: 'SECTION_SLIDE' });
slide13.addText("4. ERASOEN AURKAKO", { x: 0.7, y: 1.8, w: 9, h: 1, fontSize: 40, bold: true, color: C_WHITE });
slide13.addText("PREBENTZIOA ETA ERANTZUNA", { x: 0.7, y: 2.8, w: 9, h: 0.8, fontSize: 32, color: C_SECONDARY });
slide13.addText("(7 minutu)", { x: 0.7, y: 3.7, w: 9, h: 0.5, fontSize: 18, color: C_SECONDARY });

// ==================== 14. HACKING ETikoA ====================
let slide14 = pres.addSlide({ masterName: 'CONTENT_SLIDE' });
slide14.addText("Hacking Etikoaren Emaitzak: Gure Defentsak Frogan", { x: 0.5, y: 0.3, w: 9, h: 0.6, fontSize: 24, bold: true, color: C_PRIMARY });

// Resultados
slide14.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 1.1, w: 1.6, h: 1.2, fill: { color: C_SUCCESS } });
slide14.addText("0", { x: 0.5, y: 1.2, w: 1.6, h: 0.6, fontSize: 36, bold: true, color: C_WHITE, align: 'center' });
slide14.addText("Kritiko", { x: 0.5, y: 1.85, w: 1.6, h: 0.4, fontSize: 12, color: C_WHITE, align: 'center' });

slide14.addShape(pres.shapes.RECTANGLE, { x: 2.4, y: 1.1, w: 1.6, h: 1.2, fill: { color: C_WARNING } });
slide14.addText("2", { x: 2.4, y: 1.2, w: 1.6, h: 0.6, fontSize: 36, bold: true, color: C_WHITE, align: 'center' });
slide14.addText("Altu (konponduak)", { x: 2.4, y: 1.85, w: 1.6, h: 0.4, fontSize: 11, color: C_WHITE, align: 'center' });

slide14.addShape(pres.shapes.RECTANGLE, { x: 4.3, y: 1.1, w: 1.6, h: 1.2, fill: { color: "FFCC80" } });
slide14.addText("4", { x: 4.3, y: 1.2, w: 1.6, h: 0.6, fontSize: 36, bold: true, color: C_TEXT, align: 'center' });
slide14.addText("Ertain", { x: 4.3, y: 1.85, w: 1.6, h: 0.4, fontSize: 12, color: C_TEXT, align: 'center' });

slide14.addShape(pres.shapes.RECTANGLE, { x: 6.2, y: 1.1, w: 1.6, h: 1.2, fill: { color: "E0E0E0" } });
slide14.addText("3", { x: 6.2, y: 1.2, w: 1.6, h: 0.6, fontSize: 36, bold: true, color: C_TEXT, align: 'center' });
slide14.addText("Baxu", { x: 6.2, y: 1.85, w: 1.6, h: 0.4, fontSize: 12, color: C_TEXT, align: 'center' });

slide14.addShape(pres.shapes.RECTANGLE, { x: 8.1, y: 1.1, w: 1.4, h: 1.2, fill: { color: C_PRIMARY } });
slide14.addText("9", { x: 8.1, y: 1.2, w: 1.4, h: 0.6, fontSize: 36, bold: true, color: C_WHITE, align: 'center' });
slide14.addText("Guztira", { x: 8.1, y: 1.85, w: 1.4, h: 0.4, fontSize: 12, color: C_WHITE, align: 'center' });

addBulletPoint(slide14, "SQL Injection konpondua: prepared statements-ekin", 0.5, 2.6, 9);
addBulletPoint(slide14, "OT sarearen isolamendua: PLC-ak ez daude Internetean", 0.5, 3.1, 9);
addBulletPoint(slide14, "WiFi: WPA3 + sarbide baimenduen zerrenda", 0.5, 3.6, 9);
addBulletPoint(slide14, "Metodologia: PTES + OWASP Testing Guide v4.2", 0.5, 4.1, 9);

// ==================== 15. SIEM ETA MONITORIZAZIOA ====================
let slide15 = pres.addSlide({ masterName: 'CONTENT_SLIDE' });
slide15.addText("Monitorizazioa Denbora Errealean: SIEM eta Wazuh", { x: 0.5, y: 0.3, w: 9, h: 0.6, fontSize: 24, bold: true, color: C_PRIMARY });

slide15.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 1.1, w: 4.5, h: 2.5, fill: { color: "E3F2FD" } });
slide15.addText("ELK Stack + Wazuh", { x: 0.5, y: 1.2, w: 4.5, h: 0.5, fontSize: 16, bold: true, color: C_PRIMARY, align: 'center' });
addBulletPoint(slide15, "Log guztiak biltzen dira zentralizatuta", 0.7, 1.8, 4);
addBulletPoint(slide15, "Elasticsearch biltegiratzea", 0.7, 2.2, 4);
addBulletPoint(slide15, "Kibana bisualizazioak", 0.7, 2.6, 4);
addBulletPoint(slide15, "Logstash prozesamendua", 0.7, 3, 4);

slide15.addShape(pres.shapes.RECTANGLE, { x: 5.2, y: 1.1, w: 4.3, h: 2.5, fill: { color: "FFF3E0" } });
slide15.addText("15+ Alerta Arau", { x: 5.2, y: 1.2, w: 4.3, h: 0.5, fontSize: 16, bold: true, color: C_WARNING, align: 'center' });
addBulletPoint(slide15, "Saihesketak detektatzeko", 5.4, 1.8, 3.8);
addBulletPoint(slide15, "Autentikazio okerrak", 5.4, 2.2, 3.8);
addBulletPoint(slide15, "Prozesu susmagarriak", 5.4, 2.6, 3.8);
addBulletPoint(slide15, "USB konekzioak OT-an", 5.4, 3, 3.8);

addBulletPoint(slide15, "MITRE ATT&CK esparrua: erasoak nola antolatzen diren ulertzeko", 0.5, 3.9, 9, { bold: true });

// ==================== 16. HONEYPOT ====================
let slide16 = pres.addSlide({ masterName: 'CONTENT_SLIDE' });
slide16.addText("Honeypot-ak: Erasotzaileak Harrapatzeko Tranpak", { x: 0.5, y: 0.3, w: 9, h: 0.6, fontSize: 24, bold: true, color: C_PRIMARY });

slide16.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 1.1, w: 4.2, h: 2.5, fill: { color: "FCE4EC" } });
slide16.addText("T-Pot / Cowrie", { x: 0.5, y: 1.2, w: 4.2, h: 0.5, fontSize: 16, bold: true, color: "880E4F", align: 'center' });
addBulletPoint(slide16, "SSH zerbitzari faltsuak", 0.7, 1.8, 3.8);
addBulletPoint(slide16, "Telnet zerbitzari faltsuak", 0.7, 2.2, 3.8);
addBulletPoint(slide16, "Erasotzaileak erakartzen ditu", 0.7, 2.6, 3.8);
addBulletPoint(slide16, "Benetako sistemak babesten ditu", 0.7, 3, 3.8);

slide16.addShape(pres.shapes.RECTANGLE, { x: 4.9, y: 1.1, w: 4.6, h: 2.5, fill: { color: "E8F5E9" } });
slide16.addText("Conpot (OT Honeypot)", { x: 4.9, y: 1.2, w: 4.6, h: 0.5, fontSize: 16, bold: true, color: C_SUCCESS, align: 'center' });
addBulletPoint(slide16, "PLC faltsuak OT sarean", 5.1, 1.8, 4.2);
addBulletPoint(slide16, "Modbus TCP simulazioa", 5.1, 2.2, 4.2);
addBulletPoint(slide16, "Eraso teknikak ikasteko", 5.1, 2.6, 4.2);
addBulletPoint(slide16, "Mehatxu inteligentzia", 5.1, 3, 4.2);

slide16.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 3.8, w: 9, h: 0.8, fill: { color: C_PRIMARY } });
slide16.addText("Helburua: Erasotzaileak atzerapenarako erakartzea, benetako sistemak babesteko", 
  { x: 0.5, y: 3.9, w: 9, h: 0.6, fontSize: 14, color: C_WHITE, align: 'center', valign: 'middle' });

// ==================== 17. GERTAERA ERANTZUNA ====================
let slide17 = pres.addSlide({ masterName: 'CONTENT_SLIDE' });
slide17.addText("Gertaera-Erantzun Plana: Arazorako Prest", { x: 0.5, y: 0.3, w: 9, h: 0.6, fontSize: 26, bold: true, color: C_PRIMARY });

slide17.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 1.1, w: 2.1, h: 1.3, fill: { color: C_DANGER } });
slide17.addText("Kritikoa", { x: 0.5, y: 1.2, w: 2.1, h: 0.4, fontSize: 14, bold: true, color: C_WHITE, align: 'center' });
slide17.addText("15 min", { x: 0.5, y: 1.65, w: 2.1, h: 0.5, fontSize: 24, bold: true, color: C_WHITE, align: 'center' });

slide17.addShape(pres.shapes.RECTANGLE, { x: 2.8, y: 1.1, w: 2.1, h: 1.3, fill: { color: C_WARNING } });
slide17.addText("Altua", { x: 2.8, y: 1.2, w: 2.1, h: 0.4, fontSize: 14, bold: true, color: C_WHITE, align: 'center' });
slide17.addText("1 ordu", { x: 2.8, y: 1.65, w: 2.1, h: 0.5, fontSize: 24, bold: true, color: C_WHITE, align: 'center' });

slide17.addShape(pres.shapes.RECTANGLE, { x: 5.1, y: 1.1, w: 2.1, h: 1.3, fill: { color: "FFCC80" } });
slide17.addText("Ertaina", { x: 5.1, y: 1.2, w: 2.1, h: 0.4, fontSize: 14, bold: true, color: C_TEXT, align: 'center' });
slide17.addText("4 ordu", { x: 5.1, y: 1.65, w: 2.1, h: 0.5, fontSize: 24, bold: true, color: C_TEXT, align: 'center' });

slide17.addShape(pres.shapes.RECTANGLE, { x: 7.4, y: 1.1, w: 2.1, h: 1.3, fill: { color: "E0E0E0" } });
slide17.addText("Baxua", { x: 7.4, y: 1.2, w: 2.1, h: 0.4, fontSize: 14, bold: true, color: C_TEXT, align: 'center' });
slide17.addText("24 ordu", { x: 7.4, y: 1.65, w: 2.1, h: 0.5, fontSize: 24, bold: true, color: C_TEXT, align: 'center' });

slide17.addText("Erantzun-denborak (ISO 27001)", { x: 0.5, y: 2.55, w: 9, h: 0.3, fontSize: 10, color: "666666", align: 'center' });

addBulletPoint(slide17, "IR Taldea: 5 pertsona, rol argiak, 24/7 eskuragarritasuna", 0.5, 2.95, 9);
addBulletPoint(slide17, "Auzitegi-analisirako prest: ebidentziak biltzeko prozedurak", 0.5, 3.4, 9);
addBulletPoint(slide17, "Hiruhileko mahai-gaineko ariketak: prozedurak probatzeko", 0.5, 3.9, 9);

// ==================== 18. ATALA 5: BETETZEA ====================
let slide18 = pres.addSlide({ masterName: 'SECTION_SLIDE' });
slide18.addText("5. BETETZE LEGALA", { x: 0.7, y: 1.8, w: 9, h: 1, fontSize: 40, bold: true, color: C_WHITE });
slide18.addText("ETA ETORKIZUNA", { x: 0.7, y: 2.8, w: 9, h: 0.8, fontSize: 32, color: C_SECONDARY });
slide18.addText("(5 minutu)", { x: 0.7, y: 3.7, w: 9, h: 0.5, fontSize: 18, color: C_SECONDARY });

// ==================== 19. ISO 27001 ====================
let slide19 = pres.addSlide({ masterName: 'CONTENT_SLIDE' });
slide19.addText("ISO 27001: %93 Betetzea, Zero Auditaldi", { x: 0.5, y: 0.3, w: 9, h: 0.6, fontSize: 26, bold: true, color: C_PRIMARY });

slide19.addShape(pres.shapes.RECTANGLE, { x: 3.5, y: 1.1, w: 3, h: 3, fill: { color: C_PRIMARY } });
slide19.addText("%93", { x: 3.5, y: 1.6, w: 3, h: 1.2, fontSize: 72, bold: true, color: C_ACCENT, align: 'center' });
slide19.addText("87/93 kontrol", { x: 3.5, y: 3, w: 3, h: 0.6, fontSize: 18, color: C_WHITE, align: 'center' });

slide19.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 1.1, w: 2.7, h: 1.3, fill: { color: "E8F5E9" } });
slide19.addText("A.5 - A.7", { x: 0.5, y: 1.2, w: 2.7, h: 0.4, fontSize: 14, bold: true, align: 'center' });
slide19.addText("%100", { x: 0.5, y: 1.6, w: 2.7, h: 0.6, fontSize: 28, bold: true, color: C_SUCCESS, align: 'center' });

slide19.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 2.6, w: 2.7, h: 1.5, fill: { color: "E3F2FD" } });
slide19.addText("A.8 Teknologikoak", { x: 0.5, y: 2.7, w: 2.7, h: 0.4, fontSize: 14, bold: true, align: 'center' });
slide19.addText("%94", { x: 0.5, y: 3.1, w: 2.7, h: 0.6, fontSize: 28, bold: true, color: C_PRIMARY, align: 'center' });

slide19.addShape(pres.shapes.RECTANGLE, { x: 6.8, y: 1.1, w: 2.7, h: 1.3, fill: { color: "FFF3E0" } });
slide19.addText("2026 Q4", { x: 6.8, y: 1.2, w: 2.7, h: 0.4, fontSize: 14, bold: true, align: 'center' });
slide19.addText("Ziurtagiria", { x: 6.8, y: 1.6, w: 2.7, h: 0.6, fontSize: 18, bold: true, color: C_WARNING, align: 'center' });

slide19.addShape(pres.shapes.RECTANGLE, { x: 6.8, y: 2.6, w: 2.7, h: 1.5, fill: { color: C_LIGHT } });
slide19.addText("Hurrengo pausoak:", { x: 6.8, y: 2.7, w: 2.7, h: 0.4, fontSize: 12, bold: true, align: 'center' });
slide19.addText("Q2: DLP + maskaratzea", { x: 6.8, y: 3.1, w: 2.7, h: 0.3, fontSize: 10, align: 'center' });
slide19.addText("Q3: Aurre-auditoria", { x: 6.8, y: 3.4, w: 2.7, h: 0.3, fontSize: 10, align: 'center' });
slide19.addText("Q4: Ziurtagiria", { x: 6.8, y: 3.7, w: 2.7, h: 0.3, fontSize: 10, align: 'center' });

slide19.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 4.3, w: 9, h: 0.5, fill: { color: C_SUCCESS } });
slide19.addText("ISO 27001 da zibersegurtasunaren 'etiketa' munduan - bezeroek eta inbertitzaileek eskatzen dute", 
  { x: 0.5, y: 4.35, w: 9, h: 0.4, fontSize: 12, color: C_WHITE, align: 'center', valign: 'middle' });

// ==================== 20. GDPR ETA NIS2 ====================
let slide20 = pres.addSlide({ masterName: 'CONTENT_SLIDE' });
slide20.addText("GDPR eta NIS2: Zigorrak Saihesten", { x: 0.5, y: 0.3, w: 9, h: 0.6, fontSize: 26, bold: true, color: C_PRIMARY });

slide20.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 1.1, w: 4.2, h: 2.8, fill: { color: "E3F2FD" } });
slide20.addText("GDPR", { x: 0.5, y: 1.2, w: 4.2, h: 0.5, fontSize: 20, bold: true, color: C_PRIMARY, align: 'center' });
addBulletPoint(slide20, "72 orduko ohartarazpena", 0.7, 1.85, 3.8);
addBulletPoint(slide20, "AEPD-rekin harremanak", 0.7, 2.25, 3.8);
addBulletPoint(slide20, "Datu subjektuen eskubideak", 0.7, 2.65, 3.8);
slide20.addShape(pres.shapes.RECTANGLE, { x: 0.7, y: 3.2, w: 3.8, h: 0.5, fill: { color: C_DANGER } });
slide20.addText("Zigorra: fakturazioaren %4 arte", { x: 0.7, y: 3.25, w: 3.8, h: 0.4, fontSize: 12, color: C_WHITE, align: 'center', valign: 'middle' });

slide20.addShape(pres.shapes.RECTANGLE, { x: 5, y: 1.1, w: 4.5, h: 2.8, fill: { color: "FFF3E0" } });
slide20.addText("NIS2 (2024 Oct)", { x: 5, y: 1.2, w: 4.5, h: 0.5, fontSize: 20, bold: true, color: C_WARNING, align: 'center' });
addBulletPoint(slide20, "Enpresa handientzat derrigorrezkoa", 5.2, 1.85, 4);
addBulletPoint(slide20, "250+ langile / 50M€ fakturazio", 5.2, 2.25, 4);
addBulletPoint(slide20, "Hornitzaile gisa prestatuta", 5.2, 2.65, 4);
slide20.addShape(pres.shapes.RECTANGLE, { x: 5.2, y: 3.2, w: 4.1, h: 0.5, fill: { color: C_DANGER } });
slide20.addText("Zigorra: 10 milioi € arte", { x: 5.2, y: 3.25, w: 4.1, h: 0.4, fontSize: 12, color: C_WHITE, align: 'center', valign: 'middle' });

slide20.addText("DPO izendatuta: Datuak Babesteko Ordezkaria", { x: 0.5, y: 4.2, w: 9, h: 0.4, fontSize: 14, bold: true, color: C_PRIMARY, align: 'center' });

// ==================== 21. IEC 62443 ====================
let slide21 = pres.addSlide({ masterName: 'CONTENT_SLIDE' });
slide21.addText("IEC 62443: Industria Segurtasunaren Estandarra", { x: 0.5, y: 0.3, w: 9, h: 0.6, fontSize: 24, bold: true, color: C_PRIMARY });

slide21.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 1.1, w: 2.1, h: 1.2, fill: { color: "E0E0E0" } });
slide21.addText("SL 0", { x: 0.5, y: 1.15, w: 2.1, h: 0.35, fontSize: 12, bold: true, align: 'center' });
slide21.addText("Ez dago eskakizunik", { x: 0.5, y: 1.5, w: 2.1, h: 0.3, fontSize: 10, align: 'center' });
slide21.addText("⚪", { x: 0.5, y: 1.85, w: 2.1, h: 0.35, fontSize: 14, align: 'center' });

slide21.addShape(pres.shapes.RECTANGLE, { x: 2.8, y: 1.1, w: 2.1, h: 1.2, fill: { color: "E8F5E9" } });
slide21.addText("SL 1", { x: 2.8, y: 1.15, w: 2.1, h: 0.35, fontSize: 12, bold: true, align: 'center' });
slide21.addText("Hutsegite akatsak", { x: 2.8, y: 1.5, w: 2.1, h: 0.3, fontSize: 10, align: 'center' });
slide21.addText("🟢", { x: 2.8, y: 1.85, w: 2.1, h: 0.35, fontSize: 14, align: 'center' });

slide21.addShape(pres.shapes.RECTANGLE, { x: 5.1, y: 1.1, w: 2.1, h: 1.2, fill: { color: C_SUCCESS } });
slide21.addText("SL 2", { x: 5.1, y: 1.15, w: 2.1, h: 0.35, fontSize: 12, bold: true, color: C_WHITE, align: 'center' });
slide21.addText("Baliabide baxua", { x: 5.1, y: 1.5, w: 2.1, h: 0.3, fontSize: 10, color: C_WHITE, align: 'center' });
slide21.addText("✓ GAUDE HEMEN", { x: 5.1, y: 1.85, w: 2.1, h: 0.35, fontSize: 11, bold: true, color: C_WHITE, align: 'center' });

slide21.addShape(pres.shapes.RECTANGLE, { x: 7.4, y: 1.1, w: 2.1, h: 1.2, fill: { color: "FFF3E0" } });
slide21.addText("SL 3", { x: 7.4, y: 1.15, w: 2.1, h: 0.35, fontSize: 12, bold: true, align: 'center' });
slide21.addText("Baliabide moderatua", { x: 7.4, y: 1.5, w: 2.1, h: 0.3, fontSize: 10, align: 'center' });
slide21.addText("🎯 HELBURUA", { x: 7.4, y: 1.85, w: 2.1, h: 0.35, fontSize: 11, bold: true, color: C_WARNING, align: 'center' });

slide21.addText("Segurtasun Mailak (SL - Security Level)", { x: 0.5, y: 2.45, w: 9, h: 0.3, fontSize: 10, color: "666666", align: 'center' });

addBulletPoint(slide21, "Gune eta Hodi eredua: sare segmentazio zorrotza", 0.5, 2.85, 9);
addBulletPoint(slide21, "Irakurketa-soila modua lehenetsita OT sistemetan", 0.5, 3.35, 9);
addBulletPoint(slide21, "Langileen segurtasun fisikoa lehenesten da", 0.5, 3.85, 9, { bold: true });

// ==================== 22. ATALA 6: ONDORIOAK ====================
let slide22 = pres.addSlide({ masterName: 'SECTION_SLIDE' });
slide22.addText("6. ONDORIOAK", { x: 0.7, y: 2, w: 9, h: 1, fontSize: 48, bold: true, color: C_WHITE });
slide22.addText("ETA GALDERAK", { x: 0.7, y: 3, w: 9, h: 0.8, fontSize: 36, color: C_ACCENT });
slide22.addText("(3 minutu)", { x: 0.7, y: 3.9, w: 9, h: 0.5, fontSize: 18, color: C_SECONDARY });

// ==================== 23. BALIOA ====================
let slide23 = pres.addSlide({ masterName: 'CONTENT_SLIDE' });
slide23.addText("Inbertsioaren Balioa: Zer Lortu Dugu?", { x: 0.5, y: 0.3, w: 9, h: 0.6, fontSize: 26, bold: true, color: C_PRIMARY });

slide23.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 1.1, w: 4.2, h: 2.2, fill: { color: "E8F5E9" } });
slide23.addText("✓", { x: 0.5, y: 1.2, w: 4.2, h: 0.6, fontSize: 36, bold: true, color: C_SUCCESS, align: 'center' });
slide23.addText("Ekoizpenaren", { x: 0.5, y: 1.9, w: 4.2, h: 0.35, fontSize: 14, bold: true, align: 'center' });
slide23.addText("Jarraitutasuna", { x: 0.5, y: 2.25, w: 4.2, h: 0.35, fontSize: 14, bold: true, align: 'center' });
slide23.addText("120 langileen segurtasuna", { x: 0.5, y: 2.7, w: 4.2, h: 0.3, fontSize: 11, align: 'center' });

slide23.addShape(pres.shapes.RECTANGLE, { x: 5, y: 1.1, w: 4.5, h: 2.2, fill: { color: "E3F2FD" } });
slide23.addText("💰", { x: 5, y: 1.2, w: 4.5, h: 0.6, fontSize: 36, align: 'center' });
slide23.addText("Arrisku Juridikoa", { x: 5, y: 1.9, w: 4.5, h: 0.35, fontSize: 14, bold: true, align: 'center' });
slide23.addText("Murriztua", { x: 5, y: 2.25, w: 4.5, h: 0.35, fontSize: 14, bold: true, align: 'center' });
slide23.addText("Milioika euroko aurrezkia", { x: 5, y: 2.7, w: 4.5, h: 0.3, fontSize: 11, align: 'center' });

slide23.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 3.5, w: 4.2, h: 1.5, fill: { color: "FFF3E0" } });
slide23.addText("🏆", { x: 0.5, y: 3.55, w: 4.2, h: 0.5, fontSize: 28, align: 'center' });
slide23.addText("Ospea Babestuta", { x: 0.5, y: 4.1, w: 4.2, h: 0.35, fontSize: 14, bold: true, align: 'center' });
slide23.addText("Bezeroen konfiantza", { x: 0.5, y: 4.5, w: 4.2, h: 0.3, fontSize: 11, align: 'center' });

slide23.addShape(pres.shapes.RECTANGLE, { x: 5, y: 3.5, w: 4.5, h: 1.5, fill: { color: "F3E5F5" } });
slide23.addText("🚀", { x: 5, y: 3.55, w: 4.5, h: 0.5, fontSize: 28, align: 'center' });
slide23.addText("Industria 4.0-rako Oinarria", { x: 5, y: 4.1, w: 4.5, h: 0.35, fontSize: 14, bold: true, align: 'center' });
slide23.addText("Etorkizunik gabeko modernizazioa ez", { x: 5, y: 4.5, w: 4.5, h: 0.3, fontSize: 11, align: 'center' });

// ==================== 24. HURRENGO PAUSOAK ====================
let slide24 = pres.addSlide({ masterName: 'CONTENT_SLIDE' });
slide24.addText("Hurrengo Pausoak eta Eskerrik Asko", { x: 0.5, y: 0.3, w: 9, h: 0.6, fontSize: 26, bold: true, color: C_PRIMARY });

slide24.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 1.1, w: 2.9, h: 2, fill: { color: "E3F2FD" } });
slide24.addText("2026 Q2", { x: 0.5, y: 1.2, w: 2.9, h: 0.5, fontSize: 18, bold: true, color: C_PRIMARY, align: 'center' });
slide24.addText("• DLP sistema osoa", { x: 0.6, y: 1.8, w: 2.7, h: 0.3, fontSize: 11 });
slide24.addText("• Datu maskaratzea", { x: 0.6, y: 2.15, w: 2.7, h: 0.3, fontSize: 11 });
slide24.addText("• Sailkapen osoa", { x: 0.6, y: 2.5, w: 2.7, h: 0.3, fontSize: 11 });

slide24.addShape(pres.shapes.RECTANGLE, { x: 3.55, y: 1.1, w: 2.9, h: 2, fill: { color: "FFF3E0" } });
slide24.addText("2026 Q3", { x: 3.55, y: 1.2, w: 2.9, h: 0.5, fontSize: 18, bold: true, color: C_WARNING, align: 'center' });
slide24.addText("• ISO 27001", { x: 3.65, y: 1.8, w: 2.7, h: 0.3, fontSize: 11 });
slide24.addText("  aurre-auditoria", { x: 3.65, y: 2.15, w: 2.7, h: 0.3, fontSize: 11 });
slide24.addText("• DLP hedapena", { x: 3.65, y: 2.5, w: 2.7, h: 0.3, fontSize: 11 });

slide24.addShape(pres.shapes.RECTANGLE, { x: 6.6, y: 1.1, w: 2.9, h: 2, fill: { color: "E8F5E9" } });
slide24.addText("2026 Q4", { x: 6.6, y: 1.2, w: 2.9, h: 0.5, fontSize: 18, bold: true, color: C_SUCCESS, align: 'center' });
slide24.addText("• Ziurtagiri ofiziala", { x: 6.7, y: 1.8, w: 2.7, h: 0.3, fontSize: 11 });
slide24.addText("• Geo-erredundantzia", { x: 6.7, y: 2.15, w: 2.7, h: 0.3, fontSize: 11 });
slide24.addText("• SL 3 lortzea", { x: 6.7, y: 2.5, w: 2.7, h: 0.3, fontSize: 11 });

slide24.addShape(pres.shapes.RECTANGLE, { x: 0.5, y: 3.4, w: 9, h: 0.8, fill: { color: C_PRIMARY } });
slide24.addText("⚡ Segurtasuna ez da proiektu bat amaitzen dena. Prozesu bat da, etengabeko hobekuntza.", 
  { x: 0.5, y: 3.5, w: 9, h: 0.6, fontSize: 14, color: C_WHITE, align: 'center', valign: 'middle' });

slide24.addText("Eskerrik asko zuhurtasunagatik!", { x: 0.5, y: 4.5, w: 9, h: 0.5, fontSize: 20, bold: true, color: C_ACCENT, align: 'center' });

// ==================== 25. GALDERAK ====================
let slide25 = pres.addSlide({ masterName: 'TITLE_SLIDE' });
slide25.addText("GALDERAK", { x: 0.5, y: 2.2, w: 9, h: 1.2, fontSize: 60, bold: true, color: C_WHITE, align: 'center' });
slide25.addText("ETA EZTABAIDAK", { x: 0.5, y: 3.4, w: 9, h: 0.8, fontSize: 36, color: C_ACCENT, align: 'center' });
slide25.addText("Zabala Gailetak - 2026", { x: 0.5, y: 4.5, w: 9, h: 0.5, fontSize: 18, color: C_SECONDARY, align: 'center' });

// ==================== GORDE ====================
pres.writeFile({ fileName: "Aurkezpena_Zabala_Gailetak.pptx" });
console.log("Aurkezpena sortuta: Aurkezpena_Zabala_Gailetak.pptx");
