
const cookie = {
    name: process.argv[2],
    value: process.argv[3],
    domain: '192.168.33.230',
    url: 'http://192.168.33.230/',
    path: '/',
    expires: -1,
    httpOnly: true,
    secure: false,
    session: true
};

const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch({
        headless: true,
        executablePath:'/var/www/html/tfe-devlop-eat/node_modules/puppeteer/.local-chromium/linux-650583/chrome-linux/chrome',
        args: ['--no-sandbox', '--disable-setuid-sandbox'],
    });
    const page = await browser.newPage();
    await page.setCookie(cookie);
    await page.setRequestInterception(true);
    page.on("request", request => {
        console.log(request.url());
        request.continue();
    });
    await page.goto('http://192.168.33.230/log/project/1', {waitUntil: 'domcontentloaded'});
    await page.pdf({
        path: '/var/www/html/tfe-devlop-eat/public/pdf/test.pdf',
        format: 'A4'
    });

    console.log(cookie);

    await browser.close();
})();
/*
const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch({headless: false});
    const page = await browser.newPage();
    await page.goto('http://localhost:8888/', {waitUntil: 'domcontentloaded'});

    await page.type('#mail', 'vauger@voila.fr');
    await page.type('#password', 'tasttast');
    await page.click('#submit');
    await page.waitForNavigation();

    const cookies = await page.cookies();

    console.log(cookies);
    const page2 = await browser.newPage();
    await page2.setCookie(...cookies);
    await page2.goto('http://localhost:8888/log/company', {waitUntil: 'domcontentloaded'});

    console.log(cookies);

    await browser.close();
})();*/
