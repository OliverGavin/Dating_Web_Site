$(document).ready(function() {
// locks the scroll to a particular element and prevents the rest of the page from scrolling
    $('.scroll').on('touchmove wheel', function (e) {

        var evt = window.event || e;

        // Prevents scrolling downwards if at the bottom
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {

            if ((evt.wheelDelta || -evt.detail) < 0) {
                e.preventDefault();
            }
        }

        // Prevents scrolling upwards if at the top
        if ($(this).scrollTop() <= 0) {

            if ((evt.wheelDelta || -evt.detail) > 0) {
                e.preventDefault();
            }
        }
    });
});