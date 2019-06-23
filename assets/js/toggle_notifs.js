const notifs = document.querySelector('.notifs');
const notifNb = document.querySelector('.notif-number');
const notifIcon = document.querySelector('.notif-icon');
notifIcon.addEventListener('click', function(e){
  e.preventDefault();
  notifNb.style.display = 'none';
  if (notifs.style.display === 'flex') {
    notifs.style.display = 'none';
  } else {
    notifs.style.display = 'flex';
  }
})