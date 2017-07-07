<article class="article-container aside" id="post-<?php the_ID();?>">
	<!-- mobile -->
	
	<div class="<?php if ( has_post_thumbnail() ) { ?>mobile-thumbnail <?php } ?>">
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('small-mobile'); ?></a>
	</div>
	<!-- desktop -->
	<div class="<?php if ( has_post_thumbnail() ) { ?>desktop-thumbnail <?php } ?>">
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('small'); ?></a>
	</div>
	<div class="post-container">
		
		<!-- content -->
		<div class="post-excerpt">
		<?php if ($post->post_excerpt) { ?>
			<div class="titre-gris">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</div>
			<p>
			<?php echo get_the_excerpt(); ?>
			<br><a class="savoir" href="<?php the_permalink(); ?>">
			<?php _e( 'En savoir +' , 'ifserbie' );  ?></a>
			</p>
		<?php } else {
			the_content();
		} ?>
		</div>

	</div>
</article>



