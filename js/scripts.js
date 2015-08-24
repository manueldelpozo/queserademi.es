////************** ANIMACIONES
/*
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
*/
/////************* DESPLIEGUE DE LISTA
/*
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
	// solo mostrar tatarabuelos?
	//$lista.find('.tatarabuelo').show();	
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
*/
/////************** VALIDACION

function avisoValidacion() {
	alert("Por favor, introduce un valor de la lista");
}

function validacion() {
	var $inputs = $("input");
	if( $inputs.eq(0).val() == "Elemento no encontrado" || $inputs.eq(0).val() == "Campo sin rellenar" || $inputs.eq(1).val() == "Elemento no encontrado" || $inputs.eq(1).val() == "Campo sin rellenar" ) { 
		avisoValidacion();
		return false;	
	} else {
		return true;
	}
}
/*
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
			ocultarLista( $input );
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
		ocultarLista( $input );
		$("#formulario").submit(); 
	});
});
// si clik fuera de la lista ocultamos lista
$(document).click(function(){
	//ocultarLista();
});

////*************** AUTOCOMPLETE

function ajaxAutocomplete( keyword, $input ) {
	var estudios_asoc = 0;
	if( $input.attr("name") == 'estudios_asoc' )
		estudios_asoc = 1;	
	$.ajax({
		url: 'ajax.php',
		type: 'GET',
		data: { keyword: keyword, estudios_asoc: estudios_asoc },	
		success: function( msg ) { desplegarLista( $input, msg ) } 
	});			
}

// Cuando escribimos en el input
$("input").keydown( function(e) {
	if(e.which == 13) { // ENTER
		var $lista = $(this).parent().next();
		var valor1 = $lista.find('li:eq(0)').text();
		$(this).val( valor1 );
		ocultarLista( $(this) );
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
*/



$("button.buscador").click( function(){
	//$('#scrollable-dropdown-menu .typeahead').typeahead.bind( $("#typeaheadField"), 'lookup' );
    //add something to ensure the menu will be shown
    console.log("click");
    //$input.focus();
    $('#scrollable-dropdown-menu .typeahead').val('%');
    $('tt-dropdown-menu').show;
    //$input.typeahead('lookup').focus();
    //$input.val('');
});
/*
function todoArray() {	
	$.ajax({
		url: 'ajax.php',
		type: 'GET',
		data: { query: '%' },	
		success: function( msg ) { var todo = msg; } 
	});			
}
todoArray();

function obtainer(query, cb) {
	var fullList = $.grep(todo, function(item,index) {
		return item.match(query);
	});
	mapped = $.map(fullList, function(item) {
		return {value:item}
	});
	cb(mapped);
}
*/
$('#scrollable-dropdown-menu .typeahead').typeahead({
	minLength: 0,
    items: 9999,
    order: "asc",
	remote: {
		url : 'ajax.php?query=%QUERY'
	},
	limit: 15,
    //callback: {
        ///onClickAfter: function (node, a, item, event) {
 
            // href key gets added inside item from options.href configuration
            //alert(item.href);
 
        //}
    //}
});
// Cuando ENTER
$("input").keydown( function(e) {
	if(e.which == 13)
		$("#formulario").submit(); 
});
// Cuando clickamos en el input 
$("input").click( function(e) {
	$(this).val('%');
	$(this).typeahead.bind($(this), 'lookup');
	$(this).val('');
});
/*
$('#scrollable-dropdown-menu .typeahead').bind('typeahead:selected', function(obj, datum, name) {      
        alert(JSON.stringify(obj)); // object
        // outputs, e.g., {"type":"typeahead:selected","timeStamp":1371822938628,"jQuery19105037956037711017":true,"isTrigger":true,"namespace":"","namespace_re":null,"target":{"jQuery19105037956037711017":46},"delegateTarget":{"jQuery19105037956037711017":46},"currentTarget":
        alert(JSON.stringify(datum)); // contains datum value, tokens and custom fields
        // outputs, e.g., {"redirect_url":"http://localhost/test/topic/test_topic","image_url":"http://localhost/test/upload/images/t_FWnYhhqd.jpg","description":"A test description","value":"A test value","tokens":["A","test","value"]}
        // in this case I created custom fields called 'redirect_url', 'image_url', 'description'   

        alert(JSON.stringify(name)); // contains dataset name
        // outputs, e.g., "my_dataset"

});
*/
// Seleccionar item con click de raton
$(".tt-is-under-cursor").click( function() {
	console.log("click");
	$("#formulario").submit(); 
});

// animar footer
$(".btn-footer").click( function() {
	if( $(this).attr("id")=="btn-footer-md" ) 
		$btn = $("#btn-footer-md");
	else if ( $(this).attr("id")=="btn-footer-xs" )
		$btn = $("#btn-footer-xs");

	if( $btn.find("span.caret").hasClass("flecha") || $btn.find("span.glyphicon").hasClass("glyphicon-menu-hamburger") ) {
		$btn.find("span.caret").removeClass("flecha");
		$btn.find("span.glyphicon").removeClass("glyphicon-menu-hamburger").addClass('glyphicon-menu-up');

		$footer = $btn.parents("footer");
		if( $btn.attr("id")=="btn-footer-md" ) {
			$footer.animate({ height: '100px'}, 200 );
		} else if ( $btn.attr("id")=="btn-footer-xs" ) {
			$footer.animate({ height: '500px'}, 200 );
			$('mobile-menu').each(function() {
			  $( this ).removeClass('hidden-xs');
			  console.log('hace algo');
			});
		}		
	} else {
		$btn.find("span.caret").addClass("flecha");
		$btn.find("span.glyphicon").removeClass("glyphicon-menu-up").addClass('glyphicon-menu-hamburger');
		$btn.parents("footer").animate({ height: '50px'}, 200 );
	}
});
