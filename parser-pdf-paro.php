<?php
 
// Include Composer autoloader if not already done.
include 'vendor/autoload.php';
 
// Parse pdf file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();

$pdf_url = 'pdf/parados_contratados/document';
$meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
// looping para leer los pdf 1 a 1
foreach ($meses as $mes => $value) {
	# code...
}
$pdf    = $parser->parseFile('document.pdf');
 
// Retrieve all pages from the pdf file.
$pages  = $pdf->getPages();
 
// Loop over each page to extract text.
foreach ($pages as $page) {
    echo $page->getText();
}
 
?>