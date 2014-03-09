(function() {
	tinymce.create('tinymce.plugins.icefit_accordions', {
		init : function(ed, url) {

			ed.addCommand('insert_accordions', function() {
				ed.windowManager.open({
					file : url + '/accordions.htm',
					width : 450 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('accordions', {title : 'Insert content accordion', cmd : 'insert_accordions', image: url + '/accordions.png' });
		
		},

		getInfo : function() {
			return {
				longname : 'insert_accordions',
				author : 'Iceable Media',
				authorurl : 'http://www.iceable.com',
				infourl : 'http://www.iceable.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('accordions', tinymce.plugins.icefit_accordions);
})();