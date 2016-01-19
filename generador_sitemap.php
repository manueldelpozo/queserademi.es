<?php
//eliminar el limite de ejecucion
set_time_limit(0);

//conectar
require('../conexion.php');

//usar PHPExcel_IOFactory
include '../vendor/autoload.php';


//Create a sitemap object
use Asika\Sitemap\Sitemap;

$sitemap = new Sitemap;

//Add items to sitemap:

$sitemap->addItem($url);
$sitemap->addItem($url);
$sitemap->addItem($url);

//You can add some optional params.

use Asika\Sitemap\ChangeFreq;

$sitemap->addItem($url, '1.0', ChangeFreq::DAILY, '2015-06-07 10:51:20');
$sitemap->addItem($url, '0.7', ChangeFreq::WEEKLY, new \DateTime('2015-06-03 11:24:20'));

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