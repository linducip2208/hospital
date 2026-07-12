// scripts/crawl-errors.cjs
// Login as admin, visit all index routes, capture errors.
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const BASE_URL = process.env.SS_BASE_URL || 'http://127.0.0.1:8765';
const CRED     = { email: 'admin@hospital.test', password: 'password' };

const ROUTES = [
    'ambulance-calls','ambulances','anc-records','appointments','assets','attendances',
    'baby-immunizations','blood-donations','chart-of-accounts','clinical-pathways','cms',
    'code-blue-activations','cost-estimates','departments','diet-orders','discharge-summaries',
    'doctors','drug-destructions','drug-supply-orders','drugs','emergencies','employees',
    'equipment-maintenances','hospital-beds','icu-monitorings','infection-surveillances',
    'informed-consents','insurance-claims','journal-entries','lab-tests','leaves','maternities',
    'medical-certificates','medical-records','medication-administrations','nurse-assignments',
    'nursing-cares','odontograms','partographs','patient-feedbacks','patient-safety-incidents',
    'patient-screenings','patients','payments','polyclinics','postnatal-records','prescriptions',
    'purchase-orders','queues','radiologies','referrals','reports','rooms','salaries','settings',
    'shift-handovers','staff-schedules','surgeries','telemedicine-sessions','treatments',
    'users','vital-signs',
];

(async () => {
    const browser = await chromium.launch({ headless: true });
    const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
    const page = await ctx.newPage();

    console.log(`▶ Login as admin...`);
    await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });
    await page.fill('input[name="email"]', CRED.email);
    await page.fill('input[name="password"]', CRED.password);
    await Promise.all([
        page.waitForLoadState('networkidle'),
        page.click('button[type="submit"]'),
    ]);
    console.log('  ✓ Logged in\n');

    const errors = [];
    let ok = 0;

    for (const route of ROUTES) {
        const url = `${BASE_URL}/${route}`;
        try {
            const response = await page.goto(url, { waitUntil: 'networkidle', timeout: 20000 });
            const status = response.status();
            const title = await page.title().catch(() => '');

            // Detect Laravel error page (Whoops/Ignition)
            const body = await page.content();
            const errorMatch = body.match(/<title>([^<]*?(?:ErrorException|Undefined|Class|SQLSTATE|RuntimeException|Exception|Error)[^<]*?)<\/title>/i)
                || body.match(/<span class="exception_title">[\s\S]*?<span>([\s\S]*?)<\/span>/);
            const detailMatch = body.match(/<span class="exception_message">[\s\S]*?<\/span>\s*<span class="exception_message_wrapper">[\s\S]*?<span>([\s\S]*?)<\/span>/)
                || body.match(/<h1 class="exception_message">([\s\S]*?)<\/h1>/);

            if (status >= 500 || errorMatch) {
                const errorTitle = errorMatch ? errorMatch[1].trim().substring(0, 120) : `HTTP ${status}`;
                const errorDetail = detailMatch ? detailMatch[1].trim().substring(0, 200) : '';
                errors.push({ route, status, title: errorTitle, detail: errorDetail });
                console.log(`  ✗ /${route.padEnd(28)} ${status}  ${errorTitle}`);
            } else {
                ok++;
                process.stdout.write(`  ✓ /${route.padEnd(28)} ${status}\n`);
            }
        } catch (e) {
            errors.push({ route, status: 0, title: 'TIMEOUT/CRASH', detail: e.message.split('\n')[0] });
            console.log(`  ✗ /${route.padEnd(28)} CRASH: ${e.message.split('\n')[0]}`);
        }
    }

    await browser.close();

    console.log(`\n═══ Result: ${ok}/${ROUTES.length} OK, ${errors.length} errors ═══\n`);

    if (errors.length) {
        console.log('ERRORS:');
        errors.forEach(e => console.log(`  - /${e.route} [${e.status}]: ${e.title} ${e.detail ? '— ' + e.detail : ''}`));

        // Save JSON for next-step processing
        const outFile = path.join(__dirname, '..', 'public', 'marketing', 'verify', 'crawl-errors.json');
        fs.mkdirSync(path.dirname(outFile), { recursive: true });
        fs.writeFileSync(outFile, JSON.stringify(errors, null, 2));
        console.log(`\nJSON saved: ${outFile}`);
    }

    process.exit(errors.length > 0 ? 1 : 0);
})();
