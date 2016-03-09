<?php
require_once 'core/init.php';
?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        
        <!-- CONTENT STARTS HERE -->
    
        <div class="loginOrRegister">
    	
        	<form method="post" action="" onSubmit="">
  			<input class="textbox" type="text" size="30" placeholder="Email" name="email"><br>
  			<input class="textbox" type="text" size="30" placeholder="Password" name="password">
  		</form>
        
        	<!-- SHOW / HIDE STARTS HERE -->
          	<form method="post" action="" onSubmit="">
            		<div class="checkboxR">
        			<input type="checkbox" name="check" id="check" onclick="showHide()"/>
              			<label for="check">Register</label>
            		</div>
            
            		<br> 
        
        	  	<div class="hideRegister">
        			<input class="textbox" type="text" size="12" placeholder="First Name" name="fname">
  				<input class="textbox" type="text" size="12" placeholder="Last Name" name="lName">
                		<br>
                		<div class="radioGender">
					<input id="male" type="radio" name="gender" value="male">
					<label for="male">Male</label>
					<input id="female" type="radio" name="gender" value="female">
					<label for="female">Female</label>
				</div>
                		<br>
                		<div class="selectLogin">
                 			<select id="day" name="day">
					       `<option value="-">Day</option>
					        <option value="1">1</option>
					        <option value="2">2</option>
					        <option value="3">3</option>
					        <option value="4">4</option>
				        </select>
                			<select id="month" name="month">
					        <option value="-">Month</option>
					        <option value="1">January</option>
					        <option value="2">Febuary</option>
					        <option value="3">March</option>
				        </select>
				        <select id="year" name="year">
					        <option value="-">Year</option>
					        <option value="2011">2011</option>
					        <option value="2010">2010</option>
					        <option value="2009">2009</option>
					        <option value="2008">2008</option>
					        <option value="2007">2007</option>
					        <option value="2006">2006</option>
				        </select>
              			</div>
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
