(function() {
// Creates a new plugin class and a custom listbox
tinymce.create('tinymce.plugins.icefit_blockquote', {

        init : function(ed, url) {
			window.icefit_blockquote_url = url;
        },

 createControl: function(n, cm) {
       	var t	= this,
       	url	= icefit_blockquote_url;
        switch (n) {

            case 'add_blockquote':
                var c = cm.createMenuButton('add_blockquote', {
                    title : 'Insert Blockquote',
                    image : url+'/blockquote.png',
                    icons: false,
                    onclick : function() {}
                });

                c.onRenderMenu.add(function(c, m) {
                    m.add({title : 'Blockquote', 'class' : 'mceMenuItemTitle'}).setDisabled(1);

                    m.add({title : 'Normal', 'class' : '', onclick : function() {
						t._register_formats();
						tinyMCE.activeEditor.formatter.remove('right');
						tinyMCE.activeEditor.formatter.remove('left');
						tinyMCE.activeEditor.formatter.remove('center');
						tinyMCE.activeEditor.formatter.toggle('normal');
                    }});
                    
                    m.add({title : 'Centered', 'class' : '', onclick : function() {
						t._register_formats();
						tinyMCE.activeEditor.formatter.remove('right');
						tinyMCE.activeEditor.formatter.remove('left');
						tinyMCE.activeEditor.formatter.remove('normal');
						tinyMCE.activeEditor.formatter.toggle('center');
                    }});

                    m.add({title : 'Left Pull Quote', 'class' : '', onclick : function() {
						t._register_formats();
						tinyMCE.activeEditor.formatter.remove('right');
						tinyMCE.activeEditor.formatter.remove('center');
						tinyMCE.activeEditor.formatter.remove('normal');
						tinyMCE.activeEditor.formatter.toggle('left');
                    }});                    
                    
                    m.add({title : 'Right Pull Quote', 'class' : '', onclick : function() {
						t._register_formats();
						tinyMCE.activeEditor.formatter.remove('left');
						tinyMCE.activeEditor.formatter.remove('center');
						tinyMCE.activeEditor.formatter.remove('normal');
						tinyMCE.activeEditor.formatter.toggle('right');
                    }});

                    m.add({title : 'Remove', 'class' : '', onclick : function() {
						t._register_formats();
						tinyMCE.activeEditor.formatter.remove('normal');
						tinyMCE.activeEditor.formatter.remove('left');
						tinyMCE.activeEditor.formatter.remove('center');
						tinyMCE.activeEditor.formatter.remove('right');
                    }});

                });

                return c;

		}

        return null;
    },
    
    	_register_formats: function() {
	tinymce.activeEditor.formatter.register(
		'normal',
		{block: 'blockquote', collapsed : false, classes: 'normal', wrapper : true, merge_siblings : false}
	);
	tinymce.activeEditor.formatter.register(
		'center',
		{block: 'blockquote', collapsed : false, classes: 'center', wrapper : true, merge_siblings : false}
	);
	tinymce.activeEditor.formatter.register(
		'left',
		{block: 'blockquote', collapsed : false, classes: 'left', wrapper : true, merge_siblings : false}
	);
	tinymce.activeEditor.formatter.register(
		'right',
		{block: 'blockquote', collapsed : false, classes: 'right', wrapper : true, merge_siblings : false}
	);
	},

});

// Register plugin with a short name
tinymce.PluginManager.add('add_blockquote', tinymce.plugins.icefit_blockquote);
})();