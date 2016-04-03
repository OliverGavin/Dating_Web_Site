<?php
require_once 'core/init.php';

verify_login();

$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

if (isset($_POST['action']) && $_POST['action'] === 'Save Changes') {
	if (!isset($_POST['current_password']) || !check_password($_POST['current_password'])) {
		echo 'invalid password';
	} else {
		// TODO validation
		if (isset($_POST['email1']) && isset($_POST['email2']) && !empty($_POST['email1']) && !empty($_POST['email2'])) {
			if ($_POST['email1'] != $_POST['email2']) {
				echo 'emails must be the same';
			} else {
				set_email($_POST['email1']);
			}
		}

		if (isset($_POST['new_password1']) && isset($_POST['new_password2']) && !empty($_POST['new_password1']) && !empty($_POST['new_password2'])) {
			if ($_POST['new_password1'] != $_POST['new_password2']) {
				echo 'passwords must be the same';
			} else {
				set_password($_POST['new_password1']);
			}
		}
	}
}

function set_password($pass) {
	global $db;

	$user_id = $_SESSION['user_id'];
	$password = hash("sha256", $pass, false);

	$prepared = $db->prepare("
            UPDATE users
            SET password = ?
            WHERE user_id = ?
        ");

	$prepared->bind_param('si', $password, $user_id);

	if (!$prepared->execute()) {
		// error push('failed');
		echo 'err';
		return false;
	}

	return true;

}

function set_email($email) {
	global $db;

	$user_id = $_SESSION['user_id'];

	$prepared = $db->prepare("
            UPDATE users
            SET email = ?
            WHERE user_id = ?
        ");

	$prepared->bind_param('si', $email, $user_id);

	if (!$prepared->execute()) {
		// error push('failed');
		echo 'err';
		return false;
	}

	return true;

}

?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        
        <!-- CONTENT STARTS HERE -->
    
    	<div class="generalSettings">
    
    		<h2>General Account Settings</h2>
        
        	<form role="" method="post" class="style-rounded-dark" action="">

				<div class="group both-rounded">
					<label for="firstname" class="visible">First name</label>
					<input class="textbox" type="text" name="first_name" size="30" placeholder="<?=$first_name?>"/>
				</div>

				<div class="group both-rounded">
					<label for="surname" class="visible">Last name</label>
					<input class="textbox" type="text" name="last_name" size="30" placeholder="<?=$last_name?>"/>
				</div>

				<br><br>

				<div class="group both-rounded">
					<label for="email" class="visible">New email</label>
					<input class="textbox" type="email" name="email1" size="30" placeholder="janedoe@gmail.com"/>
				</div>

				<div class="group both-rounded">
					<label for="email" class="visible">Confirm email</label>
					<input class="textbox" type="email" name="email2" size="30" placeholder="janedoe@gmail.com"/>
				</div>

				<br><br>

				<div class="group both-rounded">
					<label for="email" class="visible">New Password</label>
					<input class="textbox" type="password" name="new_password1" size="30" placeholder=""/>
				</div>

				<div class="group both-rounded">
					<label for="email" class="visible">Confirm Password</label>
					<input class="textbox" type="password" name="new_password2" size="30" placeholder=""/>
				</div>

				<br><br><br>

				<div class="group both-rounded">
					<label for="email" class="visible">Current password</label>
					<input class="textbox" type="password" name="current_password" size="30" placeholder=""/>
				</div>

				<input class="button" type="submit" name="action" value="Save Changes" />
		</form>
	</div>
    
    	<!--CONTENT ENDS HERE -->
        
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
