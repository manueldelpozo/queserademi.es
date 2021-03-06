<?php
//eliminar el _LIMIT de ejecucion
set_time_limit(0);

// Include Composer autoloader
include 'vendor/autoload.php';

use Asika\Sitemap\Sitemap;
use Asika\Sitemap\ChangeFreq;

//Create a sitemap object
$sitemap = new Sitemap;

foreach(glob('*.html') as $filename) {
	$url = 'https://queserademi.com/' . $filename;
	$sitemap->addItem($url, '1', ChangeFreq::MONTHLY, new \DateTime()); //You can add some optional params
}
//Render it to XML:
header('Content-Type: application/xml'); 
$nombre_sitemap   = 'sitemap.xml';
$pagina_xml = fopen($nombre_sitemap, "w+") or die("No se puede crear este sitemap");
// guardar xml
fwrite($pagina_xml, $sitemap);
fclose($pagina_xml);
echo '<h2>' . $nombre_sitemap . '</h2>';
//echo $sitemap->toString() . '<br>';


// vaciar sitemap - nueva instancia
$sitemap = new Sitemap;
//Loop de todas las url creadas
$num = 1;

foreach(glob('profesiones/*.html') as $n => $filename) {
	$url = 'https://' . 'queserademi.com' . '/' . $filename;
	//if ($_SERVER['HTTP_HOST'] === 'localhost')
		//$url = 'https://' . $_SERVER['HTTP_HOST'] . '/queserademi/queserademi/' . $filename;
	$sitemap->addItem($url, '0.7', ChangeFreq::MONTHLY, new \DateTime()); //You can add some optional params
	if ($n % $_LIMIT === 0 && $n > 0) {
		//Render it to XML:
		header('Content-Type: application/xml');
		$nombre_sitemap = 'sitemap-' . $num . '.xml';
		$pagina_xml = fopen($nombre_sitemap, "w+") or die("No se puede crear este sitemap");
		// guardar xml
		fwrite($pagina_xml, $sitemap);
		fclose($pagina_xml);
		echo $sitemap->toString();
		// vaciar sitemap - nueva instancia
		$sitemap = new Sitemap;
		$num++;
	}
}

exit();
?>