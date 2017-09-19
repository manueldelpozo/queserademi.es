<?php

function consultarFormacionesAnteriores($id_formacion, $campos_formacion, $pdo, $arbol_formaciones) {
    try {
        $consulta_formaciones = 'SELECT id_formacion_ant FROM formaciones_formacion_ant WHERE id_formacion = '. $id_formacion . ';';
        $rs = $pdo->prepare($consulta_formaciones);
        $rs->execute();
        $cod_formaciones_ant = $rs->fetchAll();
        $info_formaciones = array();
    
        foreach ($cod_formaciones_ant as $cod_formacion_ant) {
            $consulta_formacion_info = 'SELECT ' . join(', ', $campos_formacion) . ' FROM formaciones WHERE cod LIKE ' . $cod_formacion_ant[0] . ';';

            $rs = $pdo->prepare($consulta_formacion_info);
            $rs->execute();
            $info_formacion = $rs->fetchAll();
            $info_formaciones[] = $info_formacion;
        }
    } catch(PDOException $Exception) {
        echo "<p>Error en la consulta.<p>\n" . $Exception;
        exit;
    } 

    $formacion_ant = current($info_formaciones)[0];

    if (!empty($formacion_ant)) {
        $arbol_formaciones[] = $formacion_ant;
        return consultarFormacionesAnteriores($formacion_ant['id'], $campos_formacion, $pdo, $arbol_formaciones);
    } else {
        return $arbol_formaciones; 
    }
}

function getTotalAnyosEstudios($formaciones, $tipoDuracion) {
    $total = 0;
    foreach ($formaciones as $formacion) {
        $total += $formacion[$tipoDuracion];
    }

    return $total;
}

$btn_colabora_f_1 = $btn_colabora_f_2 = false;
$arbol_formaciones = array(); 
$arbol_formaciones_dos = array();

if (isset($profesion) && !empty($filas_formaciones)) {
    $ultima_formaciones = end($filas_formaciones);

    $arbol_formaciones[] = $ultima_formaciones;
    $arbol_formaciones = consultarFormacionesAnteriores($ultima_formaciones['id'], $tablas['formaciones'], $pdo, $arbol_formaciones);
}

if (isset($profesion_dos) && !empty($filas_formaciones_dos)) {
    $ultima_formaciones_dos = end($filas_formaciones_dos);

    $arbol_formaciones_dos[] = $ultima_formaciones_dos;
    $arbol_formaciones_dos = consultarFormacionesAnteriores($ultima_formaciones_dos['id'], $tablas['formaciones'], $pdo, $arbol_formaciones_dos);
}

?>

var chartFormacion = {
    chart: {
        type: 'bar',
        backgroundColor:'rgba(255, 255, 255, 0)',
        spacingBottom: 40,
        spacingTop: 20,
        spacingLeft: 40,
        spacingRight: 20,
        width: null,
        height: 380,
        events: {
            load: function () {
                <?php if( isset($profesion) && !empty($filas_formaciones) ) { ?>
                    var profesion = this.series[0].points[0];
                    this.tooltip.refresh(profesion);
                <?php } ?>            
            }
        }
    },
    title: {
        text: 'FORMACIÓN',
        align: "center",
        style: { 
            'color': '#555',
            'fontSize': '14px',
            'fontWeight': 'bold'
        }
    },
    xAxis: {
        categories: [
        '<?php echo "<div style=\'text-overflow: ellipsis;width: 300px;overflow: hidden;\'>" . mb_strtoupper($profesion, "UTF-8") . "<br>Terminarías a los <strong>" . getTotalAnyosEstudios($arbol_formaciones, "duracion_academica") . " años</strong></div>"; ?>'
        <?php if( isset($profesion_dos) && !empty($filas_formaciones_dos) ) { ?>
        , '<?php echo "<div style=\'text-overflow: ellipsis;width: 300px;overflow: hidden;\'>" . mb_strtoupper($profesion_dos, "UTF-8") . "<br>Terminarías a los <strong>" . getTotalAnyosEstudios($arbol_formaciones_dos, "duracion_academica") . " años</strong></div>"; ?>'
        <?php } ?>
        ],
        labels: {
            x: 8,
            y: 30,
            useHTML : true,
            style: { 
                fontSize: '11px',
                maxWidth: '0px',
                'text-overflow': 'ellipsis',
                'white-space': 'normal',
                'overflow': 'visible !important'
            }
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Duración de estudios (años)'
        }
    },
    legend: {
        enabled: false
    },
    tooltip: {
        headerFormat: '',
        pointFormat: '<span>{series.name}</span><br><strong>Duración de estudios (años): {point.y}</strong>',
        style: {
            display: 'block', 
            width: '310px',
            whiteSpace: 'normal',
            fontSize: 10
        },
        positioner: function (labelWidth, labelHeight, point) {
            var tooltipX = 40;
            var tooltipY = point.plotY - 15;
            return {
                x: tooltipX,
                y: tooltipY
            };
        }
    },
    credits: {
        enabled: false
    },
    plotOptions: {
        series: {
            cursor: 'pointer',
            stacking: 'normal',
            stickyTracking: false,
            pointWidth: 30
        }
    },
    exporting: {
        buttons: {
            /*anotherButton: {
                text: 'Dónde estudiar?',
                onclick: function () {
                    alert('Dónde estudiar? En desarrollo... Disculpe las molestias');
                }
            },*/
            contextButton: {
                menuItems: [{
                    text: '<a><i class="fa fa-facebook-square fa-2x" style="padding:5px"></i>Compartir en Facebook</a>',
                    onclick: function(event) {
                        if (event.target.href === '') {
                            getUrlShare('facebook', this, event.target);    
                        }
                    }
                },{
                    text: '<a><i class="fa fa-linkedin-square fa-2x" style="padding:5px"></i>Compartir en LinkedIn</a>',
                    onclick: function(event) {
                        if (event.target.href === '') {
                        getUrlShare('linkedin', this, event.target);    
                        }
                    }
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
        },
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
    series: [
        <?php
            $series = array();
            if( isset($profesion) && !empty($filas_formaciones) ) {
                $i = 0;
                foreach ($arbol_formaciones as $formac) {
                    if ($formac['duracion_academica']) {
                       $serie = '{';
                        $serie .= "name: '" . $formac['f_nombre_ppal'] . "', ";
                        $serie .= 'data: [' . $formac['duracion_academica'] . ', 0], ';
                        $serie .= 'color: "#d5001e"';
                        $serie .= '}';
                        $series[] = $serie; 
                        $i++;
                    }
                }
            } else { $btn_colabora_f_1 = true; }
            if( isset($profesion_dos) && !empty($filas_formaciones_dos) ) {
                foreach ($arbol_formaciones_dos as $formac_dos) {
                    if ($formac_dos['duracion_academica']) {
                       $serie = '{';
                        $serie .= "name: '" . $formac_dos['f_nombre_ppal'] . "', ";
                        $serie .= 'data: [0, ' . $formac_dos['duracion_academica'] . '], ';
                        $serie .= 'color: "#337ab7"';
                        $serie .= '}';
                        $series[] = $serie; 
                    }
                }
            } else { $btn_colabora_f_2 = true; }
            echo join($series, ',');
        ?>
    ]
};

$('#container_formacion').highcharts(chartFormacion);

// Comprobar si se necesitan botones producido
<?php if($btn_colabora_f_1 || $btn_colabora_f_2) { ?>

    var capa_aviso = '<div class="capa-aviso">';
    capa_aviso += '<div class="cerrar-aviso"><a href="#"><img class="icon" src="images/cross.svg"></img></a></div>';
    capa_aviso += '<div class="col-md-10 col-md-offset-1">';
    capa_aviso += '<h3>Aún no tenemos imformación suficiente!</h3>';

    <?php if($btn_colabora_f_1) { ?>
        capa_aviso += '<p class="text-center">Ayúdanos a completar información sobre <strong>formación</strong> de la profesión<br>';
        capa_aviso += '<strong><?php echo mb_strtoupper($profesion,"UTF-8"); ?></strong></p>';
        capa_aviso += '<a href="colabora.php?profesion=<?php echo $profesion; ?>" class="btn btn-aviso" style="border-color: #d5001e; color: #d5001e;">Colabora!</a>';
    <?php } ?>

    <?php if($btn_colabora_f_2) { ?>
        capa_aviso += '<p class="text-center">Ayúdanos a completar información sobre <strong>formación</strong> de la profesión<br>';
        capa_aviso += '<strong><?php echo mb_strtoupper($profesion_dos,"UTF-8"); ?></strong></p>';
        capa_aviso += '<a href="colabora.php?profesion=<?php echo $profesion_dos; ?>" class="btn btn-aviso" style="border-color: #337ab7; color: #337ab7;">Colabora!</a>';
    <?php } ?>

    capa_aviso += '</div>';
    capa_aviso += '</div>';

    // debe aparecer despues de 1 segundo
    $('#container_formacion').append(capa_aviso);
<?php } ?>
