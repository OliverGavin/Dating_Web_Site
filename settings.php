<?php
require_once 'core/init.php';
require_once 'core/func/validation.php';

verify_login();

$valid_password = false;

$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

$changing_email = false;
$changing_password = false;

if (isset($_POST['action']) && $_POST['action'] === 'Save Changes') {
	if (!isset($_POST['current_password']) || !check_password($_POST['current_password'])) {
		$_SESSION['form_errors']['current_password'] = 'incorrect password.';
	} else $valid_password = true;

	if (isset($_POST['email1']) && isset($_POST['email2'])){
		$changing_email = true;
		if (!empty($_POST['email1'])) {
			$email1 = validate_email($_POST['email1'], 'email1');

			if (!empty($_POST['email2'])) {
				$email2 = $_POST['email2'];
				if ($email1 != $email2) {
					$_SESSION['form_errors']['email2'] = 'emails must be the same.';
				}
			}
		}
		if (!empty($_POST['email1']) && !empty($_POST['email2'])) {
			if ($valid_password && empty($_SESSION['form_errors'])) {
				if (set_email($email1)) {
					$message['success'][] = 'Your email has been changed. ';
					$changing_email = false;
				}
			}
		} else if (!empty($_POST['email1']) && empty($_POST['email2'])) {
			$_SESSION['form_errors']['email2'] = 'Both email fields required.';

		} else if (empty($_POST['email1']) && !empty($_POST['email2'])) {
			$email2 = $_POST['email2'];
			$_SESSION['form_errors']['email1'] = 'Both email fields required.';

		} else $changing_email = false;
	}

	if (isset($_POST['new_password1']) && isset($_POST['new_password2'])) {
		$changing_password = true;
		if (!empty($_POST['new_password1'])) {
			$new_password1 = validate_password($_POST['new_password1'], 'new_password1');

			if (!empty($_POST['new_password2'])) {
				$new_password2 = $_POST['new_password2'];
				if ($new_password1 != $new_password2) {
					$_SESSION['form_errors']['new_password2'] = 'passwords must be the same.';
				}
			}
		}
		if (!empty($_POST['new_password1']) && !empty($_POST['new_password2'])) {
			if ($valid_password && empty($_SESSION['form_errors'])) {
				if (set_password($new_password1)) {
					$message['success'][] = 'Your password has been changed. ';
					$changing_password = false;
				}
			}
		} else if (!empty($_POST['new_password1']) && empty($_POST['new_password2'])) {
			$_SESSION['form_errors']['new_password2'] = 'Both password fields required.';

		} else if (empty($_POST['new_password1']) && !empty($_POST['new_password2'])) {
			$new_password2 = $_POST['new_password2'];
			$_SESSION['form_errors']['new_password1'] = 'Both password fields required.';

		} else $changing_password = false;
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
    <main id="main" class="site-main frame" role="main">
    
    	<div class="generalSettings">

			<h2 class="page-title">Account Settings</h2>

			<?php
			if (isset($message['success']) && !empty($message['success'])) {
				echo '<div class="notice success">';
				foreach ($message['success'] as $msg) {
					echo $msg.'<br>';
				}
				echo '</div>';
			}
			?>
        
        	<form role="" method="post" class="style-rounded-dark" action="">

<!--				TODO -->
<!--				<div class="group both-rounded">-->
<!--					<label for="firstname" class="visible">First name</label>-->
<!--					<input class="textbox" type="text" name="first_name" size="30" placeholder="--><?//=$first_name?><!--"/>-->
<!--				</div>-->
<!---->
<!--				<div class="group both-rounded">-->
<!--					<label for="surname" class="visible">Last name</label>-->
<!--					<input class="textbox" type="text" name="last_name" size="30" placeholder="--><?//=$last_name?><!--"/>-->
<!--				</div>-->
<!---->
<!--				<br><br>-->

				<div class="group both-rounded <?php if($changing_email) echo get_form_field_status('email1'); ?>">
					<label for="email" class="visible">New email</label>
					<input class="textbox" type="email" name="email1" size="30" placeholder="janedoe@gmail.com" value="<?php if(isset($email1) && $changing_email) echo $email1?>"/>
				</div>
				<?php if($changing_email) echo get_form_field_message('email1');  ?>

				<div class="group both-rounded <?php if($changing_email && !empty($email1)) echo get_form_field_status('email2'); ?>">
					<label for="email" class="visible">Confirm email</label>
					<input class="textbox" type="email" name="email2" size="30" placeholder="janedoe@gmail.com" value="<?php if(isset($email2) && $changing_email) echo $email2?>"/>
				</div>
				<?php if($changing_email && !empty($email1)) echo get_form_field_message('email2');  ?>

				<br><br>

				<div class="group both-rounded <?php if($changing_password) echo get_form_field_status('new_password1'); ?>">
					<label for="email" class="visible">New Password</label>
					<input class="textbox" type="password" name="new_password1" size="30" value="<?php if(isset($new_password1) && $changing_password) echo $new_password1?>"/>
				</div>
				<?php if($changing_password) echo get_form_field_message('new_password1');  ?>

				<div class="group both-rounded <?php if($changing_password && !empty($new_password1)) echo get_form_field_status('new_password2'); ?>">
					<label for="email" class="visible">Confirm Password</label>
					<input class="textbox" type="password" name="new_password2" size="30" value="<?php if(isset($new_password2) && $changing_password) echo $new_password2?>"/>
				</div>
				<?php if($changing_password && !empty($new_password1)) echo get_form_field_message('new_password2');  ?>

				<br><br><br>

				<div class="group both-rounded <?= get_form_field_status('current_password'); ?>">
					<label for="email" class="visible">Current password</label>
					<input class="textbox" type="password" name="current_password" size="30" value="<?php if(isset($_POST['current_password']) && $valid_password && ($changing_email||$changing_password)) echo $_POST['current_password']?>"/>
				</div>
				<?= get_form_field_message('current_password');  ?>

				<br>

				<input class="button" type="submit" name="action" value="Save Changes" />
				<br>
				<?php if (!user_is_at_least_role(ROLE_ADMIN)) { ?>
				<br>
				<br>
				<a class="button" href="search.php?action=browse&blocked=true">Manage blocked users</a>
				<?php } ?>
				<?php if (user_is_role(ROLE_SUPER_ADMIN)) { ?>
				<br>
				<br>
				<a class="button" href="add-admin.php">Add another admin</a>
				<?php } ?>
		</form>

	</div>
        
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
