////************** ANIMACIONES

function doBounce(element, times, speed) {
    for(var i = 0; i < times; i++) {
        element.animate({height: '100px'}, speed)
            .animate({height: '80px'}, speed);
    }        
}
// Animacion inicial
// animar letras
$("#titulo").animate({ left: "", opacity: 1 }, 2000 );
//animar logo
doBounce( $('.img-responsive'), 1, 200 );

// animar logo al clicar y pasar encima
$( ".img-responsive" ).bind({
  click: function() {
    doBounce($(this), 1, 200);  
  },
  mouseenter: function() {
    doBounce($(this), 1, 200); 
  }
});

/////************* DESPLIEGUE DE LISTA

var lista = false;	

function ocultarLista( $input ) {
	lista = false;
	// Ocultar lista correspondiente si el valor esta vacio
	if( !$input )
		$input = $("input");
	$input.parent().next().slideUp( 300 );	
	// Recolocar boton arriba si no hay video
	if ( !$('video').is(":visible") )
		$('#ver-video').animate({ marginTop: "130px" }, 300 );
	// Cambiar orientacion de boton flecha si esta hacia arriba
	if ( $input.next().find('.caret').css('border-top') == '0px none rgb(51, 51, 51)' )
		$input.next().find('.caret').css('border-top','4px solid').css('border-bottom','0');
}	
function desplegarLista( $input, msg ) {
	lista = true;
	var $lista = $input.parent().next();
	$lista.slideDown( 300 );
	$lista.html(msg);
	// Pausar y ocultar video si hay video
	if ( $('video').is(":visible") ) {
		ocultarVideo();
        $('#ver-video').animate({ marginTop: "220px" }, 300 );
	} else {
		$('#ver-video').animate({ marginTop: "220px" }, 300 );
    }
	// Cambiar orientacion de boton flecha si esta hacia abajo
	if ( $input.next().find('.caret').css('border-bottom') == '0px none rgb(51, 51, 51)' )
		$input.next().find('.caret').css('border-bottom','4px solid').css('border-top','0');
}

/////************** VALIDACION

function avisoValidacion() {
	alert("Por favor, introduce un valor de la lista");
}

function validacion() {
	var $inputs = $("input");
	if( $inputs.eq(0).val() == "Profesion no encontrada" || $inputs.eq(0).val() == "Profesion vacia" || $inputs.eq(1).val() == "Profesion no encontrada" || $inputs.eq(1).val() == "Profesion vacia" ) { 
		avisoValidacion();
		return false;	
	} else {
		return true;
	}
}

/////************** NAVEGACION

// Resetear elementos clase .sel
function resetSel( $input ) {
  $("li").children().each( function(){           
    if( $(this).hasClass("sel") )
      $(this).removeClass("sel").addClass("search-option");
  });
}
// Navegar en la lista
function navegar($sel){
	var $input = $sel.parent().parent().prev().find("input");
	$input.val( $sel.text() );
	$sel.keydown( function(e) {
		if(e.which == 40) { // down	
			$sel.removeClass('sel').addClass( "search-option" );
			$sel.parent().next().find(".search-option").focus().removeClass('search-option').addClass("sel");
			$input.val( $sel.text() );
			navegar( $('.sel') );
			return false; // detiene scrolling
		}
		if(e.which == 38) { // up
			$sel.removeClass('sel').addClass( "search-option" );
			$sel.parent().prev().find(".search-option").focus().removeClass('search-option').addClass("sel");
			$input.val( $sel.text() );
			navegar( $(".sel") );
			return false; // detiene el scrolling
		}
		if(e.which == 13 || e.which == 39) { // enter or right
			$sel.removeClass('sel').addClass( "search-option" );
			$input.val( $sel.text() );
			$("#formulario").submit();
		}  
	});
}
// Iniciar navegador
function navegarInit( $input ) {
	var $lista = $input.parent().next();
	var $first = $lista.children().find("a.search-option").first();
	resetSel();			
	$input.val( $first.text() );
	$first.focus().removeClass('search-option').addClass("sel");
	var $sel = $lista.children().find('a.sel');
	navegar( $sel );
	return false; // detiene el scrolling
}

/////************** SELECCIONADORES

// Seleccionador con click de raton
$("ul").mouseover(function(){
	$("li").click( function() {
		var $input = $(this).parent().prev().find("input");
		$input.val( $(this).find("a").text() );
		$("#formulario").submit(); 
	});
});
// si clik fuera de la lista ocultamos lista
$(document).click(function(){
	//ocultarLista();
});

////*************** AUTOCOMPLETE

function ajaxAutocomplete( keyword, $input ) {	
	$.ajax({
		url: 'ajax.php',
		type: 'GET',
		data: { keyword: keyword },	
		success: function( msg ) { desplegarLista( $input, msg ) } 
	});			
}

// Cuando escribimos en el input
$("input").keydown( function(e) {
	if(e.which == 13) { // ENTER
		var $lista = $(this).parent().next();
		var valor1 = $lista.find('li:eq(0)').text();
		$(this).val( valor1 );
        $("#formulario").submit();
	} else if( e.which == 40 ) { // CURSOR ABAJO
		navegarInit( $(this) );
	} else { // TEXTO
		var keyword = $(this).val()
		if( keyword.length > 0 )
			ajaxAutocomplete( keyword, $(this) );
		else
			ocultarLista( $(this) );
	}   
});

// cuando clikeamos
$("input").click( function(){	
	var keyword = $(this).val()
			if( keyword.length > 0 ){
				ajaxAutocomplete( keyword, $(this) );
			}else{
				ocultarLista( $(this) );
		}
});

// Cuando clicamos el boton
$("button.buscador").click( function(){
	var keyword = '%'
	var $input = $(this).parent().prev();
	if ( !lista ) {	
		ajaxAutocomplete( keyword, $input );
		var $lista = $input.parent().next(); 
		$lista.mouseenter(function(){
			navegarInit( $input );			
		});
	} else {
		ocultarLista( $input );
	}
});
