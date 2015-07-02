<?php

/*
Plugin Name:       Chelan Recipe Plugin
Plugin URI:        https://chelanfruit.com
Description:       Recipes
Version:           1.0.1
Author:            Bradford Knowlton
GitHub Plugin URI: https://github.com/DesignMissoula/chelan-recipe-plugin
Requires WP:       3.8
Requires PHP:      5.3
*/

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
        
        'supports' => array( 'title', 'editor', 'custom-fields', 'revisions' ),
        
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
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'recipe', $args );
}