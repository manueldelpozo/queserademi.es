$('#container').highcharts({
    chart: {
        type: 'areaspline',
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
        text: 'DESEMPLEO (%)',
        align: "center"
    },
    legend: { enable: false },
    xAxis: {
        categories: ['2010','2015','2020']
    },
    yAxis: {
        title: {
            text: '% Desempleo'
        }
    },
    tooltip: {
        shared: true,
        valueSuffix: ' %'
    },
    credits: {
        enabled: false
    },
    plotOptions: {
        areaspline: {
            fillOpacity: 0.5
        }
    },
    series: [{
        name: '<?php echo mb_strtoupper($registro["profesion"],"UTF-8" ); ?>',
        data: [<?php echo $registro['p_past'] ?>,<?php echo $registro['p_present'] ?>, <?php echo $registro['p_future'] ?>]
    }
	<?php	if(isset($profesion_dos) && $registro_dos["profesion"] != "" ){ ?>
    ,{
        name: '<?php echo mb_strtoupper($registro_dos["profesion"],"UTF-8" ); ?>',
        data: [<?php echo $registro_dos['p_past'] ?>,<?php echo $registro_dos['p_present'] ?>,<?php echo $registro_dos['p_future'] ?>]
	}	
	<?php  } ?> 
	]
});

