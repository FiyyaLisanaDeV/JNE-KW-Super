const puppeteer = require('puppeteer');

(async () => {
  const browser = await puppeteer.launch({ args: ['--no-sandbox'] });
  const page = await browser.newPage();
  
  page.on('console', msg => console.log('PAGE LOG:', msg.text()));
  page.on('pageerror', error => console.log('PAGE ERROR:', error.message));
  page.on('response', response => {
    if (!response.ok()) {
      console.log('NETWORK ERROR:', response.status(), response.url());
    }
  });

  console.log('Navigating to admin dashboard...');
  await page.goto('https://kurir.fiyya.cloud/admin', { waitUntil: 'networkidle0' });
  console.log('Navigation complete.');
  
  await browser.close();
})();
