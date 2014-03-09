(function() {
	tinymce.create('tinymce.plugins.icefit_blogposts', {
		init : function(ed, url) {

			ed.addCommand('insert_blogposts', function() {
				ed.windowManager.open({
					file : url + '/blogposts.htm',
					width : 450 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('blogposts', {title : 'Insert Blogposts', cmd : 'insert_blogposts', image: url + '/blogposts.png' });
		
		},

		getInfo : function() {
			return {
				longname : 'insert_blogposts',
				author : 'Iceable Media',
				authorurl : 'http://www.iceable.com',
				infourl : 'http://www.iceable.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('blogposts', tinymce.plugins.icefit_blogposts);
})();