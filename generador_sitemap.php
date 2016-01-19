<?php
//eliminar el limite de ejecucion
set_time_limit(0);

// Include Composer autoloader
include 'vendor/autoload.php';

//Create a sitemap object
use Asika\Sitemap\Sitemap;

$sitemap = new Sitemap;

//Add items to sitemap:
//Loop de todas las url creadas

foreach(glob('profesiones/*.html') as $filename) {
	//TEST//$url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $filename;
	$url = 'http://' . $_SERVER['HTTP_HOST'] . '/queserademi/queserademi/' . $filename;
	$sitemap->addItem($url);
    echo $url.'<br>';
}

//You can add some optional params.

/*use Asika\Sitemap\ChangeFreq;

$sitemap->addItem($url, '1.0', ChangeFreq::DAILY, '2015-06-07 10:51:20');
$sitemap->addItem($url, '0.7', ChangeFreq::WEEKLY, new \DateTime('2015-06-03 11:24:20'));*/

//The arguments are loc, priority, changefreq and lastmod

//Render it to XML:
header('Content-Type: application/xml');

echo $sitemap->toString();

$pagina_xml = fopen('sitemap.xml', "w+") or die("No se puede crear este documento");
// guardar xml
fwrite($pagina_xml, $sitemap);
fclose($pagina_xml);

exit();

?>