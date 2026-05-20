import './bootstrap';

import Alpine from 'alpinejs';
import calendar from './calendar';

window.Alpine = Alpine;

Alpine.data('calendar', calendar);

Alpine.start();
