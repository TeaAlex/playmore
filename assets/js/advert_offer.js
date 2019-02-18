const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

const body = document.querySelector('body');
const backdrop = document.createElement('div');
backdrop.classList.add('backdrop');
const modal = document.createElement('div');
modal.classList.add('modal');
const modalTitle = document.createElement('h1');
modalTitle.classList.add('text-primary');
modalTitle.classList.add('font-bold');
modalTitle.innerText = 'Faire une offre';
const modalContent = document.createElement('div');
modalContent.classList.add('modal-content');
modal.appendChild(modalTitle);
modal.appendChild(modalContent);
body.appendChild(modal);
body.appendChild(backdrop);

const axios = require('axios');

function triggerModal(e) {
  e.preventDefault();
  const advertId = this.dataset.advertId;
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

function showOffers(e){
  e.preventDefault();
  const advertId = this.dataset.advertId;
  axios({
    method: 'get',
    url: Routing.generate('offer_list', {id: advertId})
  }).then(response => {
    modal.classList.add('bg-grey-lightest');
    modalTitle.innerText = 'Offres sur cette annonce';
    modalContent.innerHTML = response.data;
    backdrop.style.display = 'block';
    modal.style.display = 'block';
  })
}

const btns = document.querySelectorAll('.offer-btn');
for (let btn of btns) {
  btn.addEventListener('click', triggerModal);
}

const showOffersBtn = document.querySelectorAll('.showOffers');
for (let btn of showOffersBtn) {
  btn.addEventListener('click', showOffers);
}


backdrop.addEventListener('click', function () {
  modal.style.display = 'none';
  modalContent.innerHTML = '';
  this.style.display = 'none';
});
