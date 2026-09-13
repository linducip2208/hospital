// Capture a small, real mobile regression set for docs/marketing.
// Run with: php artisan serve --host=127.0.0.1 --port=8765 (separate terminal), then node scripts/screenshot-mobile.cjs
const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');

const BASE_URL = process.env.SS_BASE_URL || 'http://127.0.0.1:8765';
const OUT_DIR = path.resolve(__dirname, '..', 'public', 'marketing', 'screens-mobile');
const EMAIL = process.env.SS_EMAIL || 'admin@hospital.test';
const PASSWORD = process.env.SS_PASSWORD || 'password';
const PAGES = [
    { url: '/dashboard', file: 'dashboard.png' },
    { url: '/patients', file: 'patients.png' },
    { url: '/patients/create', file: 'patients-create.png' },
];

fs.mkdirSync(OUT_DIR, { recursive: true });

(async () => {
    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext({
        viewport: { width: 414, height: 896 },
        deviceScaleFactor: 2,
        isMobile: true,
    });
    const page = await context.newPage();
    await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });
    await page.locator('input[type="email"]').first().fill('');
    await page.type('input[type="email"]', EMAIL, { delay: 25 });
    await page.locator('input[type="password"]').first().fill('');
    await page.type('input[type="password"]', PASSWORD, { delay: 25 });
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');

    for (const target of PAGES) {
        await page.goto(`${BASE_URL}${target.url}`, { waitUntil: 'networkidle' });
        await page.waitForTimeout(500);
        await page.screenshot({ path: path.join(OUT_DIR, target.file), fullPage: false });
        console.log(`✓ ${target.file}`);
    }
    await browser.close();
})();
