<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 Mathieu Sarrasin - Iceable Media
 *
 * Custom Widget: Contact Info
 *
 */

/* ---------------- Contact Info Widget ---------------- */

class icefitcontactinfoWidget extends WP_Widget
{
  function icefitcontactinfoWidget()
  {
    $widget_ops = array('classname' => 'icefitcontactinfoWidget', 'description' => __('Display your contact info.', 'icefit') );
    $this->WP_Widget('icefitcontactinfoWidget', __('IceFit Contact Info', 'icefit'), $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => __('Get in touch', 'icefit') ));
    $title = $instance['title'];
	$address = (isset($instance['address'])) ? $instance['address'] : "";
	$phone = (isset($instance['phone'])) ? $instance['phone'] : "";
	$email = (isset($instance['email'])) ? $instance['email'] : "";
	$form = (isset($instance['form'])) ? $instance['form'] : "";
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">
  <?php _e('Title', 'icefit'); ?>
  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
  </label></p>
  <p><label for="<?php echo $this->get_field_id('address'); ?>">
  <?php _e('Address', 'icefit'); ?>
  <textarea class="widefat" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>" type="text"><?php echo esc_attr($address); ?></textarea>
  </label></p>
  <p><label for="<?php echo $this->get_field_id('phone'); ?>">
  <?php _e('Phone', 'icefit'); ?>
  <input class="widefat" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" type="text" value="<?php echo esc_attr($phone); ?>" />
  </label></p>
  <p><label for="<?php echo $this->get_field_id('email'); ?>">
  <?php _e('Email', 'icefit'); ?>
  <input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo esc_attr($email); ?>" />
  </label></p>
  <p><label for="<?php echo $this->get_field_id('form'); ?>">
  <?php _e('Contact Form Link', 'icefit'); ?>
  <input class="widefat" id="<?php echo $this->get_field_id('form'); ?>" name="<?php echo $this->get_field_name('form'); ?>" type="text" value="<?php echo esc_attr($form); ?>" />
  </label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['address'] = $new_instance['address'];
    $instance['phone'] = $new_instance['phone'];
    $instance['email'] = $new_instance['email'];
    $instance['form'] = $new_instance['form'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
    $address = $instance['address'];
    $phone = $instance['phone'];
    $email = $instance['email'];
    $form = $instance['form'];
 	
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
	echo '<ul class="contactinfo-widget">';
	if ($address) echo '<li class="contactinfo-widget-address">'.str_replace("\n", "<br />", $address)."</li>";
	if ($phone) echo '<li class="contactinfo-widget-phone">'.$phone."</li>";
	if ($email) echo '<li class="contactinfo-widget-email">'.$email."</li>";
	if ($form) echo '<li class="contactinfo-widget-form"><a href="'.$form.'">Contact Form</a></li>';
	echo "</ul>";
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("icefitcontactinfoWidget");') );


?>