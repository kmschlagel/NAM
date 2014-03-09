(function() {
	tinymce.create('tinymce.plugins.icefit_toggles', {
		init : function(ed, url) {

			ed.addCommand('insert_toggles', function() {
				ed.windowManager.open({
					file : url + '/toggles.htm',
					width : 450 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('toggles', {title : 'Insert content toggles', cmd : 'insert_toggles', image: url + '/toggles.png' });
		
		},

		getInfo : function() {
			return {
				longname : 'insert_toggles',
				author : 'Iceable Media',
				authorurl : 'http://www.iceable.com',
				infourl : 'http://www.iceable.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('toggles', tinymce.plugins.icefit_toggles);
})();