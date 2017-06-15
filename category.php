
<?php
get_header(); 
?>

		<!--CONTENT-->
		<div class="column-layout container">
			<!--Main-->
			<main class="main-column ">	
				<h1><?php echo single_cat_title( '' ); ?></h1>

				<div class="banner-image">
              		<?php category_image(array('size'=>'full'),true); ?>
             	</div>
				<!--Description-->
				<div class="description">
					<p><?php echo category_description($category_id); ?></p>
				</div>

				<!-- 3th level od Primery Menu -->
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
				<!-- / 3th level -->
				
				<!--Info Post Loop-->
				<section>	
				<?php 	
					$args_info = array(
					  'category__in' => array($current_cat), // Don’t show posts from child category in parents 
					  'post_type' => 'info',
					);
					$query_info = new WP_Query($args_info);
					if ($query_info->have_posts()) :
						while ($query_info->have_posts()) : $query_info->the_post(); 
							get_template_part('content', get_post_format());  
						endwhile;			
					endif;
					wp_reset_query();
				?><!--end Info Post Loop-->


				<!--Event Post Loop-->
				<?php 
					$lang = ICL_LANGUAGE_CODE; 
					$url = site_url();								
    				$cat_slug = $current_cat->slug;	// get category slug				
					
					// args FUTURE
					$args = array(
						'post_type' => 'post',
						'category__in' => array($current_cat), // Don’t show posts from child category in parents 	
						'category_name' => $cat_slug,
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
	
					// Query - FUTURE or OLD
					$query_future = new WP_Query( $args );	
					if ( $query_future->have_posts() ):
						while ( $query_future->have_posts() ) : $query_future->the_post();
							get_template_part('content','event');
						endwhile; ?>
						<!-- Archive Link-->			
						<div class="archive-link">
							<a href="<?php echo $url; ?>/<?php echo $lang; ?>/archive/?cat=<?php echo $current_cat; ?>"> 
							<?php _e( '← Archives:' , 'ifserbie' );  ?> <?php echo 	get_cat_name($current_cat) ?>
							</a>
						</div> 
					<?php 	
					endif; 
					wp_reset_query(); // Restore global post data stomped by the_post(). 
					?>
					<!--end Event Post Loop-->

					<?php // Archive Link if empty (if no posts and no category description)		
						if (! $query_future->have_posts() && ! category_description() ) : ?>
						<div class="archive-link">
							<a href="<?php echo $url; ?>/<?php echo $lang; ?>/archive/?cat=<?php echo $current_cat; ?>"> 
							<?php _e( '← Archives:' , 'ifserbie' );  ?> <?php echo 	get_cat_name($current_cat) ?>
							</a>
						</div> 
					<?php endif; 
					?>

					<!-- SIBLINGS: if category has no children - list siblings -->	
					<?php 
				    if( count( $children ) == 0 )  {
						?>
						<div class="sep2"></div>
						<div class="children-container"><ul class="child-item">	
						<?php wp_list_categories('title_li=&use_desc_for_title=0&hide_empty=0&depth=1&parent='. $parent); // siblings ?>
						</ul></div>
					<?php } ?><!-- / end siblings -->

				</section>
				 
			</main><!--.main-column-->

			<?php get_sidebar(); ?>
		
		</div><!--column-layout-->
		<div class="left-corner"></div>

<?php get_footer(); ?>