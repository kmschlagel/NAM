(function() {
	tinymce.create('tinymce.plugins.icefit_columns', {
		init : function(ed, url) {

			ed.addCommand('insert_columns', function() {
				ed.windowManager.open({
					file : url + '/columns.htm',
					width : 450 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('columns', {
				title : 'Insert columns',
				cmd : 'insert_columns',
				image: url + '/columns.png'
			});
		
		},

		getInfo : function() {
			return {
				longname : 'insert_columns',
				author : 'Iceable Media',
				authorurl : 'http://www.iceablethemes.com',
				infourl : 'http://www.iceablethemes.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('columns', tinymce.plugins.icefit_columns);
})();