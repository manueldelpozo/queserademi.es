<?php

//require('conexion.php');

// actualizar datos de la bbdd sobre profesiones
//include('excel/converter_tablas.php');

$output = shell_exec('php excel/converter_tablas.php');
echo "<pre>$output</pre>";

?>