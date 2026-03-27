<?php
require 'aws.phar';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;


/*configuracion inical */

$bucket='mi-backet-2026-humiri';
$region= 'us-east-2';

$accessKey = 'TU_ACCESS_KEY';
$secretKey  = 'TU_SECRET_KEY';

$s3 = new S3Client([
    'version'     => 'latest',
    'region'      => $region,
    'credentials' => [
        'key'    => $accessKey,
        'secret' => $secretKey,
    ],
]);

/*subir <archvio></archvio>*/

/*Subir archivo*/

if(!isset($_FILES['archivo'])){

  die("No se envió ningún archivo");

}


$archivo=$_FILES['archivo'];

if($archivo['error'] !== UPLOAD_ERR_OK){
  die("Error al subir archivo");

}

$nombreArchivo=time()."_".basename($archivo['name']);

try{

  $s3->putObject([
    'Bucket' =>$bucket,
    'Key' =>$nombreArchivo,
    'SourceFile' =>$archivo['tmp_name'],
    'ContentType' =>mime_content_type($archivo['tmp_name'])

  ]);
  $url=$s3->getObjectUrl($bucket,$nombreArchivo);


  echo "Imagen subida correctamente<br>";
  echo "<a href='$url' target='_blank'>Ver Imagen</a>";
}catch(AwsException $e){
  echo "Error AWS: ".$e->getAwsErrorMessage();
}