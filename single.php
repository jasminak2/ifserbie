
<?php
get_header(); 
?>
		

		<!--CONTENT-->
		
		<div class="column-layout container">
			<!--Main-->
			<main class="main-column ">	

				<!--Post-->
				<section>

				<?php 
				// PREPARE DATA - convert unix timestamp date format (from custom field) to d.m.Y
				$start_date_field = get_post_meta($post->ID, 'if_events_startdate', true);
				$start_date = date_i18n( 'j M', (int)$start_date_field );
				$year = date_i18n( ' Y', (int)$start_date_field);
				$end_date_field = get_post_meta($post->ID, 'if_events_enddate', true);
				$end_date = date_i18n( 'j M Y', (int)$end_date_field );
				$time = get_post_meta($post->ID, 'if_events_time', true);
				$ville_field = get_post_meta($post->ID, 'ville', true);
				
				if (have_posts()) :
					while (have_posts()) : the_post(); ?>
					<div>
						<div class="titre-gris">
							<?php 
							if ( $end_date_field > $start_date_field ) {
								echo $start_date; ?> - <?php echo $end_date;
							}
							else {
								echo $start_date;?>  <?php echo $year;
								if ( $time ) { ?> - <?php echo $time; }
							} ?>

							<?php if ( $ville_field ) { ?> / <?php the_field('ville'); } ?>  
						</div><!-- date -->

						<h2><?php the_title(); ?></h2> 
						<div class="banner-image"><?php the_post_thumbnail('banner-image'); ?></div>
						
						<?php the_content(); ?>
						
						<div class="post-meta"><?php the_tags('',', ') ?></div><!-- tags -->
					</div>				
					
				<?php 
					endwhile;				
					else :
						_e( 'No content found' , 'ifserbie' ); 				
				endif;
				?>		
				</section>

				<!--INFO BOX-->	
				<?php 
				$lieu = get_post_meta($post->ID, 'lieu', true);
				$adresse = get_post_meta($post->ID, 'adresse', true);
				$info_box = get_post_meta($post->ID, 'info_box', true);
					if ( $lieu OR $adresse OR $info_box ) : 
						?>
						<div class="info-box">
							<h3><span class="info-pict"></span><?php _e( 'Info' , 'ifserbie' ); ?></h3>
							<div class="info-container box-shadow">
								<h4><?php the_field('lieu') ?></h4>
								<span><?php the_field('adresse') ?></span>
								<span><?php the_field('info_box') ?></span>
							</div>
						</div> 
						<?php 	
					endif; 
					?>
				<!-- END Info Box-->
				
			</main><!--.main-column-->

			<?php get_sidebar(); ?>
		
		</div><!--column-layout-->
		<div class="left-corner"></div>

<?php get_footer(); ?>