<?php
/**
 * @package EZ Premium Testimonials - Wordpress Plugin
*/
/*
Plugin Name: EZ Premium Testimonials
Plugin URI: http://wordpress.org/plugins/premium-testimonials/
Description: This plugin will help your visitors decide to buy your services or products.
Version: 1.0.0
Author: eztiv
Author URI: http://www.eztiv.com/
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

global $wpdb;

define('PREMIUM_TESTIMONIALS_SCRIPT_VERSION', '1.0.0');
define('PREMIUM_TESTIMONIALS_SCRIPT_URL', plugin_dir_url( __FILE__ ));

define('PREMIUM_TESTIMONIALS_SCRIPT_CSS', PREMIUM_TESTIMONIALS_SCRIPT_URL . 'assets/css');
define('PREMIUM_TESTIMONIALS_SCRIPT_JS', PREMIUM_TESTIMONIALS_SCRIPT_URL . 'assets/js');
define('PREMIUM_TESTIMONIALS_SCRIPT_IMG', PREMIUM_TESTIMONIALS_SCRIPT_URL . 'assets/img');
define('PREMIUM_TESTIMONIALS_SCRIPT_DB_NAME', $wpdb->prefix . 'premium_testimonials_script');

$wp_upl_dir = wp_upload_dir();
define('PREMIUM_TESTIMONIALS_SCRIPT_URL_BASE_UPLOAD_DIR', $wp_upl_dir['baseurl']);
define('PREMIUM_TESTIMONIALS_SCRIPT_DIR_BASE_UPLOAD_DIR', $wp_upl_dir['basedir']);

require 'lib/pts-admin-functions.php';
require 'lib/pts-functions.php';
require 'lib/pts-widget.php';
require 'lib/pts-form-add-testimonial.php';
require 'lib/pts-testimonials-display.php';





register_activation_hook(__FILE__, 'pts_testimonials_options_install');

// general actions
add_action('admin_menu', '__pts_admin_menu');
add_action('wp_footer', 'frontend_cssjs');
add_action('wp_head','pts_testiminials_head');

// admin actions
add_action('admin_head', 'pts_admin_register_head');
add_action('wp_ajax_pts_approve_testiminial', 'pts_approve_testiminial');
add_action('wp_ajax_pts_unapprove_testiminial', 'pts_unapprove_testiminial');
add_action('wp_ajax_pts_delete_testiminial', 'pts_delete_testiminial');
add_action('wp_ajax_pts_edit_testimonial', 'pts_edit_testimonial');
add_action('wp_ajax_pts_add_testimonial', 'pts_add_testimonial');
add_action('wp_ajax_pts_integration_settings', 'pts_integration_settings');

// frontend actions
add_action('wp_ajax_pts_front_add_testimonial', 'pts_front_add_testimonial');
add_action('wp_ajax_nopriv_pts_front_add_testimonial', 'pts_front_add_testimonial');
add_action('wp_ajax_pts_get_add_form', 'pts_form_add_testimonial_popup');
add_action('wp_ajax_nopriv_pts_get_add_form', 'pts_form_add_testimonial_popup');


// shortcodes
add_shortcode('premiumTestimonialsAdd', 'pts_form_add_testimonial');
add_shortcode('premiumTestimonialsDisplay', 'pts_testimonials_display');