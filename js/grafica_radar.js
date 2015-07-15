$('#container3').highcharts({
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
        categories: ['Memoria', 'Creatividad', 'Comunicación', 'Forma física', 'Liógica'],
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
        name: '<?php echo mb_strtoupper( $registro["profesion"] ,"UTF-8" ); ?>',
        data: [<?php echo $registro['c_memoria']; ?>,<?php echo $registro['c_creatividad']; ?>,<?php echo $registro['c_comunicacion']; ?>,<?php echo $registro['c_forma_fisica']; ?>,<?php echo $registro['c_logica']; ?>],
        pointPlacement: 'on'
		 <?php	if(isset($profesion_dos) && $registro_dos["profesion"] != "" ){ ?>
    }, {
        name: '<?php echo mb_strtoupper( $registro_dos["profesion"] ,"UTF-8" ); ?>',
        data: [<?php echo $registro_dos['c_memoria']; ?>,<?php echo $registro_dos['c_creatividad']; ?>,<?php echo $registro_dos['c_comunicacion']; ?>,<?php echo $registro_dos['c_forma_fisica']; ?>,<?php echo $registro_dos['c_logica']; ?>],
        pointPlacement: 'on'
		<?php  } ?> 
    }]
});

