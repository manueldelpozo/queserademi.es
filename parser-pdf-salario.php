<?php
 
// Include Composer autoloader if not already done.
include 'vendor/autoload.php';
 
// Parse pdf file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();

function encontrarValor($tipo,$array) {
	$posicion = array_search($tipo, $array);
    $valor = $array[$posicion + 1];
    if (!is_nan($valor))
    	return $valor;
	else
		return false;
}

$letras = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','Y','Z'];
$meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
$meses = array_merge($meses,$meses); // concatenar meses
$year = '2014';
// Looping para leer los pdf 1 a 1
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
			$pdf_url = 'pdf/salarios/salario_ine.pdf';
			echo '<h2>'.$pdf_url.'</h2>';
			
			$pdf = $parser->parseFile($pdf_url);

			// Retrieve all pages from the pdf file.
			$pages  = $pdf->getPages();
			
			// Loop over each page to extract text.
			foreach ($pages as $n_page => $page) {
				echo '<h4>Pagina '.$n_page.'</h4>';
			    $texto = $page->getText();
			    //escanear texto para encontrar los valores deseados
			    //$array_texto = $texto.split('');
			    
			    echo $texto;
			    //$valor_parados = encontrarValor('parados', $array_texto);
			    //$valor_contratados = encontrarValor('contratados', $array_texto);
			}
			
		}
	} else {
		continue;
	}
	
}

 		

 
?>