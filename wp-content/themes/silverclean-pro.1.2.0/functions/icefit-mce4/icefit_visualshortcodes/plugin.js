/*
 * Icefit Visual Shortcodes TinyMCE plugin.
 * Inspired by "Visual Shortcodes" WordPress plugin by John P. Bloch (http://wordpress.org/extend/plugins/visual-shortcodes)
 * and the updated WP gallery TinyMCE plugin in WP 3.9
 * Updated and adapted to work with TinyMCE 4 (as of WP 3.9) and the Icefit Framework
 *
 * Copyright 2014 Mathieu Sarrasin - Iceable Media
 */

(function() {
	// Create the TinyMCE plugin object.
	tinymce.create('tinymce.plugins.icefit_visualshortcodes', {

		/* Initialize the plugin. Set up all the properties necessary to function
		 * and then set some event handlers to make everything work properly.
		 */
		init: function(editor, url) {

			// A counter to assign unique ids to each shortcode in this editor.
			this.counter = 0;
			this.url = url;

			var t = this,
				i,
				shortcode,
				names = [];

			// Define WP shortcodes to hook to this plugin, and their associated TinyMCE command
			t._shortcodes = [
				{shortcode:"blogposts",command:"insert_blogposts"},
				{shortcode:"portfolio",command:"insert_portfolio"},
				{shortcode:"testimonials",command:"insert_testimonials"},
				{shortcode:"features",command:"insert_features"},
				{shortcode:"partners",command:"insert_partners"},
				{shortcode:"contact-form",command:"insert_contactform"},
				{shortcode:"maps",command:"insert_maps"}
			];

			// Set up the shortcodes object and fill it with the shortcodes
			t.shortcodes = {};
			for( i = 0, shortcode = t._shortcodes[i]; i < t._shortcodes.length; shortcode = t._shortcodes[++i]){
				t.shortcodes[shortcode.shortcode] = shortcode;
				names.push(shortcode.shortcode);
			}

			t._buildRegex( names );
			t._createButtons();

			/*
			 * Event handler on the 'mousedown' event. Sets up
			 * the handler to show control buttons if the user clicks a
			 * placeholder
			 */
			editor.on( 'mousedown', function( e ) {
				// We're only interested in images that have the right class
				if( e.target.nodeName == 'IMG' && editor.dom.hasClass(e.target, 'icefit_visual')){
					// Get the name of the shortcode from the ID
					var imgID = e.target.id.replace( /^icefit_placeholder\d+-(.+)$/, '$1' );
					// Check if the shortcode has a command defined.
					if( undefined !== t.shortcodes[imgID] && undefined !== t.shortcodes[imgID].command )
						var showbutton = 'icefit_visual_buttons'; // Show both the delete and edit buttons.
					else
						var showbutton = 'icefit_visual_button'; // Only show the delete button

					// Position the buttons (code based on _showButtons() function from WP tinyMCE plugin)
					var DOM = tinyMCE.DOM;
	
					var rect = e.target.getBoundingClientRect(); // Position of the target placeholder
					var p1, p2, vp, X, Y;

					vp = editor.dom.getViewPort( editor.getWin() );
					p1 = DOM.getPos( editor.getContentAreaContainer() );
					p2 = editor.dom.getPos( e.target );
					// Position buttons on the right of the placeholder (WP3.9-beta2)
					// if ( showbutton = 'icefit_visual_buttons' ) p2.x = rect.right - 82;
					// else p2.x = rect.right - 41;
					
					// Position button on the left, 7px away from the left side (since WP3.9-beta3)
					p2.x += 7;

					X = Math.max( p2.x - vp.x, 0 ) + p1.x;
					Y = Math.max( p2.y - vp.y, 0 ) + p1.y;

					DOM.setStyles( showbutton, {
						'top' : Y + 'px',
						'left' : X + 'px',
						'display': 'block',
						'position': 'absolute',
					});

				} else {
					// If we're not clicking the right kind of image, hide the buttons just in case
					t._hideButtons();
				}
			});

			/*
			 * Event handler for the editor's 'BeforeSetContent' event.
			 * Swaps the shortcode out for its placeholder when the editor is initialized, or
			 * whenever switching from text (HTML) to Visual mode.
			 */	    
			editor.on('BeforeSetContent', function(event) {
				event.content = t._replaceShortcodes(event.content);
			});

			/*
			 * Event handler for the editor's 'PostProcess' event.
			 * Changes the placeholders back to shortcodes before saving the content to the form field
			 * and when switching from Visual mode to text (HTML) mode.
			 */
			editor.on('PostProcess', function(event) {
				if( event.get ) event.content = t._restoreShortcodes(event.content);
			});

			/* Event handler on the editor's initialization event.
			 * Sets up some global event handlers to hide the buttons if the user scrolls or
			 * if they drag something with their mouse.
			 */
			editor.on('Init', function(ed) {
				tinyMCE.DOM.bind(editor.getWin(), 'scroll', t._hideButtons);
				tinyMCE.DOM.bind(editor.getBody(), 'dragstart', t._hideButtons);
			});

		},

		/* Replace shortcodes with their placeholders.
		 * 
		 * The arguments correspond, respectively, to:
		 *  - the whole matched string (the whole shortcode, possibly wrapped in <p> tags)
		 *  - the name of the shortcode
		 *  - the arguments of the shortcode (could be an empty string)
		 * 
		 * The class 'mceItem' prevents WordPress's normal image management icons from showing up.
		 * The attribute data-mce-resize="false" disables the resize handles in TinyMCE 4
		 * 
		 * The arguments of the shortcode are encoded and stored in the 'data-src' attribute of the image.
		 * 
		 * This code is based largely on John P. Bloch's work (which is largely based on the WordPress gallery TinyMCE plugin.)
		 */
		_replaceShortcodes: function(content) {

			var t = this;
			return content.replace( t.regex, function(a,b,c){
				// add new classes
				var shortcode_class = b.replace(/_/, '-');
				shortcode_class = shortcode_class.replace(/_/g, '');
				
				var column = c.match(/col="(.*?)"/);
				if( column && column.length == 2 )
					new_class = shortcode_class + ' ' + column[1];
				else
					new_class = shortcode_class + ' one-fourth';

				return '<p class="helper-tag '+shortcode_class+'"><img src="'+tinymce.Env.transparentSrc+'" id="icefit_placeholder'+(t.counter++)+'-'+b+'" class="mceItem icefit_visual ' + new_class + '" data-src="' + b + tinymce.DOM.encode(c) + '" data-mce-resize="false" /></p>';
			});

		},

		/* Replace images with their respective shortcodes.
		 * This code is based mostly on John P. Bloch's work (which is based mostly on the WordPress gallery TinyMCE plugin.)
		 */
		_restoreShortcodes: function(content) {
				function getAttr(s, n) {
					n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
					return n ? tinymce.DOM.decode(n[1]) : '';
				};
				return content.replace(/(?:<p[^>]*>)*(<img[^>]+>)(?:<\/p>)*/g, function(a,im) {
					var cls = getAttr(im, 'class');
					if ( cls.indexOf('icefit_visual') != -1 )
					return '<p>['+tinymce.trim(getAttr(im, 'data-src'))+']</p>';
					return a;
				});
		},

		/* Builds the plugin's regex for finding registered shortcodes
		 * The regex is global and case insensitive, and only searches for self-closing shortcodes.
		 */
		_buildRegex: function( names ){
			var t = this,
				reString = '';
			reString = '\\\[(' + names.join('|') + ')(( [^\\\]]+)*)\\\]';
			t.regex = new RegExp( reString, 'gi' );
		},

		/* Hide the buttons from the user!
		 */
		_hideButtons: function(){
			tinymce.DOM.hide('icefit_visual_buttons');
			tinymce.DOM.hide('icefit_visual_button');
		},

		/* Creates the action buttons
		 * We need two sets of buttons: one for shortcodes that only get a delete button
		 * and one for shortcodes that get both a delete and an edit button. Set up the
		 * event handlers for all three of the buttons here too.
		 */
		_createButtons: function(){

			// Initialize the variables we need/want
			var t = this,
				ed = tinyMCE.activeEditor,
				DOM = tinyMCE.DOM,
				edbutton,
				delbutton,
				delbutton2;

			// Remove extra copies of our buttons (in case we have multiple editors on the page)
			DOM.remove( 'icefit_visual_buttons' );
			DOM.remove( 'icefit_visual_button' );

			/* Add the divs to hold the buttons and hide them.
			 * 'icefit_visual_buttons' div will have an edit and a delete button
			 * 'icefit_visual_button' will have only a delete button.
			 */
			DOM.add( document.body, 'div', { id: 'icefit_visual_buttons', });
			DOM.add( document.body, 'div', { id: 'icefit_visual_button', });

			// Add the 'edit' button
			edbutton = DOM.add( 'icefit_visual_buttons', 'div', {
				id: 'icefit_visual_editshortcode',
				class: 'icefit_visual_button_icon dashicons dashicons-edit',
			});

			// Add the 'delete' button (to go with the 'edit' button)
			delbutton = DOM.add( 'icefit_visual_buttons', 'div', {
				id: 'icefit_visual_delshortcode',
				class: 'icefit_visual_button_icon dashicons dashicons-no-alt',
			});

			// Add the 'delete' button (to go by itself)
			delbutton2 = DOM.add( 'icefit_visual_button', 'div', {
				id: 'icefit_visual_delshortcode2',
				class: 'icefit_visual_button_icon dashicons dashicons-dismiss',
			});

			// Add the event handler for clicking the 'edit' button
			DOM.bind( edbutton, 'mousedown', function(e){
				// Initialize some variables
				var ed = tinyMCE.activeEditor,
					el = ed.selection.getNode(),
					imgID = el.id.replace( /^icefit_placeholder\d+-(.+)$/, '$1' );
					// pass the content of the selection through "window.temp_GlobalSelection" global variable to modal window
					window.temp_GlobalSelection = ed.selection.getContent();
				if( !imgID || undefined === t.shortcodes[imgID] || undefined === t.shortcodes[imgID].command )
					return; // We don't want to be here if we're not on a valid shortcode with a command

				ed.execCommand( t.shortcodes[imgID].command ); // Execute the command
				t._hideButtons(); // Hide the buttons
			});

			// Add an event handler for both delete buttons to delete the image on click
			DOM.bind( [ delbutton, delbutton2 ], 'mousedown', function(e){
				var ed = tinyMCE.activeEditor, el = ed.selection.getNode(), el2;
				if( el.nodeName == 'IMG' && ed.dom.hasClass( el, 'icefit_visual' ) ){
					// If we have the right kind of image selected, go about deleting it.
					el2 = el.parentNode; // Grab the parent node ahead of time.
					ed.dom.remove( el ); // Get rid of the element
					ed.execCommand( 'mceRepaint' ); // Repaint the editor, just in case.
					t._hideButtons(); // Hide the buttons
					ed.selection.select(el2); // Select the parent element
					return false; // Prevent bubbling.
				}
			});

		},

		getInfo : function() {
			return {
				longname : 'icefit_visualshortcodes',
				author : 'Iceable Media',
				authorurl : 'http://www.iceablethemes.com',
				infourl : 'http://www.iceablethemes.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}

	});
	tinymce.PluginManager.add('icefit_visualshortcodes', tinymce.plugins.icefit_visualshortcodes);
})();