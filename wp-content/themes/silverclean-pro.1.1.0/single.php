<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 Mathieu Sarrasin - Iceable Media
 *
 * Single Post Template
 *
 */
?>

<?php get_header();

	global $icefit_options;
	
	if ( $icefit_options['blog_single_slider'] == "On" ): // Check if slider is activated for blog single
		$args = array( 'post_type' => 'icf_slides', 'posts_per_page' => -1 ); // Prepare arguments for WP_query: query slides
		$slides_cat = $icefit_options['blog_slides_cat']; // Check slides category selection 
		if ($slides_cat != 'All Slides') // If a category is selected, filter the slides
			$args['icf-slides-category'] = $slides_cat;
		$loop = new WP_Query( $args ); // Begin slider loop
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

		$blog_sidebar_side = strtolower( $icefit_options['blog_sidebar_side'] );
		if ($blog_sidebar_side == 'right' || $blog_sidebar_side == ''):
			$blog_sidebar_side = 'right';
			$page_container_side = 'left';
		else:
			$page_container_side = 'right';
		endif;

		?><div id="page-container" class="<?php echo $page_container_side; ?> with-sidebar"><?php

		if(have_posts()):
			while(have_posts()) : the_post();

			?><div id="post-<?php the_ID(); ?>" <?php post_class("single-post"); ?>>

			<div class="post-content">
			<div class="postmetadata"><?php
				if (has_post_thumbnail() && "Off" != $icefit_options['show_single_post_thumbnail']):
				?><div class="thumbnail"><a rel="prettyPhoto" href="<?php
					$image_id = get_post_thumbnail_id();
					$image_url = wp_get_attachment_image_src($image_id,'large', true);
					echo $image_url[0];  ?>"><?php
					the_post_thumbnail('post-thumbnail', array('class' => 'scale-with-grid'));
					?></a>
				</div><?php
				endif;
				?><span class="meta-date"><?php the_time(get_option('date_format')); ?></span><?php
				?><span class="meta-author"><?php _e('By ', 'icefit'); the_author(); ?></span><?php
				?><span class="meta-category"><?php _e('In ', 'icefit'); the_category(', ') ?></span><?php
				if (has_tag()) { echo '<span class="tags">'; the_tags('<span class="tag">', '</span><span>', '</span></span>'); }
				edit_post_link(__('Edit', 'icefit'), '<span class="editlink">', '</span>');
			?></div><h1 class="entry-title"><?php the_title(); ?></h1><?php
			the_content();
			?></div><?php // End post-content
			
			?><div class="clear" /></div><?php
			
			$args = array(
				'before'           => '<br class="clear" /><div class="paged_nav">' . __('Pages:', 'icefit'),
				'after'            => '</div>',
				'link_before'      => '',
				'link_after'       => '',
				'next_or_number'   => 'number',
				'nextpagelink'     => __('Next page', 'icefit'),
				'previouspagelink' => __('Previous page', 'icefit'),
				'pagelink'         => '%',
				'echo'             => 1
			);
			wp_link_pages( $args );

			?></div><?php // end div post
		
			// Display comments section only if comments are open or if there are comments already.
			if ( comments_open() || get_comments_number()!=0 ):
				?><hr /><div class="comments"><?php
				comments_template( '', true );
				next_comments_link(); previous_comments_link();
				?></div><?php
			endif;

			?><div class="article_nav"><?php
						if ("" != get_adjacent_post( false, "", false ) ): // Is there a previous post?
							?><div class="next"><?php next_post_link('%link', "Next Post"); ?></div><?php
						endif;
						if ("" != get_adjacent_post( false, "", true ) ): // Is there a next post?
							?><div class="previous"><?php  previous_post_link('%link', "Previous Post"); ?></div><?php
						endif;
						?><br class="clear" /></div><?php

			endwhile;

		else: // Empty loop (this should never happen!)

			?><h2><?php _e('Not Found', 'icefit'); ?></h2><?php
			?><p><?php _e('What you are looking for isn\'t here...', 'icefit'); ?></p><?php

		endif;

		?></div><?php // End page container
		
		?><div id="sidebar-container" class="<?php echo $blog_sidebar_side; ?>"><?php
			get_sidebar();
		?></div><?php

	?></div><?php

get_footer(); ?>