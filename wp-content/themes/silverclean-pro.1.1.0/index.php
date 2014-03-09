<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 Mathieu Sarrasin - Iceable Media
 *
 * Main Index
 *
 */
?>

<?php get_header();

global $icefit_options;

	if ( $icefit_options['blog_slider'] == "On" ): 	// Check whether slider is activated for blog index
		$args = array( 'post_type' => 'icf_slides', 'posts_per_page' => -1 ); // Prepare arguments for WP_query: query slides
		$slides_cat = $icefit_options['blog_slides_cat']; // Check slides category selection
		if ($slides_cat != 'All Slides') // If a category is selected, filter the slides 
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
	wp_reset_postdata(); // Reset the main loop
	endif; // End slider code

	?><div id="main-content" class="container"><?php

		$blog_sidebar_side = strtolower( $icefit_options['blog_sidebar_side'] );
		if ($blog_sidebar_side == 'right' || $blog_sidebar_side == ''):
			$blog_sidebar_side = 'right';
			$page_container_side = 'left';
		else:
			$page_container_side = 'right';
		endif;

		?><div id="page-container" class="<?php echo $page_container_side; ?> with-sidebar"><?php

		/* SEARCH CONDITIONAL TITLE */
		if ( is_search() ) :
		?><h1 class="page-title"><?php _e('Search Results for ', 'icefit'); ?>"<?php the_search_query() ?>"</h1><?php
		endif;
		
		/* TAG CONDITIONAL TITLE */
		if ( is_tag() ):
		?><h1 class="page-title"><?php _e('Tag: ', 'icefit'); single_tag_title(); ?></h1><?php
		endif;

		/* CATEGORY CONDITIONAL TITLE */
		if ( is_category() ):
		?><h1 class="page-title"><?php _e('Category: ', 'icefit'); single_cat_title(); ?></h1><?php
		endif;

		/* ARCHIVES CONDITIONAL TITLE */
		if ( is_day() ):
		?><h1 class="page-title"><?php _e('Daily archives: ', 'icefit'); echo get_the_time('F jS, Y'); ?></h1><?php
		endif;
		
		if ( is_month() ):
		?><h1 class="page-title"><?php _e('Monthly archives: ', 'icefit'); echo get_the_time('F, Y'); ?></h1><?php
		endif;
		if ( is_year() ):
		?><h1 class="page-title"><?php _e('Yearly archives: ', 'icefit'); echo get_the_time('Y'); ?></h1><?php
		endif;

		/* DEFAULT CONDITIONAL TITLE */ 
		if (!is_front_page() && !is_search() && !is_tag() && !is_category() && !is_year() && !is_month() && !is_day()
			&& "off" != $icefit_options['blog_index_page_title'] ):	
		?><h1 class="page-title"><?php echo get_the_title(get_option('page_for_posts')); ?></h1><?php
		endif;

		if(have_posts()):
		while(have_posts()) : the_post();

?><div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="post-contents">
<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
<div class="post-content"><?php

			$blog_index_content = $icefit_options['blog_index_content'];
			if ($blog_index_content == "Default Excerpt" || $blog_index_content == "Icefit Improved Excerpt"):
				the_excerpt();
			else:
				the_content();
			endif;
	?></div></div><div class="postmetadata"><?php

			if (has_post_thumbnail()):
				?><div class="thumbnail"><?php
				echo '<a href="' . get_permalink() . '" title="' . get_the_title() . '">';
				the_post_thumbnail('post-thumbnail', array('class' => 'scale-with-grid')); ?></a></div><?php

			endif;
			?><span class="meta-date"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php
				the_time(get_option('date_format'));
			?></a></span><span class="meta-author"><?php
				_e('By ', 'icefit'); the_author();
			?></span><span class="meta-category"><?php
				_e('In ', 'icefit'); the_category(', ')
			?></span><span class="meta-comments"><?php
				comments_popup_link( __( 'No Comment', 'icefit' ), __( '1 Comment', 'icefit' ), __( '% Comments', 'icefit' ) );
			?></span><?php
				if (has_tag()):
					echo '<span class="tags">';
					the_tags('<span class="tag">', '</span><span>', '</span></span>');
				endif;
			edit_post_link( __('Edit', 'icefit'), '<span class="editlink">', '</span>');
			?></div></div><?php // end div post

			?><hr /><?php // Post separator

		endwhile;

		else: // If there is no post in the loop

			if ( is_search() ): // Empty search results

				?><h2><?php _e('Not Found', 'icefit'); ?></h2>
				<p><?php echo sprintf( __('Your search for "%s" did not return any result.', 'icefit'), get_search_query() ); ?><br /><?php
				_e('Would you like to try another search ?', 'icefit'); ?></p><?php
				get_search_form();

			else: // Empty loop (this should never happen!)

				?><h2><?php _e('Not Found', 'icefit'); ?></h2>
				<p><?php _e('What you are looking for isn\'t here...', 'icefit'); ?></p><?php

			endif;

		endif;

		?><div class="page_nav"><?php
		
		if ( null != get_next_posts_link() ):
			?><div class="previous"><?php next_posts_link( __('Previous Posts', 'icefit') ); ?></div><?php
			endif;
			if ( null != get_previous_posts_link() ):
			?><div class="next"><?php previous_posts_link( __('Next Posts', 'icefit') ); ?></div><?php
			endif;
		?></div>

		</div><?php // End page container ?>

		<div id="sidebar-container" class="<?php echo $blog_sidebar_side; ?>"><?php get_sidebar(); ?></div>		

	</div><?php //  End main content

get_footer(); ?>