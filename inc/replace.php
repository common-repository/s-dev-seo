<?php


/********************************************
*	Brute force replace title tag
* 	Todo: wp_head caching with ability to purge?
*/

	function wpse_sdevseo_start_head_buffer() {
		ob_start();
	}

	function wpse_sdevseo_end_head_buffer() {
		
		global $post;
		
		$wpse_sdevseo_settings = get_option('wpse_sdevseo_settings');
		
		if(is_home()) {
			
			$home_obj = get_post(get_option( 'page_for_posts' ));
			$title = get_post_meta($home_obj->ID,'sdevseo_title', true);
			
			if(empty($title)) {
				$title = $home_obj->post_title;
				$auto_title = true;
			}
			
		} elseif(is_category()) {

			$category = get_category( get_query_var( 'cat' ) );
			$cat_id = $category->cat_ID;
			$title = get_cat_name($cat_id);
			$auto_title = true;

		} elseif(!$title = get_post_meta($post->ID,'sdevseo_title', true)) {
			
			$auto_title = true;
			$title = $post->post_title;
			
		}
		
		$title = addcslashes($title,'$'); /* Bugfix, preg_replace needs escaped dollar signs */
		
		if(!$desc = get_post_meta($post->ID,'sdevseo_desc', true)) {
			$desc = false;
		} else {
			$desc = str_replace('%year%', date('Y'), $desc);
			$desc = addcslashes($desc,'$');
		}
		
		/* 1.84 - Dynamic year implementation */
		$title = str_replace('%year%', date('Y'), $title);
		$in = ob_get_clean();

		if($title) {
			
			
			if( (isset($auto_title)) && ( (int)$wpse_sdevseo_settings['enable_autoadd_blogname'] === 1) ) {
				
				$title .= ' ' . $wpse_sdevseo_settings['separator_character'] . ' ' . get_bloginfo('name');
				
			}
			
			if($desc) {
				
				if( (int)$wpse_sdevseo_settings['enable_meta_desc'] === 1 ) {
					preg_match('/<meta.*?name=("|\')description("|\').*?content=("|\')(.*?)("|\')/i', $in, $matches);
					
					if(count($matches) === 0) {
						$meta_desc_tag = "\n\t".'<meta name="description" content="'.$desc.'" />'."\n";
					} else {
						$meta_desc_tag = null;
					}
				}
				
				$in = preg_replace('/<title>(.*)<\/title>/i','<title>'.$title.'</title>'.$meta_desc_tag, $in);
				
			} else {
				
				$in = preg_replace('/<title>(.*)<\/title>/i','<title>'.$title.'</title>', $in);
				
			}
			
			$in = preg_replace('/property="og:title" content="(.*?)"/', 'property="og:title" content="' . $title . '"', $in);
			
		} else {
			
			if($desc) {
				
				if( (int)$wpse_sdevseo_settings['enable_meta_desc'] === 1 ) {
				
					preg_match('/<meta.*?name=("|\')description("|\').*?content=("|\')(.*?)("|\')/i', $in, $matches);
					
					if (count($matches) < 1) {
						$in .= "\n".'<meta name="description" content="'.$desc.'" />'."\n";
					}
					
				}
			}			
			
		}
		
		echo $in; // output the result unless you want to remove it
	}
	
	function wpse_sdevseo_check_availability() {
		
		if(is_admin()) { return false; }
		
		global $post;
		
		$wpse_sdevseo_settings = get_option('wpse_sdevseo_settings');
		
		if(!$title = get_post_meta($post->ID,'sdevseo_title', true)) {
			
			$title = $post->post_title;
			
		}
		
		if(!$desc = get_post_meta($post->ID,'sdevseo_desc', true)) {
			$desc = false;
		}
		
		if(!$title && !$desc) {
			if( (int)$wpse_sdevseo_settings['enable_autoadd_blogname'] !== 1) {
				return false;
			}
		}

		add_action('get_header','wpse_sdevseo_start_head_buffer',0);
		add_action('wp_head','wpse_sdevseo_end_head_buffer', PHP_INT_MAX);
		
		return false;
		
	}
	
	add_action('template_redirect', 'wpse_sdevseo_check_availability', PHP_INT_MAX);
	
	function wpse_sdevseo_meta_desc() {
		
		global $post;
		
		if($desc = get_post_meta($post->ID,'sdevseo_desc', true)) {
		
			echo "\n".'<meta name="description" content="'.$desc.'" />'."\n";
			
		}
		
	}
	