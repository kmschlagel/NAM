(function() {
	tinymce.create('tinymce.plugins.icefit_tabs', {
		init : function(ed, url) {

			ed.addCommand('insert_tabs', function() {
				ed.windowManager.open({
					file : url + '/tabs.htm',
					width : 450 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('tabs', {title : 'Insert tabbed content', cmd : 'insert_tabs', image: url + '/tabs.png' });
		
		},

		getInfo : function() {
			return {
				longname : 'insert_tabs',
				author : 'Iceable Media',
				authorurl : 'http://www.iceablethemes.com',
				infourl : 'http://www.iceablethemes.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('tabs', tinymce.plugins.icefit_tabs);
})();