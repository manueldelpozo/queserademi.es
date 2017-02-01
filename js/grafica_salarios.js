
<?php
$btn_colabora_s_1 = $btn_colabora_s_2 = 0;
$s_princ_min = $s_princ_med = $s_princ_max = $s_junior_min = $s_junior_med = $s_junior_max = $s_intermedio_min = $s_intermedio_med = $s_intermedio_max = $s_senior_min = $s_senior_med = $s_senior_max = 0;
$s_princ_min_dos = $s_princ_med_dos = $s_princ_max_dos = $s_junior_min_dos = $s_junior_med_dos = $s_junior_max_dos = $s_intermedio_min_dos = $s_intermedio_med_dos = $s_intermedio_max_dos = $s_senior_min_dos = $s_senior_med_dos = $s_senior_max_dos = 0;

/*function val($s_valor) {
    $r = 0;
    if( !is_null($s_valor) && !empty($s_valor) )
        $r = round($s_valor);
    echo $r;  
}*/
function val($s_valor) {
    echo (!is_null($s_valor) && !empty($s_valor)) ? round($s_valor) : 0;  
}

foreach( $tablas['salarios'] as $rango) { 
    if( isset($profesion) && !empty($profesion) ){
        $$rango = $filas_salarios[0][$rango]; 
        if( is_null($filas_salarios[0][$rango]) || $filas_salarios[0][$rango] == 0 ) // busqueda de nulos
            $btn_colabora_s_1++; 
    }
    if( isset($profesion_dos) && !empty($profesion_dos) ){
        $rango_dos = $rango . '_dos';
        $$rango_dos = $filas_salarios_dos[0][$rango];
        if( is_null($filas_salarios_dos[0][$rango]) || $filas_salarios_dos[0][$rango] == 0 ) // busqueda de nulos
            $btn_colabora_s_2++; 
    }
}

?>
<?php if( isset($profesion) && !empty($profesion) ){ ?>
var salarios = [
    [0, <?php val($s_princ_min); ?>, <?php val($s_princ_max); ?>],
    [5, <?php val($s_junior_min); ?>, <?php val($s_junior_max); ?>],
    [10, <?php val($s_intermedio_min); ?>, <?php val($s_intermedio_max); ?>], 
    [15, <?php val($s_senior_min); ?>, <?php val($s_senior_max); ?>]
], medias = [
    [0, <?php val($s_princ_med); ?>],
    [5, <?php val($s_junior_med); ?>],
    [10, <?php val($s_intermedio_med); ?>], 
    [15, <?php val($s_senior_med); ?>]
];
<?php } ?>
<?php if( isset($profesion_dos) && !empty($profesion_dos) ){ ?>
var salarios_dos = [
    [0, <?php val($s_princ_min_dos); ?>, <?php val($s_princ_max_dos); ?>],
    [5, <?php val($s_junior_min_dos); ?>, <?php val($s_junior_max_dos); ?>],
    [10, <?php val($s_intermedio_min_dos); ?>, <?php val($s_intermedio_max_dos); ?>], 
    [15, <?php val($s_senior_min_dos); ?>, <?php val($s_senior_max_dos); ?>]
], medias_dos = [
    [0, <?php val($s_princ_med_dos); ?>],
    [5, <?php val($s_junior_med_dos); ?>],
    [10, <?php val($s_intermedio_med_dos); ?>], 
    [15, <?php val($s_senior_med_dos); ?>]
];
<?php } ?>

var chartSalarios = {
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
        text: 'SALARIO BRUTO ANUAL',
        align: 'center',
        style: { 
            'color': '#555',
            'fontSize': '14px',
            'fontWeight': 'bold'
        } 
    },
    subtitle: {
        text: '- € / año -'
    },
    legend: { 
        enable: false ,
        itemStyle: {
            width: '300%'
        },
        title: {
            text: '<span>(Click para ocultar)</span>',
            style: {
                fontStyle: 'italic',
                fontSize: '9px',
                color: '#888'
            }
        } 
    },
    xAxis: {
        title: {
            text: 'EXPERIENCIA ' + '(años)'.toUpperCase()
        }
    },
    yAxis: {
        title: {
            text: 'Salario bruto anual - € / año'
        }
    },
    exporting: {
        chartOptions: {
            chart: {
                events: {
                    load: function(event) {                
                        this.renderer.image('http://queserademi.com/images/logo.png', 15, 15, 30, 30).add();
                    }
                } 
            }
        }
    },
    tooltip: {
        shared: true,
        headerFormat: '<strong style="font-size:16px">{point.x} años de experiencia</strong><br>',
        //pointFormat: '<span style="color:{series.color}">{series.name}: </span><strong>{point.y}</strong><br>',
        valueSuffix: ' €',
        style: {
            display: 'block', 
            width: '300px',
            whiteSpace: 'normal' 
        }
    },
    credits: {
        enabled: false
    },
    plotOptions: {
        arearange: {
            fillOpacity: 0.5
        },
        series: {
            allowPointSelect: true
        }
    },
    series: [
        <?php if( isset($profesion) && !empty($profesion) ){ ?>
        {
            name: '<?php echo $profesion; ?>',
            data: medias,
            color: Highcharts.getOptions().colors[0],
            zIndex: 1,
            marker: {
                fillColor: 'white',
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[0]
            }
        },
        <?php } ?>
        <?php if( isset($profesion_dos) && !empty($profesion_dos) ){ ?>
        {
            name: '<?php echo $profesion_dos; ?>',
            data: medias_dos,
            color: Highcharts.getOptions().colors[1],
            zIndex: 1,
            marker: {
                fillColor: 'white',
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[1]
            }
        }, 
        <?php } ?>
        <?php if( isset($profesion) && !empty($profesion) ){ ?>
        {
            name: 'Rango salarial',
            data: salarios,
            type: 'arearange',
            lineWidth: 0,
            linkedTo: '<?php echo $profesion; ?>',
            color: Highcharts.getOptions().colors[0],
            fillOpacity: 0.3,
            zIndex: 0
        }, 
        <?php } ?>
        <?php if( isset($profesion_dos) && !empty($profesion_dos) ){ ?>
        {
            name: 'Rango salarial',
            data: salarios_dos,
            type: 'arearange',
            lineWidth: 0,
            linkedTo: '<?php echo $profesion_dos; ?>',
            color: Highcharts.getOptions().colors[1],
            fillOpacity: 0.3,
            zIndex: 0
        }
        <?php } ?>
    ]
};

$('#container_salarios').highcharts(chartSalarios);

// Comprobar si los datos han salido iguales(objetos) en la comparacion pero con nombres distintos
if (chartSalarios.series.length == 4 && JSON.stringify(chartSalarios.series[0].data)==JSON.stringify(chartSalarios.series[1].data) && chartSalarios.series[0].name !== chartSalarios.series[1].name) {
    var capa_iguales = '<div class="capa-aviso">';
    capa_iguales += '<div class="cerrar-aviso"><a href="#"><img class="icon" src="images/cross.svg"></img></a></div>';
    capa_iguales += '<div class="col-md-10 col-md-offset-1">';
    capa_iguales += '<h3>Atención! Aparecen gráficas similares porque se usan datos generales.</h3>';

    capa_iguales += '<p class="text-center">Ayúdanos a tener información específica sobre <strong>salarios</strong>:<br><br>';
    <?php if( isset($profesion) && !empty($profesion) ){ ?>
        capa_iguales += '<strong><?php echo mb_strtoupper($profesion,"UTF-8"); ?></strong></p>';
        capa_iguales += '<a href="colabora.php?profesion=<?php echo $profesion; ?>" class="btn btn-aviso" style="border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);">Colabora!</a>';
    <?php  }  ?> 
    <?php if( isset($profesion_dos) && !empty($profesion_dos) ){ ?>
        capa_iguales += '<br><strong><?php echo mb_strtoupper($profesion_dos,"UTF-8"); ?></strong></p>';
        capa_iguales += '<a href="colabora.php?profesion=<?php echo $profesion_dos; ?>" class="btn btn-aviso" style="border-color: #337ab7; color: #337ab7;">Colabora!</a>';
    <?php  }  ?> 
    
    capa_iguales += '</div>';
    capa_iguales += '</div>';

    $('#container_salarios').append(capa_iguales);
}

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