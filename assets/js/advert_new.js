// hide or show select based on advert kind value
// TODO refactor value into something more reliable
function triggerSelect() {
  const formWrap = document.querySelector('.advert-infos');
  const advertKindInput = document.querySelector('#advert_advertKind');
  advertKindInput.addEventListener('change', function(e) {
    const platformForm = document.querySelector('#advert_platform') || false;
    const text = this.options[this.selectedIndex].innerHTML;
    const priceInput = document.querySelector('.price-input');
    const gameWantedInput = document.querySelector('.game-wanted-input');
    if (text === 'Echange') {
      priceInput.classList.add('hidden');
      gameWantedInput.classList.remove('hidden');
    } else if (text === 'Location') {
      gameWantedInput.classList.add('hidden');
      priceInput.classList.remove('hidden');
      console.log(platformForm);
      if(platformForm){
        formWrap.removeChild(platformForm.parentNode);
      }
    } else {
      gameWantedInput.classList.add('hidden');
      priceInput.classList.add('hidden');
      if(platformForm){
        formWrap.removeChild(platformForm.parentNode);
      }
    }
  });
}


// add platform if advertKind is exchange based on gameWanted choice
function addPlatform() {
  const form = document.querySelector('form');
  const game = form.querySelector('#advert_gameWanted');
  const axios = require('axios');
  axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
  if(game !== null){
    game.addEventListener('change', function(e) {
      const data = {};
      data[game.getAttribute('name')] = e.target.value;
      const formData = new FormData(form);
      axios({
        method: 'post',
        url: form['action'],
        data: formData,
        config: {
          headers: { 'Content-Type': 'multipart/form-data' }
        }
      })
      .then(response => {
        const formWrap = document.querySelector('.advert-infos');
        const existingPlatformForm = document.querySelector('#advert_platform');
        console.log(existingPlatformForm);
        if( existingPlatformForm !== null){
          console.log('alo');
          formWrap.removeChild(existingPlatformForm.parentNode);
        }
        const html = new DOMParser().parseFromString(response.data, "text/html");
        const platformForm = html.querySelector('#advert_platform').parentNode;
        formWrap.appendChild(platformForm);
      })
      .catch(error => console.log(error));
    });
  }
}

addPlatform();
triggerSelect();


