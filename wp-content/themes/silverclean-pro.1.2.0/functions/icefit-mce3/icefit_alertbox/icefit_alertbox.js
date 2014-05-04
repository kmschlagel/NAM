(function() {
	tinymce.create('tinymce.plugins.icefit_alertbox', {
		init : function(ed, url) {

			ed.addCommand('insert_alertbox', function() {
				ed.windowManager.open({
					file : url + '/alertbox.htm',
					width : 450 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('alertbox', {title : 'Insert alertbox', cmd : 'insert_alertbox', image: url + '/alertbox.png' });
		
		},

		getInfo : function() {
			return {
				longname : 'insert_alertbox',
				author : 'Iceable Media',
				authorurl : 'http://www.iceablethemes.com',
				infourl : 'http://www.iceablethemes.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('alertbox', tinymce.plugins.icefit_alertbox);
})();