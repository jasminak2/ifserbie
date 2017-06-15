
<?php
get_header(); 
?>

		<!--CONTENT-->
		<div class="column-layout container">
			<!--Main-->
			<main class="main-column ">	
				<h1>Agenda Serbie</h1>

				<div class="banner-image">
              		<?php category_image(array('size'=>'full'),true); ?>
             	</div>
				<!--Description-->
				<div class="description">
					<p><?php echo category_description($category_id); ?></p>
				</div>

				<!-- 3th level - Direct children -->
					<?php 
					$current_cat = intval( get_query_var('cat') );
					$this_category = get_category($cat);
					$parent = $this_category->category_parent; 
					$children = get_categories( array( 'child_of' => $current_cat, 'taxonomy' => 'category' ) );
					// If category has children - list direct children
					if( count( $children ) > 0 )  { ?>
						<div class="children-container"><ul class="child-item">	
						<?php wp_list_categories('title_li=&use_desc_for_title=0&hide_empty=0&depth=1&parent='. $current_cat); ?>
						</ul></div>
					<?php }?>
				<!-- / children -->

				<section>
				<!--Event Post Loop-->
				<?php 
    				$cat_slug = $current_cat->slug;	
    				$category_midi = get_cat_ID( 'midi' );	
    				$category_mini = get_cat_ID( 'mini' );	

					$args = array(
						'post_type' => 'post',
						 //'category__in' => array($current_cat), Don’t show posts from child category in parents 	
						'category_name' => $cat_slug,
						'category__not_in' => array($category_midi, $category_mini),
						'orderby' => 'meta_value_num',
						'meta_key' => 'if_events_startdate',
						'order' => 'ASC',
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
					$query_future = new WP_Query( $args );	
					if ( $query_future->have_posts() ):
						while ( $query_future->have_posts() ) : $query_future->the_post();
							get_template_part('content','event');
						endwhile;
					endif; 
					wp_reset_query(); // Restore global post data stomped by the_post(). 
					?><!--end Event Post Loop-->
				</section>

				<!--MIDI Event Post Loop-->				
				<?php 	    						
					$args_midi = array(
						'post_type' => 'post',			
						'cat' => $category_midi,
						'orderby' => 'meta_value_num',
						'meta_key' => 'if_events_startdate',
						'order' => 'ASC',
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
					$query_midi = new WP_Query( $args_midi );	
					if ( $query_midi->have_posts() ): ?>
					<h2>Mais aussi</h2>
					<section class="midi-container"> 
						<?php  
						while ( $query_midi->have_posts() ) : $query_midi->the_post();
							get_template_part('content','midi');
						endwhile; 
						?>
					</section>
					<?php endif; 
					wp_reset_query(); // Restore global post data stomped by the_post(). 
					?>	
				<!--end Midi Post Loop-->
				
				<!--MINI Event Post Loop-->
				<?php if (get_category($category_midi)->category_count > 0) echo '<div class="sep2"></div>' ?>
				<section>
				<?php 	    						
					$args_mini = array(
						'post_type' => 'post',			
						'cat' => $category_mini,
						'orderby' => 'meta_value_num',
						'meta_key' => 'if_events_startdate',
						'order' => 'ASC',
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
					$query_mini = new WP_Query( $args_mini );	
					if ( $query_mini->have_posts() ):
						while ( $query_mini->have_posts() ) : $query_mini->the_post();
							get_template_part('content','mini');
						endwhile;
					endif; 
					wp_reset_query(); // Restore global post data stomped by the_post(). 
					?>	
				</section><!--end Mini Post Loop-->

				<!--Archive Link-->
				<?php 
				$url = site_url();
				$lang = ICL_LANGUAGE_CODE; 
				?>
				<div class="archive-link">
					<a href="<?php echo $url; ?>/<?php echo $lang; ?>/archive/?cat=<?php echo $current_cat; ?>"> 
					<?php _e( '← Archives:' , 'ifserbie' );  ?> <?php echo get_cat_name($current_cat) ?>
					</a>
				</div> 
				 
			</main><!--.main-column-->

			<?php get_sidebar(); ?>
		
		</div><!--column-layout-->
		<div class="left-corner"></div>

<?php get_footer(); ?>