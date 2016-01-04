
<?php
$btn_colabora_s_1 = $btn_colabora_s_2 = 0;
$s_princ_min = $s_princ_med = $s_princ_max = $s_junior_min = $s_junior_med = $s_junior_max = $s_intermedio_min = $s_intermedio_med = $s_intermedio_max = $s_senior_min = $s_senior_med = $s_senior_max = 0;
$s_princ_min_dos = $s_princ_med_dos = $s_princ_max_dos = $s_junior_min_dos = $s_junior_med_dos = $s_junior_max_dos = $s_intermedio_min_dos = $s_intermedio_med_dos = $s_intermedio_max_dos = $s_senior_min_dos = $s_senior_med_dos = $s_senior_max_dos = 0;

function imprimirSeriesSal($fila, $btn, $btn_colabora) {
    if( !is_null($fila) && !$fila == 0 )
        return $fila;
    else
        $btn_colabora = $btn + 1;
}

/*foreach( $tablas['salarios'] as $n => $rango) {
    $$rango = imprimirSeriesSal($filas_salarios[0][$rango], $n, $btn_colabora_s_1);
    if( isset($profesion_dos) && !empty($profesion_dos) ){
        $rango_dos = $rango . '_dos';
        $$rango_dos = imprimirSeriesSal($filas_salarios_dos[0][$rango], $n, $btn_colabora_s_2);
    }
}*/

$s_princ_min = $filas_salarios[0]['s_princ_min'];
$s_princ_med = $filas_salarios[0]['s_princ_med'];
$s_princ_max = $filas_salarios[0]['s_princ_max'];
$s_junior_min = $filas_salarios[0]['s_junior_min'];
$s_junior_med = $filas_salarios[0]['s_junior_med'];
$s_junior_max = $filas_salarios[0]['s_junior_max'];
$s_intermedio_min = $filas_salarios[0]['s_intermedio_min'];
$s_intermedio_med = $filas_salarios[0]['s_intermedio_med'];
$s_intermedio_max = $filas_salarios[0]['s_intermedio_max'];
$s_senior_min = $filas_salarios[0]['s_senior_min'];
$s_senior_med = $filas_salarios[0]['s_senior_med'];
$s_senior_max = $filas_salarios[0]['s_senior_max'];

if( isset($profesion_dos) && !empty($profesion_dos) ) {
    $s_princ_min_dos = $filas_salarios_dos[0]['s_princ_min'];
    $s_princ_med_dos = $filas_salarios_dos[0]['s_princ_med'];
    $s_princ_max_dos = $filas_salarios_dos[0]['s_princ_max'];
    $s_junior_min_dos = $filas_salarios_dos[0]['s_junior_min'];
    $s_junior_med_dos = $filas_salarios_dos[0]['s_junior_med'];
    $s_junior_max_dos = $filas_salarios_dos[0]['s_junior_max'];
    $s_intermedio_min_dos = $filas_salarios_dos[0]['s_intermedio_min'];
    $s_intermedio_med_dos = $filas_salarios_dos[0]['s_intermedio_med'];
    $s_intermedio_max_dos = $filas_salarios_dos[0]['s_intermedio_max'];
    $s_senior_min_dos = $filas_salarios_dos[0]['s_senior_min'];
    $s_senior_med_dos = $filas_salarios_dos[0]['s_senior_med'];
    $s_senior_max_dos = $filas_salarios_dos[0]['s_senior_max'];
}

function val($s_valor, $btn_colabora) {
    $r;
    $btn_colabora = 'btn_colabora_' . $btn_colabora;
    if( !is_null($s_valor) && !empty($s_valor) ) {
        $r = $s_valor;
        $$btn_colabora = 0;
    } else {
        $r = 0;
        $$btn_colabora = 1;
    }
    echo $r;  
}

?>

var salarios = [
    [0, <?php val($s_princ_min, 's_1'); ?>, <?php val($s_princ_max, 's_1'); ?>],
    [5, <?php val($s_junior_min, 's_1'); ?>, <?php val($s_junior_max, 's_1'); ?>],
    [10, <?php val($s_intermedio_min, 's_1'); ?>, <?php val($s_intermedio_max, 's_1'); ?>], 
    [15, <?php val($s_senior_min, 's_1'); ?>, <?php val($s_senior_max, 's_1'); ?>]
], medias = [
    [0, <?php val($s_princ_med, 's_1'); ?>],
    [5, <?php val($s_junior_med, 's_1'); ?>],
    [10, <?php val($s_intermedio_med, 's_1'); ?>], 
    [15, <?php val($s_senior_med, 's_1'); ?>]
];

<?php if( isset($profesion_dos) && !empty($profesion_dos) ){ ?>
var salarios_dos = [
    [0, <?php val($s_princ_min_dos, 's_2'); ?>, <?php val($s_princ_max_dos, 's_2'); ?>],
    [5, <?php val($s_junior_min_dos, 's_2'); ?>, <?php val($s_junior_max_dos, 's_2'); ?>],
    [10, <?php val($s_intermedio_min_dos, 's_2'); ?>, <?php val($s_intermedio_max_dos, 's_2'); ?>], 
    [15, <?php val($s_senior_min_dos, 's_2'); ?>, <?php val($s_senior_max_dos, 's_2'); ?>]
], medias_dos = [
    [0, <?php val($s_princ_med_dos, 's_2'); ?>],
    [5, <?php val($s_junior_med_dos, 's_2'); ?>],
    [10, <?php val($s_intermedio_med_dos, 's_2'); ?>], 
    [15, <?php val($s_senior_med_dos, 's_2'); ?>]
];
<?php } ?>

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
        text: 'SALARIO BRUTO ANUAL (€/año)',
        align: 'center'
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
            text: 'SALARIO BRUTO ANUAL'
        }
    },

    tooltip: {
        headerFormat: '<strong>{point.x} años de experiencia</strong><br>',
        //pointFormat: '{point.x} años de experiencia',
        crosshairs: true,
        shared: true,
        valueSuffix: ' €'
    },

    credits: {
        enabled: false
    },

    //colorBypoint: true,
    //colors: [ '#cc0000', '#cc0000', '#337ab7', '#337ab7' ],

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
        <?php if( isset($profesion_dos) && !empty($profesion_dos) ){ ?>
        }, {
            name: '<?php echo $profesion_dos; ?>',
            data: medias_dos,
            zIndex: 1,
            marker: {
                fillColor: 'white',
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[1]
            }
        <?php } ?>
        }, {
            name: 'Rango salarial',
            data: salarios,
            type: 'arearange',
            lineWidth: 0,
            linkedTo: '<?php echo $profesion; ?>',
            fillOpacity: 0.3,
            zIndex: 0
        <?php if( isset($profesion_dos) && !empty($profesion_dos) ){ ?>
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
    var capa_aviso = '<div class="capa-aviso">';
    capa_aviso += '<div class="cerrar-aviso"><a href="#"><img class="icon" src="images/cross.svg"></img></a></div>';
    capa_aviso += '<div class="col-md-10 col-md-offset-1">';
    capa_aviso += '<h3>Aún no tenemos imformación suficiente!</h3>';

    <?php if( $btn_colabora_s_1 > 0 ) { ?>
        capa_aviso += '<p class="text-center">Ayúdanos a completar información sobre <strong>salario</strong> de la profesión<br>';
        capa_aviso += '<strong><?php echo mb_strtoupper($profesion,"UTF-8"); ?></strong></p>';
        capa_aviso += '<a href="colabora.php?profesion=<?php echo $profesion; ?>" class="btn btn-aviso" style="border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);">Colabora!</a>';
    <?php } ?>

    <?php if( $btn_colabora_s_2 > 0 ) { ?>
        capa_aviso += '<p class="text-center">Ayúdanos a completar información sobre <strong>salario</strong> de la profesión<br>';
        capa_aviso += '<strong><?php echo mb_strtoupper($profesion_dos,"UTF-8"); ?></strong></p>';
        capa_aviso += '<a href="colabora.php?profesion=<?php echo $profesion_dos; ?>" class="btn btn-aviso" style="border-color: #337ab7; color: #337ab7;">Colabora!</a>';
    <?php } ?>

    capa_aviso += '</div></div>';

    // debe aparecer despues de 1 segundo
    $('#container_salarios').append(capa_aviso);
<?php } ?>