//*************** DESPLIEGUE DEL VIDEO
function ocultarVideo() {
	$("#ver-video").children().text('VER VIDEO');
	$('#ver-video').animate( { marginTop: "130px" }, 300 );	
	$('#video').fadeOut(300);
	if( !$('video').get(0).paused )
		$('video').get(0).pause();
}
function mostrarVideo() {	
	$("#ver-video").children().text("OCULTAR VIDEO");
	$('#ver-video').animate({ marginTop: "20px" }, 300 );
	$('#video').fadeIn(300);
	if( $('video').get(0).paused )
		$('video').get(0).play();
	if( lista )
		ocultarLista();
}

// Cuando pulsamos el boton 
$("#ver-video").children().click( function() {
	if ( !$("video").is(':visible') )
		mostrarVideo();
	else
		ocultarVideo()
});

// Fin del video
$('video').on('ended',function(){
	$("#ver-video").children().text("VER VIDEO");
	$('#ver-video').animate({ marginTop: "130px" }, 300 );
	$(this).parent().fadeOut(400);
});

