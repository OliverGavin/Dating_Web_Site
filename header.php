<?php
require_once 'core/func/messaging.php';
require_once 'core/init.php';
require_once 'core/func/notifications.php';
?>
<!DOCTYPE html>

<html>
<head>

    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <script src="//use.edgefonts.net/lobster-two:n4,i4,n7,i7:all.js"></script>
    <script type="text/javascript" src="js/scroll-lock.js"></script>
    <script type="text/javascript" src="js/modal.js"></script>

</head>

<?php //Use the filename e.g. index/profile/dashboard as a css class to target css at that page only ?>
<body class="<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>">
<div id="page" class="site">

    <header id="main-header" class="site-header" role="banner">
        <div class="site-wrapper">
            <div class="site-branding">
                <h1 class="site-title">
<!--                    Swoon--><img src="images/logo.png">
                </h1>
            </div>

            <?php
            if (is_user_logged_in()) {
                $profile_thumb_extra = '<div class="profile-image">
                                            <img class="profile-pic" src="' . get_profile_image(IMG_THUMB) . '">
                                            <div class="profile-notification-counter">
                                                <p>'.get_unseen_notification_count($_SESSION['user_id']).'</p>
                                            </div>
                                        </div>';

                $notifications_extra = '<div class="scroll"><div style="height: 600px">Add here!</div></div>';

                $messages_extra = '<ul class="scroll messages" style="height: 600px">';
                    $messages = get_latest_messages();
                    if ($messages) {
                        foreach ($messages as $message) {
                            $target_user_id = ($message->sender_id == $_SESSION['user_id']) ? $message->receiver_id : $message->sender_id;

                            if (can_message_each_other($target_user_id, $_SESSION['user_id'])) {
                                $messages_extra .=   '<li id="message-'.$target_user_id.'" class="message">';
                                $messages_extra .=      '<a href="chat.php?id='.$target_user_id.'" class="message-action" onclick="open_chat('.$target_user_id.')">';
                                $messages_extra .=          '<div class="profile-image message-pic">';
                                $messages_extra .=              '<img class="profile-pic" src="'.get_profile_image(IMG_THUMB, $target_user_id).'">';
                                if ($message->seen) {
                                    $messages_extra .=          '<div class="profile-notification-counter">';
                                    $messages_extra .=          '<p>' . $message->seen . '</p>';
                                    $messages_extra .=          '</div>';
                                }
                                $messages_extra .=          '</div>';
                                $messages_extra .=          '<div class="message-text">';
                                $messages_extra .=              '<span class="message-title">'.$message->target_name.'</span>';
                                $messages_extra .=              '<span class="message-message">'.$message->content.'</span>';
                                $messages_extra .=          '</div>';
                                $messages_extra .=      '</a>';
                                $messages_extra .=  '</li>';
                            }
                        }
                    }
                $messages_extra .= '</ul>';


                // TODO permissions
                $menu_items = array(
                    array(
                        'parent'    => new MenuItem('<i class="fa fa-comments"></i>', null, "menu-messages", null, null),
                        'child'     => array(
                                            array(
                                                'parent'    => new MenuItem("Messages", null, null, null, $messages_extra)
                                            )
                        )
                    ),
                    array(
                        'parent'    => new MenuItem('<i class="fa fa-bell"></i>', null, "menu-notifications", null, null),
                        'child'     => array(
                                            array(
                                                'parent'    => new MenuItem("Notifications", null, null, null, $notifications_extra)
                                            )
                        )
                    ),
                    array(
                        'parent'    => new MenuItem("Hello, ".$_SESSION['first_name'], null, "menu-default", null, $profile_thumb_extra),
                        'child'     => array(
                                            array(
                                                'parent'    => new MenuItem("Dashboard", "dashboard.php", null, null, null)
                                            ),
                                            array(
                                                'parent'    => new MenuItem("My Profile", "profile.php", null, null, null, !user_is_at_least_role(ROLE_ADMIN))
                                            ),
                                            array(
                                                'parent'    => new MenuItem("Search", "search.php", null, null, null)
                                            ),
                                            array(
                                                'parent'    => new MenuItem("Browse", "search.php?action=browse", null, null, null)
                                            ),
                                            array(
                                                'parent'    => new MenuItem("Suggestions", "search.php?action=suggestions", null, null, null, !user_is_at_least_role(ROLE_ADMIN))
                                            ),
                                            array(
                                                'parent'    => new MenuItem("Settings", "settings.php", null, null, null)
                                            ),
                                            array(
                                                'parent'    => new MenuItem("Log out", basename($_SERVER["SCRIPT_FILENAME"])."?logout", null, null, null)
                                            )
                                        )
                    )
                );
            }


            ?>

            <nav id="main-navigation" class="site-navigation" role="navigation">
                <div class="navmenu-container">
                    <ul id="primary-menu" class="nav-menu">
                        <?php if (isset($menu_items)) create_navigation_menu_items($menu_items); ?>
                    </ul>

                </div>
            </nav>
        </div>
    </header>

    <div id="content" class="site-content site-wrapper">
