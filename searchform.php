<form role="search" method="get" id="searchform"
    class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="search-container">
        <label class="screen-reader-text" for="s"><?php _x( 'Recherche pour:', 'label' ); ?></label>
        <input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" class="type-text"/>
        <input type="submit" id="searchsubmit" value="<?php echo esc_attr_x( 'OK', 'submit button' ); ?>" />
        <input type="hidden" value="post" name="post_type" id="post_type" /> <!-- Pretrazuje samo post type : post -->
    </div>
</form>