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

$meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
$meses = array_merge($meses,$meses);
$year = '2014';
// Looping para leer los pdf 1 a 1
foreach ($meses as $mes) {
	if( array_search($mes, $meses) > 11 )
		$year = '2015';
	$pdf_url = 'pdf/parados_contratados/'.$mes.'_'.$year.'.pdf';
	$pdf     = $parser->parseFile($pdf_url);

	// Retrieve all pages from the pdf file.
	$pages  = $pdf->getPages();
	 
	// Loop over each page to extract text.
	foreach ($pages as $page) {
	    $texto = $page->getText();
	    //escanear texto para encontrar los valores deseados
	    $array_texto = $texto.split('');
	    $valor_parados = encontrarValor('parados', $array_texto);
	    $valor_contratados = encontrarValor('contratados', $array_texto);
	}
}

 		

 
?>