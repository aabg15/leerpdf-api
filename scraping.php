
<?php

//recibo el pdf y lo guardo en una carpeta para luego leerlo.
$nombre_archivo = $_FILES['file']['name'];
$tipo_archivo = $_FILES['file']['type'];
$tamano_archivo = $_FILES['file']['size'];

echo $nombre_archivo;
echo "<br>";
echo $tipo_archivo;
echo "<br>";
echo $tamano_archivo;

exit();

if (move_uploaded_file($_FILES['file']['tmp_name'],  "paralospdf/$nombre_archivo")) {
   // echo "El archivo ha sido cargado correctamente."; //PDF almacenado.
} else {
    //echo "Ocurrió algún error al subir el fichero. No pudo guardarse.";
}

include "pdf2text/src/Pdf2text/Pdf2text.php";

//$pdf2text = new Pdf2text($nombre_archivo);
$pdf2text = new \Pdf2text\Pdf2text($nombre_archivo);

$output = $pdf2text->decode();
var_dump($output);



?>