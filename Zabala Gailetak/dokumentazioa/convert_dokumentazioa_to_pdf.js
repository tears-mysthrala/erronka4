const { marked } = require('marked');
const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

// Zabala Gailetak color palette
const COLORS = {
    primary: '#B91C1C',
    primaryLight: '#DC2626',
    primaryDark: '#7F1D1D',
    accent: '#EA580C',
    accentLight: '#F97316',
    darkBg: '#18181B',
    card: '#1C1C1F',
    elevated: '#27272A',
    text: '#FAFAFA',
    textSecondary: '#A1A1AA',
    textTertiary: '#71717A',
    success: '#059669',
    warning: '#D97706',
    info: '#0284C7'
};

// HTML Template with Zabala Gailetak branding
const createHTMLTemplate = (content, title) => `<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>${title} - Zabala Gailetak</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
            background: white;
        }
        
        /* Cover Page */
        .cover {
            page-break-after: always;
            background: ${COLORS.darkBg};
            color: ${COLORS.text};
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 60px;
            position: relative;
        }
        
        .cover::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 15px;
            background: ${COLORS.primary};
        }
        
        .cover::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 10px;
            background: ${COLORS.primary};
        }
        
        .cover-line {
            width: 200px;
            height: 3px;
            background: ${COLORS.accent};
            margin: 30px auto;
        }
        
        .cover h1 {
            font-size: 48pt;
            font-weight: 700;
            color: ${COLORS.text};
            margin-bottom: 10px;
            letter-spacing: -1px;
        }
        
        .cover .subtitle {
            font-size: 20pt;
            color: ${COLORS.accent};
            font-weight: 500;
            margin-bottom: 20px;
        }
        
        .cover .title-main {
            font-size: 28pt;
            font-weight: 600;
            color: ${COLORS.text};
            margin-top: 60px;
            max-width: 80%;
        }
        
        .cover .module-number {
            font-size: 16pt;
            color: ${COLORS.textSecondary};
            margin-top: 20px;
            padding: 10px 30px;
            border: 2px solid ${COLORS.primary};
            border-radius: 30px;
        }
        
        .cover .date {
            font-size: 12pt;
            color: ${COLORS.textSecondary};
            margin-top: 80px;
        }
        
        .cover .confidential {
            font-size: 10pt;
            color: ${COLORS.textTertiary};
            margin-top: 40px;
        }
        
        /* Content Styles */
        .content { padding: 40px 50px; }
        
        h1 {
            font-size: 24pt;
            font-weight: 700;
            color: ${COLORS.primary};
            margin: 30px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid ${COLORS.accent};
            page-break-after: avoid;
        }
        
        h2 {
            font-size: 18pt;
            font-weight: 600;
            color: ${COLORS.primaryDark};
            margin: 25px 0 12px 0;
            page-break-after: avoid;
        }
        
        h3 {
            font-size: 14pt;
            font-weight: 600;
            color: ${COLORS.accent};
            margin: 20px 0 10px 0;
            page-break-after: avoid;
        }
        
        h4 {
            font-size: 12pt;
            font-weight: 600;
            color: #444;
            margin: 15px 0 8px 0;
        }
        
        p { margin: 10px 0; text-align: justify; }
        
        ul, ol { margin: 10px 0 10px 25px; }
        li { margin: 5px 0; }
        li::marker { color: ${COLORS.primary}; }
        
        a { color: ${COLORS.primary}; text-decoration: none; }
        
        /* Code Blocks */
        pre {
            background: ${COLORS.card};
            border: 1px solid ${COLORS.elevated};
            border-radius: 6px;
            padding: 15px;
            margin: 15px 0;
            overflow-x: auto;
            page-break-inside: avoid;
        }
        
        code {
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            font-size: 9.5pt;
            color: ${COLORS.text};
        }
        
        p code, li code {
            background: #f4f4f5;
            padding: 2px 6px;
            border-radius: 3px;
            color: ${COLORS.primaryDark};
            font-size: 10pt;
        }
        
        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 10pt;
            page-break-inside: auto;
        }
        
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        
        th {
            background: ${COLORS.primary};
            color: white;
            font-weight: 600;
            padding: 10px 12px;
            text-align: left;
            border: 1px solid ${COLORS.primaryDark};
        }
        
        td {
            padding: 8px 12px;
            border: 1px solid #e4e4e7;
        }
        
        tr:nth-child(even) { background: #fafafa; }
        
        /* Blockquotes */
        blockquote {
            border-left: 4px solid ${COLORS.primary};
            background: #fafafa;
            padding: 12px 20px;
            margin: 15px 0;
            font-style: italic;
            color: #555;
        }
        
        /* Horizontal Rules */
        hr {
            border: none;
            height: 2px;
            background: linear-gradient(to right, ${COLORS.primary}, ${COLORS.accent});
            margin: 30px 0;
        }
        
        /* Images */
        img {
            max-width: 100%;
            height: auto;
            margin: 15px 0;
        }
        
        /* Page Breaks */
        .page-break { page-break-before: always; }
        
        /* Print optimizations */
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <!-- Cover Page -->
    <div class="cover">
        <h1>ZABALA GAILETAK</h1>
        <div class="subtitle">S.L. - Dokumentazio Akademikoa</div>
        <div class="cover-line"></div>
        <div class="title-main">${title}</div>
        <div class="date">${new Date().toLocaleDateString('eu-ES', { year: 'numeric', month: 'long', day: 'numeric' })}</div>
        <div class="confidential">Dokumentu hau akademikoa da / Este documento es académico</div>
    </div>
    
    <!-- Main Content -->
    <div class="content">
        ${content}
    </div>
</body>
</html>`;

async function convertMarkdownToPDF(inputFile, outputFile, title) {
    console.log(`📄 Convirtiendo: ${path.basename(inputFile)}`);
    
    try {
        const markdown = fs.readFileSync(inputFile, 'utf-8');
        const htmlContent = marked.parse(markdown);
        const html = createHTMLTemplate(htmlContent, title);
        
        const browser = await puppeteer.launch({
            headless: 'new',
            args: ['--no-sandbox', '--disable-setuid-sandbox']
        });
        
        const page = await browser.newPage();
        await page.setContent(html, { waitUntil: 'networkidle0' });
        
        await page.pdf({
            path: outputFile,
            format: 'A4',
            printBackground: true,
            margin: {
                top: '60px',
                right: '50px',
                bottom: '80px',
                left: '50px'
            }
        });
        
        await browser.close();
        
        console.log(`  ✅ ${outputFile}`);
        return true;
    } catch (error) {
        console.error(`  ❌ Error:`, error.message);
        return false;
    }
}

async function main() {
    const docsDir = __dirname;
    const outputDir = docsDir;
    
    const documents = [
        { file: 'MODULUA_01_SAREAK_ETA_SISTEMAK.md', title: 'Sareak eta Sistemak Gotortzea' },
        { file: 'MODULUA_02_EKOIZPEN_SEGURUAN_JARTZEA.md', title: 'Ekoizpen Seguruan Jartzea' },
        { file: 'MODULUA_03_HACKING_ETIKOA.md', title: 'Hacking Etikoa' },
        { file: 'MODULUA_04_ZIBERSEGURTASUN_GORABEHERAK.md', title: 'Zibersegurtasun Gorabeherak Kudeatzea' },
        { file: 'MODULUA_05_AUZITEGI_ANALISI_INFORMATIKOA.md', title: 'Auzitegi Analisi Informatikoa' },
        { file: 'MODULUA_06_ZIBERSEGURTASUNAREN_ARLOKO_ARAUDIA.md', title: 'Zibersegurtasunaren Arloko Araudia' },
    ];
    
    console.log('');
    console.log('╔═══════════════════════════════════════════════════════╗');
    console.log('║  Zabala Gailetak - Dokumentazioa PDF Konbertitzailea  ║');
    console.log('╚═══════════════════════════════════════════════════════╝');
    console.log('');
    
    let success = 0;
    let failed = 0;
    
    for (const doc of documents) {
        const inputPath = path.join(docsDir, doc.file);
        const outputName = doc.file.replace('.md', '.pdf');
        const outputPath = path.join(outputDir, outputName);
        
        if (fs.existsSync(inputPath)) {
            const result = await convertMarkdownToPDF(inputPath, outputPath, doc.title);
            if (result) success++; else failed++;
        } else {
            console.log(`⚠️  No encontrado: ${doc.file}`);
        }
    }
    
    console.log('');
    console.log('═══════════════════════════════════════════════════════════');
    console.log(`✅ Exitosos: ${success}`);
    console.log(`❌ Fallidos: ${failed}`);
    console.log('═══════════════════════════════════════════════════════════');
    console.log('');
    console.log(`📁 PDFs guardados en: ${outputDir}`);
    console.log('');
}

main().catch(console.error);
