(function() {
	tinymce.create('tinymce.plugins.icefit_sliders', {
		init : function(ed, url) {

			ed.addCommand('insert_sliders', function() {
				ed.windowManager.open({
					file : url + '/sliders.htm',
					width : 450 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('sliders', {title : 'Insert content slider', cmd : 'insert_sliders', image: url + '/sliders.png' });
		
		},

		getInfo : function() {
			return {
				longname : 'insert_sliders',
				author : 'Iceable Media',
				authorurl : 'http://www.iceable.com',
				infourl : 'http://www.iceable.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('sliders', tinymce.plugins.icefit_sliders);
})();