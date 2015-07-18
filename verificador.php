<?php
if ($_POST['verificacion'] != ""){
    // Es un SPAMbot
    exit();
}else{
    // Es un usuario real, proceder a enviar el formulario.
}
?>