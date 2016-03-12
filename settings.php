<?php
require_once 'core/init.php';
?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        
            <!-- CONTENT STARTS HERE -->
    
    <div class="generalSettings">
    
    	<h2>General Account Settings</h2>
        
        <form>
			<label for="firstname">Name:</label>        <input class="textbox" type="text" name="firstname" size="30" placeholder="Jane"/><br>
			<label for="surname">Surname:</label>   <input class="textbox" type="text" name="surname" size="30" placeholder="Doe"/><br>
			<label for="email">Email: </label>      <input class="textbox" type="text" name="email" size="30" placeholder="janedoe@gmail.com"/><br>
            <label for="email">Password: </label>      <input class="textbox" type="text" name="oldPassword" size="30" placeholder="Old Password"/><br>
            <label for="email"> </label>      <input class="textbox" type="text" name="newPassword" size="30" placeholder="New Password"/><br>
			<br class="clear" />
            <input class="button" type="submit" value="Save Changes" />
            <br class="clear"/>
		</form>

    
    </div>
    
    <!--CONTENT HERE -->
        
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
