var validation_on = true;

function set_validation(on) {
    validation_on = on;
}

function validate_field(el, field_value, field_name, validation_type) {
    if (!validation_on) return;

    if (field_value.length == 0) field_value = ' ';
    $.post('ajax/validate_form.php', {action:'validate_field', field_name:field_name, field_value:field_value, validation_type:validation_type}, function(data) {
        // Callback function
        if (data == 'failed') {
            return;
        }

        //var el_container = $(el).closest(".group");
        //$('#'+field_name+'-field-message').remove();
        //el_container.removeClass('valid');

        if (data == 'success') {
            update_field_message(el, true, field_name, '');
            //el_container.addClass('valid');
            //var msg = '<p id="' + field_name + '-field-message" class="field-message valid">'
            //    + '<i class="fa"></i> '
            //    + '</p>';
            //el_container.after(msg);
        } else {
//                                el.focus();
            update_field_message(el, false, field_name, data[field_name]);
            //el_container.addClass('invalid');
            //var msg = '<p id="' + field_name + '-field-message" class="field-message invalid">'
            //    + '<i class="fa"></i> ' + data[field_name]
            //    + '</p>';
            //el_container.after(msg);
        }

        //var valid = true;
        //var msg = $(el_container).next();
        //while (valid && $(msg).hasClass('field-message')) {
        //    if (msg.hasClass('invalid')) {
        //        valid = false;
        //    }
        //    msg = $(msg).next();
        //}
        //if (valid) {
        //    el_container.removeClass('invalid');
        //}
    });
}

function check_same_password(el, field_name, pass1, pass2) {
    if (pass1 == pass2) {
        update_field_message(el, true, field_name, '');
    } else {
        update_field_message(el, false, field_name, 'Passwords do not match');
    }
}

function update_field_message(el, valid, field_name, field_message) {

    var el_container = $(el).closest(".group");
    $('#'+field_name+'-field-message').remove();
    el_container.removeClass('valid');

    if (valid) {
        el_container.addClass('valid');
        var msg = '<p id="' + field_name + '-field-message" class="field-message valid">'
            + '<i class="fa"></i> ' + field_message
            + '</p>';
        el_container.after(msg);
    } else {
//                                el.focus();
        el_container.addClass('invalid');
        var msg = '<p id="' + field_name + '-field-message" class="field-message invalid">'
            + '<i class="fa"></i> ' + field_message
            + '</p>';
        el_container.after(msg);
    }

    var valid = true;
    var msg = $(el_container).next();
    while (valid && $(msg).hasClass('field-message')) {
        if (msg.hasClass('invalid')) {
            valid = false;
        }
        msg = $(msg).next();
    }
    if (valid) {
        el_container.removeClass('invalid');
    }

}