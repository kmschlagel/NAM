<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 - 2014 Mathieu Sarrasin - Iceable Media
 *
 * Contact form functions
 *
 */


/*---------- Contact Form Shortcode ----------*/

function contact_shortcode($atts) {
	extract( shortcode_atts( array( 'col' => 'one_fourth', 'title' => 'Contact Us', 'content' => '' ), $atts ) );

	$col_options = array('one-fourth', 'one-third', 'one-half', 'two-thirds', 'three-fourths', 'full-width');
	if (!in_array($col, $col_options)) $col = 'one-fourth';

	$output	= '<div id="contactform-wrap" class="'.$col.'">';
	if ('' != $title) $output .= '<h3>'.$title.'</h3>';
	if ('' != $content) $output .= '<p>'.$content.'</p>';
	$output .= '<form name="contact-form" id="contact_form" method="POST">';
	$output .= '<label for="name" id="name_wrap">';
	$output .= __('Name:', 'icefit');
	$output .= '<input id="name" type="text" name="name" /></label>';
	$output .= '<label for="email" id="email_wrap">';
	$output .= __('Email:', 'icefit');
	$output .= '<input id="email" type="text" name="email" /></label>';
	$output .= '<label for="phone" id="phone_wrap">';
	$output .= __('Phone:', 'icefit');
	$output .= '<input id="phone" type="text" name="phone" /></label>';
	$output .= '<label for="subject" id="subject_wrap">';
	$output .= __('Subject:', 'icefit');
	$output .= '<input id="subject" type="text" name="subject" /></label>';
	$output .= '<label for="message" id="message_wrap">';
	$output .= __('Message:', 'icefit');
	$output .= '<textarea id="message" name="message"></textarea></label>';
	$output .= '<input type="submit" name="submit" value="'.__('Send', 'icefit').'" id="submit">';
	$output .= '<div id="results"></div>';
	$output .= '<br class="clear" />';
	$output	.= wp_nonce_field('icefit_contact_ajax_post_action','icefit_contact_nonce', true, false);
	$output	.= '<script type="text/javascript">var ajaxurl=\''.admin_url('admin-ajax.php').'\';</script>';
	$output	.= '</form></div>';

	return $output;
}
add_shortcode('contact-form', 'contact_shortcode');

/*---------- Contact Form Widget ----------*/

class icefitcontactformWidget extends WP_Widget
{
  function icefitcontactformWidget()
  {
    $widget_ops = array('classname' => 'icefitcontactformWidget', 'description' => __('Contact Form', 'icefit') );
    $this->WP_Widget('icefitcontactformWidget', __('IceFit Contact Form', 'icefit'), $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => __('Contact Us', 'icefit') ));
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
 	
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
	$output	= '<div id="contactform-widget-wrap">';
	$output .= '<form name="contact-form" id="contact_form" method="POST">';
	$output .= '<label for="name" id="name_wrap">';
	$output .= __('Name:', 'icefit');
	$output .= '<input id="name" type="text" name="name" /></label>';
	$output .= '<label for="email" id="email_wrap">';
	$output .= __('Email:', 'icefit');
	$output .= '<input id="email" type="text" name="email" /></label>';
	$output .= '<label for="phone" id="phone_wrap">';
	$output .= __('Phone:', 'icefit');
	$output .= '<input id="phone" type="text" name="phone" /></label>';
	$output .= '<label for="subject" id="subject_wrap">';
	$output .= __('Subject:', 'icefit');
	$output .= '<input id="subject" type="text" name="subject" /></label>';
	$output .= '<label for="message" id="message_wrap">';
	$output .= __('Message:', 'icefit');
	$output .= '<textarea id="message" name="message"></textarea></label>';
	$output .= '<input type="submit" name="submit" value="'.__('Send', 'icefit').'" id="submit">';
	$output .= '<div id="results"></div>';
	$output .= '<br class="clear" />';
	$output	.= wp_nonce_field('icefit_contact_ajax_post_action','icefit_contact_nonce', true, false);
	$output	.= '<script type="text/javascript">var ajaxurl=\''.admin_url('admin-ajax.php').'\';</script>';
	$output	.= '</form></div>';
 	echo $output;
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("icefitcontactformWidget");') );


/* --- Handle AJAX POST values --- */

function icefit_contact_ajax_callback() {
	$intl_mess = icefit_contact_intl();
	check_ajax_referer('icefit_contact_ajax_post_action','icefit_contact_nonce');
	$data = $_POST['data'];
	parse_str($_POST['data'], $data);
	$message = "";
	if ("" == $data['name']) $message .= $intl_mess['noname']."<br/>";
	if ("" == $data['email']) $message .= $intl_mess['nomail']."<br/>";
	if ("" != $data['email'] && !filter_var( $data['email'], FILTER_VALIDATE_EMAIL )) $message .= $intl_mess['invalidmail']."<br/>";
	if ("" == $data['message']) $message .= $intl_mess['nomess'];
	if ("" == $message) {
		$to = get_bloginfo('admin_email');
		$subject = $data['subject'];
		$message = $data['message'];
		$message .= "\n\n Name: ".$data['name']."\n";
		$message .= "Email: ".$data['email']."\n";
		$message .= "Tel: ".$data['phone'];
		$headers = 'From: '.$data['name'].' <'.$data['email'].'>' . "\r\n";
		$sent = wp_mail($to, $subject, $message, $headers );
		if ($sent) {
		$result['message'] = $intl_mess['success'];
		$result['status'] = 1;
		} else {
		$result['message'] = $intl_mess['failure'];
		$result['status'] = 0;		
		}
	} else {
		$result['message'] = $message;
		$result['status'] = 0;
	}
	echo json_encode($result);
	die();
}
// This action will be available for logged in users
add_action('wp_ajax_icefit_contact_ajax_post_action', 'icefit_contact_ajax_callback');
// "nopriv" makes this action available for non logged users
add_action('wp_ajax_nopriv_icefit_contact_ajax_post_action', 'icefit_contact_ajax_callback');

function icefit_contact_intl() {
	$intl_mess['noname'] = __("Please enter your name.", 'icefit');
	$intl_mess['nomail'] = __("Please enter your E-Mail address.", 'icefit');
	$intl_mess['invalidmail'] = __("Please enter a valid E-Mail address.", 'icefit');
	$intl_mess['nomess'] = __("Please enter your message.", 'icefit');
	$intl_mess['success'] = __("Thank you. Your message has been sent successfully.", 'icefit');
	$intl_mess['failure'] = __("Sorry, an unexpected error occurred while trying to send your message.", 'icefit');
	return $intl_mess;
}

?>