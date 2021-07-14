<?php

if(!file_exists('vendor/autoload.php')){
  require '../vendor/autoload.php';
  require '../lib/obs-autoloader.php';
}else {
  require 'vendor/autoload.php';
  require 'lib/obs-autoloader.php';
}

use Obs\ObsClient;
use Obs\ObsException;
//use Obs\Common\ObsException;
use function GuzzleHttp\json_encode;

// $ak = 'HNRQYLSLEIFJRULUAS8T';
// $sk = 'oeCohS7UMTqKWbgSOP64wzgHEBHsIGpDJGSDxjAa';
// $endpoint = 'https://obs.ap-southeast-2.myhwclouds.com';
// $bucketName = 'obs-jas-nextcloud';

$ak = 'ZQEMH83J1AJCP37738R4';
$sk = 'p7xsaGLdkhMwv7qQS9do3MCTzIrnZKjXx7kS6FKJ';
$endpoint = 'http://10.11.165.111:7480';
$bucketName = 'tis';

$signature = 'obs';
/*
 * Constructs a obs client instance with your account for accessing OBS
 */
$obsClient = ObsClient::factory ( [
		'key' => $ak,
		'secret' => $sk,
		'endpoint' => $endpoint,
		'socket_timeout' => 30,
		'connect_timeout' => 10
] );

try {
   return HeadBucket();
} catch ( ObsException $e ) {

  	return $e->getStatusCode();
} finally{
	$obsClient->close ();
}

/**
 * [ListBuckets list bucket]
 *
 */
function ListBuckets() {
    global $obsClient;
    try {
        $resp = $obsClient->listBuckets();
			  return $resp;
    } catch ( ObsException $e ) {
        return $e->getStatusCode();
    }
    return "400";
}
/**
 * [HeadBucket Check bucket exists]
 *
 */
function HeadBucket() {
    global $obsClient;
    global $bucketName;
    try {
        $resp = $obsClient->headBucket(array ('Bucket' => $bucketName));
				return $resp ['HttpStatusCode'];
    } catch ( ObsException $e ) {
				return $e->getStatusCode();
    }
    return "400";
}

function ListObjects($prefix) {
    global $obsClient;
    global $bucketName;
    $prefix=$prefix;
    try {
        $resp = $obsClient->listObjects(array (
                'Bucket' => $bucketName,
                'Delimiter' => '',
                'Marker' => '',
                'MaxKeys' => '',
                'Prefix' => $prefix
        ));
        return  $resp;

    } catch ( ObsException $e ) {
        return $e->getStatusCode();
    }
    return "400";
}

function DeleteObjectOnBucket($prefix,$filename){
  global $obsClient;
  global $bucketName;
  global  $ak;
  global $endpoint;
  $objectKey=$prefix.$filename;
  try {

     $resp  = $obsClient->deleteObject([
			'Bucket' => $bucketName,
			'Key'    => $objectKey,
		]);
     return "200"; //default return 204 success but no Content

  } catch ( Obs\ObsException $obsException ) {
      return $obsException->getExceptionCode ();
  }
  return "400";

}


function UploadObjectsToBucket($prefix,$filename,$pathFile,$contentType){
  global $obsClient;
  global $bucketName;
  global  $ak;
  global $endpoint;
  $objectKey=$prefix.$filename;
  try {

     $resp  = $obsClient->PutObject([
			'Bucket' => $bucketName,
			'Key'    => $objectKey,
			'SourceFile' => $pathFile,
      'ACL'=>ObsClient::AclPrivate,
      'ContentType'=>$contentType
		]);

      return $resp ['HttpStatusCode'];

  } catch ( Obs\ObsException $obsException ) {
      return $obsException->getExceptionCode ();

  }

    return "400";

}

function SignUrlObjectOnBucket($prefix,$filename,$isUrl){
  global $obsClient;
  global $bucketName;
  global  $ak;
  global $endpoint;
  $objectKey=$prefix.$filename;
  try {

    $expires = 3600;
    $resp = $obsClient->createSignedUrl( [
                  'Method' => 'GET',
                  'Bucket' => $bucketName ,
                  'Key' => $objectKey,
                  'Expires' => $expires
    ] );

   if($isUrl){
       return $resp['SignedUrl'] ;
   }else{
       return $resp ;
   }


  } catch ( Obs\ObsException $obsException ) {
    return $obsException->getExceptionCode ();
  }

  return "400";

}


function CopyObjectOnBucket($copySource,$prefix,$filename){
  global $obsClient;
  global $bucketName;

  try {
    $expires = 3600;
    $resp = $obsClient -> copyObject([
            'Bucket' => $bucketName,
            'Key' => $prefix.$filename,
            'CopySource' => $bucketName."/".$copySource
          ]);
   ///print_r($resp);
     return $resp ['HttpStatusCode'];

  } catch ( Obs\ObsException $obsException ) {
    return $obsException->getExceptionCode ();
  }

  return "400";

}









?>
