
<?php
$btn_colabora_s_1 = $btn_colabora_s_2 = 0;

$s_junior_min = $s_junior_max = $s_intermedio_min = $s_intermedio_max = $s_senior_min = $s_senior_max = 0;
if( !is_null($filas_salarios[0]['s_junior_min']) && !$filas_salarios[0]['s_junior_min'] == 0 )
    $s_junior_min = $filas_salarios[0]['s_junior_min'];
else
    $btn_colabora_s_1 = 1;
if( !is_null($filas_salarios[0]['s_junior_max']) && !$filas_salarios[0]['s_junior_max'] == 0 )
    $s_junior_max = $filas_salarios[0]['s_junior_max'];
else
    $btn_colabora_s_1 = 2;
if( !is_null($filas_salarios[0]['s_intermedio_min']) && !$filas_salarios[0]['s_intermedio_min'] == 0 )
    $s_intermedio_min = $filas_salarios[0]['s_intermedio_min'];
else
    $btn_colabora_s_1 = 3;
if( !is_null($filas_salarios[0]['s_intermedio_max']) && !$filas_salarios[0]['s_intermedio_max'] == 0 )
    $s_intermedio_max = $filas_salarios[0]['s_intermedio_max'];
else
    $btn_colabora_s_1 = 4;
if( !is_null($filas_salarios[0]['s_senior_min']) && !$filas_salarios[0]['s_senior_min'] == 0 )
    $s_senior_min = $filas_salarios[0]['s_senior_min'];
else
    $btn_colabora_s_1 = 5;
if( !is_null($filas_salarios[0]['s_senior_max']) && !$filas_salarios[0]['s_senior_max'] == 0 )
    $s_senior_max = $filas_salarios[0]['s_senior_max'];
else
    $btn_colabora_s_1 = 6;

$s_junior_min_dos = $s_junior_max_dos = $s_intermedio_min_dos = $s_intermedio_max_dos = $s_senior_min_dos = $s_senior_max_dos = 0;
if( !is_null($filas_salarios_dos[0]['s_junior_min']) && !$filas_salarios_dos[0]['s_junior_min'] == 0 )
    $s_junior_min_dos = $filas_salarios_dos[0]['s_junior_min'];
else
    $btn_colabora_s_2 = 1;
if( !is_null($filas_salarios_dos[0]['s_junior_max']) && !$filas_salarios_dos[0]['s_junior_max'] == 0 )
    $s_junior_max_dos = $filas_salarios_dos[0]['s_junior_max'];
else
    $btn_colabora_s_2 = 2;
if( !is_null($filas_salarios_dos[0]['s_intermedio_min']) && !$filas_salarios_dos[0]['s_intermedio_min'] == 0 )
    $s_intermedio_min_dos = $filas_salarios_dos[0]['s_intermedio_min'];
else
    $btn_colabora_s_2 = 3;
if( !is_null($filas_salarios_dos[0]['s_intermedio_max']) && !$filas_salarios_dos[0]['s_intermedio_max'] == 0 )
    $s_intermedio_max_dos = $filas_salarios_dos[0]['s_intermedio_max'];
else
    $btn_colabora_s_2 = 4;
if( !is_null($filas_salarios_dos[0]['s_senior_min']) && !$filas_salarios_dos[0]['s_senior_min'] == 0 )
    $s_senior_min_dos = $filas_salarios_dos[0]['s_senior_min'];
else
    $btn_colabora_s_2 = 5;
if( !is_null($filas_salarios_dos[0]['s_senior_max']) && !$filas_salarios_dos[0]['s_senior_max'] == 0 )
    $s_senior_max_dos = $filas_salarios_dos[0]['s_senior_max'];
else
    $btn_colabora_s_2 = 6;
?>

var salarios = [
    [0, <?php echo $s_junior_min; ?>, <?php echo $s_junior_max; ?>],
    [2, <?php echo $s_junior_min; ?>, <?php echo $s_junior_max; ?>],
    [5, <?php echo $s_intermedio_min; ?>, <?php echo $s_intermedio_max; ?>], 
    [20, <?php echo $s_senior_min; ?>, <?php echo $s_senior_max; ?>]
], medias = [
    [0, <?php echo ($s_junior_min + $s_junior_max) / 2 ; ?>],
    [2, <?php echo ($s_junior_min + $s_junior_max) / 2 ; ?>],
    [5, <?php echo ($s_intermedio_min + $s_intermedio_max) / 2 ; ?>], 
    [20, <?php echo ($s_senior_min + $s_senior_max) / 2 ; ?>]
];
var salarios_dos = [
    [0, <?php echo $s_junior_min_dos; ?>, <?php echo $s_junior_max_dos; ?>],
    [2, <?php echo $s_junior_min_dos; ?>, <?php echo $s_junior_max_dos; ?>],
    [5, <?php echo $s_intermedio_min_dos; ?>, <?php echo $s_intermedio_max_dos; ?>], 
    [20, <?php echo $s_senior_min_dos; ?>, <?php echo $s_senior_max_dos; ?>]
], medias_dos = [
    [0, <?php echo ($s_junior_min_dos + $s_junior_max_dos) / 2 ; ?>],
    [2, <?php echo ($s_junior_min_dos + $s_junior_max_dos) / 2 ; ?>],
    [5, <?php echo ($s_intermedio_min_dos + $s_intermedio_max_dos) / 2 ; ?>], 
    [20, <?php echo ($s_senior_min_dos + $s_senior_max_dos) / 2 ; ?>]
];
$('#container_salarios').highcharts({

    chart: {
        backgroundColor:'rgba(255, 255, 255, 0)',
        spacingBottom: 20,
        spacingTop: 20,
        spacingLeft: 20,
        spacingRight: 20,
        width: null,
        height: 380
    },

    title: {
        text: 'SALARIO',
        align: "center"
    },

    legend: { 
        enable: false 
    },

    xAxis: {
        //categories: ['JUNIOR','INTERMEDIO','SENIOR']
        title: {
            text: 'EXPERIENCIA ' + '(años)'.toUpperCase()
        }
    },

    yAxis: {
        title: {
            text: 'SALARIO NETO (€/mes)'
        }
    },

    tooltip: {
        headerFormat: '<b>{point.x} años de experiencia</b><br>',
        //pointFormat: '{point.x} años de experiencia',
        crosshairs: true,
        shared: true,
        valueSuffix: ' €'
    },

    credits: {
        enabled: false
    },

    plotOptions: {
        arearange: {
            fillOpacity: 0.5
        }
    },

    series: [
        {
            name: '<?php echo $profesion; ?>',
            data: medias,
            zIndex: 1,
            marker: {
                fillColor: 'white',
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[0]
            }
        }, {
            name: '<?php echo $profesion_dos; ?>',
            data: medias_dos,
            zIndex: 1,
            marker: {
                fillColor: 'white',
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[1]
            }
        <?php if( isset($profesion_dos) && !empty($profesion) ){ ?>
        }, {
            name: 'Rango salarial',
            data: salarios,
            type: 'arearange',
            lineWidth: 0,
            linkedTo: '<?php echo $profesion; ?>',
            fillOpacity: 0.3,
            zIndex: 0
        }, {
            name: 'Rango salarial',
            data: salarios_dos,
            type: 'arearange',
            lineWidth: 0,
            linkedTo: '<?php echo $profesion_dos; ?>',
            fillOpacity: 0.3,
            zIndex: 0
        <?php } ?>
        }
    ]
});

// Comprobar si se necesitan botones producido
<?php if( $btn_colabora_s_1 || $btn_colabora_s_2 ) { ?>
    // agregar capa de aviso semitransparente (con opcion a quitar?)
    var capa_aviso = "<div class='capa-aviso'>";
    capa_aviso += '<div class="cerrar-aviso"><a href="#"><img class="icon" src="images/cross.svg"></img></a></div>';
    capa_aviso += "<div class='col-md-10 col-md-offset-1'>";
    capa_aviso += "<h3>Aún no tenemos imformación suficiente!</h3>";

    <?php if( $btn_colabora_s_1 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Ayúdanos a completar información sobre <strong>salario</strong> de la profesión<br>";
        capa_aviso += "<strong><?php echo mb_strtoupper($profesion,'UTF-8' ); ?></strong></p>";
        capa_aviso += "<a href='colabora.php?profesion=<?php echo $profesion; ?>' class='btn btn-aviso' style='border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);'>Colabora!</a>";
    <?php } ?>

    <?php if( $btn_colabora_s_2 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Ayúdanos a completar información sobre <strong>salario</strong> de la profesión<br>";
        capa_aviso += "<strong><?php echo mb_strtoupper($profesion_dos,'UTF-8' ); ?></strong></p>";
        capa_aviso += "<a href='colabora.php?profesion=<?php echo $profesion_dos; ?>' class='btn btn-aviso' style='border-color: #337ab7; color: #337ab7;'>Colabora!</a>";
    <?php } ?>

    capa_aviso += "</div></div>";

    // debe aparecer despues de 1 segundo
    $('#container_salarios').append(capa_aviso);
<?php } ?>