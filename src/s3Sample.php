<?php
require 'vendor/autoload.php';
require 'lib/obs-autoloader.php';
use Obs\ObsClient;
use Obs\ObsException;
use function GuzzleHttp\json_encode;


$ak = 'ZQEMH83J1AJCP37738R4';
$sk = 'p7xsaGLdkhMwv7qQS9do3MCTzIrnZKjXx7kS6FKJ';
$endpoint = 'http://10.11.165.111:7480';
$bucketName = 'tis';

// $ak = 'HNRQYLSLEIFJRULUAS8T';
// $sk = 'oeCohS7UMTqKWbgSOP64wzgHEBHsIGpDJGSDxjAa';
// $endpoint = 'https://obs.ap-southeast-2.myhwclouds.com';
// $bucketName = 'obs-jas-nextcloud';
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
	//Unused function
	//createBucket (); // สร้าง bucket
	//ListBuckets(); // list bucket ใน login
	//getBucketLocation (); //แสดง location bucket
	//getBucketMetadata (); //Get bucket metadata operation
	//doBucketQuotaOperation (); //Put/Get bucket quota operations
	//doBucketVersioningOperation (); //Put/Get bucket versioning operations
	//$ownerId = doBucketAclOperation (); //Put/Get bucket acl operations
	//doBucketLoggingOperation ($ownerId); //Put/Get/Delete bucket logging operations
	//Cors not enable
	//doBucketCorsOperation (); //Put/Get/Delete bucket cors operations
	//optionsBucket (); //Options bucket operation
	//doBucketLifecycleOperation (); //Put/Get/Delete bucket lifecycle operations
	//doBucketTaggingOperation (); //Put/Get/Delete bucket tagging operations
	//doBucketWebsiteOperation (); //Put/Get/Delete bucket website operations
	//deleteBucket (); //Delete bucket operation
	if (HeadBucket()=="200") {
		echo "Bucket exists<br/>";
	} else {
		echo "Bucket error<br/>";
		exit;
	}
	echo "<HR>getBucketStorageInfo<br/>";
	//echo "Size:6823644<br>";
	getBucketStorageInfo (); //Get bucket storageInfo operation
	echo "<HR>GetBucketStoragePolicy<br/>";
	GetBucketStoragePolicy(); //see Storage Classes.
	echo "<HR>ListObjects<br/>";
	ListObjects();
} catch ( ObsException $e ) {
	echo "Error exception<HR>";
	echo 'Response Code:' . $e->getStatusCode () . PHP_EOL."<BR>";
	echo 'Error Message:' . $e->getExceptionMessage () . PHP_EOL."<BR>";
	echo 'Error Code:' . $e->getExceptionCode () . PHP_EOL."<BR>";
	echo 'Request ID:' . $e->getRequestId () . PHP_EOL."<BR>";
	echo 'Exception Type:' . $e->getExceptionType () . PHP_EOL."<BR>";
} finally{
	$obsClient->close ();
}


function createBucket()
{
	global $obsClient;
	global $bucketName;

	$resp = $obsClient->createBucket ([
		'Bucket' => $bucketName,
	]);
	printf("HttpStatusCode:%s\n\n", $resp ['HttpStatusCode']);
	printf("Create bucket: %s successfully!\n\n", $bucketName);
}

// list buckets
function ListBuckets() {
    global $obsClient;
    echo "list bucket start...\n";
    try {
        $resp = $obsClient->listBuckets();
				echo "HttpStatusCode=".$resp ['HttpStatusCode']."<BR>";
				echo "RequestId=".$resp ['RequestId']."<BR>";
        //printf("HttpStatusCode:%s\n", $resp ['HttpStatusCode']);
        //printf("RequestId:%s\n", $resp ['RequestId']);
        $i = 0;
        foreach ( $resp ['Buckets'] as $bucket ) {
            //printf("Buckets[$i][Name]:%s,Buckets[$i][CreationDate]:%s\n", $bucket ['Name'], $bucket ['CreationDate']);
						echo $i.".";
						echo "Name=".$bucket['Name']."<br/>";
            $i ++;
        }
        //printf("Owner[ID]:%s\n", $resp ['Owner'] ['ID']);
    } catch ( ObsException $e ) {
        echo $e;
    }
}

//check bucket exists
function HeadBucket() {
    global $obsClient;
    global $bucketName;
    try {
        $resp = $obsClient->headBucket(array ('Bucket' => $bucketName));
				return $resp ['HttpStatusCode'];
    } catch ( ObsException $e ) {
				echo $e;
				return "404";
    }
}

function getBucketLocation()
{
	global $obsClient;
	global $bucketName;

	$promise = $obsClient -> getBucketLocationAsync(['Bucket' => $bucketName], function($exception, $resp){
		printf("Getting bucket location %s\n\n", $resp ['Location']);
	});
	$promise -> wait();
}

function getBucketStorageInfo()
{
	global $obsClient;
	global $bucketName;
	$promise = $obsClient -> getBucketStorageInfoAsync(['Bucket' => $bucketName], function($exception, $resp){
		printf("Getting bucket storageInfo Size:%d,ObjectNumber:%d\n\n", $resp ['Size'], $resp ['ObjectNumber']);
	});
	$promise -> wait();
}

function doBucketQuotaOperation()
{
	global $obsClient;
	global $bucketName;
	$obsClient->setBucketQuota ([
			'Bucket' => $bucketName,
			'StorageQuota' => 1024 * 1024 * 1024//Set bucket quota to 1GB
	]);

	$resp = $obsClient->getBucketQuota ([
			'Bucket' => $bucketName
	]);
	printf ("Getting bucket quota:%s\n\n", $resp ['StorageQuota'] );
}

function getBucketQuota()
{
	global $obsClient;
	global $bucketName;
	$resp = $obsClient->getBucketQuota ([
			'Bucket' => $bucketName
	]);
	printf ("Getting bucket quota:%s\n\n", $resp ['StorageQuota'] );
}

function doBucketVersioningOperation()
{
	global $obsClient;
	global $bucketName;

	$resp = $obsClient->getBucketVersioningConfiguration ( [
			'Bucket' => $bucketName
	]);
	printf ( "Getting bucket versioning config:%s\n\n", $resp ['Status']);
	//Enable bucket versioning
	$obsClient->setBucketVersioningConfiguration ([
			'Bucket' => $bucketName,
			'Status' => 'Enabled'
	]);
	$resp = $obsClient->getBucketVersioningConfiguration ( [
			'Bucket' => $bucketName
	]);
	printf ( "Current bucket versioning config:%s\n\n", $resp ['Status']);

	//Suspend bucket versioning
	$obsClient->setBucketVersioningConfiguration ([
			'Bucket' => $bucketName,
			'Status' => 'Suspended'
	]);
	$resp = $obsClient->getBucketVersioningConfiguration ( [
			'Bucket' => $bucketName
	]);
	printf ( "Current bucket versioning config:%s\n\n", $resp ['Status']);
}

function doBucketAclOperation()
{
	global $obsClient;
	global $bucketName;
	printf ("Setting bucket ACL to ". ObsClient::AclPublicRead. "\n\n");
	$obsClient->setBucketAcl ([
			'Bucket' => $bucketName,
			'ACL' => ObsClient::AclPublicRead,
	]);

	$resp = $obsClient->getBucketAcl ([
			'Bucket' => $bucketName
	]);
	printf ("Getting bucket ACL:%s\n\n", json_encode($resp -> toArray()));

	printf ("Setting bucket ACL to ". ObsClient::AclPrivate. "\n\n");

	$obsClient->setBucketAcl ([
			'Bucket' => $bucketName,
			'ACL' => ObsClient::AclPrivate,
	]);
	$resp = $obsClient->getBucketAcl ([
			'Bucket' => $bucketName
	]);
	printf ("Getting bucket ACL:%s\n\n", json_encode($resp -> toArray()));
	return $resp ['Owner'] ['ID'];
}

function doBucketCorsOperation()
{
	global $obsClient;
	global $bucketName;
	printf ("Setting bucket CORS\n\n");
	$obsClient->setBucketCors ( [
			'Bucket' => $bucketName,
			'CorsRule' => [
					[
							'AllowedMethod' => ['HEAD', 'GET', 'PUT'],
							'AllowedOrigin' => ['http://www.a.com', 'http://www.b.com'],
							'AllowedHeader'=> ['Authorization'],
							'ExposeHeaders' => ['x-obs-test1', 'x-obs-test2'],
							'MaxAgeSeconds' => 100
					]
			]
	] );
	printf ("Getting bucket CORS:%s\n\n", json_encode($obsClient-> getBucketCors(['Bucket' => $bucketName])-> toArray()));

}

function optionsBucket()
{
	global $obsClient;
	global $bucketName;

	$resp = $obsClient->optionsBucket([
			'Bucket'=>$bucketName,
			'Origin'=>'http://www.a.com',
			'AccessControlRequestMethods' => ['PUT'],
			'AccessControlRequestHeaders'=> ['Authorization']
	]);
	printf ("Options bucket: %s\n\n", json_encode($resp -> toArray()));

}

function getBucketMetadata()
{
	global $obsClient;
	global $bucketName;
	printf ("Getting bucket metadata\n\n");

	$resp = $obsClient->getBucketMetadata ( [
			"Bucket" => $bucketName,
			"Origin" => "http://www.a.com",
			"RequestHeader" => "Authorization"
	] );
	printf ( "\tHttpStatusCode:%s\n", $resp ['HttpStatusCode'] );
	printf ( "\tStorageClass:%s\n", $resp ["StorageClass"] );
	printf ( "\tAllowOrigin:%s\n", $resp ["AllowOrigin"] );
	printf ( "\tMaxAgeSeconds:%s\n", $resp ["MaxAgeSeconds"] );
	printf ( "\tExposeHeader:%s\n", $resp ["ExposeHeader"] );
	printf ( "\tAllowHeader:%s\n", $resp ["AllowHeader"] );
	printf ( "\tAllowMethod:%s\n", $resp ["AllowMethod"] );

	printf ("Deleting bucket CORS\n\n");
	$obsClient -> deleteBucketCors(['Bucket' => $bucketName]);
}

function doBucketLifecycleOperation()
{
	global $obsClient;
	global $bucketName;

	$ruleId0 = "delete obsoleted files";
	$matchPrefix0 = "obsoleted/";
	$ruleId1 = "delete temporary files";
	$matchPrefix1 = "temporary/";
	$ruleId2 = "delete temp files";
	$matchPrefix2 = "temp/";

	printf ("Setting bucket lifecycle\n\n");

	$obsClient->setBucketLifecycleConfiguration ( [
			'Bucket' => $bucketName,
			'Rules' => [
					[
							'ID' => $ruleId0,
							'Prefix' => $matchPrefix0,
							'Status' => 'Enabled',
							'Expiration'=> ['Days'=>5]
					],
					[
							'ID' => $ruleId1,
							'Prefix' => $matchPrefix1,
							'Status' => 'Enabled',
							'Expiration' => ['Date' => '2017-12-31T00:00:00Z']
					],
					[
							'ID' => $ruleId2,
							'Prefix' => $matchPrefix2,
							'Status' => 'Enabled',
							'NoncurrentVersionExpiration' => ['NoncurrentDays' => 10]
					]
			]
	]);

	printf ("Getting bucket lifecycle\n\n");

	$resp = $obsClient->getBucketLifecycleConfiguration ([
			'Bucket' => $bucketName
	]);

	$i = 0;
	foreach ( $resp ['Rules'] as $rule ) {
		printf ( "\tRules[$i][Expiration][Date]:%s,Rules[$i][Expiration][Days]:%d\n", $rule ['Expiration'] ['Date'], $rule ['Expiration'] ['Days'] );
		printf ( "\yRules[$i][NoncurrentVersionExpiration][NoncurrentDays]:%s\n", $rule ['NoncurrentVersionExpiration'] ['NoncurrentDays'] );
		printf ( "\tRules[$i][ID]:%s,Rules[$i][Prefix]:%s,Rules[$i][Status]:%s\n", $rule ['ID'], $rule ['Prefix'], $rule ['Status'] );
		$i ++;
	}

	printf ("Deleting bucket lifecycle\n\n");
	$obsClient->deleteBucketLifecycleConfiguration (['Bucket' => $bucketName]);
}

function doBucketLoggingOperation($ownerId)
{
	global $obsClient;
	global $bucketName;

	printf ("Setting bucket ACL, give the log-delivery group " . ObsClient::PermissionWrite ." and " .ObsClient::PermissionReadAcp ." permissions\n\n");

	$obsClient->setBucketAcl ([
			'Bucket' => $bucketName,
			'Owner' => [
					'ID' => $ownerId
			],
			'Grants' => [
					[
							'Grantee' => [
							        'URI' => ObsClient::GroupLogDelivery,
									'Type' => 'Group'
							],
					       'Permission' => ObsClient::PermissionWrite
					],
					[
							'Grantee' => [
							        'URI' => ObsClient::GroupLogDelivery,
									'Type' => 'Group'
							],
					       'Permission' => ObsClient::PermissionReadAcp
					],
			]
	]);

	printf ("Setting bucket logging\n\n");

	$targetBucket = $bucketName;
	$targetPrefix = 'log-';

	$obsClient->setBucketLoggingConfiguration ( [
			'Bucket' => $bucketName,
			'LoggingEnabled' => [
					'TargetBucket' => $targetBucket,
					'TargetPrefix' => $targetPrefix,
					'TargetGrants' => [
							[
									'Grantee' => [
									        'URI' => ObsClient::GroupAuthenticatedUsers,
											'Type' => 'Group'
									],
									'Permission' => ObsClient::PermissionRead
							]
					]
			]
	]);

	printf ("Getting bucket logging\n");

	$resp = $obsClient->getBucketLoggingConfiguration ([
			'Bucket' => $bucketName
	]);

	printf ("\tTarget bucket=%s, target prefix=%s\n", $resp ['LoggingEnabled'] ['TargetBucket'], $resp ['LoggingEnabled'] ['TargetPrefix'] );
	printf("\tTargetGrants=%s\n\n", json_encode($resp ['LoggingEnabled'] ['TargetGrants']));

	printf ("Deletting bucket logging\n");

	$obsClient->setBucketLoggingConfiguration ( [
			'Bucket' => $bucketName
	]);
}

function doBucketWebsiteOperation()
{
	global $obsClient;
	global $bucketName;

	printf ("Setting bucket website\n\n");

	$obsClient->setBucketWebsiteConfiguration ([
			'Bucket' => $bucketName,
			'IndexDocument' => [
					'Suffix' => 'index.html'
			],
			'ErrorDocument' => [
					'Key' => 'error.html'
			]
	]);
	printf ("Getting bucket website\n");

	$resp = $obsClient->GetBucketWebsiteConfiguration ( [
			'Bucket' => $bucketName
	]);

	printf ("\tIndex document=%s, error document=%s\n\n", $resp ['IndexDocument'] ['Suffix'], $resp ['ErrorDocument'] ['Key']);
	printf ("Deletting bucket website\n");

	$obsClient->deleteBucketWebsiteConfiguration ([
			'Bucket' => $bucketName
	]);
}

function doBucketTaggingOperation()
{
	global $obsClient;
	global $bucketName;
	printf ("Setting bucket tagging\n\n");
	$obsClient -> setBucketTagging([
			'Bucket' => $bucketName,
			'TagSet' => [
					[
							'Key' => 'testKey1',
							'Value' => 'testValue1'
					],
					[
							'Key' => 'testKey2',
							'Value' => 'testValue2'
					]
			]
	]);
	printf ("Getting bucket tagging\n");

	$resp = $obsClient -> getBucketTagging(['Bucket' => $bucketName]);

	printf ("\t%s\n\n", json_encode($resp->toArray()));

	printf ("Deletting bucket tagging\n\n");

	$obsClient -> deleteBucketTagging(['Bucket' => $bucketName]);
}

function deleteBucket()
{

	global $obsClient;
	global $bucketName;

	$resp = $obsClient->deleteBucket ([
			'Bucket' => $bucketName
	] );
	printf("Deleting bucket %s successfully!\n\n", $bucketName);
	printf("HttpStatusCode:%s\n\n", $resp ['HttpStatusCode']);
}

// get bucket storage policy
function GetBucketStoragePolicy() {
    global $obsClient;
    global $bucketName;
    echo "get bucket storage policy start...\n";
    try {
        $resp = $obsClient->getBucketStoragePolicy(array (
                'Bucket' => $bucketName
        ));
        printf("HttpStatusCode:%s\n", $resp ['HttpStatusCode']);
        printf("RequestId:%s\n", $resp ['RequestId']);
        printf("StorageClass:%s\n", $resp ['StorageClass']);
    } catch ( ObsException $e ) {
        echo $e;
    }
}

function ListObjects() {
    global $obsClient;
    global $bucketName;
    echo "list objects start...<br/>\n";
    try {
        $resp = $obsClient->listObjects(array (
                'Bucket' => $bucketName,
                'Delimiter' => '',
                'Marker' => '',
                'MaxKeys' => '',
                'Prefix' => 'TIS/'
        ));
        //printf("HttpStatusCode:%s\n", $resp ['HttpStatusCode']);
        //printf("RequestId:%s\n", $resp ['RequestId']);
        printf("IsTruncated:%d,Marker:%s,NextMarker:%s,Name:%s\n", $resp ['IsTruncated'], $resp ['Marker'], $resp ['NextMarker'], $resp ['Name']);
				echo "<br>";
        printf("Prefix:%s,Delimiter:%s,MaxKeys:%d\n", $resp ['Prefix'], $resp ['Delimiter'], $resp ['MaxKeys']);
        $i = 0;
				echo "<HR>Commonprefix<br>";
        foreach ( $resp ['CommonPrefixes'] as $CommonPrefixe ) {
            printf("CommonPrefixes[$i][Prefix]:%s\n", $CommonPrefixe ['Prefix']);
            $i ++;
						echo "<BR>";
        }
        $i = 0;
				echo "<HR>content<br/>";
        foreach ( $resp ['Contents'] as $content ) {
            printf("Contents[$i][ETag]:%s,Contents[$i][Size]:%d,Contents[$i][StorageClass]:%s\n", $content ['ETag'], $content ['Size'], $content ['StorageClass']);
						echo "<BR>";
            printf("Contents[$i][Key]:%s,Contents[$i][LastModified]:%s\n", $content ['Key'], $content ['LastModified']);
						echo "<BR>";
            printf("Contents[$i][Owner][ID]:%s\n", $content ['Owner'] ['ID']);
            $i ++;
						echo "<BR><BR>";
        }
    } catch ( ObsException $e ) {
        echo $e;
    }
}
