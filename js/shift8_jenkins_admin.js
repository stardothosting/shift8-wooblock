jQuery(document).ready(function() {
	jQuery(document).on( 'click', '#shift8-wooblock-push', function(e) {
		e.preventDefault();
		//if (confirm('Are you sure you want to push staging to production?')) {
			var button = jQuery(this);
	    	var url = button.attr('href');
	    	jQuery.ajax({
	        	url: url,
	        	data: {
	            	'action': 'shift8_wooblock_push',
	        	},
	        	success:function(data) {
	            	// This outputs the result of the ajax request
	            	jQuery('.shift8-wooblock-push-progress').html(data);
	        	},
	        	error: function(errorThrown){
	            	console.log('Error : ' + JSON.stringify(errorThrown));
	        	}
			}); 
		//}
	});
});
