<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
<!--     <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <title>Leer PDF</title>
</head>
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
 -->
<body>

    <?php
    //variables que se obtuvieron al crear una cuenta en la API 'pdf.co'.
    $apiKey = 'deprueba1215@gmail.com_9b89280a19a7410f30120297032f1bfcf3f10547fae4f2fabaed51cca2438b31c273a4f9'; //APIKey
    $pages = '0';  //0 para que acceda a todas las paginas



    $url = "https://api.pdf.co/v1/file/upload/get-presigned-url" .
        "?name=" . $_FILES["file"]["name"] .
        "&contenttype=application/octet-stream";


    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("x-api-key: " . $apiKey));
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    if (curl_errno($curl) == 0) {
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($status_code == 200) {
            $json = json_decode($result, true);
            $uploadFileUrl = $json["presignedUrl"];
            $uploadedFileUrl = $json["url"];
            $localFile = $_FILES["file"]["tmp_name"];
            $fileHandle = fopen($localFile, "r");

            curl_setopt($curl, CURLOPT_URL, $uploadFileUrl);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("content-type: application/octet-stream"));
            curl_setopt($curl, CURLOPT_PUT, true);
            curl_setopt($curl, CURLOPT_INFILE, $fileHandle);
            curl_setopt($curl, CURLOPT_INFILESIZE, filesize($localFile));
            curl_exec($curl);
            fclose($fileHandle);

            if (curl_errno($curl) == 0) {
                $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                if ($status_code == 200) {
                    ExtractText($apiKey, $uploadedFileUrl, $pages);
                } else {
                    echo "<p>Status code: " . $status_code . "</p>";
                    echo "<p>" . $result . "</p>";
                }
            } else {
                echo "Error: " . curl_error($curl);
            }
        } else {

            echo "<p>Status code: " . $status_code . "</p>";
            echo "<p>" . $result . "</p>";
        }
        curl_close($curl);
    } else {

        echo "Error: " . curl_error($curl);
    }

    function ExtractText($apiKey, $uploadedFileUrl, $pages)
    {
        // crear URL
        $url = "https://api.pdf.co/v1/pdf/convert/to/text";

        // Parametros
        $parameters = array();
        $parameters["url"] = $uploadedFileUrl;
        $parameters["pages"] = $pages;

        // Create Json payload
        $data = json_encode($parameters);

        // Crear solicitud
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("x-api-key: " . $apiKey, "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($curl);

        if (curl_errno($curl) == 0) {
            $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($status_code == 200) {
                $json = json_decode($result, true);

                if ($json["error"] == false) {
                    $resultFileUrl = $json["url"];

                    //Mostrando resultados de la API
                    echo "<div><center><h2>Consumiendo API pdf.co</h2><center><br><center><h3>Link para ver el resultado!</h3></center><a href='" . $resultFileUrl . "' target='_blank'>" . $resultFileUrl . "</a></div>";
                    echo "<br><br><center><h3><a href='index.html'>Regresar</a></h3></center>";
                } else {

                    echo "<p>Error: " . $json["message"] . "</p>";
                }
            } else {
                // Errores
                echo "<p>Status code: " . $status_code . "</p>";
                echo "<p>" . $result . "</p>";
            }
        } else {

            echo "Error: " . curl_error($curl);
        }

        curl_close($curl);
    }

    ?>

</body>

</html>