
<?php
get_header(); 
?>
		<!--CONTENT-->	
		<div class="column-layout container">
			<!--Main-->
			<main class="main-column ">	
				<h3><?php _e( 'Search for:' , 'ifserbie' );  ?> <?php the_search_query(); ?></h3>
				<?php 
				if (have_posts()) :
					while (have_posts()) : the_post(); 
						get_template_part('content','mini'); 
					endwhile;
					
					$nav = get_the_posts_pagination( array(
        			    'mid_size'  => 2,
        			    'prev_text' => __('« Précédent', 'ifserbie'),
        			    'next_text' => __('Suivant »', 'ifserbie'),
        			    'screen_reader_text' => __( 'Navigation' )
        			) );
        			$nav = str_replace('<h2 class="screen-reader-text">Navigation</h2>', '', $nav);
					echo $nav;
				
					else : ?>
						<h2><?php _e( 'No content found' , 'ifserbie' ); ?></h2> 
					<?php  	
					endif;
				 ?>
			</main><!--.main-column-->
			<?php get_sidebar(); ?>
		
		</div><!--column-layout-->
		<div class="left-corner"></div>

<?php get_footer(); ?>