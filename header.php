<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title><?php bloginfo('name'); ?></title>
	<meta name="keywords" content="institut français, Francuski institut, Belgrade, Serbie, Beograd, Srbija, centre culturel français, francuski kulturni centar, culture, kultura, coopération culturelle, évènements, dogadjaju, desavanja, evenements, francais, langue, francuski jezik, IF, IFS, ucenje francuskog, langue française, cours de langue, casovi francuskog, časovi, stipendije">
	<meta name="description" content="<?php if ( is_single() ) {
        single_post_title('', true); 
    } else {
        bloginfo('name'); echo " - "; bloginfo('description');
    }
    ?>" />
	<?php wp_head(); ?>
	

</head>

<body <?php body_class(); ?>>
<div id="background">
	<div id="wraper">

		<!--HEADER-->
		<header class="header">
			<div class="header-logo container">
				<a href="<?php echo home_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/IFS.png" alt="Institut français de Serbie"></a>
			</div>
			
			<div class="header-widget">
				<?php languages_list_header(); /* outputs the language switcher */ ?>
				<?php get_search_form() ?>

			</div>
			
			
		</header>
		<div class="right-corner"></div>


		<!--MAIN NAVIGATION-->
		<div class="main-nav clearfix" role="navigation">
			<?php
				wp_nav_menu( array(
				    'theme_location' => 'primary',
				    'depth' => 2,
				    'container' => false,
				    )
				);
			?>
		</div><!-- / main navigation -->

		<!-- 2th level od Primery Menu - Navigation Submenu WIDGET AREA -->
		<div class="sub-navigation clearfix">
			<?php dynamic_sidebar('submenu'); ?>
		</div>
		
		<!-- / widget area -->

		<!--MOBILE NAVIGATION-->
		<div class="mobile-nav clearfix" role="navigation">
		<a href="#"><span class="menu-trigger"><?php _e( 'Menu', 'ifserbie' );  ?></span></a>
			<?php
				wp_nav_menu( array(
				    'theme_location' => 'mobile',
				    'depth' => 2,
				    'container' => false,
				    )
				);
			?>
		</div><!-- / mobile navigation -->


		<div class="breadcrumbs" typeof="BreadcrumbList" vocab="http://schema.org/">
		    <?php if(function_exists('bcn_display'))
		    {
		        bcn_display();
		    }?>
		</div>


		
