<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 - 2014 Mathieu Sarrasin - Iceable Media
 *
 * Social Media Widget
 *
 */


/*---------- Social Media Widget ----------*/

class icefitsocialmediaWidget extends WP_Widget
{
  function icefitsocialmediaWidget()
  {
    $widget_ops = array('classname' => 'icefitsocialmediaWidget', 'description' => __('Social Media', 'icefit') );
    $this->WP_Widget('icefitsocialmediaWidget', __('IceFit Social Media', 'icefit'), $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => __('Follow Us', 'icefit') ));
    $title = $instance['title'];

?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">
  <?php _e('Title', 'icefit'); ?>
  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
  </label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 	
    if (!empty($title)) echo $before_title . $title . $after_title;

    // WIDGET CODE GOES HERE
    $output = icefit_socialmedia_widget_content();

 	echo $output;
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("icefitsocialmediaWidget");') );


function icefit_socialmedia_widget_content() {

	global $icefit_options;
	$facebook =    $icefit_options['facebook_url'];
	$twitter =     $icefit_options['twitter_url'];
	$googleplus =  $icefit_options['googleplus_url'];
	$linkedin =    $icefit_options['linkedin_url'];
	$instagram =   $icefit_options['instagram_url'];
	$pinterest =   $icefit_options['pinterest_url'];
	$tumblr =      $icefit_options['tumblr_url'];
	$stumbleupon = $icefit_options['stumbleupon_url'];
	$dribbble =    $icefit_options['dribbble_url'];
	$behance =     $icefit_options['behance_url'];
	$deviantart =  $icefit_options['deviantart_url'];
	$flickr =      $icefit_options['flickr_url'];
	$youtube =     $icefit_options['youtube_url'];
	$vimeo =       $icefit_options['vimeo_url'];
	$yelp =        $icefit_options['yelp_url'];
	$rss =         $icefit_options['rss_url'];

	$output	= '<div class="socialmedia-widget-wrap">';
	if ($facebook)    $output .= '<a href="'.$facebook.		'" class="facebook" target="_blank"></a>';
	if ($twitter)     $output .= '<a href="'.$twitter.		'" class="twitter" target="_blank"></a>';
	if ($googleplus)  $output .= '<a href="'.$googleplus.	'" class="googleplus" target="_blank"></a>';
	if ($linkedin)    $output .= '<a href="'.$linkedin.		'" class="linkedin" target="_blank"></a>';
	if ($instagram)   $output .= '<a href="'.$instagram.	'" class="instagram" target="_blank"></a>';
	if ($pinterest)   $output .= '<a href="'.$pinterest.	'" class="pinterest" target="_blank"></a>';
	if ($tumblr)      $output .= '<a href="'.$tumblr.		'" class="tumblr" target="_blank"></a>';
	if ($stumbleupon) $output .= '<a href="'.$stumbleupon.	'" class="stumbleupon" target="_blank"></a>';
	if ($dribbble)    $output .= '<a href="'.$dribbble.		'" class="dribbble" target="_blank"></a>';
	if ($behance)     $output .= '<a href="'.$behance.		'" class="behance" target="_blank"></a>';
	if ($deviantart)  $output .= '<a href="'.$deviantart.	'" class="deviantart" target="_blank"></a>';
	if ($flickr)      $output .= '<a href="'.$flickr.		'" class="flickr" target="_blank"></a>';
	if ($youtube)     $output .= '<a href="'.$youtube.		'" class="youtube" target="_blank"></a>';
	if ($vimeo)       $output .= '<a href="'.$vimeo.		'" class="vimeo" target="_blank"></a>';
	if ($yelp)        $output .= '<a href="'.$yelp.			'" class="yelp" target="_blank"></a>';
	if ($rss)         $output .= '<a href="'.$rss.			'" class="rss"></a>';
	$output	.= '</div>';
	
	return $output;
}

?>