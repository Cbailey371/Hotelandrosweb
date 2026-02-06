import Sortable from 'sortablejs';
window.Sortable = Sortable;

import Alpine from 'alpinejs';
window.Alpine = Alpine;
console.log('Alpine exposed to window');

Alpine.start();
