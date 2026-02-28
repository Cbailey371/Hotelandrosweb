import Sortable from 'sortablejs';
window.Sortable = Sortable;

import Alpine from 'alpinejs';
window.Alpine = Alpine;
console.log('Alpine exposed to window');

import interact from 'interactjs';
window.interact = interact;

Alpine.start();
