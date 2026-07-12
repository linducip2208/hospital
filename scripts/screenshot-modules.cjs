// scripts/screenshot-modules.cjs
// Verifikasi section "Modul Lainnya" di 3 viewport.
const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');

const BASE_URL = process.env.SS_BASE_URL || 'http://127.0.0.1:8765';
const OUT_DIR  = path.resolve(__dirname, '..', 'public', 'marketing', 'verify');

const VIEWPORTS = [
    { name: 'mobile-375',   width: 375,  height: 812,  scale: 2, isMobile: true  },
    { name: 'tablet-768',   width: 768,  height: 1024, scale: 2, isMobile: true  },
    { name: 'desktop-1440', width: 1440, height: 900,  scale: 1, isMobile: false },
];

if (!fs.existsSync(OUT_DIR)) fs.mkdirSync(OUT_DIR, { recursive: true });

(async () => {
    const browser = await chromium.launch({ headless: true });
    for (const vp of VIEWPORTS) {
        const ctx = await browser.newContext({
            viewport: { width: vp.width, height: vp.height },
            deviceScaleFactor: vp.scale,
            isMobile: vp.isMobile,
        });
        const page = await ctx.newPage();
        await page.goto(`${BASE_URL}/#modul-lainnya`, { waitUntil: 'networkidle' });
        await page.waitForTimeout(800);
        const out = path.join(OUT_DIR, `${vp.name}__modul-lainnya.png`);
        await page.screenshot({ path: out, fullPage: false });
        console.log(`  ✓ ${vp.name}`);
        await ctx.close();
    }
    await browser.close();
})();
