// scripts/screenshot.cjs
// Capture screenshot fitur SIMRS untuk marketing landing.
// Jalankan: php artisan serve --host=127.0.0.1 --port=8765 (di terminal lain) lalu `node scripts/screenshot.cjs`
const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');

const BASE_URL = process.env.SS_BASE_URL || 'http://127.0.0.1:8765';
const OUT_DIR  = path.resolve(__dirname, '..', 'public', 'marketing', 'screens');
const CRED     = { email: 'admin@hospital.test', password: 'password' };

const PAGES = [
    { url: '/dashboard',         file: 'dashboard.png',       label: 'Dashboard Executive' },
    { url: '/appointments',      file: 'appointments.png',    label: 'Janji Temu Pasien' },
    { url: '/medical-records',   file: 'medical-records.png', label: 'Rekam Medis Digital' },
    { url: '/drugs',             file: 'drugs.png',           label: 'Farmasi & Obat' },
    { url: '/emergencies',       file: 'emergencies.png',     label: 'IGD & Triase' },
    { url: '/lab-tests',         file: 'lab-tests.png',       label: 'Laboratorium' },
    { url: '/payments',          file: 'payments.png',        label: 'Pembayaran & Invoice' },
    { url: '/salaries',          file: 'salaries.png',        label: 'HR & Payroll' },
    { url: '/rooms',             file: 'rooms.png',           label: 'Rawat Inap & Kamar' },
    { url: '/journal-entries',   file: 'journal-entries.png', label: 'Akuntansi Jurnal' },
];

if (!fs.existsSync(OUT_DIR)) fs.mkdirSync(OUT_DIR, { recursive: true });

(async () => {
    console.log(`▶ Connecting to ${BASE_URL}`);
    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext({
        viewport: { width: 1440, height: 900 },
        deviceScaleFactor: 1.5,
    });
    const page = await context.newPage();

    // ── Login programmatic ──
    console.log('▶ Login as admin...');
    await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });
    await page.fill('input[name="email"]', CRED.email);
    await page.fill('input[name="password"]', CRED.password);
    await Promise.all([
        page.waitForLoadState('networkidle'),
        page.click('button[type="submit"]'),
    ]);
    console.log('  ✓ Logged in');

    // ── Loop capture ──
    let ok = 0, fail = 0;
    for (const p of PAGES) {
        const fullUrl = `${BASE_URL}${p.url}`;
        const outPath = path.join(OUT_DIR, p.file);
        try {
            await page.goto(fullUrl, { waitUntil: 'networkidle', timeout: 30000 });
            await page.waitForTimeout(800); // beri waktu animasi/chart render
            await page.screenshot({ path: outPath, fullPage: false });
            const size = (fs.statSync(outPath).size / 1024).toFixed(0);
            console.log(`  ✓ ${p.file.padEnd(28)} ${size} KB  — ${p.label}`);
            ok++;
        } catch (e) {
            console.log(`  ✗ ${p.file.padEnd(28)} FAILED: ${e.message.split('\n')[0]}`);
            fail++;
        }
    }

    await browser.close();
    console.log(`\n▶ Done — ${ok} ok, ${fail} fail.  Output: ${OUT_DIR}`);
    process.exit(fail > 0 ? 1 : 0);
})();
