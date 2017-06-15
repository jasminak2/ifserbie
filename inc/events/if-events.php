<?php
/* ------------------- THEME FORCE ---------------------- */

/*
 * EVENTS FUNCTION - GPL & all that good stuff obviously...
 *
 *  BY http://www.noeltock.com/web-design/wordpress/custom-post-types-events-pt1/
 */


// 0. Base

add_action('admin_init', 'if_functions_css');
function if_functions_css() {
	wp_enqueue_style('if-functions-css', get_bloginfo('template_directory') . '/inc/events/css/if-functions.css');
}


// 4. Show Meta-Box

add_action( 'admin_init', 'if_events_create' );
function if_events_create() {
    add_meta_box('if_events_meta', __('Calendar information','ifserbie'), 'if_events_meta', 'post');
   
}

function if_events_meta () {

    // - grab data -

    global $post;
    $custom = get_post_custom($post->ID);
    
    $meta_sd = !empty($custom["if_events_startdate"][0]) ? $custom["if_events_startdate"][0] : NULL;
    $meta_ed = !empty($custom["if_events_enddate"][0]) ? $custom["if_events_enddate"][0] : NULL;
    $meta_time = !empty($custom["if_events_time"][0]) ? $custom["if_events_time"][0] : NULL;
    // - grab wp time format -

    $date_format = get_option('date_format'); // Not required in my code
    $time_format = get_option('time_format');

    // - populate today if empty, 00:00 for time -

    if ($meta_sd == null) { $meta_sd = time(); $meta_ed = $meta_sd; $meta_time = '';}

    // - convert to pretty formats -

    $clean_sd = date("D, M d, Y", $meta_sd);
    $clean_ed = !$meta_ed ? '' : date("D, M d, Y", $meta_ed);
    //$clean_st = date($time_format, $meta_st);


    // - output -

    ?>
    <div class="if-meta clearfix">
        <ul>
            <li><label><?php _e("Start Date",'ifserbie');?>&nbsp;<span style="color:red" title="<?php __("Mandatory field", 'ifserbie');?>">*</span></label><input type="text" name="if_events_startdate" id="if_events_startdate" class="ifdate" value="<?php echo $clean_sd; ?>" /></li>
            <li><label><?php _e("End Date",'ifserbie');?>&nbsp;<span style="color:red" title="<?php __("Mandatory field", 'ifserbie');?>">*</span></label><input type="text" name="if_events_enddate" id="if_events_enddate" class="ifdate" value="<?php echo $clean_ed; ?>" /></li>
            <li><label><?php _e("Time",'ifserbie');?></label><input type="text" name="if_events_time" id="if_events_time" value="<?php echo $meta_time; ?>" /><?php _e("<em>Use 24h format (7pm = 19:00). Use the \"Time field\" only if \"End date\" is equal to \"Start date\"</em>",'ifserbie');?></li>
        </ul>
    </div>
    <?php
}


// 5. Save Data

add_action ('save_post', 'save_if_events');
function save_if_events() {

    global $post;
    //$meta_dis = get_post_meta($post->ID, 'if_events_disciplines');
    
    if ( !current_user_can( 'edit_posts' ) )
    	return $post->ID;

    // - convert back to unix & update post

    if(isset($_POST["if_events_startdate"])):
     $updatestartd = strtotime ( $_POST["if_events_startdate"] );
     update_post_meta($post->ID, "if_events_startdate", $updatestartd );
    endif;

    if(isset($_POST["if_events_enddate"])):
        $updateendd = strtotime ( $_POST["if_events_enddate"]);
        
        if($updateendd < $updatestartd) $updateendd = $updatestartd;
        
        update_post_meta($post->ID, "if_events_enddate", $updateendd );
    endif;

    if(isset($_POST["if_events_time"])):
        $updatetime = $_POST["if_events_time"];
        update_post_meta($post->ID, "if_events_time", $updatetime );
    endif;
    
    

}


// 7. JS Datepicker UI

function events_styles() {
    global $post_type;
    if( 'post' != $post_type )
        return;
    wp_enqueue_style('ui-datepicker', get_bloginfo('template_url') . '/inc/events/css/jquery-ui-1.8.9.custom.css');
}

function events_scripts() {
    global $post_type;
    if( 'post' != $post_type )
    return;
    //wp_enqueue_script('jquery-ui', get_bloginfo('template_url') . '/inc/events/js/jquery-ui-1.8.9.custom.min.js', array('jquery'));

    wp_enqueue_script('ui-datepicker', get_bloginfo('template_url') . '/inc/events/js/jquery.ui.datepicker.js', array('jquery'));
		
		wp_enqueue_script('jquery-validate', get_bloginfo('template_url') . '/inc/events/js/jquery.validate.min.js', array('jquery'), '1.8.1',true);
    
    wp_enqueue_script('custom_script', get_bloginfo('template_url').'/inc/events/js/ifevents-admin.js', array('jquery-validate'));
    wp_localize_script( 'custom_script', 'objectL10n', array());
}

add_action( 'admin_print_styles-post.php', 'events_styles', 1000 );
add_action( 'admin_print_styles-post-new.php', 'events_styles', 1000 );

add_action( 'admin_print_scripts-post.php', 'events_scripts', 1000 );
add_action( 'admin_print_scripts-post-new.php', 'events_scripts', 1000 );


?>
