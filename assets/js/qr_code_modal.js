/**
 * QR Code Modal Component
 */

import $ from 'jquery';

document.addEventListener('DOMContentLoaded', function() {
    $('#qrCodeModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let imgDataUri = button.data('qrCodeDataUri');
        let qrCodeUrl = button.data('qrCodeDownloadUrl');
        let qrCodeMessage = button.data('qrCodeMessage');

        let modal = $(this);
        modal.find('.modal-body img').attr('src', imgDataUri);
        modal.find('.modal-body a').attr('href', qrCodeMessage);
        modal.find('.modal-body a').text(qrCodeMessage);
        modal.find('.modal-footer #qrCodeModalButtonDownload').attr('href', qrCodeUrl);
    })
});