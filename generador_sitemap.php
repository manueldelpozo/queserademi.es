<?php
//eliminar el limite de ejecucion
set_time_limit(0);

// Include Composer autoloader
include 'vendor/autoload.php';

use Asika\Sitemap\Sitemap;
use Asika\Sitemap\ChangeFreq;

//Create a sitemap object
$sitemap = new Sitemap;
//Add items to sitemap:
//Loop de todas las url creadas
$limite = 5862;
$num = 1;
foreach(glob('profesiones/*.html') as $n => $filename) {
	$url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $filename;
	if ($_SERVER['HTTP_HOST'] === 'localhost')
		$url = 'http://' . $_SERVER['HTTP_HOST'] . '/queserademi/queserademi/' . $filename;
	$sitemap->addItem($url);
	$sitemap->addItem($url, '0.7', ChangeFreq::MONTHLY, new \DateTime()); //You can add some optional params
	if ($n % $limite === 0 && $n > 0) {
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