<?php

//recibo el pdf y lo guardo en una carpeta para luego leerlo.
$nombre_archivo = $_FILES['file']['name'];
$tipo_archivo = $_FILES['file']['type'];
$tamano_archivo = $_FILES['file']['size'];


if (move_uploaded_file($_FILES['file']['tmp_name'],  "paralospdf/$nombre_archivo")) {
   // echo "El archivo ha sido cargado correctamente."; //PDF almacenado.
} else {
    //echo "Ocurrió algún error al subir el fichero. No pudo guardarse.";
}


//require 'path/to/PdfToText.phpcass';
include('class.pdf2text.php');
$a = new PDF2Text();
var_dump($a);
$a->setFilename($nombre_archivo); 
$a->decodePDF();

echo $a->output(); 