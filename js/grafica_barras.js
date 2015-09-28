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
        name: '<?php echo mb_strtoupper($registro["nombre_ppal"],"UTF-8" ); ?>',
        data: [
        <?php if( is_null($registro['s_past']) || $registro['s_past'] == 0 ) {echo 0;$btn_colabora_s_1+=1;} else {echo $registro['s_past'];} ?>,
        <?php echo $registro['s_present'] ?>, 
        <?php if( is_null($registro['s_future']) || $registro['s_future'] == 0 ) {echo 0;$btn_colabora_s_1+=2;} else {echo $registro['s_future'];} ?>
        ],
        stack: '<?php echo $registro["nombre_ppal"] ?>'
        <?php	if( isset($profesion_dos) && !empty($registro_dos["nombre_ppal"]) ){ ?>
	}, {
        name: '<?php echo mb_strtoupper($registro_dos["nombre_ppal"],"UTF-8" ); ?>',
        data: [
        <?php if( is_null($registro_dos['s_past']) || $registro_dos['s_past'] == 0 ) {echo 0;$btn_colabora_s_2+=1;} else {echo $registro_dos['s_past'];} ?>,
        <?php echo $registro_dos['s_present'] ?>, 
        <?php if( is_null($registro_dos['s_future']) || $registro_dos['s_future'] == 0 ) {echo 0;$btn_colabora_s_2+=2;} else {echo $registro_dos['s_future'];} ?>
        ],
        stack: '<?php echo $registro_dos["nombre_ppal"] ?>'
    	<?php  }  ?> 
	}]
});

// Comprobar si se necesitan botones producido
<?php if( $btn_colabora_s_1 || $btn_colabora_s_2 ) { ?>
    // agregar capa de aviso semitransparente (con opcion a quitar?)
    var capa_aviso = "<div class='capa-aviso'>";
    capa_aviso += '<div class="cerrar-aviso"><a href="#"><img class="icon" src="images/cross.svg"></img></a></div>';
    capa_aviso += "<div class='col-md-10 col-md-offset-1'>";
    capa_aviso += "<h3>Aún no tenemos imformación suficiente!</h3>";

    <?php if( $btn_colabora_s_1 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Ayúdanos a completar información sobre <strong>salario</strong> de la profesión<br>";
        capa_aviso += "<strong><?php echo mb_strtoupper($registro['nombre_ppal'],'UTF-8' ); ?></strong></p>";
        capa_aviso += "<a href='colabora.php?profesion=<?php echo $registro['nombre_ppal']; ?>' class='btn btn-aviso' style='border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);'>Colabora!</a>";
    <?php } ?>

    <?php if( $btn_colabora_s_2 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Ayúdanos a completar información sobre <strong>salario</strong> de la profesión<br>";
        capa_aviso += "<strong><?php echo mb_strtoupper($registro_dos['nombre_ppal'],'UTF-8' ); ?></strong></p>";
        capa_aviso += "<a href='colabora.php?profesion=<?php echo $registro_dos['nombre_ppal']; ?>' class='btn btn-aviso' style='border-color: #337ab7; color: #337ab7;'>Colabora!</a>";
    <?php } ?>

    capa_aviso += "</div>";
    capa_aviso += "</div>";

    // debe aparecer despues de 1 segundo
    $('#container2').append(capa_aviso);


<?php } ?>
