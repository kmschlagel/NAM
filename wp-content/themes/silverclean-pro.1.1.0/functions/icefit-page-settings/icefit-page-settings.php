<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 Mathieu Sarrasin - Iceable Media
 *
 * Page Settings Metabox
 *
 */

/* ------ Add Page Settings Metabox to Page editor ----- */

function icefit_pagesettings_metabox_settings() {

	global $icefit_options;

	/* Prepare sidebar selector options */
	$icefit_unlimited_sidebars = $icefit_options['unlimited_sidebar'];
	$icefit_sidebars_list = explode("\n", $icefit_unlimited_sidebars);
	$sidebar_options[] = array('name' => __('Default Sidebar', 'icefit'), 'value' => 'sidebar');
	foreach ($icefit_sidebars_list as $additional_sidebar) {
		if ($additional_sidebar != "") {
			$sidebar_options[] = array(
				'name' => $additional_sidebar,
				'value' => sanitize_title($additional_sidebar)
				);
		}
	}

	/* Prepare footer selector options */
	$additional_footer_sidebars = $icefit_options['additional_footer_sidebars'];
	$icefit_footers_list = explode("\n", $additional_footer_sidebars);
	$footer_options[] = array('name' => __('Default Footer', 'icefit'), 'value' => 'footer-sidebar');
	$footer_options[] = array('name' => __('No Widgetized Footer', 'icefit'), 'value' => 'no-footer-widget');
	foreach ($icefit_footers_list as $additional_footer) {
		if ($additional_footer != "") {
			$footer_options[] = array(
				'name' => $additional_footer,
				'value' => sanitize_title($additional_footer)
				);
		}
	}

	/* Prepare slider category selector options */
    $cats = get_terms('icf-slides-category');
  	$slides_cat[] = array('name' => __('All Slides', 'icefit'), 'value' => 'all');
  	foreach($cats as $cat):
  		$slides_cat[] = array(
					'name' => $cat->name,
					'value' => $cat->slug,
					);
	endforeach;

	$prefix = 'icefit_pagesettings_';
	
	$meta_box_settings = array(
		'id' => 'sidebars-meta-box',
		'title' => __('Icefit Page Settings', 'icefit'),
		'page' => 'page',
		'context' => 'side',
		'priority' => 'default',
		'fields' => array(
			array(
				'name' => __('Sticky Header', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'sticky_header',
				'type' => 'select',				
				'options' => array(
								array('name' => __('Use Global Setting', 'icefit'), 'value' => 'Use Global Setting'),
								array('name' => __('Normal Header Only', 'icefit'), 'value' => 'Normal Header Only'),
								array('name' => __('Sticky Header on scroll', 'icefit'), 'value' => 'Sticky Header on scroll'),
								array('name' => __('Sticky Navbar on scroll', 'icefit'), 'value' => 'Sticky Navbar on scroll'),
								array('name' => __('Sticky Navbar on scroll + Logo', 'icefit'), 'value' => 'Sticky Navbar on scroll + Logo' ),
								array('name' => __('Sticky Header Only', 'icefit'), 'value' => 'Sticky Header Only'),
								array('name' => __('Sticky Navbar Only', 'icefit'), 'value' => 'Sticky Navbar Only'),
								array('name' => __('Sticky Navbar Only + Logo', 'icefit'), 'value' => 'Sticky Navbar Only + Logo'),
								array('name' => __('No Header', 'icefit'), 'value' => 'No Header'),
							),
				),
			array(
				'name' => __('Sidebar Side', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'sidebar_side',
				'type' => 'select',
				'options' => array(
								array('name' => __('None', 'icefit'), 'value' => 'none'),
								array('name' => __('Right', 'icefit'), 'value' => 'right'),
								array('name' => __('Left', 'icefit'), 'value' => 'left'),
							),
				),
			array(
				'name' => __('Select Sidebar', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'sidebar',
				'type' => 'select',
				'options' => $sidebar_options,
				),
			array(
				'name' => __('Slider', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'slider',
				'type' => 'select',
				'options' => array(
								array('name' => __('Off', 'icefit'), 'value' => 'off'),
								array('name' => __('On', 'icefit'), 'value' => 'on'),
							),
				),
			array(
				'name' => __('Slides Category', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'slides_cat',
				'type' => 'select',
				'options' => $slides_cat,
				),
			array(
				'name' => __('Show Page Title', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'showtitle',
				'type' => 'select',
				'options' => array(
								array('name' => __('Yes', 'icefit'), 'value' => 'yes'),
								array('name' => __('No', 'icefit'), 'value' => 'no'),
							),
				),
			array(
				'name' => __('Select Footer', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'footer',
				'type' => 'select',
				'options' => $footer_options,
				),
		),
	);
	return $meta_box_settings;
}

// Add meta box
add_action('admin_menu', 'icefit_pagesettings_add_box');
function icefit_pagesettings_add_box() {
	$meta_box_settings = icefit_pagesettings_metabox_settings();
	add_meta_box(
		$meta_box_settings['id'],
		$meta_box_settings['title'],
		'icefit_pagesettings_show_box',
		$meta_box_settings['page'],
		$meta_box_settings['context'],
		$meta_box_settings['priority']
	);
}

// Callback function to show fields in meta box
function icefit_pagesettings_show_box() {
	$meta_box_settings = icefit_pagesettings_metabox_settings();
	global $post;
	// Use nonce for verification
	echo '<input type="hidden" name="sidebars_meta_box_nonce" value="', wp_create_nonce('sidebars_meta_box_nonce'), '" />';
	echo '<table class="form-table">';
	foreach ($meta_box_settings['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
		switch ($field['type']) {
			case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />',
					'<br />', $field['desc'];
				break;
			case 'textarea':
				echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>',
					'<br />', $field['desc'];
				break;
			case 'select':
				echo '<select name="', $field['id'], '" id="', $field['id'], '">';
				foreach ($field['options'] as $option) {
					echo '<option value="', $option['value'],'"', $meta == $option['value'] ? ' selected="selected"' : '', '>', $option['name'], '</option>';
				}
				echo '</select>';
				break;
			case 'radio':
				foreach ($field['options'] as $option) {
					echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
				}
				break;
			case 'checkbox':
				echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
				break;
		}
		echo 	'</td></tr>';
	}
	
	echo '</table>';
}

// Save data from meta box
add_action('save_post', 'icefit_pagesettings_save_data');
function icefit_pagesettings_save_data($post_id) {
	
	$meta_box_settings = icefit_pagesettings_metabox_settings();
	
	// verify nonce
	if(!isset($_POST['sidebars_meta_box_nonce'])) return;
	if (!wp_verify_nonce($_POST['sidebars_meta_box_nonce'], 'sidebars_meta_box_nonce')) {
		return $post_id;
	}
	
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
	
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	foreach ($meta_box_settings['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}
?>