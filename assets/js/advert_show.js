const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

const offerBtn = document.querySelector('#offer-btn');
const backdrop = document.querySelector('.backdrop');
const modal = document.querySelector('.modal');

if(offerBtn !== null){
    offerBtn.addEventListener('click', (e) => {
        backdrop.style.display = 'block';
        modal.style.display = 'block';
    });

    backdrop.addEventListener('click', (e) => {
        backdrop.style.display = 'none';
        modal.style.display = 'none';
    });

    const acceptBtns = document.querySelectorAll('.accept-btn');
    if(acceptBtns){
        acceptBtns.forEach((btn) => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.offerId;
                fetch(Routing.generate('offer_accept', {'id': id}), {
                    'method': 'POST'
                })
                    .then(res => res.json())
                    .then(offers => {
                        if(offers.length > 0){
                            offers.forEach(declineOffer(id));
                        }
                        const card = document.querySelector(`div[data-offer-id="${id}"]`);
                        const buttons = card.querySelector('.buttons');
                        card.removeChild(buttons);
                        const p = document.createElement('p');
                        p.innerText = 'offre accepté';
                        p.classList.add('text-right');
                        p.classList.add('font-bold');
                        p.classList.add('text-green-light');
                        p.classList.add('uppercase');
                        card.appendChild(p);
                    })
            })
        });
    }

    const declineBtns = document.querySelectorAll('.decline-btn');
    if(declineBtns){
        declineBtns.forEach((btn) => {
            btn.addEventListener('click', function () {
                const id = this.dataset.offerId;
                fetch(Routing.generate('offer_decline', {'id': id}), {
                    'method': 'POST'
                })
                .then(() => declineOffer(id) )
            })
        });
    }

    const declineOffer = function(id){
        const card = document.querySelector(`div[data-offer-id="${id}"]`);
        card.style.opacity = 0.5;
        const buttons = card.querySelector('.buttons');
        card.removeChild(buttons);
    };

}
