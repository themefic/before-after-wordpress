<?php
/**
 * Plugin Name: Ultimate Before After Image Slider - BEAF
 * Plugin URI: https://themefic.com/plugins/beaf/
 * Description: Want to show comparison of two images? With BEAF, you can easily create before and after image slider or image gallery. Elementor Supported.
 * Version: 2.2.3
 * Author: Themefic
 * Author URI: https://themefic.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: bafg
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit();
}

class BAFG_Before_After_Gallery {
    
    public function __construct(){
                
        /*
        * Enqueue css and js for BAFG
        */
        add_action( 'wp_enqueue_scripts', array($this, 'bafg_image_before_after_foucs_scripts') );
        add_action( 'admin_enqueue_scripts', array($this, 'bafg_image_before_after_foucs_scripts') );
        
        /*
        * BAFG init
        */
        add_action( 'init', array( $this, 'bafg_image_before_after_foucs_posttype' ) );
        
        /*
        * BAFG meta fields
        */
        $this->bafg_meta_fields();
        
        /*
        * Require admin file
        */
        require_once('admin/bafg-admin.php');
        
        /*
        * Adding shortcode for bafg
        */
        add_shortcode('bafg', array( $this, 'bafg_post_shortcode' ));
        
        /*
        * Submenu for pro version
        */
        add_action('admin_menu', array( $this, 'bafg_register_submenu_page' ) );
        
        /*
        * Require elementor widget
        */
        require_once('bafg-elementor/bafg-elementor.php');
    }
    
    /*
    * Enqueue css and js in frontend
    */
    public function bafg_image_before_after_foucs_scripts() {
        
        wp_enqueue_style( 'bafg_twentytwenty', plugin_dir_url( __FILE__ ) . 'assets/css/twentytwenty.css'); 
        wp_enqueue_style( 'bafg-style', plugin_dir_url( __FILE__ ) . 'assets/css/bafg-style.css'); 

        wp_enqueue_script( 'bafg_event_move', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.event.move.js', array('jquery'), null, true );
        wp_enqueue_script( 'bafg_twentytwenty', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.twentytwenty.js', array('jquery'), null, true );
        wp_enqueue_script( 'bafg_custom_js', plugin_dir_url( __FILE__ ) . 'assets/js/bafg-custom-js.js', array('jquery'), null, true );
    }
    
    //Enqueue script in admin area
    public function bafg_admin_enqueue_scripts(){
        wp_enqueue_script( 'custom_js', plugin_dir_url( __FILE__ ) . 'assets/js/bafg-script.js', array('jquery'), null, true );

        wp_enqueue_style('bafg_admin_style', plugin_dir_url( __FILE__ ) . 'assets/css/bafg-admin-style.css');
    }
    
    //register post type
    public function bafg_image_before_after_foucs_posttype() {
        register_post_type( 'bafg',
            array(
                'labels' => array(
                    'name' => _x( 'Before and After Gallery', 'bafg' ),
                    'singular_name' => _x( 'Before and After Gallery', 'bafg' ),
                    'add_new' => __( 'Add New', 'bafg' ),
                    'add_new_item' => __( 'Add New Gallery', 'bafg' ),
                    'new_item' => __( 'New Gallery', 'bafg' ),
                    'edit_item' => __( 'Edit Gallery', 'bafg' ),
                    'view_item' => __( 'View Gallery', 'bafg' ),
                    'all_items' => __( 'All Galleries', 'bafg' ),
                    'search_items' => __( 'Search Galleries', 'bafg' ),
                    'not_found' => __( 'No gallery found.', 'bafg' ),
                    'not_found_in_trash' => __( 'No gallery found in Trash.', 'bafg' ),
                ),
                'has_archive' => true,
                'public' => true,
                'rewrite' => array('slug' => 'bafg'),
                'supports' => array('title'),
                'menu_icon'  => 'dashicons-format-gallery'
            )
        );
    }
    
    /*
    * Adding submenu for pro version
    */
    public function bafg_register_submenu_page() {
        if ( !is_plugin_active( 'beaf-before-and-after-gallery-pro/before-and-after-gallery-pro.php' ) ){
            add_submenu_page(
                'edit.php?post_type=bafg',
                'Go Pro',
                '<span class="bafg-pro-link">★ Go Pro</span>',
                'manage_options',
                'https://live.themefic.com/beaf/pro/'
            );
        }
    }
    
    /*
     metabox included
    */
    public function bafg_meta_fields(){
        require_once('metabox/bafg-metaboxs.php');
    }
    
    /*
    * BAFG shortcode callback
    */
    public function bafg_post_shortcode( $atts, $content = null ){

        extract( shortcode_atts(array(
            'id' => ''
        ), $atts) );
        
        $b_image = get_post_meta( $id, 'bafg_before_image', true);
        $a_image = get_post_meta( $id, 'bafg_after_image', true);
        
        $orientation = !empty(get_post_meta( $id, 'bafg_image_styles', true)) ? get_post_meta( $id, 'bafg_image_styles', true) : 'horizontal';
        $offset = !empty(get_post_meta( $id, 'bafg_default_offset', true)) ? get_post_meta( $id, 'bafg_default_offset', true) : '0.5';
        $before_label = !empty(get_post_meta( $id, 'bafg_before_label', true)) ? get_post_meta( $id, 'bafg_before_label', true) : 'Before';
        $after_label = !empty(get_post_meta( $id, 'bafg_after_label', true)) ? get_post_meta( $id, 'bafg_after_label', true) : 'After';
        $overlay = !empty(get_post_meta( $id, 'bafg_no_overlay', true)) ? get_post_meta( $id, 'bafg_no_overlay', true) : 'no';
        $move_slider_on_hover = !empty(get_post_meta( $id, 'bafg_move_slider_on_hover', true)) ? get_post_meta( $id, 'bafg_move_slider_on_hover', true) : 'no';
        $click_to_move = !empty(get_post_meta( $id, 'bafg_click_to_move', true)) ? get_post_meta( $id, 'bafg_click_to_move', true) : 'no';
        
        ob_start();
        if(get_post_status($id) == 'publish' ) :
        ?>
        <div class="bafg-twentytwenty-container <?php echo esc_attr('slider-'.$id.''); ?> <?php if(get_post_meta($id, 'bafg_custom_color', true) == 'yes') echo 'bafg-custom-color'; ?>" bafg-orientation="<?php echo esc_attr($orientation); ?>" bafg-default-offset="<?php echo esc_attr($offset); ?>" bafg-before-label="<?php echo esc_attr($before_label); ?>" bafg-after-label="<?php echo esc_attr($after_label); ?>" bafg-overlay="<?php echo esc_attr($overlay); ?>" bafg-move-slider-on-hover="<?php echo esc_attr($move_slider_on_hover); ?>" bafg-click-to-move="<?php echo esc_attr($click_to_move); ?>">
            <img src="<?php echo esc_url($b_image); ?>" alt="Before Image">
            <img src="<?php echo esc_url($a_image); ?>" alt="After Image">
        </div>
        <style>
        <?php
        $bafg_before_label_background = !empty(get_post_meta( $id, 'bafg_before_label_background', true )) ? get_post_meta( $id, 'bafg_before_label_background', true ) : '';
        
        $bafg_before_label_color = !empty(get_post_meta( $id, 'bafg_before_label_color', true )) ? get_post_meta( $id, 'bafg_before_label_color', true ) : '';
        
        $bafg_after_label_background = !empty(get_post_meta( $id, 'bafg_after_label_background', true )) ? get_post_meta( $id, 'bafg_after_label_background', true ) : '';
        
        $bafg_after_label_color = !empty(get_post_meta( $id, 'bafg_after_label_color', true )) ? get_post_meta( $id, 'bafg_after_label_color', true ) : '';
        ?>
        <?php
        if( !empty($bafg_before_label_background) || !empty($bafg_before_label_color) ) {
        ?>
        .<?php echo 'slider-'.$id.''; ?> .twentytwenty-before-label::before {
            background: <?php echo esc_attr($bafg_before_label_background); ?>;
            color: <?php echo esc_attr($bafg_before_label_color); ?>;
        }
        <?php } ?>
            
        <?php
        if( !empty($bafg_after_label_background) || !empty($bafg_after_label_color) ) {
        ?>
        .<?php echo 'slider-'.$id.''; ?> .twentytwenty-after-label::before {
            background: <?php echo esc_attr($bafg_after_label_background); ?>;
            color: <?php echo esc_attr($bafg_after_label_color); ?>;
        }
        <?php } ?>
            
        </style>
        <?php
        endif;
        return ob_get_clean();
    }

}

new BAFG_Before_After_Gallery();
