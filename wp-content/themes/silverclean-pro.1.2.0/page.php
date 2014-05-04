<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 - 2014 Mathieu Sarrasin - Iceable Media
 *
 * Page Template
 *
 */
?>

<?php get_header();

	if(have_posts()) :
	while(have_posts()) : the_post();
	
	$slider = get_post_meta(get_the_ID(), 'icefit_pagesettings_slider', true); // Is slider activated ?
	if ($slider == 'on'): // Begin slider code
		$args = array( 'post_type' => 'icf_slides', 'posts_per_page' => -1 ); // Prepare arguments for WP_query: query slides
		$slides_cat = get_post_meta(get_the_ID(), 'icefit_pagesettings_slides_cat', true); // Check slides category selection
		if ($slides_cat != 'all') // If a category is selected, filter the slides
			$args['icf-slides-category'] = $slides_cat;
		$loop = new WP_Query( $args ); // Initiate slider loop
		if($loop->have_posts()):
?><div id="slider-wrap" class="flexslider-container container"><div class="flexslider"><ul class="slides"><?php

			while( $loop->have_posts() ) : $loop->the_post();
			
				if ( has_post_thumbnail() ):
				?><li><?php
					$slide = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
					$caption = get_post_meta($post->ID, 'icf_slides_caption', true);
					$link = get_post_meta($post->ID, 'icf_slides_link', true);
					if ($link):
						?><a href="<?php echo $link; ?>"><img class="scale-with-grid" src="<?php echo $slide; ?>" alt="" /></a><?php				
					else:
						?><img class="scale-with-grid" src="<?php echo $slide; ?>" alt="" /><?php
					endif;
					
					if($caption):
				// Embed $caption output in __() for compatibility with translation plugins like qTranslate
				?><div class="flex-caption"><p><?php echo __( $caption ); ?></p></div><?php
					endif;
					?></li><?php

				endif;
			endwhile;

			?></ul></div></div><?php

	endif; // End slider loop
	wp_reset_postdata(); 
	endif; // End slider code

?><div class="container" id="main-content"><?php

		$sidebar_side = get_post_meta(get_the_ID(), 'icefit_pagesettings_sidebar_side', true);
		$page_container_class = "";
		if ($sidebar_side == 'left' || $sidebar_side == 'right'):
			$content_side = ($sidebar_side == 'left') ? "right" : "left";
			$page_container_class = $content_side . " with-sidebar";
		endif;
	?><div id="page-container" <?php post_class($page_container_class); ?>><?php
	
		$showtitle = get_post_meta(get_the_ID(), 'icefit_pagesettings_showtitle', true);
		if ($showtitle != 'no'):
		?><h1 class="page-title"><?php the_title(); ?></h1><?php
		endif;
	
		if (has_post_thumbnail()):
			?><div class="thumbnail"><a rel="prettyPhoto" href="<?php $image_id = get_post_thumbnail_id();
			$image_url = wp_get_attachment_image_src($image_id,'large', true);
			echo $image_url[0];  ?>"><?php
			the_post_thumbnail('large', array('class' => 'scale-with-grid'));
			?></a></div><?php
		endif;
	
		the_content();
	
		?><br class="clear" /><?php 
		edit_post_link(__('Edit', 'icefit'), '<p class="editlink">', '</p>');
	
		// Display comments section only if comments are open or if there are comments already.
		if ( comments_open() || get_comments_number()!=0 ):
			?><div class="comments"><?php
			comments_template( '', true );
			next_comments_link(); previous_comments_link();
			?></div><?php
		endif;

	endwhile;

	else: // Empty loop (this should never happen!)

		?><h2><?php _e('Not Found', 'icefit'); ?></h2>
		<p><?php _e('What you are looking for isn\'t here...', 'icefit'); ?></p><?php

	endif;

	?></div><?php // End page container

	if ($sidebar_side == 'left' || $sidebar_side == 'right'):
		?><div id="sidebar-container" class="<?php echo $sidebar_side; ?>"><ul id="sidebar"><?php
		dynamic_sidebar( get_post_meta(get_the_ID(), 'icefit_pagesettings_sidebar', true) );
		?></ul></div><?php
	endif;
	?></div><?php

get_footer(); ?>