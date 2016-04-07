<?php
require_once 'core/init.php';
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
            
            <ul>
          		<li><span><a href="#"><img src="profile.png"></a></span>
                <span>Jane Doe</span>
                </li>
          		<li><span><a href="#"><img src="profile.png"></a></span>
                <span>Jane Doe</span>
                </li>
          		<li><span><a href="#"><img src="profile.png"></a></span>
                <span>Jane Doe</span>
                </li>
          		<li><span><a href="#"><img src="profile.png"></a></span>
                <span>Jane Doe</span>
                </li>
                <li><span><a href="#"><img src="profile.png"></a></span>
                <span>Jane Doe</span>
                </li>
                <li><span><a href="#"><img src="profile.png"></a></span>
                <span>Jane Doe</span>
                </li>
                <li><span><a href="#"><img src="profile.png"></a></span>
                <span>Jane Doe</span>
                </li>
                <li><span><a href="#"><img src="profile.png"></a></span>
                <span>Jane Doe</span>
                </li>
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

			$query = $db->prepare("SELECT `content`, `notification_id` FROM `notifications` WHERE `user_id`=?");
	
			$query->bind_param('i', $user_id);
	
			$query->execute();
       			
			$query->bind_result($content, $notification_id);
				
			$counter = 0;
			$max = 30;
				
			/*if (countNotifications($user_id) == 0) {
                    	echo "You have no notifications.";
                	}*/
				
			while(($row = $query->fetch()) and ($counter < $max)){
			?>
                   
                    		<li onClick="seen_notification(<?=$notification_id?>)"><span><img src="profile.png"></span><!-- Get image from folder THE SENDER_ID -->
                    		<span><?php echo $content . " (" . $notification_id . ")"?></span>
                    		<i class="fa fa-trash" onClick="delete_notification(this, <?=$notification_id?>)"></i><!-- link to some script which gets the notification id sents it to the delter script and remove the notificatio from the data base -->
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
	</script>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
