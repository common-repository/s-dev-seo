<?php


/********************************************
*	Add Backend Pages to Menu
*/
	
	add_action('admin_menu', 'wpse_sdevseo_admin_menu');

	function wpse_sdevseo_admin_menu() {

		//create new top-level menu
		add_menu_page('S-DEV SEO', 'S-DEV SEO', 'edit_posts', 'sdev-seo-overview', 'wpse_sdevseo_overview' , 'dashicons-admin-site-alt', 2 );
		add_submenu_page('sdev-seo-overview', 'SEO Overview', 'SEO Overview', 'edit_posts', 'sdev-seo-overview');
		add_submenu_page('sdev-seo-overview', 'Settings', 'SEO Settings', 'edit_posts', 'sdev-seo-settings', 'wpse_sdevseo_settings');

	}


/********************************************
*	Backend Overview Page
*/

	
	function wpse_sdevseo_overview() {
		
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		
		if(isset($_POST['wpse-sdevseo-form'])) { /* Only check isset, no sanitation needed */
			
			wpse_sdevseo_overview_save();
			
		}
		
		if(isset($_POST['import_seo_ultimate'])) { /* Only check isset, no sanitation needed */
			
			wpse_sdevseo_seoultimate_import();
			
		}
		
		if(isset($_POST['import_aio_seo'])) { /* Only check isset, no sanitation needed */
			
			wpse_sdevseo_aio_seo_import();
			
		}
		
		$wpse_sdevseo_settings = get_option('wpse_sdevseo_settings');
		
		if( (int)$wpse_sdevseo_settings['enable_autoadd_blogname'] === 1 ) {
			echo '<div class="updated notice sdev-seo-notice">';
				
				echo '<p><strong>S-DEV SEO Notice</strong></p>';
				echo '<ul>';
				
					echo '<li>The option <em>\'Automatically add blogname last in titles\'</em> is activated. All titles <sup>1</sup> will automatically have \'<strong> ' . $wpse_sdevseo_settings['separator_character'] . ' '.get_bloginfo('name').'</strong>\' in the end of the title. You can disable this option in <a href="'.esc_url( admin_url( 'admin.php' ) ) . '?page=sdev-seo-settings" class="button">The settings</a></li>';
					
					echo '<li>Dynamic Year support has been added. Use <strong>%year%</strong> in titles and description to print current year.</li>';
					
				echo '</ul>';
				
				echo '<hr />';
				
				echo '<p><sup>1</sup> - Is only added to pages / posts where the title has not been manually set.</p>';
			
			echo '</div>';
		}
		
		echo '<div class="wrap sdev-seo-overview">';
			
			echo '<h1 class="wp-heading-inline">S-DEV SEO - Overview</h1>';
			
			echo '<form method="post" name="wpse-sdevseo">';
				
				echo '<input type="hidden" name="wpse-sdevseo-form" value="submit" />';
				
				echo '<input type="hidden" name="nonce" value="'.wp_create_nonce('sdev-seo-overview').'" />';
				
				/******************
				/* Pages section
				*/
			
				echo '<h1 class="wp-heading-inline section-heading">Pages</h1>';
				
				echo '<div class="wpse-sdevseo-table">';
					
					echo '<div class="wpse-sdevseo-table-row table-header">';
						
						echo '<div class="wpse-sdevseo-table-cell">';
							echo 'ID';
						echo '</div>';
						
						echo '<div class="wpse-sdevseo-table-cell">';
							echo 'Page Name';
						echo '</div>';
						
						echo '<div class="wpse-sdevseo-table-cell">';
							echo 'Page Attributes';
						echo '</div>';
						
						echo '<div class="wpse-sdevseo-table-cell">';
							echo 'SEO Attributes';
							echo '<input type="submit" class="button wpse-sdevseo-submit-btn" value="Save" />';
						echo '</div>';
					
					echo '</div>';
					
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
					
					foreach($pages as $page) {
						
						
						if(!$sdevseo_title = get_post_meta($page->ID, 'sdevseo_title', true)) {
							$sdevseo_title = '';
							$sdevseo_title_count = 0;
						} else {
							$sdevseo_title_count = mb_strlen($sdevseo_title);
						}
						
						if(!$sdevseo_desc = get_post_meta($page->ID, 'sdevseo_desc', true)) {
							$sdevseo_desc = '';
							$sdevseo_desc_count = 0;
						} else {
							$sdevseo_desc_count = mb_strlen($sdevseo_desc);
						}
						
						echo '<div class="wpse-sdevseo-table-row">';
								
							echo '<div class="wpse-sdevseo-table-cell';
							
							if($page->post_parent) {
								echo ' has-parent';
							}
							
							echo '">';
								
								echo '<span class="id-brick">';
									echo $page->ID;
								echo '</span>';
								
							echo '</div>';
							
							echo '<div class="wpse-sdevseo-table-cell">';
								
								echo $page->post_title;
								echo '<br />';
								
								echo '<a href="' . esc_url( admin_url( 'post.php' ) ) . '?post='.$page->ID.'&action=edit">Edit Page</a>';
								
								echo ' | ';
								
								echo '<a href="'.get_permalink($page->ID).'" target="_blank">';
									echo 'View Page';
								echo '</a>';
								
							echo '</div>';
							
							echo '<div class="wpse-sdevseo-table-cell">';
								
								echo '<strong>Permalink:</strong> ';
								
								echo '<a href="'.get_permalink($page->ID).'" target="_blank">';
									
									if(str_replace(get_bloginfo('url'), '', get_permalink($page->ID)) === '/') {
										echo 'Frontpage';
									} else {
										echo str_replace(get_bloginfo('url'), '', get_permalink($page->ID));
									}
									
								echo '</a>';
								
								echo '<br />';
								
								echo '<strong>Status:</strong> ' . $page->post_status;
								
								if($page->post_parent !== 0) {
									echo '<br />';
									echo '<strong>Parent:</strong> ';
									
									$parent = get_post($page->post_parent);
									
									echo '<a href="'.get_permalink($page->post_parent).'" target="_blank">';
										echo $parent->post_title;
									echo '</a>';
								}
								
							echo '</div>';
							
							echo '<div class="wpse-sdevseo-table-cell input-cell">';
								
								echo '<div class="sdevseo-input-container">';
									
									echo '<div class="sdevseo-input-container-header">';
									
										echo '<span>Title Tag</span>';
										echo '<span id="charnum">Characters: '.$sdevseo_title_count.'</span>';
										echo '<span class="clear"></span>';
										
									echo '</div>';
									
									echo '<input class="sdevseo-input" spellcheck="true" autocomplete="off" title="Title Tag" type="text" value="'.$sdevseo_title.'" name="sdevseo_title['.$page->ID.']" />';
									
								echo '</div>';
									
								if( (int)$wpse_sdevseo_settings['enable_meta_desc'] === 1 ) {
								
									echo '<div class="sdevseo-input-container">';
										
										echo '<div class="sdevseo-input-container-header">';
											
											echo '<span>Meta Description Tag</span>';
											echo '<span id="charnum">Characters: '.$sdevseo_desc_count.' / 155</span>';
											echo '<span class="clear"></span>';
											
										echo '</div>';
										
										echo '<textarea class="sdevseo-textarea" spellcheck="true" autocomplete="off" title="Meta Description Tag" name="sdevseo_desc['.$page->ID.']">'.$sdevseo_desc.'</textarea>';
										
									echo '</div>';
									
								}
								
							echo '</div>';
						
						echo '</div>';
					
					}
					
					echo '<div class="wpse-sdevseo-table-row table-footer">';

						echo '<div class="wpse-sdevseo-table-cell"></div>';
						echo '<div class="wpse-sdevseo-table-cell"></div>';
						echo '<div class="wpse-sdevseo-table-cell"></div>';
						echo '<div class="wpse-sdevseo-table-cell action-cell">';
							echo '<input type="submit" class="button wpse-sdevseo-submit-btn" value="Save" />';
						echo '</div>';
					
					echo '</div>';
				
				
				/******************
				/* Posts section
				*/
					
					$posts = get_posts(
						array(
							'numberposts' => -1
						)
					);
					
					if(count($posts) < 1) {
						
						echo '<p>No posts available.</p>';
						
					} else {
						
						$categories = get_categories(array(
							'orderby' => 'name',
							'parent'  => 0
						));
						
						foreach($categories as $key=>$category) {
							
							$posts = get_posts(
								array(
									'numberposts' => -1,
									'category' => $category->cat_ID,
									'post_status' => array(
										'publish',
										'pending',
										'future',
										'private'
									)
								)
							);
							
							echo '<div class="wpse-sdevseo-table-row clear-header">';
								echo '<div class="wpse-sdevseo-table-cell" style="position: relative;">';
									
									if($key == 1) {
										echo '<h1 class="wp-heading-inline section-heading" style="position: relative;">Posts</h1><br />';
										echo '<h2 class="wp-heading-inline section-heading" style="position: relative;">&nbsp;<span style="white-space: nowrap; position: absolute; top: 0; left: 0;">Category: '.$category->name . ' (' . count($posts) . ' posts)</span></h2>';
									} else {
										echo '<h2 class="wp-heading-inline section-heading" style="position: relative;">&nbsp;<span style="white-space: nowrap; position: absolute; top: 0; left: 0;">Category: '.$category->name . ' (' . count($posts) . ' posts)</span></h2>';
									}
									
								echo '</div>';
							echo '</div>';
							
							echo '<div class="wpse-sdevseo-table-row table-header">';
								
								echo '<div class="wpse-sdevseo-table-cell">';
									echo 'ID';
								echo '</div>';
								
								echo '<div class="wpse-sdevseo-table-cell">';
									echo 'Post Name';
								echo '</div>';
								
								echo '<div class="wpse-sdevseo-table-cell">';
									echo 'Post Attributes';
								echo '</div>';
								
								echo '<div class="wpse-sdevseo-table-cell">';
									echo 'SEO Attributes';
									echo '<input type="submit" class="button wpse-sdevseo-submit-btn" value="Save" />';
								echo '</div>';
							
							echo '</div>';
						
							foreach($posts as $post_item) {
								
								
								if(!$sdevseo_title = get_post_meta($post_item->ID, 'sdevseo_title', true)) {
									$sdevseo_title = '';
									$sdevseo_title_count = 0;
								} else {
									$sdevseo_title_count = mb_strlen($sdevseo_title);
								}
								
								if(!$sdevseo_desc = get_post_meta($post_item->ID, 'sdevseo_desc', true)) {
									$sdevseo_desc = '';
									$sdevseo_desc_count = 0;
								} else {
									$sdevseo_desc_count = mb_strlen($sdevseo_desc);
								}
								
								echo '<div class="wpse-sdevseo-table-row">';
										
									echo '<div class="wpse-sdevseo-table-cell';
									
									if($post_item->post_parent) {
										echo ' has-parent';
									}
									
									echo '">';
										
										echo '<span class="id-brick">';
											echo $post_item->ID;
										echo '</span>';
										
									echo '</div>';
									
									echo '<div class="wpse-sdevseo-table-cell">';
										
										echo $post_item->post_title;
										echo '<br />';
										
										echo '<a href="' . esc_url( admin_url( 'post.php' ) ) . '?post='.$post_item->ID.'&action=edit">Edit Post</a>';
										
										echo ' | ';
										
										echo '<a href="'.get_permalink($post_item->ID).'" target="_blank">';
											echo 'View post';
										echo '</a>';
										
									echo '</div>';
									
									echo '<div class="wpse-sdevseo-table-cell">';
										
										echo '<strong>Permalink:</strong> ';
										
										echo '<a href="'.get_permalink($post_item->ID).'" target="_blank">';
											
											if(str_replace(get_bloginfo('url'), '', get_permalink($post_item->ID)) === '/') {
												echo 'Frontpage';
											} else {
												echo str_replace(get_bloginfo('url'), '', get_permalink($post_item->ID));
											}
											
										echo '</a>';
										
										echo '<br />';
										
										echo '<strong>Status:</strong> ' . $post_item->post_status;
										
										if($post_item->post_parent !== 0) {
											echo '<br />';
											echo '<strong>Parent:</strong> ';
											
											$parent = get_post($post_item->post_parent);
											
											echo '<a href="'.get_permalink($post_item->post_parent).'" target="_blank">';
												echo $parent->post_title;
											echo '</a>';
										}
										
										echo '<br />';
										
										echo '<strong>Publish date:</strong> ' . $post_item->post_date_gmt;
										
										echo '<br />';
										
										echo '<strong>Modified date:</strong> ' . $post_item->post_modified_gmt;
										
									echo '</div>';
									
									echo '<div class="wpse-sdevseo-table-cell input-cell">';
										
										echo '<div class="sdevseo-input-container">';
											
											echo '<div class="sdevseo-input-container-header">';
											
												echo '<span>Title Tag</span>';
												echo '<span id="charnum">Characters: '.$sdevseo_title_count.'</span>';
												echo '<span class="clear"></span>';
												
											echo '</div>';
											
											echo '<input class="sdevseo-input" spellcheck="true" autocomplete="off" title="Title Tag" type="text" value="'.$sdevseo_title.'" name="sdevseo_title['.$post_item->ID.']" />';
											
										echo '</div>';
										
										if( (int)$wpse_sdevseo_settings['enable_meta_desc'] === 1 ) {
										
											echo '<div class="sdevseo-input-container">';
												
												echo '<div class="sdevseo-input-container-header">';
													
													echo '<span>Meta Description Tag</span>';
													echo '<span id="charnum">Characters: '.$sdevseo_desc_count.' / 155</span>';
													echo '<span class="clear"></span>';
													
												echo '</div>';
												
												echo '<textarea class="sdevseo-textarea" spellcheck="true" autocomplete="off" title="Meta Description Tag" name="sdevseo_desc['.$post_item->ID.']">'.$sdevseo_desc.'</textarea>';
												
											echo '</div>';
											
										}
										
									echo '</div>';
								
								echo '</div>';
							
							}
						
						}
						
						echo '<div class="wpse-sdevseo-table-row table-footer">';

							echo '<div class="wpse-sdevseo-table-cell"></div>';
							echo '<div class="wpse-sdevseo-table-cell"></div>';
							echo '<div class="wpse-sdevseo-table-cell"></div>';
							echo '<div class="wpse-sdevseo-table-cell action-cell">';
								echo '<input type="submit" class="button wpse-sdevseo-submit-btn" value="Save" />';
							echo '</div>';
						
						echo '</div>';
						
					}
				
				echo '</div>';
				
			echo '</form>';
			
			echo '<br />';
			
			if( is_plugin_active( 'seo-ultimate/seo-ultimate.php' ) ) {
			
				echo '<hr />';			
				echo '<br /><h1 id="import_seo_ultimate">Import from SEO Ultimate</h1>';
				echo '<p>SEO Ultimate was a great SEO plugin for Wordpress. Unfortunately this plugin is abandonware and it is highly unlikely it will ever be updated again, which might be a security concern.</p>';
				echo '<p><span style="color: #ff0000;">Warning:</span> When importing from SEO Ultimate, titles and descriptions saved with S-DEV SEO above will be overwritten.</p>';
				echo '<br />';
				
				echo '<form method="post" action="?page=sdev-seo-overview">';
					echo '<input name="import_seo_ultimate" type="submit" value=" Import Now " class="button" />';
				echo '</form>';
				echo '<br />';
				echo '<br />';
				echo '<hr />';
				echo '<br />';
				echo '<br />';
				
			}
			
			if( is_plugin_active( 'all-in-one-seo-pack/all_in_one_seo_pack.php' ) ) {
			
				echo '<hr />';			
				echo '<br /><h1 id="import_aio_seo">Import from All in One SEO</h1>';
				echo '<p>As we do not recommend that two SEO plugin is active at once we recommend that you import the titles and descriptions from All in One SEO and then uninstall the plugin.</p>';
				echo '<p><span style="color: #ff0000;">Warning:</span> When importing from All in One SEO, titles and descriptions saved with S-DEV SEO above will be overwritten.</p>';
				echo '<br />';
				
				echo '<form method="post" action="?page=sdev-seo-overview">';
					echo '<input name="import_aio_seo" type="submit" value=" Import Now " class="button" />';
				echo '</form>';
				echo '<br />';
				echo '<br />';
				echo '<hr />';
				echo '<br />';
				echo '<br />';
				
			}
			
		echo '</div>';
	}


/********************************************
*	Backend Overview Save Action
*/

	function wpse_sdevseo_overview_save() {
		
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		
		$nonce = $_POST['nonce'];
		
		if(!wp_verify_nonce($nonce, 'sdev-seo-overview')) {
			
			echo '<div class="error notice"><p><strong>S-DEV SEO Alert</strong><br />Invalid request.</p></div>';
			
			return false;
			
		}
		
		$typeArray = array('sdevseo_title','sdevseo_desc');
		
		foreach($typeArray as $type) {
			
			if ( isset ( $_POST[$type] ) && ( count($_POST[$type]) > 0 ) ) {
				
				$sdevseo_item_arr = wpse_sdevseo_recursive_sanitize_text_field($_POST[$type]); /* Sanitize array (extras.php) */
				
				foreach($sdevseo_item_arr as $item_id=>$item_value) {
					
					$item_value = sanitize_text_field(trim($item_value)); /* Sanitize extra just in case per item */
					
					if( !empty($item_value) && mb_strlen($item_value) > 0) {
						
						$response = update_post_meta( $item_id, $type, $item_value );
						
						if(!$response) {
							
							$saved_value = get_post_meta($item_id, $type, true);
							
							if($saved_value === $item_value) { /* Has no change been done? Let it go. */
								continue;
							}
							
							$failed_arr[] = 'Item '.$item_id.' failed to save '.$type.'. Value was "'.$item_value.'"';
							
						}
						
					} else {
						
						delete_post_meta( $item_id, $type );
						
					}
					
				}
				
			}
			
		}
		
		if(isset($failed_arr)) {
			
			$response_string = implode('<br />',$failed_arr);
			
			echo '<div class="error notice"><p>'.$response_string.'</p></div>';
			
		} else {
			
			echo '<div class="updated"><p><strong>S-DEV SEO Notice</strong><br />Update was successful.</p></div>';
			
		}
		
		
	}

/********************************************
*	Backend Settings Page
*/

	function wpse_sdevseo_settings() {
		
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		
		if(isset($_POST['wpse_sdevseo_settings_do_save'])) { /* Only check isset, no sanitation needed */
			
			wpse_sdevseo_settings_save();
			
		}
		
		$wpse_sdevseo_settings = get_option('wpse_sdevseo_settings');
		
		echo '<div class="wrap sdev-seo-settings">';
			
			echo '<h1 class="wp-heading-inline">S-DEV SEO - Settings</h1>';
			
			echo '<form method="post">';
				
				echo '<input type="hidden" name="nonce" value="'.wp_create_nonce('sdev-seo-settings').'" />';
				
				echo '<div class="wpse-sdevseo-table">';
					
					echo '<div class="wpse-sdevseo-table-row table-header">';
						
						echo '<div class="wpse-sdevseo-table-cell">';
							echo 'Setting';
						echo '</div>';
						
						echo '<div class="wpse-sdevseo-table-cell">';
							echo 'Option';
						echo '</div>';
					
					echo '</div>';
			
					echo '<div class="wpse-sdevseo-table-row">';
						
						echo '<div class="wpse-sdevseo-table-cell">';
							
							echo 'Automatically add blogname last in titles <sup>1</sup>';
							
						echo '</div>';
						
						echo '<div class="wpse-sdevseo-table-cell">';
							
							echo '<input type="checkbox" name="enable_autoadd_blogname" value="1"';
							
							if( (int) $wpse_sdevseo_settings['enable_autoadd_blogname'] === 1 ) {
								echo ' checked="checked"';
							}
							
							echo ' />';
						
						echo '</div>';
						
					echo '</div>';
					
					echo '<div class="wpse-sdevseo-table-row">';
						
						echo '<div class="wpse-sdevseo-table-cell">';
							
							echo 'Separator between title and blogname';
							
						echo '</div>';
						
						echo '<div class="wpse-sdevseo-table-cell">';
							
							echo '<select name="separator_character">';
								
								$available_separators = array(
								
									array('&#124;','&#124; (Pipeline)'),
									array('&mdash;','- (EM Dash)'),
									array('&raquo;','&raquo; (Right arrow)'),
									array('&laquo;','&laquo; (Left arrow)')
									
								);
							
								foreach($available_separators as $separator) {
									
									echo '<option value="'.$separator[0].'"';
									
									if($separator[0] === htmlentities($wpse_sdevseo_settings['separator_character'])) {
										echo ' selected="selected"';
									}
									
									echo '>' , $separator[1] . '</option>';
									
								}
								
							echo '</select>';
						
						echo '</div>';
						
					echo '</div>';
			
					echo '<div class="wpse-sdevseo-table-row">';
						
						echo '<div class="wpse-sdevseo-table-cell">';
							
							echo 'Enable Opengraph tags in header';
							
						echo '</div>';
						
						echo '<div class="wpse-sdevseo-table-cell">';
							
							echo '<input type="checkbox" name="enable_og" value="1"';

							if( (int)$wpse_sdevseo_settings['enable_og'] === 1 ) {
								echo ' checked="checked"';
							}

							echo ' />';
						
						echo '</div>';
						
					echo '</div>';
					
					echo '<div class="wpse-sdevseo-table-row">';
						
						echo '<div class="wpse-sdevseo-table-cell">';
							
							echo 'Enable Meta Descriptions';
							
						echo '</div>';
						
						echo '<div class="wpse-sdevseo-table-cell">';
							
							echo '<input type="checkbox" name="enable_meta_desc" value="1"';

							if( (int)$wpse_sdevseo_settings['enable_meta_desc'] === 1 ) {
								echo ' checked="checked"';
							}

							echo ' />';
						
						echo '</div>';
						
					echo '</div>';
					
					echo '<div class="wpse-sdevseo-table-row table-footer">';

						echo '<div class="wpse-sdevseo-table-cell"></div>';
						echo '<div class="wpse-sdevseo-table-cell action-cell">';
							echo '<input type="submit" class="button wpse-sdevseo-settings-submit-btn" name="wpse_sdevseo_settings_do_save" value="Save" />';
						echo '</div>';
					
					echo '</div>';
					
				echo '</div>';
				
				echo '<p>';
					
					echo '<sup>1</sup> - Is only added to pages / posts where the title has not been manually set.';
					
				echo '</p>';
				
			echo '</form>';
			
		echo '</div>';
		
	}


/********************************************
*	Backend Settings Save Action
*/
	
	function wpse_sdevseo_settings_save() {
		
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		
		$nonce = $_POST['nonce'];
		
		if(!wp_verify_nonce($nonce, 'sdev-seo-settings')) {
			
			echo '<div class="error notice"><p><strong>S-DEV SEO Alert</strong><br />Invalid request.</p></div>';
			
			return false;
			
		}
		
		$wpse_sdevseo_settings = get_option('wpse_sdevseo_settings');
		
		$updated_options = $wpse_sdevseo_settings;

		foreach($updated_options as $option_name=>$option_value) {
			
			if(!isset($_POST[$option_name])) {
				$updated_options[$option_name] = 0;
			} else {
				$updated_options[$option_name] = sanitize_text_field($_POST[$option_name]); /* Sanitized */
			}
			
		}
	
		if($updated_options_response = update_option('wpse_sdevseo_settings', $updated_options)) {
			
			echo '<div class="updated notice"><p><strong>S-DEV SEO Notice</strong><br />Saving settings was successful.</p></div>';
			
		} else {
			
			if(json_encode($wpse_sdevseo_settings) === json_encode($updated_options)) { /* No changes made, send success anyways */
				
				echo '<div class="updated notice"><p><strong>S-DEV SEO Notice</strong><br />Saving settings was successful.</p></div>';
				
			} else {
				
				echo '<div class="error notice"><p><strong>S-DEV SEO Alert</strong><br />Fail during saving of settings.</p></div>';
				
			}
			
		}
		
		return;
		
	}
