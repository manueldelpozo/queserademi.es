<?php 
$btn_colabora_e_1 = $btn_colabora_e_2 = 0;
$meses = ['enero', 'abril', 'julio', 'octubre'];
$meses = array_merge($meses, $meses, $meses); // concatenar meses 
$anyos = ['2014', '2015', '2016'];
//array_pop($meses); // y eliminar el ultimo elemento

function mediaEmpleabilidad($pdo, $meses, $anyos) {
    $medias = array();
    foreach ($meses as $n_mes => $mes) {
        $media = array();
        $anyo = $anyos[ceil(($n_mes + 1) / (count($meses) / count($anyos))) - 1];
        $consulta = "SELECT parados, contratados FROM empleabilidad WHERE mes LIKE '". $mes ."' AND anyo LIKE ". $anyo;
        $rs = $pdo->prepare($consulta);
        $rs->execute();
        $filas = $rs->fetchAll();
        foreach ($filas as $fila) {
            $media[] = empleabilidad($fila['contratados'], $fila['parados']);
        }
        $medias[] = round(array_sum($media) / count($media), 2);
    }
    return $medias;
}

$media_min = min(mediaEmpleabilidad($pdo, $meses, $anyos));
$media_max = max(mediaEmpleabilidad($pdo, $meses, $anyos));

function coefMin($parados) {
    $output = 1;
    $n = 15000;
    $m = 0.95;
    while ($n >= 1000) {
        if ($parados < $n)
            $output = $m;
        $n -= 1000;
        $m -= 0.05;
    }
    if ($parados < 100)
        $output = 0.1;
    return $output;
}

function empleabilidad($contratados, $parados) {
    return (!is_null($parados) && $parados > 0) ?  round(coefMin($parados) * round(100 - ($contratados * 100 / ($parados + $contratados)), 2), 2) : 0;
}

function imprimirSeriesEmp($filas, $n_meses) {
    $counter = 0;
    $counter_rect = 0;
    $no_duplicado = true;
    $memo = [];
    $seriesEmp = array();
    foreach ($filas as $fila) {
        $memo[$counter] = $fila;
        if (count($memo) > 1)
            $no_duplicado = ($memo[$counter - 1]['mes'] !== $memo[$counter]['mes']);
        if ($no_duplicado && $counter_rect < $n_meses) {
            $counter_rect++;
            $emp = empleabilidad($fila['contratados'], $fila['parados']);
            echo (is_null($emp) || $emp == 0) ? "0," : $emp.",";
            //array_push($seriesEmp, (is_null($emp) || $emp == 0) ? "0" : $emp); 
        }
        $counter++;
    }
    //return $seriesEmp;
}

// busqueda de nulos
if( isset($profesion) && !empty($profesion) ){
    foreach ($filas_empleabilidad as $fila_empleabilidad) { 
        $empleabilidad = empleabilidad($fila_empleabilidad['contratados'], $fila_empleabilidad['parados']); 
        if(is_null($empleabilidad))
            $btn_colabora_e_1++;
    }
}
if( isset($profesion_dos) && !empty($profesion_dos) ){
    foreach ($filas_empleabilidad_dos as $fila_empleabilidad_dos) { 
        $empleabilidad_dos = empleabilidad($fila_empleabilidad_dos['contratados'], $fila_empleabilidad_dos['parados']); 
        if(is_null($empleabilidad_dos))
            $btn_colabora_e_2++;
    }
}
?>

var chartEmpleabilidad = {
    chart: {
        type: 'column',
        marginTop: 80,
        marginRight: 40,
        backgroundColor:'rgba(255, 255, 255, 0)',
        // Edit chart size
        spacingBottom: 20,
        spacingTop: 20,
        spacingLeft: 20,
        spacingRight: 20,
        width: null,
        height: 380
    },
    title: {
        text: 'PARO',
        align: "center",
        style: { 
            'color': '#555',
            'fontSize': '14px',
            'fontWeight': 'bold'
        }
    },
    subtitle: {
        text: '- DIFICULTAD DE CONSEGUIR TRABAJO -'
    },
    legend: { 
        enable: false,
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
        categories: [ 
        <?php 
         foreach ($meses as $n_mes => $mes) { 
            $anyo = $anyos[ceil(($n_mes + 1) / (count($meses) / count($anyos))) - 1];
            echo "'".ucfirst($mes)." ".$anyo."'";
            if ($n_mes + 1 < count($meses))
                echo ',';
        }
        ?>
        ]
    },
    yAxis: {
        allowDecimals: true,
        min: 0,
        title: {
            text: 'Dificultad de conseguir trabajo %'
        },
        plotBands: [{ // Paro alto
                from: <?php echo $media_max; ?>,
                to: 100,
                color: 'rgba(0, 0, 0, 0.3)',
                label: {
                    rotation: 90,
                    verticalAlign: "top",
                    x: 2,
                    text: 'ALTO',
                    style: {
                        color: '#DDDDDD',
                        fontSize:'12px'
                    }
                }
            }, { // Paro medio
                from: <?php echo $media_min; ?>,
                to: <?php echo $media_max; ?>,
                color: 'rgba(0, 0, 0, 0.2)'
            }, { // Paro bajo
                from: 0,
                to: <?php echo $media_min; ?>,
                color: 'rgba(0, 0, 0, 0.1)',
                label: {
                    rotation: 90,
                    verticalAlign: "top",
                    x: 2,
                    text: 'BAJO',
                    style: {
                        color: '#AAA',
                        fontSize:'12px'
                    }
                }
            }]
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
        },
        buttons: {
            contextButton: {
                menuItems: [{
                    text: '<a><i class="fa fa-facebook-square fa-2x" style="padding:5px"></i>Compartir en Facebook</a>',
                    onclick: function(event) {
                        if (event.target.href === '') {
                            getUrlShare('facebook', this, event.target);    
                        }
                    }
                },{
                    text: '<a><i class="fa fa-linkedin-square fa-2x" style="padding:5px"></i>Compartir en LinkedIn</a>'
                },{
                    separator: true
                },{
                    text: '<a href="#"><i class="glyphicon glyphicon-download-alt" style="padding:5px"></i>Descargar JPEG</a>',
                    onclick: function() {
                        this.exportChart({
                            type: 'image/jpeg'
                        });
                    }
                }]
            }
        }
    },
    tooltip: {
        headerFormat: '<strong style="font-size:16px">{point.key}</strong><br><br>',
        pointFormat: '<span style="color:{series.color}">{series.name}: </span><strong>{point.y}</strong>',
        valueSuffix: ' %',
        style: {
            "display": "block", 
            "width": "300px",
            "whiteSpace": "normal" 
        }
    },
    credits: {
        enabled: false
    }, 
    series: [
    <?php if( isset($profesion) && !empty($profesion) ){ ?>
    {
        name: '<?php echo $profesion; ?>',
        data: [ <?php imprimirSeriesEmp($filas_empleabilidad, count($meses)); ?> ],
        stack: '<?php echo $profesion ?>'
    },
    <?php  }  ?> 
    <?php if( isset($profesion_dos) && !empty($profesion_dos) ){ ?>
	{
        name: '<?php echo $profesion_dos; ?>',
        data: [ <?php imprimirSeriesEmp($filas_empleabilidad_dos, count($meses)); ?> ],
        stack: '<?php echo $profesion_dos ?>'
    },
    <?php  }  ?> 
	{
        name: 'Media de paro de todas las profesiones',
        type: 'spline',
        data: [ <?php echo join(", ", mediaEmpleabilidad($pdo, $meses, $anyos)); ?> ],
        stack: 'Media de paro',
        color: 'rgba(0, 0, 0, 0.2)',
        dashStyle: 'shortdot',
        marker: {
            fillColor: 'transparent',
            lineWidth: 1,
            lineColor: 'rgba(0, 0, 0, 0.2)',
        }
    }
    ]
};

$('#container_empleabilidad').highcharts(chartEmpleabilidad);


// Comprobar si los datos han salido iguales en la comparacion pero con nombres distintos
if (chartEmpleabilidad.series.length == 3 && $(chartEmpleabilidad.series[0].data).not(chartEmpleabilidad.series[1].data).length === 0 && chartEmpleabilidad.series[0].name !== chartEmpleabilidad.series[1].name) {
    var capa_iguales = '<div class="capa-aviso">';
    capa_iguales += '<div class="cerrar-aviso"><a href="#"><img class="icon" src="images/cross.svg"></img></a></div>';
    capa_iguales += '<div class="col-md-10 col-md-offset-1">';
    capa_iguales += '<h3>Atención! Aparecen gráficas similares porque se usan datos generales.</h3>';

    capa_iguales += '<p class="text-center">Ayúdanos a tener información específica sobre <strong>paro</strong>:<br><br>';
    <?php if( isset($profesion) && !empty($profesion) ){ ?>
        capa_iguales += '<strong><?php echo mb_strtoupper($profesion,"UTF-8"); ?></strong></p>';
        capa_iguales += '<a href="colabora.php?profesion=<?php echo $profesion; ?>" class="btn btn-aviso" style="border-color: #d5001e; color: #d5001e;">Colabora!</a><br>';
    <?php  }  ?> 
    <?php if( isset($profesion_dos) && !empty($profesion_dos) ){ ?>
        capa_iguales += '<strong><?php echo mb_strtoupper($profesion_dos,"UTF-8"); ?></strong></p>';
        capa_iguales += '<a href="colabora.php?profesion=<?php echo $profesion_dos; ?>" class="btn btn-aviso" style="border-color: #337ab7; color: #337ab7;">Colabora!</a>';
    <?php  }  ?> 

    capa_iguales += '</div>';
    capa_iguales += '</div>';

    $('#container_empleabilidad').append(capa_iguales);
}

// Comprobar si se necesitan botones producido
<?php if( $btn_colabora_e_1 || $btn_colabora_e_2 ) { ?>
    // agregar capa de aviso semitransparente (con opcion a quitar)
    var capa_aviso = '<div class="capa-aviso">';
    capa_aviso += '<div class="cerrar-aviso"><a href="#"><img class="icon" src="images/cross.svg"></img></a></div>';
    capa_aviso += '<div class="col-md-10 col-md-offset-1">';
    capa_aviso += '<h3>Aún no tenemos imformación suficiente!</h3>';

    <?php if( $btn_colabora_e_1 > 0 ) { ?>
        capa_aviso += '<p class="text-center">Ayúdanos a completar información sobre <strong>desempleo</strong> de la profesión<br>';
        capa_aviso += '<strong><?php echo mb_strtoupper($profesion,"UTF-8"); ?></strong></p>';
        capa_aviso += '<a href="colabora.php?profesion=<?php echo $profesion; ?>" class="btn btn-aviso" style="border-color: #d5001e; color: #d5001e;">Colabora!</a>';
    <?php } ?>

    <?php if( $btn_colabora_e_2 > 0 ) { ?>
        capa_aviso += '<p class="text-center">Ayúdanos a completar información sobre <strong>desempleo</strong> de la profesión<br>';
        capa_aviso += '<strong><?php echo mb_strtoupper($profesion_dos,"UTF-8"); ?></strong></p>';
        capa_aviso += '<a href="colabora.php?profesion=<?php echo $profesion_dos; ?>" class="btn btn-aviso" style="border-color: #337ab7; color: #337ab7;">Colabora!</a>';
    <?php } ?>

    capa_aviso += '</div>';
    capa_aviso += '</div>';

    $('#container_empleabilidad').append(capa_aviso);

<?php } ?>
