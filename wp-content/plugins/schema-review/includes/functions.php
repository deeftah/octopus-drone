<?php
/**
 * Schema Editor Review Functions
 *
 * @version 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


add_action( 'admin_init', 'schema_wp_editor_review_admin_init' );
/**
 * Schema Review init
 *
 * @since 1.0
 */
function schema_wp_editor_review_admin_init() {
	
	if ( ! class_exists( 'Schema_WP' ) ) return;
	
	$prefix = '_schema_review_';

	$fields = array(
	
		array( 
			'label'	=> __('Type', 'schema-wp-review'), 
			'desc'	=> __('Select Review Type.', 'schema-wp-review'),
			'tip'	=> __('This will also enable the review functionality on your post type', 'schema-wp-review'),
			'id'	=> $prefix.'type', 
			'type'	=> 'select', 
			'options' => array (
				'editor_review' => array ( 
					'label' => __('Editor Review', 'schema-wp-review'),
					'value'	=> 'editor_review'
				),
				/*
				'star_votes' => array (
					'label' => __('Star Rating Votes', 'schema-wp-review'),
					'value'	=> 'star_votes'
				)*/
			)
		),
		
		array( 
			'label'	=> __('Scale', 'schema-wp-review'), 
			'desc'	=> __('Set rating scale, default & recommended value is 5', 'schema-wp-review'),
			'id'	=> $prefix.'rating_scale', 
			'type'	=> 'number',
			'size'	=> 'small' 
		),
	);

	/**
	* Instantiate the class with all variables to create a meta box
	* var $id string meta box id
	* var $title string title
	* var $fields array fields
	* var $page string|array post type to add meta box to
	* var $context string context where to add meta box at (normal, side)
	* var $priority string meta box priority (high, core, default, low) 
	* var $js bool including javascript or not
	*/
	$schema_wp_review = new Schema_Custom_Add_Meta_Box( 'schema_review', 'Review', $fields, 'schema', 'normal', 'high', true );
}



add_action( 'current_screen', 'schema_wp_editor_review_post_meta' );
/**
 * Create review post meta box for active post types edit screens
 *
 * @since 1.0
 */
function schema_wp_editor_review_post_meta() {
	
	if ( ! class_exists( 'Schema_Custom_Add_Meta_Box' ) ) return;
	
	global $post;
	
	$prefix = '_schema_review_';

	/**
	* Create meta box on active post types edit screens
	*/
	$fields = array(
		array( 
			'label'	=> __('Rating', 'schema-wp-review'),
			'desc'	=> __('Enter your rating score', 'schema-wp-review'),
			'id'	=> $prefix.'rating',
			'type'	=> 'text',
			'size'	=> 'small'
		),
		array( 
			'label'	=> __('Name', 'schema-wp-review'),
			'desc'	=> __('Enter the name of the reviewed item.', 'schema-wp-review'),
			'id'	=> $prefix.'name',
			'type'	=> 'text',
			'size'	=> 'large'
		)
	);
	
	
	/**
	* Get enabled post types to create a meta box on
	*/
	$schemas_enabled = array();
	
	// Get schame enabled array
	$schemas_enabled = schema_wp_cpt_get_enabled();
	
	if ( empty($schemas_enabled) ) return;

	// Get post type from current screen
	$current_screen = get_current_screen();
	$post_type = $current_screen->post_type;
	
	foreach( $schemas_enabled as $schema_enabled ) : 
		
		// debug
		//echo '<pre>'; print_r($current_screen); echo '</pre>'; 
		
		$review_type = $schema_enabled['review_type'];
		
		if ( isset($review_type) && $review_type == 'editor_review' ) {
		
			// Get Schema enabled post types array
			$schema_cpt = $schema_enabled['post_type'];
		
			if ( ! empty($schema_cpt) && in_array( $post_type, $schema_cpt, true ) ) {

	
				$schema_wp_review_active = new Schema_Custom_Add_Meta_Box( 'schema_review', __('Review', 'schema-wp-review'), $fields, $post_type, 'normal', 'high', true );

			}
		}
		
		// debug
		//print_r($schema_enabled);
		
	endforeach;
}



add_filter('schema_wp_cpt_enabled', 'schema_wp_editor_review_extend_cpt_enabled');
/**
 * Extend the CPT Enabled array
 *
 * @since 1.0
 */
function schema_wp_editor_review_extend_cpt_enabled( $cpt_enabled ) {

	if ( empty($cpt_enabled) ) return;
	
	$args = array(
					'post_type'			=> 'schema',
					'post_status'		=> 'publish',
					'posts_per_page'	=> -1
				);
				
	$schemas_query = new WP_Query( $args );
	
	$schemas = $schemas_query->get_posts();
	
	// If there is no schema types set, return and empty array
	if ( empty($schemas) ) return array();
	
	$i = 0;
	
	foreach( $schemas as $schema ) : 
		
		// Get post meta
		$schema_review_type	= get_post_meta( $schema->ID, '_schema_review_type' , true );
		// Append review type
		$cpt_enabled[$i]['review_type']  = $schema_review_type;
						
		$i++;
			
	endforeach;
 	
	// debug
	//echo '<pre>'; print_r($cpt_enabled); echo '</pre>';
	
	return $cpt_enabled;
}



add_filter('schema_output', 				'schema_wp_editor_review_simple_output');
add_filter('schema_output_blog_post', 		'schema_wp_editor_review_simple_output');
add_filter('schema_output_category_post', 	'schema_wp_editor_review_simple_output');
/**
 * Add Review output
 *
 * @since 1.0
 */
function schema_wp_editor_review_simple_output( $schema ) {

	if ( empty($schema) ) return;
	
	global $post;
	
	// Get post meta
	$schema_ref = get_post_meta( $post->ID, '_schema_ref' , true );
	
	// Check for ref, if is not presented, then get out!
	if ( ! isset($schema_ref) || $schema_ref  == '' ) return $schema;
	
	$scale	= get_post_meta( $schema_ref, '_schema_review_rating_scale'	, true );
	
	if ( ! isset($scale) || $scale == '' ) $scale = 5; // set default value
	
	// Get review type
	$review_type = schema_wp_editor_review_get_type($post->ID);
	
	// Check for review type, if is not presented, then get out!
	if ( ! isset($review_type) || $review_type != 'editor_review' ) return $schema; 
	
	// Get review rating value
	$rating	= get_post_meta( $post->ID, '_schema_review_rating'		, true );
	
	if ( $rating != '' ) {
		
		// Get post content
		$content_post		= get_post($post->ID);
		$author				= get_userdata($content_post->post_author); 
		$name				= apply_filters( 'schema_review_name', $content_post->post_title );
	
		$schema['review'] = array(
								'@type'			=> 'Review',
								'author'		=> array (
									'@type'			=> 'Person',
									'name'			=> $author->display_name,
								),
								'name'			=> $name,
								'reviewRating'	=> array (
									'@type'			=> 'Rating',
									'bestRating'	=> $scale, // default is set to 5
									'ratingValue'	=> $rating
								)
							);
	}
	
	// debug
	//echo '<pre>'; print_r($schema); echo '</pre>';
	
	return $schema;
}


add_filter('schema_review_name', 'schema_wp_editor_review_title_filter');
/**
 * Override Review Name
 *
 * @since 1.0
 * return string
 */
function schema_wp_editor_review_title_filter( $review_name ) {
	
	global $post;

	$review_name_meta = get_post_meta($post->ID, '_schema_review_name', true);
	
	if (isset($review_name_meta) && $review_name_meta != '') {
		$review_name = $review_name_meta;
	}
	
	return $review_name;
}


/**
 * Get review type
 *
 * @since 1.0
 * return false or review type
 */
function schema_wp_editor_review_get_type( $post_id = null ) {
	
	global $post;
		
	if ( ! isset($post_id) ) $post_id = $post->ID;
	
	// Get post meta
	$schema_ref = get_post_meta( $post_id, '_schema_ref' , true );
	
	// Check for ref, if is not presented, then get out!
	if ( ! isset($schema_ref) || $schema_ref  == '' ) return false;
	
	$review_type = get_post_meta( $schema_ref, '_schema_review_type' , true );
	
	if ( ! isset($review_type) || $review_type == '' ) return false;
	
	return $review_type;
}


add_shortcode( 'schema-editor-review', 'schema_wp_editor_review_shortcode' );
/**
 * Editor Review Shortcode
 *
 * @since 1.0
 */
function schema_wp_editor_review_shortcode( $atts ) {
	
	global $post;
	
	extract( shortcode_atts( array(
        'before' 	=> __('Editor Rating : ', 'schema-wp-review'),
        'after'		=> '',
    ), $atts ) );
	
	$exclude = get_post_meta( $post->ID, '_schema_review_exclude' , true );
	if ( $exclude )
		return;
	
	$editor_review = get_post_meta( $post->ID, '_schema_review_rating' , true );
	
	if ( ! isset($editor_review) || $editor_review == '' )
		return;
	
	return $before . $editor_review . $after;
}
