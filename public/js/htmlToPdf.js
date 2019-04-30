const cookie = {
    name: process.argv[2],
    value: process.argv[3],
    domain: 'localhost',
    url: 'http://localhost:8888/',
    path: '/',
    expires: -1,
    httpOnly: true,
    secure: false,
    session: true
};

const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.setCookie(cookie);
    await page.goto('http://localhost:8888/log/project/929', {waitUntil: 'networkidle2'});
    await page.pdf({
        path: '/Users/edmonddaoust/Documents/CODING/Learning/PERSONAL_PROJECT/Devlop-Eat/public/pdf/test.pdf',
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
