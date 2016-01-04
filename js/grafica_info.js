<?php
function createExcerpts($text,$length,$more_txt) { 
    $text = preg_replace('/[\n\r]/','',$text);
	$text = str_replace('"','',$text);
    $content = substr( $text, 0 , $length ); 
    $excerpt = substr( $text,  $length , strlen($text) );
    echo $content . '<span class="excerpt"><span style="display:none;">' . $excerpt . '</span>' . '<strong class="more">' . $more_txt . '</strong></span>'; 
}
?>

$('#container_info').html('<h4 style="margin:15px; font-family: sans-serif;">INFORMACIÓN</h4><div id="info"></div>');
//$('#container_info').append('<div id="slider"></div>'); eliminar slider

<?php if( isset( $profesion ) ) { ?>  
    $('#info').append('<h5 class="principal nombre"><?php echo $profesion; ?></h5>');
    <?php if( empty( $filas_info[0]['descripcion'] ) ) { ?>
        $('#info').append('<p class="descripcion" id="desc1">Descripcion: Falta información! Ayúdanos a conseguirla.</p>');
        $('#info').append('<div class="col-md-8 col-md-offset-2"><a href="colabora.php?profesion=<?php echo $profesion; ?>" class="btn btn-aviso" style="border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);">Colabora!</a></div>');
    <?php } else { ?>
        $('#info').append('<p class="descripcion"><?php createExcerpts( $filas_info[0]["descripcion"] , 150 , " [ + ]" ); ?></p>');
    <?php } ?>
<?php } ?>
<?php if( isset( $profesion_dos ) && $profesion_dos != ''  ) { ?>
    $('#info').append('<h5 class="secundaria nombre" style="clear:both;"><?php echo $profesion_dos; ?></h5>');
    <?php if( empty( $filas_info_dos[0]['descripcion'] ) ) { ?>
        $('#info').append('<p class="descripcion">Descripcion: Falta información! Ayúdanos a conseguirla.</p>');
        $('#info').append('<div class="col-md-8 col-md-offset-2"><a href="colabora.php?profesion=<?php echo $profesion_dos; ?>" class="btn btn-aviso" style="border-color: #337ab7; color: #337ab7;">Colabora!</a></div>');
    <?php } else { ?>
        $('#info').append('<p class="descripcion"><?php createExcerpts( $filas_info_dos[0]["descripcion"] , 150 , " [ + ]" ); ?></p>');
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

iniciarSlider(3);


