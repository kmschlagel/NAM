(function() {
	tinymce.create('tinymce.plugins.icefit_partners', {
		init : function(ed, url) {

			ed.addCommand('insert_partners', function() {
				ed.windowManager.open({
					file : url + '/partners.htm',
					width : 450 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('partners', {title : 'Insert Partners/Clients', cmd : 'insert_partners', image: url + '/partners.png' });
		
		},

		getInfo : function() {
			return {
				longname : 'insert_partners',
				author : 'Iceable Media',
				authorurl : 'http://www.iceable.com',
				infourl : 'http://www.iceable.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('partners', tinymce.plugins.icefit_partners);
})();