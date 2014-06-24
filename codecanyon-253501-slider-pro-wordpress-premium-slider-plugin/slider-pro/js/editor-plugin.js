(function() {
	
	tinymce.PluginManager.add('sliderpro', function(editor) {

		editor.addButton('sliderpro', function() {
			return {
				type: 'menubutton',
				text: 'Slider PRO',
				title: 'Slider PRO',
				onPostRender: function() {
					var _this = this,
						menu = this.menu;

					jQuery.ajax({
						url: 'admin-ajax.php',
						dataType: 'json',
						data: {action: 'sliderpro_tinymce_plugin'},
						success: function(data) {
							var tempMenu = [];

							jQuery.each(data, function(i){
								tempMenu.push({
									'text': data[i].name + ' (' + data[i].id + ')',
									onclick: function(){
										editor.insertContent('[slider_pro id="' + data[i].id + '"]');
									}
								});
							});

							_this.settings.values = _this.settings.menu = tempMenu;
						}
					});
				}
			};
		});
	});
	
})();