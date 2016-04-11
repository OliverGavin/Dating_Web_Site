<?php
require_once 'core/init.php';
require_once 'core/func/profiles.php';
require_once 'core/func/notifications.php';

verify_login();
?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

    <!-- CONTENT STARTS HERE -->
    
    <div id="featured">
    	<div class="featuredHead">
        	<p><b>Featured Users</b></p>
        </div>
    	<div class="pictures">
            
            <ul><!-- Div will fit 8 profiles -->
            
            	<?php
            		
			global $db;
				
			$user_id = $_SESSION['user_id'];

			$query = $db->prepare("SELECT `user_id`, `first_name` FROM `users` ORDER BY RAND() LIMIT 8");
	
			$query->execute();
       			
			$query->bind_result($user_id, $firstname);
					
			while($row = $query->fetch()){
		?>
          	<li><span><a href="" onClick="get_profile(<?=$user_id?>)"><img src=<?php echo get_profile_image(300, $user_id); ?>></a></span>
                <span><?php echo $firstname ?></span>
                </li>
                <?php
			}
		?>
	    </ul>
            
        </div>
    </div>
    
    <div id="notifications">
    	<div class="notificationHead">
        	<p><b>Notifications</b></p>
        </div>
    	<div class="notePictures">
            
            	<ul>
            	<?php
			global $db;
				
			$user_id = $_SESSION['user_id'];

			$query = $db->prepare("SELECT `content`, `notification_id`, `sender_id`, `seen` FROM `notifications` WHERE `user_id`=?");
	
			$query->bind_param('i', $user_id);
	
			if(!$query->execute()){
				echo "You have no notifications.";
			}
       			
			$query->bind_result($content, $notification_id, $sender_id, $seen);
				
			$counter = 0;
			$max = 30;
				
			while(($row = $query->fetch()) and ($counter < $max)){
		?>
                   <!--  TODO change css -->
                <li onClick="seen_notification(<?=$notification_id?>)"><span><img src=<?php echo get_profile_image(45, $sender_id); ?>></span>
                <i class="fa fa-trash" onClick="delete_notification(this, <?=$notification_id?>)"></i>
                <span><?php echo $content ?></span>
                </li>
                    
                <?php
			$counter++;
			}
		?>
		</ul>
            
        </div>
    </div>
    
    <div id="pOverview">
    	<div class="pOverviewLink">
        	<i class="fa fa-user fa-2x"></i>
            <p><b>Your Profile</b></p>
        </div>
    
    	<img src="profile.png">
        <p>Jane Doe</p>
        <p>22, Single</p>
            
    </div>
    
    <div id="recentlyViewedYou">
    	<div class="recentlyViewedYouHead">
        	<p><b>Recently Viewed You</b></p>
        </div>
    
    </div>
    <div id="clearDash"></div>
    
    <!--CONTENT HERE -->

	<script type="text/javascript">
	function delete_notification(el, notification_id) {
		event.preventDefault();
		$.post( "ajax/delete_notification.php", {notification_id:notification_id}, function( data ) {
		  if (data == 'success') {
    			$(el).parent().remove();
			}
		});
	}
	function seen_notification(notification_id){
		event.preventDefault();
		$.post("ajax/set_notification.php", {notification_id:notification_id}, function( data ) {
			if (data == 'success') {
				//someting or other
			}
		});
	}
	
	function get_profile(id) {
    	event.preventDefault()
    	$.post('ajax/get_profile.php', {id:id}, function(data) {
    		// Callback function
    		show_modal(data);
    		});
    	}
	</script>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
