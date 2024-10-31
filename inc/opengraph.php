<?php

/********************************************
*	Add Opengraph data to header
*/

	function wpse_sdevseo_add_og() {

	$wpse_sdevseo_settings = get_option('wpse_sdevseo_settings');
	
	if( (int) $wpse_sdevseo_settings['enable_og'] !== 1 ) { return false; }
	
	global $post;
	
	$str = '<!-- S-DEV SEO -->
	<meta property="og:type" content="article" />';
	
	if($title = get_post_meta($post->ID,'sdevseo_title', true)) {
		$str .= "\n\t".'<meta property="og:title" content="' . htmlentities($title) . '" />';
	} else {
		$str .= "\n\t".'<meta property="og:title" content="' . htmlentities(wp_title('', false)) . '" />';
	}
	
	if( (int)$wpse_sdevseo_settings['enable_meta_desc'] === 1 ) {
		if($desc = get_post_meta($post->ID,'sdevseo_desc', true)) {
			$str .= "\n\t".'<meta property="og:description" content="'.$desc.'" />';
		}
	}
	
	$str .= "\n\t".'<meta property="og:url" content="'.get_permalink($post->ID).'" />
	<meta property="article:published_time" content="'.get_the_date('Y-m-d H:i:s', $post->ID).'" />
	<meta property="article:modified_time" content="'.get_the_modified_date('Y-m-d H:i:s', $post->ID).'" />
	<meta property="og:site_name" content="'.get_bloginfo('name').'" />
	<meta name="twitter:card" content="summary" />
<!-- /S-DEV SEO -->'."\n\n";
	
	echo $str;
	
	}
	
	add_action('wp_head', 'wpse_sdevseo_add_og', 1);
	