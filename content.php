
<article class="article-container info" id="post-<?php the_ID();?>">
	<!-- top block for mobile -->
	<div class="top-block-mobile titre">
		<div class="date-time"><?php the_field('ville'); ?></div>
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</div>
	<div class="<?php if ( has_post_thumbnail() ) { ?>mobile-thumbnail <?php } ?>">
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('small-mobile'); ?></a>
	</div>

	<!-- top block for desktop -->
	<div class="<?php if ( has_post_thumbnail() ) { ?>desktop-thumbnail <?php } ?>">
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('small'); ?></a>
	</div>
	<div class="post-container">
		<div class="top-block titre">
			<div class="date-time"><?php the_field('ville'); ?></div>
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><!-- post titre -->
		</div>

		<!-- content -->
		<p class="post-excerpt">
		<?php if ($post->post_excerpt) {
			echo get_the_excerpt(); ?>
			<br><a class="savoir" href="<?php the_permalink(); ?>">
			<?php _e( 'En savoir +' , 'ifserbie' );  ?></a>
		<?php } else {
			the_content();
		} ?>
		</p>

		<div class="post-meta"><?php /* the_category(', ')*/ the_tags('',', ') ?></div>


	</div>
</article>

