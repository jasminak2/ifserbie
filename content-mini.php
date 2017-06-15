<?php 
$start_date_field = get_post_meta($post->ID, 'if_events_startdate', true);
$start_date = date_i18n( 'j M', (int)$start_date_field);
$end_date_field = get_post_meta($post->ID, 'if_events_enddate', true);
$end_date = date_i18n( 'j M', (int)$end_date_field);
$year = date_i18n( ' Y', (int)$end_date_field);
$time = get_post_meta($post->ID, 'if_events_time', true);
$ville_field = get_post_meta($post->ID, 'ville', true);
?>

<article class="mini" id="post-<?php the_ID();?>">		
	<div class="<?php if ( has_post_thumbnail() ) { ?>mini-1  <?php } ?>"><a href="<?php the_permalink(); ?>	"><?php the_post_thumbnail('small'); ?></a></div>
		
	<div class="mini-2">
		<div class="date-time">
			<?php if ($start_date_field) { echo $start_date; }   
			if ( $end_date_field > $start_date_field ) { ?> - <?php echo $end_date; } 
			if ($start_date_field OR $end_date_field) { echo $year; }  
			if ( $time ) { ?> - <?php echo $time; } 
			if ( $ville_field ) { ?> / <?php the_field('ville'); } ?>
		</div>
		<span><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
		<div class="post-meta"><?php the_tags('',', ') ?></div>
		<div><a class="savoir" href="<?php the_permalink(); ?>">
			<?php _e( 'En savoir +' , 'ifserbie' );  ?></a></div>
	</div>
</article>
