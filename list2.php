<?php
require 'aws.phar';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;


/*configuracion inical */

$bucket='mi-backet-2026-humiri';
$region= 'us-east-2';

$accessKey = 'TU_ACCESS_KEY';
$secretKey  = 'TU_SECRET_KEY';

/*cliente s3 */

$s3=new S3Client([
    'version' => 'latest',
    'region'=>$region,
    'credentials'=>[
        'key'=>$aceessKey,
        'secret' =>$secretKey,  
      ],
]);

/*listar contenido */

try {
    $result = $s3->listObjectsV2([
        'Bucket' => $bucket,
    ]);

    echo "<h2>Contenido del bucket '$bucket':</h2>";

    if (!empty($result['Contents'])) {
        echo "<ul>";
        foreach ($result['Contents'] as $object) {
            $key  = $object['Key'];
            $size = round($object['Size'] / 1024, 2);
            $date = $object['LastModified']->format('Y-m-d H:i');

          $url=$s3->getObjectUrl($bucket, $key);
          echo "<li>
          <a href='{$url}' target='_blank'>{$key}</a>
          <small>({$size} KB | {$date})</small>
          </li>";
        }
        echo"</ul>";
    }else{
    echo"<p> El bucket esta vacio</p>";
    }
}catch(AwsException $e){
    echo "<strong>Error: </strong>".$e->getAwsErrorMessage();
}

?>