<?php 

// PREPARE DATA - convert unix timestamp date format (from custom field) to d.m.Y
$start_date_field = get_post_meta($post->ID, 'if_events_startdate', true);
$start_date = date_i18n( 'j M', (int)$start_date_field);
$end_date_field = get_post_meta($post->ID, 'if_events_enddate', true);
$end_date = date_i18n( 'j M', (int)$end_date_field);
$time = get_post_meta($post->ID, 'if_events_time', true);
$ville_field = get_post_meta($post->ID, 'ville', true);
?>
<div class="midi" id="post-<?php the_ID();?>">
	<div class="titre date-time">
		<?php the_tags('',', ') ?> 
	</div>
	<div><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('midi-image'); ?></a></div>
	<div class="date-time">
	<?php if ($start_date_field) { echo $start_date; } 
		if ( $end_date_field > $start_date_field ) { ?> - <?php echo $end_date; } 
		elseif ( $time ) { ?> - <?php echo $time; }  
		?>  
	</div>
	<div  class="midi-titre"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
	<div class="post-meta"><?php if ( $ville_field ) { ?><?php the_field('ville'); } ?> </div>
</div>
