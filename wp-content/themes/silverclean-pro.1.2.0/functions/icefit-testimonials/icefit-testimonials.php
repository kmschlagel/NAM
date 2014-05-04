<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 - 2014 Mathieu Sarrasin - Iceable Media
 *
 * Custom Post Type: Testimonials
 *
 */

/* ---------------- Register Testimonial post type ---------------- */

add_action( 'init', 'icefit_testimonial_create_post_type' );
function icefit_testimonial_create_post_type() {
	register_post_type( 'icf_testimonial',
		array( 'labels' => array( 'name' => __( 'Testimonials', 'icefit' ),
					'singular_name' => __( 'Testimonial', 'icefit' ),
					 'menu_name' => __( 'Testimonials', 'icefit' ),
					 'all_items' => __( 'All Testimonials', 'icefit' ),
					 'add_new' => __( 'Add New', 'icefit' ),
					 'add_new_item' => __('Add New Testimonial', 'icefit'),
					 'edit_item' => __( 'Edit Testimonial', 'icefit' ),
					 'new_item' => __( 'New Testimonial', 'icefit' ),
					 'view_item' => __( 'View Testimonial', 'icefit' ),
					 'items_archive' => __( 'Testimonials Archive', 'icefit' ),
					 'search_items' => __( 'Search Testimonials', 'icefit' ),
					 'not_found' => __( 'No Testimonial Found', 'icefit' ),
					 'not_found_in_trash' => __( 'No Testimonial found in Trash', 'icefit' ),
					),
			'public' => true,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'has_archive' => false,
			'supports' => array( 'title', 'thumbnail' )
			)
		);
}

/* --------------- Customize Testimonials Admin Menu ---------------- */

// Add columns 
function icefit_testimonial_change_columns( $cols ) {
  $cols = array(
    'cb'       => '<input type="checkbox" />',
    'title'      => __( 'Title', 'trans' ),
    'testimonial_thumb'      => __( 'Thumbnail', 'trans' ),    
    'testimonial_client'      => __( 'Client', 'trans' ),    
    'testimonial_position'      => __( 'Position', 'trans' ),    
    'testimonial_company'     => __( 'Company', 'trans' ),
    'testimonial_url'     => __( 'URL', 'trans' ),
    'testimonial_content'     => __( 'Testimonial', 'trans' ),    
    'date'     => __( 'Date', 'trans' ),    
  );
  return $cols;
}
add_filter( "manage_icf_testimonial_posts_columns", "icefit_testimonial_change_columns" );

// Populate columns
function icefit_testimonial_custom_columns( $column, $post_id ) {
  switch ( $column ) {
    case "testimonial_thumb":
      echo get_the_post_thumbnail( $post_id, array(50,50) ); break;

    case "testimonial_client":
      echo get_post_meta( $post_id, 'icf_testimonial_client', true); break;
    case "testimonial_position":
      echo get_post_meta( $post_id, 'icf_testimonial_position', true); break;
    case "testimonial_company":
      echo get_post_meta( $post_id, 'icf_testimonial_company', true); break;
    case "testimonial_url":
      echo get_post_meta( $post_id, 'icf_testimonial_url', true); break;
    case "testimonial_content":
      echo get_post_meta( $post_id, 'icf_testimonial_content', true); break;
  }
}
add_action( "manage_posts_custom_column", "icefit_testimonial_custom_columns", 10, 2 );

/* ---------------- Add Metabox to Testimonials  ---------------- */

function icefit_testimonial_metabox_settings() {
	$prefix = 'icf_testimonial_';
	$meta_box_settings = array('id' => 'icf-testimonial-meta-box',
		'title' => __('Testimonial', 'icefit'),
		'page' => 'icf_testimonial',
		'context' => 'normal',
		'priority' => 'default',
		'fields' => array(
			array(
				'name' => __('Client', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'client',
				'type' => 'text',
				'std' => '' ),
			array(
				'name' => __('Position', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'position',
				'type' => 'text',
				'std' => '' ),
			array(
				'name' => __('Company', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'company',
				'type' => 'text',
				'std' => '' ),
			array(
				'name' => __('URL', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'url',
				'type' => 'text',
				'std' => '' ),
			array(
				'name' => __('Content', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'content',
				'type' => 'textarea',
				'std' => '' ),
			)
	);
	return $meta_box_settings;
}

// Add meta box
add_action('admin_menu', 'icefit_testimonial_add_box');
function icefit_testimonial_add_box() {
	$meta_box_settings = icefit_testimonial_metabox_settings();
	add_meta_box($meta_box_settings['id'], $meta_box_settings['title'], 'icefit_testimonial_show_box', $meta_box_settings['page'], $meta_box_settings['context'], $meta_box_settings['priority']);
}

// Callback function to show fields in meta box
function icefit_testimonial_show_box() {
	$meta_box_settings = icefit_testimonial_metabox_settings();
	global $post;
	// Use nonce for verification
	echo '<input type="hidden" name="testimonial_meta_box_nonce" value="', wp_create_nonce('testimonial_meta_box_nonce'), '" />';
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
add_action('save_post', 'icefit_testimonial_save_data');
function icefit_testimonial_save_data($post_id) {
	
	$meta_box_settings = icefit_testimonial_metabox_settings();
	
	// verify nonce
	if(!isset($_POST['testimonial_meta_box_nonce'])) return;
	if (!wp_verify_nonce($_POST['testimonial_meta_box_nonce'], 'testimonial_meta_box_nonce')) {
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

/* ---------------- Testimonial Shortcode ---------------- */

function icefit_testimonial_shortcode($atts) {
	extract( shortcode_atts( array( 'title' => 'Testimonials', 'col' => 'one-fourth', 'auto' => 'false' ), $atts ) );

	$col_options = array('one-fourth', 'one-third', 'one-half', 'two-thirds', 'three-fourths', 'full-width');
	if (!in_array($col, $col_options)) $col = 'one-fourth';
	$auto = ($auto == 'false') ? "" : " auto";

	$args = array( 'post_type' => 'icf_testimonial', 'orderby' => 'rand', 'posts_per_page' => -1 );

	$loop = new WP_Query( $args );
	if($loop->have_posts()) :
	$output = '<div class="'.$col.' caroufredsel-wrap testimonials-wrap"><h3>'.$title.'</h3><div class="caroufredsel'.$auto.'">';

	while( $loop->have_posts() ) : $loop->the_post();
	
		$post_id = get_the_id();
		$output .= '<div class="testimonial">';
		$output .= '<p>'. __( get_post_meta(get_the_id(), 'icf_testimonial_content', true) ).'</p>';	
		$output .= '<div class="testimonial-meta">';

		$output .=  '<div class="testimonial-author-thumb">';
		$output .= get_the_post_thumbnail( $post_id, array(50,50) );
		$output .= '</div>';

		$output .= '<div class="testimonial-author">';
		$output .= '<span class="testimonial-author-name">'. __( get_post_meta( $post_id, 'icf_testimonial_client', true) ).'</span>';
		$output .= '<span class="testimonial-author-position">'. __( get_post_meta( $post_id, 'icf_testimonial_position', true) ).'</span>';
		$output .= '<span class="testimonial-author-company">';
		$output .= '<a href="'. __( get_post_meta( $post_id, 'icf_testimonial_url', true) ).'">';
		$output .= __( get_post_meta( $post_id, 'icf_testimonial_company', true) ).'</a></span>';
		$output .= '</div>';

		$output .=  '</div></div>';
		
	endwhile;
	$output .=  '</div><a class="prev"></a><a class="next"></a></div>';
	endif;
	wp_reset_postdata();
	return $output;
}
add_shortcode('testimonials', 'icefit_testimonial_shortcode');

/* ---------------- Testimonials Widget ---------------- */

class icefittestimonialsWidget extends WP_Widget
{
  function icefittestimonialsWidget()
  {
    $widget_ops = array('classname' => 'icefittestimonialsWidget', 'description' => __('Displays Testimonials', 'icefit') );
    $this->WP_Widget('icefittestimonialsWidget', __('IceFit Testimonials', 'icefit'), $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => __('Testimonials', 'icefit') ) );
    $title = $instance['title'];
    $auto = (isset($instance['auto'])) ? $instance['auto'] : "";
?>
<p><label for="<?php echo $this->get_field_id('title'); ?>">
<?php _e('Title', 'icefit'); ?>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
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
    $instance['auto'] = $new_instance['auto'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
    $auto = ( empty($instance['auto']) || 'false' == $instance['auto']) ? '' : ' auto';
 	
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE    
	$args = array( 'post_type' => 'icf_testimonial', 'orderby' => 'rand', 'posts_per_page' => -1 );

	$loop = new WP_Query( $args );
	if($loop->have_posts()) :

		echo '<div class="caroufredsel-wrap testimonials-wrap"><div class="caroufredsel'.$auto.'">';

		while( $loop->have_posts() ) : $loop->the_post();

			$output = '<div class="testimonials-widget-item">';

			$post_id = get_the_id();
			$output .= '<div class="testimonial">';
			$output .= '<p>'.__( get_post_meta(get_the_id(), 'icf_testimonial_content', true) ).'</p>';	
			$output .= '<div class="testimonial-meta">';
			$output .=  '<div class="testimonial-author-thumb">';
			$output .= get_the_post_thumbnail( $post_id, array(50,50) );
			$output .= '</div>';
			$output .= '<div class="testimonial-author">';
			$output .= '<span class="testimonial-author-name">'. __( get_post_meta( $post_id, 'icf_testimonial_client', true) ).'</span>';
			$output .= '<span class="testimonial-author-position">'. __( get_post_meta( $post_id, 'icf_testimonial_position', true) ).'</span>';
			$output .= '<span class="testimonial-author-company">';
			$output .= '<a href="'. __( get_post_meta( $post_id, 'icf_testimonial_url', true) ).'">';
			$output .= __( get_post_meta( $post_id, 'icf_testimonial_company', true) ).'</a></span>';
			$output .= '</div></div></div>';
			echo $output;

			echo "</div>";
		endwhile;
		echo '</div><a class="prev"></a><a class="next"></a></div>';
	endif; 
	wp_reset_query();
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("icefittestimonialsWidget");') );

/* ---------------- Admin Icon ---------------- */

global $wp_version;
if ( $wp_version >= 3.8 ): // If WP 3.8 or later: use dashicons
	add_action( 'admin_head', 'testimonials_post_type_dashicon' );
else:
	add_action( 'admin_head', 'testimonials_post_type_icon' );
endif;

function testimonials_post_type_dashicon() {
	echo '<style>#adminmenu #menu-posts-icf_testimonial div.wp-menu-image:before{content:"\f328"}</style>';	
}

function testimonials_post_type_icon() {
    ?>
    <style>
        /* Admin Menu - 16px */
        #menu-posts-icf_testimonial .wp-menu-image {
            background: url(<?php echo get_template_directory_uri(); ?>/functions/icefit-testimonials/testimonials-icon16-sprite.png) no-repeat 6px 6px !important;
        }
		#menu-posts-icf_testimonial:hover .wp-menu-image, #menu-posts-icf_testimonial.wp-has-current-submenu .wp-menu-image {
            background-position: 6px -26px !important;
        }
        /* Post Screen - 32px */
        .icon32-posts-icf_testimonial {
        	background: url(<?php echo get_template_directory_uri(); ?>/functions/icefit-testimonials/testimonials-icon16-sprite_2x.png) no-repeat left top !important;
        }
        @media
        only screen and (-webkit-min-device-pixel-ratio: 1.5),
        only screen and (   min--moz-device-pixel-ratio: 1.5),
        only screen and (     -o-min-device-pixel-ratio: 3/2),
        only screen and (        min-device-pixel-ratio: 1.5),
        only screen and (        		min-resolution: 1.5dppx) {
        	
        	/* Admin Menu - 16px @2x */
        	#menu-posts-icf_testimonial .wp-menu-image {
        		background-image: url('<?php echo get_template_directory_uri(); ?>/functions/icefit-testimonials/testimonials-icon32.png') !important;
        		-webkit-background-size: 16px 48px;
        		-moz-background-size: 16px 48px;
        		background-size: 16px 48px;
        	}
        	/* Post Screen - 32px @2x */
        	.icon32-posts-icf_testimonial {
        		background-image: url('<?php echo get_template_directory_uri(); ?>/functions/icefit-testimonials/testimonials-icon32_2x.png') !important;
        		-webkit-background-size: 32px 32px;
        		-moz-background-size: 32px 32px;
        		background-size: 32px 32px;
        	}         
        }
    </style>
<?php } ?>