<?php
 
// Include Composer autoloader if not already done.
include 'vendor/autoload.php';
 
// Parse pdf file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();

function restriccion($tipo, $str) {
	if ($tipo=='profesion') 
		return (strtoupper($str) == $str)?true:false;
	else
		return ( (int)$str )?true:false;
}

function encontrarValor($tipo, $arrayPagina, $year) {
	$palabra_clave_i = $palabra_clave_f = '';
	if($tipo=='profesion') {
		//echo '<b>buscando profesion</b>';
		$palabra_clave_i = "".$year."";
		$palabra_clave_f = '(*)';
		$veces = 20;
	} else if($tipo=='parados') {
		$palabra_clave_i = 'Discapacidad';
		$palabra_clave_f = 'parados';
		$veces = 30;
	} else if($tipo=='contratados') {
		$palabra_clave_i = 'los';
		$palabra_clave_f = 'contratos';
		$veces = 2;
	}
	if(in_array($palabra_clave_i, $arrayPagina, TRUE)) {
		$posicion_i = array_search($palabra_clave_i, $arrayPagina);
		$valor = '';
		for ($i=1; $i < $veces ; $i++) { 
			$siguiente_valor = $arrayPagina[$posicion_i + $i];
			if ($siguiente_valor != $palabra_clave_f && restriccion($tipo, $siguiente_valor))
				$valor .= $siguiente_valor.' ';
			else
				continue;
		}
		// ultimo filtro
		$slices = explode(' ',$valor);
		if ($tipo=='profesion') {
			$patrones = [];
			$patrones[0] = '/^\\s\\W/';
			$patrones[1] = '/\\d/';
			foreach ($patrones as $patron) {
				$find_arrays = preg_grep($patron, $slices);
				foreach ($find_arrays as $key => $value) {
					unset($slices[$key]);
				}
			}
			$valor = implode(' ',$slices);
		} else if ($tipo=='parados') {
			$penultimo_str = $slices[count($slices)-2];
			if(preg_match('/(%)$/',$penultimo_str))
				$valor = $slices[count($slices)-3];
			else
				$valor = $penultimo_str;
		}
		
	    return $valor;
    }
}

$letras = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','Y','Z'];
$meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
$meses = array_merge($meses,$meses); // concatenar meses
$year = '2014';
// Looping para leer los pdf 1 a 1
echo '<table border><tr><th>PROFESION</th><th>PARADOS</th><th>CONTRATADOS</th></tr>';
foreach ($letras as $letra) {

	$pdf_url = 'pdf/empleabilidad/'.$letra.'_*.pdf';
	// Comprobar si has contenidos
	if (count(glob($pdf_url)) > 0) {

		foreach ($meses as $n_mes => $mes) {
			
			if( $n_mes > 11 )
				$year = '2015';
			else
				$year = '2014';
			
			$pdf_url = 'pdf/empleabilidad/'.$letra.'_'.$mes.'_'.$year.'.pdf';
			$pdf_url = 'pdf/empleabilidad/OcuA_Ab14.pdf';
			//echo '<h2>'.$pdf_url.'</h2>';
			
			$pdf = $parser->parseFile($pdf_url);

			// Retrieve all pages from the pdf file.
			$pages  = $pdf->getPages();
			
			// Loop over each page to extract text.
			foreach ($pages as $n_page => $page) {
				// si la pagina es par
				if($n_page%2==0) {
					//echo '<h4>Pagina '.$n_page.'</h4>';
				    $texto = $page->getText();
				    //escanear texto para encontrar los valores deseados
				    $array_texto = explode(" ",$texto);
				    
				    //extraer valores
				    $valor_profesion = encontrarValor('profesion', $array_texto, $year);
				    $valor_parados = encontrarValor('parados', $array_texto, $year);
				    $valor_contratados = encontrarValor('contratados', $array_texto, $year);

				    //crear tabla
				    
				    echo '<tr><td>'.$valor_profesion.'</td><td>'.$valor_parados.'</td><td>'.$valor_contratados.'</td></tr>';
				}
			}
			
			
		}
	} else {
		continue;
	}
	
}
echo '</table>';
 		

 
?>