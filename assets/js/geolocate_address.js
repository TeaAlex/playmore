import places from 'places.js';
import { appId } from './algolia_keys';
import { apiKey } from './algolia_keys';

const fixedOptions = {
  appId,
  apiKey,
  container: document.querySelector('#address-input'),
};
const reconfigurableOptions = {
  language: 'fr',
  countries: 'fr',
  aroundLatLngViaIP: true
};
const placesAutocomplete = places(fixedOptions).configure(reconfigurableOptions);