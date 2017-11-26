// COMBOBOX
// Declaracion de typeahead
var isEstatica = $(location).attr('href').indexOf('/profesiones') > -1;
var prefix = isEstatica ? '../' : '';

function getUrl(dataTipo) {
    return prefix + 'data/' + dataTipo + '.json';
}

$('.typeahead[data-tipo="profesiones"]').typeahead({
    name: 'profesiones',
    prefetch: getUrl('profesiones'),
    hint: true,
    highlight: true,
    minLength: 0,
    items: 9999,
    order: "asc",
    limit: Infinity,
});

$('.typeahead[data-tipo="formaciones"]').typeahead({
    name: 'formaciones',
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
    var item = item || $input.val();
    if (item) {
        $.ajax({
            url: prefix + "ajax.php?query=" + item + "&tipo=profesiones&validar=true",
            success: function(result) {
                var $form = $input.parents("#formulario");
                // Comprobar que estamos en la homepage
                if ($input.hasClass('principal') && !isEstatica && $(location).attr('href').indexOf('comparador.php') < 0) {
                    var profesionLimpia = item.replace(/\'|\"|\,|\;|\(|\)|\/|\~|\+/g, '').latinize().toLowerCase().replace(/ /g, "-");
                    var prepositions = ['-a-', '-e-', '-o-', '-u-', '-y-', '-en-', '-de-', '-del-', '-al-', '-el-', '-la-', '-los-', '-las-', '-para-', '-por-'];
                    for (var i = 0; i < prepositions.length; i++) {
                       profesionLimpia = profesionLimpia.replace(prepositions[i], '-');
                    }                   
                    var urlLimpia = $(location).attr('href').replace(/index.html/g, '');
                    var urlEstatica = urlLimpia + 'profesiones/' + profesionLimpia + '.html';
                    $form.attr('action', urlEstatica);
                }
                $form.submit();
            },
            error: function(xhr, textStatus, errorThrown) {
                // incluir mensaje sobre la profesion no encontrada
                var mensaje = '<h3 class="aviso-error">Lo sentimos, no encontramos <strong>' + item + '</strong></h3>';
                if ($('#popUp').find('.aviso-error')) {
                    $('#popUp .aviso-error').remove(); // para no acumular mensajes
                }
                $(mensaje).insertBefore($('#popUp h2'));
                // mostrar POPUP aqui
                $('#popUp').show('slow');
                // TODO proponer mejora de busqueda... por filtros con select
                return false;
            }
        });
    }
}

// Cuando presionamos ENTER coger el primero si no hemos seleccionado ninguno
$('.typeahead').keyup(function(event) {
    var hasFocus = $(this).is(":focus");

    if (event.which === 13) {
        $lista = $(this).siblings('.tt-dropdown-menu');
        if (hasFocus) {
            if ($lista.css('display') == 'none') {
                $(this).attr('placeholder', 'por favor, busca otra vez');
                $(this).typeahead('setQuery', '');
                return false;
            }

            $(this).siblings('.tt-hint').val('');
            $(this).typeahead('setQuery', $(this).siblings('.tt-dropdown-menu').find('.tt-suggestion:first-child').text());
            submitar($(this));
        }
    }
});

// DROPDOWN
// Submit despues de seleccionar item de la lista
$('.typeahead').bind('typeahead:selected', function(obj, datum) {
    $(this).typeahead('setQuery', datum.value);
    submitar($(this), datum.value);
});

// MAS LISTA
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

// COMPARADOR
// mostrar input para comparar
$('#btnAddComparador').click(function() {
    $(this).hide(100);
    $(this).next().show(200, function() {
        $(this).find('input').focus();
    });
});
