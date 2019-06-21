const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import createElemWithClasses from './helper'
Routing.setRoutingData(routes);

const btn = document.querySelector('.geolocate-btn');
btn.addEventListener('click', (e) => {
    e.preventDefault();
    const slug = btn.dataset.slug;
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
            const positions = {lat : position.coords.latitude, lon: position.coords.longitude };
            fetch(Routing.generate('user_geolocate', positions))
            .then(res => res.json())
            .then(address => {
                const h1 = createElemWithClasses('h1', 'text-xl text-playmore-purple-500 mb-2');
                const popup = createElemWithClasses('div', 'geoloc-popup');
                const p = createElemWithClasses('p', 'position text-playmore-purple-400 mb-2');
                const a = document.createElement('a');
                h1.innerText = 'Votre position';
                a.href = '/user/edit/' + slug;
                a.innerText = 'Cliquez ici pour la modifier';
                p.innerText = address;
                popup.appendChild(h1);
                popup.appendChild(p);
                popup.appendChild(a);
                const body = document.querySelector("body");
                body.appendChild(popup);
            });
        });
    }
});