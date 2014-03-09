<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 Mathieu Sarrasin - Iceable Media
 *
 * 404 Page Template
 *
 */
?>

<?php get_header(); ?>
<div class="container" id="main-content">
<div id="page-container">
<h1 class="page-title"><?php _e('404: Page Not Found', 'icefit'); ?></h1>

<div class="one-third">
<h2><?php _e('Not Found', 'icefit'); ?></h2>
<p><?php _e('What you are looking for isn\'t here...', 'icefit'); ?></p>
<p><?php _e('Maybe a search will help ?', 'icefit'); ?></p>
<p><?php get_search_form(); ?></p>
</div>

<div class="one-third">
<h2><?php _e('Monthly Archives', 'icefit'); ?></h2>
<ul><?php wp_get_archives('type=monthly'); ?></ul>
<h2><?php _e('All Categories', 'icefit'); ?></h2>
<ul><?php wp_list_categories( array('title_li' => '') ); ?></ul>
</div>

<div class="one-third">
<h2><?php _e('All Pages', 'icefit'); ?></h2>
<ul><?php wp_list_pages( array('title_li' => '') ); ?></ul>
</div>

</div></div>
<?php get_footer(); ?>