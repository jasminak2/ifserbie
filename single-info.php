
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
				if (have_posts()) :
					while (have_posts()) : the_post(); ?>
					<div>						
						<h2><?php the_title(); ?></h2> <!-- post titre -->
						<div class="banner-image"><?php the_post_thumbnail('banner-image'); ?></div><!-- /	post-thumbnail -->
						<?php the_content(); ?><!-- texte -->
						<div class="post-meta"><?php the_tags('',', ') ?></div><!-- tags -->
					</div>						
				<?php 
					endwhile;				
					else :
						_e( 'No content found' , 'ifserbie' ); 				
				endif;
				?>		
				</section>
			</main><!--.main-column-->

			<?php get_sidebar(); ?>
		
		</div><!--column-layout-->
		<div class="left-corner"></div>

<?php get_footer(); ?>