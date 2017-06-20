
<?php
/*
Template Name: Archives
*/
get_header(); 
?>


		<!--CONTENT-->
		
		<div class="column-layout container">
			<!--Main-->
			<main class="main-column ">

				<div class="archive-widget clearfix">
				<?php dynamic_sidebar('archives'); ?>
				</div>

				<?php $cat_id = $_GET['cat'];	 ?>
				<h2><?php _e( 'Archives : ' , 'ifserbie' );  
				echo get_cat_name($cat_id); ?></h2>
		
				<section>	
				<?php // prepare data
				// find todays date
				$now = date('Ymd');
								
    			// get category slug in wordpress
    			if ( is_single() ) {
    			    $cats =  get_the_category();
    			    $cat = $cats[0];
    			} else {
    			    $cat = get_category( get_query_var( 'cat' ) );
    			}
    			$cat_slug = $cat->slug;
    			//echo $cat_slug;
							
				// args OLD
				$paged = get_query_var('paged');
				$args_old = array(
					'post_type' => 'post',
					'category_name' => $cat_slug,
					'orderby' => 'meta_value_num',
					'meta_key' => 'if_events_startdate',
					'order' => 'DSC',
					'posts_per_page' => 20, 
					'paged' => $paged,
					'post__not_in' => array(1, 1421), //Post NOT to retrieve (post: no-content).
					'meta_query' => array(
    			  		array(
    			  		   'key' => 'if_events_enddate',
    			  		   'value' => strtotime('yesterday'),
    			  		   'compare' => '<=',
    			  		   'type' => 'numeric'
    			  		   )
    			    )
    			);		
				$query_old = 	new WP_Query( $args_old );
				if ( $query_old->have_posts() ):			
					while ( $query_old->have_posts() ) : $query_old->the_post();
						get_template_part('content','mini');
					endwhile; ?>

					<div class="nav-links">
					<?php 
					echo paginate_links(array(
					'total'  => $query_old->max_num_pages,
					'mid_size'  => 2,
					)); ?>
					</div>

				<?php else :
					_e( 'No content found' , 'ifserbie' ); 
													
				endif; 
				wp_reset_query();	 // Restore global post data stomped by the_post(). 
				?>
				</section>
				
			</main><!--.main-column-->

			<?php get_sidebar(); ?>
		
		</div><!--column-layout-->
		<div class="left-corner"></div>

<?php get_footer(); ?>