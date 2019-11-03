/**
 * Clipboard Component
 */

import ClipboardJS from 'clipboard';
import $ from 'jquery';

document.addEventListener('DOMContentLoaded', function() {
    let clipboard = new ClipboardJS('.btn');

    clipboard.on('success', function(e) {
        // console.log(e);
        $(e.trigger)
            .tooltip('hide')
            .attr('data-original-title', $(e.trigger).data('copiedText'))
            .tooltip('show')
        ;
    });

    clipboard.on('error', function(e) {
        // console.log(e);
    });
});