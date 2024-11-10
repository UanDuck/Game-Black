<?php

$tp="archivos/";
$tp=$tp.basename($_FILES['uploaderfile']['name']);

echo $tp;
echo '<br>';
if(move_uploaded_file($_FILES['uploaderfile']['tmp_name'], $tp)){
    echo "El archivo ".basename($_FILES['uploaderfile']['name'])." ha sido subido";
}else{
    echo "Ha ocurrido un error, trate de nuevo";
}





?>