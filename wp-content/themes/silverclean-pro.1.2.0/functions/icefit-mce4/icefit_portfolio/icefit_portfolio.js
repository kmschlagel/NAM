(function() {
	tinymce.create('tinymce.plugins.icefit_portfolio', {
		init : function(ed, url) {

			ed.addCommand('insert_portfolio', function() {
				ed.windowManager.open({
					file : url + '/portfolio.htm',
					width : 450 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('example.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('portfolio', {title : 'Insert Portfolio', cmd : 'insert_portfolio', image: url + '/portfolio.png' });
		
		},

		getInfo : function() {
			return {
				longname : 'insert_portfolio',
				author : 'Iceable Media',
				authorurl : 'http://www.iceablethemes.com',
				infourl : 'http://www.iceablethemes.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('portfolio', tinymce.plugins.icefit_portfolio);
})();