const { Document, Packer, Paragraph, TextRun, Table, TableRow, TableCell, 
        Header, Footer, AlignmentType, PageOrientation, LevelFormat,
        HeadingLevel, BorderStyle, WidthType, ShadingType,
        VerticalAlign, PageNumber, PageBreak } = require('docx');
const fs = require('fs');

// Colores corporativos
const COLOR_PRIMARY = "1E3A5F";    // Azul corporativo oscuro
const COLOR_SECONDARY = "D4AF37";  // Dorado
const COLOR_ACCENT = "2E7D32";     // Verde
const COLOR_LIGHT = "F5F5F5";      // Gris claro

const border = { style: BorderStyle.SINGLE, size: 1, color: "CCCCCC" };
const borders = { top: border, bottom: border, left: border, right: border };

const doc = new Document({
  styles: {
    default: { 
      document: { 
        run: { font: "Arial", size: 22 }  // 11pt default
      } 
    },
    paragraphStyles: [
      { 
        id: "Heading1", 
        name: "Heading 1", 
        basedOn: "Normal", 
        next: "Normal", 
        quickFormat: true,
        run: { size: 36, bold: true, font: "Arial", color: COLOR_PRIMARY },
        paragraph: { spacing: { before: 400, after: 200 }, outlineLevel: 0 }
      },
      { 
        id: "Heading2", 
        name: "Heading 2", 
        basedOn: "Normal", 
        next: "Normal", 
        quickFormat: true,
        run: { size: 28, bold: true, font: "Arial", color: COLOR_PRIMARY },
        paragraph: { spacing: { before: 300, after: 150 }, outlineLevel: 1 }
      },
      { 
        id: "Heading3", 
        name: "Heading 3", 
        basedOn: "Normal", 
        next: "Normal", 
        quickFormat: true,
        run: { size: 24, bold: true, font: "Arial", color: COLOR_ACCENT },
        paragraph: { spacing: { before: 200, after: 100 }, outlineLevel: 2 }
      }
    ]
  },
  numbering: {
    config: [
      { 
        reference: "bullets",
        levels: [
          { 
            level: 0, 
            format: LevelFormat.BULLET, 
            text: "•", 
            alignment: AlignmentType.LEFT,
            style: { paragraph: { indent: { left: 720, hanging: 360 } } }
          }
        ]
      }
    ]
  },
  sections: [{
    properties: {
      page: {
        size: { width: 12240, height: 15840 },
        margin: { top: 1080, right: 1080, bottom: 1080, left: 1080 }
      }
    },
    headers: {
      default: new Header({
        children: [
          new Paragraph({
            alignment: AlignmentType.RIGHT,
            children: [
              new TextRun({ 
                text: "Zabala Gailetak - Zibersegurtasun Proiektua 2026", 
                font: "Arial", 
                size: 18,
                color: "666666"
              })
            ]
          })
        ]
      })
    },
    footers: {
      default: new Footer({
        children: [
          new Paragraph({
            alignment: AlignmentType.CENTER,
            children: [
              new TextRun({ text: "Orrialdea ", font: "Arial", size: 18 }),
              new TextRun({ children: [PageNumber.CURRENT], font: "Arial", size: 18 })
            ]
          })
        ]
      })
    },
    children: [
      // PORTADA
      new Paragraph({ spacing: { before: 2000 } }),
      new Paragraph({
        alignment: AlignmentType.CENTER,
        children: [
          new TextRun({ 
            text: "ZABALA GAILETAK", 
            font: "Arial", 
            size: 56, 
            bold: true,
            color: COLOR_PRIMARY
          })
        ]
      }),
      new Paragraph({
        alignment: AlignmentType.CENTER,
        children: [
          new TextRun({ 
            text: "Zibersegurtasun Proiektu Integrala", 
            font: "Arial", 
            size: 36,
            color: COLOR_SECONDARY
          })
        ]
      }),
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        alignment: AlignmentType.CENTER,
        children: [
          new TextRun({ 
            text: "Aurkezpena Zuzendaritzari eta Zuzendari Nagusiari", 
            font: "Arial", 
            size: 28,
            color: "333333"
          })
        ]
      }),
      new Paragraph({ spacing: { before: 600 } }),
      new Paragraph({
        alignment: AlignmentType.CENTER,
        children: [
          new TextRun({ 
            text: "2026ko Otsaila", 
            font: "Arial", 
            size: 24,
            color: "666666"
          })
        ]
      }),
      new Paragraph({ children: [new PageBreak()] }),

      // ÍNDICE
      new Paragraph({
        heading: HeadingLevel.HEADING_1,
        children: [new TextRun("AURKIBIDEA")]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "1. TESTUINGURUA ETA ERRONKA (4 min)", bold: true })]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "2. GURE ESTRATEGIA: ZERO TRUST ETA ARKITEKTURA SEGURUA (6 min)", bold: true })]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "3. BERRIKUNTZA SEGURUA ETA EGUNEROKO OPERAZIOAK (5 min)", bold: true })]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "4. ERASOEN AURKAKO PREBENTZIOA ETA ERANTZUNA (7 min)", bold: true })]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "5. BETETZE LEGALA ETA ETORKIZUNA (5 min)", bold: true })]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "6. ONDORIOAK ETA GALDERAK (3 min)", bold: true })]
      }),
      new Paragraph({ children: [new PageBreak()] }),

      // ==================== SECCIÓN 1 ====================
      new Paragraph({
        heading: HeadingLevel.HEADING_1,
        children: [new TextRun("1. TESTUINGURUA ETA ERRONKA")]
      }),
      new Paragraph({
        children: [new TextRun({ text: "(4 minutu)", italics: true, color: "666666" })]
      }),

      // Diapositiva 1
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        heading: HeadingLevel.HEADING_2,
        children: [new TextRun("1.1. Zabala Gailetak: Tradizioa eta Etorkizuna")]
      }),
      new Paragraph({
        shading: { fill: COLOR_LIGHT, type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "Puntu Nagusiak:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("120 langile, 6 zuzendaritzako kide, 5 IKT profesional")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Euskal Herriko gaileta tradizioa Europako merkatuetara zabaltzen")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Industria 4.0-ra igarotzea: digitalizazioa eta segurtasuna")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Erronka: ekoizpena babestu, baina modernizatu")]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      new Paragraph({
        shading: { fill: "E8F4FD", type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "HIZLARIAREN GIDOI A:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        children: [new TextRun("On egun, Zuzendari Nagusia, Zuzendaritza. Eskerrik asko gaur hemen egoteagatik. Gaur Zabala Gailetak-en etorkizuna definituko duen proiektu bat aurkeztuko dut: zibersegurtasunaren modernizazio integrala.")]
      }),
      new Paragraph({
        children: [new TextRun("Enpresa familiar gisa, 120 lagilek osatzen gaituzte. Baina gaur egungo merkatuan, tradizioa ez da nahikoa. Bezeroek online eskaerak egin nahi dituzte, langileek mugikorretik konektatu, eta fabrika konektatuta egon behar da. Hori da Industria 4.0. Baina hemen dago arazoa...")]
      }),

      // Diapositiva 2
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        heading: HeadingLevel.HEADING_2,
        children: [new TextRun("1.2. Arrisku Estrategikoa: IT eta OT Sareen Batzea")]
      }),
      new Paragraph({
        shading: { fill: COLOR_LIGHT, type: ShadingType.CLEAR },
        children: [
          new TextRun({ text: "Puntu Nagusiak:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("OT = Operational Technology: PLC-ak, SCADA, ekoizpen-makineria")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Arriskua: erasotzaile batek sartuz gero, 120 langileen segurtasuna arriskuan", bold: true, color: "CC0000" })]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Adibideak: Colonial Pipeline (USA), Norsk Hydro (Norvegia)")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Gure azterketak: PLC-ak Interneten espostuta aurkitu ziren!", bold: true, color: "CC0000" })]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      new Paragraph({
        shading: { fill: "E8F4FD", type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "HIZLARIAREN GIDOI B:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        children: [new TextRun("Hacking etikoaren proba bat egin genuenean, aurkikuntza kezkagarri bat egin genuen: gure PLC-ak —gailetak egiteko makinak kontrolatzen dituztenak— Internet zabalean ikusgai zeuden. Horrek esan nahi du edozein erasotzailek, munduko edozein lekutatik, gure labeak itzali ditzakeela, konbentoreak geldiarazi, edo tenperatura arriskutsuetara igo.")]
      }),
      new Paragraph({
        children: [new TextRun("Colonial Pipeline-k 4,4 milioi dolar ordaindu zituen ransomware baten ondorioz. Baina Zabala Gailetak-en, ez dira diruak bakarrik jokoan. Langileen segurtasuna fisikoa dago jokoan. Eta horregatik gaude hemen gaur: hori guztia aldatzeko.")]
      }),
      new Paragraph({ children: [new PageBreak()] }),

      // ==================== SECCIÓN 2 ====================
      new Paragraph({
        heading: HeadingLevel.HEADING_1,
        children: [new TextRun("2. GURE ESTRATEGIA: ZERO TRUST ETA ARKITEKTURA SEGURUA")]
      }),
      new Paragraph({
        children: [new TextRun({ text: "(6 minutu)", italics: true, color: "666666" })]
      }),

      // Diapositiva 3
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        heading: HeadingLevel.HEADING_2,
        children: [new TextRun("2.1. Zero Trust Printzipioa: Inork ez du fidatzen")]
      }),
      new Paragraph({
        shading: { fill: COLOR_LIGHT, type: ShadingType.CLEAR },
        children: [
          new TextRun({ text: "Puntu Nagusiak:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Europako Banku Zentralak eta BBVA-k erabiltzen duten estrategia bera", bold: true })]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Ez dago sare 'barne segurrik': konexio bakoitza egiaztatu behar da")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("5 VLAN segmentatu: Bulegoa, Zerbitzariak, DMZ, OT, Honeypot")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Fortinet FortiGate 200F suebakia: trafiko guztia kontrolatuta")]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      new Paragraph({
        shading: { fill: "E8F4FD", type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "HIZLARIAREN GIDOI A:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        children: [new TextRun("Nola ebatzi dugu arazoa? Zero Trust estrategiarekin. Europako Banku Zentralak eta enpresa handiek erabiltzen duten metodologia bera. Printzipioa sinplea da: inork ez du fidatzen, ezta sare barruan daudenek ere. Konexio bakoitza egiaztatu behar da, erabiltzaile bakoitza identifikatu, eta gailu bakoitza baieztatu.")]
      }),
      new Paragraph({
        children: [new TextRun("Bost sare segmentatu ezarri ditugu: langileen bulegoak, zerbitzariak, DMZ —publikoa dena—, OT industriala, eta honeypot bat erasotzaileak harrapatzeko. Suebaki batek kontrolatzen du trafiko guztia. Eta garrantzitsuena: IT eta OT artean ez dago zubi zuzenik. Fabrikara sartzeko, bost segurtasun geruza igaro behar dira.")]
      }),

      // Diapositiva 4
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        heading: HeadingLevel.HEADING_2,
        children: [new TextRun("2.2. Purdue Eredua: Fabrikaren Babesa Geruzaka")]
      }),
      new Paragraph({
        shading: { fill: COLOR_LIGHT, type: ShadingType.CLEAR },
        children: [
          new TextRun({ text: "Puntu Nagusiak:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Maila 4: Enpresa sarea (ERP, emaila, web orria)")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Maila 3.5: DMZ Industriala (historialariak, patch zerbitzaria)")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Maila 3: SCADA/HMI (OpenPLC, ScadaBR)")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Maila 1-0: Sentsoreak eta aktuadoreak (segurtasun fisikoa)", bold: true })]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      new Paragraph({
        shading: { fill: "E8F4FD", type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "HIZLARIAREN GIDOI B:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        children: [new TextRun("Purdue eredua industriako estandarra da. Lau geruza daude: goian enpresaren sarea —ERP-a, emaila—, gero DMZ industriala, SCADA sistema, eta azkenik, makina fisikoak. Garrantzitsuena: salto bakoitza suebaki batek babesten du.")]
      }),
      new Paragraph({
        children: [new TextRun("OpenPLC erabiltzen dugu PLC-ak simulatzeko, eta ScadaBR HMI interfaze gisa. Baina hori ez da nahikoa. Zubi bakoitza 'data diode' bat izan daiteke —datuak soilik norabide batean joan daitezke—, edo VPN seguru bat MFA-rekin. Hau da: fabrikara sartzeko, nazioarteko banku batean bezain segurtasun handia behar da.")]
      }),

      // Diapositiva 5
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        heading: HeadingLevel.HEADING_2,
        children: [new TextRun("2.3. Zerbitzarien Bastionatzea eta Automatizazioa")]
      }),
      new Paragraph({
        shading: { fill: COLOR_LIGHT, type: ShadingType.CLEAR },
        children: [
          new TextRun({ text: "Puntu Nagusiak:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Ansible: 50+ zerbitzari konfiguratu automatikoki")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Proxmox/Isard: birtualizazioa segurtasun isolamenduarekin")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Docker kontenedoreak: aplikazioak isolatuta")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Onura: errore konfigurazioak %90 murriztu dira", bold: true, color: COLOR_ACCENT })]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      new Paragraph({
        shading: { fill: "E8F4FD", type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "HIZLARIAREN GIDOI C:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        children: [new TextRun("Arkitektura hau kudeatzeko, automatizazioa ezinbestekoa da. Ansible erabiltzen dugu: 50 zerbitzaritik gora daude, eta guztiak konfiguratu ditugu kodearen bidez. Horrek esan nahi du konfigurazio bera aplikatzen dela denetan, eta ez dago 'ahaztutako' zerbitzaririk.")]
      }),
      new Paragraph({
        children: [new TextRun("Proxmox eta Isard-ekin birtualizatzen dugu. Zerbitzari fisiko bakoitza hain birtual bihurtzen du, isolatuta. Docker-ekin, aplikazioak kontenedoretan exekutatzen dira: bat erasotzen badute, besteak seguru daude. Eta garrantzitsuena: errore konfigurazioak %90 murriztu ditugu. Segurtasuna ez da soilik teknologia bat; prozesu bat da.")]
      }),
      new Paragraph({ children: [new PageBreak()] }),

      // ==================== SECCIÓN 3 ====================
      new Paragraph({
        heading: HeadingLevel.HEADING_1,
        children: [new TextRun("3. BERRIKUNTZA SEGURUA ETA EGUNEROKO OPERAZIOAK")]
      }),
      new Paragraph({
        children: [new TextRun({ text: "(5 minutu)", italics: true, color: "666666" })]
      }),

      // Diapositiva 6
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        heading: HeadingLevel.HEADING_2,
        children: [new TextRun("3.1. Langileen Atari Berria: Segurtasuna eta Erraztasuna")]
      }),
      new Paragraph({
        shading: { fill: COLOR_LIGHT, type: ShadingType.CLEAR },
        children: [
          new TextRun({ text: "Puntu Nagusiak:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("HR Portal: 120 langileen kudeaketa birtuala")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("JWT + MFA: bi faktoreko autentikazioa")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("5 rol desberdin: ADMIN, RRHH MGR, JEFE SECCIÓN, EMPLEADO, AUDITOR")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Sarbide soilik behar den informaziora: 'pribilegio gutxieneko' printzipioa", bold: true })]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      new Paragraph({
        shading: { fill: "E8F4FD", type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "HIZLARIAREN GIDOI A:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        children: [new TextRun("Orain, tresna berriak. Langileen atari bat eraiki dugu, 120 lagunek erabil dezaten. Bertan, nóminak ikusi, oporrak eskatu, dokumentazioa kudeatu... Baina segurua izan behar da. JWT tokenak erabiltzen ditugu, eta MFA —bi faktoreko autentikazioa— derrigorrezkoa da.")]
      }),
      new Paragraph({
        children: [new TextRun("Bost rol desberdin daude: administratzaileak, RRHH-ko arduradunak, saileko buruak, langileak, eta auditorrak. Bakoitzak soilik bere lanerako behar duena ikusten du. Jefe batek ez du ikusten beste sail baten soldatak. Langile batek ez du auditorren txostenik ikusten. 'Pribilegio gutxienekoa' deitzen zaio horri.")]
      }),

      // Diapositiva 7
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        heading: HeadingLevel.HEADING_2,
        children: [new TextRun("3.2. Mugikor Aplikazioa: Android Segurua")]
      }),
      new Paragraph({
        shading: { fill: COLOR_LIGHT, type: ShadingType.CLEAR },
        children: [
          new TextRun({ text: "Puntu Nagusiak:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Kotlin 2.0 + Jetpack Compose: teknologia aurreratuena")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Certificate Pinning: ez daiteke 'falsifikatu' zerbitzaria")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Biometria: hatz-marka edo aurpegi-ezagutza")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Datuak enkriptatuta gailuan: telefonoa galdu arren, informazioa segurua", bold: true, color: COLOR_ACCENT })]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      new Paragraph({
        shading: { fill: "E8F4FD", type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "HIZLARIAREN GIDOI B:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        children: [new TextRun("Eta mugikorrerako aplikazio bat ere bai. Kotlin 2.0-rekin egina, teknologia aurreratuena. Bi funtsezko segurtasun neurri: Certificate Pinning —zerbitzaria benetakoa dela baieztatzen du— eta biometria —hatz-marka edo aurpegia—.")]
      }),
      new Paragraph({
        children: [new TextRun("Baina garrantzitsuena: datuak enkriptatuta gordetzen dira telefonoan. Langile baten telefonoa galdu arren, ez da arriskurik. Eta aplikazioa 'reverse engineering' egitea oso zaila da. Hau da: modernitatea segurtasunarekin batera.")]
      }),

      // Diapositiva 8
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        heading: HeadingLevel.HEADING_2,
        children: [new TextRun("3.3. Garapen Azkarra, Segurtasun Geldogabea")]
      }),
      new Paragraph({
        shading: { fill: COLOR_LIGHT, type: ShadingType.CLEAR },
        children: [
          new TextRun({ text: "Puntu Nagusiak:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Dev/Staging/Produkzio: hiru ingurune isolatu")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("CI/CD Pipeline: SAST/DAST eskaneatze automatikoak")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Kode-berrikuspena: aldaketa guztiak bi begi pare baino gehiagok ikusten dituzte", bold: true })]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Emaitza: garapen azkarra, baina kalitate eta segurtasun gabe ez", color: COLOR_ACCENT })]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      new Paragraph({
        shading: { fill: "E8F4FD", type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "HIZLARIAREN GIDOI C:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        children: [new TextRun("Nola konpontzen dugu garapen azkarra eta segurtasunaren arteko tentsioa? Hiruko ingurune-sistema batekin: garapena, proba-ingurunea, eta produkzioa. Guztiz isolatuak. Produkziora igotzeko, atebat igaro behar da: SAST —kodea automatikoki analizatzen duena—, DAST —aplikazioa eraso simulazioak egiten dizkiona—, eta giza berrikuspena.")]
      }),
      new Paragraph({
        children: [new TextRun("Emaitza: garraiatze azkarra, baina segurtasuna lehenetsita. Ez dago 'ahaztutako' adabakirik. Ez dago 'ez dakit nork aldatu zuen' momenturik. Guztia agerian, trazabilidade osoarekin. Hauxe da Industria 4.0.")]
      }),
      new Paragraph({ children: [new PageBreak()] }),

      // ==================== SECCIÓN 4 ====================
      new Paragraph({
        heading: HeadingLevel.HEADING_1,
        children: [new TextRun("4. ERASOEN AURKAKO PREBENTZIOA ETA ERANTZUNA")]
      }),
      new Paragraph({
        children: [new TextRun({ text: "(7 minutu)", italics: true, color: "666666" })]
      }),

      // Diapositiva 9
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        heading: HeadingLevel.HEADING_2,
        children: [new TextRun("4.1. Hacking Etikoaren Emaitzak: Gure Defentsak Frogan")]
      }),
      new Paragraph({
        shading: { fill: COLOR_LIGHT, type: ShadingType.CLEAR },
        children: [
          new TextRun({ text: "Puntu Nagusiak:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "9 ahultasun identifikatu guztira: 0 kritiko, 2 altu, 4 ertain, 3 baxu", bold: true, color: COLOR_ACCENT })]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("SQL Injection konpondua: prepared statements")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("OT sarearen isolamendua: PLC-ak ez daude Internetean")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("WiFi: WPA3 + sarbide baimenduen zerrenda")]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      new Paragraph({
        shading: { fill: "E8F4FD", type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "HIZLARIAREN GIDOI A:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        children: [new TextRun("Orain, gure defentsak frogatzeko unea. Hacking etikoaren proba oso bat egin dugu, PTES metodologiaren arabera. Emaitzak hauek dira: bederatzi ahultasun identifikatu dira. Zero kritiko. Bi altu —jada konponduak—. Lau ertain eta hiru baxu. Hau da, gure sistema ez da hutsa, baina sendoa da.")]
      }),
      new Paragraph({
        children: [new TextRun("Ahultasun nagusia SQL Injection bat zen. Web aplikazioak ez zituen sarrerak behar bezala balidatzen. Konpondu dugu prepared statements-ekin. Bigarrena, OT sarearen esposizioa. PLC-ak Internetetik ikusgai zeuden. Orain, VPN bidez soilik sartzen da, eta sare segmentazio zorrotza dago.")]
      }),

      // Diapositiva 10
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        heading: HeadingLevel.HEADING_2,
        children: [new TextRun("4.2. Monitorizazioa Denbora Errealean: SIEM eta Wazuh")]
      }),
      new Paragraph({
        shading: { fill: COLOR_LIGHT, type: ShadingType.CLEAR },
        children: [
          new TextRun({ text: "Puntu Nagusiak:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("ELK Stack + Wazuh: log guztiak biltzen dira zentralizatuta")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "15+ alerta arau konfiguratuta: jokabide susmagarriak detektatzeko", bold: true })]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("MITRE ATT&CK esparrua: erasoak nola antolatzen diren ulertzeko")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Kibana dashboard: ikuspegi orokorra klik bakarrera", color: COLOR_ACCENT })]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      new Paragraph({
        shading: { fill: "E8F4FD", type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "HIZLARIAREN GIDOI B:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        children: [new TextRun("Baina erasoak detektatzea bezain garrantzitsua da haien aurrean ikustea. Horregatik daukagu SIEM sistema: Wazuh eta ELK Stack. Sistema guztiek beren log-ak bidaltzen dituzte zentralera. Saihesketak, autentikazio okerrak, prozesu susmagarriak... Guztia biltzen da.")]
      }),
      new Paragraph({
        children: [new TextRun("Hamabost alerta arau baino gehiago ditugu. Adibidez: erabiltzaile batek gauerdian saioa hastea, hiru autentikazio huts egitea jarraian, edo USB bat konektatzea OT ekipo batean. Alerta bat sortzen denean, korreoa bidaltzen zaie segurtasun taldeari. Eta Kibana panelak ikuspegi orokorra ematen du: nor dago konektatuta, zer gertatzen ari den, eta non.")]
      }),

      // Diapositiva 11
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        heading: HeadingLevel.HEADING_2,
        children: [new TextRun("4.3. Honeypot-ak: Erasotzaileak Harrapatzeko Tranpak")]
      }),
      new Paragraph({
        shading: { fill: COLOR_LIGHT, type: ShadingType.CLEAR },
        children: [
          new TextRun({ text: "Puntu Nagusiak:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("T-Pot/Cowrie: SSH eta Telnet zerbitzari faltsuak")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Conpot: PLC faltsuak OT sarean")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Helburua: erasotzaileak atzerapenarako erakartzea, benetako sistemak babesteko", bold: true })]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Mehatxu inteligentzia: nork, nola, eta nondik erasten saiatzen den ikasteko", color: COLOR_ACCENT })]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      new Paragraph({
        shading: { fill: "E8F4FD", type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "HIZLARIAREN GIDOI C:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        children: [new TextRun("Honeypot-ak ere erabiltzen ditugu. Zer dira? Zerbitzari faltsuak, benetakoak diren bezala itxuratuak. SSH zerbitzari bat, PLC bat, datu-base bat... Baina tranpak. Erasotzaile batek sartzen saiatzen denean, guk ikusten dugu. Ez dago benetako daturik, ez dago arriskurik.")]
      }),
      new Paragraph({
        children: [new TextRun("Bi helburu: bat, erasotzaileak atzerapenarako erakartzea, benetako sistemak babesteko. Bi, mehatxu inteligentzia lortzea. Jakin nahi dugu nork erasten saiatzen den, nola, nondik, eta zer teknikak erabiltzen dituen. Horrela, gure defentsak hobetu ditzakegu.")]
      }),

      // Diapositiva 12
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        heading: HeadingLevel.HEADING_2,
        children: [new TextRun("4.4. Gertaera-Erantzun Plana: Arazorako Prest")]
      }),
      new Paragraph({
        shading: { fill: COLOR_LIGHT, type: ShadingType.CLEAR },
        children: [
          new TextRun({ text: "Puntu Nagusiak:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Erantzun-denborak: Kritikoa 15 min, altua ordubete, ertaina 4 ordu", bold: true })]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("IR Taldea: 5 pertsona, rol argiak, 24/7 eskuragarritasuna")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Auzitegi-analisirako prest: ebidentziak biltzeko prozedurak")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Hiruhileko mahai-gaineko ariketak: prozedurak probatzeko", color: COLOR_ACCENT })]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      new Paragraph({
        shading: { fill: "E8F4FD", type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "HIZLARIAREN GIDOI D:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        children: [new TextRun("Eta gertaera bat jasaten badugu? Horregatik daukagu Gertaera-Erantzun Plana. Denbora-objektibo argiak: gertaera kritiko bati 15 minututan erantzun. Altuari, ordubete. Eta hori esan nahi du talde bat prest egon behar dela 24 orduz eguneko, 7 egun asteko.")]
      }),
      new Paragraph({
        children: [new TextRun("Bost pertsonako IR taldea dugu, rol argiekin: batzordeburua, teknikaria, komunikazio arduraduna, eta beste. Prozedura guztiak dokumentatuta daude. Eta hiruhilekoan behin, mahai-gaineko ariketa bat egiten dugu: 'zer gertatuko litzateke ransomware bat jasoko bagenu?'. Probatu gabe, plan bat soilik paper bat da.")]
      }),
      new Paragraph({ children: [new PageBreak()] }),

      // ==================== SECCIÓN 5 ====================
      new Paragraph({
        heading: HeadingLevel.HEADING_1,
        children: [new TextRun("5. BETETZE LEGALA ETA ETORKIZUNA")]
      }),
      new Paragraph({
        children: [new TextRun({ text: "(5 minutu)", italics: true, color: "666666" })]
      }),

      // Diapositiva 13
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        heading: HeadingLevel.HEADING_2,
        children: [new TextRun("5.1. ISO 27001:93% Betetzea, Zero Auditaldi")]
      }),
      new Paragraph({
        shading: { fill: COLOR_LIGHT, type: ShadingType.CLEAR },
        children: [
          new TextRun({ text: "Puntu Nagusiak:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "ISO 27001:2022-ren 93tik 87 kontrol inplementatuak (%93)", bold: true, color: COLOR_ACCENT })]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("A.5-A.7 artean: %100 inplementatuta")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("A.8 Teknologikoak: %94 inplementatuta")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "2026ko 4. hiruhilekorako: ziurtagiri auditoria planifikatuta", bold: true })]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      new Paragraph({
        shading: { fill: "E8F4FD", type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "HIZLARIAREN GIDOI A:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        children: [new TextRun("Orain, betekuntza. Zergatik da garrantzitsua? Ez soilik arrisku juridikoak saihesteko. Baizik eta bezeroek, hornitzaileek, eta inbertitzaileek eskatzen dutelako. ISO 27001 da zibersegurtasunaren 'etiketa' munduan.")]
      }),
      new Paragraph({
        children: [new TextRun("Gaur egun, 93tik 87 kontrol inplementatu ditugu, %93. A.5, A.6 eta A.7 osoak dira —organizazioa, pertsonak, eta segurtasun fisikoa—. A.8 teknologikoan, %94. Bost kontrol soilik falta dira, eta horiek ez dira kritikoak. 2026ko laugarren hiruhilekoan, kanpo auditoria bat egin nahi dugu, ziurtagiria lortzeko.")]
      }),

      // Diapositiva 14
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        heading: HeadingLevel.HEADING_2,
        children: [new TextRun("5.2. GDPR eta NIS2: Zigorrak Saihesten")]
      }),
      new Paragraph({
        shading: { fill: COLOR_LIGHT, type: ShadingType.CLEAR },
        children: [
          new TextRun({ text: "Puntu Nagusiak:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "GDPR: 72 orduko ohartarazpena prestatuta", bold: true })]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("NIS2: 2024ko urriaz geroztik indarrean, enpresa handientzat derrigorrezkoa")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Zigorra: GDPR-rekin, fakturazioaren %4 arte; NIS2-rekin, 10 milioi euro", bold: true, color: "CC0000" })]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("DPO izendatuta: Datuak Babesteko Ordezkaria")]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      new Paragraph({
        shading: { fill: "E8F4FD", type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "HIZLARIAREN GIDOI B:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        children: [new TextRun("Baina araudiak ere bete behar dira. GDPR-rekin, datu-haustura bat gertatzen bada, 72 ordu dituzu AEPDri jakinarazteko. Gure planak prest daude. NIS2, aldiz, 2024ko urrian indarrean sartu zen. Enpresa 'handientzat' derrigorrezkoa da —kritikoak ez diren sektoreetan, 250 langile baino gehiago eta 50 milioi fakturazio—.")]
      }),
      new Paragraph({
        children: [new TextRun("Gure kasuan, 120 langile ditugu, beraz ez gara zuzenean NIS2-ren mende. Baina hornitzaile gisa, enpresa handiek eska diezazkiguketen betekuntza maila bera. Eta zigorrak... GDPR-rekin, fakturazioaren %4 arte. NIS2-rekin, 10 milioi euro arte. Ez dira zenbakiak diru irabaziak galduko balira bezala kalkulatzeko.")]
      }),

      // Diapositiva 15
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        heading: HeadingLevel.HEADING_2,
        children: [new TextRun("5.3. IEC 62443: Industria Segurtasunaren Estandarra")]
      }),
      new Paragraph({
        shading: { fill: COLOR_LIGHT, type: ShadingType.CLEAR },
        children: [
          new TextRun({ text: "Puntu Nagusiak:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("SL 2 (Segurtasun Maila 2) lortuta, SL 3-rako bidean")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Gune eta Hodi eredua: sare segmentazio zorrotza")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "OT sistemak: irakurketa-soila modua lehenetsita", bold: true })]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Segurtasun fisikoa lehenetsita: langileen babesa", color: COLOR_ACCENT })]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      new Paragraph({
        shading: { fill: "E8F4FD", type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "HIZLARIAREN GIDOI C:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        children: [new TextRun("Eta industria-segurtasunaz, IEC 62443 da erreferentzia. Segurtasun Mailak daude SL 0-tik SL 4-ra. Gaur egun SL 2-n gaude, baina SL 3-rako bidean gara. Zein da aldea? SL 2-k 'nahigabeko akatsak' prebenitzen ditu. SL 3-k 'nahita egindako erasoak', baliabide ertainekin.")]
      }),
      new Paragraph({
        children: [new TextRun("Gune eta Hodi eredua erabiltzen dugu. Guneak dira segurtasun maila berdineko ekipoen taldeak. Hodiak dira beraien arteko komunikazioak kontrolatzeko mekanismoak. Eta gure OT sistemetan, irakurketa-soila modua da lehenetsia. Idazteko baimena behar izanez gero, prozesu formal bat dago. Hauxe da segurtasuna, eta hauxe da langileen babesa.")]
      }),
      new Paragraph({ children: [new PageBreak()] }),

      // ==================== SECCIÓN 6 ====================
      new Paragraph({
        heading: HeadingLevel.HEADING_1,
        children: [new TextRun("6. ONDORIOAK ETA GALDERAK")]
      }),
      new Paragraph({
        children: [new TextRun({ text: "(3 minutu)", italics: true, color: "666666" })]
      }),

      // Diapositiva 16
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        heading: HeadingLevel.HEADING_2,
        children: [new TextRun("6.1. Inbertsioaren Balioa: Zer Lortu Dugu?")]
      }),
      new Paragraph({
        shading: { fill: COLOR_LIGHT, type: ShadingType.CLEAR },
        children: [
          new TextRun({ text: "Puntu Nagusiak:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Ekoizpenaren jarraitutasuna bermatuta: 120 langileen segurtasuna", bold: true, color: COLOR_ACCENT })]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Zigor arriskua murriztua: milioika euroko aurrezkia")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("Enpresaren ospea babestuta: bezeroen konfiantza")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Industria 4.0-rako oinarria: etorkizunik gabeko modernizazioa ez", bold: true })]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      new Paragraph({
        shading: { fill: "E8F4FD", type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "HIZLARIAREN GIDOI A:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        children: [new TextRun("Laburbilduz, zer lortu dugu? Lehenik eta behin, ekoizpenaren jarraitutasuna. Gaur egun, erasotzaile batek fabrika geldiaraztea oso zaila da. Langileen segurtasuna fisikoa babestuta dago. Bigarrenik, arrisku juridikoa murriztu dugu. Zigor bat jasotzea? Zailagoa. Eta izoztutako dirua milioika euro izan daiteke.")]
      }),
      new Paragraph({
        children: [new TextRun("Hirugarrenik, enpresaren ospea. Zabala Gailetak-en izena seguruagoa da orain. Bezeroek, hornitzaileek, eta inbertitzaileek ikusten dute inbertitu dugula. Eta laugarrenik, eta garrantzitsuena: etorkizuna. Industria 4.0-ra igarotzeko oinarria daukagu. Automatizazioa, IA, IoT... guztia segurtasunaren gainean eraiki daiteke.")]
      }),

      // Diapositiva 17
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        heading: HeadingLevel.HEADING_2,
        children: [new TextRun("6.2. Hurrengo Pausoak eta Eskerrik Asko")]
      }),
      new Paragraph({
        shading: { fill: COLOR_LIGHT, type: ShadingType.CLEAR },
        children: [
          new TextRun({ text: "Puntu Nagusiak:", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("2026 Q2: DLP sistema osoa eta datu maskaratzea")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("2026 Q3: ISO 27001 aurre-auditoria")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun("2026 Q4: Ziurtagiri ofiziala eta geo-erredundantzia")]
      }),
      new Paragraph({
        numbering: { reference: "bullets", level: 0 },
        children: [new TextRun({ text: "Etengabeko hobekuntza: segurtasuna ez da proiektu bat, prozesu bat baizik", bold: true, color: COLOR_ACCENT })]
      }),
      new Paragraph({ spacing: { before: 200 } }),
      new Paragraph({
        shading: { fill: "E8F4FD", type: ShadingType.CLEAR },
        margins: { top: 100, bottom: 100, left: 100, right: 100 },
        children: [
          new TextRun({ text: "HIZLARIAREN GIDOI B (Amaiera):", bold: true, color: COLOR_PRIMARY })
        ]
      }),
      new Paragraph({
        children: [new TextRun("Eta zer dator orain? 2026rako plan bat daukagu. Bigarren hiruhilekoan, DLP sistema osoa eta datu maskaratzea. Hirugarren hiruhilekoan, ISO 27001 aurre-auditoria. Eta laugarrenean, ziurtagiria eta geo-erredundantzia. Baina gogoan izan: segurtasuna ez da proiektu bat amaitzen dena. Prozesu bat da, etengabeko hobekuntza.")]
      }),
      new Paragraph({
        children: [new TextRun("Eskerrik asko zuhurtasunagatik. Orain, zure galderak entzun nahi nituzke. Zuen zalantzak, zuen kezkak, eta zuen ideiak. Guztiak ongietorriak dira. Eta gogoan izan: hau ez da soilik IKT departamentuaren proiektua. Zabala Gailetak guztion proiektua da. Eskerrik asko.")]
      }),

      // Página final
      new Paragraph({ spacing: { before: 800 } }),
      new Paragraph({
        alignment: AlignmentType.CENTER,
        children: [
          new TextRun({ 
            text: "GALDERAK ETA EZTABAIDAK", 
            font: "Arial", 
            size: 32, 
            bold: true,
            color: COLOR_PRIMARY
          })
        ]
      }),
      new Paragraph({ spacing: { before: 400 } }),
      new Paragraph({
        alignment: AlignmentType.CENTER,
        children: [
          new TextRun({ 
            text: "Eskerrik asko zuhurtasunagatik", 
            font: "Arial", 
            size: 24,
            color: "666666"
          })
        ]
      }),
      new Paragraph({
        alignment: AlignmentType.CENTER,
        children: [
          new TextRun({ 
            text: "Zabala Gailetak - 2026", 
            font: "Arial", 
            size: 22,
            color: "999999"
          })
        ]
      })
    ]
  }]
});

Packer.toBuffer(doc).then(buffer => {
  fs.writeFileSync("Aurkezpena_Zabala_Gailetak_Zibersegurtasuna.docx", buffer);
  console.log("Documentua sortuta: Aurkezpena_Zabala_Gailetak_Zibersegurtasuna.docx");
});
