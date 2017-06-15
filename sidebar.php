

<!--SIDEBAR-->
<div class="separator"></div>
<div class="sidebar">	
	<div class="aside-container">
		
		<a href="http://institutfrancais.us6.list-manage.com/subscribe?u=4aa1733bbad1171af162c1b8f&id=fa8a978541" target="_blanc">
		<div class="newsletter widget-item clearfix">
			<p class="newletter-titre"><?php _e( "Lettre d'information" , "ifserbie" ); ?></p>
			<img  class="newletter-go" src="<?php echo get_template_directory_uri(); ?>/images/fleche-nl.png">
		</div>
		</a>

		<!-- CALENDAR -->
		<div id="ajax_calendrier" class="box-shadow"><?php  include( get_template_directory() . '/inc/calendar/calendrier.php'); ?>
		</div>
		
		<?php dynamic_sidebar('sidebar'); ?>

		
	</div>
</div>

