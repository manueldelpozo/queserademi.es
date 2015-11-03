/*
$('#container_capacidades').highcharts({
    chart: {
        polar: true,
        type: 'line',
        backgroundColor:'rgba(255, 255, 255, 0.5)',
        // Edit chart size
        spacingBottom: 20,
        spacingTop: 20,
        spacingLeft: 20,
        spacingRight: 20,
        width: null,
        height: 380
    },
    title: {
        text: 'CUALIDADES PROFESIONALES',
        align: "center"
    },
    legend: { enable: false },
    pane: {
        size: '80%'
    },
    xAxis: {
        categories: ['Memoria', 'Creatividad', 'Comunicación', 'Forma física', 'Lógica','Otro'],
        tickmarkPlacement: 'on',
        lineWidth: 0,
        gridLineColor: '#999999'
    },
    yAxis: {
        gridLineInterpolation: 'polygon',
        lineWidth: 0,
        min: 0,
        gridLineColor: '#999999'
    },
    tooltip: {
        shared: true,
        pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
    },
    credits: {
         enabled: false
    },
    series: [{
        <?php $btn_colabora_c_1=0;$btn_colabora_c_2=0; ?>
        name: '<?php echo mb_strtoupper($filas_capacidades[0]["nombre_ppal"],"UTF-8" ); ?>',
        data: [
        <?php if( is_null($filas_capacidades['c_memoria']) || $filas_capacidades['c_memoria'] == 0 ) {echo 0;$btn_colabora_c_1+=1;} else {echo $filas_capacidades['c_memoria'];} ?>,
        <?php if( is_null($filas_capacidades['c_creatividad']) || $filas_capacidades['c_creatividad'] == 0 ) {echo 0;$btn_colabora_c_1+=2;} else {echo $filas_capacidades['c_creatividad'];} ?>,
        <?php if( is_null($filas_capacidades['c_comunicacion']) || $filas_capacidades['c_comunicacion'] == 0 ) {echo 0;$btn_colabora_c_1+=4;} else {echo $filas_capacidades['c_comunicacion'];} ?>,
        <?php if( is_null($filas_capacidades['c_forma_fisica']) || $filas_capacidades['c_forma_fisica'] == 0 ) {echo 0;$btn_colabora_c_1+=8;} else {echo $filas_capacidades['c_forma_fisica'];} ?>,
        <?php if( is_null($filas_capacidades['c_logica']) || $filas_capacidades['c_logica'] == 0 ) {echo 0;$btn_colabora_c_1+=16;} else {echo $filas_capacidades['c_logica'];} ?>
        ],
        stack: '<?php echo $filas_capacidades[0]["nombre_ppal"] ?>'
        <?php if( isset($profesion_dos) && !empty($filas_capacidades_dos[0]["nombre_ppal"]) ){ ?>
    }, {
        name: '<?php echo mb_strtoupper($filas_capacidades_dos[0]["nombre_ppal"],"UTF-8" ); ?>',
        data: [
        <?php if( is_null($filas_capacidades_dos['c_memoria']) || $filas_capacidades_dos['c_memoria'] == 0 ) {echo 0;$btn_colabora_c_2+=1;} else {echo $filas_capacidades_dos['c_memoria'];} ?>,
        <?php if( is_null($filas_capacidades_dos['c_creatividad']) || $filas_capacidades_dos['c_creatividad'] == 0 ) {echo 0;$btn_colabora_c_2+=2;} else {echo $filas_capacidades_dos['c_creatividad'];} ?>,
        <?php if( is_null($filas_capacidades_dos['c_comunicacion']) || $filas_capacidades_dos['c_comunicacion'] == 0 ) {echo 0;$btn_colabora_c_2+=4;} else {echo $filas_capacidades_dos['c_comunicacion'];} ?>,
        <?php if( is_null($filas_capacidades_dos['c_forma_fisica']) || $filas_capacidades_dos['c_forma_fisica'] == 0 ) {echo 0;$btn_colabora_c_2+=8;} else {echo $filas_capacidades_dos['c_forma_fisica'];} ?>,
        <?php if( is_null($filas_capacidades_dos['c_logica']) || $filas_capacidades_dos['c_logica'] == 0 ) {echo 0;$btn_colabora_c_2+=16;} else {echo $filas_capacidades_dos['c_logica'];} ?> 
        ],
        stack: '<?php echo $filas_capacidades_dos[0]["nombre_ppal"] ?>' 
        <?php  }  ?> 
    }]
});
*/





$('#container_satisfaccion').highcharts({
    chart: {
        type: 'scatter',
        zoomType: 'xy'
    },
    title: {
        text: 'GRADO DE SATISFACCION'
    },
    xAxis: {
        title: {
            enabled: true,
            text: 'Experiencia'
        },
        startOnTick: true,
        endOnTick: true,
        showLastLabel: true
    },
    yAxis: {
        title: {
            text: 'Grado de satisfaccion'
        }
    },
    legend: {
        layout: 'vertical',
        align: 'left',
        verticalAlign: 'top',
        x: 100,
        y: 70,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF',
        borderWidth: 1
    },
    plotOptions: {
        scatter: {
            marker: {
                radius: 5,
                states: {
                    hover: {
                        enabled: true,
                        lineColor: 'rgb(100,100,100)'
                    }
                }
            },
            states: {
                hover: {
                    marker: {
                        enabled: false
                    }
                }
            },
            tooltip: {
                headerFormat: '<b>{series.name}</b><br>',
                pointFormat: '{point.x} cm, {point.y} kg'
            }
        }
    },
    series: [{
        <?php $btn_colabora_c_1=0;$btn_colabora_c_2=0; ?>
        name: '<?php echo mb_strtoupper($filas_satisfaccion[0]["nombre_ppal"],"UTF-8" ); ?>',
        color: 'rgba(223, 83, 83, .5)',
        data: [
        <?php foreach ($filas_satisfaccion as $fila_sat) { ?>
            [ 
        <?php if( is_null($fila_sat['experiencia']) || $fila_sat['experiencia'] == 0 ) {echo 0;$btn_colabora_e_1+=1;} else {echo $fila_sat['experiencia'];} ?>,
        <?php if( is_null($fila_sat['satisfaccion']) || $fila_sat['satisfaccion'] == 0 ) {echo 0;$btn_colabora_e_1+=1;} else {echo $fila_sat['satisfaccion'];} ?>
            ],
        <?php } ?>
        ],
        stack: '<?php echo $filas_satisfaccion[0]["nombre_ppal"] ?>'
        <?php if( isset($profesion_dos) && !empty($filas_satisfaccion_dos[0]["nombre_ppal"]) ){ ?>

    }, {
        name: '<?php echo mb_strtoupper($filas_satisfaccion_dos[0]["nombre_ppal"],"UTF-8" ); ?>',
        color: 'rgba(119, 152, 191, .5)',
        data: [
        <?php foreach ($filas_satisfaccion_dos as $fila_sat_dos) { ?>
            [ 
        <?php if( is_null($fila_sat_dos['experiencia']) || $fila_sat_dos['experiencia'] == 0 ) {echo 0;$btn_colabora_e_1+=1;} else {echo $fila_sat_dos['experiencia'];} ?>,
        <?php if( is_null($fila_sat_dos['satisfaccion']) || $fila_sat_dos['satisfaccion'] == 0 ) {echo 0;$btn_colabora_e_1+=1;} else {echo $fila_sat_dos['satisfaccion'];} ?>
            ],
        <?php } ?>
        ],
        stack: '<?php echo $filas_satisfaccion_dos[0]["nombre_ppal"] ?>'
        <?php } ?>
    }]
});






$(function () {
    $('#container_formacion').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Evolucion educativa'
        },
        xAxis: {
            categories: ['Profesion1', 'Profesion1_real','Profesion2', 'Profesion2_real']
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Tiempo de formacion'
            }
        },
        legend: {
            reversed: true
        },
        plotOptions: {
            series: {
                stacking: 'normal'
            }
        },
        series: [{
            <?php $btn_colabora_f_1=0;$btn_colabora_f_2=0; ?>
            name: 'E.S.O.',
            data: [4, 4, 4, 4]
        }, {
            name: 'Bachillerato',
            data: [2, 2, 2, 2]
        }, {
            name: 'Universidad',
            data: [
            <?php foreach ($filas_formacion as $fila_form) { ?>
            <?php if( is_null($fila_form['duracion_academica']) || $fila_form['duracion_academica'] == 0 ) {echo 0;$btn_colabora_f_1+=1;} else {echo $fila_form['duracion_academica'];} ?>,
            <?php if( is_null($fila_form['duracion_real']) || $fila_form['duracion_real'] == 0 ) {echo 0;$btn_colabora_f_1+=1;} else {echo $fila_form['duracion_real'];} ?>,
            <?php } ?>
            ]
        }]
    });
});

// Comprobar si se necesitan botones producido
<?php if( $btn_colabora_c_1 || $btn_colabora_c_2 ) { ?>
    // agregar capa de aviso semitransparente (con opcion a quitar?)
    var capa_aviso = "<div class='capa-aviso'>";
    capa_aviso += '<div class="cerrar-aviso"><a href="#"><img class="icon" src="images/cross.svg"></img></a></div>';
    capa_aviso += "<div class='col-md-10 col-md-offset-1'>";
    capa_aviso += "<h3>Aún no tenemos imformación suficiente!</h3>";

    <?php if( $btn_colabora_c_1 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Ayúdanos a completar información sobre <strong>cualidades profesionales</strong> de la profesión<br>";
        capa_aviso += "<strong><?php echo mb_strtoupper($filas_capacidades[0]['nombre_ppal'],'UTF-8' ); ?></strong></p>";
        capa_aviso += "<a href='colabora.php?profesion=<?php echo $filas_capacidades[0]['nombre_ppal']; ?>' class='btn btn-aviso' style='border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);'>Colabora!</a>";
    <?php } ?>

    <?php if( $btn_colabora_c_2 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Ayúdanos a completar información sobre <strong>cualidades profesionales</strong> de la profesión<br>";
        capa_aviso += "<strong><?php echo mb_strtoupper($filas_capacidades_dos[0]['nombre_ppal'],'UTF-8' ); ?></strong></p>";
        capa_aviso += "<a href='colabora.php?profesion=<?php echo $filas_capacidades_dos[0]['nombre_ppal']; ?>' class='btn btn-aviso' style='border-color: #337ab7; color: #337ab7;'>Colabora!</a>";
    <?php } ?>

    capa_aviso += "</div>";
    capa_aviso += "</div>";

    // debe aparecer despues de 1 segundo
    $('#container_capacidades').append(capa_aviso);


<?php } ?>