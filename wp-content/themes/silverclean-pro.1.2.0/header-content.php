<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 - 2014 Mathieu Sarrasin - Iceable Media
 *
 * Header Content Template
 *
 */
?>
<div id="header"><div class="container"><div id="logo"><a href="<?php echo home_url(); ?>"><?php

	global $icefit_options;

	$logo_url = $icefit_options['logo'];

	if ( $icefit_options['header_title'] == 'Display Title'
			|| $logo_url == ""
			|| "null.png" == substr($logo_url, -8) ):
		?><span class="site-title"><?php echo bloginfo('name'); ?></span><?php
	else:
		?><img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo bloginfo('name'); ?>"><?php
	endif;
		?></a></div><?php

	if( $icefit_options['header_layout'] == 'Logo left + Tagline' ):
		?><div id="tagline"><?php echo bloginfo('description'); ?></div><?php
	endif;		

	if( $icefit_options['header_layout'] == 'Logo Left + Social Media' ):
		?><div id="social-media"><?php echo icefit_socialmedia_widget_content(); ?></div><?php
	endif;
	
	if( $icefit_options['header_layout'] == 'Logo Left + Custom HTML' ):
		?><div id="header-right"><?php echo $icefit_options['header_custom_HTML']; ?></div><?php
	endif;
			
?></div></div>