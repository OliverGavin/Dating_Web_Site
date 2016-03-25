
function show_modal(content) {
    var modal =
        '<div id="modal" class="modal">' +
        '<div class="modal-content">' +
        '<span class="close">×</span>' +
        content +
        '</div>' +
        '</div>';

    $('#main').append(modal);

    modal = document.getElementById('modal');
    var close = document.getElementsByClassName("close")[0];

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