<?php
/*
Plugin Name: 3D Image Gallery Room
Description: An experimental image gallery with a realistic touch. The images are displayed in a 3D room with walls.
Author: Codrops, Mary Lou, @crnacura. WP plugin by Martin Doubravsky, @matesd.
Version: 0.0.1
Author URI: http://tympanus.net/codrops/2013/01/15/3d-image-gallery-room/
Plugin URI: [github]

Created by Codrops.

Please read about our license: http://tympanus.net/codrops/licensing/

WP plugin developed by Martin Doubravsky.

*/

define('IMAGEGALLERY_DIR', WP_PLUGIN_DIR.'/3DImageGalleryRoom');
define('IMAGEGALLERY_URL', WP_PLUGIN_URL.'/3DImageGalleryRoom');

include_once 'settings.php';

// Activating plugin
register_activation_hook(__FILE__, 'js_activate');
function js_activate(){
	add_option('wall1', '3');
	add_option('wall2', '2');
	add_option('wall3', '3');
	add_option('wall4', '2');
}

/* Slider Post Types */
add_action('init', 'js_custom_init');
function js_custom_init() {
	load_plugin_textdomain( 'imageGalleryRoom', false, basename(dirname(__FILE__)) . '/languages' );
	
	$labels = array(
		'name' => _x('Slides', 'post type general name'),
		'singular_name' => _x('Slide', 'post type singular name'),
		'add_new' => _x('Add New', 'slide'),
		'add_new_item' => __('Add New Slide'),
		'edit_item' => __('Edit Slide'),
		'new_item' => __('New Slide'),
		'view_item' => __('View Slide'),
		'search_items' => __('Search Slides'),
		'not_found' =>  __('No slides found'),
		'not_found_in_trash' => __('No slides found in Trash'), 
		'parent_item_colon' => '',
		'menu_name' => '3D Gallery Room');

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => false,
		'hierarchical' => false,
		'menu_position' => 20,
		'supports' => array('editor','thumbnail')); 
  
  register_post_type('slide',$args);
}

// Load javascripts and css files
if(!is_admin()){
	add_action('wp_print_scripts', 'js_load_js');
	function js_load_js(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('modernizr', IMAGEGALLERY_URL.'/js/modernizr.custom.js');
		wp_enqueue_script('wallgallery', IMAGEGALLERY_URL.'/js/wallgallery.js');
	}

	add_action('wp_print_styles', 'js_load_css');
	function js_load_css(){
		wp_enqueue_style('galleryRoomCss', IMAGEGALLERY_URL.'/css/default.css');
		wp_enqueue_style('galleryRoomCss', IMAGEGALLERY_URL.'/css/component.css');
	}

	add_action('wp_head', 'js_head_code');
	function js_head_code(){
		$out = "<script>
				$(function() {
	
					Gallery.init( {
						layout : [".get_option('wall1').",".get_option('wall2').",".get_option('wall3').",".get_option('wall4')."]
					} );
	
				});
				</script>";

		echo $out;
	}
}

function imageGalleryRoom(){
	global $post;
	
	$qry = new WP_Query('post_type=slide&showposts=-1');
	if($qry->have_posts()):
	  
	  $out = '<div id="gr-gallery" class="gr-gallery">
	  			<div class="gr-main">';
	  	while($qry->have_posts()) : $qry->the_post();
	  	$out .= '<figure><div>';
	  	
	  	$images = get_posts( 'post_parent='.$post->ID.'&post_type=attachment&post_mime_type=image' );
	  	if ( !empty($images) ) {
	  		  $imgAttr = wp_get_attachment_image_src( $images[0]->ID );
  			$out .= '<img src="'.$imgAttr[0].'" />';
  		}
  		$out .= '</div>';
  		
  		$out .= '<figcaption><h2>'.get_the_content($post->ID).'</h2></figcaption>'; 
	  	
	  	$out .= '</figure>';
	  	
	  	endwhile;
	  	
	  $out .= '</div></div>';
	  
	endif;
	wp_reset_postdata();

	return $out;
}

add_shortcode('Image Gallery Room
', 'imageGalleryRoom');
add_theme_support('post-thumbnails');