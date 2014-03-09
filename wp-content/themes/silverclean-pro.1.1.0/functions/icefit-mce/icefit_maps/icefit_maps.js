(function() {
	tinymce.create('tinymce.plugins.icefit_maps', {
		init : function(ed, url) {

			ed.addCommand('insert_maps', function() {
				ed.windowManager.open({
					file : url + '/maps.htm',
					width : 450 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('maps', {title : 'Insert Map', cmd : 'insert_maps', image: url + '/maps.png' });
		
		},

		getInfo : function() {
			return {
				longname : 'insert_maps',
				author : 'Iceable Media',
				authorurl : 'http://www.iceable.com',
				infourl : 'http://www.iceable.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('maps', tinymce.plugins.icefit_maps);
})();