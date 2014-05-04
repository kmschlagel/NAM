(function() {
	tinymce.create('tinymce.plugins.icefit_testimonials', {
		init : function(ed, url) {

			ed.addCommand('insert_testimonials', function() {
				ed.windowManager.open({
					file : url + '/testimonials.htm',
					width : 450 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('testimonials', {title : 'Insert Testimonials', cmd : 'insert_testimonials', image: url + '/testimonials.png' });
		
		},

		getInfo : function() {
			return {
				longname : 'insert_testimonial',
				author : 'Iceable Media',
				authorurl : 'http://www.iceablethemes.com',
				infourl : 'http://www.iceablethemes.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('testimonials', tinymce.plugins.icefit_testimonials);
})();