<?php

?>
<!DOCTYPE html>

<html>
<head>

    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>

</head>

<?php //Use the filename e.g. index/profile/dashboard as a css class to target css at that page only ?>
<body class="<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>">
<div id="page" class="site">

    <header id="main-header" class="site-header" role="banner">
        <nav id="main-navigation" class="site-navigation" role="navigation">

        </nav>

        <div class="site-branding">
            <h1 class="site-title">

            </h1>
        </div>
    </header>

    <div id="content" class="site-content">
