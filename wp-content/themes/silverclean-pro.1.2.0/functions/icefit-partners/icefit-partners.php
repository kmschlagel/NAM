<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 - 2014 Mathieu Sarrasin - Iceable Media
 *
 * Custom Post Type: Partners
 *
 */

/* ---------------- Register Partners post type ---------------- */

add_action( 'init', 'icefit_partners_create_post_type' );
function icefit_partners_create_post_type() {
	register_post_type( 'icf_partners',
		array( 'labels' => array( 'name' => __( 'Partners/Clients', 'icefit' ),
					'singular_name' => __( 'Partner/Client', 'icefit' ),
					 'menu_name' => __( 'Partners/Clients', 'icefit' ),
					 'all_items' => __( 'All Partners/Clients', 'icefit' ),
					 'add_new' => __( 'Add New', 'icefit' ),
					 'add_new_item' => __('Add New Partner/Client', 'icefit'),
					 'edit_item' => __( 'Edit Partner/Client', 'icefit' ),
					 'new_item' => __( 'New Partner/Client', 'icefit' ),
					 'view_item' => __( 'View Partner/Client', 'icefit' ),
					 'items_archive' => __( 'Partners/Clients Archive', 'icefit' ),
					 'search_items' => __( 'Search Partners/Clients', 'icefit' ),
					 'not_found' => __( 'No Partner/Client Found', 'icefit' ),
					 'not_found_in_trash' => __( 'No Partner/Client found in Trash', 'icefit' ),
					),
			'public' => true,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'has_archive' => false,
			'supports' => array( 'title', 'thumbnail' )
			)
		);
}

/* ---------------- Create Custom Partners Taxonomies ---------------- */

add_action( 'init', 'icefit_create_partners_taxonomies', 0 );
function icefit_create_partners_taxonomies()  {

	// Add partners category taxonomy (hierarchical)
	$labels = array(
	'name' => _x( 'Partners/Clients Categories', 'taxonomy general name', 'icefit' ),
    'singular_name' => _x( 'Partners/Clients Category', 'taxonomy singular name', 'icefit' ),
    'search_items' =>  __( 'Search Category', 'icefit' ),
    'all_items' => __( 'All Categories', 'icefit' ),
    'parent_item' => __( 'Parent Category', 'icefit' ),
    'parent_item_colon' => __( 'Parent Category:', 'icefit' ),
    'edit_item' => __( 'Edit Category', 'icefit' ), 
    'update_item' => __( 'Update Category', 'icefit' ),
    'add_new_item' => __( 'Add New Category', 'icefit' ),
    'new_item_name' => __( 'New Category Name', 'icefit' ),
    'menu_name' => __( 'Partners/Clients Categories', 'icefit' ),
	); 	

	register_taxonomy('icf-partners-category',array('icf_partners'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'icf-partners-category' ),
  ));

}

/* --------------- Customize Partners Admin Menu ---------------- */

// Add columns 
function icefit_partners_change_columns( $cols ) {
  $cols = array(
    'cb'       => '<input type="checkbox" />',
    'title'      => __( 'Title', 'trans' ),
    'partner_thumb'      => __( 'Logo', 'trans' ),    
    'partner_url'     => __( 'URL', 'trans' ),
    'partner_cat'	=> __('Category', 'trans'),
    'date'     => __( 'Date', 'trans' ),    
  );
  return $cols;
}
add_filter( "manage_icf_partners_posts_columns", "icefit_partners_change_columns" );

// Populate columns
function icefit_partners_custom_columns( $column, $post_id ) {
  switch ( $column ) {
    case "partner_thumb":
      echo get_the_post_thumbnail( $post_id, array(100,100) ); break;
    case "partner_url":
      echo get_post_meta( $post_id, 'icf_partners_url', true); break;
    case "partner_cat":
    	$cats = wp_get_post_terms( $post_id, 'icf-partners-category', array('fields' => 'names') );      
    	foreach($cats as $cat):
    		echo $cat."<br />";
    	endforeach;
	break;
  }
}
add_action( "manage_posts_custom_column", "icefit_partners_custom_columns", 10, 2 );

/* ---------------- Add metabox to Partners  ---------------- */

function icefit_partners_metabox_settings() {
	$prefix = 'icf_partners_';
	$meta_box_settings = array('id' => 'icf-partners-meta-box',
		'title'		=> __('Partner', 'icefit'),
		'page' => 'icf_partners',
		'context' => 'normal',
		'priority' => 'default',
		'fields' => array(
						array(
							'name'	=> __('URL', 'icefit'),
							'desc' => '',
							'id' => $prefix . 'url',
							'type' => 'text',
							'std' => '' ),
						)
	);
	return $meta_box_settings;
}

// Add meta box
add_action('admin_menu', 'icefit_partners_add_box');
function icefit_partners_add_box() {
	$meta_box_settings = icefit_partners_metabox_settings();
	add_meta_box($meta_box_settings['id'], $meta_box_settings['title'], 'icefit_partners_show_box', $meta_box_settings['page'], $meta_box_settings['context'], $meta_box_settings['priority']);
}

// Callback function to show fields in meta box
function icefit_partners_show_box() {
	$meta_box_settings = icefit_partners_metabox_settings();
	global $post;
	// Use nonce for verification
	echo '<input type="hidden" name="partners_meta_box_nonce" value="', wp_create_nonce('partners_meta_box_nonce'), '" />';
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
add_action('save_post', 'icefit_partners_save_data');
function icefit_partners_save_data($post_id) {
	
	$meta_box_settings = icefit_partners_metabox_settings();
	
	// verify nonce
	if(!isset($_POST['partners_meta_box_nonce'])) return;
	if (!wp_verify_nonce($_POST['partners_meta_box_nonce'], 'partners_meta_box_nonce')) {
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

/* ---------------- Partners Shortcode ---------------- */

function icefit_partners_shortcode($atts) {
	extract( shortcode_atts( array( 'cat' => 'all', 'col' => 'one-fourth', 'title' => '', 'auto' => 'false' ), $atts ) );

	$col_options = array('one-fourth', 'one-third', 'one-half', 'two-thirds', 'three-fourths', 'full-width');
	if (!in_array($col, $col_options)) $col = 'one-fourth';
	$auto = ($auto == 'false') ? "" : " auto";

	$args = array( 'post_type' => 'icf_partners', 'posts_per_page' => -1);
	if ($cat != "all") $args['icf-partners-category'] = $cat;		
		
	$loop = new WP_Query( $args );
	if($loop->have_posts()) :

	$output = '<div class="'.$col.' caroufredsel-wrap partner-wrap">';
	if ($title) $output .= '<h3>'.$title.'</h3>';
	$output .= '<div class="caroufredsel'.$auto.'">';

	while( $loop->have_posts() ) : $loop->the_post();
		$post_id = get_the_id();
		if(has_post_thumbnail($post_id)):
			if (get_post_meta(get_the_id(), 'icf_partners_url', true)):	
				$output .= '<div class="partner-thumb">';
				$output .= '<a href="'.get_post_meta(get_the_id(), 'icf_partners_url', true).'" target="_blank">';
				$output .= get_the_post_thumbnail( $post_id, array(250,100) );
				$output .= '</a></div>';
			else:
				$output .= '<div class="partner-thumb">';
				$output .= get_the_post_thumbnail( $post_id, array(250,100) );
				$output .= '</div>';			
			endif;
		endif;		
	endwhile;
	$output .=  '</div><a class="prev"></a><a class="next"></a></div>';
	endif;
	wp_reset_postdata();
	return $output;
}
add_shortcode('partners', 'icefit_partners_shortcode');

/* ---------------- Partners Widget ---------------- */

class icefitpartnersWidget extends WP_Widget
{
  function icefitpartnersWidget()
  {
    $widget_ops = array('classname' => 'icefitpartnersWidget', 'description' => __('Displays Partners', 'icefit') );
    $this->WP_Widget('icefitpartnersWidget', __('IceFit Partners', 'icefit'), $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => __('Partners', 'icefit') ) );
    $title = $instance['title'];
    $category = (isset($instance['category'])) ? $instance['category'] : "";
    $auto = (isset($instance['auto'])) ? $instance['auto'] : "";

    $cats = get_terms('icf-partners-category');

?><p><label for="<?php echo $this->get_field_id('title'); ?>">
<?php _e('Title', 'icefit'); ?>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
</label></p>
<p><label for="<?php echo $this->get_field_id('category'); ?>">
<?php _e('Category', 'icefit'); ?>
<select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
<option value="all">All</option>
<?php foreach($cats as $cat): ?>
<option value="<?php echo $cat->slug; ?>" <?php selected( esc_attr($category), $cat->slug ); ?>><?php echo $cat->name; ?></option>
<?php endforeach; ?>
</select>
</label></p>
<p><label for="<?php echo $this->get_field_id('auto'); ?>">
<?php _e('Auto Slide', 'icefit'); ?>
<select class="widefat" id="<?php echo $this->get_field_id('auto'); ?>" name="<?php echo $this->get_field_name('auto'); ?>">
<option value="false">No</option>
<option value="true" <?php selected( esc_attr($auto), 'true' ); ?>>Yes</option>
</select>
</label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['category'] = $new_instance['category'];
    $instance['auto'] = $new_instance['auto'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
    $category = empty($instance['category']) ? $category = 'all' : $instance['category'];
    $auto = ( empty($instance['auto']) || 'false' == $instance['auto']) ? '' : ' auto';

    if (!empty($title))
      echo $before_title . $title . $after_title;
 
    // WIDGET CODE GOES HERE
	$args = array( 'post_type' => 'icf_partners', 'posts_per_page' => -1);
	if ($category != "all") $args['icf-partners-category'] = $category;

	$loop = new WP_Query( $args );
	if($loop->have_posts()) :
 
		echo '<div class="caroufredsel-wrap partners-widget"><div class="caroufredsel'.$auto.'">';
		while( $loop->have_posts() ) : $loop->the_post();
			$output = '<div class="partners-widget-item">';
			$post_id = get_the_id();
			if(has_post_thumbnail($post_id)):
				if (get_post_meta(get_the_id(), 'icf_partners_url', true)):	
				$output .= '<div class="partner-thumb">';
				$output .= '<a href="'.get_post_meta(get_the_id(), 'icf_partners_url', true).'" target="_blank">';
				$output .= get_the_post_thumbnail( $post_id, array(250,200) );
				$output .= '</a></div>';
				else:
				$output .= '<div class="partner-thumb">';
				$output .= get_the_post_thumbnail( $post_id, array(250,200) );
				$output .= '</div>';			
				endif;
			endif;	
			echo $output;
			echo "</div>";
		endwhile;
		echo '</div><a class="prev"></a><a class="next"></a></div>';
	endif; 
	wp_reset_postdata(); 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("icefitpartnersWidget");') );

/* ---------------- Admin Icon ---------------- */

global $wp_version;
if ( $wp_version >= 3.8 ): // If WP 3.8 or later: use dashicons
	add_action( 'admin_head', 'partners_post_type_dashicon' );
else:
	add_action( 'admin_head', 'partners_post_type_icon' );
endif;

function partners_post_type_dashicon() {
	echo '<style>#adminmenu #menu-posts-icf_partners div.wp-menu-image:before{content:"\f307"}</style>';	
}

function partners_post_type_icon() {
    ?>
    <style>
        /* Admin Menu - 16px */
        #menu-posts-icf_partners .wp-menu-image {
            background: url(<?php echo get_template_directory_uri(); ?>/functions/icefit-partners/partners-icon16-sprite.png) no-repeat 6px 6px !important;
        }
		#menu-posts-icf_partners:hover .wp-menu-image, #menu-posts-icf_partners.wp-has-current-submenu .wp-menu-image {
            background-position: 6px -26px !important;
        }
        /* Post Screen - 32px */
        .icon32-posts-icf_partners {
        	background: url(<?php echo get_template_directory_uri(); ?>/functions/icefit-partners/partners-icon16-sprite_2x.png) no-repeat left top !important;
        }
        @media
        only screen and (-webkit-min-device-pixel-ratio: 1.5),
        only screen and (   min--moz-device-pixel-ratio: 1.5),
        only screen and (     -o-min-device-pixel-ratio: 3/2),
        only screen and (        min-device-pixel-ratio: 1.5),
        only screen and (        		min-resolution: 1.5dppx) {
        	
        	/* Admin Menu - 16px @2x */
        	#menu-posts-icf_partners .wp-menu-image {
        		background-image: url('<?php echo get_template_directory_uri(); ?>/functions/icefit-partners/partners-icon32.png') !important;
        		-webkit-background-size: 16px 48px;
        		-moz-background-size: 16px 48px;
        		background-size: 16px 48px;
        	}
        	/* Post Screen - 32px @2x */
        	.icon32-posts-icf_partners {
        		background-image: url('<?php echo get_template_directory_uri(); ?>/functions/icefit-partners/partners-icon32_2x.png') !important;
        		-webkit-background-size: 32px 32px;
        		-moz-background-size: 32px 32px;
        		background-size: 32px 32px;
        	}         
        }
    </style>
<?php }
?>