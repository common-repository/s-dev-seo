<?php

/********************************************
*	Place stuff in the backend head
*/

	
	function wpse_sdevseo_admin_script( $hook ) {
		
		global $wpse_sdevseo_plugin_url;
		
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		
		if ( 'toplevel_page_sdev-seo-overview' != $hook && 'post.php' != $hook ) {
			return;
		}
		
		wp_enqueue_script( 'wpse-sdevseo-admin-script', $wpse_sdevseo_plugin_url . '/js/sdev-seo.js', array(), '1.0' );
	}
	
	add_action( 'admin_enqueue_scripts', 'wpse_sdevseo_admin_script' );
	
	function wpse_sdevseo_admin_style() {

		global $wpse_sdevseo_plugin_url;

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		wp_register_style($handle = 'wpse-sdevseo-admin-style', $src = $wpse_sdevseo_plugin_url . '/css/sdev-seo.css', $deps = array(), $ver = '1.0.0', $media = 'all');
		wp_enqueue_style('wpse-sdevseo-admin-style');
		
	}
	
	add_action( 'admin_print_styles', 'wpse_sdevseo_admin_style' );