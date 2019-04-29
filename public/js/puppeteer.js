const cookie = {
    name: process.argv[2],
    value: process.argv[3],
    domain: '127.0.0.1',
    url: 'http://127.0.0.1:8000/',
    path: '/',
    httpOnly: true,
    secure: false
}

const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.setCookie(cookie);
    await page.goto('http://127.0.0.1:8000/log/project/929', {waitUntil: 'networkidle2'});
    await page.pdf({
        path: '/Users/edmonddaoust/Documents/CODING/Learning/PERSONAL_PROJECT/Devlop-Eat/public/pdf/test.pdf',
        format: 'A4'
    });

    console.log(cookie);

    await browser.close();
})();