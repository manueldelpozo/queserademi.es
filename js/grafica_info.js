<?php
function createExcerpts($text, $length, $more_txt) { 
    $text = preg_replace('/[\n\r]/','',$text);
	$text = str_replace('"','',$text);
    // primer letra en mayuscula
    $text = ucfirst($text);
    $content = substr( $text, 0 , $length ); 
    $excerpt = substr( $text,  $length , strlen($text) );
    return $content . '<span class="excerpt"><span style="display:none;">' . $excerpt . '</span>' . '<strong class="more">' . $more_txt . '</strong></span>'; 
}
?>

$('#container_info').html('<h5 style="margin:15px; font-family: sans-serif; font-weight: bold;">INFORMACIÓN</h5><div id="info"></div>');
//$('#container_info').append('<div id="slider"></div>'); eliminar slider

<?php if( isset( $profesion ) ) { ?>  
    $('#info').append('<h4 class="principal nombre"><?php echo mb_strtoupper($profesion,"UTF-8" ); ?></h4>');
    <?php if( empty( $filas_info[0]['descripcion'] ) ) { ?>
        $('#info').append('<p class="descripcion" id="desc1">Falta información! Ayúdanos a conseguirla.</p>');
        $('#info').append('<div class="col-md-8 col-md-offset-2"><a href="colabora.php?profesion=<?php echo $profesion; ?>" class="btn btn-aviso" style="border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);">Colabora!</a></div>');
    <?php } else { ?>
        $('#info').append('<p class="descripcion"><?php echo createExcerpts( $filas_info[0]["descripcion"] , 150 , " [ + ]" ); ?></p>');
    <?php } ?>
<?php } ?>
<?php if( isset( $profesion_dos ) && $profesion_dos != ''  ) { ?>
    $('#info').append('<h1 class="secundaria nombre" style="clear:both; color:#555; margin:10px;">~ VS ~</h1>');
    $('#info').append('<h4 class="secundaria nombre"><?php echo mb_strtoupper($profesion_dos,"UTF-8" ); ?></h4>');
    <?php if( empty( $filas_info_dos[0]['descripcion'] ) ) { ?>
        $('#info').append('<p class="descripcion">Falta información! Ayúdanos a conseguirla.</p>');
        $('#info').append('<div class="col-md-8 col-md-offset-2"><a href="colabora.php?profesion=<?php echo $profesion_dos; ?>" class="btn btn-aviso" style="border-color: #337ab7; color: #337ab7;">Colabora!</a></div>');
    <?php } else { ?>
        $('#info').append('<p class="descripcion"><?php echo createExcerpts( $filas_info_dos[0]["descripcion"] , 150 , " [ + ]" ); ?></p>');
    <?php } ?>
<?php } ?>

// Excerpt
$('.more').click( function() {
    $(this).prev().fadeToggle();
    var text = $(this).text();
    $(this).text(text == ' [ + ]' ? ' [ - ]' : ' [ + ]');
});

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


