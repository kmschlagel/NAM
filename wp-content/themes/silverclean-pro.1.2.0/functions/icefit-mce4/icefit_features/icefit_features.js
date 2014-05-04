(function() {
	tinymce.create('tinymce.plugins.icefit_features', {
		init : function(ed, url) {

			ed.addCommand('insert_features', function() {
				ed.windowManager.open({
					file : url + '/features.htm',
					width : 450 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('features', {title : 'Insert Features', cmd : 'insert_features', image: url + '/features.png' });
		
		},

		getInfo : function() {
			return {
				longname : 'insert_features',
				author : 'Iceable Media',
				authorurl : 'http://www.iceablethemes.com',
				infourl : 'http://www.iceablethemes.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('features', tinymce.plugins.icefit_features);
})();