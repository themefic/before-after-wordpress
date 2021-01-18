<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit();
}

/*
* Enqueue css and js for bafg
*/
add_action( 'admin_enqueue_scripts', 'bafg_admin_enqueue_scripts' );

//Enqueue script in admin area
function bafg_admin_enqueue_scripts(){
    wp_enqueue_script( 'custom_js', plugins_url( '../assets/js/bafg-script.js', __FILE__ ), array('jquery'), null, true );

    wp_enqueue_style('bafg_admin_style', plugins_url( '../assets/css/bafg-admin-style.css', __FILE__ ));
}

// admin column
add_filter('manage_bafg_posts_columns', 'bafg_custom_columns', 10);  
add_action('manage_posts_custom_column', 'bafg_custom_columns_bimage', 10, 2);  
add_action('manage_posts_custom_column', 'bafg_custom_columns_aimage', 10, 2);  
add_action('manage_posts_custom_column', 'bafg_custom_columns_shortcode', 10, 2);  

function bafg_custom_columns($columns) {
   $columns = array(
      'cb' => '<input type="checkbox" />',
      'title' => esc_html__('Title', 'bafg'),
      'shortcode' => esc_html__('Shortcode', 'bafg'),
      'bimage' => esc_html__('Before Image', 'bafg'),
      'aimage' => esc_html__('After Image', 'bafg'),
      'date' => __( 'Date' )
   );
  return $columns;
}

function bafg_custom_columns_bimage($column_name, $id){  
  if($column_name === 'bimage') {
      
      $bafg_before_after_method = !empty(get_post_meta($id, 'bafg_before_after_method', true)) ? get_post_meta($id, 'bafg_before_after_method', true) : 'method_1';
      
      if( is_plugin_active( 'beaf-before-and-after-gallery-pro/before-and-after-gallery-pro.php' ) ){
          
          if($bafg_before_after_method == 'method_2'){
          
              $image_url = get_post_meta($id, 'bafg_before_after_image', true);

          }else{

              $image_url = get_post_meta($id, 'bafg_before_image', true);
          }
      }else{
          $image_url = get_post_meta($id, 'bafg_before_image', true);
      }
  	
  	 $image_id = attachment_url_to_postid( $image_url );
  	 $before_image = wp_get_attachment_image( $image_id, 'thumbnail');
  	 echo $before_image;
  }
}

function bafg_custom_columns_aimage($column_name, $id){  
  if($column_name === 'aimage') {
      
      $bafg_before_after_method = !empty(get_post_meta($id, 'bafg_before_after_method', true)) ? get_post_meta($id, 'bafg_before_after_method', true) : 'method_1';
      
      if( is_plugin_active( 'beaf-before-and-after-gallery-pro/before-and-after-gallery-pro.php' ) ){
          
          if($bafg_before_after_method == 'method_2'){
          
              $image_url = get_post_meta($id, 'bafg_before_after_image', true);

          }else{

              $image_url = get_post_meta($id, 'bafg_after_image', true);
          }
      }else{
          $image_url = get_post_meta($id, 'bafg_after_image', true);
      }
      
  	 $image_id = attachment_url_to_postid( $image_url );
  	 $after_image = wp_get_attachment_image( $image_id, 'thumbnail');
  	 echo $after_image;
  }
}

function bafg_custom_columns_shortcode($column_name, $id){  
  if($column_name === 'shortcode') { 
   $post_id =	$id;
   $shortcode = 'bafg id="' . $post_id . '"';
      echo '[' . $shortcode .']';   
  }  
}


add_action('admin_footer', function(){
    echo '<div id="bafg_copy">'.esc_html('Shortcode Copied!').'</div>';
});
