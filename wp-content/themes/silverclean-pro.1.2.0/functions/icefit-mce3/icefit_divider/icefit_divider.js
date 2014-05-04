(function() {
    tinymce.create('tinymce.plugins.icefit_divider', {
        init : function(ed, url) {
            ed.addButton('add_divider', {
                title : 'Insert Divider',
                image : url+'/divider.png',
                onclick : function() {
                     ed.selection.setContent('<hr />');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('add_divider', tinymce.plugins.icefit_divider);
})();