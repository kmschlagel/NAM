(function() {
    tinymce.create('tinymce.plugins.icefit_lineafter', {
        init : function(ed, url) {
            ed.addButton('lineafter', {
                title : 'Insert Line After',
                image : url+'/lineafter.png',
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
							}                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('lineafter', tinymce.plugins.icefit_lineafter);
})();