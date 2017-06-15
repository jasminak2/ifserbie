
<!--FOOTER-->
		<footer class="footer-container">
			<div class="footer-logo">
				<a href="<?php echo home_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/IFS-footer.png" alt="Institut français de Serbie"></a>
			</div>	
			<div class="footer-block">
				<?php dynamic_sidebar('footer'); ?>
			</div>
		</footer>
			
	</div><!--#wraper-->
</div><!--#background-->
<?php wp_footer(); /* this is used by many Wordpress features and plugins to work properly */ ?>
</body>
</html>


