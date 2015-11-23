$('#container_empleabilidad').highcharts({
    chart: {
        type: 'column',
        marginTop: 80,
        marginRight: 40,
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
        text: 'PARO (dificultad de conseguir trabajo) ',
        align: "center"
    },
    legend: { enable: false },
    xAxis: {
        categories: [
        <?php 
        $meses = ['enero','abril','julio','octubre'];
        $meses = array_merge($meses,$meses); // concatenar meses
        foreach ($meses as $n_mes => $mes) { 
            $year = ( $n_mes > count($meses)/2 - 1 )?'2015':'2014';
            echo "'".ucfirst($mes)." ".$year."',";
        }
        ?>
        ]
    },
    yAxis: {
        allowDecimals: true,
        min: 0,
        title: {
            text: 'Dificultad de conseguir trabajo %'
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
        <?php 
        $btn_colabora_e_1=$btn_colabora_e_2=0;
        function empleabilidad($contratados, $parados) {
            return 100 - ( $contratados * 100 / ($parados + $contratados) );
        }
        ?>
        name: '<?php echo mb_strtoupper($profesion,"UTF-8" ); ?>',
        data: [
        <?php foreach ($filas_empleabilidad as $fila_emp) { ?>
        <?php $emp = empleabilidad($fila_emp['contratados'], $fila_emp['parados']); ?>
        <?php if( is_null($emp) || $emp == 0 ) {echo 0;$btn_colabora_e_1+=1;} else {echo $emp;} ?>,
        <?php } ?>
        ],
        stack: '<?php echo $profesion ?>'
        <?php if( isset($profesion_dos) && !empty($profesion_dos) ){ ?>
	}, {
        name: '<?php echo mb_strtoupper($profesion_dos,"UTF-8" ); ?>',
        data: [
        <?php foreach ($filas_empleabilidad_dos as $fila_emp_dos) { ?>
        <?php $emp = empleabilidad($fila_emp_dos['contratados'], $fila_emp_dos['parados']); ?>
        <?php if( is_null($emp) || $emp == 0 ) {echo 0;$btn_colabora_e_2+=1;} else {echo $emp;} ?>,
        <?php } ?>
        ],
        stack: '<?php echo $profesion_dos ?>'
    	<?php  }  ?> 
	}]
});

// Comprobar si se necesitan botones producido
<?php if( $btn_colabora_e_1 || $btn_colabora_e_2 ) { ?>
    // agregar capa de aviso semitransparente (con opcion a quitar?)
    var capa_aviso = "<div class='capa-aviso'>";
    capa_aviso += '<div class="cerrar-aviso"><a href="#"><img class="icon" src="images/cross.svg"></img></a></div>';
    capa_aviso += "<div class='col-md-10 col-md-offset-1'>";
    capa_aviso += "<h3>Aún no tenemos imformación suficiente!</h3>";

    <?php if( $btn_colabora_e_1 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Ayúdanos a completar información sobre <strong>empleabilidad</strong> de la profesión<br>";
        capa_aviso += "<strong><?php echo mb_strtoupper($profesion,'UTF-8'); ?></strong></p>";
        capa_aviso += "<a href='colabora.php?profesion=<?php echo $profesion; ?>' class='btn btn-aviso' style='border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);'>Colabora!</a>";
    <?php } ?>

    <?php if( $btn_colabora_e_2 > 0 ) { ?>
        capa_aviso += "<p class='text-center'>Ayúdanos a completar información sobre <strong>empleabilidad</strong> de la profesión<br>";
        capa_aviso += "<strong><?php echo mb_strtoupper($profesion_dos,'UTF-8'); ?></strong></p>";
        capa_aviso += "<a href='colabora.php?profesion=<?php echo $profesion_dos; ?>' class='btn btn-aviso' style='border-color: #337ab7; color: #337ab7;'>Colabora!</a>";
    <?php } ?>

    capa_aviso += "</div>";
    capa_aviso += "</div>";

    // debe aparecer despues de 1 segundo
    $('#container_empleabilidad').append(capa_aviso);


<?php } ?>
