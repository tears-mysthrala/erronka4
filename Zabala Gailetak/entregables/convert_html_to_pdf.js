const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

async function generatePDF(htmlFile, outputFile) {
    const browser = await chromium.launch();
    const page = await browser.newPage();
    
    const htmlContent = fs.readFileSync(htmlFile, 'utf-8');
    await page.setContent(htmlContent, { waitUntil: 'networkidle' });
    
    await page.pdf({
        path: outputFile,
        format: 'A4',
        printBackground: true,
        margin: { top: '20mm', right: '15mm', bottom: '20mm', left: '15mm' }
    });
    
    await browser.close();
    console.log(`✅ PDF generado: ${outputFile}`);
}

async function main() {
    const htmlFiles = [];
    
    function findHTML(dir) {
        const items = fs.readdirSync(dir);
        for (const item of items) {
            const fullPath = path.join(dir, item);
            const stat = fs.statSync(fullPath);
            if (stat.isDirectory()) {
                findHTML(fullPath);
            } else if (item.endsWith('.html')) {
                htmlFiles.push(fullPath);
            }
        }
    }
    
    findHTML('.');
    
    for (const htmlFile of htmlFiles) {
        const outputFile = htmlFile.replace('.html', '.pdf');
        await generatePDF(htmlFile, outputFile);
    }
    
    console.log('\n🎉 Conversión completada');
}

main().catch(console.error);
