// COMBOBOX
// Declaracion de typeahead
var isEstatica = $(location).attr('href').indexOf('/profesiones') > -1;
var isTest = $(location).attr('href').indexOf('/test') > -1;
var prefix = (isEstatica || isTest) ? '../' : '';

if (isTest) {
    var password
    var pass1 = 'qsdmtest';
    password = prompt('Introduce la contraseña',' ');
    if (password === pass1) {
        alert('Contraseña correcta');
    } else {
        alert('Contraseña incorrecta');
        window.location = "http://queserademi.com";
    }
}

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
                    var urlPrefixClean = $(location).attr('href').replace('/test/', '/').replace('/#', '/').replace(/index.html/g, '');
                    var urlEstatica = urlPrefixClean + 'profesiones/' + profesionLimpia + '.html';
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
    $('#filterDropdown').hide();

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

$('.dropdown-menu li').click(function() {
    var content = $(this).text();
    $(this).parent().prev().text(content);
    $.ajax({
        url: 'ajax.php?query=%&tipo=profesiones',
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
});

// FILTER
$(document).ready(function() {

$('.slider.filter').bind('change', function(e) {
    var salario_from = $('#s_principiante_min').val();
    var salario_to = $('#s_principiante_max').val();
    var empleabilidad_from = $('#empleabilidad_min').val();
    var empleabilidad_to = $('#empleabilidad_max').val();

    $.ajax({
        url: '../filter.php?salario_from=' + salario_from + '&salario_to=' + salario_to + '&empleabilidad_from=' + empleabilidad_from + '&empleabilidad_to=' + empleabilidad_to,
        success: function(result) {
            var professions = JSON.parse(result);
            var list = document.createElement('DIV');
            list.classList.add('tt-dataset-profesiones');
            var suggestions = document.createElement('SPAN');
            suggestions.classList.add('tt-suggestions');
            suggestions.style.cssText = 'display: block;';

            for (var i = 0; i < professions.length; i++) {
                var suggestion = document.createElement('DIV');
                suggestion.classList.add('tt-suggestion');
                suggestion.style.cssText = 'white-space: nowrap; cursor: pointer;';
                var content = document.createElement('P');
                content.innerText = professions[i];
                content.style.cssText = 'white-space: normal;';
                suggestion.appendChild(content);
                suggestions.appendChild(suggestion);
            }

            if (!suggestions.hasChildNodes()) {
                var warning = document.createElement('DIV');
                warning.classList.add('tt-suggestion');
                warning.classList.add('qsdm-color-red');
                warning.style.cssText = 'white-space: nowrap;';
                var warningContent = document.createElement('P');
                warningContent.innerText = 'Ouups, no hay profesiones. Intentalo de nuevo';
                warningContent.style.cssText = 'white-space: normal;';
                warning.appendChild(warningContent);
                suggestions.appendChild(warning);
            }

            list.appendChild(suggestions);
            $('#filterDropdown').html(list);
            $('#filterDropdown').show();

            /*if ($('#filterDropdown .tt-suggestion').first().position()) {
               $('#filterDropdown').scrollTop($('#filterDropdown .tt-suggestion').first().position().top); 
            }*/

            $('#filterDropdown .tt-suggestion').mouseover(function() {
                $(this).addClass('tt-is-under-cursor');
            });

            $('#filterDropdown .tt-suggestion').mouseout(function() {
                $(this).removeClass('tt-is-under-cursor');
            });

            $('#filterDropdown .tt-suggestion').click(function(event) {
                var selection = event.target.innerText;
                $('.typeahead').typeahead('setQuery', selection);
                submitar($('.typeahead'), selection);
            });
        },
        error: function(xhr, textStatus, errorThrown) {
            console.log(errorThrown)
            //alert('request failed');
            return false;
        }
    });
});

});