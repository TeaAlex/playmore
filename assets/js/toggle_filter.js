const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

const btn = document.querySelector('.toggle-filter');
const header = document.querySelector('header');
btn.addEventListener('click', function (e) {
  const filtersContainer = document.querySelector('.search-filters');
  if(filtersContainer){
    filtersContainer.parentNode.removeChild(filtersContainer);
    return;
  }
  console.log('clicked');
  fetch(Routing.generate('app_security_filters'))
  .then(res => res.text())
  .then(html => {
      const doc = new DOMParser().parseFromString(html, "text/html");
      const container = doc.querySelector('.search-filters');
      header.appendChild(container);
  })
  .catch(error => console.log(error));
});