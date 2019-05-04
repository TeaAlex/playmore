const axios = require('axios');
const game = document.querySelector('#user_game_game');
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
game.addEventListener('change', function (e) {
  const form = document.querySelector('form');
  const data = {};
  data[game.getAttribute('name')] = e.target.value;
  const formData = new FormData(form);
  axios({
    method: 'post',
    url: form['action'],
    data: formData,
    config: { headers: {'Content-Type': 'multipart/form-data' }}
  })
  .then(response => {
    const platformWrap = document.querySelector('.platform-wrap');
    const existingPlatformForm = document.querySelector('#user_game_platform');
    if( existingPlatformForm !== null){
      platformWrap.removeChild(existingPlatformForm.parentNode);
    }
    const html = new DOMParser().parseFromString(response.data, "text/html");
    console.log(html.querySelector('#user_game_platform'));
    const platformForm = html.querySelector('#user_game_platform').parentNode;
    platformWrap.appendChild(platformForm);
  })
  .catch(error => console.log(error));
});

