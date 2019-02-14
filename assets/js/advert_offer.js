const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

const backdrop = document.querySelector('.backdrop');
const modal = document.querySelector('.modal');
const modalContent = modal.querySelector('.modal-content');
function triggerModal(e) {
  e.preventDefault();
  const advertId = this.dataset.advertId;
  const axios = require('axios');
  axios({
    method: 'get',
    url: Routing.generate('offer_new', {id: advertId}),
  }).then(response => {
    const html = new DOMParser().parseFromString(response.data, "text/html");
    const form = html.querySelector('form');
    modalContent.appendChild(form);
    backdrop.style.display = 'block';
    modal.style.display = 'block';
  });
}

const btns = document.querySelectorAll('.offer-btn');
for (let btn of btns) {
  btn.addEventListener('click', triggerModal);
}

backdrop.addEventListener('click', function () {
  modal.style.display = 'none';
  modalContent.innerHTML = '';
  this.style.display = 'none';
});
