const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

const btn = document.querySelector('.geolocate-btn');
btn.addEventListener('click', (e) => {
    e.preventDefault();
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
            const positions = {lat : position.coords.latitude, lon: position.coords.longitude };
            console.log(positions);
            fetch(Routing.generate('user_geolocate', positions))
        });
    }
});