<?php

// Include the SCS SDK using the Composer autoloader
require 'scs-autoloader.php';

use SohuCS\Scs\ScsClient;

$bucket = 'huake1';
$keyname = 'test.zip';
$filename = 'D:\\test.zip';

// 1. Instantiate the client.
$client = ScsClient::factory(array(
    'key'    => 'ETYeNGyviw36eFeVD7PztQ==',
    'secret' => 'yEWJXRIa7kBp8ESmpU4YyA=='
));

// 2. Create a new multipart upload and get the upload ID.
$result = $client->createMultipartUpload(array(
    'Bucket'            => $bucket,
    'Key'                  => $keyname,
));
$uploadId = $result['UploadId'];

// 3. Upload the file in parts.
try {    
    $file = fopen($filename, 'r');
    $parts = array();
    $partNumber = 1;
    while (!feof($file)) {
        $result = $client->uploadPart(array(
            'Bucket'     => $bucket,
            'Key'        => $keyname,
            'UploadId'   => $uploadId,
            'PartNumber' => $partNumber,
            'Body'       => fread($file, 5 * 1024 * 1024),
        ));
        $parts[] = array(
            'PartNumber' => $partNumber++,
            'ETag'       => $result['ETag'],
        );

        echo "Uploading part {$partNumber} of {$filename}.\n";
    }
    fclose($file);
} catch (ScsClientException $e) {
    $result = $client->abortMultipartUpload(array(
        'Bucket'   => $bucket,
        'Key'      => $keyname,
        'UploadId' => $uploadId
    ));

    echo "Upload of {$filename} failed.\n";
}

// 4. Complete multipart upload.
$result = $client->completeMultipartUpload(array(
    'Bucket'   => $bucket,
    'Key'      => $keyname,
    'UploadId' => $uploadId,
    'Parts'    => $parts,
));
$url = $result['Location'];

echo "Uploaded {$filename} to {$url}.\n";