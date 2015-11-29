<?php
$i = 0;

$formacion          = $filas_formaciones[$i]['f_nombre_ppal'];
$duracion           = $filas_formaciones[$i]['duracion_academica'];
$duracion_real      = $filas_formaciones[$i]['duracion_real'];
$nivel              = $filas_formaciones[$i]['nivel'];

$formacion_dos      = $filas_formaciones_dos[$i]['f_nombre_ppal'];
$duracion_dos       = $filas_formaciones_dos[$i]['duracion_academica'];
$duracion_real_dos  = $filas_formaciones_dos[$i]['duracion_real'];
$nivel_dos          = $filas_formaciones_dos[$i]['nivel'];
?>

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
            '<?php echo mb_strtoupper($profesion, "UTF-8")."<br><strong>".$formacion." &gt;&gt;</strong>"; ?>'
            , '(Duracion real estimada) <?php echo "<br><strong>".$formacion." &gt;&gt;</strong>"; ?>'
            <?php if( isset($profesion_dos) && !empty($profesion_dos) ) { ?>
            , '<?php echo mb_strtoupper($profesion_dos, "UTF-8")."<br><strong>".$formacion_dos." &gt;&gt;</strong>"; ?>'
            , '(Duracion real estimada) <?php echo "<br><strong>".$formacion_dos." &gt;&gt;</strong>"; ?>'
            <?php } ?>
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Duracion de estudios (años)'
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
            },
            scatter: {
                tooltip: {
                    pointFormat: '{point.x} años de estudios'
                }
            }
        },
        exporting: {
            buttons: {
                /*customButton: {
                    text: '+',
                    onclick: function () {
                        <?php 
                        if ($i < 3) {                             
                            $i++;  
                        } else { 
                            $i = 0;
                        } 
                        ?>
                    }
                },*/
               anotherButton: {
                    text: 'Donde estudiar?',
                    onclick: function () {
                        alert('Donde estudiar? En desarrollo... Disculpe las molestias');
                    }
                }
            }
        },
        <?php
        $btn_colabora_f_1 = $btn_colabora_f_2 = 0; 

        $formacion          = $filas_formaciones[$i]['f_nombre_ppal'];
        $duracion           = $filas_formaciones[$i]['duracion_academica'];
        $duracion_real      = $filas_formaciones[$i]['duracion_real'];
        $nivel              = $filas_formaciones[$i]['nivel'];
        //$formacion_b        = $filas_formaciones[1]['f_nombre_ppal'];
        //$duracion_b         = $filas_formaciones[1]['duracion_academica'];
        //$nivel_b            = $filas_formaciones[1]['nivel'];

        $formacion_dos      = $filas_formaciones_dos[$i]['f_nombre_ppal'];
        $duracion_dos       = $filas_formaciones_dos[$i]['duracion_academica'];
        $duracion_real_dos  = $filas_formaciones_dos[$i]['duracion_real'];
        $nivel_dos          = $filas_formaciones_dos[$i]['nivel'];
        //$formacion_b_dos    = $filas_formaciones_dos[1]['f_nombre_ppal'];
        //$duracion_b_dos     = $filas_formaciones_dos[1]['duracion_academica'];
        //$nivel_b_dos        = $filas_formaciones_dos[1]['nivel'];

        $doctorado = $master = $universidad = $fp_superior = false;
        $doctorado_dos = $master_dos = $universidad_dos = $fp_superior_dos = false;
        ?>
        series: [         
            <?php if( ($duracion > 16 && $nivel == 11) || ($duracion_dos > 16 && $nivel_dos == 11) ) { ?>
            {
                name: 'Doctorado',
                data: [
                <?php if( isset($duracion) && $duracion > 16 ) { $doctorado = true; ?>
                <?php if($duracion > 18) {echo ($duracion - 18);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_1 = 9; echo '0, 0';} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 16 ) { $doctorado_dos = true; ?>
                ,<?php if($duracion_dos > 18) {echo ($duracion_dos - 18);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_2 = 9;} ?>
                ]
            },
            <?php } if( ($duracion > 16 && $nivel == 10) || ($duracion_dos > 16 && $nivel_dos == 10) ) { ?>
            {
                name: 'Master',
                data: [
                <?php if( isset($duracion) && $duracion > 16 ) { $master = true; ?>
                <?php if($duracion < 19) {echo ($duracion - 16);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_1 = 8; echo '0, 0';} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 16 ) { $master_dos = true; ?>
                ,<?php if($duracion_dos < 19) {echo ($duracion_dos - 16);} else {echo 2;} ?>
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
                <?php } else { $btn_colabora_f_1 = 7; echo '0, 0';} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 12 ) { ?>
                ,<?php if($duracion_dos > 16) {echo ($duracion_dos - 16);} 
                else if($duracion_dos < 17) {echo ($duracion_dos - 12);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_2 = 7;} ?>
                ]
            }, 
            <?php } if( ($duracion > 12 || $nivel == 8) || ($duracion_dos > 12 && $nivel_dos == 8) || $master || $master_dos || $doctorado || $doctorado_dos ) { ?>
            {
                name: 'Grado Universitario',
                data: [
                <?php if( isset($duracion) && $duracion > 12 ) { $universidad = true; ?>
                <?php if($duracion < 17) {echo ($duracion - 12);} else {echo 4;} ?>
                , 0
                <?php } else { $btn_colabora_f_1 = 6; echo '0, 0';} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 12 ) { $universidad_dos = true; ?>
                ,<?php if($duracion_dos < 17) {echo ($duracion_dos - 12);} else {echo 4;} ?>
                , 0
                <?php } else { $btn_colabora_f_2 = 6;} ?>
                ]
            }, 
            <?php } if( ($duracion > 12 && $nivel == 7) || ($duracion_dos > 12 && $nivel_dos == 7) ) { ?>
            {
                name: 'F.P. Superior',
                data: [
                <?php if( isset($duracion) && $duracion > 12 ) { $fp_superior = true; ?>
                <?php if($duracion < 15) {echo ($duracion - 12);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_1 = 5; echo '0, 0';} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 12 ) { $fp_superior_dos = true; ?>
                ,<?php if($duracion_dos < 15) {echo ($duracion_dos - 12);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_2 = 5;} ?>
                ]
            }, 
            <?php } if( ($duracion > 10 && $nivel == 6) || ($duracion_dos > 10 && $nivel_dos == 6) || $universidad || $universidad_dos || $fp_superior || $fp_superior_dos ) { ?>
            {
                name: 'Bachillerato',
                data: [
                <?php if( isset($duracion) && $duracion > 10 ) { ?>
                <?php if($duracion < 13) {echo ($duracion - 10);} else {echo 2;} ?>
                , 0
                <?php } else { $btn_colabora_f_1 = 4; echo '0, 0';} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 10 ) { ?>
                ,<?php if($duracion_dos < 13) {echo ($duracion_dos - 10);} else {echo 2;} ?>
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
                <?php } else { $btn_colabora_f_1 = 3; echo '0, 0';} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 10 ) { ?>
                ,<?php if($duracion_dos < 13) {echo ($duracion_dos - 10);} else {echo 2;} ?>
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
                <?php } else { $btn_colabora_f_1 = 2; echo '0, 0';} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 6 ) { ?>
                ,<?php if($duracion_dos < 11) {echo ($duracion_dos - 6);} else {echo 4;} ?>
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
                <?php } else { $btn_colabora_f_1 = 1; echo '0, 0';} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_dos) && $duracion_dos > 0 ) { ?>
                ,<?php if($duracion_dos < 7) {echo $duracion_dos;} else {echo 6;} ?>
                , 0
                <?php } else { $btn_colabora_f_2 = 1;} ?>
                ]
            }, 
            <?php } if( isset($duracion_real) || isset($duracion_real_dos) ) { ?>
            {
                name: 'Duracion real estimada',
                data: [
                <?php if( isset($duracion_real) && $duracion_real_dos > 0 ) { ?>
                0
                ,<?php echo $duracion_real; ?>
                <?php } else { $btn_colabora_f_1 = 10;  echo '0, 0';} ?>
                <?php if( isset($profesion_dos) && !empty($profesion_dos) && isset($duracion_real_dos) && $duracion_real_dos > 0 ) { ?>
                , 0
                ,<?php echo $duracion_real_dos; ?>
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
    var capa_aviso = '<div class="capa-aviso">';
    capa_aviso += '<div class="cerrar-aviso"><a href="#"><img class="icon" src="images/cross.svg"></img></a></div>';
    capa_aviso += '<div class="col-md-10 col-md-offset-1">';
    capa_aviso += '<h3>Aún no tenemos imformación suficiente!</h3>';

    <?php if( $btn_colabora_f_1 > 0 ) { ?>
        capa_aviso += '<p class="text-center">Ayúdanos a completar información sobre <strong>formacion</strong> de la profesión<br>';
        capa_aviso += '<strong><?php echo mb_strtoupper($profesion,"UTF-8"); ?></strong></p>';
        capa_aviso += '<a href="colabora.php?profesion=<?php echo $profesion; ?>" class="btn btn-aviso" style="border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);">Colabora!</a>';
    <?php } ?>

    <?php if( $btn_colabora_f_2 > 0 ) { ?>
        capa_aviso += '<p class="text-center">Ayúdanos a completar información sobre <strong>formacion</strong> de la profesión<br>';
        capa_aviso += '<strong><?php echo mb_strtoupper($profesion_dos,"UTF-8"); ?></strong></p>';
        capa_aviso += '<a href="colabora.php?profesion=<?php echo $profesion_dos; ?>" class="btn btn-aviso" style="border-color: #337ab7; color: #337ab7;">Colabora!</a>';
    <?php } ?>

    capa_aviso += '</div>';
    capa_aviso += '</div>';

    // debe aparecer despues de 1 segundo
    $('#container_capacidades').append(capa_aviso);<?php } ?>