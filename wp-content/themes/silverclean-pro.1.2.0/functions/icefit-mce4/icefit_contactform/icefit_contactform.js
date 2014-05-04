(function() {
	tinymce.create('tinymce.plugins.icefit_contactform', {
		init : function(ed, url) {

			ed.addCommand('insert_contactform', function() {
				ed.windowManager.open({
					file : url + '/contactform.htm',
					width : 450 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('contactform', {title : 'Insert Contact Form', cmd : 'insert_contactform', image: url + '/contactform.png' });
		
		},

		getInfo : function() {
			return {
				longname : 'insert_contactform',
				author : 'Iceable Media',
				authorurl : 'http://www.iceablethemes.com',
				infourl : 'http://www.iceablethemes.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('contactform', tinymce.plugins.icefit_contactform);
})();