<?php

/********************************************
*	Check conditions and alert user
*/


	function wpse_check_visibility() {
		if ( ! class_exists( 'WPSEO_Admin' ) ) {
			if ( '0' == get_option( 'blog_public' ) ) {
				add_action( 'admin_notices', 'wpse_sdevseo_private_wp_warning', PHP_INT_MAX );
			}
			
			if ( is_plugin_active( 'headRewriteOG/index.php' ) ) {
				add_action( 'admin_notices', 'wpse_sdevseo_hrwog_wp_warning', PHP_INT_MAX );
			}
			
			if( is_plugin_active( 'seo-ultimate/seo-ultimate.php' ) ) {
				add_action( 'admin_notices', 'wpse_sdevseo_seoult_wp_warning', PHP_INT_MAX );
			}
			
			if( is_plugin_active( 'all-in-one-seo-pack/all_in_one_seo_pack.php' ) ) {
				add_action( 'admin_notices', 'wpse_sdevseo_aioseo_wp_warning', PHP_INT_MAX );
			}

		}
	}

	add_action( 'admin_init', 'wpse_check_visibility' );

	function wpse_sdevseo_private_wp_warning() {
		if ( ( function_exists( 'is_network_admin' ) && is_network_admin() ) ) {
			return;
		}

		echo '<div id="robotsmessage" class="error notice">';
		echo '<p><strong>' . __( 'S-DEV SEO Alert<br />Search Engine Indexing is disabled.', 'wpse-sdevseo' ) . '</strong> ' . sprintf( __( 'You must %sgo to your Reading Settings%s and uncheck the box for Search Engine Visibility.', 'wpse-sdevseo' ), '<a href="' . esc_url( admin_url( 'options-reading.php' ) ) . '">', '</a>' ) . '</p></div>';
	}
	
	function wpse_sdevseo_hrwog_wp_warning() {
		
		if ( ( function_exists( 'is_network_admin' ) && is_network_admin() ) ) {
			return;
		}

		echo '<div id="robotsmessage" class="error notice">';
		echo '<p><strong>' . __( 'S-DEV SEO Alert<br />Plugin \'HeadRewriteOG\' is installed and active.', 'wpse-sdevseo' ) . '</strong> ' . sprintf( __( 'This plugin is deprecated as same functionality exists in S-DEV SEO instead. %sPlease uninstall it now%s to avoid performance issues.', 'wpse-sdevseo' ), '<a href="' . esc_url( admin_url( 'plugins.php' ) ) . '" class="button">', '</a>' ) . '</p></div>';
		
	}

	function wpse_sdevseo_seoult_wp_warning() {
		
		if ( ( function_exists( 'is_network_admin' ) && is_network_admin() ) ) {
			return;
		}

		echo '<div id="robotsmessage" class="error notice">';
		echo '<p><strong>' . __( 'S-DEV SEO Alert<br />Plugin \'SEO Ultimate\' is installed and active.', 'wpse-sdevseo' ) . '</strong> This plugin is abandonware and for security reasons it is highly recommended to uninstall this plugin. Do you want to <a href="' . esc_url( admin_url( 'admin.php' ) ) . '?page=sdev-seo-overview#import_seo_ultimate" class="button">Import Titles and Meta Descriptions</a> to S-DEV SEO before uninstalling?</p></div>';
		
	}
	
	function wpse_sdevseo_aioseo_wp_warning() {
		
		if ( ( function_exists( 'is_network_admin' ) && is_network_admin() ) ) {
			return;
		}

		echo '<div id="robotsmessage" class="error notice">';
		echo '<p><strong>' . __( 'S-DEV SEO Alert<br />Plugin \'All in One SEO\' is installed and active.', 'wpse-sdevseo' ) . '</strong> We do not recommend to have two active SEO plugins at once. Do you want to <a href="' . esc_url( admin_url( 'admin.php' ) ) . '?page=sdev-seo-overview#import_aio_seo" class="button">Import Titles and Meta Descriptions</a> to S-DEV SEO before uninstalling?</p></div>';
		
	}
	
