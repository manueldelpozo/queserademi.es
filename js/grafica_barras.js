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
        <?php $btn_colabora_s_1=0;$btn_colabora_s_2=0;?>
        name: '<?php echo mb_strtoupper($registro["profesion"],"UTF-8" ); ?>',
        data: [
        <?php if( !is_null($registro['s_past']) ) {echo $registro['s_past'];} else {echo 0;$btn_colabora_s_1+=1;} ?>,
        <?php echo $registro['s_present'] ?>, 
        <?php if( !is_null($registro['s_future']) ) {echo $registro['s_future'];} else {echo 0;$btn_colabora_s_1+=2;} ?>
        ],
        stack: '<?php echo $registro["profesion"] ?>'
        <?php	if( isset($profesion_dos) && !empty($registro_dos["profesion"]) ){ ?>
	}, {
        name: '<?php echo mb_strtoupper($registro_dos["profesion"],"UTF-8" ); ?>',
        data: [
        <?php if( !is_null($registro_dos['s_past']) ) {echo $registro_dos['s_past'];} else {echo 0;$btn_colabora_s_2+=1;} ?>,
        <?php echo $registro_dos['s_present'] ?>, 
        <?php if( !is_null($registro_dos['s_future']) ) {echo $registro_dos['s_future'];} else {echo 0;$btn_colabora_s_2+=2;} ?>
        ],
        stack: '<?php echo $registro_dos["profesion"] ?>'
    	<?php  } else {
            // mostrar boton de colaborar
        } ?> 
	}]
});

// Comprobar si se necesitan botones producido
<?php if( $btn_colabora_s_1 || $btn_colabora_s_2 ) { ?>
    // agregar capa de aviso semitransparente (con opcion a quitar?)
    var capa_aviso = "<div class='capa-aviso'>";
    capa_aviso += "<div class='col-md-6 col-md-offset-3'>";

    <?php if( $btn_colabora_s_1 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Falta informacion sobre el salario de la profesion<br>";
        capa_aviso += "<strong>"<?php echo mb_strtoupper($registro["profesion"],"UTF-8" ); ?> +"</strong></p>";
        capa_aviso += "<a href='colabora.php?profesion="+ <?php echo mb_strtoupper($registro["profesion"],"UTF-8" ); ?> +"' class='btn-aviso' style='background-color: rgba(204, 0, 0, 0.6);'>Colabora!</a>";
    <?php } ?>

    <?php if( $btn_colabora_s_2 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Falta informacion sobre el salario de la profesion<br>";
        capa_aviso += "<strong>"<?php echo mb_strtoupper($registro_dos["profesion"],"UTF-8" ); ?> +"</strong></p>";
        capa_aviso += "<a href='colabora.php?profesion="+ <?php echo mb_strtoupper($registro_dos["profesion"],"UTF-8" ); ?> +"' class='btn-aviso' style='background-color: rgba(52, 39, 199, 0.6);'>Colabora!</a>";
    <?php } ?>

    capa_aviso += "</div>";
    capa_aviso += "</div>";

    // debe aparecer despues de 1 segundo
    $('#container2').append(capa_aviso);

<?php } ?>