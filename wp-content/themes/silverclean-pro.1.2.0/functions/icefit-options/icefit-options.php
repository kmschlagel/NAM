<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 - 2014 Mathieu Sarrasin - Iceable Media
 *
 * Admin settings Framework
 *
 */

// Get all current settings options (returns an array with options => value)
function icefit_get_all_options(){
	global $icefit_settings_slug;
	$icefit_settings = get_option($icefit_settings_slug);
	return $icefit_settings;
}

// Get all export-able settings - returns the export string (base64-encoded-gzdeflated-serialized array)
function icefit_get_export_settings() {
	global $icefit_settings_slug;
	$icefit_settings = get_option($icefit_settings_slug);
	$template = icefit_settings_template();
	$export_array = array();
	foreach($template as $template_item):
		$type = $template_item['type'];
		if ($type != 'start_menu' && $type != 'end_menu' && $type != 'image' && $type != 'import' && $type != 'export' && $type != 'index'):
			$id = $template_item['id'];
			if ( isset( $icefit_settings[$id] ) )
				$export_array[$id] = $icefit_settings[$id];
		endif;
	endforeach;

	return base64_encode ( gzdeflate ( serialize( $export_array ) ) );
}


// Adds "Theme option" link under "Appearance" in WP admin panel
function icefit_settings_add_admin() {
	global $menu;
    $icefit_option_page = add_theme_page( __('Theme Options', 'icefit'), __('Theme Options', 'icefit'), 'edit_theme_options', 'icefit-settings', 'icefit_settings_page');
	add_action( 'admin_print_scripts-'.$icefit_option_page, 'icefit_settings_admin_scripts' );
} add_action('admin_menu', 'icefit_settings_add_admin');

// Registers and enqueue js and css for settings panel
function icefit_settings_admin_scripts() {
	wp_register_style( 'icefit_admin_css', get_template_directory_uri() .'/functions/icefit-options/style.css');
	wp_enqueue_style( 'icefit_admin_css' );
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'icefit_admin_js', get_template_directory_uri() . '/functions/icefit-options/functions.js', array( 'wp-color-picker' ), false, true );
}

// Generates the settings panel's menu
function icefit_settings_machine_menu($options) {
	$output = "";
	foreach ($options as $arg) {

		if ( $arg['type'] == "start_menu" )
		{
			$output .= '<li class="icefit-admin-panel-menu-li"><a class="icefit-admin-panel-menu-link '.$arg['icon'].'" href="#'.$arg['name'].'" id="icefit-admin-panel-menu-'.$arg['id'].'"><span></span>'.$arg['name'].'</a></li>'."\n";
		} 
	}
	return $output;
}

// Generate the settings panel's content
function icefit_settings_machine($options) {
	global $icefit_settings_slug;
	$icefit_settings = get_option($icefit_settings_slug);
	$output = "";
	$option_index = "";
	foreach ($options as $arg) {

		if ( is_array($arg) && is_array($icefit_settings) && $arg['type'] != 'update'  ) {
			if ( array_key_exists('id', $arg) ) {
				if ( array_key_exists($arg['id'], $icefit_settings) ) $val = stripslashes($icefit_settings[$arg['id']]);
				else $val = "";
			} else { $val = ""; }
		} else { $val = ""; }
		
		if ( $arg['type'] == "start_menu" )
		{
			$output .= '<div class="icefit-admin-panel-content-box" id="icefit-admin-panel-content-'.$arg['id'].'">';
		}
		elseif ( $arg['type'] == "text" )
		{
			$val = esc_attr($val);
			$output .= '<h3>'. $arg['name'] .'</h3>'."\n";
			if ( $val == "" && $arg['default'] != "") $icefit_settings[$arg['id']] = $val = $arg['default'];
			$output .= '<input class="icefit_input_text" name="'. $arg['id'] .'" id="'. $arg['id'] .'" type="'. $arg['type'] .'" value="'. $val .'" />'."\n";			
			$output .= '<br class="clear"><label>'. $arg['desc'] .'</label>'."\n";
		}
		elseif ( $arg['type'] == "textarea" )
		{
			$val = esc_textarea($val);
			$output .= '<h3>'. $arg['name'] .'</h3>'."\n";
			if ( $val == "" && $arg['default'] != "") $icefit_settings[$arg['id']] = $val = $arg['default'];
			$output .= '<textarea class="icefit_input_textarea" name="'. $arg['id'] .'" id="'. $arg['id'] .'" rows="5" cols="60">' . $val . '</textarea>'."\n";
			$output .= '<br class="clear"><label>'. $arg['desc'] .'</label>'."\n";
		}				
		elseif ( $arg['type'] == "radio" )
		{
			$output .= '<h3>'. $arg['name'] .'</h3>'."\n";
			if ( $val == "" && $arg['default'] != "") $icefit_settings[$arg['id']] = $val = $arg['default'];
			$values = $arg['values'];
			$output .= '<div class="radio-group">';
			foreach ($values as $value) {
			$output .= '<input type="radio" name="'.$arg['id'].'" value="'.$value['value'].'" '.checked($value['value'], $val, false).'>'.$value['display'].'<br/>';
			}
			$output .= '</div>';
			$desc = $arg['desc'];
				if ($arg['id'] == 'blog_index_content') {
					if ( ! extension_loaded('tidy') ) $desc .= __("Warning: The PHP Tidy extension is not enabled on your server; this may prevent Icefit Improve Excerpt from working properly!", 'icefit');
			}
			$output .= '<label class="desc">'. $desc .'</label><br class="clear" />'."\n";
		}	
		elseif ( $arg['type'] == "select" )
		{
			$output .= '<h3>'. $arg['name'] .'</h3>'."\n";
			if ( $val == "" && $arg['default'] != "") $icefit_settings[$arg['id']] = $val = $arg['default'];
			$values = $arg['values'];
			$output .= '<select name="'.$arg['id'].'">';
			foreach ($values as $value) {
				$output .= '<option value="'.$value.'" '.selected($value, $val, false).'>'.$value.'</option>';
			}
			$output .= '</select>';
			$output .= '<div class="desc">'. $arg['desc'] .'</div><br class="clear" />'."\n";
		}
		elseif ( $arg['type'] == "font" )
		{
			include_once('webfonts-list.php');
			$output .= '<h3>'. $arg['name'] .'</h3>'."\n";
			if ( $val == "" && $arg['default'] != "") $icefit_settings[$arg['id']] = $val = $arg['default'];
			$values = unserialize($webfont_list);
			$output .= '<select name="'.$arg['id'].'">';
			$output .= '<optgroup label="' . __('Web Standards', 'icefit') . '">';
			$websafe_fonts = array("Georgia", "Times New Roman", "Andale Mono", "Arial", "Arial Black", "Impact", "Trebuchet MS", "Verdana", "Webdings", "Comic Sans MS", "Courier New", "Century Gothic", "Lucida", "Lucida Grande", "Palatino", "Tahoma");
			foreach ($websafe_fonts as $websafe) {
				$output .= '<option value="'.$websafe.'" '.selected($websafe, $val, false).'>'.$websafe.'</option>';
			}
			$output .= '</optgroup>';			
			$output .= '<optgroup label="' . __('Google Webfonts', 'icefit') . '">';
			foreach ($values as $value) {
				$output .= '<option value="'.$value.'" '.selected($value, $val, false).'>'.$value.'</option>';
			}
			$output .= '</optgroup></select>';
			$output .= '<div class="desc">'. $arg['desc'] .'</div><br class="clear" />'."\n";
		}
		elseif ( $arg['type'] == "image" )
		{
			$output .= '<h3>'. $arg['name'] .'</h3>'."\n";
			if ( $val == "" && $arg['default'] != "") $icefit_settings[$arg['id']] = $val = $arg['default'];
			$output .= '<input class="icefit_input_img" name="'. $arg['id'] .'" id="'. $arg['id'] .'" type="text" value="'. $val .'" />'."\n";
			$output .= '<br class="clear"><label>'. $arg['desc'] .'</label><br class="clear">'."\n";
			$output .= '<input class="icefit_upload_button" name="'. $arg['id'] .'_upload" id="'. $arg['id'] .'_upload" type="button" value="' . __('Upload Image', 'icefit') . '">'."\n";
			$output .= '<input class="icefit_remove_button" name="'. $arg['id'] .'_remove" id="'. $arg['id'] .'_remove" type="button" value="' . __('Remove', 'icefit') . '">'."\n";
			$output .= '<input class="icefit_default_button" name="'. $arg['id'] .'_remove" id="'. $arg['id'] .'_default" type="button" value="' . __('Default', 'icefit') . '" data-default="'.$arg['default'].'"><br />'."\n";
			$output .= '<img class="icefit_image_preview" id="'. $arg['id'] .'_preview" src="'.$val.'"><br class="clear">'."\n";
		}
		elseif ( $arg['type'] == "color" )
		{
			$val = esc_attr($val);
			$output .= '<h3>'. $arg['name'] .'</h3>'."\n";
			if ( $val == "" && $arg['default'] != "") $icefit_settings[$arg['id']] = $val = $arg['default'];
			$output .= '<input class="icefit_input_color" name="'. $arg['id'] .'" id="'. $arg['id'] .'" type="text" value="'. $val .'" data-default-color="'. $arg['default'] .'" />'."\n";
			$output .= '<div class="desc">'. $arg['desc'] .'</div><br class="clear">'."\n";
		}
		elseif ( $arg['type'] == "export" )
		{
			$export_string = icefit_get_export_settings();
			$output .= '<h3>'. $arg['name'] .'</h3>'."\n";
			
			$output .= '<textarea disabled="disabled" class="icefit_input_textarea export" name="'. $arg['id'] .'" id="'. $arg['id'] .'">'.$export_string.'</textarea>'."\n";
			$output .= '<br class="clear"><label>'. $arg['desc'] .'</label>'."\n";
		}
		elseif ( $arg['type'] == "import" )
		{
			$output .= '<h3>'. $arg['name'] .'</h3>'."\n";
			
			$output .= '<textarea class="icefit_input_textarea import" name="'. $arg['id'] .'" id="'. $arg['id'] .'"></textarea>'."\n";
			$output .= '<input type="button" value="' . __('Import','icefit') . '" name="icefit-import-settings" id="icefit-import-settings" class="button-primary" />';
			$output .= '<br class="clear"><label>'. $arg['desc'] .'</label>'."\n";
		}
		elseif ( $arg['type'] == "index" )
		{
			$output .= '<h3>'. $arg['name'] .'</h3>'."\n";
			$output .= '<label>'. $arg['desc'] .'</label>'."\n";
			$output .= '<ul class="option-index">' . $option_index . "</ul>";
		}
		elseif ( $arg['type'] == "end_menu" )
		{
			$output .= '</div>';
		}

		if ( $arg['type'] == "start_menu" ) {
			$option_index .= '<li><strong>'.$arg['name'].'</strong></li><ul>';
		} elseif ( $arg['type'] == "end_menu" ) {
			$option_index .= "</ul>";			
		} else {
			$option_index .= "<li>".$arg['name']."</li>";			
		}

	}
	update_option($icefit_settings_slug,$icefit_settings);	
	return $output;
}

// Ajax callback function for the "reset" button (resets settings to default)
function icefit_settings_reset_ajax_callback() {
	global $icefit_settings_slug;
	// Get settings from the database
	$icefit_settings = get_option($icefit_settings_slug);
	// Get the settings template
	$options = icefit_settings_template();
	// Revert all settings to default value
	foreach($options as $arg){
		if ($arg['type'] != 'start_menu' && $arg['type'] != 'end_menu' && isset($arg['default']) ) {
			$icefit_settings[$arg['id']] = $arg['default'];
		}	
	}
	// Updates settings in the database	
	update_option($icefit_settings_slug,$icefit_settings);	
}
add_action('wp_ajax_icefit_settings_reset_ajax_post_action', 'icefit_settings_reset_ajax_callback');

// AJAX callback function for the "Save changes" button (updates user's settings in the database)
function icefit_settings_ajax_callback() {
	global $icefit_settings_slug;
	// Check nonce
	check_ajax_referer('icefit_settings_ajax_post_action','icefit_settings_nonce');
	// Get POST data
	$data = $_POST['data'];
	parse_str($data,$output);
	// Get current settings from the database
	$icefit_settings = get_option($icefit_settings_slug);
	// Get the settings template
	$options = icefit_settings_template();
	// Updates all settings according to POST data
	foreach($options as $option_array){
	
	
		if ($option_array['type'] != 'start_menu' && $option_array['type'] != 'end_menu' && $option_array['type'] != 'import' && $option_array['type'] != 'export' && $type != 'index') {
		
		$id = $option_array['id'];
		
			if (isset ($id, $option_array) ) {

				if ($option_array['type'] == "text") {
					$new_value = esc_textarea($output[$option_array['id']]);
				} else {
					$new_value = $output[$option_array['id']];		
				}
			$icefit_settings[$id] = stripslashes($new_value);		
			}
		
		}
	}	// Updates settings in the database
	update_option($icefit_settings_slug,$icefit_settings);	
}
add_action('wp_ajax_icefit_settings_ajax_post_action', 'icefit_settings_ajax_callback');

// AJAX callback function for the "Import" button
function icefit_settings_import_ajax_callback() {
	global $icefit_settings_slug;
	// Get POST data
	$data =  $_POST['data'];
	// Recreate an array from export string
	$output = unserialize ( gzinflate ( base64_decode ( $data ) ) );
	
	// Get current settings from the database
	$icefit_settings = get_option($icefit_settings_slug);
	// Get the settings template
	$options = icefit_settings_template();
	// Updates all settings according to POST data
	foreach($options as $option_array){

		if ($option_array['type'] != 'start_menu' && $option_array['type'] != 'end_menu' && $option_array['type'] != 'import' && $option_array['type'] != 'image' && $option_array['type'] != 'export' && $type != 'index') {
		
		$id = $option_array['id'];
		
			if (isset ($id, $option_array) ) {

				if ($option_array['type'] == "text") {
					$new_value = esc_textarea($output[$option_array['id']]);
				} else {
					$new_value = $output[$option_array['id']];		
				}
			$icefit_settings[$id] = stripslashes($new_value);
			}
		
		}
	}
	// Updates settings in the database
	update_option($icefit_settings_slug,$icefit_settings);
}
add_action('wp_ajax_icefit_settings_import_ajax_post_action', 'icefit_settings_import_ajax_callback');

// Update settings template in the database upon theme activation
function icefit_settings_theme_activation() {
	global $icefit_settings_slug;
	// Get current settings from the database
	$icefit_settings = get_option($icefit_settings_slug);
	// Get the settings template
	$options = icefit_settings_template();
	// Updates all settings
	foreach($options as $option_array){
		if ($option_array['type'] != 'start_menu' && $option_array['type'] != 'end_menu' && $option_array['type'] != 'update') {
			$id = $option_array['id'];
			if ( !isset( $icefit_settings[$id] ) && isset( $option_array['default'] ) )
				$icefit_settings[$id] = stripslashes($option_array['default']);
		}

	}
	// Updates settings in the database
	update_option($icefit_settings_slug,$icefit_settings);	
}
add_action('after_switch_theme', 'icefit_settings_theme_activation');

// Outputs the settings panel
function icefit_settings_page(){
	
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
	global $icefit_settings_slug;
	global $icefit_settings_name;

	?>
	<div id="icefit-admin-panel">
		<form enctype="multipart/form-data" id="icefitform">
			<div id="icefit-admin-panel-header">
				<div id="icon-options-general" class="icon32"><br></div>
				<h3><?php echo $icefit_settings_name; ?></h3>
			</div>
			<div id="icefit-admin-panel-main">
				<div id="icefit-admin-panel-menu">
					<ul>
						<?php echo icefit_settings_machine_menu(icefit_settings_template()); ?>
					</ul>
				</div>
				<div id="icefit-admin-panel-content">
					<?php echo icefit_settings_machine(icefit_settings_template()); ?>
				</div>
				<div class="clear"></div>
			</div>
			<div id="icefit-admin-panel-footer">
				<div id="icefit-admin-panel-footer-submit">
					<input type="button" class="button" id="icefit-reset-button" name="reset" value="Reset Options" />
					<input type="submit" value="Save all Changes" class="button-primary" id="submit-button" />
					<div id="ajax-result-wrap"><div id="ajax-result"></div></div>
					<?php wp_nonce_field('icefit_settings_ajax_post_action','icefit_settings_nonce'); ?>
				</div>
			</div>
		</form>
	</div>
	<script type="text/javascript">
	<?php $options = icefit_settings_template(); ?>
		
		jQuery(document).ready(function(){

		<?php
			$has_image = false;
			foreach ($options as $arg) {
				if ( $arg['type'] == "image" ) {
					$has_image = true;
		?>
					jQuery(<?php echo "'#".$arg['id']."_upload'"; ?>).click(function() {
					formfield = <?php echo "'#".$arg['id']."'"; ?>;
					tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
					return false;
					});
					
					jQuery(<?php echo "'#".$arg['id']."_remove'"; ?>).click(function() {
					formfield = <?php echo "'#".$arg['id']."'"; ?>;
					preview = <?php echo "'#".$arg['id']."_preview'"; ?>;
					jQuery(formfield).val(<?php echo "'".get_template_directory_uri(). "/functions/icefit-options/img/null.png'"; ?>);
					jQuery(preview).attr("src",<?php echo "'".get_template_directory_uri(). "/functions/icefit-options/img/null.png'"; ?>);
					return false;
					});
					
<?php $default_val = ( $arg['default'] != "") ? $arg['default'] : ""; 
						$default_prev = ( $arg['default'] != "") ? $arg['default'] : get_template_directory_uri(). "/functions/icefit-options/img/null.png";
					?>
					jQuery(<?php echo "'#".$arg['id']."_default'"; ?>).click(function() {
					formfield = <?php echo "'#".$arg['id']."'"; ?>;
					preview = <?php echo "'#".$arg['id']."_preview'"; ?>;
					jQuery(formfield).val('<?php  echo $default_val; ?>');
					jQuery(preview).attr("src",'<?php echo $default_prev; ?>');
					return false;
					});
					
		<?php	}
			}
			if ($has_image) {
		?>
			window.send_to_editor = function(html) {
				imgurl = jQuery('img',html).attr('src');
				jQuery(formfield).val(imgurl);
				jQuery(formfield+'_preview').attr("src",imgurl);
				tb_remove();
			}
		<?php } ?>
		});
	</script>
	<?php	
}
?>