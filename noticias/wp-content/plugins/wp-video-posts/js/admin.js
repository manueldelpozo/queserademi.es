admin_ajax_url= wpvp_vars.admin_ajax;
jQuery(window).load(function(){
	jQuery('.recheckExt').click(function(){
		var data = {
			action: 'wpvp_check_ffmpeg',
			'cookie': encodeURIComponent(document.cookie)
		};
		jQuery.post(admin_ajax_url,data,function(response){
			alert('Check Completed');
			jQuery('.wpvp_system_stats span.ffmpeg').fadeOut(0).html(response).fadeIn(500);
		});
	});
});