<?php 

// PREPARE DATA - convert unix timestamp date format (from custom field) to d.m.Y
$start_date_field = get_post_meta($post->ID, 'if_events_startdate', true);
$start_date = date_i18n( 'j M', (int)$start_date_field);
$end_date_field = get_post_meta($post->ID, 'if_events_enddate', true);
$end_date = date_i18n( 'j M', (int)$end_date_field);
$time = get_post_meta($post->ID, 'if_events_time', true);
$ville_field = get_post_meta($post->ID, 'ville', true);
?>

<article class="article-container event" id="post-<?php the_ID();?>">
	<!-- Mobile -->
	<div class="top-block-mobile titre">
		<div class="date-time">
			<?php if ($start_date_field) { echo $start_date; } 
			if ( $end_date_field > $start_date_field ) { ?> - <?php echo $end_date; } 
			elseif ( $time ) { ?> - <?php echo $time; }  
			if ( $ville_field ) { ?> / <?php the_field('ville'); } ?>  
		</div><!-- date -->
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</div>
	<div class="<?php if ( has_post_thumbnail() ) { ?>mobile-thumbnail <?php } ?>">
	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('small-mobile'); ?></a>
	</div>

	<!-- Desktop -->
	<div class="<?php if ( has_post_thumbnail() ) { ?>desktop-thumbnail <?php } ?>">
	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('small'); ?></a>
	</div>
	<div class="post-container">
		<div class="top-block titre">
			<div class="date-time">
				<?php if ($start_date_field) { echo $start_date; }   
				if ( $end_date_field > $start_date_field ) { ?> - <?php echo $end_date; } 
				elseif ( $time ) { ?> - <?php echo $time; } 
				if ( $ville_field ) { ?> / <?php the_field('ville'); } ?> 	
			</div><!-- date -->
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><!-- post titre -->
		</div>

		<!-- content -->
		<p class="post-excerpt">
		<?php if ($post->post_excerpt) {
			echo get_the_excerpt(); ?>
			<br><a class="savoir" href="<?php the_permalink(); ?>">
			<?php _e( 'En savoir +' , 'ifserbie' );  ?></a>
		<?php } else {
			the_content();
		} ?>
		</p>

		<div class="post-meta"><?php the_tags('',', ') ?></div><!-- tags -->


	</div>
</article>

