(function() {
    tinymce.create('tinymce.plugins.icefit_linebefore', {
        init : function(ed, url) {
            ed.addButton('linebefore', {
                title : 'Insert Line before',
                image : url+'/linebefore.png',
                onclick : function() {
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
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('linebefore', tinymce.plugins.icefit_linebefore);
})();