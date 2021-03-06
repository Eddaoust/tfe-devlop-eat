/*const cookie = {
    name: process.argv[2],
    value: process.argv[3],
    domain: '127.0.0.1',
    url: 'http://127.0.0.1:8888/',
    path: '/',
    expires: -1,
    httpOnly: true,
    secure: false,
    session: true
};*/

const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    //await page.setCookie(cookie);
    await page.setViewport({
        width: 1250,
        height: 1300,
    });
    await page.setRequestInterception(true);
    page.on("request", request => {
        console.log(request.url());
        request.continue();
    });
    await page.goto(`http://localhost:8888/internal/project/${process.argv[3]}`, {waitUntil: 'networkidle2'});
    await page.pdf({
        path: `/Users/edmonddaoust/Web_dev/PERSONAL_PROJECT/tfe-devlop-eat/public/pdf/${process.argv[2]}.pdf`,
        format: 'A4'
    });


    await browser.close();
})();
