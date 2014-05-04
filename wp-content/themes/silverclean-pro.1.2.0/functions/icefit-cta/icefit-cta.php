<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 - 2014 Mathieu Sarrasin - Iceable Media
 *
 * Call to action shortcode
 *
 */


/* ---------------- CTA Shortcode ---------------- */

function icefit_cta_shortcode($atts, $content = NULL) {
	extract( shortcode_atts( array( 'title' => '', 'button' => '', 'link' => '' ), $atts ) );
	
	$output = '<div class="cta">';
	if ('' != $button && '' != $link) $output .= '<div class="cta-button"><a class="button blue" href="'.$link.'">'.$button.'</a></div>';
	if ('' != $button && '' != $link) $output .= '<div class="cta-text-with-button">';
	else $output .= '<div class="cta-text">';
	if ('' != $title) $output .= '<h3>'.$title.'</h3>';
	if ('' != $content) $output .= '<p>'.$content.'</p>';
	$output .=  '</div>';
	$output .=  '</div>';

	return $output;
}
add_shortcode('cta', 'icefit_cta_shortcode');

?>