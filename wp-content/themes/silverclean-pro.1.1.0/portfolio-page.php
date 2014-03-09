<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 Mathieu Sarrasin - Iceable Media
 *
 * Portfolio Page Template
 *
 */
 /*
 Template Name: Portfolio
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

			?></ul></div></div><?php // End slider

	endif; // End slider loop
	wp_reset_postdata(); 
	endif; // End slider code

?><div class="container" id="main-content"><?php

		$sidebar_side = get_post_meta(get_the_ID(), 'icefit_pagesettings_sidebar_side', true);
		$page_container_class = "";
		if ($sidebar_side == 'left' || $sidebar_side == 'right') {	
			$content_side = ($sidebar_side == 'left') ? "right" : "left";
			$page_container_class = $content_side . " with-sidebar";
		}
		
		?><div id="page-container" <?php post_class($page_container_class . " portfolio-page"); ?>><?php
	
		$showtitle = get_post_meta(get_the_ID(), 'icefit_pagesettings_showtitle', true);
		if ($showtitle != 'no'):
		?><h1 class="page-title"><?php the_title(); ?></h1><?php
		endif;
		
		if ( 'content_above' == $icefit_options['portfolio_page_content'] ) the_content();

			$portfolio_count = wp_count_posts( 'icf_portfolio' );
			$portfolio_count = $portfolio_count->publish;
			$cats = get_terms('icf-portfolio-category', array() );

			?><ul class="filter"><li class="current all"><a href="#" title="All">All (<?php echo $portfolio_count; ?>)</a></li><?php
				foreach ($cats as $cat):
				echo '<li class="', $cat->slug, '"><a href="#" title="', $cat->slug, '">', $cat->name, ' (', $cat->count, ')</a></li>';
				endforeach;
			?></ul>
			<div class="portfolio"><?php
				$args = array( 'post_type' => 'icf_portfolio', 'posts_per_page' => -1 );
				$loop = new WP_Query( $args );
				if($loop->have_posts()) :
				while( $loop->have_posts() ) : $loop->the_post();
						
				$cats = implode(' ', wp_get_post_terms( get_the_id(), 'icf-portfolio-category', array("fields" => "slugs") ));				
				echo '<div class="one-fourth qsand" data-id="'.get_the_id().'" data-type="'.$cats.'">',
					'<div class="portfolio-item"><div class="portfolio-thumb">';

				if (has_post_thumbnail()) :
					$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'portfolio-thumb');
					echo '<a href="' , get_permalink() , '" title="' , get_the_title() , '">',
						'<img class="scale-with-grid" src="', $thumbnail[0] ,'" alt="', get_the_title() ,'">',
						'</a>';
				endif;
				echo '</div>',
					'<a href="' , get_permalink() , '" title="' , get_the_title() , '">', 
					'<div class="portfolio-desc">',
					'<h3>' , get_the_title() , '</h3>',
					'</div>',
					'</a>',

					'</div></div>';
	
				endwhile;
				endif;
				wp_reset_postdata();

			?><br class="clear" /></div><?php

		if ( 'content_below' == $icefit_options['portfolio_page_content'] ) the_content();

	endwhile;
			
	else :
	
		?><h2><?php _e('Not Found', 'icefit'); ?></h2><?php
		?><p><?php _e('What you are looking for isn\'t here...', 'icefit'); ?></p><?php

	endif;

	?></div><?php

	if ($sidebar_side == 'left' || $sidebar_side == 'right'):
		?><div id="sidebar-container" class="<?php echo $sidebar_side; ?>"><ul id="sidebar"><?php
		dynamic_sidebar( get_post_meta(get_the_ID(), 'icefit_pagesettings_sidebar', true) );
		?></ul></div><?php
	endif;
	?></div><?php
	
get_footer(); ?>