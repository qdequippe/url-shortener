/**
 * Delete Modal Component
 */

import $ from 'jquery';

document.addEventListener('DOMContentLoaded', function() {
    $('#deleteModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let csrfToken = button.data('csrfToken');
        let actionPath = button.data('actionPath');

        let modal = $(this);
        modal.find('input[name="_token"]').val(csrfToken);
        modal.find('form').attr('action', actionPath);
    })
});