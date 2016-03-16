<?php
require_once 'core/init.php';
?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        
        <!-- CONTENT STARTS HERE -->

		<div class="loginOrRegister">

			<?php
			if (isset($message['error'])) {
				foreach ($message['error'] as $msg) {
					echo 'Error: ' . $msg;
				}
			}
			if (isset($message['success'])) {
				foreach ($message['success'] as $msg) {
					echo $msg;
				}
			}

			$redirect = "";
			if (isset($_GET['redirect'])) {
				$redirect = '&redirect='.$_GET['redirect'];
			}
			?>

			<form action="<?php echo $_SERVER['PHP_SELF'] . '?login=1' . $redirect?>" method="post" onSubmit="">
				<input class="textbox" type="text" size="30" placeholder="Email" name="email"><br>
				<input class="textbox" type="password" size="30" placeholder="Password" name="password">

			<!-- SHOW / HIDE STARTS HERE -->

				<div class="hideRegister">
					<input class="textbox" type="password" size="30" placeholder="Confirm password" name="password2"><br>
					<input class="textbox" type="text" size="12" placeholder="First Name" name="first_name">
					<input class="textbox" type="text" size="12" placeholder="Last Name" name="last_name">
					<br>
					<div class="radioGender">
						<input id="male" type="radio" name="sex" value="1">
						<label for="male">Male</label>
						<input id="female" type="radio" name="sex" value="0">
						<label for="female">Female</label>
					</div>
					<br>
					<div class="selectLogin">
						<select id="DOB_day" name="DOB_day">
							<option value="-">Day</option>
							<?php
							for($i = 1; $i <= 31; $i++) {
								echo "<option value=\"$i\">$i</option>";
							}
							?>
						</select>
						<select id="DOB_month" name="DOB_month">
							<option value="-">Month</option>
							<?php
							$months = array("January", "February", "March", "April", "May", "June", "July",
											"August", "September", "October", "November", "December");
							for($i = 0; $i < 12; $i++) {
								echo "<option value=\"$i\">$months[$i]</option>";
							}
							?>
						</select>
						<select id="DOB_year" name="DOB_year">
							<option value="-">Year</option>
							<?php
							$current_year = date("Y");
							for($i = $current_year; $i > $current_year - 100; $i--) {
								echo "<option value=\"$i\">$i</option>";
							}
							?>
						</select>
					</div>
				</div>
				<div class="checkboxR">
					<input type="checkbox" name="check" id="check" onclick="showHide()"/>
					<label for="check">Register</label>
				</div>

				<div class="action-submit">
					<input type="submit" name="action" value="Login">
					<input type="submit" name="action" value="Register">
				</div>
			</form>
		</div>
<!-- Just put this here for now your more than welcome to move it -->
	<script type="text/javascript">
		function showHide(){
			var checkbox = document.getElementById("check");
			var hiddeninputs = document.getElementsByClassName("hideRegister");
			
			for(var i = 0; i != hiddeninputs.length; i++){
				if(checkbox.checked){
						hiddeninputs[i].style.display = "block";
				}
				else{
					hiddeninputs[i].style.display = "none";
				}
			}
		}
    	</script>
        
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
