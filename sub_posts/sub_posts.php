<?php
/* Plugin Name: Sub Posts
Plugin URI: https://github.com/franky1059/wordpress_sub_posts
Description: Sub Posts Extension for WordPress
Version: 1.0
Author: franky1059
Author https://github.com/franky1059/
License: GPLv2 or later
*/


function create_post_type() {
	// register custom post type
	register_post_type( 'sub_post',
		array(
			'labels' => array(
				'name' => __( ' Sub Posts ' ),
				'singular_name' => __( 'Sub Post' ),
				'add_new_item' => __( 'Add New Sub Post' ),
				'edit_item' => __( 'Edit Sub Post' ),
				'new_item' => __( 'New Sub Post' ),
				'view' => __( 'View Sub Post' ),
				'view_item' => __( 'View Sub Post' ),
				'search_items' => __( 'Search Sub Post' ),
				'not_found' => __( 'No Sub Posts found' ),
				'not_found_in_trash' => __( 'No Sub Posts found in Trash' ),
				'parent' => __( 'Parent Article' ),
			),
			'public' => true,
			'show_ui' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'has_archive' => true,
			'rewrite' => true,
			'hierarchical' => true,
			'query_var' => true,
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields', 'comments' ),
			'taxonomies' => array('category','post_tag'),
		)
	);

	// register custom taxonomy

}

function sub_post_add_meta_box_parent() {

	$screens = array( 'sub_post' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'sub_post_meta_box_parent',
			__( 'Parent Post', 'myplugin_textdomain' ),
			'sub_post_meta_box_parent',
			$screen,
			'side'
		);
	}
}


add_action( 'init', 'create_post_type' );



function sub_post_meta_box_parent( $post ) 
{
	$args = array();
	$args['post_id'] = $post->ID;
	$args['post_parent'] = $post->post_parent;
	if($args['post_parent']) {
		$post_parent = get_post($post->post_parent);
		$post_parent_title = $post_parent->post_title; 
	} else {
		$post_parent_title = '';
	}
	$args['post_parent_title'] = $post_parent_title;
	$args['wp_nonce_field'] = wp_nonce_field( basename( __FILE__ ), 'sub_post_meta_box', true , false ) ;

	echo sub_post_display_template(
		plugin_dir_path( __FILE__ ).'templates/admin-meta_box_parent.php',
		$args
	 );	
}

add_action( 'add_meta_boxes', 'sub_post_add_meta_box_parent' );



function sub_post_parent_list()
{
	$args = array();
	$args['current_value'] = '';
	$post = get_post($_GET['post_id']); 

	$prfx_stored_meta = get_post_meta( $post->ID );
	if ( isset ( $prfx_stored_meta['meta-text'] ) ) 
		$args['current_value'] = $prfx_stored_meta['meta-text'][0];

	$args['parent_list'] = sub_post_get_parent_list();

	echo sub_post_display_template(
		plugin_dir_path( __FILE__ ).'templates/admin-sub_post_parent_list.php',
		$args
	 );
}

function sub_post_get_parent_list()
{
	$parent_list = array();

	$args = array( 
		'posts_per_page' => -1, 
		'post_type' => 'sub_post',
		'post_parent' => null 
		);
	$posts_list = get_posts( $args );
	foreach ( $posts_list as $posts_list_item ) {
		$current_parent = array(
							'ID' => $posts_list_item->ID,
							'post_title' => $posts_list_item->post_title,
						);

		$parent_list[] = $current_parent;
	}

	return $parent_list;	
}

add_action( 'wp_ajax_sub_post_parent_list', 'sub_post_parent_list' );



function sub_post_meta_box_parent_save( $post_id ) {
 	global $wpdb;

    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'sub_post_meta_box' ] ) && wp_verify_nonce( $_POST[ 'sub_post_meta_box' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    $post_parent = (isset( $_POST[ 'current-parent-post' ] )) ? (int)$_POST[ 'current-parent-post' ] : null;
 
    // Checks for input and sanitizes/saves if needed
    if($post_parent > 0) {
		$rows_affected = $wpdb->query(
		    $wpdb->prepare(
		        "UPDATE {$wpdb->posts} SET post_parent = %d WHERE ID = %d;",
		        $post_parent, $post_id
		    ) // $wpdb->prepare
		); // $wpdb->query
    }
 
}

add_action( 'save_post', 'sub_post_meta_box_parent_save' );



function sub_post_display_template( $file_path, $args = array() )
{
	extract( $args );
	unset( $args );

	ob_start();
	require $file_path;
	return ob_get_clean();
}






// TODOs
	// menu_order functionality in parent post for all sub posts under parent
	// menu_order meta box in sub posts
	// next slide / prev slide function w/ template
	// put custom templates in plugin dir 
	









