<?php
#define('SCS_ACCESS_KEY', 'oP3ViaO5botUNfXf1FuXmg==');
#define('SCS_SECRET_KEY', 'KXoqL/IbRs3T8Dzk8ENGMw==');

# online qianjie account
#define('SCS_ACCESS_KEY', 'jzCMCzNpyTz+KNgLkXbg5Q==');
#define('SCS_SECRET_KEY', 'DIOCNl2f0wVklBJK9IjLmg==');

define('SCS_ACCESS_KEY', 'ETYeNGyviw36eFeVD7PztQ==');
define('SCS_SECRET_KEY', 'yEWJXRIa7kBp8ESmpU4YyA==');
// require the SCS SDK for PHP library
require 'scs-autoloader.php';

use SohuCS\Scs\ScsClient;

// Establish connection with DreamObjects with an Scs client.
$client = ScsClient::factory(array(
    'key'      => SCS_ACCESS_KEY,
    'secret'   => SCS_SECRET_KEY,
	'region'   => "bjctc"
));


/*
 * =====================Bucket Operations=================================
 */
/*
// HeadBucket
$bucket = 'example';
$result = $client->getBucketLocation(array(
    // Bucket is required
    'Bucket' => $bucket,
));
echo $result['Location'] . "\n";

$exist = $client->doesBucketExist("huake1");
echo $exist==true?"exist \n":"not exist \n";
*/

/*
// Create a valid bucket and use a LocationConstraint
$bucket = 'huake1';
$result = $client->createBucket(array(
	'Bucket' => $bucket,
	'LocationConstraint' => 'bjcnc.scs.sohucs.com'
));
// Get the request ID
echo $result['RequestId'] . "\n";

// Poll the bucket until it is accessible
$client->waitUntilBucketExists(array('Bucket' => $bucket));
*/


/*// ListBuckets
$blist = $client->listBuckets();
echo "   Buckets belonging to " . $blist['Owner']['ID'] . ":\n";
echo $blist;
$iterator = $client->getIterator('ListBuckets', array());

foreach ($iterator as $object) {
    echo $object['Name'] . "    ";
	echo $object['Region'] . "\n";
}
*/

// DeleteBucket
// Delete the objects in the bucket before attempting to delete
// the bucket
/*$bucket = 'huake1';
//$client->clearBucket($bucket);

// Delete the bucket
$client->deleteBucket(array('Bucket' => $bucket));

// Wait until the bucket is not accessible
$client->waitUntilBucketNotExists(array('Bucket' => $bucket));
*/

/*// set version enable or not for bucket
$result = $client->putBucketVersioning(array(
    // Bucket is required
    'Bucket' => $bucket,
    'Status' => 'Suspended'
));
$result = $client->getBucketVersioning(array(
    // Bucket is required
    'Bucket' => $bucket,
));
echo $result['Status']."\n";
*/

/*
// Get Bucket Location
$result = $client->getBucketLocation(array(
    // Bucket is required
    'Bucket' => $bucket,
));
echo $result['Location'] . "\n";
*/
/*
// ListObjects
$result = $client->listObjects(array(
    // Bucket is required
    'Bucket' => $bucket,
    //'Delimiter' => 'string',
    //'Marker' => 'string',
    'MaxKeys' => 2,
    'Prefix' => 'img',
));
echo $result . "\n";

$iterator = $client->getIterator('ListObjects', array(
    'Bucket' => $bucket
));

foreach ($iterator as $object) {
    echo $object['Key'] . "\n";
}*/

/*
// ListObjectVersions
$result = $client->listObjectVersions(array(
    // Bucket is required
    'Bucket' => $bucket,
    //'Delimiter' => 'string',
    'KeyMarker' => '1395281485261.jpg',
    'MaxKeys' => 100,
    //'Prefix' => 'string',
    'VersionIdMarker' => 'OTIyMzM3MjAzNjg1NDc3NTgwNyM4ZmYy',
));

echo $result . "\n";

$iterator = $client->getIterator('ListObjectVersions', array(
    'Bucket' => $bucket
));

foreach ($iterator as $object) {
    echo $object['Key'] . "\n";
	echo $object['VersionId'] . "\n";
}
*/

/*
// ListMultipartUploads
$result = $client->listMultipartUploads(array(
    // Bucket is required
    'Bucket' => $bucket,
    'Delimiter' => 'string',
    'KeyMarker' => 'string',
    'MaxUploads' => integer,
    'Prefix' => 'string',
    'UploadIdMarker' => 'string',
));

echo $result;
*/

/*
 * =====================Object Operations=================================
 */
$bucket = 'wangrenfei-bjctc';

/*// Upload an object to Sohu Cloud Service
$result = $client->putObject(array(
    'Bucket' => $bucket,
    'Key'    => 'data.txt',
    'Body'   => 'Hello!'
));

// Access parts of the result object
echo $result['VersionId'] . "\n";
echo $result['RequestId'] . "\n";

// Get the URL the object can be downloaded from
echo $result['ObjectURL'] . "\n";
*/

// Upload an object by streaming the contents of a file
// $pathToFile should be absolute path to a file on disk

$result = $client->putObject(array(
    'Bucket'     => $bucket,
    'Key'        => 'gjfs04/M03/B9/43/wKhzK1GRtu6DYQopAAFcNPzQV945,4_800-600_9-0.jpg',
    'SourceFile' => 'D:\\asdfgh.jpg',
    'Metadata'   => array(
        'Foo' => 'abc',
        'Baz' => '123'
    )
));

// We can poll the object until it is accessible
$client->waitUntilObjectExists(array(
    'Bucket' => $bucket,
    'Key'    => 'gjfs04/M03/B9/43/wKhzK1GRtu6DYQopAAFcNPzQV945,4_800-600_9-0.jpg'
));

$exist = $client->doesObjectExist($bucket, 'gjfs04/M03/B9/43/wKhzK1GRtu6DYQopAAFcNPzQV945,4_800-600_9-0.jpg');
echo $exist==true?"exist \n":"not exist \n";


/*
// GetObject -- Read String
// Get an object using the getObject operation
$result = $client->getObject(array(
    'Bucket' => $bucket,
    'Key'    => 'data.txt'
));

// The 'Body' value of the result is an EntityBody object
echo get_class($result['Body']) . "\n";
// > Guzzle\Http\EntityBody

// The 'Body' value can be cast to a string
echo $result['Body'] . "\n";
// 
// Seek to the beginning of the stream
$result['Body']->rewind();

// Read the body off of the underlying stream in chunks
while ($data = $result['Body']->read(1024)) {
    echo $data;
}

// Cast the body to a primitive string
// Warning: This loads the entire contents into memory!
$bodyAsString = (string) $result['Body'];
echo $bodyAsString;
*/

/*
// GetObject -- Save As file 
$result = $client->getObject(array(
    'Bucket' => $bucket,
    'Key'    => 'test.zip',
	'SaveAs' => 'D:\\test1.zip',
	'Range'  => "bytes=0-1000000"
));

// Contains an EntityBody that wraps a file resource of /tmp/data.txt
//foreach($result['Metadata'] as $key=>$value)
//	echo $key."=>".$value ;
echo $result;

*/

/*
// DeleteObject
$result = $client->deleteObject(array(
    // Bucket is required
    'Bucket' => $bucket,
    // Key is required
    'Key' => '1395281485261.jpg',
	'VersionId' => 'OTIyMzM3MjAzNjg1NDc3NTgwNyM4ZmYy'
));

echo $result . "\n";
*/

/*
// deleteObjects
$result = $client->deleteObjects(array(
    // Bucket is required
    'Bucket' => $bucket,
    // Objects is required
    'Objects' => array(
        array(
            // Key is required
            'Key' => 'favicon2.ico',
        ),
		array(
            // Key is required
            'Key' => 'example.png',
			'VersionId' => 'OTIyMzM3MjAzNjg1NDc3NTgwNyM0MTQ4'
        ),
        // ... repeated
    ),
    'Quiet' => false,
));

echo $result . "\n";
*/

/*
// Copy Object
$result = $client->copyObject(array(
    // Bucket is required
    'Bucket' => $bucket,
    // CopySource is required
    'CopySource' => '/huake1/example.png',
	'VersionId' => 'OTIyMzM3MjAzNjg1NDc3NTgwNiM2YjAw',
    // Key is required
    'Key' => 'test.png'
));

echo $result . "\n";
*/


/*
$result = $client->listMultipartUploads(array(
    // Bucket is required
    'Bucket' => $bucket,
    //'Delimiter' => 'string',
    //'EncodingType' => 'string',
    //'KeyMarker' => 'test.zip',
    //'MaxUploads' => integer,
    //'Prefix' => 'string',
   // 'UploadIdMarker' => '21219c55-9614-449c-8c6b-3768f96a91e8',
));

echo $result . "\n";

// abortMultipartUpload
$result = $client->abortMultipartUpload(array(
    // Bucket is required  
    'Bucket' => $bucket,
    // Key is required
    'Key' => 'test.zip',
    // UploadId is required
    'UploadId' => '4b2690b8-e64a-4a9c-9c7d-cad882dd9098',
));

echo $result . "\n";*/



echo "finish";
