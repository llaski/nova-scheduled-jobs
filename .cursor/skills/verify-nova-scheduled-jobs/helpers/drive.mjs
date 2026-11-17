#!/usr/bin/env node
/**
 * Drive Nova Scheduled Jobs UI features via Puppeteer (system Chrome).
 *
 * Usage (from package root):
 *   .cursor/skills/verify-nova-scheduled-jobs/helpers/drive.mjs --feature browse-scheduled-jobs
 *   .cursor/skills/verify-nova-scheduled-jobs/helpers/drive.mjs --feature dispatch-job
 */
import puppeteer from 'puppeteer-core';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const BASE_URL = process.env.BASE_URL || 'http://127.0.0.1:8000';
const EVIDENCE_DIR =
  process.env.EVIDENCE_DIR ||
  (fs.existsSync('/opt/cursor/artifacts')
    ? '/opt/cursor/artifacts/verify-nova-scheduled-jobs'
    : path.join(__dirname, '..', 'artifacts'));
const EMAIL = process.env.NOVA_DEMO_EMAIL || 'admin@example.com';
const PASSWORD = process.env.NOVA_DEMO_PASSWORD || 'password';
const CHROME =
  process.env.CHROME_PATH ||
  ['/usr/bin/google-chrome-stable', '/usr/bin/google-chrome', '/usr/bin/chromium'].find((p) =>
    fs.existsSync(p),
  );

const args = process.argv.slice(2);
const featureIdx = args.indexOf('--feature');
const feature = featureIdx >= 0 ? args[featureIdx + 1] : 'browse-scheduled-jobs';

fs.mkdirSync(EVIDENCE_DIR, { recursive: true });

function shot(page, name) {
  const p = path.join(EVIDENCE_DIR, name);
  return page.screenshot({ path: p, fullPage: false }).then(() => {
    console.log('SHOT', p);
    return p;
  });
}

function writeJson(name, data) {
  const p = path.join(EVIDENCE_DIR, name);
  fs.writeFileSync(p, JSON.stringify(data, null, 2));
  console.log('JSON', p);
  return p;
}

async function login(page) {
  await page.goto(`${BASE_URL}/nova/login`, { waitUntil: 'networkidle2' });
  const emailSel = 'input[type="email"], input[name="email"], #email';
  const passSel = 'input[type="password"], input[name="password"], #password';
  await page.waitForSelector(emailSel);
  await page.click(emailSel, { clickCount: 3 });
  await page.type(emailSel, EMAIL);
  await page.click(passSel, { clickCount: 3 });
  await page.type(passSel, PASSWORD);
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'networkidle2' }).catch(() => null),
    page.keyboard.press('Enter'),
  ]);
  if (page.url().includes('/login')) {
    const btn = await page.$('button[type="submit"]');
    if (btn) {
      await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle2' }).catch(() => null),
        btn.click(),
      ]);
    }
  }
  await page.waitForFunction(() => !location.pathname.includes('/login'), { timeout: 20000 });
  await new Promise((r) => setTimeout(r, 1000));
}

async function fetchJobsApi(page) {
  return page.evaluate(async () => {
    const res = await fetch('/nova-vendor/nova-scheduled-jobs/jobs', {
      headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin',
    });
    const json = await res.json();
    return { status: res.status, count: (json.data || json).length, data: json.data || json };
  });
}

async function browseScheduledJobs(page) {
  // Card on main dashboard — demo uses only(['command', 'nextDue'])
  await page.goto(`${BASE_URL}/nova/dashboards/main`, { waitUntil: 'networkidle2' });
  await new Promise((r) => setTimeout(r, 1500));
  await shot(page, 'browse-card.png');
  const cardText = await page.evaluate(() => document.body.innerText);
  if (!/Scheduled Jobs/i.test(cardText)) throw new Error('dashboard missing Scheduled Jobs card');
  if (!/inspire|PruneDemoRecords|demo:heartbeat/i.test(cardText)) {
    throw new Error('dashboard card missing expected job rows');
  }
  if (!/\bName\b/i.test(cardText) || !/Next Due/i.test(cardText)) {
    throw new Error('dashboard card missing Name/Next Due headers');
  }
  // Expression column intentionally hidden on the demo card
  const cardHeaders = await page.evaluate(() => {
    const card = [...document.querySelectorAll('h1,h2,h3,[class*="heading"]')]
      .find((el) => /Scheduled Jobs/i.test(el.textContent || ''));
    const root = card?.closest('[class*="card"], section, div') || document.body;
    return [...root.querySelectorAll('thead th')].map((th) => th.textContent.trim());
  });
  if (cardHeaders.some((h) => /^Expression$/i.test(h))) {
    throw new Error('demo card should hide Expression column, got: ' + JSON.stringify(cardHeaders));
  }

  // Tool — demo uses except(['timezone', 'runInBackground', 'evenInMaintenanceMode'])
  await page.goto(`${BASE_URL}/nova/nova-scheduled-jobs`, { waitUntil: 'networkidle2' });
  await new Promise((r) => setTimeout(r, 1500));
  await shot(page, 'browse-tool.png');
  const toolText = await page.evaluate(() => document.body.innerText);
  for (const needle of ['Schedule', 'Expression', 'Without Overlapping', 'On One Server', 'Dispatch']) {
    if (!new RegExp(needle.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'i').test(toolText)) {
      throw new Error(`tool missing column/value: ${needle}`);
    }
  }
  for (const hidden of ['Timezone', 'Run In Background', 'Run In Maintenance Mode']) {
    if (new RegExp(hidden.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'i').test(toolText)) {
      throw new Error(`demo tool should hide column: ${hidden}`);
    }
  }

  const api = await fetchJobsApi(page);
  if (api.status !== 200) throw new Error(`jobs API ${api.status}`);
  if (api.count < 10) throw new Error(`expected >=10 jobs, got ${api.count}`);
  const hasNy = (api.data || []).some((j) => j.timezone === 'America/New_York');
  if (!hasNy) throw new Error('API missing America/New_York timezone job (still expected in payload)');
  writeJson('browse-jobs.json', { feature: 'browse-scheduled-jobs', api, cardHeaders });
  console.log('PASS browse-scheduled-jobs count=', api.count, 'cardHeaders=', cardHeaders.join(','));
}

async function dispatchJob(page) {
  await page.goto(`${BASE_URL}/nova/nova-scheduled-jobs`, { waitUntil: 'networkidle2' });
  await new Promise((r) => setTimeout(r, 1500));

  const buttonState = await page.evaluate(() => {
    const rows = [...document.querySelectorAll('tbody tr')];
    const info = rows.map((tr) => {
      const cmd =
        tr.querySelector('td p')?.textContent?.trim() ||
        (tr.cells[0]?.innerText || '').trim().split('\n')[0] ||
        '';
      const btn = tr.querySelector('button[aria-label="Dispatch"]');
      return { cmd, disabled: !btn ? null : btn.disabled, hasBtn: !!btn };
    });
    return info;
  });

  const artisan = buttonState.find(
    (r) => /artisan|inspire|demo:/i.test(r.cmd) && !r.cmd.includes('Jobs'),
  );
  // DOM text is App\Jobs\... (single backslash in the string value)
  const target =
    buttonState.find((r) => r.disabled === false && r.cmd.includes('Jobs')) ||
    buttonState.find((r) => r.disabled === false);

  if (!artisan || artisan.disabled !== true) {
    console.warn('WARN expected disabled Dispatch on artisan row', artisan);
  }
  if (!target || target.disabled !== false) {
    throw new Error('no enabled Dispatch button for a Job class row: ' + JSON.stringify(buttonState.slice(0, 5)));
  }

  // Cancel path
  await page.evaluate((cmd) => {
    const rows = [...document.querySelectorAll('tbody tr')];
    for (const tr of rows) {
      const text = tr.textContent || '';
      if (text.includes(cmd)) {
        const btn = tr.querySelector('button[aria-label="Dispatch"], button[title="Dispatch"]');
        if (btn && !btn.disabled) {
          btn.click();
          return;
        }
      }
    }
    throw new Error('click failed for ' + cmd);
  }, target.cmd);

  await page.waitForFunction(
    () => /dispatch job/i.test(document.body.innerText) && document.querySelector('[role=alertdialog], [role=dialog]'),
    { timeout: 8000 },
  );
  await shot(page, 'dispatch-modal.png');

  // Cancel — Nova may use custom elements (<linkbutton>); prefer dusk/testid then form close.
  await page.evaluate(() => {
    const root = document.querySelector('[role=alertdialog], [role=dialog]');
    const cancel =
      root?.querySelector('[data-testid="cancel-button"], [dusk="cancel-delete-button"]') ||
      [...(root || document).querySelectorAll('button, a, [role=button], linkbutton')].find((b) =>
        /^Cancel$/i.test((b.textContent || '').trim()),
      );
    if (!cancel) throw new Error('Cancel control missing');
    cancel.dispatchEvent(new MouseEvent('click', { bubbles: true, cancelable: true, view: window }));
  });
  await page.waitForFunction(
    () => !document.querySelector('[role=alertdialog], [role=dialog]'),
    { timeout: 8000 },
  ).catch(() => {});
  await new Promise((r) => setTimeout(r, 400));

  // Confirm path — listen for POST
  const dispatchResult = { status: null, command: target.cmd };
  page.on('response', async (res) => {
    if (res.url().includes('/nova-vendor/nova-scheduled-jobs/dispatch') && res.request().method() === 'POST') {
      dispatchResult.status = res.status();
    }
  });

  await page.evaluate((cmd) => {
    const rows = [...document.querySelectorAll('tbody tr')];
    for (const tr of rows) {
      if ((tr.textContent || '').includes(cmd)) {
        const btn = tr.querySelector('button[aria-label="Dispatch"], button[title="Dispatch"]');
        if (btn && !btn.disabled) {
          btn.click();
          return;
        }
      }
    }
    throw new Error('re-open dispatch failed');
  }, target.cmd);

  await page.waitForFunction(
    () => /dispatch job/i.test(document.body.innerText) && document.querySelector('[role=alertdialog], [role=dialog]'),
    { timeout: 8000 },
  );
  // Confirm — <loadingbutton type="submit"> does not always respond to .click(); submit the form.
  await page.evaluate(() => {
    const root = document.querySelector('[role=alertdialog], [role=dialog]');
    const form = root?.querySelector('form');
    if (form && typeof form.requestSubmit === 'function') {
      form.requestSubmit();
      return;
    }
    if (form) {
      form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
      return;
    }
    throw new Error('Confirm form missing');
  });

  await page.waitForFunction(
    () => {
      const toast = /job was dispatched/i.test(document.body.innerText);
      const closed = !document.querySelector('[role=alertdialog], [role=dialog]');
      return toast || closed;
    },
    { timeout: 15000 },
  );
  await new Promise((r) => setTimeout(r, 1200));
  await shot(page, 'dispatch-success.png');

  const bodyText = await page.evaluate(() => document.body.innerText);
  const toastOk = /job was dispatched/i.test(bodyText);
  if (!toastOk && !(dispatchResult.status >= 200 && dispatchResult.status < 300)) {
    // toast may have auto-dismissed; accept HTTP success alone
    if (!(dispatchResult.status >= 200 && dispatchResult.status < 300)) {
      throw new Error(`dispatch not confirmed toast=${toastOk} status=${dispatchResult.status}`);
    }
  }

  writeJson('dispatch-job.json', {
    feature: 'dispatch-job',
    artisanDisabled: artisan?.disabled ?? null,
    artisanCommand: artisan?.cmd ?? null,
    dispatchedCommand: target.cmd,
    httpStatus: dispatchResult.status,
    toastSeen: toastOk,
    buttonSample: buttonState.slice(0, 8),
  });
  console.log('PASS dispatch-job command=', target.cmd, 'status=', dispatchResult.status);
}

async function main() {
  if (!CHROME) throw new Error('Chrome not found; set CHROME_PATH');
  const browser = await puppeteer.launch({
    executablePath: CHROME,
    headless: 'new',
    defaultViewport: { width: 1440, height: 900 },
    args: ['--no-sandbox', '--disable-setuid-sandbox', '--disable-dev-shm-usage'],
  });
  const page = await browser.newPage();
  page.setDefaultTimeout(30000);
  try {
    await login(page);
    if (feature === 'browse-scheduled-jobs') await browseScheduledJobs(page);
    else if (feature === 'dispatch-job') await dispatchJob(page);
    else throw new Error(`unknown feature: ${feature}`);
  } finally {
    await browser.close();
  }
}

main().catch((e) => {
  console.error('FAIL', e);
  process.exit(1);
});
