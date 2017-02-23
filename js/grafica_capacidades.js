<?php 
$descripciones = array(
    'Análisis'                  => 'Razonamiento lógico, toma de decisiones, organización, gestión, etc.',
    'Comunicación'              => 'Comunicación, hablar en público, escucha activa.',
    'Destreza y físico'         => 'Destreza técnica, apariciencia física, etc.',
    'Cooperación'               => 'Empatía, sensibilidad, colaboración, trabajo en equipo, escucha.',
    'Logro de objetivos'        => 'Orientado a objetivos, resultados, etc.',
    'Persuasión'                => 'Influencia, negociación, habilidades comerciales, etc.'
);
$iconos = array(
    'Análisis'                  => 'line-chart',
    'Comunicación'              => 'comments-o',
    'Destreza y físico'         => 'wrench',
    'Cooperación'               => 'users',
    'Logro de objetivos'        => 'trophy',
    'Persuasión'                => 'briefcase'
);

$btn_colabora_c_1 = $btn_colabora_c_2 = 0;

function imprimirSeriesCap($filas, $tablas) {
    foreach ($tablas['capacidades'] as $campo) {
        echo (is_null($filas[$campo]) || $filas[$campo] == 0) ? "2," : round($filas[$campo]) . ",";
    }
}

// busqueda de nulos
if( isset($profesion) && !empty($profesion) ){
    foreach ($filas_capacidades as $fila_capacidad) { 
        if( is_null($fila_capacidad) || $fila_capacidad == 0 )
            $btn_colabora_c_1++;
    }
}
if( isset($profesion_dos) && !empty($profesion_dos) ){
    foreach ($filas_capacidades_dos as $fila_capacidad_dos) { 
        if( is_null($fila_capacidad_dos) || $fila_capacidad_dos == 0 )
            $btn_colabora_c_2++;
    }
}
?>

$('#container_capacidades').highcharts({
    chart: {
        polar: true,
        type: 'line',
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
        text: 'CUALIDADES PROFESIONALES',
        align: "center",
        style: { 
            'color': '#555',
            'fontSize': '14px',
            'fontWeight': 'bold'
        }
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
    pane: {
        size: '80%'
    },
    xAxis: {
        categories: [
        <?php 
        foreach($descripciones as $nombre => $descripcion) { 
            echo '"'.$nombre.'",'; // puede ser un punto negro!!
        } 
        ?>
        ],
        tickmarkPlacement: 'on',
        lineWidth: 0,
        gridLineColor: '#999999',
        labels: {
            style: {
                fontSize: '12px',
                zIndex: '-1'
            },
            useHTML: true,
            formatter: function () {
                return [
                <?php 
                $numItems = count($iconos);
                $i = 0;
                foreach($iconos as $nombre => $icono) { 
                    echo "'";
                    echo '<i class="fa fa-'.$icono.' fa-lg"></i>';
                    echo "'";
                    if(++$i !== $numItems)
                        echo '+';
                } 
                ?>
                ];
            }
        }
    },
    yAxis: {
        gridLineInterpolation: 'polygon',
        lineWidth: 0,
        min: 0,
        minorTickInterval: 'auto',
        gridLineColor: '#999999',
        labels: {
            style: {
                fontSize: '0px'
            }
        }
    },
    exporting: {
        buttons: {
           anotherButton: {
                text: '??',
                y: 28,
                x: 0,
                width: 24,
                onclick: function () {
                    // agregar capa de glosario semitransparente (con opcion a quitar)
                    var capa_glosario = '<div class="capa-glosario">';
                    capa_glosario += '<div class="cerrar-glosario"><img class="icon" src="images/cross.svg"></img></div>';
                    capa_glosario += '<div class="col-md-10 col-md-offset-1">';
                   
                    capa_glosario += '<h3>No te preocupes, te lo aclaramos aquí!</h3><br>';
                    capa_glosario += '<dl class="dl-horizontal">';
                    <?php foreach ($descripciones as $nombre => $descripcion) { ?>
                        capa_glosario += '<dt>';
                        capa_glosario += '<?php echo $nombre; ?>:';
                        capa_glosario += '&nbsp;<i class="fa fa-<?php echo $iconos[$nombre]; ?> fa-lg"></i><br>';
                        capa_glosario += '</dt><dd>&gt;&gt;<?php echo $descripcion; ?></dd>';
                    <?php } ?>
                    capa_glosario += '</dl>';

                    capa_glosario += '</div>';
                    capa_glosario += '</div>';

                    $('#container_capacidades').append(capa_glosario);

                    // cerrar glosario
                    $('.cerrar-glosario').click( function() {
                        $(this).parent().remove();
                    });
                }
            },
            contextButton: {
                menuItems: [{
                },{
                },{
                    separator: true
                },{
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
    tooltip: {
        shared: true,
        headerFormat: '<strong style="font-size:17px">{point.key}</strong><br>',
        formatter: function() {
            var descripciones = {
                Analisis:                   "<?php echo $descripciones['Análisis']; ?>",
                Comunicacion:               "<?php echo $descripciones['Comunicación']; ?>",
                Destreza_y_fisico:          "<?php echo $descripciones['Destreza y físico']; ?>",
                Cooperacion:                "<?php echo $descripciones['Cooperación']; ?>",
                Logro_de_objetivos:         "<?php echo $descripciones['Logro de objetivos']; ?>",
                Persuasion:                 "<?php echo $descripciones['Persuasión']; ?>"
            };

            var points = '<span style="color:'+this.points[0].series.color+'">'+this.points[0].series.name+': </span>(<strong>'+this.points[0].y+'</strong>/5)<br/>';
            if (this.points[1]) {
                points += '<span style="color:'+this.points[1].series.color+'">'+this.points[1].series.name+': </span>(<strong>'+this.points[1].y+'</strong>/5)<br/>';
            }
            
            return '<strong style="font-size:17px;color:rgb(0,0,0);">'+ this.x +'</strong><br/>'+'<span>'+ descripciones[this.x.replace(/ /g,"_").latinize()] +'</span><br/>'+ points;   
        },
        style: {
            display: 'block', 
            width: '300px',
            whiteSpace: 'normal' 
        }      
    },
    credits: {
         enabled: false
    },

    series: [
    <?php if( isset($profesion) && !empty($profesion) ) { ?>
    {  
        name: '<?php echo $profesion; ?>',
        data: [ <?php imprimirSeriesCap($filas_capacidades[0], $tablas); ?> ],
        stack: '<?php echo $profesion ?>'    
    },
    <?php  }  ?>  
    <?php if( isset($profesion_dos) && !empty($profesion_dos) ) { ?>
    {
        name: '<?php echo $profesion_dos; ?>',
        data: [ <?php imprimirSeriesCap($filas_capacidades_dos[0], $tablas); ?> ],
        stack: '<?php echo $profesion_dos ?>' 
    }
    <?php  }  ?> 
    ]
});

// Comprobar si se necesitan botones producido
<?php if( $btn_colabora_c_1 || $btn_colabora_c_2 ) { ?>
    // agregar capa de aviso semitransparente (con opcion a quitar?)
    var capa_aviso = '<div class="capa-aviso">';
    capa_aviso += '<div class="cerrar-aviso"><a href="#"><img class="icon" src="images/cross.svg"></img></a></div>';
    capa_aviso += '<div class="col-md-10 col-md-offset-1">';
    capa_aviso += '<h3>Aún no tenemos imformación suficiente!</h3>';

    <?php if( $btn_colabora_c_1 > 0 ) { ?>
        capa_aviso += '<p class="text-center">Ayúdanos a completar información sobre <strong>cualidades profesionales</strong> de la profesión<br>';
        capa_aviso += '<strong><?php echo mb_strtoupper($profesion,"UTF-8"); ?></strong></p>';
        capa_aviso += '<a href="colabora.php?profesion=<?php echo $profesion; ?>" class="btn btn-aviso" style="border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);">Colabora!</a>';
    <?php } ?>

    <?php if( $btn_colabora_c_2 > 0 ) { ?>
        capa_aviso += '<p class="text-center">Ayúdanos a completar información sobre <strong>cualidades profesionales</strong> de la profesión<br>';
        capa_aviso += '<strong><?php echo mb_strtoupper($profesion_dos,"UTF-8"); ?></strong></p>';
        capa_aviso += '<a href="colabora.php?profesion=<?php echo $profesion_dos; ?>" class="btn btn-aviso" style="border-color: #337ab7; color: #337ab7;">Colabora!</a>';
    <?php } ?>

    capa_aviso += '</div>';
    capa_aviso += '</div>';

    // debe aparecer despues de 1 segundo
    $('#container_capacidades').append(capa_aviso);


<?php } ?>