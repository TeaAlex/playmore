import createElemWithClasses from './helper'

const url = new URL('http://localhost:3000/hub');
url.searchParams.append('topic', 'http://monsite.com/offer');
url.searchParams.append('topic', 'http://monsite.com/accepted_offer');
url.searchParams.append('topic', 'http://monsite.com/declined_offer');

const eventSource = new EventSource(url, { withCredentials: true });

eventSource.onmessage = e => {
  notify(e.data);
};

function notify (data) {
  data = JSON.parse(data);
  const user = JSON.parse(data.user);
  let message = "";
  switch (data.type) {
    case "new_offer":
          message = `${user.username} vous a <a class="text-playmore-purple-500 font-bold" href="${data.url}">fait une offre</a>`;
          break;
    case "accepted_offer":
          message = "toto";
          break;
    default:
          message = "";
  }
  displayNotif(message);
}

function displayNotif(message){
  const notifNb = document.querySelector('.notif-number');
  const notifs = document.querySelector('.notifs');
  const p = createElemWithClasses('p', 'text-sm my-2');
  const nb = parseInt(notifNb.innerText) + 1;
  notifNb.innerText = nb;
  notifNb.style.display = 'flex';
  p.innerHTML = message;
  notifs.appendChild(p);
}


