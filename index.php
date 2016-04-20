<?php
require_once 'core/init.php';
require_once 'core/func/validation.php';

// TODO redirect to dashboard if logged in

$register = false;
$register_success = false;

if (isset($_GET['login']) && isset($_POST['action'])) {
	if ($_POST['action'] == 'Login') {
		$email = $_POST['email'];
		$password = $_POST['password'];
		login($email, $password);

	} else if ($_POST['action'] == 'Register') {
		$register = true;
		$email = validate_email($_POST['email'], 'email');
		$password = validate_password($_POST['password'], 'password');
		$password2 = $_POST['password2'];

		if ($password !== $password2) {
			$_SESSION['form_errors']['password2'] = "Passwords don't match";
		}

		$first_name = validate_name($_POST['first_name'], 'first_name');
		$last_name = validate_name($_POST['last_name'], 'last_name');

		$DOB_day = $_POST['DOB_day'];
		$DOB_month = $_POST['DOB_month'];
		$DOB_year = $_POST['DOB_year'];
		$DOB = validate_date_of_birth($DOB_day, $DOB_month, $DOB_year, 'DOB_date');

		$sex = isset($_POST['sex']) ? (int)$_POST['sex'] : 100;
		if ($sex != 0 && $sex != 1) {
			$_SESSION['form_errors']['sex'] = "Please specify your gender";
		}

		if (empty($_SESSION['form_errors'])) {
			register($email, $password, $first_name, $last_name, $DOB_day, $DOB_month, $DOB_year, $sex);
			login($email, $password);
		}

	}
}

?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        
        <!-- CONTENT STARTS HERE -->

		<div class="loginOrRegister">

			<?php
			if (isset($message['error']) && !empty($message['error'])) {
				echo '<div class="notice error">';
				echo '<h6 class="notice-title">WARNING</h6>';
				foreach ($message['error'] as $msg) {
					echo '<p>';
					switch ($msg) {
						case USER_BANNED:
							echo 'You have been temporarily banned until ' . get_ban_details()->until_date_time;
							break;

						case USER_DELETED:
							echo 'You have been permanently banned from access to the site';
							break;

						case INCORRECT_USER_PASS:
							echo 'Incorrect username or password, please try again';
							break;

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

			$redirect = "";
			if (isset($_GET['redirect'])) {
				$redirect = '&redirect='.$_GET['redirect'];
			}
			?>

			<form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?login=1' . $redirect?>" class="style-rounded-dark" onSubmit="">
<!--				<input class="textbox" type="email" size="30" placeholder="Email" name="email"><br>-->
<!--				<input class="textbox" type="password" size="30" placeholder="Password" name="password">-->

				<div class="group both-rounded <?= get_form_field_status('email'); ?>">
					<label for="email" class="visible">Email</label>
					<input type="email" id="email" name="email" size="30" value="<?php if(isset($email)) echo $email?>" onblur="validate_field(this, $(this).val(), 'email', 'email')" />
				</div>
				<?= get_form_field_message('email');  ?>
				<br>
				<div class="group both-rounded <?= get_form_field_status('password'); ?>">
					<label for="password" class="visible">Password</label>
					<input type="password" id="password" name="password" size="30" value="<?php if(isset($password)) echo $password?>" onblur="validate_field(this, $(this).val(), 'password', 'password')" />
				</div>
				<?= get_form_field_message('password');  ?>
				<br>
			<!-- SHOW / HIDE STARTS HERE -->

				<div id="registration" class="<?php if(!$register) echo 'hidden'; ?>">
					<div class="group both-rounded <?= get_form_field_status('password2'); ?>">
						<label for="password2" class="visible">Confirm Password</label>
						<input type="password" id="password2" name="password2" size="30" value="<?php if(isset($password2)) echo $password2?>" onblur="check_same_password(this, 'password2', $(this).val(), $('#password').val())" />
					</div>
					<?= get_form_field_message('password2');  ?>
					<br>
					<div class="group both-rounded <?= get_form_field_status('first_name'); ?>">
						<label for="first_name" class="visible">First Name</label>
						<input type="text" id="first_name" name="first_name" size="30" value="<?php if(isset($first_name)) echo $first_name?>" onblur="validate_field(this, $(this).val(), 'first_name', 'name')" />
					</div>
					<?= get_form_field_message('first_name');  ?>
					<br>
					<div class="group both-rounded <?= get_form_field_status('last_name'); ?>">
						<label for="last_name" class="visible">Last Name</label>
						<input type="text" id="last_name" name="last_name" size="30" value="<?php if(isset($last_name)) echo $last_name?>" onblur="validate_field(this, $(this).val(), 'last_name', 'name')" />
					</div>
					<?= get_form_field_message('last_name');  ?>
					<br>
					<div class="group both-rounded <?= get_form_field_status('DOB_date'); ?>">
						<label for="DOB_day DOB_month DOB_year" class="visible">Date of Birth</label>
						<select id="DOB_day" name="DOB_day" onchange="validate_date_fields(this)">
							<option value="-">Day</option>
							<?php
							$default = (int)(isset($DOB_day))? $DOB_day : '-';
							for($i = 1; $i <= 31; $i++) {
								$value = str_pad((string)$i, 2, "0", STR_PAD_LEFT);
								$selected = ($i == $default)? 'selected="selected"': '';
								echo "<option $selected value=\"$value\">$i</option>";
							}
							?>
						</select>
						<select id="DOB_month" name="DOB_month" onchange="validate_date_fields(this)">
							<option value="-">Month</option>
							<?php
							$months = array("January", "February", "March", "April", "May", "June", "July",
											"August", "September", "October", "November", "December");
							$default = (int)(isset($DOB_month))? $DOB_month - 1 : '-';
							for($i = 0; $i < 12; $i++) {
								$value = str_pad((string)($i+1), 2, "0", STR_PAD_LEFT);
								$selected = ($i == $default)? 'selected="selected"': '';
								echo "<option $selected value=\"$value\">$months[$i]</option>";
							}
							?>
						</select>
						<select id="DOB_year" name="DOB_year" onchange="validate_date_fields(this)">
							<option value="-">Year</option>
							<?php
							$current_year = date("Y");
							$default = (int)(isset($DOB_year))? $DOB_year : '-';
							for($i = $current_year; $i > $current_year - 100; $i--) {
								$selected = ($i == $default)? 'selected="selected"': '';
								echo "<option $selected value=\"$i\">$i</option>";
							}
							?>
						</select>
						<script>
							function validate_date_fields(el) {
								if ($('#DOB_day').val() != '-' && $('#DOB_month').val() != '-' && $('#DOB_year').val() != '-')
									validate_field(el, $('#DOB_day').val()+$('#DOB_month').val()+$('#DOB_year').val(), 'DOB_date', 'date_of_birth')
							}
						</script>
					</div>
					<?= get_form_field_message('DOB_date');  ?>

					<div class="radioGender">
						<input id="male" type="radio" name="sex" value="1" <?php if(isset($sex) && $sex == 1) echo 'checked="checked"' ?>>
						<label for="male">Male</label>
						<input id="female" type="radio" name="sex" value="0" <?php if(isset($sex) && $sex == 0) echo 'checked="checked"' ?>>
						<label for="female">Female</label>
					</div>
					<?= get_form_field_message('sex');  ?>
					<br>
				</div>

				<div class="action-submit">
					<input type="submit" name="action" value="Login">
					<input type="submit" name="action" value="Register" onclick="register()">
				</div>
			</form>
			<script src="js/validation.js" type="text/javascript"></script>
		</div>

		<script type="text/javascript">

			<?php if(!$register) { ?>
				set_validation(false);
			<?php } ?>

			function register() {
				if($('#registration').hasClass('hidden')) {
					event.preventDefault();
					$('#registration').toggleClass('hidden');
					$('input[value=Login]').remove();
					set_validation(true);
				}
			}
			$('input[name="sex"]').on('click change', function(e) {
				update_field_message(this, true, 'sex', '');
			});
		</script>
        
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
