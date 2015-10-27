/*
    var ranges = [
            [1246406400000, 14.3, 27.7],
            [1246492800000, 14.5, 27.8],
            [1246579200000, 15.5, 29.6],
            [1246665600000, 16.7, 30.7],
            [1246752000000, 16.5, 25.0],
            [1246838400000, 17.8, 25.7],
            [1246924800000, 13.5, 24.8],
            [1247011200000, 10.5, 21.4],
            [1247097600000, 9.2, 23.8],
            [1247184000000, 11.6, 21.8],
            [1247270400000, 10.7, 23.7],
            [1247356800000, 11.0, 23.3],
            [1247443200000, 11.6, 23.7],
            [1247529600000, 11.8, 20.7],
            [1247616000000, 12.6, 22.4],
            [1247702400000, 13.6, 19.6],
            [1247788800000, 11.4, 22.6],
            [1247875200000, 13.2, 25.0],
            [1247961600000, 14.2, 21.6],
            [1248048000000, 13.1, 17.1],
            [1248134400000, 12.2, 15.5],
            [1248220800000, 12.0, 20.8],
            [1248307200000, 12.0, 17.1],
            [1248393600000, 12.7, 18.3],
            [1248480000000, 12.4, 19.4],
            [1248566400000, 12.6, 19.9],
            [1248652800000, 11.9, 20.2],
            [1248739200000, 11.0, 19.3],
            [1248825600000, 10.8, 17.8],
            [1248912000000, 11.8, 18.5],
            [1248998400000, 10.8, 16.1]
        ],
        averages = [
            [1246406400000, 21.5],
            [1246492800000, 22.1],
            [1246579200000, 23],
            [1246665600000, 23.8],
            [1246752000000, 21.4],
            [1246838400000, 21.3],
            [1246924800000, 18.3],
            [1247011200000, 15.4],
            [1247097600000, 16.4],
            [1247184000000, 17.7],
            [1247270400000, 17.5],
            [1247356800000, 17.6],
            [1247443200000, 17.7],
            [1247529600000, 16.8],
            [1247616000000, 17.7],
            [1247702400000, 16.3],
            [1247788800000, 17.8],
            [1247875200000, 18.1],
            [1247961600000, 17.2],
            [1248048000000, 14.4],
            [1248134400000, 13.7],
            [1248220800000, 15.7],
            [1248307200000, 14.6],
            [1248393600000, 15.3],
            [1248480000000, 15.3],
            [1248566400000, 15.8],
            [1248652800000, 15.2],
            [1248739200000, 14.8],
            [1248825600000, 14.4],
            [1248912000000, 15],
            [1248998400000, 13.6]
        ];

*/
$('#container_salarios').highcharts({

    chart: {
        type: 'arearange',
        backgroundColor:'rgba(255, 255, 255, 0.5)',
        spacingBottom: 20,
        spacingTop: 20,
        spacingLeft: 20,
        spacingRight: 20,
        width: null,
        height: 380
    },

    title: {
        text: 'SALARIO NETO (euros/mes)',
        align: "center"
    },

    legend: { 
        enable: false 
    },

    xAxis: {
        categories: ['JUNIOR','INTERMEDIO','SENIOR']
    },

    yAxis: {
        title: {
            text: 'salario'
        }
    },

    tooltip: {
        crosshairs: true,
        shared: true,
        valueSuffix: ' %'
    },

    credits: {
        enabled: false
    },

    plotOptions: {
        arearange: {
            fillOpacity: 0.5
        }
    },

    series: [{
        <?php $btn_colabora_p_1=0;$btn_colabora_p_2=0; ?>
        name: '<?php echo mb_strtoupper($filas_salario["nombre_ppal"],"UTF-8" ); ?>',
        data: [
            [
            0,
            <?php if( is_null($filas_salario['s_junior_min']) || $filas_salario['s_junior_min'] == 0 ) {echo 0;$btn_colabora_p_1+=1;} else {echo $filas_salario['s_junior_min'];} ?>,
            <?php if( is_null($filas_salario['s_junior_max']) || $filas_salario['s_junior_max'] == 0 ) {echo 0;$btn_colabora_p_1+=1;} else {echo $filas_salario['s_junior_max'];} ?>
            ],
            [
            2,
            <?php if( is_null($filas_salario['s_junior_min']) || $filas_salario['s_junior_min'] == 0 ) {echo 0;$btn_colabora_p_1+=2;} else {echo $filas_salario['s_junior_min'];} ?>,
            <?php if( is_null($filas_salario['s_junior_max']) || $filas_salario['s_junior_max'] == 0 ) {echo 0;$btn_colabora_p_1+=2;} else {echo $filas_salario['s_junior_max'];} ?>
            ],
            [
            5,
            <?php if( is_null($filas_salario['s_intermedio_min']) || $filas_salario['s_intermedio_min'] == 0 ) {echo 0;$btn_colabora_p_1+=4;} else {echo $filas_salario['s_intermedio_min'];} ?>,
            <?php if( is_null($filas_salario['s_intermedio_max']) || $filas_salario['s_intermedio_max'] == 0 ) {echo 0;$btn_colabora_p_1+=4;} else {echo $filas_salario['s_intermedio_max'];} ?>
            ], 
            [
            0,
            <?php if( is_null($filas_salario['s_senior_min']) || $filas_salario['s_senior_min'] == 0 ) {echo 0;$btn_colabora_p_1+=8;} else {echo $filas_salario['s_senior_min'];} ?>,
            <?php if( is_null($filas_salario['s_senior_max']) || $filas_salario['s_senior_max'] == 0 ) {echo 0;$btn_colabora_p_1+=8;} else {echo $filas_salario['s_senior_max'];} ?>
            ]
        ],
        stack: '<?php echo $filas_salario["nombre_ppal"] ?>',
        type: 'arearange',
        zIndex: 1,
        fillOpacity: 0.3,
        marker: {
            fillColor: 'white',
            lineWidth: 2,
            lineColor: Highcharts.getOptions().colors[0]
        }
        <?php if( isset($profesion_dos) && !empty($filas_salario["nombre_ppal"]) ){ ?>
    }, {
        name: '<?php echo mb_strtoupper($filas_salario["nombre_ppal"],"UTF-8" ); ?>',
        data: [
            [
            0,
            <?php if( is_null($filas_salario_dos['s_junior_min']) || $filas_salario_dos['s_junior_min'] == 0 ) {echo 0;$btn_colabora_p_2+=1;} else {echo $filas_salario_dos['s_junior_min'];} ?>,
            <?php if( is_null($filas_salario_dos['s_junior_max']) || $filas_salario_dos['s_junior_max'] == 0 ) {echo 0;$btn_colabora_p_2+=1;} else {echo $filas_salario_dos['s_junior_max'];} ?>
            ],
            [
            2,
            <?php if( is_null($filas_salario_dos['s_junior_min']) || $filas_salario_dos['s_junior_min'] == 0 ) {echo 0;$btn_colabora_p_2+=2;} else {echo $filas_salario_dos['s_junior_min'];} ?>,
            <?php if( is_null($filas_salario_dos['s_junior_max']) || $filas_salario_dos['s_junior_max'] == 0 ) {echo 0;$btn_colabora_p_2+=2;} else {echo $filas_salario_dos['s_junior_max'];} ?>
            ],
            [
            5,
            <?php if( is_null($filas_salario_dos['s_intermedio_min']) || $filas_salario_dos['s_intermedio_min'] == 0 ) {echo 0;$btn_colabora_p_2+=4;} else {echo $filas_salario_dos['s_intermedio_min'];} ?>,
            <?php if( is_null($filas_salario_dos['s_intermedio_max']) || $filas_salario_dos['s_intermedio_max'] == 0 ) {echo 0;$btn_colabora_p_2+=4;} else {echo $filas_salario_dos['s_intermedio_max'];} ?>
            ], 
            [
            10,
            <?php if( is_null($filas_salario_dos['s_senior_min']) || $filas_salario_dos['s_senior_min'] == 0 ) {echo 0;$btn_colabora_p_2+=8;} else {echo $filas_salario_dos['s_senior_min'];} ?>,
            <?php if( is_null($filas_salario_dos['s_senior_max']) || $filas_salario_dos['s_senior_max'] == 0 ) {echo 0;$btn_colabora_p_2+=8;} else {echo $filas_salario_dos['s_senior_max'];} ?>
            ]
        ],
        stack: '<?php echo $filas_salario_dos["nombre_ppal"] ?>', 
        <?php  }  ?> 
        type: 'arearange',
        lineWidth: 0,
        //linkedTo: ':previous',
        //color: Highcharts.getOptions().colors[0],
        fillOpacity: 0.3,
        zIndex: 0
    }]
    /*
    series: [{
        name: 'Temperature',
        data: averages,
        zIndex: 1,
        marker: {
            fillColor: 'white',
            lineWidth: 2,
            lineColor: Highcharts.getOptions().colors[0]
        }
    }, {
        name: 'Range',
        data: ranges,
        type: 'arearange',
        lineWidth: 0,
        linkedTo: ':previous',
        color: Highcharts.getOptions().colors[0],
        fillOpacity: 0.3,
        zIndex: 0
    }]
    */
});

// Comprobar si se necesitan botones producido
<?php if( $btn_colabora_p_1 || $btn_colabora_p_2 ) { ?>
    // agregar capa de aviso semitransparente (con opcion a quitar?)
    var capa_aviso = "<div class='capa-aviso'>";
    capa_aviso += '<div class="cerrar-aviso"><a href="#"><img class="icon" src="images/cross.svg"></img></a></div>';
    capa_aviso += "<div class='col-md-10 col-md-offset-1'>";
    capa_aviso += "<h3>Aún no tenemos imformación suficiente!</h3>";

    <?php if( $btn_colabora_p_1 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Ayúdanos a completar información sobre <strong>salario</strong> de la profesión<br>";
        capa_aviso += "<strong><?php echo mb_strtoupper($filas_salario['nombre_ppal'],'UTF-8' ); ?></strong></p>";
        capa_aviso += "<a href='colabora.php?profesion=<?php echo $filas_salario['nombre_ppal']; ?>' class='btn btn-aviso' style='border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);'>Colabora!</a>";
    <?php } ?>

    <?php if( $btn_colabora_p_2 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Ayúdanos a completar información sobre <strong>salario</strong> de la profesión<br>";
        capa_aviso += "<strong><?php echo mb_strtoupper($filas_salario_dos['nombre_ppal'],'UTF-8' ); ?></strong></p>";
        capa_aviso += "<a href='colabora.php?profesion=<?php echo $filas_salario_dos['nombre_ppal']; ?>' class='btn btn-aviso' style='border-color: #337ab7; color: #337ab7;'>Colabora!</a>";
    <?php } ?>

    capa_aviso += "</div>";
    capa_aviso += "</div>";

    // debe aparecer despues de 1 segundo
    $('#container_salarios').append(capa_aviso);
<?php } ?>