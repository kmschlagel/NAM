<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 - 2014 Mathieu Sarrasin - Iceable Media
 *
 * Custom Post Type: Features
 *
 */

/* ---------------- Register Features post type ---------------- */

add_action( 'init', 'icefit_features_create_post_type' );
function icefit_features_create_post_type() {
	register_post_type( 'icf_features',
		array( 'labels' => array( 'name' => __( 'Features', 'icefit' ),
					'singular_name' => __( 'Feature', 'icefit' ),
					 'menu_name' => __( 'Features', 'icefit' ),
					 'all_items' => __( 'All Features', 'icefit' ),
					 'add_new' => __( 'Add New', 'icefit' ),
					 'add_new_item' => __('Add New Feature', 'icefit'),
					 'edit_item' => __( 'Edit Feature', 'icefit' ),
					 'new_item' => __( 'New Feature', 'icefit' ),
					 'view_item' => __( 'View Feature', 'icefit' ),
					 'items_archive' => __( 'Features Archive', 'icefit' ),
					 'search_items' => __( 'Search Features', 'icefit' ),
					 'not_found' => __( 'No Feature Found', 'icefit' ),
					 'not_found_in_trash' => __( 'No Feature found in Trash', 'icefit' ),
					),
			'public' => true,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'has_archive' => false,
			'supports' => array( 'title', 'thumbnail' )
			)
		);
}

/* --------------- Customize Features Admin Menu ---------------- */

// Add columns 
function icefit_features_change_columns( $cols ) {
  $cols = array(
    'cb'       => '<input type="checkbox" />',
    'title'      => __( 'Title', 'trans' ),
    'feature_thumb'      => __( 'Icon', 'trans' ),    
    'feature_url'     => __( 'URL', 'trans' ),
    'feature_content'     => __( 'Content', 'trans' ),    
    'date'     => __( 'Date', 'trans' ),    
  );
  return $cols;
}
add_filter( "manage_icf_features_posts_columns", "icefit_features_change_columns" );

// Populate columns
function icefit_features_custom_columns( $column, $post_id ) {
  switch ( $column ) {
    case "feature_thumb":
      echo get_the_post_thumbnail( $post_id, array(50,50) ); break;
    case "feature_url":
      echo get_post_meta( $post_id, 'icf_feature_url', true); break;
    case "feature_content":
      echo get_post_meta( $post_id, 'icf_feature_content', true); break;
  }
}
add_action( "manage_posts_custom_column", "icefit_features_custom_columns", 10, 2 );

/* ---------------- Add Metabox to Features  ---------------- */

function icefit_features_metabox_settings() {
	$prefix = 'icf_features_';
	$meta_box_settings = array('id' => 'icf-features-meta-box',
		'title' => __( 'Feature', 'icefit' ),
		'page' => 'icf_features',
		'context' => 'normal',
		'priority' => 'default',
		'fields' => array(
			array(
				'name' => __('Content', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'content',
				'type' => 'textarea',
				'std' => '' ),
			array(
				'name' => __('URL', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'url',
				'type' => 'text',
				'std' => '' ),
			)
	);
	return $meta_box_settings;
}

// Add meta box
add_action('admin_menu', 'icefit_features_add_box');
function icefit_features_add_box() {
	$meta_box_settings = icefit_features_metabox_settings();
	add_meta_box($meta_box_settings['id'], $meta_box_settings['title'], 'icefit_features_show_box', $meta_box_settings['page'], $meta_box_settings['context'], $meta_box_settings['priority']);
}

// Callback function to show fields in meta box
function icefit_features_show_box() {
	$meta_box_settings = icefit_features_metabox_settings();
	global $post;
	// Use nonce for verification
	echo '<input type="hidden" name="features_meta_box_nonce" value="', wp_create_nonce('features_meta_box_nonce'), '" />';
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
add_action('save_post', 'icefit_features_save_data');
function icefit_features_save_data($post_id) {
	
	$meta_box_settings = icefit_features_metabox_settings();
	
	// verify nonce
	if(!isset($_POST['features_meta_box_nonce'])) return;
	if (!wp_verify_nonce($_POST['features_meta_box_nonce'], 'features_meta_box_nonce')) {
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


/* ---------------- Features Shortcode ---------------- */

function icefit_features_shortcode($atts) {
	extract( shortcode_atts( array( 'title' => '', 'items' => '4', 'col' => 'one-fourth' ), $atts ) );

	$col_options = array('one-fourth', 'one-third', 'one-half', 'full-width');
	if (!in_array($col, $col_options)) $col = 'one-fourth';
	
	if ("all" == $items) $items = -1;

	$args = array( 'post_type' => 'icf_features', 'posts_per_page' => $items);
	$loop = new WP_Query( $args );
	if($loop->have_posts()) :

		$output = "";
		if ($title) $output .= '<h3>'.$title.'</h3>';

	while( $loop->have_posts() ) : $loop->the_post();
	
		$post_id = get_the_id();
		$output .= '<div class="feature-block '.$col.'">';

			$output .= '<h4>'.get_the_title().'</h4>';			

		if(has_post_thumbnail($post_id)):
			$output .= '<div class="feature-thumb">';
			$output .= get_the_post_thumbnail( $post_id, array(32,32) );
			$output .= '</div>';
		endif;
		$output .= '<p>'. __( get_post_meta(get_the_id(), 'icf_features_content', true) );
		if (get_post_meta(get_the_id(), 'icf_features_url', true)):	
			$output .= '<span class="read-more">';
			$output .= '<a href="'.get_post_meta(get_the_id(), 'icf_features_url', true).'">'.__('Learn More', 'icefit').'</a></span>';	
		endif;
		
		$output .=  '</p></div>';
		
	endwhile;
	endif;
	wp_reset_postdata();
	return $output;
}
add_shortcode('features', 'icefit_features_shortcode');

/* ---------------- Features Widget ---------------- */

class icefitfeaturesWidget extends WP_Widget
{
  function icefitfeaturesWidget()
  {
    $widget_ops = array('classname' => 'icefitfeaturesWidget', 'description' => __('Displays Features', 'icefit') );
    $this->WP_Widget('icefitfeaturesWidget', __('IceFit Features', 'icefit'), $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => __('Features', 'icefit'), 'count' => 'all' ) );
    $title = $instance['title'];
    $count = $instance['count'];

?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">
  <?php _e('Title', 'icefit'); ?>
  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
  </label></p>
  <p><label for="<?php echo $this->get_field_id('count'); ?>">
  <?php _e('Count', 'icefit'); ?>
  <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr($count); ?>" />
  </label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['count'] = $new_instance['count'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
    $count = ( empty($instance['count']) || !is_numeric($instance['count']) ) ? 'all' : apply_filters('widget_title', $instance['count']);
 	if ('all' == $count) $count = '-1';
 
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE    
    query_posts('posts_per_page='.$count.'&post_type=icf_features');
	if (have_posts()) : 
		echo "<ul>";
		while (have_posts()) : the_post();
			$output = '<li class="features-widget-item">';

			$post_id = get_the_id();
			$output .= '<div class="feature-block">';
			$output .= '<h5>'.get_the_title().'</h5>';			
	
			if(has_post_thumbnail($post_id)):
				$output .= '<div class="feature-thumb">';
				$output .= get_the_post_thumbnail( $post_id, array(32,32) );
				$output .= '</div>';
			endif;
			$output .= '<p>'. __( get_post_meta(get_the_id(), 'icf_features_content', true) ).'</p>';
			if (get_post_meta(get_the_id(), 'icf_features_url', true)):	
				$output .= '<span class="read-more">';
				$output .= '<a href="'.__( get_post_meta(get_the_id(), 'icf_features_url', true) ).'">'.__('Learn More', 'icefit').'</a></span>';	
			endif;
			$output .=  '</div>';
			
			echo $output;

			echo "</li>";
		endwhile;
		echo "</ul>";
	endif; 
	wp_reset_query();
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("icefitfeaturesWidget");') );



/* ---------------- Admin Icon ---------------- */

global $wp_version;
if ( $wp_version >= 3.8 ): // If WP 3.8 or later: use dashicons
	add_action( 'admin_head', 'features_post_type_dashicon' );
else:
	add_action( 'admin_head', 'features_post_type_icon' );
endif;

function features_post_type_dashicon() {
	echo '<style>#adminmenu #menu-posts-icf_features div.wp-menu-image:before{content:"\f155"}</style>';	
}

function features_post_type_icon() {
    ?>
    <style>
        /* Admin Menu - 16px */
        #menu-posts-icf_features .wp-menu-image {
            background: url(<?php echo get_template_directory_uri(); ?>/functions/icefit-features/features-icon16-sprite.png) no-repeat 6px 6px !important;
        }
		#menu-posts-icf_features:hover .wp-menu-image, #menu-posts-icf_features.wp-has-current-submenu .wp-menu-image {
            background-position: 6px -26px !important;
        }
        /* Post Screen - 32px */
        .icon32-posts-icf_features {
        	background: url(<?php echo get_template_directory_uri(); ?>/functions/icefit-features/features-icon16-sprite_2x.png) no-repeat left top !important;
        }
        @media
        only screen and (-webkit-min-device-pixel-ratio: 1.5),
        only screen and (   min--moz-device-pixel-ratio: 1.5),
        only screen and (     -o-min-device-pixel-ratio: 3/2),
        only screen and (        min-device-pixel-ratio: 1.5),
        only screen and (        		min-resolution: 1.5dppx) {
        	
        	/* Admin Menu - 16px @2x */
        	#menu-posts-icf_features .wp-menu-image {
        		background-image: url('<?php echo get_template_directory_uri(); ?>/functions/icefit-features/features-icon32.png') !important;
        		-webkit-background-size: 16px 48px;
        		-moz-background-size: 16px 48px;
        		background-size: 16px 48px;
        	}
        	/* Post Screen - 32px @2x */
        	.icon32-posts-icf_features {
        		background-image: url('<?php echo get_template_directory_uri(); ?>/functions/icefit-features/features-icon32_2x.png') !important;
        		-webkit-background-size: 32px 32px;
        		-moz-background-size: 32px 32px;
        		background-size: 32px 32px;
        	}         
        }
    </style>
<?php } ?>