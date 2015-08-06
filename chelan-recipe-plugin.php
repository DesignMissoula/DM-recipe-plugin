<?php

/*
Plugin Name:       Chelan Recipe Plugin
Plugin URI:        https://chelanfruit.com
Description:       Recipes
Version:           1.3.3
Author:            Bradford Knowlton
GitHub Plugin URI: https://github.com/DesignMissoula/chelan-recipe-plugin
Requires WP:       3.8
Requires PHP:      5.3
*/

require_once ( plugin_dir_path( __FILE__ ) . '/inc/class-recipe-widget.php' );


function recipe_rewrite_flush() {
    // First, we "add" the custom post type via the above written function.
    // Note: "add" is written with quotes, as CPTs don't get added to the DB,
    // They are only referenced in the post_type column with a post entry, 
    // when you add a post of this CPT.
    register_cpt_recipe();

    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'recipe_rewrite_flush' );	

add_action( 'init', 'register_cpt_recipe' );

function register_cpt_recipe() {

    $labels = array( 
        'name' => _x( 'Recipes', 'recipe' ),
        'singular_name' => _x( 'Recipe', 'recipe' ),
        'add_new' => _x( 'Add New', 'recipe' ),
        'add_new_item' => _x( 'Add New Recipe', 'recipe' ),
        'edit_item' => _x( 'Edit Recipe', 'recipe' ),
        'new_item' => _x( 'New Recipe', 'recipe' ),
        'view_item' => _x( 'View Recipe', 'recipe' ),
        'search_items' => _x( 'Search Recipes', 'recipe' ),
        'not_found' => _x( 'No recipes found', 'recipe' ),
        'not_found_in_trash' => _x( 'No recipes found in Trash', 'recipe' ),
        'parent_item_colon' => _x( 'Parent Recipe:', 'recipe' ),
        'menu_name' => _x( 'Recipes', 'recipe' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        
        'supports' => array( 'title', 'editor', 'revisions', 'thumbnail' ), //  'custom-fields',
        'taxonomies' => array( 'fruits' ),       
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        
        'menu_icon' => 'dashicons-carrot',
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => array( 'slug' => 'recipes' ),
        'capability_type' => 'post',
        'register_meta_box_cb' => 'add_recipe_metaboxes'
    );

    register_post_type( 'recipe', $args );
}

add_action( 'init', 'register_taxonomy_fruits' );

function register_taxonomy_fruits() {

    $labels = array( 
        'name' => _x( 'Fruits', 'fruits' ),
        'singular_name' => _x( 'Fruit', 'fruits' ),
        'search_items' => _x( 'Search Fruits', 'fruits' ),
        'popular_items' => _x( 'Popular Fruits', 'fruits' ),
        'all_items' => _x( 'All Fruits', 'fruits' ),
        'parent_item' => _x( 'Parent Fruit', 'fruits' ),
        'parent_item_colon' => _x( 'Parent Fruit:', 'fruits' ),
        'edit_item' => _x( 'Edit Fruit', 'fruits' ),
        'update_item' => _x( 'Update Fruit', 'fruits' ),
        'add_new_item' => _x( 'Add New Fruit', 'fruits' ),
        'new_item_name' => _x( 'New Fruit', 'fruits' ),
        'separate_items_with_commas' => _x( 'Separate fruits with commas', 'fruits' ),
        'add_or_remove_items' => _x( 'Add or remove Fruits', 'fruits' ),
        'choose_from_most_used' => _x( 'Choose from most used Fruits', 'fruits' ),
        'menu_name' => _x( 'Fruits', 'fruits' ),
    );

    $args = array( 
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => false,
        'show_admin_column' => false,
        'hierarchical' => true,

        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy( 'fruits', array('recipe', 'location', 'fruit'), $args );
}



 /**
  * Custom columns for recipe custom post type edit screen
  *
  */
function edit_recipe_columns( $columns ) {

	// possible value
	// ZIP	Danmer Tracking Phone # 	Danmer Local office Cities	Area Code	City	State	County
	
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'recipe' ),
		'totaltime' => __( 'Total Time' ),
		'preptime' => __( 'Prep Time' ),
		// 'cooktime' => __( 'Cook Time' ),
		'servings' => __( 'Servings' ),
		'date' => __('Date')
		
	);

	return $columns;
}

add_filter( 'manage_edit-recipe_columns', 'edit_recipe_columns' ) ;



 /**
  * Returns values for columns for recipe custom post type edit screen
  *
  */  
function manage_recipe_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {

		/* If displaying the 'totaltime' column. */
		case 'totaltime' :

			/* Get the post meta. */
			$totaltime = get_post_meta( $post_id, 'totaltime', true );

			/* If no duration is found, output a default message. */
			if ( empty( $totaltime ) )
				echo __( 'N/A' );

			/* If there is a totaltime, format it to the text string. */
			else
				echo format_duration($totaltime);

		break;
		
		/* If displaying the 'preptime' column. */
		case 'preptime' :

			/* Get the post meta. */
			$preptime = get_post_meta( $post_id, 'preptime', true );

			/* If no duration is found, output a default message. */
			if ( empty( $preptime ) )
				echo __( 'N/A' );

			/* If there is a preptime, format it to the text string. */
			else
				echo format_duration($preptime);

		break;
		
		/* If displaying the 'cooktime' column. */
		case 'cooktime' :

			/* Get the post meta. */
			$cooktime = get_post_meta( $post_id, 'cooktime', true );

			/* If no duration is found, output a default message. */
			if ( empty( $cooktime ) )
				echo __( 'N/A' );

			/* If there is a cooktime, format it to the text string. */
			else
				echo format_duration($cooktime);

		break;


		/* If displaying the 'servings' column. */
		case 'servings' :

			/* Get the post meta. */
			$servings = get_post_meta( $post_id, 'servings', true );

			/* If no duration is found, output a default message. */
			if ( empty( $servings ) )
				echo __( 'N/A' );

			/* If there is a servings, format it to the text string. */
			else
				echo $servings;

		break;
		

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

add_action( 'manage_recipe_posts_custom_column', 'manage_recipe_columns', 10, 2 );



 /**
  * Adds sortable columns to recipe custom post type edit screen
  *
  * @param array $columns Array of sortable columns
  *
  */
function recipe_sortable_columns( $columns ) {

	$columns['totaltime'] = 'totaltime';
	$columns['preptime'] = 'preptime';
	// $columns['cooktime'] = 'cooktime';
	$columns['servings'] = 'servings';

	return $columns;
}

add_filter( 'manage_edit-recipe_sortable_columns', 'recipe_sortable_columns' );

 /**
  * Returns values for columns for recipe custom post type edit screen
  *
  */
function edit_recipe_load() {
	add_filter( 'request', 'sort_recipe' );
}
/* Only run our customization on the 'edit.php' page in the admin. */
add_action( 'load-edit.php', 'edit_recipe_load' );

 /**
  * Returns custom sort values for post type edit screen
  *
  * @param array $vars Array of custom query variables
  *
  */
function sort_recipe( $vars ) {

	/* Check if we're viewing the 'movie' post type. */
	if ( isset( $vars['post_type'] ) && 'recipe' == $vars['post_type'] ) {

		/* Check if 'orderby' is set to 'duration'. */
		if ( isset( $vars['orderby'] ) && 'totaltime' == $vars['orderby'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'totaltime',
					'orderby' => 'meta_value'
				)
			);
		}
		
		/* Check if 'orderby' is set to 'duration'. */
		if ( isset( $vars['orderby'] ) && 'preptime' == $vars['orderby'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'preptime',
					'orderby' => 'meta_value'
				)
			);
		}
		
		/* Check if 'orderby' is set to 'duration'. */
		if ( isset( $vars['orderby'] ) && 'cooktime' == $vars['orderby'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'cooktime',
					'orderby' => 'meta_value'
				)
			);
		}
		
		/* Check if 'orderby' is set to 'duration'. */
		if ( isset( $vars['orderby'] ) && 'servings' == $vars['orderby'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'servings',
					'orderby' => 'meta_value'
				)
			);
		}
	}

	return $vars;
}


if( !function_exists('format_duration')){
	
	/**
	 * Returns string formated as a standard US time duration
	 *
	 * @param string $number Int number of minutes
	 *
	 */	
	function format_duration($durationInSeconds = '') {
		
	 // http://stackoverflow.com/questions/6534490/formatting-duration-time-in-php	
	  $durationInSeconds *= 60; // convert from minutes to seconds	
	  $duration = '';
	  $days = floor($durationInSeconds / 86400);
	  $durationInSeconds -= $days * 86400;
	  $hours = floor($durationInSeconds / 3600);
	  $durationInSeconds -= $hours * 3600;
	  $minutes = floor($durationInSeconds / 60);
	  $seconds = $durationInSeconds - $minutes * 60;
	
	  if($days > 0) {
	    $duration .= $days . ' days';
	  }
	  if($hours > 0) {
	    $duration .= ' ' . $hours . ' hours';
	  }
	  if($minutes > 0) {
	    $duration .= ' ' . $minutes . ' minutes';
	  }
	  if($seconds > 0) {
	    $duration .= ' ' . $seconds . ' seconds';
	  }
	  return $duration;
	}
	
}




// Add the Recipe Meta Boxes

function add_recipe_metaboxes() {
	add_meta_box('ctp_recipe_times', 'Recipe Cook Times', 'ctp_recipe_times', 'recipe', 'side', 'default');
	add_meta_box('ctp_recipe_ingredients', 'Recipe Details', 'ctp_recipe_ingredients', 'recipe', 'normal', 'high');
}


// The Recipe Sidebar Metabox

function ctp_recipe_times() {
	global $post;
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="recipemeta_noncename" id="recipemeta_noncename" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	// Get the total time data if its already been entered
	$totaltime = get_post_meta($post->ID, 'totaltime', true);
	
	echo '<p>Total Time? (in minutes)</p>';
	// Echo out the field
	echo '<input type="text" name="totaltime" value="' . $totaltime  . '" class="widefat" />';
	
	// Get the prep time data if its already been entered
	$preptime = get_post_meta($post->ID, 'preptime', true);
	
	echo '<p>Prep Time? (in minutes)</p>';
	// Echo out the field
	echo '<input type="text" name="preptime" value="' . $preptime  . '" class="widefat" />';
	
	// Get the cook time data if its already been entered
	$cooktime = get_post_meta($post->ID, 'cooktime', true);
	
	echo '<p>Cook Time? (in minutes)</p>';
	// Echo out the field
	echo '<input type="text" name="cooktime" value="' . $cooktime  . '" class="widefat" />';
	
	// Get the cook time data if its already been entered
	$servings = get_post_meta($post->ID, 'servings', true);
	
	echo '<p>Servings</p>';
	// Echo out the field
	echo '<input type="text" name="servings" value="' . $servings  . '" class="widefat" />';	

}

// The Recipe Advanced Metabox

function ctp_recipe_ingredients() {
	global $post;
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="recipemeta_noncename" id="recipemeta_noncename" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	// Get the prep time data if its already been entered
	$tagline = get_post_meta($post->ID, 'tagline', true);
	
	echo '<p>Recipe Tagline</p>';
	// Echo out the field
	echo '<input type="text" name="tagline" value="' . $tagline  . '" class="widefat" />';
	
	// Get the total time data if its already been entered
	$ingredients = get_post_meta($post->ID, 'ingredients', true);
	
	echo '<p>Ingredients</p>';
	// Echo out the field
	echo '<textarea type="text" name="ingredients" class="widefat" rows="10" >' . $ingredients  . '</textarea>';
		
}


// Save the Metabox Data

function wpt_save_recipes_meta($post_id, $post) {
	
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !isset($_POST['recipemeta_noncename']) || !wp_verify_nonce( $_POST['recipemeta_noncename'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	
	$recipes_meta['totaltime'] = $_POST['totaltime'];
	$recipes_meta['preptime'] = $_POST['preptime'];
	$recipes_meta['cooktime'] = $_POST['cooktime'];
	$recipes_meta['servings'] = $_POST['servings'];
	$recipes_meta['tagline'] = $_POST['tagline'];
	$recipes_meta['ingredients'] = $_POST['ingredients'];
	
	// Add values of $recipes_meta as custom fields
	
	foreach ($recipes_meta as $key => $value) { // Cycle through the $recipes_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}

}

add_action('save_post', 'wpt_save_recipes_meta', 1, 2); // save the custom fields
