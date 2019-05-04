import { format, render, cancel, register } from 'timeago.js';

const dates = document.querySelectorAll('.created_at');
const localeFunc = (number, index, total_sec) => {
  return [
    ['à l\'instant', 'dans un instant'],
    ['Il y a %s secondes ', 'dans %s secondes'],
    ['Il y a 1 minute ', 'dans 1 minute'],
    ['Il y a %s minutes ', 'dans %s minutes'],
    ['Il y a 1 heure ', 'dans 1 heure'],
    ['Il y a %s heures ', 'dans %s heures'],
    ['Il y a 1 day ', 'dans 1 day'],
    ['Il y a %s days ', 'dans %s days'],
    ['Il y a 1 semaine ', 'dans 1 semaine'],
    ['Il y a %s semaines ', 'dans %s semaines'],
    ['Il y a 1 mois ', 'dans 1 mois'],
    ['Il y a %s mois ', 'dans %s mois'],
    ['Il y a 1 an ', 'dans 1 an'],
    ['Il y a %s ans ', 'dans %s ans']
  ][index];
};
// register your locale with timeago
register('fr_FR', localeFunc);

render(dates, 'fr_FR');