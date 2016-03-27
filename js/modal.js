
function show_modal(content) {
    var modal =
        '<div id="modal" class="modal">' +
        '<div class="modal-content">' +
        '<span id="modal-close" class="modal-close">×</span>' +
        content +
        '</div>' +
        '</div>';

    $('#main').append(modal);

    modal = document.getElementById('modal');
    var close = document.getElementById("modal-close");

    close.onclick = function () {
        // remove modal
        modal.remove();
    }

    // click outside modal content
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.remove();
        }
    }
}