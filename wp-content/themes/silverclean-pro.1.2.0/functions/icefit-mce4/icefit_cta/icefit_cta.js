(function() {
	tinymce.create('tinymce.plugins.icefit_cta', {
		init : function(ed, url) {

			ed.addCommand('insert_cta', function() {
				ed.windowManager.open({
					file : url + '/cta.htm',
					width : 450 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('cta', {title : 'Insert Call to Action', cmd : 'insert_cta', image: url + '/cta.png' });
		
		},

		getInfo : function() {
			return {
				longname : 'insert_cta',
				author : 'Iceable Media',
				authorurl : 'http://www.iceablethemes.com',
				infourl : 'http://www.iceablethemes.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('cta', tinymce.plugins.icefit_cta);
})();