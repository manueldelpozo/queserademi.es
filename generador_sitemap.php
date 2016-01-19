<?php
//eliminar el limite de ejecucion
set_time_limit(0);

// Include Composer autoloader
include 'vendor/autoload.php';

//Create a sitemap object
use Asika\Sitemap\Sitemap;
use Asika\Sitemap\ChangeFreq;
$sitemap = new Sitemap;

//Add items to sitemap:
//Loop de todas las url creadas
foreach(glob('profesiones/*.html') as $filename) {
	//TEST//$url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $filename;
	$url = 'http://' . $_SERVER['HTTP_HOST'] . '/queserademi/queserademi/' . $filename;
	echo $url.'<br>';
	$sitemap->addItem($url);
	$sitemap->addItem($url, '0.7', ChangeFreq::MONTHLY, new \DateTime()); //You can add some optional params
}

//Render it to XML:
header('Content-Type: application/xml');

echo $sitemap->toString();

$pagina_xml = fopen('sitemap.xml', "w+") or die("No se puede crear este documento");
// guardar xml
fwrite($pagina_xml, $sitemap);
fclose($pagina_xml);

exit();
?>