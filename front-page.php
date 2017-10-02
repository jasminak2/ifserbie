

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
					<?php the_post_thumbnail('banner-image'); ?>
				</div>

				<!-- Teasers loop -->
				<div class="teaser-container" id="post-<?php the_ID()?>">
				<?php 
				$lang = ICL_LANGUAGE_CODE; 
					if($lang=='fr'){
					$query_teasers = new WP_Query('category_name=teasers&post_type=info');
					}
					if($lang=='sr'){
					$query_teasers = new WP_Query('category_name=teasers-sr&post_type=info');
					}
					
					if ($query_teasers->have_posts()) :
						while ($query_teasers->have_posts()) : $query_teasers->the_post();?>
						<div class="teaser">		
							<a href="<?php echo get_the_content() ?>">
							<div class="titre"><?php the_title(); ?></div>
							<?php the_post_thumbnail('small'); ?>
							</a>
							</div>
						<?php endwhile;				
					endif;
					wp_reset_query();
				?>	
				</div>

				<!-- Page content -->
				<?php the_content();?>				
				<br>				 
				<!-- Home post loop -->
				<?php 
					if($lang=='fr'){
					$args_home = array(
						'category_name' => 'accueil', 
						'post_type' => 'any',
					 	);
					 }
					if($lang=='sr'){
						$args_home = array(
						'category_name' => 'pocetna', 
						'post_type' => 'any',
					 	); 
					 }
									
				$homePosts = new WP_Query($args_home);
				if ($homePosts -> have_posts()) :
					while ($homePosts ->have_posts()) : $homePosts -> the_post(); 
						get_template_part('content', get_post_format());
					endwhile;						
					endif;
					wp_reset_query();
				?>	
				
			</main><!--.main-column-->

			<?php get_sidebar(); ?>
		
		</div><!--column-layout-->
		<div class="left-corner"></div>

<?php get_footer(); ?>