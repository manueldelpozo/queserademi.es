<?php

//require('conexion.php');

// actualizar datos de la bbdd sobre profesiones
//include('excel/converter_tablas.php');

$output = shell_exec('php excel/converter_tablas.php');
echo "<pre>$output</pre>";


//$currentdir = getcwd();
//$target = $currentdir .'/profesiones/' . basename($_FILES['photo']['name']);
//move_uploaded_file($_FILES['photo']['tmp_name'], $target);
?>