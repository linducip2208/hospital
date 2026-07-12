// scripts/screenshot-sidebar.cjs
// Capture sidebar admin setelah login untuk verify struktur menu baru.
const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');

const BASE_URL = process.env.SS_BASE_URL || 'http://127.0.0.1:8765';
const OUT_DIR  = path.resolve(__dirname, '..', 'public', 'marketing', 'verify');
const CRED     = { email: 'admin@hospital.test', password: 'password' };

if (!fs.existsSync(OUT_DIR)) fs.mkdirSync(OUT_DIR, { recursive: true });

(async () => {
    const browser = await chromium.launch({ headless: true });
    const ctx = await browser.newContext({
        viewport: { width: 1440, height: 1800 },
        deviceScaleFactor: 1.2,
    });
    const page = await ctx.newPage();

    await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });
    await page.fill('input[name="email"]', CRED.email);
    await page.fill('input[name="password"]', CRED.password);
    await Promise.all([
        page.waitForLoadState('networkidle'),
        page.click('button[type="submit"]'),
    ]);

    await page.goto(`${BASE_URL}/dashboard`, { waitUntil: 'networkidle' });
    await page.waitForTimeout(500);

    // Full-page screenshot — supaya sidebar penuh ke bawah terlihat
    const out = path.join(OUT_DIR, 'sidebar-new-structure.png');
    await page.screenshot({ path: out, fullPage: true });
    console.log('  ✓ saved', out);

    await browser.close();
})();
