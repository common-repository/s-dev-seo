<?php

/*
Plugin Name: S-DEV SEO
Description: Add SEO Title and Meta Description fields to posts and pages
Version: 1.88
Author: Rob von Bothmer / SeoDev
Author URI: https://www.seodev.se
Text Domain: sdev-seo
*/

if(!function_exists("add_action")) {
	return false;
}

$wpse_sdevseo_plugin_url = plugin_dir_url( __FILE__ );

$files = glob( dirname (__FILE__) . '/inc/*.php');

foreach ($files as $file) {
    require($file);   
}

/********************************************
*	Activation function, registers default options
*/

	function wpse_sdevseo_activation() {
		
		$default_settings = array(
			'enable_autoadd_blogname' => "0",
			'separator_character' => '&#124;',
			'enable_og' => "1",
			'enable_meta_desc' => "1"
		);
		
		$add_response = add_option('wpse_sdevseo_settings', $default_settings);
		
	}
	
	register_activation_hook( __FILE__, 'wpse_sdevseo_activation' );
	

/********************************************
*	Deactivation function, removes default options
*/

	function wpse_sdevseo_deactivation() {
		
		$delete_response = delete_option('wpse_sdevseo_settings');
		
	}
	
	register_deactivation_hook( __FILE__, 'wpse_sdevseo_deactivation' );
	
