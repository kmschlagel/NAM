(function() {
	tinymce.create('tinymce.plugins.icefit_highlight', {
		init : function(ed, url) {

			ed.addCommand('insert_highlight', function() {
				ed.windowManager.open({
					file : url + '/highlight.htm',
					width : 450 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('highlight', {title : 'Insert Highlight', cmd : 'insert_highlight', image: url + '/highlight.png' });
		
		},

		getInfo : function() {
			return {
				longname : 'insert_highlight',
				author : 'Iceable Media',
				authorurl : 'http://www.iceable.com',
				infourl : 'http://www.iceable.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('highlight', tinymce.plugins.icefit_highlight);
})();