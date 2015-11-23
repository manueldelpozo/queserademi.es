

$(function () {
    $('#container_formacion').highcharts({
        chart: {
            type: 'bar',
            backgroundColor:'rgba(255, 255, 255, 0)',
            spacingBottom: 20,
            spacingTop: 20,
            spacingLeft: 20,
            spacingRight: 20,
            width: null,
            height: 380
        },
        title: {
            text: 'FORMACION'
        },
        xAxis: {
            categories: [
            '<?php echo $profesion; ?>'
            , 'Duracion real'
            <?php if( isset($profesion_dos) && !empty($profesion_dos) ) { ?>
            , '<?php echo $profesion_dos; ?>'
            , 'Duracion real'
            <?php } ?>
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Estudios asociados'
            }
        },
        legend: {
            reversed: true
        },
        credits: {
             enabled: false
        },
        colorBypoint: true,
        colors: [ '#ede2e8', '#dcc6d1', '#ba8da4', '#975577', '#751c4a', '#58002e', '#420022', '#2c0017', '#210011', '#160000' ],
        plotOptions: {
            series: {
                stacking: 'normal',
            }
        },
        series: [
            <?php
            $btn_colabora_f_1 = $btn_colabora_f_2 = 0; 

            $duracion       = $filas_formaciones[0]['duracion_academica'];
            $nivel          = $filas_formaciones[0]['nivel'];
            $duracion_dos   = $filas_formaciones_dos[0]['duracion_academica'];
            $nivel_dos      = $filas_formaciones_dos[0]['nivel'];
            ?>
            
            <?php if( ($duracion > 16 && $nivel == 11) || ($duracion_dos > 16 && $nivel_dos == 10) ) { ?>
            {
                name: 'Doctorado',
                data: [
                <?php if( isset($duracion) && $duracion > 16 ) { ?>
                <?php if($duracion > 18) {echo ($duracion - 18);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_1 = 9; echo "0, 0";} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 16 ) { ?>
                ,<?php if($filas_formaciones_dos[0]['duracion_academica'] > 18) {echo ($filas_formaciones_dos[0]['duracion_academica'] - 18);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_2 = 9;} ?>
                ]
            },
            <?php } if( ($duracion > 16 && $nivel == 10) || ($duracion_dos > 16 && $nivel_dos == 10) ) { ?>
            {
                name: 'Master',
                data: [
                <?php if( isset($duracion) && $duracion > 16 ) { ?>
                <?php if($duracion < 19) {echo ($duracion - 16);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_1 = 8; echo "0, 0";} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 16 ) { ?>
                ,<?php if($filas_formaciones_dos[0]['duracion_academica'] < 19) {echo ($filas_formaciones_dos[0]['duracion_academica'] - 16);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_2 = 8;} ?>
                ]
            }, 
            <?php } if( $nivel == 9 || $nivel_dos == 9 ) { ?>
            {
                name: 'Oposiciones',
                data: [
                <?php if( isset($duracion) && $duracion > 12 ) { ?>
                <?php if($duracion > 16) {echo ($duracion - 16);} 
                else if($duracion < 17) {echo ($duracion - 12);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_1 = 7; echo "0, 0";} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 12 ) { ?>
                ,<?php if($filas_formaciones_dos[0]['duracion_academica'] > 16) {echo ($filas_formaciones_dos[0]['duracion_academica'] - 16);} 
                else if($filas_formaciones_dos[0]['duracion_academica'] < 17) {echo ($filas_formaciones_dos[0]['duracion_academica'] - 12);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_2 = 7;} ?>
                ]
            }, 
            <?php } if( ($duracion > 12 && $nivel == 8) || ($duracion_dos > 12 && $nivel_dos == 8) ) { ?>
            {
                name: 'Grado Universitario',
                data: [
                <?php if( isset($duracion) && $duracion > 12 ) { ?>
                <?php if($duracion < 17) {echo ($duracion - 12);} else {echo 4;} ?>
                , 0
                <?php } else { $btn_colabora_f_1 = 6; echo "0, 0";} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 12 ) { ?>
                ,<?php if($filas_formaciones_dos[0]['duracion_academica'] < 17) {echo ($filas_formaciones_dos[0]['duracion_academica'] - 12);} else {echo 4;} ?>
                , 0
                <?php } else { $btn_colabora_f_2 = 6;} ?>
                ]
            }, 
            <?php } if( ($duracion > 12 && $nivel == 7) || ($duracion_dos > 12 && $nivel_dos == 7) ) { ?>
            {
                name: 'F.P. Superior',
                data: [
                <?php if( isset($duracion) && $duracion > 12 ) { ?>
                <?php if($duracion < 15) {echo ($duracion - 12);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_1 = 5; echo "0, 0";} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 12 ) { ?>
                ,<?php if($filas_formaciones_dos[0]['duracion_academica'] < 15) {echo ($filas_formaciones_dos[0]['duracion_academica'] - 12);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_2 = 5;} ?>
                ]
            }, 
            <?php } if( ($duracion > 10 && $nivel == 6) || ($duracion_dos > 10 && $nivel_dos == 6) ) { ?>
            {
                name: 'Bachillerato',
                data: [
                <?php if( isset($duracion) && $duracion > 10 ) { ?>
                <?php if($duracion < 13) {echo ($duracion - 10);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_1 = 4; echo "0, 0";} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 10 ) { ?>
                ,<?php if($filas_formaciones_dos[0]['duracion_academica'] < 13) {echo ($filas_formaciones_dos[0]['duracion_academica'] - 10);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_2 = 4;} ?>
                ]
            },
            <?php } if( ($duracion > 10 && $nivel == 5) || ($duracion_dos > 10 && $nivel_dos == 5) ) { ?>
            {
                name: 'F.P. Medio',
                data: [
                <?php if( isset($duracion) && $duracion > 10 ) { ?>
                <?php if($duracion < 13) {echo ($duracion - 10);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_1 = 3; echo "0, 0";} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 10 ) { ?>
                ,<?php if($filas_formaciones_dos[0]['duracion_academica'] < 13) {echo ($filas_formaciones_dos[0]['duracion_academica'] - 10);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_2 = 3;} ?>
                ]
            }, 
            <?php } if( $duracion > 6 || $duracion_dos > 6 ) { ?>
            {
                name: 'E.S.O.',
                data: [
                <?php if( isset($duracion) && $duracion > 6 ) { ?>
                <?php if($duracion < 11) {echo ($duracion - 6);} else {echo 4;} ?>
                , 0
                <?php } else { $btn_colabora_f_1 = 2; echo "0, 0";} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 6 ) { ?>
                ,<?php if($filas_formaciones_dos[0]['duracion_academica'] < 11) {echo ($filas_formaciones_dos[0]['duracion_academica'] - 6);} else {echo 4;} ?>
                , 0
                <?php } else { $btn_colabora_f_2 = 2;} ?>
                ]
            }, 
            <?php } if( $duracion > 0 || $duracion_dos > 0 ) { ?>
            {
                name: 'Primaria',
                data: [
                <?php if( isset($duracion) && $duracion > 0 ) { ?>
                <?php if($duracion < 7) {echo $duracion;} else {echo 6;} ?>
                , 0 
                <?php } else { $btn_colabora_f_1 = 1; echo "0, 0";} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 0 ) { ?>
                ,<?php if($duracion_dos < 7) {echo $duracion_dos;} else {echo 6;} ?>
                , 0
                <?php } else { $btn_colabora_f_2 = 1;} ?>
                ]
            }, 
            <?php } if( isset($filas_formaciones[0]['duracion_real']) || isset($filas_formaciones_dos[0]['duracion_real']) ) { ?>
            {
                name: 'Duracion real',
                data: [
                <?php if( isset($filas_formaciones[0]['duracion_real']) && $filas_formaciones[0]['duracion_real'] > 0 ) { ?>
                0
                ,<?php echo $filas_formaciones[0]['duracion_real']; ?>
                <?php } else { $btn_colabora_f_1 = 10;  echo "0, 0";} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($filas_formaciones_dos[0]['duracion_real']) && $filas_formaciones_dos[0]['duracion_real'] > 0 ) { ?>
                , 0
                ,<?php echo $filas_formaciones_dos[0]['duracion_real']; ?>
                <?php } else { $btn_colabora_f_2 = 10;} ?>
                ]
            }
            <?php } ?>
        ]
    });
});

// Comprobar si se necesitan botones producido
<?php if( $btn_colabora_f_1 || $btn_colabora_f_2 ) { ?>
    // agregar capa de aviso semitransparente (con opcion a quitar?)
    var capa_aviso = "<div class='capa-aviso'>";
    capa_aviso += '<div class="cerrar-aviso"><a href="#"><img class="icon" src="images/cross.svg"></img></a></div>';
    capa_aviso += "<div class='col-md-10 col-md-offset-1'>";
    capa_aviso += "<h3>Aún no tenemos imformación suficiente!</h3>";

    <?php if( $btn_colabora_f_1 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Ayúdanos a completar información sobre <strong>cualidades profesionales</strong> de la profesión<br>";
        capa_aviso += "<strong><?php echo mb_strtoupper($profesion,'UTF-8' ); ?></strong></p>";
        capa_aviso += "<a href='colabora.php?profesion=<?php echo $profesion; ?>' class='btn btn-aviso' style='border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);'>Colabora!</a>";
    <?php } ?>

    <?php if( $btn_colabora_f_2 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Ayúdanos a completar información sobre <strong>cualidades profesionales</strong> de la profesión<br>";
        capa_aviso += "<strong><?php echo mb_strtoupper($profesion_dos,'UTF-8' ); ?></strong></p>";
        capa_aviso += "<a href='colabora.php?profesion=<?php echo $profesion_dos; ?>' class='btn btn-aviso' style='border-color: #337ab7; color: #337ab7;'>Colabora!</a>";
    <?php } ?>

    capa_aviso += "</div>";
    capa_aviso += "</div>";

    // debe aparecer despues de 1 segundo
    $('#container_formacion').append(capa_aviso);
<?php } ?>