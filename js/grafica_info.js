<?php
function createExcerpts($text,$length,$more_txt) { 
    $text = preg_replace('/[\n\r]/','',$text);
	$text = str_replace('"','',$text);
    $content = substr( $text, 0 , $length ); 
    $excerpt = substr( $text,  $length , strlen($text) );
    echo $content . "<span class='excerpt'><span style='display:none;'>" . $excerpt . "</span>" . "<strong class='more'>" . $more_txt . "</strong></span>"; 
}
?>

$('#container1').html("<h4 style='margin:15px'>INFORMACIÓN</h4><div id='info'></div><div id='slider'></div>");

<?php if( isset( $profesion_uno ) ) { ?>  
    $('#info').append("<h5 class='principal nombre'><?php echo $registro['profesion']; ?></h5>");
    <?php if( empty( $registro['descripcion'] ) ) { ?>
        $('#info').append("<p class='descripcion' id='desc1'>Descripcion: Falta informacion. Disculpe las molestias</p>");
    <?php } else { ?>
        $('#info').append("<p class='descripcion'><?php createExcerpts( $registro['descripcion'] , 150 , ' [ + ]' ); ?></p>");
    <?php } if( empty( $registro['estudios_asoc'] ) ) { ?>
        $('#info').append("<p class='estudios'>Estudios asociados: Falta informacion. Disculpe las molestias</p>");
    <?php } else { ?>
        $('#info').append("<p class='estudios'>Estudios asociados: <strong><?php echo preg_replace('/[\"]/','/[\']/',$registro['estudios_asoc']); ?></strong></p>");
    <?php } ?>
    <?php if( empty( $registro['descripcion'] ) || empty( $registro['estudios_asoc'] ) ) { ?>
        $('#info').append("<div class='col-md-8 col-md-offset-2'><a href='colabora.php?profesion=<?php echo $registro['profesion']; ?>' class='btn btn-aviso' style='border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);'>Colabora!</a></div>");
    <?php } ?>
<?php } ?>
<?php if( isset( $profesion_dos ) && $registro_dos["profesion"] != ""  ) { ?>
    $('#info').append("<h5 class='secundaria nombre' style='clear:both;'><?php echo $registro_dos['profesion']; ?></h5>");
    <?php if( empty( $registro_dos['descripcion'] ) ) { ?>
        $('#info').append("<p class='descripcion'>Descripcion: Falta informacion. Disculpe las molestias</p>");
    <?php } else { ?>
        $('#info').append("<p class='descripcion'><?php createExcerpts( $registro_dos['descripcion'] , 150 , ' [ + ]' ); ?></p>");
    <?php } if( empty( $registro_dos['estudios_asoc'] ) ) { ?>
        $('#info').append("<p class='estudios'>Estudios asociados: Falta informacion. Disculpe las molestias</p>");
    <?php } else { ?>
        $('#info').append("<p class='estudios'>Estudios asociados: <strong><?php echo preg_replace('/[\n\r]/','',$registro_dos['estudios_asoc']); ?></strong></p>");
    <?php } ?>
    <?php if( empty( $registro_dos['descripcion'] ) || empty( $registro_dos['estudios_asoc'] ) ) { ?>
        $('#info').append("<div class='col-md-8 col-md-offset-2'><a href='colabora.php?profesion=<?php echo $registro_dos['profesion']; ?>' class='btn btn-aviso' style='border-color: #337ab7; color: #337ab7;'>Colabora!</a></div>");
    <?php } ?>
<?php } ?>

// Excerpt
$(".more").click( function() {
    $(this).prev().fadeToggle();
    var text = $(this).text();
    $(this).text(text == " [ + ]" ? " [ - ]" : " [ + ]");
});

//// SLIDER
function iniciarSlider( nImages ) { 
    var n = 1;
    var image_url;
    setInterval( function(){
        image_url = "images/slider/img"+n+".jpg";
        $('#slider').css({'background-image': 'url(' + image_url + ')',});
        n >= nImages ? n = 1 : n++;
    }, 4000);
}

iniciarSlider(3);


