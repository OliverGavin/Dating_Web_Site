<?php
$pathToRoot = '../';
require_once $pathToRoot.'core/init.php';
require_once $pathToRoot.'core/func/notifications.php';

verify_login();

if (isset($_POST['id'])) {
    $report_id = (int) $_POST['id'];
    $reports = get_report_notifications($report_id);

    if (!$reports) {
        echo 'failed';
        exit();
    }

    $report = $reports[0];

    $sender_name = get_user_name($report->sender_id);
    $receiver_name = get_user_name($report->user_id);
    ?>

    <div id="report-form">
        <a href="" onClick="get_profile(<?=$report->sender_id?>)"><img class="profile-pic large" src=<?php echo get_profile_image(IMG_MEDIUM, $report->sender_id); ?>></a>
        <div class="container">
            <h3>Report from <?=$sender_name->first_name?> <?=$sender_name->last_name?></h3>

            <br>
            <div id="report-content">
                <a href="" onClick="get_profile(<?=$report->user_id?>)"><img class="profile-pic medium" src=<?php echo get_profile_image(IMG_THUMB, $report->user_id); ?>></a>
                <h4><?=$receiver_name->first_name?> <?=$receiver_name->last_name?></h4>
                <br>
                <h4>Reason:</h4>
                <p style="margin-left: 15px;"><?php echo nl2br($report->content); ?></p>

                <h4>Date:</h4>
                <p> <?php echo $report->date_time ?> </p>
            </div>
        </div>

    </div>
    <style>
        .profile-pic.large {
            height: 100px;
            width: 100px;
        }

        .profile-pic.medium {
            height: 50px;
            width: 50px;
        }

        .container {
            display: inline-block;
        }

        .container,
        .profile-pic {
            vertical-align: top;
        }

        #report-content {
            padding-left: 20px;
        }

        #report-content h4 {
            display: inline-block;
        }

        h3, h4 {
            margin-top: 20px;
        }
    </style>
<?php } ?>