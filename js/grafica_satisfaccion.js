
$('#container_satisfaccion').highcharts({
    chart: {
        type: 'scatter',
        zoomType: 'xy',
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
        text: 'GRADO DE SATISFACCIÓN'
    },
    xAxis: {
        title: {
            //enabled: true,
            text: 'EXPERIENCIA ' + '(años)'.toUpperCase()
        },
        //startOnTick: true,
        //endOnTick: true,
        //showLastLabel: true
    },
    yAxis: {
        title: {
            text: 'SATISFACCIÓN'
        }
    },
    /*legend: {
        layout: 'vertical',
        align: 'left',
        verticalAlign: 'top',
        x: 100,
        y: 70,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF',
        borderWidth: 1
    },*/
    legend: { enable: false },
    credits: {
        enabled: false
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
        }
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
                pointFormat: '{point.x} años de experiencia'
            }
        }
    },
    series: [{
        <?php $btn_colabora_sat_1=0;$btn_colabora_sat_2=0; ?>
        name: '<?php echo mb_strtoupper($profesion,"UTF-8" ); ?>',
        //color: 'rgba(223, 83, 83, .5)',
        data: [
        <?php foreach ($filas_satisfaccion as $fila_sat) { ?>
            [ 
        <?php if( is_null($fila_sat['experiencia']) || $fila_sat['experiencia'] == 0 ) {echo 0;$btn_colabora_sat_1+=1;} else {echo $fila_sat['experiencia'];} ?>,
        <?php if( is_null($fila_sat['grado_satisfaccion']) || $fila_sat['grado_satisfaccion'] == 0 ) {echo 0;$btn_colabora_sat_1+=1;} else {echo $fila_sat['grado_satisfaccion'];} ?>
            ],
        <?php } ?>
        ],
        stack: '<?php echo $profesion; ?>'
        <?php if( isset($profesion_dos) && !empty($profesion_dos) ){ ?>

    }, {
        name: '<?php echo mb_strtoupper($profesion_dos,"UTF-8" ); ?>',
        //color: 'rgba(119, 152, 191, .5)',
        data: [
        <?php foreach ($filas_satisfaccion_dos as $fila_sat_dos) { ?>
            [ 
        <?php if( is_null($fila_sat_dos['experiencia']) || $fila_sat_dos['experiencia'] == 0 ) {echo 0;$btn_colabora_sat_1+=1;} else {echo $fila_sat_dos['experiencia'];} ?>,
        <?php if( is_null($fila_sat_dos['grado_satisfaccion']) || $fila_sat_dos['grado_satisfaccion'] == 0 ) {echo 0;$btn_colabora_sat_1+=1;} else {echo $fila_sat_dos['grado_satisfaccion'];} ?>
            ],
        <?php } ?>
        ],
        stack: '<?php echo $profesion_dos; ?>'
        <?php } ?>
    }]
});



// Comprobar si se necesitan botones producido
<?php if( $btn_colabora_sat_1 || $btn_colabora_sat_2 ) { ?>
    // agregar capa de aviso semitransparente (con opcion a quitar?)
    var capa_aviso = '<div class="capa-aviso">';
    capa_aviso += '<div class="cerrar-aviso"><a href="#"><img class="icon" src="images/cross.svg"></img></a></div>';
    capa_aviso += '<div class="col-md-10 col-md-offset-1">';
    capa_aviso += '<h3>Aún no tenemos imformación suficiente!</h3>';

    <?php if( $btn_colabora_sat_1 > 0 ) { ?>
        capa_aviso += '<p class="text-center">Ayúdanos a completar información sobre <strong>satisfaccion</strong> de la profesión<br>';
        capa_aviso += '<strong><?php echo mb_strtoupper($profesion,"UTF-8"); ?></strong></p>';
        capa_aviso += '<a href="colabora.php?profesion=<?php echo $profesion; ?>" class="btn btn-aviso" style="border-color: #d5001e; color: #d5001e;">Colabora!</a>';
    <?php } ?>

    <?php if( $btn_colabora_sat_2 > 0 ) { ?>
        capa_aviso += '<p class="text-center">Ayúdanos a completar información sobre <strong>satisfaccion</strong> de la profesión<br>';
        capa_aviso += '<strong><?php echo mb_strtoupper($profesion_dos,"UTF-8"); ?></strong></p>';
        capa_aviso += '<a href="colabora.php?profesion=<?php echo $profesion_dos; ?>" class="btn btn-aviso" style="border-color: #337ab7; color: #337ab7;">Colabora!</a>';
    <?php } ?>

    capa_aviso += '</div>';
    capa_aviso += '</div>';

    // debe aparecer despues de 1 segundo
    $('#container_satisfaccion').append(capa_aviso);


<?php } ?>