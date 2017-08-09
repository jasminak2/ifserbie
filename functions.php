<?php

/* Enable support for Post Thumbnails on posts and pages.
* @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
*/
add_theme_support( 'post-thumbnails' );


/*
	==========================================
	 Enqueue scripts and styles
	==========================================
*/
function ifs_resources() {
		
	wp_enqueue_style('style', get_stylesheet_uri());
	wp_enqueue_script( 'jquery', get_template_directory_uri() . '/js/jquery-3.1.1.min.js', array(), '20120206', true );
	wp_enqueue_script( 'custom-script', get_template_directory_uri() . '/js/script.js', array( 'jquery'), '1.1', true );
	wp_enqueue_script('if-ajax',	get_template_directory_uri() . '/inc/calendar/ajax.js');

}
add_action('wp_enqueue_scripts', 'ifs_resources');

/*
	==========================================
	 Theme setup
	==========================================
*/
function ifs_setup() {
	
	// Navigation Menus
	register_nav_menus(array(
		'primary' => __( 'Primary Menu' ),
		'mobile'  => __( 'Mobile Menu' ),
	));

	// Add featured image support // priprema slike 720x540
	add_theme_support('post-thumbnails');
	add_image_size('banner-image', 720, 400, array('center', 'center')); 
	add_image_size('small', 230, 170, true); 
	add_image_size('small-mobile', 482, 270, true);
	add_image_size('midi-image', 230, 130, array('center', 'center'));

	// Add post format support
	add_theme_support('post-formats', array('aside', 'info', 'quote'));

	// Load up our post meta box .
	require( get_template_directory() . '/inc/events/if-events.php' );

	/* Make Institut Français available for translation.
	 * Translations can be added to the /languages/ directory.*/
	load_theme_textdomain( 'ifserbie', get_template_directory() . '/languages' );

}
add_action( 'after_setup_theme', 'ifs_setup' );




/*
	==========================================
	 Excerpt
	==========================================
*/
function custom_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

function new_excerpt_more( $more ) {
	return ' <br><a class="savoir" href="'. get_permalink( get_the_ID() ) . '">' . __('En savoir +', 'ifserbie') . '</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );

/*
	==========================================
	 Add Widget Areas
	==========================================
*/
function ifs_WidgetsInit() {
	
	register_sidebar( array(
		'name' => 'Submenu',
		'id' => 'submenu',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
	));
	
	register_sidebar( array(
		'name' => 'Main Archive',
		'id' => 'archives',
		'before_widget' => '<div class="widget-item">',
		'after_widget' => '</div>',
	));

	register_sidebar( array(
		'name' => 'Sidebar',
		'id' => 'sidebar',
		'before_widget' => '<div class="widget-item">',
		'after_widget' => '</div>',
	));
			
	register_sidebar( array(
		'name' => 'Footer Area',
		'id' => 'footer',
		'before_widget' => '<div class="widget-item">',
		'after_widget' => '</div>',
	));
	
}

add_action('widgets_init', 'ifs_WidgetsInit');



/*
	==========================================
	 My Custom Post Type
	==========================================
*/
function ifs_custom_post_type (){
	
	$labels = array(
		'name' => 'Infos',
		'singular_name' => 'Info',
		'add_new' => 'Add Item',
		'all_items' => 'All Items',
		'add_new_item' => 'Add Item',
		'edit_item' => 'Edit Item',
		'new_item' => 'New Item',
		'view_item' => 'View Item',
		'search_item' => 'Search Info',
		'not_found' => 'No items found',
		'not_found_in_trash' => 'No items found in trash',
		'parent_item_colon' => 'Parent Item'
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'has_archive' => true,
		'publicly_queryable' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			'revisions',
		),
		'taxonomies' => array('category', 'post_tag'),
		'menu_position' => 5,
		'exclude_from_search' => false
	);
	register_post_type('info',$args);
}
add_action('init','ifs_custom_post_type');

//add post-formats to post_type 'my_custom_post_type'
add_post_type_support( 'info', 'post-formats' );


/*
	==========================================
	  SEARCH RESULTS
	==========================================
*/
/* Extend the default WordPress Search (https://vzurczak.wordpress.com/2013/06/15/extend-the-default-wordpress-search/) - Include tags, tilte and content in search results*/
function my_smart_search( $search, &$wp_query ) {
    global $wpdb;
 
    if ( empty( $search ))
        return $search;
 
    $terms = $wp_query->query_vars[ 's' ];
    $exploded = explode( ' ', $terms );
    if( $exploded === FALSE || count( $exploded ) == 0 )
        $exploded = array( 0 => $terms );
         
    $search = '';
    foreach( $exploded as $tag ) {
        $search .= " AND (
            (wp_posts.post_title LIKE '%$tag%')
            OR (wp_posts.post_content LIKE '%$tag%')
            OR EXISTS
            (
                SELECT * FROM wp_terms
                INNER JOIN wp_term_taxonomy
                    ON wp_term_taxonomy.term_id = wp_terms.term_id
                INNER JOIN wp_term_relationships
                    ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id
                WHERE taxonomy = 'post_tag'
                    AND object_id = wp_posts.ID
                    AND wp_terms.name LIKE '%$tag%'
            )
        )";
    }
    return $search;
}
add_filter( 'posts_search', 'my_smart_search', 500, 2 );


/*
	==========================================
	  POSTS QUERY
	==========================================
*/ 
/**
* Alter main query to be aware of our meta field "if_events_startdate"
* order by startdate and special treatment for end date if start date allready passed
*/
function if_display_posts_listing ( $query ) {

	if( $query->is_main_query() && is_category() && !is_admin() ) {
	
	$value = mktime(23, 59, 59, date('m'), date('d')-1, date('Y')); //yesterday
	$compare = '>=';
	$compare2 = '<=';
	}
}
add_action( 'pre_get_posts', 'if_display_posts_listing' );

/**
* Alter main query for archive pages
* to be aware of our meta field "if_events_startdate"
*/
function if_display_posts_on_archive_pages( $query ) {
	//if( $query->is_main_query() && isset($query->query['year']) && !is_admin() ) {
	if( $query->is_main_query() && $query->is_archive && !is_admin() && !$query->is_category) {

		$year = $query->query['year'];
		$month = isset($query->query['monthnum']) ? $query->query['monthnum'] : null;
		$day = isset($query->query['day']) ? $query->query['day'] : null;
		
		if($query->is_year){
			$value = array(mktime(0, 0, 0, 01, 01, $year), mktime(23, 59, 59, 12, 31, $year));
			$compare = 'BETWEEN';
			
		} else if($query->is_month){
			$next_month = sprintf("%02d",$month+1);
			$value = array(mktime(0, 0, 0, $month, 01, $year), mktime(0, 0, 0, $next_month, 01, $year));
			$compare = 'BETWEEN';
			
		} else if($query->is_day){
			$value = mktime(0, 0, 0, $month, $day, $year);
			$compare = '<=';
			$compare2 = '>=';
		}
		
		$meta_query[] =
			array(
			   'key' => 'if_events_startdate',
			   'value' => $value,
			   'compare' => $compare,
			  );
		
		if($query->is_month){
			$meta_query[] =
				array(
				   'key' => 'if_events_enddate',
				   'value' => $value,
				   'compare' => $compare,
				  );
			$meta_query['relation'] = 'OR';
		}
		
		if($query->is_day){
			$meta_query[] =
				array(
				   'key' => 'if_events_enddate',
				   'value' => $value,
				   'compare' => $compare2
				  );
			$meta_query['relation'] = 'AND';
		}
		
		$query->set( 'year','' );
		$query->set( 'monthnum', '' );
		$query->set( 'day','' );
	
		// redosled prikazivanja
		$query->set( 'meta_key', 'if_events_startdate' );
		$query->set( 'orderby', 'meta_value_num' );
		$query->set( 'order', 'DESC' );
	if($query->is_year){	
		$query->set( 'order', 'ASC' );
	}
		$query->set( 'posts_per_page', '20' );	
		$query->set( 'meta_query', $meta_query );
	}
}
add_action( 'pre_get_posts', 'if_display_posts_on_archive_pages' );

//for use in php ZA KALENDAR
function get_meta_raw_if_post($pid=''){
	global $post;
	$pid = !$pid ? $post->ID : $pid;
	
	//setlocale(LC_ALL,'LANGUAGE CODE'); =>TODO get the current language code
	
	$start = get_post_meta($pid, 'if_events_startdate', false);
	$data['start'] = !empty($start[0]) ? $start[0] : null;

	$end = get_post_meta($pid, 'if_events_enddate', false);
	$data['end'] = !empty($end[0]) ? $end[0] : null;
	
	$time = get_post_meta($pid, 'if_events_time', false);
	/* $data['time'] = !empty($time[0]) && $time[0] != '00:00' ? $time[0] : null; */
	$data['time'] = !empty($time[0]) ? $time[0] : null;
	
	return $data;
}

/*
	==========================================
	  WIDGETS
	==========================================
*/
// unregister all default WP Widgets
function unregister_default_wp_widgets() {
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Pages');
}
add_action('widgets_init', 'unregister_default_wp_widgets', 1);

//remove some meta box from admin post edit page
function if_remove_meta_boxes() {
    //remove_meta_box( 'submitdiv', 'post', 'normal' ); // Publish meta box
    remove_meta_box( 'commentsdiv', 'post', 'normal' ); // Comments meta box
    remove_meta_box( 'revisionsdiv', 'post', 'normal' ); // Revisions meta box
    remove_meta_box( 'authordiv', 'post', 'normal' ); // Author meta box
    remove_meta_box( 'slugdiv', 'post', 'normal' ); // Slug meta box
    //remove_meta_box( 'tagsdiv-post_tag', 'post', 'side' ); // Post tags meta box
    //remove_meta_box( 'categorydiv', 'post', 'side' ); // Category meta box
    //remove_meta_box( 'postexcerpt', 'post', 'normal' ); // Excerpt meta box
    //remove_meta_box( 'formatdiv', 'post', 'normal' ); // Post format meta box
    remove_meta_box( 'trackbacksdiv', 'post', 'normal' ); // Trackbacks meta box
    remove_meta_box( 'postcustom', 'post', 'normal' ); // Custom fields meta box
    remove_meta_box( 'commentstatusdiv', 'post', 'normal' ); // Comment status meta box
    //remove_meta_box( 'postimagediv', 'post', 'side' ); // Featured image meta box  
    //remove_meta_box( 'pageparentdiv', 'page', 'side' ); // Page attributes meta box
}
add_action( 'admin_menu', 'if_remove_meta_boxes' );


/*
	==========================================
	 Custom Language Switcher
	==========================================
*/
//Must have installed WPML plugin (cf. http://wpml.org)
if(function_exists('icl_get_languages')) {
	function languages_list_header(){ //cf. http://wpml.org/documentation/getting-started-guide/language-setup/custom-language-switcher/
	    $languages = icl_get_languages('skip_missing=0&orderby=code');

	    if(!empty($languages)){
	        
	        echo '<div id="header_language_list"><ul>';
	        
	        foreach($languages as $l){
	        	$class = $l['active'] ? 'class="active"':'';
	            echo '<li '.$class.'>';
	            if(!$l['active']) echo '<a href="'.$l['url'].'">';
	            //echo icl_disp_language($l['native_name'], $l['translated_name']);
	            echo icl_disp_language($l['language_code'],'');
	            if(!$l['active']) echo '</a>';
	            echo '</li>';
	        }
	        
	        echo '</ul></div>';
	    }
	}
}


//get site language in short code (za calendrier.php)
function get_site_lang() {
  //to realize if the actual php version ist newer than '5.3'
  if (strnatcmp(phpversion(),'5.3.0') >= 0) {
      $lg = strstr(get_bloginfo('language'), '-', TRUE);
  }
  else {
    $haystack = get_bloginfo('language');
    $needle = '-';
    $result = substr($haystack, 0, strpos($haystack, $needle)); // $result = php
  }

  $lg = $lg ? $lg : 'fr'; //if nothing is returned best to return at least 'fr'. We're assuming that default language is french...

  return $lg;
}


/*
	==========================================
	 TinyMCE Custom Styles @url https://codex.wordpress.org/TinyMCE_Custom_Styles
	==========================================
*/
function myprefix_mce_buttons_1( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}

add_filter( 'mce_buttons_1', 'myprefix_mce_buttons_1' );
// add editor style
function myprefix_theme_add_editor_styles() {
    add_editor_style( 'editor-style.css' );
}
add_action( 'init', 'myprefix_theme_add_editor_styles' );
//add style
function myprefix_add_format_styles( $init_array ) {
	$style_formats = array(
		// Each array child is a format with it's own settings - add as many as you want
		array(
			'title'    => __( 'Titre', 'ifserbie' ), // Title for dropdown
			'block'   => 'div', // Wrap a span around the selected content
			'classes'  => 'titre', // Class name used for CSS
		),
		array(
			'title'    => __( 'Titre-gris', 'ifserbie' ), // Title for dropdown
			'block'   => 'div', // Wrap a span around the selected content
			'classes'  => 'titre-gris', // Class name used for CSS
		),
		array(
			'title'    => __( 'Sous-titre', 'ifserbie' ), // Title for dropdown
			'inline'   => 'span', // Wrap a span around the selected content
			'classes'  => 'sous-titre' // Class name used for CSS
		),
		array(
			'title'    => __( 'Float left', 'ifserbie' ), // Title for dropdown
			'block'   => 'div', // Wrap a span around the selected content
			'classes'  => 'float-left', // Class name used for CSS
		),
		array(
			'title'    => __( 'Separator-gris', 'ifserbie' ), // Title for dropdown
			'block'   => 'div', // Wrap a span around the selected content
			'classes'  => 'sep2', // Class name used for CSS
		),
		array(
			'title'    => __( 'Separator-blue', 'ifserbie' ), // Title for dropdown
			'block'   => 'div', // Wrap a span around the selected content
			'classes'  => 'sep1', // Class name used for CSS
		),
		
	);
	
	$init_array['style_formats'] = json_encode( $style_formats );
	return $init_array;
} 
add_filter( 'tiny_mce_before_init', 'myprefix_add_format_styles' );

/*
	==========================================
	 Remouve default styles for gallery
	==========================================
*/

add_filter( 'use_default_gallery_style', '__return_false' );