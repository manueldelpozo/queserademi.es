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
        <?php $btn_colabora_c_1=0;$btn_colabora_c_2=0; ?>
        name: '<?php echo mb_strtoupper($registro["profesion"],"UTF-8" ); ?>',
        data: [
        <?php if( is_null($registro['c_memoria']) || $registro['c_memoria'] == 0 ) {echo 0;$btn_colabora_c_1+=1;} else {echo $registro['c_memoria'];} ?>,
        <?php if( is_null($registro['c_creatividad']) || $registro['c_creatividad'] == 0 ) {echo 0;$btn_colabora_c_1+=2;} else {echo $registro['c_creatividad'];} ?>,
        <?php if( is_null($registro['c_comunicacion']) || $registro['c_comunicacion'] == 0 ) {echo 0;$btn_colabora_c_1+=4;} else {echo $registro['c_comunicacion'];} ?>,
        <?php if( is_null($registro['c_forma_fisica']) || $registro['c_forma_fisica'] == 0 ) {echo 0;$btn_colabora_c_1+=8;} else {echo $registro['c_forma_fisica'];} ?>,
        <?php if( is_null($registro['c_logica']) || $registro['c_logica'] == 0 ) {echo 0;$btn_colabora_c_1+=16;} else {echo $registro['c_logica'];} ?>
        ],
        stack: '<?php echo $registro["profesion"] ?>'
        <?php if( isset($profesion_dos) && !empty($registro_dos["profesion"]) ){ ?>
    }, {
        name: '<?php echo mb_strtoupper($registro_dos["profesion"],"UTF-8" ); ?>',
        data: [
        <?php if( is_null($registro_dos['c_memoria']) || $registro_dos['c_memoria'] == 0 ) {echo 0;$btn_colabora_c_2+=1;} else {echo $registro_dos['c_memoria'];} ?>,
        <?php if( is_null($registro_dos['c_creatividad']) || $registro_dos['c_creatividad'] == 0 ) {echo 0;$btn_colabora_c_2+=2;} else {echo $registro_dos['c_creatividad'];} ?>,
        <?php if( is_null($registro_dos['c_comunicacion']) || $registro_dos['c_comunicacion'] == 0 ) {echo 0;$btn_colabora_c_2+=4;} else {echo $registro_dos['c_comunicacion'];} ?>,
        <?php if( is_null($registro_dos['c_forma_fisica']) || $registro_dos['c_forma_fisica'] == 0 ) {echo 0;$btn_colabora_c_2+=8;} else {echo $registro_dos['c_forma_fisica'];} ?>,
        <?php if( is_null($registro_dos['c_logica']) || $registro_dos['c_logica'] == 0 ) {echo 0;$btn_colabora_c_2+=16;} else {echo $registro_dos['c_logica'];} ?> 
        ],
        stack: '<?php echo $registro_dos["profesion"] ?>' 
        <?php  }  ?> 
    }]
});

// Comprobar si se necesitan botones producido
<?php if( $btn_colabora_c_1 || $btn_colabora_c_2 ) { ?>
    // agregar capa de aviso semitransparente (con opcion a quitar?)
    var capa_aviso = "<div class='capa-aviso'>";
    capa_aviso += '<div class="cerrar-aviso"><a href="#"><img class="icon" src="images/cross.svg"></img></a></div>';
    capa_aviso += "<div class='col-md-10 col-md-offset-1'>";
    capa_aviso += "<h3>Aún no tenemos imformación suficiente!</h3>";

    <?php if( $btn_colabora_c_1 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Ayúdanos a completar información sobre <strong>cualidades profesionales</strong> de la profesión<br>";
        capa_aviso += "<strong><?php echo mb_strtoupper($registro['profesion'],'UTF-8' ); ?></strong></p>";
        capa_aviso += "<a href='colabora.php?profesion=<?php echo $registro['profesion']; ?>' class='btn btn-aviso' style='border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);'>Colabora!</a>";
    <?php } ?>

    <?php if( $btn_colabora_c_2 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Ayúdanos a completar información sobre <strong>cualidades profesionales</strong> de la profesión<br>";
        capa_aviso += "<strong><?php echo mb_strtoupper($registro_dos['profesion'],'UTF-8' ); ?></strong></p>";
        capa_aviso += "<a href='colabora.php?profesion=<?php echo $registro_dos['profesion']; ?>' class='btn btn-aviso' style='border-color: #337ab7; color: #337ab7;'>Colabora!</a>";
    <?php } ?>

    capa_aviso += "</div>";
    capa_aviso += "</div>";

    // debe aparecer despues de 1 segundo
    $('#container3').append(capa_aviso);


<?php } ?>