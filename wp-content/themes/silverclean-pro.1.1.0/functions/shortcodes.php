<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 Mathieu Sarrasin - Iceable Media
 *
 * Shortcodes
 *
 */

/*-------- Buttons shortcode -------*/

function button_shortcode( $atts, $content = null ) {
   extract( shortcode_atts( array(
   									'color' => 'silver',
   									'link' => '' ,
   									'size' => 'small'
   									), $atts ) );
   return '<a class="button button-'. esc_attr($size) . ' ' . esc_attr($color) . '" href="' . esc_attr($link) . '">' . $content . '</a>';
}

add_shortcode( 'button', 'button_shortcode' );

/*-------- Tabs shortcode -------*/

function tabs_shortcode( $atts, $content ){
	$GLOBALS['tab_count'] = 0;
	$GLOBALS['tab_set'] = (!isset($GLOBALS['tab_set'])) ? 0 : $GLOBALS['tab_set']+1;
	do_shortcode( $content );
	if( is_array( $GLOBALS['tabs'] ) ){
		foreach( $GLOBALS['tabs'] as $tab ):
			$tabs[] = '<li><a href="#'.$tab['id'].'">'.$tab['title'].'</a></li>';
			$panes[] = '<li id="'.$tab['id'].'">'.$tab['content'].'</li>';
		endforeach;
		$return = "\n" . '<ul class="tabs">' . implode( "\n", $tabs ) . "</ul>\n" . '<ul class="tabs-content">' . implode( "\n", $panes ) . "</ul>\n";
   }
   return $return; }

function tab_shortcode( $atts, $content ){
	extract(shortcode_atts(array( 'title' => '%d', 'id' => '%d' ), $atts));
	$x = $GLOBALS['tab_count'];
	$GLOBALS['tabs'][$x] = array(
		'title' => sprintf( $title, $GLOBALS['tab_count'] ),
		'content' =>  do_shortcode($content),
		'id' =>  "tab" . $GLOBALS['tab_set'] . "-" . $x
	);
	$GLOBALS['tab_count']++;
}

add_shortcode( 'tabs', 'tabs_shortcode' );
add_shortcode( 'tab', 'tab_shortcode' );

/*--- Toggles ---*/

function toggles_shortcode( $atts, $content = null ) {
	return '<div class="toggles">'. do_shortcode($content) .'</div>';
	}

function toggle_shortcode( $atts, $content = null ) {
	extract(shortcode_atts(array( 'title' => '' ), $atts));
	$output = '';
	$output .= '<div class="toggle"><p class="trigger">' .$title. '</p>';
	$output .= '<div class="toggle_container">';
	$output .= do_shortcode($content);
	$output .= '</div></div>';

	return $output;
	}

add_shortcode('toggles', 'toggles_shortcode');
add_shortcode('toggle', 'toggle_shortcode');

/*--- Accordions ---*/

function accordions_shortcode( $atts, $content = null ) {
	return '<div class="accordions">'. do_shortcode($content) .'</div>';
	}

function accordion_shortcode( $atts, $content = null ) {
	extract(shortcode_atts(array( 'title' => '' ), $atts));
	$output = '';
	$output .= '<div class="accordion"><p class="trigger">' .$title. '</p>';
	$output .= '<div class="accordion_container">';
	$output .= do_shortcode($content);
	$output .= '</div></div>';

	return $output;
	}

add_shortcode('accordions', 'accordions_shortcode');
add_shortcode('accordion', 'accordion_shortcode');

/*--- Content Sliders ---*/

function sliders_shortcode( $atts, $content = null ) {
	extract(shortcode_atts(array( 'auto' => 'false' ), $atts));
	$auto = ($auto == 'false') ? "" : " auto";
	return '<div class="content-slider-wrap caroufredsel-wrap"><ul class="content-slider caroufredsel'.$auto.'">'. do_shortcode($content) .'</ul><a class="prev"></a><a class="next"></a></div>';
}


function slider_shortcode( $atts, $content = null ) {
	extract(shortcode_atts(array( 'title' => '' ), $atts));
	$output = '<li>';
	if ( '' != $title )
		$output .= '<p class="slide-title">' .$title. '</p>';
	$output .= '<p class="slide-content">';
	$output .= do_shortcode($content);
	$output .= '</p></li>';
	return $output;
}

add_shortcode('sliders', 'sliders_shortcode');
add_shortcode('slider', 'slider_shortcode');


/*---------- Enable Shortcodes in excerpts and widgets ------------*/

add_filter('widget_text', 'do_shortcode');
add_filter( 'the_excerpt', 'do_shortcode');
add_filter('get_the_excerpt', 'do_shortcode');


?>