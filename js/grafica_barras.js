$('#container2').highcharts({
    chart: {
        type: 'column',
        marginTop: 80,
        marginRight: 40,
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
        text: 'SALARIO MENSUAL (€) ',
        align: "center"
    },
    legend: { enable: false },
    xAxis: {
        categories: ['2010','2015','2020']
    },
    yAxis: {
        allowDecimals: true,
        min: 0,
        title: {
            text: 'Aprox. salario neto en €'
        }
    },
    tooltip: {
        headerFormat: '<b>{point.key}</b><br>',
        pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y} / {point.stackTotal}'
    },
    credits: {
        enabled: false
    }, 
    series: [{
        name: '<?php echo mb_strtoupper($registro["profesion"],"UTF-8" ); ?>',
        data: [<?php echo $registro['s_past'] ?>,<?php echo $registro['s_present'] ?>, <?php echo $registro['s_future'] ?>],
        stack: '<?php echo $registro["profesion"] ?>'
    <?php	if(isset($profesion_dos) && $registro_dos["profesion"] != "" ){ ?>
	}, {
        name: '<?php echo mb_strtoupper($registro_dos["profesion"],"UTF-8" ); ?>',
        data: [<?php echo $registro_dos['s_past'] ?>,<?php echo $registro_dos['s_present'] ?>, <?php echo $registro_dos['s_future'] ?>],
        stack: '<?php echo $registro_dos["profesion"] ?>'
    	<?php  } ?> 
	}]
});


