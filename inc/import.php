<?php

/********************************************
*	Import from SEO Ultimate and save to S-DEV SEO meta
*/


	function wpse_sdevseo_seoultimate_import() {
		
		$pages = get_pages(
			array(
				'post_status' => array(
					'publish',
					'pending',
					'future',
					'private'
				)
			)
		);
		
		$posts = get_posts(
			array(
				'numberposts' => -1
			)
		);
		
		foreach($pages as $page) {
			
			$import_array[] = $page->ID;
			
		}

		foreach($posts as $post_item) {

			$import_array[] = $post_item->ID;

		}
		
		$countTitles = $countDesc = 0;
		
		if($import_array)
		foreach($import_array as $item) {
		
			if($su_title = get_post_meta($item, '_su_title', true)) {
				
				if(mb_strlen($su_title) > 0) {
				
					if($response = update_post_meta( $item, 'sdevseo_title', $su_title )) {
						
						$countTitles++;
						
					} else {
						
						if($su_title !== get_post_meta($item,'sdevseo_title', true)) { /* Identical, move along */
							$failed_arr[] = 'Item '.$item.' - Failed to import title.';
						}
						
					}
					
				}				
				
			}
			
			if($su_desc = get_post_meta($item, '_su_description', true)) {
				
				if(mb_strlen($su_desc) > 0) {
				
					if($response = update_post_meta( $item, 'sdevseo_desc', $su_desc )) {
						
						$countDesc++;
						
					} else {
						
						if($su_desc !== get_post_meta($item,'sdevseo_desc', true)) { /* Identical, move along */
							$failed_arr[] = 'Item '.$item.' - Failed to import description.';
						}
						
						
					}
					
				}
				
			}
			
		}
		
		if(isset($failed_arr)) {
			
			$response_string = implode('<br />',$failed_arr);
			
			echo '<div class="error notice"><p>'.$response_string.'</p></div>';
			
		} else {
			
			echo '<div class="updated"><p><strong>S-DEV SEO Notice</strong><br />Import from SEO Ultimate was successful. Go to the plugins page and <a href="' . esc_url( admin_url( 'plugins.php' ) ) . '" class="button">Uninstall SEO Ultimate</a> now.</p></div>';
			
		}
		
	}
	
/********************************************
*	Import from All in One SEO and save to S-DEV SEO meta
*/
	
	function wpse_sdevseo_aio_seo_import() {
		
		$pages = get_pages(
			array(
				'post_status' => array(
					'publish',
					'pending',
					'future',
					'private'
				)
			)
		);
		
		$posts = get_posts(
			array(
				'numberposts' => -1
			)
		);
		
		foreach($pages as $page) {
			
			$import_array[] = $page->ID;
			
		}

		foreach($posts as $post_item) {

			$import_array[] = $post_item->ID;

		}
		
		$countTitles = $countDesc = 0;
		
		if($import_array)
		foreach($import_array as $item) {
		
			if($su_title = get_post_meta($item, '_aioseop_title', true)) {
				
				if(mb_strlen($su_title) > 0) {
				
					if($response = update_post_meta( $item, 'sdevseo_title', $su_title )) {
						
						$countTitles++;
						
					} else {
						
						if($su_title !== get_post_meta($item,'sdevseo_title', true)) { /* Identical, move along */
							$failed_arr[] = 'Item '.$item.' - Failed to import title.';
						}
						
					}
					
				}				
				
			}
			
			if($su_desc = get_post_meta($item, '_aioseop_description', true)) {
				
				if(mb_strlen($su_desc) > 0) {
				
					if($response = update_post_meta( $item, 'sdevseo_desc', $su_desc )) {
						
						$countDesc++;
						
					} else {
						
						if($su_desc !== get_post_meta($item,'sdevseo_desc', true)) { /* Identical, move along */
							$failed_arr[] = 'Item '.$item.' - Failed to import description.';
						}
						
						
					}
					
				}
				
			}
			
		}
		
		if(isset($failed_arr)) {
			
			$response_string = implode('<br />',$failed_arr);
			
			echo '<div class="error notice"><p>'.$response_string.'</p></div>';
			
		} else {
			
			echo '<div class="updated"><p><strong>S-DEV SEO Notice</strong><br />Import from All in One SEO was successful. Go to the plugins page and <a href="' . esc_url( admin_url( 'plugins.php' ) ) . '" class="button">Uninstall All in One SEO</a> now.</p></div>';
			
		}
		
	}