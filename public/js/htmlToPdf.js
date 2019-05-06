const cookie = {
    name: process.argv[2],
    value: process.argv[3],
    domain: '127.0.0.1',
    url: 'http://127.0.0.1:8888/',
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
    await page.setRequestInterception(true);
    page.on("request", request => {
        console.log(request.url());
        request.continue();
    });
    await page.goto('http://127.0.0.1:8888/log/project/1', {waitUntil: 'networkidle2'});
    await page.pdf({
        path: '/Users/edmonddaoust/Web_dev/PERSONAL_PROJECT/tfe-devlop-eat/public/pdf/test.pdf',
        format: 'A4'
    });

    console.log(cookie);

    await browser.close();
})();
