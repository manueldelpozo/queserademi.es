<?php 
$btn_colabora_e_1 = $btn_colabora_e_2 = 0;
$meses = ['enero','abril','julio','octubre'];
$meses = array_pop(array_merge($meses,$meses)); // concatenar meses y eliminar el ultimo elemento

function coefMin($parados) {
    $output = 1;
    $n = 15000;
    $m = 0.95;
    while ($n >= 1000) {
        if ($parados < $n)
            $output = $m;
        $n -= 1000;
        $m -= 0.05;
    }
    if ($parados < 100)
        $output = 0.1;
    return $output;
}

function empleabilidad($contratados, $parados) {
    return (!is_null($parados) && $parados > 0) ?  round(coefMin($parados) * round(100 - ($contratados * 100 / ($parados + $contratados)), 2), 2) : 0;
}

function imprimirSeriesEmp($filas) {
    $counter = 0;
    $counter_rect = 0;
    $no_duplicado = true;
    $memo = [];
    foreach ($filas as $fila) {
        $memo[$counter] = $fila;
        if (count($memo) > 1)
            $no_duplicado = ($memo[$counter - 1]['mes'] !== $memo[$counter]['mes']);
        if ($no_duplicado && $counter_rect < 7) {
            $counter_rect++;
            $emp = empleabilidad($fila['contratados'], $fila['parados']);
            echo (is_null($emp) || $emp == 0) ? "0," : $emp.",";
        }
        $counter++;
    }
}

// busqueda de nulos
foreach ($filas_empleabilidad as $fila_empleabilidad) { 
    $empleabilidad = empleabilidad($fila_empleabilidad['contratados'], $fila_empleabilidad['parados']); 
    if( is_null($empleabilidad) || $empleabilidad == 0 )
        $btn_colabora_e_1++;
}
if( isset($profesion_dos) && !empty($profesion_dos) ){
    foreach ($filas_empleabilidad_dos as $fila_empleabilidad_dos) { 
        $empleabilidad_dos = empleabilidad($fila_empleabilidad_dos['contratados'], $fila_empleabilidad_dos['parados']); 
        if( is_null($empleabilidad_dos) || $empleabilidad_dos == 0 )
            $btn_colabora_e_2++;
    }
}
?>

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
        text: 'PARO',
        align: "center",
        style: { 
            'color': '#555',
            'fontSize': '14px',
            'fontWeight': 'bold'
        }
    },
    subtitle: {
        text: '- DIFICULTAD DE CONSEGUIR TRABAJO -'
    },

    legend: { enable: false },
    xAxis: {
        categories: [ 'Enero 2014', 'Abril 2014', 'Julio 2014', 'Octubre 2014', 'Enero 2015', 'Abril 2015', 'Julio 2015' ]
        /*<?php 
         foreach ($meses as $n_mes => $mes) { 
            $year = ( $n_mes > count($meses)/2 - 1 )?'2015':'2014';
            echo "'".ucfirst($mes)." ".$year."',";
        }
        ?>*/
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
        pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}'
    },
    credits: {
        enabled: false
    }, 
    series: [{
        name: '<?php echo mb_strtoupper($profesion,"UTF-8" ); ?>',
        data: [ <?php imprimirSeriesEmp($filas_empleabilidad); ?> ],
        stack: '<?php echo $profesion ?>'
        <?php if( isset($profesion_dos) && !empty($profesion_dos) ){ ?>
	}, {
        name: '<?php echo mb_strtoupper($profesion_dos,"UTF-8" ); ?>',
        data: [ <?php imprimirSeriesEmp($filas_empleabilidad_dos); ?> ],
        stack: '<?php echo $profesion_dos ?>'
    	<?php  }  ?> 
	}]
});

// Comprobar si se necesitan botones producido
<?php if( $btn_colabora_e_1 || $btn_colabora_e_2 ) { ?>
    // agregar capa de aviso semitransparente (con opcion a quitar?)
    var capa_aviso = '<div class="capa-aviso">';
    capa_aviso += '<div class="cerrar-aviso"><a href="#"><img class="icon" src="images/cross.svg"></img></a></div>';
    capa_aviso += '<div class="col-md-10 col-md-offset-1">';
    capa_aviso += '<h3>Aún no tenemos imformación suficiente!</h3>';

    <?php if( $btn_colabora_e_1 > 0 ) { ?>
        capa_aviso += '<p class="text-center">Ayúdanos a completar información sobre <strong>desempleo</strong> de la profesión<br>';
        capa_aviso += '<strong><?php echo mb_strtoupper($profesion,"UTF-8"); ?></strong></p>';
        capa_aviso += '<a href="colabora.php?profesion=<?php echo $profesion; ?>" class="btn btn-aviso" style="border-color: rgb(204, 0, 0); color: rgb(204, 0, 0);">Colabora!</a>';
    <?php } ?>

    <?php if( $btn_colabora_e_2 > 0 ) { ?>
        capa_aviso += '<p class="text-center">Ayúdanos a completar información sobre <strong>desempleo</strong> de la profesión<br>';
        capa_aviso += '<strong><?php echo mb_strtoupper($profesion_dos,"UTF-8"); ?></strong></p>';
        capa_aviso += '<a href="colabora.php?profesion=<?php echo $profesion_dos; ?>" class="btn btn-aviso" style="border-color: #337ab7; color: #337ab7;">Colabora!</a>';
    <?php } ?>

    capa_aviso += '</div>';
    capa_aviso += '</div>';

    // debe aparecer despues de 1 segundo
    $('#container_empleabilidad').append(capa_aviso);

<?php } ?>
