(function() {
// Creates a new plugin class and a custom listbox
tinymce.create('tinymce.plugins.icefit_columns', {

        init : function(ed, url) {
			window.icefit_columns_url = url;
        },


    createControl: function(n, cm) {
       	var t	= this,
       	url	= icefit_columns_url;
        switch (n) {

            case 'format_column':
                var c = cm.createMenuButton('format_columns', {
                    title : 'Format Columns',
                    image : url+'/format_column.png',
                    icons: false,
                    onclick : function() {}
                });

                c.onRenderMenu.add(function(c, m) {
                    m.add({title : 'Columns', 'class' : 'mceMenuItemTitle'}).setDisabled(1);

                    m.add({title : '1/4', 'class' : '', onclick : function() {
						t._register_formats();
						tinyMCE.activeEditor.formatter.remove('icf_one-third');
						tinyMCE.activeEditor.formatter.remove('icf_two-thirds');
						tinyMCE.activeEditor.formatter.remove('icf_three-fourths');
						tinyMCE.activeEditor.formatter.remove('icf_half');
						tinyMCE.activeEditor.formatter.toggle('icf_one-fourth');
                    }});
                    
                    m.add({title : '1/3', 'class' : '', onclick : function() {
						t._register_formats();
						tinyMCE.activeEditor.formatter.remove('icf_two-thirds');
						tinyMCE.activeEditor.formatter.remove('icf_three-fourths');
						tinyMCE.activeEditor.formatter.remove('icf_half');
						tinyMCE.activeEditor.formatter.remove('icf_one-fourth');
						tinyMCE.activeEditor.formatter.toggle('icf_one-third');
                    }});

                    m.add({title : '1/2', 'class' : '', onclick : function() {
						t._register_formats();
						tinyMCE.activeEditor.formatter.remove('icf_one-third');
						tinyMCE.activeEditor.formatter.remove('icf_two-thirds');
						tinyMCE.activeEditor.formatter.remove('icf_one-fourth');
						tinyMCE.activeEditor.formatter.remove('icf_three-fourths');
						tinyMCE.activeEditor.formatter.toggle('icf_half');
                    }});                    
                    
                    m.add({title : '2/3', 'class' : '', onclick : function() {
						t._register_formats();
						tinyMCE.activeEditor.formatter.remove('icf_three-fourths');
						tinyMCE.activeEditor.formatter.remove('icf_half');
						tinyMCE.activeEditor.formatter.remove('icf_one-fourth');
						tinyMCE.activeEditor.formatter.remove('icf_one-third');
						tinyMCE.activeEditor.formatter.toggle('icf_two-thirds');
                    }});
                    
                    m.add({title : '3/4', 'class' : '', onclick : function() {
						t._register_formats();
						tinyMCE.activeEditor.formatter.remove('icf_half');
						tinyMCE.activeEditor.formatter.remove('icf_one-fourth');
						tinyMCE.activeEditor.formatter.remove('icf_one-third');
						tinyMCE.activeEditor.formatter.remove('icf_two-thirds');
						tinyMCE.activeEditor.formatter.toggle('icf_three-fourths');
                    }});
                    
                    m.add({title : 'remove', 'class' : '', onclick : function() {
						t._register_formats();
						tinyMCE.activeEditor.formatter.remove('icf_half');
						tinyMCE.activeEditor.formatter.remove('icf_one-third');
						tinyMCE.activeEditor.formatter.remove('icf_two-thirds');
						tinyMCE.activeEditor.formatter.remove('icf_one-fourth');
						tinyMCE.activeEditor.formatter.remove('icf_three-fourths');
					}});

                });

                return c;

			case 'remove_column': 
				var c = cm.createMenuButton('remove_column', {
					title	: 'Remove Column Formatting',
					image	: url + '/remove_column.png',
					icons	: false,
					onclick	: function() {
							t._register_formats();
							tinyMCE.activeEditor.formatter.remove('icf_half');
							tinyMCE.activeEditor.formatter.remove('icf_one-third');
							tinyMCE.activeEditor.formatter.remove('icf_two-thirds');
							tinyMCE.activeEditor.formatter.remove('icf_one-fourth');
							tinyMCE.activeEditor.formatter.remove('icf_three-fourths');
						}
					});
				return c;


    		case 'insert_lineBefore': 
				var c = cm.createMenuButton('insert_lineBefore', {
					title	: 'Insert Line Before',
					image	: url + '/insert_lineBefore.png',
					icons	: false,
					onclick	: function() {
						if(window.tinyMCE) {
							var node			= tinyMCE.activeEditor.selection.getNode(),
								parents			= tinyMCE.activeEditor.dom.getParents(node).reverse(),
								oldestParent	= parents[2];
								blank			= document.createElement('p');
		
							blank.innerHTML = "&nbsp;";
		
							if (typeof oldestParent != "undefined") {
								oldestParent.parentNode.insertBefore(blank, oldestParent);
							} else if (typeof node != "undefined") {
								node.parentNode.insertBefore(blank, node);
							}
							var range = document.createRange();
							var textNode = blank;
							range.setStart(textNode, 0);
							range.setEnd(textNode, 0);

							tinyMCE.activeEditor.selection.setRng(range);
						}
					}
				});
				return c;
				
			case 'insert_lineAfter': 
				var c = cm.createMenuButton('insert_lineAfter', {
						title : 'Insert Line After',
						image : url + '/insert_lineAfter.png',
						icons : false,
						onclick : function() {
							if(window.tinyMCE) {
								var node			= tinyMCE.activeEditor.selection.getNode(),
									parents			= tinyMCE.activeEditor.dom.getParents(node).reverse(),
									oldestParent	= parents[2];
									blank			= document.createElement('p');
			
								blank.innerHTML = "&nbsp;";
			
								if (typeof oldestParent != "undefined") {
									tinyMCE.activeEditor.dom.insertAfter(blank, oldestParent);
								} else if (typeof node != "undefined") {
									tinyMCE.activeEditor.dom.insertAfter(blank, node);
								}

								var range = document.createRange();
								var textNode = blank;
								range.setStart(textNode, 0);
								range.setEnd(textNode, 0);
			
								tinyMCE.activeEditor.selection.setRng(range);
							}
						}

					});
				return c;
		}

        return null;
    },
    
	_register_formats: function() {
	tinymce.activeEditor.formatter.register(
		'icf_half',
		{block: 'div', collapsed : false, classes: 'one-half', wrapper : true, merge_siblings : false}
	);
	tinymce.activeEditor.formatter.register(
		'icf_one-third',
		{block: 'div', collapsed : false, classes: 'one-third', wrapper : true, merge_siblings : false}
	);
	tinymce.activeEditor.formatter.register(
		'icf_two-thirds',
		{block: 'div', collapsed : false, classes: 'two-thirds', wrapper : true, merge_siblings : false}
	);
	tinymce.activeEditor.formatter.register(
		'icf_one-fourth',
		{block: 'div', collapsed : false, classes: 'one-fourth', wrapper : true, merge_siblings : false}
	);
	tinymce.activeEditor.formatter.register(
		'icf_three-fourths',
		{block: 'div', collapsed : false, classes: 'three-fourths', wrapper : true, merge_siblings : false}
	);
	},
    
});

// Register plugin with a short name
tinymce.PluginManager.add('format_column', tinymce.plugins.icefit_columns);
})();