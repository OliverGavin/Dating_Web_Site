<?php
require_once 'core/init.php';
require_once 'core/func/validation.php';
require_once 'core/func/notifications.php';

verify_login();

if (!user_is_at_least_role(ROLE_ADMIN)) {
	header("Location: 404.php");
}

$clear_form = false;

if (isset($_POST['action'])) {
	if ($_POST['action'] == 'Add admin') {
		$role = ROLE_ADMIN;
		$email = validate_email($_POST['email'], 'email');
		$password = validate_password($_POST['password'], 'password');
		$password2 = $_POST['password2'];

		if ($password !== $password2) {
			$_SESSION['form_errors']['password2'] = "Passwords don't match";
		}

		$first_name = validate_name($_POST['first_name'], 'first_name');
		$last_name = validate_name($_POST['last_name'], 'last_name');

		if (empty($_SESSION['form_errors'])) {
			$password = hash("sha256", $password, false);

			$prepared = $db->prepare("
					INSERT INTO users (email, password, first_name, last_name, role_id)
					VALUES (
						?,
						?,
						?,
						?,
						(SELECT role_id FROM roles WHERE name = ?)
					)
				");

			$prepared->bind_param('sssss', $email, $password, $first_name, $last_name, $role); //s - string

			if ($prepared->execute()) {
				create_notification($prepared->insert_id, "Welcome to swoon. You have been selected to be an admin.", "SYSTEM");
				$message['success'][] = "$first_name $last_name is now an admin. ";
				$clear_form = true;
			} else {
				// Error code 1062 - duplicate
				if($prepared->errno === 1062) {
					array_push($message['error'], ALREADY_EXISTS);
					$email = '';
				} else if ($prepared->errno) {
					array_push($message['error'], ERROR);
				}
			}


		}

	}
}

?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main frame" role="main">

		<div class="generalSettings">

			<h2 class="page-title">Add an admin</h2>

			<?php
			if (isset($message['error']) && !empty($message['error'])) {
				echo '<div class="notice error">';
				echo '<h6 class="notice-title">WARNING</h6>';
				foreach ($message['error'] as $msg) {
					echo '<p>';
					switch ($msg) {

						case ALREADY_EXISTS:
							echo 'A user with this email address already exists';
							break;

						default:
							echo 'Error: ' . $msg;
							break;
					}
					echo '</p>';
				}
				echo '</div>';
			}
			if (isset($message['success']) && !empty($message['success'])) {
				echo '<div class="notice success">';
				foreach ($message['success'] as $msg) {
					echo $msg;
				}
				echo '</div>';
			}
			?>

			<form method="post" action="" class="style-rounded-dark" onSubmit="">
	<!--				<input class="textbox" type="email" size="30" placeholder="Email" name="email"><br>-->
	<!--				<input class="textbox" type="password" size="30" placeholder="Password" name="password">-->

				<div class="group both-rounded <?= get_form_field_status('email'); ?>">
					<label for="email" class="visible">Email</label>
					<input type="email" id="email" name="email" size="30" value="<?php if(isset($email) && !$clear_form) echo $email?>" onblur="validate_field(this, $(this).val(), 'email', 'email')" />
				</div>
				<?php if (!$clear_form) echo get_form_field_message('email');  ?>
				<br>
				<div class="group both-rounded <?= get_form_field_status('password'); ?>">
					<label for="password" class="visible">Password</label>
					<input type="password" id="password" name="password" size="30" value="<?php if(isset($password) && !$clear_form) echo $password?>" onblur="validate_field(this, $(this).val(), 'password', 'password')" />
				</div>
				<?php if (!$clear_form) echo get_form_field_message('password');  ?>
				<br>

				<div class="group both-rounded <?= get_form_field_status('password2'); ?>">
					<label for="password2" class="visible">Confirm Password</label>
					<input type="password" id="password2" name="password2" size="30" value="<?php if(isset($password2) && !$clear_form) echo $password2?>" onblur="check_same_password(this, 'password2', $(this).val(), $('#password').val())" />
				</div>
				<?php if (!$clear_form) echo get_form_field_message('password2');  ?>
				<br>
				<div class="group both-rounded <?= get_form_field_status('first_name'); ?>">
					<label for="first_name" class="visible">First Name</label>
					<input type="text" id="first_name" name="first_name" size="30" value="<?php if(isset($first_name) && !$clear_form) echo $first_name?>" onblur="validate_field(this, $(this).val(), 'first_name', 'name')" />
				</div>
				<?php if (!$clear_form) echo get_form_field_message('first_name');  ?>
				<br>
				<div class="group both-rounded <?= get_form_field_status('last_name'); ?>">
					<label for="last_name" class="visible">Last Name</label>
					<input type="text" id="last_name" name="last_name" size="30" value="<?php if(isset($last_name) && !$clear_form) echo $last_name?>" onblur="validate_field(this, $(this).val(), 'last_name', 'name')" />
				</div>
				<?php if (!$clear_form) echo get_form_field_message('last_name');  ?>
				<br>

				<div class="action-submit">
					<input type="submit" name="action" value="Add admin">
				</div>
			</form>
			<script src="js/validation.js" type="text/javascript"></script>

		</div>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
