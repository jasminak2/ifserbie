<article class="article-container aside" id="post-<?php the_ID();?>">
	<!-- mobile -->
	<div class="top-block-mobile titre-gris">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</div>
	<div class="<?php if ( has_post_thumbnail() ) { ?>mobile-thumbnail <?php } ?>">
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('small-mobile'); ?></a>
	</div>
	<!-- desktop -->
	<div class="<?php if ( has_post_thumbnail() ) { ?>desktop-thumbnail <?php } ?>">
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('small'); ?></a>
	</div>
	<div class="post-container">
		<div class="top-block titre-gris">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><!-- post titre -->
		</div>

		<!-- content -->
		<p class="post-excerpt">
		<?php if ($post->post_excerpt) { ?>
			<?php echo get_the_excerpt(); ?>
			<br><a class="savoir" href="<?php the_permalink(); ?>">
			<?php _e( 'En savoir +' , 'ifserbie' );  ?></a>
		<?php } else {
			the_content();
		} ?>
		</p>

	</div>
</article>


