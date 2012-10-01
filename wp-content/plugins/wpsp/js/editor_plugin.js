(function() {
	tinymce.create('tinymce.plugins.WPSP', {
		// Plugin initialisation
		init: function(ed, url) {
			// Add command to be fired by button
			ed.addCommand('tinyWPSP', function() {
				ed.windowManager.open({
					file: url + '/../wpsp-popup.php',
					width: 530 + parseInt(ed.getLang('example.delta_width', 0)),
					height: 200 + parseInt(ed.getLang('example.delta_height', 0)),
					inline: 1
				}, {
					// Custom params
					plugin_url : url,
					highlightedContent : ed.selection.getContent()
				});
			});
			
			// Add button, hooking to command above
			ed.addButton('wpsp', {
				title: 'Insert WPSP Shortcode', 
				cmd: 'tinyWPSP',
				image: url + '/../images/admin/style_boxes.png'
			});
		},
		
		// Plugin info
		getInfo: function() {
			return {
				longname : 'WPSP Shortcodes',
				author : 'Ron Chamberlain',
				authorurl : 'http://www.wpsqueezepage.com',
				infourl : 'http://www.wpsqueezepage.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	
	// Add plugin created above
	tinymce.PluginManager.add('wpsp', tinymce.plugins.WPSP);
})();

/*
 (function() {  
     tinymce.create('tinymce.plugins.graybox', {  
         init : function(ed, url) {  
             ed.addButton('graybox', {  
                 title : 'Wrap highlighted text in a Gray Box',  
                 image : url+'/img/graybox.png',  
                 onclick : function() {  
                      ed.selection.setContent('[graybox]' + ed.selection.getContent() + '[/graybox]');  
   
                 }  
             });  
         },  
         createControl : function(n, cm) {  
             return null;  
         },  
     });  
     tinymce.PluginManager.add('graybox', tinymce.plugins.graybox);  
 })();
 */