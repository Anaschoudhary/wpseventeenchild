<?php

add_action('init', 'movies_custom_post_type');
add_action('add_meta_boxes', 'movies_rating_meta_box');
add_action('save_post', 'save_rating_data');

function movies_custom_post_type(){

$lables = array(
    'name' => 'Movies',
    'singular_name' => 'Movies',
    'menu_name' => 'Movie',
    'name_admin_bar' => 'Movies'
);

$args = array(
    'labels' => $lables,
    'show_ui' => true,
    'show_in_menu' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 26,
    'public' => true,
    'menu_icon' => 'dashicons-format-video',
    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
    'taxonomies' => array( 'category' )
);
register_post_type('movies', $args);
}

function movies_rating_meta_box(){
    add_meta_box('movie_rating', 'Rating', 'movie_rating_cb', 'movies', 'side', 'default');
}

function movie_rating_cb( $post ){
    wp_nonce_field('save_rating_data', 'movie_button_nonce');

    $value = get_post_meta($post->ID, '_movie_rating_value_key', true);

    echo '<label for="movie_radio_button">Rating </label>';

    echo '<select name="movie_rating_select_field" id="movie_rating_select_field">
            <option value="1" '.($value == 1 ? 'selected': '').'>1 Star</option>
            <option value="2" '.($value == 2 ? 'selected': '').'>2 Star</option>
            <option value="3" '.($value == 3 ? 'selected': '').'>3 Star</option>
            <option value="4" '.($value == 4 ? 'selected': '').'>4 Star</option>
            <option value="5" '.($value == 5 ? 'selected': '').'>5 Star</option>
        </select>';
}

function save_rating_data($post_id){
    if(!isset($_POST['movie_button_nonce'])){
        return;
    }
    if(!wp_verify_nonce($_POST['movie_button_nonce'], 'save_rating_data')){
        return;
    }
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
        return;
    }
    if(!current_user_can('edit_post', $post_id)){
        return;
    }
    if(!isset($_POST['movie_rating_select_field'])){
        return;
    }
    $my_data = sanitize_text_field($_POST['movie_rating_select_field']);
    update_post_meta($post_id, '_movie_rating_value_key', $my_data);

}

add_action('wp_ajax_myfilter', 'misha_filter_function'); // wp_ajax_{ACTION HERE} 
add_action('wp_ajax_nopriv_myfilter', 'misha_filter_function');
 
function misha_filter_function(){
	
 
	// for taxonomies / categories
	if( isset( $_POST['categoryfilter'] ) ){
    $args = array(
        'post_type'=> 'movies',
        'cat'=> $_POST['categoryfilter'],
        'posts_per_page'=> '1',
        'meta_key' => '_movie_rating_value_key',
        'meta_value' => $_POST['filter_movie_rating_select_field']
    );}
 
	$query = new WP_Query( $args );
 
	if( $query->have_posts() ) :
        while( $query->have_posts() ): $query->the_post();
        echo '<div style="width:400px;float: left;margin-left:10px;">';
            echo '<div style="width:400px;">' .get_the_post_thumbnail(). '</div>';
            echo '<center><h2>' . $query->post->post_title . '</h2></center>';
        echo '</div>';
		endwhile;
		wp_reset_postdata();
	else :
		echo 'No Movies Found';
	endif;
 
	die();
}


