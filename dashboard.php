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
    
	<?php if($display == 2 && user_can(PERM_VIEW_PROFILES)){?><!-- FEATURED USERS -->
    	<div id="featured">
    			<div class="featuredHead">
        			<p><b>Featured Users</b></p>
        		</div>
    		<div class="pictures">
            
            	<ul><!-- Div will fit 8 profiles -->
            
            	<?php
            		
			$rUsers = get_random_profiles($_SESSION['user_id']);
			if ($rUsers) {
                		foreach ($rUsers as $rUser) {
		?>
            
          		<li>
            			<span><a href="" onClick="get_profile(<?=$rUser->user_id?>)"><img src=<?php echo get_profile_image(IMG_SMALL, $rUser->user_id); ?>></a></span>
                		<span><?php echo $rUser->first_name ?></span>
            		</li>
            	<?php
				}
			}
		?>
	    	</ul>
            
        	</div>
    	</div>
    <?php } ?>
    
     <!-- NOTIFICATIONS -->
     <?php if($display == 2){ ?>
    	<div id="notifications">
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
                		<li class="<?php if ($notification->seen) echo 'seen'; ?>" onClick="seen_notification(this, <?=$notification->notification_id?>)">
                			<span><img src=<?php echo get_profile_image(IMG_THUMB, $notification->sender_id); ?>></span>
                			<i class="fa fa-trash" onClick="delete_notification(this, <?=$notification->notification_id?>)"></i>
                			<span><?php echo truncate($notification->content, $length=65, $dots="...") ?></span>
        				</li>
                	<?php 
                			}
                		}
                	?>
			</ul>
            
        	</div>
    	</div>
    <?php } ?>

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
    
    <?php if($display == 1){ ?><!-- USER REPORTS -->
    <div id="userReports">
    	<div class="userReportsHead">
        	<p><b>User Reports</b></p>
        </div>
        
        <div class="reportPictures">
            
            <ul>
            
            	<table id="heading">
                        <tr>
    				<td class="A">Reporter</td>
    				<td class="B">Reported</td>
    				<td class="C">Reason</td>
                        	<td class="D">Time</td>
  			</tr>
		</table>
                
            	<?php
			$reports = get_report_notifications();
			if ($reports) {
                		foreach ($reports as $report) {
		?>
                <li class="<?php if ($report->seen) echo 'seen'; ?>" onClick="get_report(<?=$report->notification_id?>); seen_notification(this, <?=$report->notification_id?>);">
                	
                	<span>
                		<a href="" onClick="get_profile(<?=$report->sender_id?>)"><img src=<?php echo get_profile_image(IMG_THUMB, $report->sender_id); ?>></a>
                    		<a href="" onClick="get_profile(<?=$report->user_id?>)"><img src=<?php echo get_profile_image(IMG_THUMB, $report->user_id); ?>></a>
               		</span>
                	<i class="fa fa-trash" onClick="delete_notification(this, <?=$report->notification_id?>)"></i>
                	<span style="margin-left: 15px;"><?php echo truncate($report->content, $length=65, $dots="...") ?></span>

                    	<span style="position: relative;line-height: 51px;float:right;margin: 0 15px 0 10px;"> <?php echo $report->date_time ?> </span>
                </li>
                    
            	<?php
				}
			}
		?>
	    </ul>
            
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
				$(el).css('background-color','rgba(255, 240, 221, 0.0)');
			}
		});
	}
	
	function get_profile(id) {
    	event.preventDefault();
    	$.post('ajax/get_profile.php', {id:id}, function(data) {
    		// Callback function
    		show_modal(data, 'modal-profile');
    		});
    	}

	function get_report(id) {
    	event.preventDefault();
    	$.post('ajax/get_report.php', {id:id}, function(data) {
    		// Callback function
    		show_modal(data, "modal-dialog dialog-user-report");
    		});
    	}
	</script>
	
	<!-- REPORT MODAL FOR SHOW MORE -->

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>

