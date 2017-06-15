
<?php
get_header(); 
?>
		<!--CONTENT-->
		<div class="column-layout container">
			<!--Main-->
			<main class="main-column ">	

				<div class="archive-widget clearfix">
				<?php dynamic_sidebar('archives'); ?>
				</div>
				<!-- ARCHIVE BY DATE -->
				<?php						
				$q = $wp_query->query;
				//setlocale(LC_ALL, 'fra');
				$mktime = mktime(0, 0, 0, $q['monthnum'], $q['day'], $q['year']);
				$mktime_month = mktime(0, 0, 0, $q['monthnum']+1, $q['day'], $q['year']);
				$mktime_year = mktime(0, 0, 0, 12, 31, $q['year']);
				if ( is_day() ) : /* if the daily archive is loaded */ ?>
				
					<?php if(ICL_LANGUAGE_CODE=='sr'): ?>
						<h2><?php echo date_i18n('j. F Y.', $mktime); ?></h2>
					<?php else: ?>
						<h2><?php echo date_i18n('j F Y', $mktime); ?></h2>
					<?php endif;?>

				<?php elseif ( is_month() ) : /* if the montly archive is loaded */ ?>
					<h2><?php echo date_i18n('F Y', $mktime_month); ?></h2> 	
				<?php elseif ( is_year() ) : /* if the yearly archive is loaded */ ?>
					<h2><?php echo date_i18n('Y', $mktime_year); ?> </h2>
				
				<!-- ARCHIVE BY TAG -->
				<?php elseif ( is_tag() ) : ?>
					<h2><?php _e('Agenda : ', 'ifserbie'). single_tag_title();  ?> </h2> 
				
					<!--Event Post Loop-->
					<?php 								
    				// get tag slug
					$tag = get_queried_object();
    				$post_tag = $tag->slug;	
					// args TAGS
					$args_tag = array(
						'post_type' => 'post',
						'tag' => $post_tag, 
						'orderby' => 'meta_value_num',
						'meta_key' => 'if_events_startdate',
						'order' => 'DESC',
						'post__not_in' => array(1), //Post NOT to retrieve (post: no-content).
						'meta_query' => array(
    				  		array(
    				  		   'key' => 'if_events_enddate',
    				  		   'value' => strtotime('yesterday'),
    				  		   'compare' => '>',
    				  		   'type' => 'numeric'
    				  		   )
    				    )
    				);			
					// Query - FUTURE by tag
					$query_tag = new WP_Query( $args_tag );	
					if ( $query_tag->have_posts() ):
						while ( $query_tag->have_posts() ) : $query_tag->the_post();
							get_template_part('content','event');
						endwhile;
					endif; 
					wp_reset_query(); // Restore global post data stomped by the_post(). 
					?>	<!--end Event Post Loop-->

					

				<?php else : /* if anything else is loaded, ex. if the tags or categories template is missing this page will load */ ?>
					<h2><?php _e('Archives', 'ifserbie');?></h2>
				<?php endif; ?>

				<!-- ARCHIVE & ARCHIVE BY DATE -->
				<section>
					<?php if (have_posts()) : while (have_posts()) : the_post(); 
						get_post_format()==false? get_template_part('content', 'mini'): false;
					endwhile;	

        			$nav = get_the_posts_pagination( array(
        			    'mid_size'  => 2,
        			    'prev_text' => __('« Précédent', 'ifserbie'),
        			    'next_text' => __('Suivant »', 'ifserbie'),
        			    'screen_reader_text' => __( 'Navigation' )
        			) );
        			$nav = str_replace('<h2 class="screen-reader-text">Navigation</h2>', '', $nav);
					echo $nav;
    						
					endif; ?>
				</section>
	
			</main><!--.main-column-->

			<?php get_sidebar(); ?>
		
		</div><!--column-layout-->
		<div class="left-corner"></div>

<?php get_footer(); ?>