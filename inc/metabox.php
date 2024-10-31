<?php

/********************************************
*	Backend Metabox add
*/

	function wpse_sdevseo_metabox()
	{
		global $post;

		if ( empty ( $post ) || (get_post_type( $GLOBALS['post'] ) !== 'post' && get_post_type( $GLOBALS['post'] ) !== 'page' ) )
			return;

		if ( ! $sdevseo_title = get_post_meta( $post->ID, 'sdevseo_title', TRUE ) ) {
			$sdevseo_title = '';
			$sdevseo_title_count = 0;
		} else {
			$sdevseo_title_count = mb_strlen($sdevseo_title);
			$sdevseo_title_attr = ' style="display: none;"';
		}
		
		if ( ! $sdevseo_desc = get_post_meta( $post->ID, 'sdevseo_desc', TRUE ) ) {
			$sdevseo_desc = '';
			$sdevseo_desc_count = 0;
		} else {
			$sdevseo_desc_count = mb_strlen($sdevseo_desc);
			$sdevseo_desc_attr = ' style="display: none;"';
		}

		echo '<div class="sdevseo-container">';
		
			echo '<div class="sdevseo-form-content">';
			
				echo '<p class="sdevseo-input-header">Title Tag</p>';
				echo '<p class="sdevseo-input-container">';
					echo '<label class="label-on-top" data-for="sdevseo_title"'.$sdevseo_title_attr.'>SEO Title</label>';
					echo '<input type="text" name="sdevseo_title" id="sdevseo_title" value="'.$sdevseo_title.'" class="sdevseo-input" title="Title Tag" spellcheck="true" autocomplete="off" />';
					echo '<span id="charnum">Characters: '.$sdevseo_title_count.'</span>';
				echo '</p>';
				
				echo '<p class="sdevseo-input-header">Meta Description Tag</p>';
				echo '<p class="sdevseo-input-container">';
					echo '<label class="label-on-top" data-for="sdevseo_desc"'.$sdevseo_desc_attr.'>SEO Description</label>';
					echo '<textarea name="sdevseo_desc" id="sdevseo_desc" class="sdevseo-textarea" spellcheck="true" autocomplete="off" title="Meta Description Tag">'.$sdevseo_desc.'</textarea>';
					echo '<span id="charnum">Characters: '.$sdevseo_desc_count.' / 155</span>';
				echo '</p>';
				
			echo '</div>';
			
		echo '</div>';
		
	}

	
	function wpse_sdevseo_add_metabox() {
		
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		
		add_meta_box('wpse-sdevseo-metabox', 'S-DEV SEO', 'wpse_sdevseo_metabox', array('post','page'), 'normal', 'low', null);
	}
	
	add_action( 'add_meta_boxes', 'wpse_sdevseo_add_metabox');

/********************************************
*	Save the title and desc as post meta on create/update
*/

	function wpse_sdevseo_save_metabox( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;


		if ( isset ( $_POST['sdevseo_title'] ) ) {
			
			$sdevseo_title = sanitize_text_field($_POST['sdevseo_title']); /* Sanitize some */
			
			if ( mb_strlen($sdevseo_title) > 0 ) {
				update_post_meta( $post_id, 'sdevseo_title', $sdevseo_title );
			} else {
				delete_post_meta( $post_id, 'sdevseo_title' );
			}
		
		}
		
		if ( isset ( $_POST['sdevseo_desc'] ) ) {
			
			$sdevseo_desc = sanitize_text_field($_POST['sdevseo_desc']); /* Sanitize some */
			
			if ( mb_strlen($sdevseo_desc) > 0 ) {
				update_post_meta( $post_id, 'sdevseo_desc', $sdevseo_desc );
			} else {
				delete_post_meta( $post_id, 'sdevseo_desc' );
			}
		
		}
		
	}

	add_action( 'save_post', 'wpse_sdevseo_save_metabox' );