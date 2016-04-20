<?php
require_once 'core/init.php';
require_once 'core/func/profiles.php';
require_once 'core/func/notifications.php';

verify_login();

if (user_is_at_least_role(ROLE_ADMIN)) {
	$display = 1;
} 
else{ 
	$display = 2;
}

?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

    <!-- CONTENT STARTS HERE -->
    
	<?php if($display == 2){?><!-- FEATURED USERS -->
    <div id="featured">
    	<div class="featuredHead">
        	<p><b>Featured Users</b></p>
        </div>
    	<div class="pictures">
            
            <ul><!-- Div will fit 8 profiles -->
            
            	<?php
            		
			global $db;

			$user_id = $_SESSION['user_id'];

			$query = $db->prepare("SELECT `user_id`, `first_name` FROM `users` WHERE `user_id`<>? ORDER BY RAND() LIMIT 8");
	
			$query->bind_param('i', $user_id);
	
			$query->execute();
       			
			$query->bind_result($featured_user_id, $firstname);
					
			while($row = $query->fetch()){
		?>
          	<li><span><a href="" onClick="get_profile(<?=$featured_user_id?>)"><img src=<?php echo get_profile_image(IMG_SMALL, $featured_user_id); ?>></a></span>
                <span><?php echo $firstname ?></span>
                </li>
                <?php
			}
		?>
	    </ul>
            
        </div>
    </div>
    <?php } ?>
    
     <!-- NOTIFICATIONS -->
    <div id="notifications<?php if($display == 1){echo 'admin';} ?>">
    	<div class="notificationHead">
        	<p><b>Notifications</b></p>
        </div>
    	<div class="notePictures">
            
            	<ul>
            	<?php
					$notifications = get_notifications($_SESSION['user_id']);
					if ($notifications) {
                		foreach ($notifications as $notification) {
				?>
                <li class="<?php if ($notification->seen) echo 'seen'; ?>" onClick="seen_notification(this, <?=$notification->notification_id?>)"><span><img src=<?php echo get_profile_image(IMG_THUMB, $notification->sender_id); ?>></span>
                <i class="fa fa-trash" onClick="delete_notification(this, <?=$notification->notification_id?>)"></i>
                <span><?php echo $notification->content ?></span>
                </li>
                    
            <?php
				}
				}
			?>
		</ul>
            
        </div>
    </div>
    <?php ?>

	<?php $profile = get_profile($_SESSION['user_id'])?>
    <?php if($display == 2 && $profile){ ?><!-- PROFILE OVERVIEW -->
    <div id="pOverview">
    	<div class="pOverviewLink">
        	<i class="fa fa-user fa-2x"></i>
            <p><b>Your Profile</b></p>
        </div>
    	<a href="" onClick="get_profile(<?=$_SESSION['user_id']?>)"><img src=<?php echo get_profile_image(IMG_MEDIUM, $_SESSION['user_id']); ?>></a>
        <p><?=$_SESSION['first_name']?> <?=$_SESSION['last_name']?></p>
        <p><?php echo $profile->age; ?></p>
            
    </div>
    <?php } ?>
    
    <?php if($display == 2){ ?><!-- VIEWED YOU -->
    <div id="recentlyViewedYou">
    	<div class="recentlyViewedYouHead">
        	<p><b>Recently Viewed You</b></p>
        </div>
    
    </div>
    <?php } ?>
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
	function seen_notification(el,notification_id){
		event.preventDefault();
		$.post("ajax/set_notification.php", {notification_id:notification_id}, function( data ) {
			if (data == 'success') {
				//CSS
				$(el).css('background-color','#FFFFFF');
			}
		});
	}
	
	function get_profile(id) {
    	event.preventDefault()
    	$.post('ajax/get_profile.php', {id:id}, function(data) {
    		// Callback function
    		show_modal(data, 'modal-profile');
    		});
    	}
	</script>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
