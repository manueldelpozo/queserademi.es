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
        <?php $btn_colabora_p_1=0;$btn_colabora_p_2=0; ?>
        name: '<?php echo mb_strtoupper($registro["profesion"],"UTF-8" ); ?>',
        data: [
        <?php if( is_null($registro['p_past']) || $registro['p_past'] == 0 ) {echo 0;$btn_colabora_p_1+=1;} else {echo $registro['p_past'];} ?>,
        <?php echo $registro['p_present'] ?>, 
        <?php if( is_null($registro['p_future']) || $registro['p_future'] == 0 ) {echo 0;$btn_colabora_p_1+=2;} else {echo $registro['p_future'];} ?>
        ],
        stack: '<?php echo $registro["profesion"] ?>'
        <?php if( isset($profesion_dos) && !empty($registro_dos["profesion"]) ){ ?>
    }, {
        name: '<?php echo mb_strtoupper($registro_dos["profesion"],"UTF-8" ); ?>',
        data: [
        <?php if( is_null($registro_dos['p_past']) || $registro_dos['p_past'] == 0 ) {echo 0;$btn_colabora_p_2+=1;} else {echo $registro_dos['p_past'];} ?>,
        <?php echo $registro_dos['p_present'] ?>, 
        <?php if( is_null($registro_dos['p_future']) || $registro_dos['p_future'] == 0 ) {echo 0;$btn_colabora_p_2+=2;} else {echo $registro_dos['p_future'];} ?>
        ],
        stack: '<?php echo $registro_dos["profesion"] ?>' 
        <?php  }  ?> 
    }]
});

// Comprobar si se necesitan botones producido
<?php if( $btn_colabora_p_1 || $btn_colabora_p_2 ) { ?>
    // agregar capa de aviso semitransparente (con opcion a quitar?)
    var capa_aviso = "<div class='capa-aviso'>";
    capa_aviso += "<div class='col-md-8 col-md-offset-2'>";
    capa_aviso += "<h3>Disculpe las molestias</h3>";

    <?php if( $btn_colabora_p_1 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Falta información sobre el <strong>desempleo</strong> de la profesión<br>";
        capa_aviso += "<strong><?php echo mb_strtoupper($registro['profesion'],'UTF-8' ); ?></strong></p>";
        capa_aviso += "<a href='colabora.php?profesion=<?php echo $registro['profesion']; ?>' class='btn btn-aviso' style='border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);'>Colabora!</a>";
    <?php } ?>

    <?php if( $btn_colabora_p_2 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Falta información sobre el <strong>desempleo</strong> de la profesión<br>";
        capa_aviso += "<strong><?php echo mb_strtoupper($registro_dos['profesion'],'UTF-8' ); ?></strong></p>";
        capa_aviso += "<a href='colabora.php?profesion=<?php echo $registro_dos['profesion']; ?>' class='btn btn-aviso' style='border-color: rgb(52, 39, 199); color: rgb(52, 39, 199);'>Colabora!</a>";
    <?php } ?>

    capa_aviso += "</div>";
    capa_aviso += "</div>";

    // debe aparecer despues de 1 segundo
    $('#container').append(capa_aviso);


<?php } ?>