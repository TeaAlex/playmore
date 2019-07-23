module.exports = {
  // login(browser) {
  //   browser
  //   .url('localhost:8000/login')
  //   .waitForElementVisible('body')
  //   .setValue('input[name=_username]', 'playmore@playmore.com')
  //   .setValue('input[name=_password]', 'toto')
  //   .waitForElementVisible('button[type=submit]')
  //   .click('button[type=submit]')
  //   .pause(1000)
  //   .assert.containsText('.rounded.bg-playmore-blue.font-bold.p-4.inline.text-white.text-lg.uppercase.text-center',
  //     'VOIR LES ANNONCES')
  //   .end();
  // },
  // fromSearchToSubmitOffer(browser){
  //   browser
  //   .url('localhost:8000/login')
  //   .waitForElementVisible('body')
  //   .setValue('input[name=_username]', 'playmore@playmore.com')
  //   .setValue('input[name=_password]', 'toto')
  //   .waitForElementVisible('button[type=submit]')
  //   .click('button[type=submit]')
  //   .waitForElementVisible('.cursor-pointer.toggle-filter')
  //   .click('.cursor-pointer.toggle-filter')
  //   .assert.elementPresent('.search-filters')
  //   .assert.containsText('.text-lg.tracking-wide.uppercase.font-bold.text-playmore-purple-400', 'PLATEFORMES')
  //   .click('#GAMEBOY')
  //   .pause(1000);
  //   browser.expect.element('#GAMEBOY').to.be.selected;
  //   browser
  //   .click('.submit-filter')
  //   .assert.containsText('.platform', 'GAMEBOY')
  //   .click('.show-link')
  //   .assert.elementPresent('.offer-btn')
  //   .click('.offer-btn')
  //   .setValue('#offer_price', 10)
  //   .setValue('#offer_endDate', '08/01/2020')
  //   .click('.btn.btn-add')
  //   .useXpath()
  //   .assert.containsText("//a[text()='Voir mon offre']" ,'Voir mon offre')
  //   .pause(20000)
  //   .end();
  // },
  showInvalidDateError(browser) {
    browser
    .url('localhost:8000/login')
    .waitForElementVisible('body')
    .setValue('input[name=_username]', 'playmore@playmore.com')
    .setValue('input[name=_password]', 'toto')
    .waitForElementVisible('button[type=submit]')
    .waitForElementVisible('body')
    .url('localhost:8000/advert/show/9')
    .click('button[type=submit]')
    .click('.offer-btn')
    .setValue('#offer_price', 10)
    .setValue('#offer_endDate', '08/01/2000')
    .click('.btn.btn-add')
  }
  
  
  
};