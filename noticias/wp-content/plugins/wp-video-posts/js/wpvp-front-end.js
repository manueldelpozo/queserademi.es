upload_size = wpvp_vars.upload_size;
file_upload_limit = wpvp_vars.file_upload_limit;
wpvp_ajax = wpvp_vars.wpvp_ajax;
var files;
jQuery(document).ready(function(){
	jQuery('.wpvp-submit').on('click',wpvp_uploadFiles);
	jQuery('input#async-upload').on('change', wpvp_prepareUpload);
	jQuery('.video-js').each(function(){
		var objId = jQuery(this).attr('id');
		var vol = jQuery(this).data('audio');
		if(objId !== 'undefined' && (vol < 100 && vol !== 'undefined')){
			var player = videojs(objId);
			player.ready(function(){
				vol = parseFloat("0."+vol);
				var playerObj = this;
			  	playerObj.volume(vol);
			});
		}
	});
});
// Grab the files and set them to our variable
function wpvp_prepareUpload(event){
	if(typeof event==='undefined')
		var event = e;
	files = event.target.files;
}
// Catch the form submit and upload the files
function wpvp_uploadFiles(event){
	if(typeof event==='undefined')
		var event = e;
	event.stopPropagation();
	event.preventDefault();
	var action = jQuery(this).attr('action');
	var deferred_validation = jQuery.Deferred();
	error = false;
	var form = jQuery('form.wpvp-processing-form');
	var formData = form.serialize();
	form.find('.wpvp_require').each(function(){
		if(!jQuery(this).val()){
			error = true;
			jQuery(this).next('.wpvp_error').html('This field is required.');
		} else {
			jQuery(this).next('.wpvp_error').html('');
		}
	}).promise().done(function(){
		deferred_validation.resolve();
	});
	deferred_validation.done(function(){
		if(error)
			return false;
		if(action=='update'){
			var data = {
				action: 'wpvp_process_update',
				'cookie': encodeURIComponent(document.cookie),
				formData: formData
			};
			jQuery.post(wpvp_ajax,data,function(response){
				var obj = JSON.parse(response);
				var status = '';
				if(obj.hasOwnProperty('status'))
					status = obj.status;
				var msg = [];
				if(obj.hasOwnProperty('msg'))
					msg = obj.msg;
				if(msg instanceof Array){
					var msgBlock = jQuery('.wpvp_msg');
					msgBlock.html('');
					for(var i=0; i < msg.length; i++){
						msgBlock.append(msg[i]);
					}
				}
			});
		} else if(action=='create'){
			var deferred = jQuery.Deferred();
			var errors = [];
			// Pre-loader Start
			wpvp_progressBar(1);
			// Check files
			error = false;
			jQuery.each(files, function(key, value){
				if(value.size>file_upload_limit){
					error = true;
					errors.push(value.name+' file exceeds allowed size.');
				}
				if(key==(files.length-1))
					deferred.resolve();
			});
			deferred.done(function(){
				if(error){
					if(errors instanceof Array){
						for(x=0;x<errors.length;x++){
							jQuery('.wpvp_file_error').append(errors[x]+'<br />');
						}
					}
					//hide loader
					wpvp_progressBar(0);
				} else {
					//clear file errors
					jQuery('.wpvp_file_error').html('');
					//process form
					var data = {
						action: 'wpvp_process_form',
						'cookie': encodeURIComponent(document.cookie),
						data: formData
					};
					var wpvp_form_done = jQuery.post(wpvp_ajax,data);
					jQuery.when(wpvp_form_done).done(function(response){
						var obj = JSON.parse(response);
						var status = '';
						var msg = '';
						var postid = 0;
						if(obj.hasOwnProperty('status'))
							status = obj.status;
						if(obj.hasOwnProperty('msg'))
							msg = obj.msg;
						if(obj.hasOwnProperty('post_id'))
							postid = obj.post_id;	
						var data = new FormData();
						jQuery.each(files, function(key, value){
							data.append(key, value);
							data.append('postid',postid);
						});
						jQuery.ajax({
							url: wpvp_ajax+'?action=wpvp_process_files',
							type: 'POST',
							data: data,
							cache: false,
							dataType: 'json',
							processData: false, // Don't process the files
							contentType: false, // Set content type to false
							success: function(obj, textStatus, jqXHR){
								var status = '';
								var errors = [];
								var html = '';
								var url = '';
								if(obj.hasOwnProperty('status'))
									status = obj.status;
								if(obj.hasOwnProperty('errors'))
									errors = obj.errors;
								if(obj.hasOwnProperty('html'))
									html = obj.html;
								if(obj.hasOwnProperty('url'))
									url = obj.url;
								if(status=='success'){
									jQuery('.wpvp_file_error').html('');
									form.html(html);
									if(url!=''){
										setTimeout(function(){
											window.location.href = url;
										},5000);
									}
								} else if(status=='error'){
									if( errors instanceof Array){
										for(i=1; i<errors.length ; i++){
											jQuery('.wpvp_file_error').append(errors[i]+'<br />');
										}
									}
								}
							},
							error: function(jqXHR, textStatus, errorThrown){
								// Handle errors here
								console.log('ERRORS: ' + textStatus);
								wpvp_progressBar(0);
							}
						});
					});
				}
			});
		}
	});
}
function wpvp_progressBar(show) {
	if(show){
		jQuery('.wpvp_upload_progress').css('display','block');
	} else {
		jQuery('.wpvp_upload_progress').css('display','none');
	}
};	