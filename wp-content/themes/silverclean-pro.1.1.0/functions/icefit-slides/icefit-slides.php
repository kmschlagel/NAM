<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 Mathieu Sarrasin - Iceable Media
 *
 * Custom Post Type: Slides
 *
 */

/* ---------------- Register Slides post type ---------------- */

add_action( 'init', 'icefit_slides_create_post_type' );
function icefit_slides_create_post_type() {
	register_post_type( 'icf_slides',
		array( 'labels' => array( 'name' => __( 'Slides', 'icefit' ),
					'singular_name' => __( 'Slide', 'icefit' ),
					 'menu_name' => __( 'Slides', 'icefit' ),
					 'all_items' => __( 'All Slides', 'icefit' ),
					 'add_new' => __( 'Add New', 'icefit' ),
					 'add_new_item' => __('Add New Slide', 'icefit'),
					 'edit_item' => __( 'Edit Slide', 'icefit' ),
					 'new_item' => __( 'New Slide', 'icefit' ),
					 'view_item' => __( 'View Slide', 'icefit' ),
					 'items_archive' => __( 'Slides Archive', 'icefit' ),
					 'search_items' => __( 'Search Slides', 'icefit' ),
					 'not_found' => __( 'No Slide Found', 'icefit' ),
					 'not_found_in_trash' => __( 'No Slide found in Trash', 'icefit' ),
					),
			'public' => true,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'has_archive' => false,
			'supports' => array( 'title', 'thumbnail' )
			)
		);
}

/* ---------------- Create Custom Slides Taxonomies ---------------- */

add_action( 'init', 'icefit_create_slides_taxonomies', 0 );
function icefit_create_slides_taxonomies()  {

	// Add slides category taxonomy
	$labels = array(
	'name' => _x( 'Slides Categories', 'taxonomy general name', 'icefit' ),
    'singular_name' => _x( 'Slides Category', 'taxonomy singular name', 'icefit' ),
    'search_items' =>  __( 'Search Category', 'icefit' ),
    'all_items' => __( 'All Categories', 'icefit' ),
    'parent_item' => __( 'Parent Category', 'icefit' ),
    'parent_item_colon' => __( 'Parent Category:', 'icefit' ),
    'edit_item' => __( 'Edit Category', 'icefit' ), 
    'update_item' => __( 'Update Category', 'icefit' ),
    'add_new_item' => __( 'Add New Category', 'icefit' ),
    'new_item_name' => __( 'New Category Name', 'icefit' ),
    'menu_name' => __( 'Slides Categories', 'icefit' ),
	); 	

	register_taxonomy('icf-slides-category',array('icf_slides'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'icf-slides-category' ),
  ));

}

/* --------------- Customize Slides Admin Menu ---------------- */

// Add columns 
function icefit_slides_change_columns( $cols ) {
  $cols = array(
    'cb'       => '<input type="checkbox" />',
    'title'      => __( 'Title', 'trans' ),
    'slide_thumb'      => __( 'Thumbnail', 'trans' ),    
    'slide_link'     => __( 'URL', 'trans' ),
    'slide_cat'     => __( 'Category', 'trans' ),
  );
  return $cols;
}
add_filter( "manage_icf_slides_posts_columns", "icefit_slides_change_columns" );

// Populate columns
function icefit_slides_custom_columns( $column, $post_id ) {
  switch ( $column ) {
    case "slide_thumb":
      echo get_the_post_thumbnail( $post_id, 'medium' ); break;
    case "slide_url":
      echo get_post_meta( $post_id, 'icf_slide_url', true); break;
    case "slide_cat":
      $cats = wp_get_post_terms( $post_id, 'icf-slides-category', array('fields' => 'names') );      
      foreach($cats as $cat):
      	echo $cat."<br />";
      endforeach;
		break;
  }
}
add_action( "manage_posts_custom_column", "icefit_slides_custom_columns", 10, 2 );

// Filter the request to just give posts for the given taxonomy, if applicable.
function icefit_slides_taxonomy_filter_restrict_manage_posts() {
    global $typenow;
    if ($typenow != "icf_slides") return;
    
    $post_types = get_post_types( array( '_builtin' => false ) );

    if ( in_array( $typenow, $post_types ) ) {
    	$filters = get_object_taxonomies( $typenow );

        foreach ( $filters as $tax_slug ) {
            $tax_obj = get_taxonomy( $tax_slug );
            $selected = ( isset($_GET[$tax_slug]) ) ? $_GET[$tax_slug] : "";
            wp_dropdown_categories( array(
                'show_option_all' => 'Show All '.$tax_obj->label,
                'taxonomy' 	  => $tax_slug,
                'name' 		  => $tax_obj->name,
                'orderby' 	  => 'name',
                'selected' 	  => $selected,
                'hierarchical' 	  => $tax_obj->hierarchical,
                'show_count' 	  => false,
                'hide_empty' 	  => true
            ) );
        }
    }
}
add_action( 'restrict_manage_posts', 'icefit_slides_taxonomy_filter_restrict_manage_posts' );

function icefit_slides_taxonomy_filter_post_type_request( $query ) {
  global $pagenow, $typenow;
  if ($typenow != "icf_slides") return;
  if ( 'edit.php' == $pagenow ) {
    $filters = get_object_taxonomies( $typenow );
    foreach ( $filters as $tax_slug ) {
      $var = &$query->query_vars[$tax_slug];
      if ( isset( $var ) ) {
        $term = get_term_by( 'id', $var, $tax_slug );
        $var = $term->slug;
      }
    }
  }
}
add_filter( 'parse_query', 'icefit_slides_taxonomy_filter_post_type_request' );

/* ---------------- Add Metabox to Slides  ---------------- */

function icefit_slides_metabox_settings() {
	$prefix = 'icf_slides_';
	$meta_box_settings = array('id' => 'icf-slides-meta-box',
		'title' => __('Slide Link', 'icefit'),
		'page' => 'icf_slides',
		'context' => 'normal',
		'priority' => 'default',
		'fields' => array(
			array(
				'name' => __('Caption', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'caption',
				'type' => 'text',
				'std' => '' ),
			array(
				'name' => __('Link', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'link',
				'type' => 'text',
				'std' => '' ),
			),
	);
	return $meta_box_settings;
}

// Add meta box
add_action('admin_menu', 'icefit_slides_add_box');
function icefit_slides_add_box() {
	$meta_box_settings = icefit_slides_metabox_settings();
	add_meta_box($meta_box_settings['id'], $meta_box_settings['title'], 'icefit_slides_show_box', $meta_box_settings['page'], $meta_box_settings['context'], $meta_box_settings['priority']);
}

// Callback function to show fields in meta box
function icefit_slides_show_box() {
	$meta_box_settings = icefit_slides_metabox_settings();
	global $post;
	// Use nonce for verification
	echo '<input type="hidden" name="slides_meta_box_nonce" value="', wp_create_nonce('slides_meta_box_nonce'), '" />';
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
					echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
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
add_action('save_post', 'icefit_slides_save_data');
function icefit_slides_save_data($post_id) {
	
	$meta_box_settings = icefit_slides_metabox_settings();
	
	// verify nonce
	if(!isset($_POST['slides_meta_box_nonce'])) return;
	if (!wp_verify_nonce($_POST['slides_meta_box_nonce'], 'slides_meta_box_nonce')) {
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

/* ---------------- Admin Icon ---------------- */

add_action( 'admin_head', 'slides_post_type_icon' );

function slides_post_type_icon() {
    ?>
    <style>
        /* Admin Menu - 16px */
        #menu-posts-icf_slides .wp-menu-image {
            background: url(<?php echo get_template_directory_uri(); ?>/functions/icefit-slides/slides-icon16-sprite.png) no-repeat 6px 6px !important;
        }
		#menu-posts-icf_slides:hover .wp-menu-image, #menu-posts-icf_slides.wp-has-current-submenu .wp-menu-image {
            background-position: 6px -26px !important;
        }
        /* Post Screen - 32px */
        .icon32-posts-icf_slides {
        	background: url(<?php echo get_template_directory_uri(); ?>/functions/icefit-slides/slides-icon16-sprite_2x.png) no-repeat left top !important;
        }
        @media
        only screen and (-webkit-min-device-pixel-ratio: 1.5),
        only screen and (   min--moz-device-pixel-ratio: 1.5),
        only screen and (     -o-min-device-pixel-ratio: 3/2),
        only screen and (        min-device-pixel-ratio: 1.5),
        only screen and (        		min-resolution: 1.5dppx) {
        	
        	/* Admin Menu - 16px @2x */
        	#menu-posts-icf_slides .wp-menu-image {
        		background-image: url('<?php echo get_template_directory_uri(); ?>/functions/icefit-slides/slides-icon32.png') !important;
        		-webkit-background-size: 16px 48px;
        		-moz-background-size: 16px 48px;
        		background-size: 16px 48px;
        	}
        	/* Post Screen - 32px @2x */
        	.icon32-posts-icf_slides {
        		background-image: url('<?php echo get_template_directory_uri(); ?>/functions/icefit-slides/slides-icon32_2x.png') !important;
        		-webkit-background-size: 32px 32px;
        		-moz-background-size: 32px 32px;
        		background-size: 32px 32px;
        	}         
        }
    </style>
<?php }
?>