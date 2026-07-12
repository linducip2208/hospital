// scripts/crawl-create.cjs
// Visit all /create endpoints to catch form errors.
const { chromium } = require('playwright');

const BASE_URL = 'http://127.0.0.1:8765';
const CRED     = { email: 'admin@hospital.test', password: 'password' };

const ROUTES = [
    'ambulance-calls','ambulances','anc-records','appointments','assets','attendances',
    'baby-immunizations','blood-donations','chart-of-accounts','clinical-pathways',
    'code-blue-activations','cost-estimates','departments','diet-orders','discharge-summaries',
    'doctors','drug-destructions','drug-supply-orders','drugs','emergencies','employees',
    'equipment-maintenances','hospital-beds','icu-monitorings','infection-surveillances',
    'informed-consents','insurance-claims','journal-entries','lab-tests','leaves','maternities',
    'medical-certificates','medical-records','medication-administrations','nurse-assignments',
    'nursing-cares','odontograms','partographs','patient-feedbacks','patient-safety-incidents',
    'patient-screenings','patients','payments','polyclinics','postnatal-records','prescriptions',
    'purchase-orders','queues','radiologies','referrals','rooms','salaries',
    'shift-handovers','staff-schedules','surgeries','telemedicine-sessions','treatments',
    'users','vital-signs',
];

(async () => {
    const browser = await chromium.launch({ headless: true });
    const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
    const page = await ctx.newPage();

    await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });
    await page.fill('input[name="email"]', CRED.email);
    await page.fill('input[name="password"]', CRED.password);
    await Promise.all([page.waitForLoadState('networkidle'), page.click('button[type="submit"]')]);

    const errors = [];
    let ok = 0;
    for (const route of ROUTES) {
        const url = `${BASE_URL}/${route}/create`;
        try {
            const r = await page.goto(url, { waitUntil: 'networkidle', timeout: 20000 });
            const status = r.status();
            if (status >= 500) {
                errors.push({ route, status });
                console.log(`  ✗ /${route}/create  ${status}`);
            } else {
                ok++;
                process.stdout.write(`  ✓ /${route}/create  ${status}\n`);
            }
        } catch (e) {
            errors.push({ route, status: 0, msg: e.message.split('\n')[0] });
            console.log(`  ✗ /${route}/create  CRASH: ${e.message.split('\n')[0]}`);
        }
    }

    await browser.close();
    console.log(`\n═══ ${ok}/${ROUTES.length} OK, ${errors.length} errors ═══`);
    if (errors.length) errors.forEach(e => console.log(`  - /${e.route}/create [${e.status}] ${e.msg || ''}`));
    process.exit(errors.length > 0 ? 1 : 0);
})();
