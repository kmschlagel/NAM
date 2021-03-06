<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 Mathieu Sarrasin - Iceable Media
 *
 * Sidebar Template
 *
 */
?>

<ul id="sidebar"><?php

	if ( ! dynamic_sidebar( 'Sidebar' ) ) :

	?><li id="recent" class="widget-container">
	    <h3 class="widget-title"><?php _e( 'Recent Posts', 'icefit' ); ?></h3>
		<ul><?php wp_get_archives( 'type=postbypost&limit=5' ); ?></ul>
	</li>

	<li id="archives" class="widget-container">
	    <h3 class="widget-title"><?php _e( 'Archives', 'icefit' ); ?></h3>
		<ul><?php wp_get_archives( 'type=monthly' ); ?></ul>
	</li>

	<li id="meta" class="widget-container">
		<h3 class="widget-title"><?php _e( 'Meta', 'icefit' ); ?></h3>
		<ul>
		<?php wp_register(); ?>
		<li><?php wp_loginout(); ?></li>
		<?php wp_meta(); ?>
		</ul>
		</li>
	<?php endif; ?>
</ul>