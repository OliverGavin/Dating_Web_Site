var mnum = 0;

function show_modal(content, style_class) {
    mnum++;
    var modal =
        '<div id="modal-'+mnum+'" class="modal '+style_class+'">' +
        '<div class="modal-content">' +
        '<span id="modal-close-'+mnum+'" class="modal-close"><i class="fa fa-times"></i></span>' +
        content +
        '</div>' +
        '</div>';

    $('#main').append(modal);

    modal = $("#modal-"+mnum);
    var close = $("#modal-close-"+mnum);

    close.click(function () {
        // remove modal
        modal.remove();
    });

    // click outside modal content
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.remove();
        }
    }
}