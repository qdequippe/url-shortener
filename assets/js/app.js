import '../scss/app.scss';

import $ from 'jquery';
import 'bootstrap';
import '@fortawesome/fontawesome-free/js/all';

import './qr_code_modal';
import './clipboard';
import './delete_modal';

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
    $('[data-toggle="tooltip"]').tooltip();
});