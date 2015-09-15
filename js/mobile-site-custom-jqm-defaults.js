/*
$(document).bind("mobileinit", function(){
 	// $.extend(  $.mobile , {
   		//defaultPageTransition: 'none'
  	//});
	if (navigator.userAgent.indexOf("Android") != -1) {
   		$.mobile.defaultPageTransition = 'none';
   		$.mobile.defaultDialogTransition = 'none';
	} 
});

$(document).on('mobileinit', function () {
    $.mobile.ignoreContentEnabled = true;
});

$(document).on('pagebeforecreate', function( e ) {
    $( "input, textarea, select", e.target ).attr( "data-role", "none" );
});

$(document).bind('mobileinit',function(){
    $.mobile.page.prototype.options.keepNative = "select,input";
});
*/
$(document).bind('mobileinit',function(){
    $.mobile.keepNative = "select,input"; /* jQuery Mobile 1.4 and higher*/
    /*$.mobile.page.prototype.options.keepNative = "select,input";*/ /* jQuery Mobile 1.4 and lower (deprecated in jQuery Mobile 1.4)*/
});