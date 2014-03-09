(function() {
	tinymce.create('tinymce.plugins.icefit_button', {
		init : function(ed, url) {

			ed.addCommand('insert_button', function() {
				ed.windowManager.open({
					file : url + '/button.htm',
					width : 450 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('button', {title : 'Insert button', cmd : 'insert_button', image: url + '/button.png' });
		
		},

		getInfo : function() {
			return {
				longname : 'insert_button',
				author : 'Iceable Media',
				authorurl : 'http://www.iceable.com',
				infourl : 'http://www.iceable.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('button', tinymce.plugins.icefit_button);
})();