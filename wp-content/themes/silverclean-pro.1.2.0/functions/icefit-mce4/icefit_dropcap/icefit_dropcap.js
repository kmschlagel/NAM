(function() {
	tinymce.create('tinymce.plugins.icefit_dropcap', {
		init : function(ed, url) {

			ed.addCommand('insert_dropcap', function() {
				ed.windowManager.open({
					file : url + '/dropcap.htm',
					width : 450 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('dropcap', {title : 'Insert Dropcap', cmd : 'insert_dropcap', image: url + '/dropcap.png' });
		
		},

		getInfo : function() {
			return {
				longname : 'insert_dropcap',
				author : 'Iceable Media',
				authorurl : 'http://www.iceablethemes.com',
				infourl : 'http://www.iceablethemes.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('dropcap', tinymce.plugins.icefit_dropcap);
})();