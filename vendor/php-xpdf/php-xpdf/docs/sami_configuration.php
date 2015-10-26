<?php

include 'vendor/autoload.php';

use Sami\Sami;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in($dir = 'src')
;

return new Sami($iterator, array(
    'title'                => 'PHP-XPDF API',
    'theme'                => 'enhanced',
    'build_dir'            => __DIR__.'/source/API/API',
    'cache_dir'            => __DIR__.'/source/API/API/cache',
    'default_opened_level' => 2,
));
