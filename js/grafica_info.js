<?php
// Include Composer autoloader if not already done.
include 'vendor/autoload.php';

use \ForceUTF8\Encoding;

function prepareText($text) {
    $text = preg_replace( '/[\n\r]/', ' ', $text); // remove breaks
    $text = str_replace('"','',$text); // remove "
    $text = preg_replace( '/^(Nota:.*\.?)$/', ' ', $text); // remove Nota
    return Encoding::toUTF8(ucfirst($text)); // primer letra en mayuscula forzando el UTF8
}

function parseToList($text, $first_symbol, $second_symbol) {
    if (empty($text)) {
        return $text;
    }
    $split_text = explode($first_symbol, $text);
    if (count($split_text) <= 1) {
        return $text;
    }
    $list = array_pop($split_text);
    $initial_text = join(' ', $split_text);
    $list_items = explode($second_symbol, $list);
    return '<span>' . $initial_text . ':</span><ul class="list-group"><li class="list-group-item">' . join('</li><li class="list-group-item">', $list_items) . '</li></ul>';
}

function createExcerpts($text, $length, $more_txt) { 
    $split_text = explode(' ', $text, $length); // dividir el texto en dos
    $excerpt = array_pop($split_text);
    $content = join(' ', $split_text);

    $excerpt = parseToList($excerpt, ': -', '; -');
    $excerpt = parseToList($excerpt, ': a)', '; b)');
    
    return $content . '<div class="excerpt"><div hidden>' . $excerpt . '</div>' . '<strong class="more">' . $more_txt . '</strong></div>'; 
}

$description_info = prepareText($filas_info[0]["descripcion"]);
$description_info = createExcerpts($description_info, 20, ' [ + ]');

if (isset($filas_info_dos) && !empty($filas_info_dos)) {
    $description_info_dos = prepareText($filas_info_dos[0]["descripcion"]);
    $description_info_dos = createExcerpts($description_info_dos, 20, ' [ + ]');
}

?>

$('#container_info').html('<h5 style="margin: 15px; font-weight: bold;">+ INFORMACIÓN</h5><div id="info"></div>');
//$('#container_info').append('<div id="slider"></div>'); eliminar slider

<?php if( isset( $profesion ) && !empty($profesion) ) { ?>  
    $('#info').append('<h4 class="principal nombre"><?php echo mb_strtoupper($profesion,"UTF-8" ); ?></h4>');
    <?php if( empty( $description_info ) ) { ?>
        $('#info').append('<p class="descripcion" id="desc1">Falta información! Ayúdanos a conseguirla.</p>' +
                          '<div class="col-md-8 col-md-offset-2"><a href="https://queserademi.com/colabora.php?profesion=<?php echo $profesion; ?>" class="btn btn-aviso" style="border-color: #d62e46; color: #d62e46;">Colabora!</a></div>');
    <?php } else { ?>
        $('#info').append('<div class="descripcion"><?php echo $description_info; ?></div>');
    <?php } ?>
<?php } ?>
<?php if( isset( $profesion_dos ) && !empty( $profesion_dos) ) { ?>
    $('#info').append('<h1 class="secundaria nombre" style="clear:both; color:#555; margin:10px;">~ VS ~</h1>' +
                      '<h4 class="secundaria nombre"><?php echo mb_strtoupper($profesion_dos,"UTF-8" ); ?></h4>');
    <?php if( empty( $description_info_dos ) ) { ?>
        $('#info').append('<p class="descripcion">Falta información! Ayúdanos a conseguirla.</p>' +
                          '<div class="col-md-8 col-md-offset-2"><a href="https://queserademi.com/colabora.php?profesion=<?php echo $profesion_dos; ?>" class="btn btn-aviso" style="border-color: #337ab7; color: #337ab7;">Colabora!</a></div>');
    <?php } else { ?>
        $('#info').append('<div class="descripcion"><?php echo $description_info_dos; ?></div>');
    <?php } ?>
<?php } ?>

// Excerpt
$('.more').click(showMore);

function showMore() {
    $(this).prev().fadeToggle();
    var text = $(this).text();
    $(this).text(text == ' [ + ]' ? ' [ - ]' : ' [ + ]');
}

//// SLIDER
function iniciarSlider( nImages ) { 
    var n = 1;
    var image_url;
    setInterval( function(){
        image_url = 'images/slider/img'+n+'.jpg';
        $('#slider').css({'background-image': 'url(' + image_url + ')',});
        n >= nImages ? n = 1 : n++;
    }, 4000);
}

//iniciarSlider(3);


