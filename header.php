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
<!--                    Cloud9-->
                </h1>
            </div>
            <nav id="main-navigation" class="site-navigation" role="navigation">
                <div class="navmenu-container">
                    <ul id="primary-menu" class="nav-menu">
<!--                        current-menu-parent-->
<!--                        menu-item-has-children-->
<!--                        current-menu-item-->
<!--                        menu-item-->

                        <li id="" class="menu-item menu-item-has-children current-menu-parent">
                            <a>Hello, Joe</a>
                            <div class="profile-image">
                                <img class="profile-pic" src="http://offline.fcwinti.com/wp-content/uploads/default-avatar-500x550.jpg">
                                <div class="profile-notification-counter">
                                    <p>2</p>
                                </div>
                            </div>
                            <ul class="sub-menu">
                                <li id="" class="menu-item">
                                    <a href="http://127.0.0.1:8080/CS4014-Project/dashboard.php">Dashboard</a>
                                </li>
                                <li id="" class="menu-item menu-item-has-children">
                                    <a href="http://127.0.0.1:8080/CS4014-Project/dashboard.php">Notifications</a>
                                    <ul class="sub-menu">
                                        <li id="" class="menu-item">
                                            <a href="http://127.0.0.1:8080/CS4014-Project/notifications.php">test</a>
                                        </li>
                                        <li id="" class="menu-item current-menu-item">
                                            <a href="http://127.0.0.1:8080/CS4014-Project/search.php">test</a>
                                        </li>
                                    </ul>
                                </li>
                                <li id="" class="menu-item">
                                    <a href="http://127.0.0.1:8080/CS4014-Project/profile.php">My Profile</a>
                                </li>
                                <li id="" class="menu-item current-menu-item">
                                    <a href="http://127.0.0.1:8080/CS4014-Project/search.php">Search</a>
                                </li>
                                <li id="" class="menu-item current-menu-item">
                                    <a href="http://127.0.0.1:8080/CS4014-Project/browse.php">Browse</a>
                                </li>
                                <li id="" class="menu-item current-menu-item">
                                    <a href="http://127.0.0.1:8080/CS4014-Project/suggestions.php">Suggestions</a>
                                </li>
                                <li id="" class="menu-item">
                                    <a href="http://127.0.0.1:8080/CS4014-Project/logout.php">Log out</a>
                                </li>
                            </ul>
                        </li>


                    </ul>

                </div>
            </nav>
        </div>
    </header>

    <div id="content" class="site-content site-wrapper">
