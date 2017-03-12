// COMBOBOX
// Declaracion de typeahead
var isEstatica = $(location).attr('href').indexOf('/profesiones') > -1;
var prefix = isEstatica ? '../' : '';

function getUrl(dataTipo) {
    return prefix + 'data/' + dataTipo + '.json';
}

$('.typeahead[data-tipo="profesiones"]').typeahead({
    prefetch: getUrl('profesiones'),
    hint: true,
    highlight: true,
    minLength: 0,
    items: 9999,
    order: "asc",
    limit: Infinity
});

$('.typeahead[data-tipo="formaciones"]').typeahead({
    prefetch: getUrl('formaciones'),
    hint: true,
    highlight: true,
    minLength: 0,
    items: 9999,
    order: "asc",
    limit: Infinity
});

// Validacion SUBMIT
function submitar($input, item) {
    var item = $input.val() || item;
    if (item) {
        $.ajax({
            url: prefix + "ajax.php?query=" + item + "&tipo=profesiones&validar=true",
            success: function(result) {
                var $form = $input.parents("#formulario");
                if (result == '[]' || result == '[""]') {
                    // incluir mensaje sobre la profesion no encontrada
                    var mensaje = '<h3 class="aviso-error">Lo sentimos, no encontramos <strong>' + item + '</strong></h3>';
                    if ($('#popUp').find('.aviso-error'))
                    	$('#popUp .aviso-error').remove(); // para no acumular mensajes
                    $(mensaje).insertBefore($('#popUp h2'));
                    // mostrar POPUP aqui
                    $('#popUp').show('slow');
                    // proponer mejora de busqueda... por filtros con select
                    return false;
                } else {
                    var urlEstatica, profesionLimpia, urlLimpia;
                    // Comprobar que estamos en la homepage
                    if ($input.hasClass('principal') && !isEstatica && $(location).attr('href').indexOf('comparador.php') < 0) {
                        profesionLimpia = item.replace(/\'|\"|\,|\;|\(|\)|\/|\~|\+/g, '').latinize().toLowerCase().replace(/ /g, "-");
                        urlLimpia = $(location).attr('href').replace(/index.html/g, '');
                        urlEstatica = urlLimpia + 'profesiones/' + profesionLimpia + '.html';
                        $form.attr('action', urlEstatica);
                    }
                    $form.submit();
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                // y cambiar el redireccionamiento a la pagina estatica si es necesario (status 500)
                /*if (xhr.status == 500) {
                    window.location = 'http://www.queserademi.com/profesiones/' + $profesion.replace(/ /g, "-").latinize().toLowerCase() + '.html';
                }*/
                alert('Fallo en la consulta');
                return false;
            }
        });
    }
}

// Cuando presionamos ENTER coger el primero si no hemos seleccionado ninguno
$(".typeahead").keypress(function(event) {
    if (event.which === 13) {
        $lista = $(this).siblings('.tt-dropdown-menu');
        // si input tiene focus 
        if ($(this).is(":focus")) {
            // si no hay lista paramos la funcion
            if ($lista.css('display') == 'none') {
                submitar($(this)); // para guardar nombres desconocidos y mostrar popup
                return false;
            }

            $(this).siblings('.tt-hint').val('');
            $(this).val($(this).siblings('.tt-dropdown-menu').find('.tt-suggestion:first-child').text());
            submitar($(this));
        }
    }
});

// Cuando clickamos boton submit
$('.btn-submit').click(function(event) {
    var $input_target = $(this).parents('#scrollable-dropdown-menu').find('.typeahead');
    event.preventDefault();
    submitar($input_target);
});

// DROPDOWN
// Submit despues de seleccionar item de la lista
$('.tt-dropdown-menu').click(function(event) {
    var $textItem = $(event.target).text();
    var $input = $(this).siblings('.typeahead');
    var $consulta = $input.val();

    if ($consulta === $textItem && $textItem !== 'Más...') {
        submitar($input);
    }
    // No usar opcion Mas por el momento
    /*else if ($textItem == 'Más...') {
    	// no ocultar lista
    	$(this).show();
    	// no mostrar mas en input
    	$input.val('');
    	// eliminar mas
    	$(e.target).parent('.tt-suggestion').remove();
    	mostrarMasLista($input);
    }*/
});

function mostrarMasLista($input) {
    // consultar todos los elementos de la lista
    $.ajax({
        url: "ajax.php?query=%&tipo=profesiones",
        success: function(result) {
            $input.typeahead({
                source: result,
            });
            // remplazar nueva lista
            return false;
        },
        error: function(xhr, textStatus, errorThrown) {
            alert('request failed');
            return false;
        }
    });
}