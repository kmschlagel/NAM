<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 Mathieu Sarrasin - Iceable Media
 *
 * Custom Post Type: Portfolio
 *
 */

/* ---------------- Register Portfolio post type ---------------- */

add_action( 'init', 'icefit_portfolio_create_post_type' );
function icefit_portfolio_create_post_type() {

	global $icefit_options;

	$portfolio_entries_slug = $icefit_options['portfolio_entries_slug'];
	if ('' == $portfolio_entries_slug) $portfolio_entries_slug = 'portfolio';
	register_post_type( 'icf_portfolio',
		array( 'labels' => array( 'name' => __( 'Portfolio', 'icefit' ),
					'singular_name' => __( 'Portfolio Entry', 'icefit' ),
					 'menu_name' => __( 'Portfolio', 'icefit' ),
					 'all_items' => __( 'All Entries', 'icefit' ),
					 'add_new' => __( 'Add New', 'icefit' ),
					 'add_new_item' => __('Add New Portfolio Entry', 'icefit'),
					 'edit_item' => __( 'Edit Portfolio Entry', 'icefit' ),
					 'new_item' => __( 'New Portfolio Entry', 'icefit' ),
					 'view_item' => __( 'View Portfolio Entry', 'icefit' ),
					 'items_archive' => __( 'Portfolio Archive', 'icefit' ),
					 'search_items' => __( 'Search Portfolio Entries', 'icefit' ),
					 'not_found' => __( 'No Entry Found', 'icefit' ),
					 'not_found_in_trash' => __( 'No Entry found in Trash', 'icefit' ),
					),
			'public' => true,
			'has_archive' => true,
			'supports' => array( 'title', 'editor', 'category', 'author', 'thumbnail', 'excerpt', 'comments' ),
			'rewrite' => array('slug' => $portfolio_entries_slug),
			)
		);
}

/* ---------------- Create Custom Portfolio Taxonomies ---------------- */

add_action( 'init', 'icefit_create_portfolio_taxonomies', 0 );
function icefit_create_portfolio_taxonomies()  {

	// Add portfolio category taxonomy (hierarchical)
	$labels = array(
	'name' => _x( 'Portfolio Categories', 'taxonomy general name', 'icefit' ),
    'singular_name' => _x( 'Portfolio Category', 'taxonomy singular name', 'icefit' ),
    'search_items' =>  __( 'Search Category', 'icefit' ),
    'all_items' => __( 'All Categories', 'icefit' ),
    'parent_item' => __( 'Parent Category', 'icefit' ),
    'parent_item_colon' => __( 'Parent Category:', 'icefit' ),
    'edit_item' => __( 'Edit Category', 'icefit' ), 
    'update_item' => __( 'Update Category', 'icefit' ),
    'add_new_item' => __( 'Add New Category', 'icefit' ),
    'new_item_name' => __( 'New Category Name', 'icefit' ),
    'menu_name' => __( 'Portfolio Categories', 'icefit' ),
	); 	

	register_taxonomy('icf-portfolio-category',array('icf_portfolio'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'icf-portfolio-category' ),
  ));

}

/* --------------- Customize Portfolio Entries Admin Menu ---------------- */

// Add columns 
function icefit_portfolio_change_columns( $cols ) {
  $cols = array(
    'cb'					=> '<input type="checkbox" />',
    'title'					=> __( 'Title', 'trans' ),
    'project_client'		=> __( 'Client', 'trans' ),    
    'project_date'			=> __( 'Project Date', 'trans' ),
    'portfolio_category'	=> __( 'Category', 'trans' ),
    'date'					=> __( 'Date', 'trans' ),    
  );
  return $cols;
}
add_filter( "manage_icf_portfolio_posts_columns", "icefit_portfolio_change_columns" );

// Populate columns
function icefit_portfolio_custom_columns( $column, $post_id ) {
  switch ( $column ) {
    case "project_client":
      echo get_post_meta( $post_id, 'icf_portfolio_client', true); break;
    case "project_date":
      echo get_post_meta( $post_id, 'icf_portfolio_date', true); break;
    case "portfolio_category":
      $cats = wp_get_post_terms( $post_id, 'icf-portfolio-category', array('fields' => 'names') );      
      foreach($cats as $cat):
      	echo $cat."<br />";
      endforeach;
      break;
  }
}
add_action( "manage_posts_custom_column", "icefit_portfolio_custom_columns", 10, 2 );

// Filter the request to just give posts for the given taxonomy, if applicable.
function icefit_portfolio_taxonomy_filter_restrict_manage_posts() {
    global $typenow;
    if ($typenow != "icf_portfolio") return;
    
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
add_action( 'restrict_manage_posts', 'icefit_portfolio_taxonomy_filter_restrict_manage_posts' );

function icefit_portfolio_taxonomy_filter_post_type_request( $query ) {
  global $pagenow, $typenow;
  if ($typenow != "icf_portfolio") return;
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
add_filter( 'parse_query', 'icefit_portfolio_taxonomy_filter_post_type_request' );

/* ---------------- Add Metabox to Portfolio items ---------------- */

function icefit_portfolio_metabox_settings() {
	$prefix = 'icf_portfolio_';
	$meta_box_settings = array('id' => 'icf-portfolio-meta-box',
		'title' => __('Portfolio Item Information', 'icefit'),
		'page' => 'icf_portfolio',
		'context' => 'side',
		'priority' => 'default',
		'fields' => array(
			array(
				'name' => __('Date', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'date',
				'type' => 'text',
				'std' => '' ),
			array(
				'name' => __('Client', 'icefit'),
				'desc' => '',
				'id' => $prefix . 'client',
				'type' => 'text',
				'std' => '' ),
			)
	);
	return $meta_box_settings;
}

// Add meta box
add_action('admin_menu', 'icefit_portfolio_add_box');
function icefit_portfolio_add_box() {
	$meta_box_settings = icefit_portfolio_metabox_settings();
	add_meta_box($meta_box_settings['id'], $meta_box_settings['title'], 'icefit_portfolio_show_box', $meta_box_settings['page'], $meta_box_settings['context'], $meta_box_settings['priority']);
}

// Callback function to show fields in meta box
function icefit_portfolio_show_box() {
	$meta_box_settings = icefit_portfolio_metabox_settings();
	global $post;
	// Use nonce for verification
	echo '<input type="hidden" name="portfolio_meta_box_nonce" value="', wp_create_nonce('portfolio_meta_box_nonce'), '" />';
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
add_action('save_post', 'icefit_portfolio_save_data');
function icefit_portfolio_save_data($post_id) {
	
	$meta_box_settings = icefit_portfolio_metabox_settings();
	
	// verify nonce
	if(!isset($_POST['portfolio_meta_box_nonce'])) return;
	if (!wp_verify_nonce($_POST['portfolio_meta_box_nonce'], 'portfolio_meta_box_nonce')) {
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

/* ---------------- Portfolio Shortcode ---------------- */

function icefit_portfolio_shortcode($atts) {
	extract( shortcode_atts( array( 'cat' => 'all', 'col' => 'one_fourth', 'title' => 'Portfolio', 'maxitems' => '10', 'auto' => 'false' ), $atts ) );
	
	if ( '0' == $maxitems ) $maxitems = -1;
	$args = array( 'post_type' => 'icf_portfolio', 'posts_per_page' => intval($maxitems) );
	if ($cat != "all") $args['icf-portfolio-category'] = $cat;
	
	$col_options = array('one-fourth', 'one-third', 'one-half', 'two-thirds', 'three-fourths', 'full-width');
	if (!in_array($col, $col_options)) $col = 'one-fourth';
	$auto = ($auto == 'false') ? "" : " auto";

	$loop = new WP_Query( $args );
	if($loop->have_posts()) :
	$output = '<div class="'.$col.' caroufredsel-wrap portfolio-wrap"><h3>'.$title.'</h3><div class="caroufredsel'.$auto.'">';
	while( $loop->have_posts() ) : $loop->the_post();
	
	$output .=  '<div class="portfolio-item">';
	$output .=  '<div class="portfolio-thumb">';
	if (has_post_thumbnail()) :
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'portfolio-thumb');
		$output .=  '<a href="' . get_permalink() . '" title="' . get_the_title() . '">'; 
		$output .=  '<img class="scale-with-grid" src="'. $thumbnail[0] .'" alt="'. get_the_title()  .'">';
		$output .=  '</a>';
	endif;
	$output .=  '</div>';
	$output .=  '<a href="' . get_permalink() . '" title="' . get_the_title() . '">'; 
	$output .=  '<div class="portfolio-desc">';
	$output .=  '<h3>'.get_the_title().'</h3>';
	$output .=  '</div>';
	$output .=  '</a>';

	$output .=  '</div>';
	endwhile;
	$output .=  '</div><a class="prev"></a><a class="next"></a></div>';
	endif;
	wp_reset_postdata();
	return $output;
}
add_shortcode('portfolio', 'icefit_portfolio_shortcode');

/* ---------------- Portfolio Widget ---------------- */

class icefitportfolioWidget extends WP_Widget
{
  function icefitportfolioWidget()
  {
    $widget_ops = array('classname' => 'icefitportfolioWidget', 'description' => __('Displays a list of recent portfolio', 'icefit') );
    $this->WP_Widget('icefitportfolioWidget', __('IceFit Portfolio', 'icefit'), $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => __('Recent Works', 'icefit'), 'count' => 3 ));
    $title = $instance['title'];
    $category = (isset($instance['category'])) ? $instance['category'] : "";
    $count = $instance['count'];
    
    $cats = get_terms('icf-portfolio-category');

?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">
  <?php _e('Title', 'icefit'); ?>
  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
  </label></p>
  <p><label for="<?php echo $this->get_field_id('count'); ?>">
  <?php _e('Number of items', 'icefit'); ?>
  <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr($count); ?>" />
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
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['category'] = $new_instance['category'];
    $instance['count'] = (empty($new_instance['count']) || !is_numeric($new_instance['count'])) ? '3' : $new_instance['count'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
    $category = empty($instance['category']) ? 'all' : $instance['category'];
    $count = (empty($instance['count']) || !is_numeric($instance['count'])) ? '3' : $instance['count'];
 	
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
    $args = array( 'post_type' => 'icf_portfolio', 'posts_per_page' => $count, 'ignore_sticky_posts' => true );
	if ($category != "all") $args['icf-portfolio-category'] = $category;
	$loop = new WP_Query( $args );
	if($loop->have_posts()) :
		echo '<ul class="portfolio-widget">';
		while ($loop->have_posts()) : $loop->the_post();
			echo '<li class="portfolio-widget-item">';
			echo '<div class="portfolio-widget-thumb">';
			if (has_post_thumbnail()) :
				$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail');
				echo '<a href="' . get_permalink() . '" title="' . get_the_title() . '">'; 
				echo '<img class="scale-with-grid" src="'. $thumbnail[0] .'" alt="'. get_the_title()  .'">';
				echo '</a>';
			endif;
			echo '</div>';
			echo "<div class=\"portfolio-widget-title\"><a href='".get_permalink()."'>".get_the_title()."</a></div>";
			$the_id = get_the_id();
			$cats = implode(', ', wp_get_post_terms( $the_id, 'icf-portfolio-category', array("fields" => "names") ));			
			$date = get_post_meta($the_id, 'portfolio_date', true);
			$client = get_post_meta($the_id, 'portfolio_client', true);
			echo '<div class="portfolio-widget-meta">';
			echo '<span class="portfolio-widget-category">'.$cats.'</span>';	
			echo '<span class="portfolio-widget-date">'.$date.'</span>';
			echo '<span class="portfolio-widget-client">'.$client.'</span>';
			echo '</div>';
			echo "</li>";
		endwhile;
		echo "</ul>";
	endif; 
	wp_reset_postdata();
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("icefitportfolioWidget");') );

/* ---------------- Admin Icon ---------------- */

add_action( 'admin_head', 'portfolio_post_type_icon' );

function portfolio_post_type_icon() {
    ?>
    <style>
        /* Admin Menu - 16px */
        #menu-posts-icf_portfolio .wp-menu-image {
            background: url(<?php echo get_template_directory_uri(); ?>/functions/icefit-portfolio/portfolio-icon16-sprite.png) no-repeat 6px 6px !important;
        }
		#menu-posts-icf_portfolio:hover .wp-menu-image, #menu-posts-icf_portfolio.wp-has-current-submenu .wp-menu-image {
            background-position: 6px -26px !important;
        }
        /* Post Screen - 32px */
        .icon32-posts-icf_portfolio {
        	background: url(<?php echo get_template_directory_uri(); ?>/functions/icefit-portfolio/portfolio-icon16-sprite_2x.png) no-repeat left top !important;
        }
        @media
        only screen and (-webkit-min-device-pixel-ratio: 1.5),
        only screen and (   min--moz-device-pixel-ratio: 1.5),
        only screen and (     -o-min-device-pixel-ratio: 3/2),
        only screen and (        min-device-pixel-ratio: 1.5),
        only screen and (        		min-resolution: 1.5dppx) {
        	
        	/* Admin Menu - 16px @2x */
        	#menu-posts-icf_portfolio .wp-menu-image {
        		background-image: url('<?php echo get_template_directory_uri(); ?>/functions/icefit-portfolio/portfolio-icon32.png') !important;
        		-webkit-background-size: 16px 48px;
        		-moz-background-size: 16px 48px;
        		background-size: 16px 48px;
        	}
        	/* Post Screen - 32px @2x */
        	.icon32-posts-icf_portfolio {
        		background-image: url('<?php echo get_template_directory_uri(); ?>/functions/icefit-portfolio/portfolio-icon32_2x.png') !important;
        		-webkit-background-size: 32px 32px;
        		-moz-background-size: 32px 32px;
        		background-size: 32px 32px;
        	}         
        }
    </style>
<?php } ?>