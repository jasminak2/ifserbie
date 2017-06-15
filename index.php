
<?php
get_header(); 
?>
		<!--CONTENT-->
		<div class="column-layout container">
			<!--Main-->
			<main class="main-column ">	
				<h1><?php the_title(); ?></h1>

				<!-- post-thumbnail -->
				<div class="banner-image">
					<?php the_post_thumbnail('full'); ?>
				</div>
				<!-- /post-thumbnail -->
				
				<?php 
				if (have_posts()) :
					while (have_posts()) : the_post(); ?>
					<?php the_content();				
					endwhile;				
					else :
						_e( 'No content found' , 'ifserbie' ); 				
					endif;
				?>
				 
				<!-- 3th level od Primery Menu - Children Navigation - WIDGET AREA -->
					<?php dynamic_sidebar('children'); ?>
				<!-- / widget area -->
				
			</main><!--.main-column-->

			<?php get_sidebar(); ?>
		
		</div><!--column-layout-->
		<div class="left-corner"></div>

<?php get_footer(); ?>