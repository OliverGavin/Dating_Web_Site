<article>
    <form action="" method="post" enctype="multipart/form-data" onSubmit="" class="style-underline no-valid-messages">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <div class="profile-image">
            <img class="profile-pic" src="<?php echo get_profile_image(IMG_MEDIUM, $user_id)?>">
        </div>
        <div class="profile-info">

<!--            TODO-->
            <div class="profile-field profile-DOB group <?= get_form_field_status('profile_image'); ?>">
                <label for="fileToUpload">Profile picture: </label>
                <input type="file" accept="image/png, image/jpg, image/jpeg" name="fileToUpload" id="fileToUpload">
            </div>
            <?= get_form_field_message('profile_image');  ?>
            <!--                user_id-->
            <!--                first_name-->
            <!--                last_name-->
            <div class="profile-field profile-name group <?= get_form_field_status('first_name'); ?> <?= get_form_field_status('last_name'); ?>">
                <label for="first_name" hidden="hidden">First name</label>
                <input type="text" id="first_name" name="first_name" size="8" maxlength="30" value="<?php echo $profile->first_name; ?>" placeholder="First Name" />

                <label for="last_name" hidden="hidden">Last name</label>
                <input type="text" id="last_name" name="last_name" size="12" maxlength="30" value="<?php echo $profile->last_name; ?>" placeholder="Last Name" />
            </div>
            <?= get_form_field_message('first_name');  ?>
            <?= get_form_field_message('last_name');  ?>

            <!--                Photo: <input type="file">-->

            <div class="profile-field profile-DOB group <?= get_form_field_status('DOB_date'); ?>">
                <label for="DOB_day" hidden="hidden">Date of birth</label>
                <select id="DOB_day" name="DOB_day" onchange="validate_date_fields(this)">
                    <?php
                    for($i = 1; $i <= 31; $i++) {
                        $default = (($i == $profile->DOB_day) ? "selected=\"selected\"" : "");  // TODO padding????????
                        echo "<option " . $default . " value=\"$i\">$i</option>";
                    }
                    ?>
                </select>
                <label for="DOB_month" hidden="hidden">Month of birth</label>
                <select id="DOB_month" name="DOB_month" onchange="validate_date_fields(this)">
                    <?php
                    $months = array("January", "February", "March", "April", "May", "June", "July",
                        "August", "September", "October", "November", "December");
                    for($i = 0; $i < 12; $i++) {
                        $default = (($i + 1 == $profile->DOB_month) ? "selected=\"selected\"" : "");    // TODO padding????????
                        echo "<option " . $default . " value=\"" . ($i+1) . "\">$months[$i]</option>";
                    }
                    ?>
                </select>
                <label for="DOB_year" hidden="hidden">Year of birth</label>
                <select id="DOB_year" name="DOB_year" onchange="validate_date_fields(this)">
                    <?php
                    $current_year = date("Y");
                    for($i = $current_year; $i > $current_year - 100; $i--) {
                        $default = (($i == $profile->DOB_year) ? "selected=\"selected\"" : "");
                        echo "<option " . $default . " value=\"$i\">$i</option>";
                    }
                    ?>
                </select>
                <script>
                    function validate_date_fields(el) {
//                        validate_field(el, $('#DOB_day').val()+$('#DOB_month').val()+$('#DOB_year').val(), 'DOB_date', 'date_of_birth');
//                        TODO after no js
                    }
                </script>
            </div>
            <?= get_form_field_message('DOB_date');  ?>

            <div class="profile-field profile-sex">
                <label for="sex" hidden="hidden">Sex</label>
                <select id="sex" name="sex">
                    <option <?php echo (($profile->sex == 1) ? "selected=\"selected\"" : ""); ?> value="1">Man</option>
                    <option <?php echo (($profile->sex == 0) ? "selected=\"selected\"" : ""); ?> value="0">Woman</option>
                </select>
            </div>

            <div class="profile-field profile-description group <?= get_form_field_status('description'); ?>">
                <label for="description">Description</label>
                <textarea id="description" name="description" cols="60" rows="10" placeholder="Tell us a little about yourself..."><?php echo $profile->description; ?></textarea>
            </div>
            <?= get_form_field_message('description');  ?>
            <!--                country-->
            <!--                county-->
            <div class="profile-field profile-location group <?= get_form_field_status('location'); ?>">
                <label for="location" hidden="hidden">Location</label>
                <input type="text" id="location" name="location" size="8" maxlength="30" value="<?php echo $profile->location; ?>" placeholder="Location" />
            </div>
            <?= get_form_field_message('location');  ?>

            <div class="profile-field profile-looking-for">
                <label for="looking_for">Looking for:</label>
                <select id="looking_for" name="looking_for">
                    <option <?php echo (($profile->looking_for == 1) ? "selected=\"selected\"" : ""); ?> value="1">Man</option>
                    <option <?php echo (($profile->looking_for == 0) ? "selected=\"selected\"" : ""); ?> value="0">Woman</option>
                </select>
            </div>
            <!--                min_age-->
            <!--                max_age-->
            <div class="profile-field profile-age-range">
                <label for="age_range">Aged:</label>
                <fieldset id="age_range" style="border: hidden">
                    <label for="min_age" hidden="hidden">Minimum age</label>
                    <select id="min_age" name="min_age">
                        <?php
                        for($i = 18; $i <= 100; $i++) {
                            $default = (($i == (isset($profile->min_age) ? $profile->min_age : $profile->age)) ? "selected=\"selected\"" : "");
                            echo "<option " . $default . " value=\"$i\">$i</option>";
                        }
                        ?>
                    </select>
                    -
                    <label for="max_age" hidden="hidden">Maximum age</label>
                    <select id="max_age" name="max_age">
                        <?php
                        for($i = 18; $i <= 100; $i++) {
                            $default = (($i == (isset($profile->max_age) ? $profile->max_age : $profile->age)) ? "selected=\"selected\"" : "");
                            echo "<option " . $default . " value=\"$i\">$i</option>";
                        }
                        ?>
                    </select>
                </fieldset>
            </div>
            <br />

            <div id="profile-interests-container">
                <?php include 'core/templates/edit-profile-interests.php'; ?>
            </div>
            <?php if ($creating == false) { ?>
            <script>
                var like_input = $('#new_interest_like');
                var dislike_input = $('#new_interest_dislike');

                // Sets up listeners
                function init() {

                    like_input = $('#new_interest_like');

                    like_input.keyup(function(e){
                        if(e.keyCode == 13)
                        {
                            add_interest(like_input, 'like');
                        }
                    });

                    dislike_input = $('#new_interest_dislike');

                    dislike_input.keyup(function(e){
                        if(e.keyCode == 13)
                        {
                            add_interest(dislike_input, 'dislike');
                        }
                    });

                }

                // Blocks enter key submit
                $(window).keydown(function(e){
                    if(e.keyCode == 13 && !$("textarea").is(":focus")) {        // except in a text area where we can press enter
                        e.preventDefault();
                        return false;
                    }
                    init();
                });

                // remove
                function remove_interest(el, interest_id) {
                    event.preventDefault();
                    var id = <?=$user_id?>;
                    $.post('ajax/add_remove_interest.php', {id:id, delete_interest:interest_id}, function(data) {
                        // Callback function
                        if (data == 'success') {
                            $(el).parent().remove();
                        }
                    });
                }

                // add and reload
                function add_interest(el, likes) {
                    var id = <?=$user_id?>;
                    $.post('ajax/add_remove_interest.php', {id:id, add_interest:el.val(), likes:likes}, function(data) {
                        // Callback function
                        if (data != 'failed') {
                            // swap
                            $('#profile-interests-container').html(data);
                            init();
                            if (likes == 'like') {
                                like_input.focus();
                            } else {
                                dislike_input.focus();
                            }
                        }
                    });
                }


            </script>
            <?php } ?>

            <button type="submit" name="action" value="Save" class="action action-save">
                <div class="action">
                    <p><i class="fa fa-floppy-o"></i></p>
                    <p>SAVE</p>
                </div>
            </button>

        </div>

    </form>
</article>