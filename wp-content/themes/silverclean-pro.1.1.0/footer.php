<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 Mathieu Sarrasin - Iceable Media
 *
 * Footer Template
 *
 */ 
?>
<?php

	global $icefit_options;

	if ( is_page() ) $footer = get_post_meta(get_the_ID(), 'icefit_pagesettings_footer', true);
			else $footer = 'footer-sidebar';
		if ( '' == $footer ) $footer = 'footer-sidebar';
		if ( $footer != 'no-footer-widget' && is_active_sidebar($footer) ):

		?><div id="footer"><div class="container"><ul><?php
			dynamic_sidebar( $footer );
		?></ul></div></div><?php

	endif;

	?><div id="sub-footer"><div class="container"><?php			
			$creation_year = $icefit_options['copyright_start_year'];
			$current_year = date('Y');
			if ($current_year == $creation_year) $copyright_years = $creation_year;
			else $copyright_years = $creation_year.'-'.$current_year;
			$footer_note = $icefit_options['footer_note'];
			$footer_note = str_replace("%date%", $copyright_years, $footer_note);
			$footer_note = str_replace("%sitename%", get_bloginfo('name'), $footer_note );
			$footer_note = htmlspecialchars_decode( $footer_note );
		?><p><?php echo $footer_note;

	?></p></div></div><?php // End footer

?></div><?php

wp_footer();
?></body>
</html>