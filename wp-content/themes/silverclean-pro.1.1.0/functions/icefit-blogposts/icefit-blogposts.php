<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 Mathieu Sarrasin - Iceable Media
 *
 * Shortcode & Widget: Blog Posts
 *
 */

/* ---------------- Blogposts Shortcode ---------------- */

function icefit_blogposts_shortcode($atts) {
	extract( shortcode_atts( array( 'cat' => 'all', 'col' => 'one_fourth', 'title' => 'Recent posts', 'maxitems' => '10', 'auto' => 'false' ), $atts ) );
	
	if ( '0' == $maxitems ) $maxitems = -1;
	$args = array( 'posts_per_page' => intval($maxitems) );
	if ($cat != "all") $args['category_name'] = $cat;
	
	$col_options = array('one-fourth', 'one-third', 'one-half', 'two-thirds', 'three-fourths', 'full-width');
	if (!in_array($col, $col_options)) $col = 'one-fourth';
	$auto = ($auto == 'false') ? "" : " auto";

	$loop = new WP_Query( $args );
	if($loop->have_posts()) :

	$output = '<div class="'.$col.' caroufredsel-wrap"><h3>'.$title.'</h3><div class="blogposts-wrap caroufredsel'.$auto.'">';

	while( $loop->have_posts() ) : $loop->the_post();
	
	$output .=  '<div class="blogpost-item">';
	$output .=  '<div class="blogpost-thumb">';
	if (has_post_thumbnail()) :
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail');
		$output .= '<a href="' . get_permalink() . '" title="' . get_the_title() . '">'; 
		$output .= '<img class="scale-with-grid" src="'. $thumbnail[0] .'" alt="'. get_the_title()  .'">';
		$output .= '</a>';
	endif;
	$output .= '</div>';
	$output .= '<div class="blogpost-desc">';
	$output .= '<a href="' . get_permalink() . '" title="' . get_the_title() . '">'; 
	$output .= '<h3>'.get_the_title().'</h3>';
	$output .= '</a>';
	$output .= '<span class="blogposts-date">'.get_the_date('m/d/Y').'</span>';
	$output .= '</div>';
	$output .= '</div>';
	endwhile;
	$output .= '</div><a class="prev"></a><a class="next"></a></div>';
	endif;
	wp_reset_postdata();
	return $output;
}
add_shortcode('blogposts', 'icefit_blogposts_shortcode');

/* ---------------- Blogposts Widget ---------------- */

class icefitblogpostsWidget extends WP_Widget
{
  function icefitblogpostsWidget()
  {
    $widget_ops = array('classname' => 'icefitblogpostsWidget', 'description' => __('IceFit\'s improved version of the "recent posts" widget. Includes thumbnails and optional category filter.', 'icefit') );
    $this->WP_Widget('icefitblogpostsWidget', __('IceFit Blog Posts', 'icefit'), $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => __('Recent Posts', 'icefit'), 'count' => 3 ));
    $title = $instance['title'];
    $category = (isset($instance['category'])) ? $instance['category'] : "";
    $count = $instance['count'];
    
    $cats = get_categories();

?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">
  <?php _e('Title', 'icefit'); ?>
  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
  </label></p>
  <p><label for="<?php echo $this->get_field_id('count'); ?>">
  <?php _e('Number of posts', 'icefit'); ?>
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
    $instance['count'] = $new_instance['count'];    
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
    $args = array( 'post_type' => 'post', 'posts_per_page' => $count, 'ignore_sticky_posts' => true );
	if ($category != "all") $args['category_name'] = $category;
	$loop = new WP_Query( $args );
	if($loop->have_posts()) :
		echo '<ul class="blogposts-widget">';
		while ($loop->have_posts()) : $loop->the_post();
			echo '<li class="blogposts-widget-item">';
			echo '<div class="blogposts-widget-thumb">';
			if (has_post_thumbnail()) :
				$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail');
				echo '<a href="' . get_permalink() . '" title="' . get_the_title() . '">'; 
				echo '<img class="scale-with-grid" src="'. $thumbnail[0] .'" alt="'. get_the_title()  .'">';
				echo '</a>';
			endif;
			echo '</div>';
			echo "<div class=\"blogposts-widget-title\"><a href='".get_permalink()."'>".get_the_title()."</a></div>";
			echo '<div class="blogposts-widget-meta">';
			echo '<span class="blogposts-widget-date">'.the_date('m/d/Y').'</span>';
			echo '<span class="blogposts-widget-comments">'.comments_popup_link( __( 'No Comment', 'icefit' ), __( '1 Comment', 'icefit' ), __( '% Comments', 'icefit' ) ).'</span>';
			echo '</div>';

			echo "</li>";
		endwhile;
		echo "</ul>";
	endif; 
	wp_reset_postdata();
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("icefitblogpostsWidget");') );


?>