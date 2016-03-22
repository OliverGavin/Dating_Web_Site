<?php

?>
<!DOCTYPE html>

<html>
<head>

    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

</head>

<?php //Use the filename e.g. index/profile/dashboard as a css class to target css at that page only ?>
<body class="<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>">
<div id="page" class="site">

    <header id="main-header" class="site-header" role="banner">
        <div class="site-wrapper">
            <div class="site-branding">
                <h1 class="site-title">
<!--                    Swoon-->
                </h1>
            </div>

            <?php
            if (is_user_logged_in()) {
                $profile_thumb_extra = '<div class="profile-image">
                                            <img class="profile-pic" src="' . get_profile_image(45) . '">
                                            <div class="profile-notification-counter">
                                                <p>2</p>
                                            </div>
                                        </div>';

                $notifications_extra = '<div class="scroll"><div style="height: 600px">Add here!</div></div>';
                $messages_extra = '<div class="scroll"><div style="height: 600px">Add here!</div></div>';

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
                        'parent'    => new MenuItem("Hello, Joe", null, "menu-default", null, $profile_thumb_extra),
                        'child'     => array(
                                            array(
                                                'parent'    => new MenuItem("Dashboard", null, null, null, null)
                                            ),
                                            array(
                                                'parent'    => new MenuItem("Notifications", null, null, null, null)
                                            ),
                                            array(
                                                'parent'    => new MenuItem("My Profile", "profile.php", null, null, null)
                                            ),
                                            array(
                                                'parent'    => new MenuItem("Search", "search.php", null, null, null)
                                            ),
                                            array(
                                                'parent'    => new MenuItem("Browse", "browse.php", null, null, null)
                                            ),
                                            array(
                                                'parent'    => new MenuItem("Suggestions", "suggestions.php", null, null, null)
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
                        <?php if (isset($menu_items)) echo create_navigation_menu_items($menu_items); ?>
<!--                        <li id="" class="menu-item menu-item-has-children current-menu-parent">-->
<!--                            <a>Hello, Joe</a>-->
<!--                            <div class="profile-image">-->
<!--                                <img class="profile-pic" src="--><?php //echo get_profile_image(45) ?><!--">-->
<!--                                <div class="profile-notification-counter">-->
<!--                                    <p>2</p>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <ul class="sub-menu">-->
<!--                                <li id="" class="menu-item">-->
<!--                                    <a href="http://127.0.0.1:8080/CS4014-Project/dashboard.php">Dashboard</a>-->
<!--                                </li>-->
<!--                                <li id="" class="menu-item menu-item-has-children">-->
<!--                                    <a href="http://127.0.0.1:8080/CS4014-Project/dashboard.php">Notifications</a>-->
<!--                                </li>-->
<!--                                <li id="" class="menu-item">-->
<!--                                    <a href="--><?php //echo ROOT; ?><!--profile.php">My Profile</a>-->
<!--                                </li>-->
<!--                                <li id="" class="menu-item current-menu-item">-->
<!--                                    <a href="http://127.0.0.1:8080/CS4014-Project/search.php">Search</a>-->
<!--                                </li>-->
<!--                                <li id="" class="menu-item current-menu-item">-->
<!--                                    <a href="http://127.0.0.1:8080/CS4014-Project/browse.php">Browse</a>-->
<!--                                </li>-->
<!--                                <li id="" class="menu-item current-menu-item">-->
<!--                                    <a href="http://127.0.0.1:8080/CS4014-Project/suggestions.php">Suggestions</a>-->
<!--                                </li>-->
<!--                                <li id="" class="menu-item">-->
<!--                                    <a href="--><?php //echo $_SERVER['PHP_SELF'] ?><!--?logout">Log out</a>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </li>-->


                    </ul>

                </div>
            </nav>
        </div>
    </header>

    <div id="content" class="site-content site-wrapper">
