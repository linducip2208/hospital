// scripts/screenshot-landing.cjs
// Capture landing page features section in desktop + mobile viewport untuk verifikasi responsive.
const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');

const BASE_URL = process.env.SS_BASE_URL || 'http://127.0.0.1:8765';
const OUT_DIR  = path.resolve(__dirname, '..', 'public', 'marketing', 'verify');

if (!fs.existsSync(OUT_DIR)) fs.mkdirSync(OUT_DIR, { recursive: true });

(async () => {
    const browser = await chromium.launch({ headless: true });

    // ── Desktop 1440x900 ──
    {
        const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 }, deviceScaleFactor: 1 });
        const page = await ctx.newPage();
        await page.goto(`${BASE_URL}/#features`, { waitUntil: 'networkidle' });
        await page.waitForTimeout(800);
        await page.screenshot({ path: path.join(OUT_DIR, 'landing-desktop.png'), fullPage: false });
        console.log('  ✓ landing-desktop.png');
        await ctx.close();
    }

    // ── Mobile 414x896 (iPhone 11 Pro Max) ──
    {
        const ctx = await browser.newContext({
            viewport: { width: 414, height: 896 }, deviceScaleFactor: 2, isMobile: true,
        });
        const page = await ctx.newPage();
        await page.goto(`${BASE_URL}/#features`, { waitUntil: 'networkidle' });
        await page.waitForTimeout(800);
        await page.screenshot({ path: path.join(OUT_DIR, 'landing-mobile.png'), fullPage: false });
        console.log('  ✓ landing-mobile.png');
        await ctx.close();
    }

    await browser.close();
})();
