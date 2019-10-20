import '../scss/app.scss';

import './countries_chart';
import './referrers_chart';
import './visits_chart';
import './browsers_chart';
import './os_chart';

const $ = require('jquery');
require('bootstrap');

import '@fortawesome/fontawesome-free/js/all'

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});