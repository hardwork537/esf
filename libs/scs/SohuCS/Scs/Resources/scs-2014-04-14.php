<?php
/**
 * Copyright 2010-2013 Sohu.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://aws.amazon.com/apache2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

return array (
    'apiVersion' => '2014-04-14',
    'endpointPrefix' => 'scs',
    'serviceFullName' => 'Sohu Cloud Service',
    'serviceAbbreviation' => 'Sohu SCS',
    'serviceType' => 'rest-xml',
    'timestampFormat' => 'rfc822',
    'globalEndpoint' => 'bjcnc.scs.sohucs.com',
    'signatureVersion' => 'scs',
    'namespace' => 'Scs',
    'regions' => array(
        'bjcnc' => array(
            'http' => true,
            'https' => false,
            'hostname' => 'bjcnc.scs.sohucs.com',
        ),
        'bjctc' => array(
            'http' => true,
            'https' => false,
            'hostname' => 'bjctc.scs.sohucs.com',
        ),
        'shctc' => array(
            'http' => true,
            'https' => false,
            'hostname' => 'shctc.scs.sohucs.com',
        ),
		'gzctc' => array(
            'http' => true,
            'https' => false,
            'hostname' => 'gzctc.scs.sohucs.com',
        ),
    ),
    'operations' => array(
        'HeadBucket' => array(
            'httpMethod' => 'HEAD',
            'uri' => '/{Bucket}',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'HeadBucketOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'The specified bucket does not exist.',
                    'class' => 'NoSuchBucketException',
                ),
            ),
        ),
		'CreateBucket' => array(
            'httpMethod' => 'PUT',
            'uri' => '/{Bucket}',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'CreateBucketOutput',
            'responseType' => 'model',
            'data' => array(
                'xmlRoot' => array(
                    'name' => 'CreateBucketConfiguration',
                    'namespaces' => array(
                    ),
                ),
            ),
            'parameters' => array(
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
				'LocationConstraint' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'The requested bucket name is not available. The bucket namespace is shared by all users of the system. Please select a different name and try again.',
                    'class' => 'BucketAlreadyExistsException',
                ),
            ),
        ),
		'ListBuckets' => array(
            'httpMethod' => 'GET',
            'uri' => '/',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'ListBucketsOutput',
            'responseType' => 'model',
            'parameters' => array(
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/xml',
                ),
            ),
        ),
		'DeleteBucket' => array(
            'httpMethod' => 'DELETE',
            'uri' => '/{Bucket}',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'DeleteBucketOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
            ),
        ),
		'PutBucketVersioning' => array(
            'httpMethod' => 'PUT',
            'uri' => '/{Bucket}?versioning',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'PutBucketVersioningOutput',
            'responseType' => 'model',
            'data' => array(
                'xmlRoot' => array(
                    'name' => 'VersioningConfiguration',
                    'namespaces' => array(
                    ),
                ),
            ),
            'parameters' => array(
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
                'Status' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
            ),
        ),
		'GetBucketVersioning' => array(
            'httpMethod' => 'GET',
            'uri' => '/{Bucket}?versioning',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'GetBucketVersioningOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/xml',
                ),
            ),
        ),
		'GetBucketLocation' => array(
            'httpMethod' => 'GET',
            'uri' => '/{Bucket}?location',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'GetBucketLocationOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
            ),
        ),
		'ListObjects' => array(
            'httpMethod' => 'GET',
            'uri' => '/{Bucket}',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'ListObjectsOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
                'Delimiter' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'delimiter',
                ),
                'EncodingType' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'encoding-type',
                ),
                'Marker' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'marker',
                ),
                'MaxKeys' => array(
                    'type' => 'numeric',
                    'location' => 'query',
                    'sentAs' => 'max-keys',
                ),
                'Prefix' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'prefix',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/xml',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'The specified bucket does not exist.',
                    'class' => 'NoSuchBucketException',
                ),
            ),
        ),
		'ListObjectVersions' => array(
            'httpMethod' => 'GET',
            'uri' => '/{Bucket}?versions',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'ListObjectVersionsOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
                'Delimiter' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'delimiter',
                ),
                'EncodingType' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'encoding-type',
                ),
                'KeyMarker' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'key-marker',
                ),
                'MaxKeys' => array(
                    'type' => 'numeric',
                    'location' => 'query',
                    'sentAs' => 'max-keys',
                ),
                'Prefix' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'prefix',
                ),
                'VersionIdMarker' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'version-id-marker',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/xml',
                ),
            ),
        ),
		'ListMultipartUploads' => array(
            'httpMethod' => 'GET',
            'uri' => '/{Bucket}?uploads',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'ListMultipartUploadsOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
                'Delimiter' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'delimiter',
                ),
                'EncodingType' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'encoding-type',
                ),
                'KeyMarker' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'key-marker',
                ),
                'MaxUploads' => array(
                    'type' => 'numeric',
                    'location' => 'query',
                    'sentAs' => 'max-uploads',
                ),
                'Prefix' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'prefix',
                ),
                'UploadIdMarker' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'upload-id-marker',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/xml',
                ),
            ),
        ),
		'PutObject' => array(
            'httpMethod' => 'PUT',
            'uri' => '/{Bucket}{/Key*}',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'PutObjectOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Body' => array(
                    'type' => array(
                        'string',
                        'object',
                    ),
                    'location' => 'body',
                ),
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
                'CacheControl' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Cache-Control',
                ),
                'ContentDisposition' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Disposition',
                ),
                'ContentEncoding' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Encoding',
                ),
                'ContentLanguage' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Language',
                ),
                'ContentLength' => array(
                    'type' => 'numeric',
                    'location' => 'header',
                    'sentAs' => 'Content-Length',
                ),
                /*'ContentMD5' => array(
                    'type' => array(
                        'string',
                        'boolean',
                    ),
                    'location' => 'header',
                    'sentAs' => 'Content-MD5',
				),*/ // not supported 
                'ContentType' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Type',
                ),
                'Expires' => array(
                    'type' => array(
                        'object',
                        'string',
                        'integer',
                    ),
                    'format' => 'date-time-http',
                    'location' => 'header',
                ),
                'Key' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                    'filters' => array(
                        'SohuCS\\Scs\\ScsClient::explodeKey',
                    ),
                ),
                'Metadata' => array(
                    'type' => 'object',
                    'location' => 'header',
                    'sentAs' => 'x-scs-meta-',
                    'additionalProperties' => array(
                        'type' => 'string',
                    ),
                ),
                'ServerSideEncryption' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-server-side-encryption',
                ), // not supported 
                'StorageClass' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-storage-class',
                ),
                'WebsiteRedirectLocation' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-website-redirect-location',
				), // not supported 
            ),
        ),
		'HeadObject' => array(
            'httpMethod' => 'HEAD',
            'uri' => '/{Bucket}{/Key*}',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'HeadObjectOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
                'IfMatch' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'If-Match',
                ),
                'IfModifiedSince' => array(
                    'type' => array(
                        'object',
                        'string',
                        'integer',
                    ),
                    'format' => 'date-time-http',
                    'location' => 'header',
                    'sentAs' => 'If-Modified-Since',
                ),
                'IfNoneMatch' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'If-None-Match',
                ),
                'IfUnmodifiedSince' => array(
                    'type' => array(
                        'object',
                        'string',
                        'integer',
                    ),
                    'format' => 'date-time-http',
                    'location' => 'header',
                    'sentAs' => 'If-Unmodified-Since',
                ),
                'Key' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                    'filters' => array(
                        'SohuCS\\Scs\\ScsClient::explodeKey',
                    ),
                ),
                'Range' => array(
                    'type' => 'string',
                    'location' => 'header',
                ),
                'VersionId' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'versionId',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'The specified key does not exist.',
                    'class' => 'NoSuchKeyException',
                ),
            ),
        ),
		'GetObject' => array(
            'httpMethod' => 'GET',
            'uri' => '/{Bucket}{/Key*}',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'GetObjectOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
                'IfMatch' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'If-Match',
                ),
                'IfModifiedSince' => array(
                    'type' => array(
                        'object',
                        'string',
                        'integer',
                    ),
                    'format' => 'date-time-http',
                    'location' => 'header',
                    'sentAs' => 'If-Modified-Since',
                ),
                'IfNoneMatch' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'If-None-Match',
                ),
                'IfUnmodifiedSince' => array(
                    'type' => array(
                        'object',
                        'string',
                        'integer',
                    ),
                    'format' => 'date-time-http',
                    'location' => 'header',
                    'sentAs' => 'If-Unmodified-Since',
                ),
                'Key' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                    'filters' => array(
                        'SohuCS\\Scs\\ScsClient::explodeKey',
                    ),
                ),
                'Range' => array(
                    'type' => 'string',
                    'location' => 'header',
                ),
                'ResponseCacheControl' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'response-cache-control',
                ),
                'ResponseContentDisposition' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'response-content-disposition',
                ),
                'ResponseContentEncoding' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'response-content-encoding',
                ),
                'ResponseContentLanguage' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'response-content-language',
                ),
                'ResponseContentType' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'response-content-type',
                ),
                'ResponseExpires' => array(
                    'type' => array(
                        'object',
                        'string',
                        'integer',
                    ),
                    'format' => 'date-time-http',
                    'location' => 'query',
                    'sentAs' => 'response-expires',
                ),
                'VersionId' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'versionId',
                ),
                'SaveAs' => array(
                    'location' => 'response_body',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'The specified key does not exist.',
                    'class' => 'NoSuchKeyException',
                ),
            ),
        ),
		'DeleteObject' => array(
            'httpMethod' => 'DELETE',
            'uri' => '/{Bucket}{/Key*}',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'DeleteObjectOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
                'Key' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                    'filters' => array(
                        'SohuCS\\Scs\\ScsClient::explodeKey',
                    ),
                ),
                'VersionId' => array(
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'versionId',
                ),
            ),
        ),
		'DeleteObjects' => array(
            'httpMethod' => 'POST',
            'uri' => '/{Bucket}?delete',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'DeleteObjectsOutput',
            'responseType' => 'model',
            'data' => array(
                'xmlRoot' => array(
                    'name' => 'Delete',
                    'namespaces' => array(
                    ),
                ),
            ),
            'parameters' => array(
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
                'Objects' => array(
                    'required' => true,
                    'type' => 'array',
                    'location' => 'xml',
                    'data' => array(
                        'xmlFlattened' => true,
                    ),
                    'items' => array(
                        'type' => 'object',
                        'sentAs' => 'Object',
                        'properties' => array(
                            'Key' => array(
                                'required' => true,
                                'type' => 'string',
                            ),
                            'VersionId' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'Quiet' => array(
                    'type' => 'boolean',
                    'format' => 'boolean-string',
                    'location' => 'xml',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/xml',
                ),
            ),
        ),
		'CopyObject' => array(
            'httpMethod' => 'PUT',
            'uri' => '/{Bucket}{/Key*}',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'CopyObjectOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
                'CacheControl' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Cache-Control',
                ),
                'ContentDisposition' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Disposition',
                ),
                'ContentEncoding' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Encoding',
                ),
                'ContentLanguage' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Language',
                ),
                'ContentType' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Type',
                ),
                'CopySource' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-copy-source',
                ),
				'VersionId' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-version-id',
                ),
                'CopySourceIfMatch' => array(
                    'type' => array(
                        'object',
                        'string',
                        'integer',
                    ),
                    'format' => 'date-time-http',
                    'location' => 'header',
                    'sentAs' => 'x-scs-copy-source-if-match',
                ),
                'CopySourceIfModifiedSince' => array(
                    'type' => array(
                        'object',
                        'string',
                        'integer',
                    ),
                    'format' => 'date-time-http',
                    'location' => 'header',
                    'sentAs' => 'x-scs-copy-source-if-modified-since',
                ),
                'CopySourceIfNoneMatch' => array(
                    'type' => array(
                        'object',
                        'string',
                        'integer',
                    ),
                    'format' => 'date-time-http',
                    'location' => 'header',
                    'sentAs' => 'x-scs-copy-source-if-none-match',
                ),
                'CopySourceIfUnmodifiedSince' => array(
                    'type' => array(
                        'object',
                        'string',
                        'integer',
                    ),
                    'format' => 'date-time-http',
                    'location' => 'header',
                    'sentAs' => 'x-scs-copy-source-if-unmodified-since',
                ),
                'Expires' => array(
                    'type' => array(
                        'object',
                        'string',
                        'integer',
                    ),
                    'format' => 'date-time-http',
                    'location' => 'header',
                ),
                'Key' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                    'filters' => array(
                        'SohuCS\\Scs\\ScsClient::explodeKey',
                    ),
                ),
                'Metadata' => array(
                    'type' => 'object',
                    'location' => 'header',
                    'sentAs' => 'x-scs-meta-',
                    'additionalProperties' => array(
                        'type' => 'string',
                    ),
                ),
                'MetadataDirective' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-metadata-directive',
                ),
                'ServerSideEncryption' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-server-side-encryption',
                ),
                'StorageClass' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-storage-class',
                ),
                'WebsiteRedirectLocation' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-website-redirect-location',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/xml',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'The source object of the COPY operation is not in the active tier and is only stored in Scs Bucket.',
                    'class' => 'ObjectNotInActiveTierErrorException',
                ),
            ),
        ),
		'CreateMultipartUpload' => array(
            'httpMethod' => 'POST',
            'uri' => '/{Bucket}{/Key*}?uploads',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'CreateMultipartUploadOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
                'CacheControl' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Cache-Control',
                ),
                'ContentDisposition' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Disposition',
                ),
                'ContentEncoding' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Encoding',
                ),
                'ContentLanguage' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Language',
                ),
                'ContentType' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Type',
                ),
                'Expires' => array(
                    'type' => array(
                        'object',
                        'string',
                        'integer',
                    ),
                    'format' => 'date-time-http',
                    'location' => 'header',
                ),
                'Key' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                    'filters' => array(
                        'SohuCS\\Scs\\ScsClient::explodeKey',
                    ),
                ),
                'Metadata' => array(
                    'type' => 'object',
                    'location' => 'header',
                    'sentAs' => 'x-scs-meta-',
                    'additionalProperties' => array(
                        'type' => 'string',
                    ),
                ),
                'ServerSideEncryption' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-server-side-encryption',
                ),
                'StorageClass' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-storage-class',
                ),
                'WebsiteRedirectLocation' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-website-redirect-location',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/xml',
                ),
            ),
        ),
		'UploadPart' => array(
            'httpMethod' => 'PUT',
            'uri' => '/{Bucket}{/Key*}',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'UploadPartOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Body' => array(
                    'type' => array(
                        'string',
                        'object',
                    ),
                    'location' => 'body',
                ),
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
                'ContentLength' => array(
                    'type' => 'numeric',
                    'location' => 'header',
                    'sentAs' => 'Content-Length',
                ),
                'Key' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                    'filters' => array(
                        'SohuCS\\Scs\\ScsClient::explodeKey',
                    ),
                ),
                'PartNumber' => array(
                    'required' => true,
                    'type' => 'numeric',
                    'location' => 'query',
                    'sentAs' => 'partNumber',
                ),
                'UploadId' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'uploadId',
                ),
            ),
        ),
		'CompleteMultipartUpload' => array(
            'httpMethod' => 'POST',
            'uri' => '/{Bucket}{/Key*}',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'CompleteMultipartUploadOutput',
            'responseType' => 'model',
            'data' => array(
                'xmlRoot' => array(
                    'name' => 'CompleteMultipartUpload',
                    'namespaces' => array(
                    ),
                ),
            ),
            'parameters' => array(
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
                'Key' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                    'filters' => array(
                        'SohuCS\\Scs\\ScsClient::explodeKey',
                    ),
                ),
                'Parts' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'data' => array(
                        'xmlFlattened' => true,
                    ),
                    'items' => array(
                        'type' => 'object',
                        'sentAs' => 'Part',
                        'properties' => array(
                            'ETag' => array(
                                'type' => 'string',
                            ),
                            'PartNumber' => array(
                                'type' => 'numeric',
                            ),
                        ),
                    ),
                ),
                'UploadId' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'uploadId',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/xml',
                ),
            ),
        ),
		'AbortMultipartUpload' => array(
            'httpMethod' => 'DELETE',
            'uri' => '/{Bucket}{/Key*}',
            'class' => 'SohuCS\\Scs\\Command\\ScsCommand',
            'responseClass' => 'AbortMultipartUploadOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Bucket' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ),
                'Key' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                    'filters' => array(
                        'SohuCS\\Scs\\ScsClient::explodeKey',
                    ),
                ),
                'UploadId' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'query',
                    'sentAs' => 'uploadId',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'The specified multipart upload does not exist.',
                    'class' => 'NoSuchUploadException',
                ),
            ),
        ),
	),
	'models' => array(
        'AbortMultipartUploadOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'CompleteMultipartUploadOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'Bucket' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'ETag' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'Expiration' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-expiration',
                ),
                'Key' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'Location' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'ServerSideEncryption' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-server-side-encryption',
                ),
                'VersionId' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-version-id',
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'CopyObjectOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'ETag' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'LastModified' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'CopySourceVersionId' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-copy-source-version-id',
                ),
                'Expiration' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-expiration',
                ),
                'ServerSideEncryption' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-server-side-encryption',
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'CreateBucketOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                /*'Location' => array(
                    'type' => 'string',
                    'location' => 'header',
				),*/
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'CreateMultipartUploadOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'Bucket' => array(
                    'type' => 'string',
                    'location' => 'xml',
                    'sentAs' => 'Bucket',
                ),
                'Key' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'ServerSideEncryption' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-server-side-encryption',
                ),
                'UploadId' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'DeleteBucketOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'DeleteBucketCorsOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'DeleteBucketLifecycleOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'DeleteBucketPolicyOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'DeleteBucketTaggingOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'DeleteBucketWebsiteOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'DeleteObjectOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'DeleteMarker' => array(
                    'type' => 'boolean',
                    'location' => 'header',
                    'sentAs' => 'x-scs-delete-marker',
                ),
                'VersionId' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-version-id',
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'DeleteObjectsOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'Deleted' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'data' => array(
                        'xmlFlattened' => true,
                    ),
                    'items' => array(
                        'type' => 'object',
                        'properties' => array(
                            'DeleteMarker' => array(
                                'type' => 'boolean',
                            ),
                            'DeleteMarkerVersionId' => array(
                                'type' => 'string',
                            ),
                            'Key' => array(
                                'type' => 'string',
                            ),
                            'VersionId' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'Errors' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'sentAs' => 'Error',
                    'data' => array(
                        'xmlFlattened' => true,
                    ),
                    'items' => array(
                        'type' => 'object',
                        'sentAs' => 'Error',
                        'properties' => array(
                            'Code' => array(
                                'type' => 'string',
                            ),
                            'Key' => array(
                                'type' => 'string',
                            ),
                            'Message' => array(
                                'type' => 'string',
                            ),
                            'VersionId' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'GetBucketAclOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'Grants' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'sentAs' => 'AccessControlList',
                    'items' => array(
                        'name' => 'Grant',
                        'type' => 'object',
                        'sentAs' => 'Grant',
                        'properties' => array(
                            'Grantee' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'DisplayName' => array(
                                        'type' => 'string',
                                    ),
                                    'EmailAddress' => array(
                                        'type' => 'string',
                                    ),
                                    'ID' => array(
                                        'type' => 'string',
                                    ),
                                    'Type' => array(
                                        'type' => 'string',
                                        'sentAs' => 'xsi:type',
                                        'data' => array(
                                            'xmlAttribute' => true,
                                            'xmlNamespace' => 'http://www.w3.org/2001/XMLSchema-instance',
                                        ),
                                    ),
                                    'URI' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                            'Permission' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'Owner' => array(
                    'type' => 'object',
                    'location' => 'xml',
                    'properties' => array(
                        'DisplayName' => array(
                            'type' => 'string',
                        ),
                        'ID' => array(
                            'type' => 'string',
                        ),
                    ),
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'GetBucketCorsOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'CORSRules' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'sentAs' => 'CORSRule',
                    'data' => array(
                        'xmlFlattened' => true,
                    ),
                    'items' => array(
                        'type' => 'object',
                        'sentAs' => 'CORSRule',
                        'properties' => array(
                            'AllowedHeaders' => array(
                                'type' => 'array',
                                'sentAs' => 'AllowedHeader',
                                'data' => array(
                                    'xmlFlattened' => true,
                                ),
                                'items' => array(
                                    'type' => 'string',
                                    'sentAs' => 'AllowedHeader',
                                ),
                            ),
                            'AllowedMethods' => array(
                                'type' => 'array',
                                'sentAs' => 'AllowedMethod',
                                'data' => array(
                                    'xmlFlattened' => true,
                                ),
                                'items' => array(
                                    'type' => 'string',
                                    'sentAs' => 'AllowedMethod',
                                ),
                            ),
                            'AllowedOrigins' => array(
                                'type' => 'array',
                                'sentAs' => 'AllowedOrigin',
                                'data' => array(
                                    'xmlFlattened' => true,
                                ),
                                'items' => array(
                                    'type' => 'string',
                                    'sentAs' => 'AllowedOrigin',
                                ),
                            ),
                            'ExposeHeaders' => array(
                                'type' => 'array',
                                'sentAs' => 'ExposeHeader',
                                'data' => array(
                                    'xmlFlattened' => true,
                                ),
                                'items' => array(
                                    'type' => 'string',
                                    'sentAs' => 'ExposeHeader',
                                ),
                            ),
                            'MaxAgeSeconds' => array(
                                'type' => 'numeric',
                            ),
                        ),
                    ),
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'GetBucketLifecycleOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'Rules' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'sentAs' => 'Rule',
                    'data' => array(
                        'xmlFlattened' => true,
                    ),
                    'items' => array(
                        'type' => 'object',
                        'sentAs' => 'Rule',
                        'properties' => array(
                            'Expiration' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'Date' => array(
                                        'type' => 'string',
                                    ),
                                    'Days' => array(
                                        'type' => 'numeric',
                                    ),
                                ),
                            ),
                            'ID' => array(
                                'type' => 'string',
                            ),
                            'Prefix' => array(
                                'type' => 'string',
                            ),
                            'Status' => array(
                                'type' => 'string',
                            ),
                            'Transition' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'Date' => array(
                                        'type' => 'string',
                                    ),
                                    'Days' => array(
                                        'type' => 'numeric',
                                    ),
                                    'StorageClass' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'GetBucketLocationOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'Location' => array(
                    'type' => 'string',
                    'location' => 'body',
                    'filters' => array(
                        'strval',
                        'strip_tags',
                        'trim',
                    ),
                ),
            ),
        ),
        'GetBucketLoggingOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'LoggingEnabled' => array(
                    'type' => 'object',
                    'location' => 'xml',
                    'properties' => array(
                        'TargetBucket' => array(
                            'type' => 'string',
                        ),
                        'TargetGrants' => array(
                            'type' => 'array',
                            'items' => array(
                                'name' => 'Grant',
                                'type' => 'object',
                                'sentAs' => 'Grant',
                                'properties' => array(
                                    'Grantee' => array(
                                        'type' => 'object',
                                        'properties' => array(
                                            'DisplayName' => array(
                                                'type' => 'string',
                                            ),
                                            'EmailAddress' => array(
                                                'type' => 'string',
                                            ),
                                            'ID' => array(
                                                'type' => 'string',
                                            ),
                                            'Type' => array(
                                                'type' => 'string',
                                                'sentAs' => 'xsi:type',
                                                'data' => array(
                                                    'xmlAttribute' => true,
                                                    'xmlNamespace' => 'http://www.w3.org/2001/XMLSchema-instance',
                                                ),
                                            ),
                                            'URI' => array(
                                                'type' => 'string',
                                            ),
                                        ),
                                    ),
                                    'Permission' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                        ),
                        'TargetPrefix' => array(
                            'type' => 'string',
                        ),
                    ),
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'GetBucketNotificationOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'TopicConfiguration' => array(
                    'type' => 'object',
                    'location' => 'xml',
                    'properties' => array(
                        'Event' => array(
                            'type' => 'string',
                        ),
                        'Topic' => array(
                            'type' => 'string',
                        ),
                    ),
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'GetBucketPolicyOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'Policy' => array(
                    'type' => 'string',
                    'instanceOf' => 'Guzzle\\Http\\EntityBody',
                    'location' => 'body',
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'GetBucketRequestPaymentOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'Payer' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'GetBucketTaggingOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'TagSet' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'items' => array(
                        'name' => 'Tag',
                        'type' => 'object',
                        'sentAs' => 'Tag',
                        'properties' => array(
                            'Key' => array(
                                'type' => 'string',
                            ),
                            'Value' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'GetBucketVersioningOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'MFADelete' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'Status' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'GetBucketWebsiteOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'ErrorDocument' => array(
                    'type' => 'object',
                    'location' => 'xml',
                    'properties' => array(
                        'Key' => array(
                            'type' => 'string',
                        ),
                    ),
                ),
                'IndexDocument' => array(
                    'type' => 'object',
                    'location' => 'xml',
                    'properties' => array(
                        'Suffix' => array(
                            'type' => 'string',
                        ),
                    ),
                ),
                'RedirectAllRequestsTo' => array(
                    'type' => 'object',
                    'location' => 'xml',
                    'properties' => array(
                        'HostName' => array(
                            'type' => 'string',
                        ),
                        'Protocol' => array(
                            'type' => 'string',
                        ),
                    ),
                ),
                'RoutingRules' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'items' => array(
                        'name' => 'RoutingRule',
                        'type' => 'object',
                        'sentAs' => 'RoutingRule',
                        'properties' => array(
                            'Condition' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'HttpErrorCodeReturnedEquals' => array(
                                        'type' => 'string',
                                    ),
                                    'KeyPrefixEquals' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                            'Redirect' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'HostName' => array(
                                        'type' => 'string',
                                    ),
                                    'HttpRedirectCode' => array(
                                        'type' => 'string',
                                    ),
                                    'Protocol' => array(
                                        'type' => 'string',
                                    ),
                                    'ReplaceKeyPrefixWith' => array(
                                        'type' => 'string',
                                    ),
                                    'ReplaceKeyWith' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'GetObjectOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'AcceptRanges' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'accept-ranges',
                ),
                'Body' => array(
                    'type' => 'string',
                    'instanceOf' => 'Guzzle\\Http\\EntityBody',
                    'location' => 'body',
                ),
                'CacheControl' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Cache-Control',
                ),
                'ContentDisposition' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Disposition',
                ),
                'ContentEncoding' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Encoding',
                ),
                'ContentLanguage' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Language',
                ),
                'ContentLength' => array(
                    'type' => 'numeric',
                    'location' => 'header',
                    'sentAs' => 'Content-Length',
                ),
                'ContentType' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Type',
                ),
                'DeleteMarker' => array(
                    'type' => 'boolean',
                    'location' => 'header',
                    'sentAs' => 'x-scs-delete-marker',
                ),
                'ETag' => array(
                    'type' => 'string',
                    'location' => 'header',
                ),
                'Expiration' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-expiration',
                ),
                'Expires' => array(
                    'type' => 'string',
                    'location' => 'header',
                ),
                'LastModified' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Last-Modified',
                ),
                'Metadata' => array(
                    'type' => 'object',
                    'location' => 'header',
                    'sentAs' => 'x-scs-meta-',
                    'additionalProperties' => array(
                        'type' => 'string',
                    ),
                ),
                'MissingMeta' => array(
                    'type' => 'numeric',
                    'location' => 'header',
                    'sentAs' => 'x-scs-missing-meta',
                ),
                'Restore' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-restore',
                ),
                'ServerSideEncryption' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-server-side-encryption',
                ),
                'VersionId' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-version-id',
                ),
                'WebsiteRedirectLocation' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-website-redirect-location',
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'GetObjectAclOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'Grants' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'sentAs' => 'AccessControlList',
                    'items' => array(
                        'name' => 'Grant',
                        'type' => 'object',
                        'sentAs' => 'Grant',
                        'properties' => array(
                            'Grantee' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'DisplayName' => array(
                                        'type' => 'string',
                                    ),
                                    'EmailAddress' => array(
                                        'type' => 'string',
                                    ),
                                    'ID' => array(
                                        'type' => 'string',
                                    ),
                                    'Type' => array(
                                        'type' => 'string',
                                        'sentAs' => 'xsi:type',
                                        'data' => array(
                                            'xmlAttribute' => true,
                                            'xmlNamespace' => 'http://www.w3.org/2001/XMLSchema-instance',
                                        ),
                                    ),
                                    'URI' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                            'Permission' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'Owner' => array(
                    'type' => 'object',
                    'location' => 'xml',
                    'properties' => array(
                        'DisplayName' => array(
                            'type' => 'string',
                        ),
                        'ID' => array(
                            'type' => 'string',
                        ),
                    ),
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'GetObjectTorrentOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'Body' => array(
                    'type' => 'string',
                    'instanceOf' => 'Guzzle\\Http\\EntityBody',
                    'location' => 'body',
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'HeadBucketOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'HeadObjectOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'AcceptRanges' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'accept-ranges',
                ),
                'CacheControl' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Cache-Control',
                ),
                'ContentDisposition' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Disposition',
                ),
                'ContentEncoding' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Encoding',
                ),
                'ContentLanguage' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Language',
                ),
                'ContentLength' => array(
                    'type' => 'numeric',
                    'location' => 'header',
                    'sentAs' => 'Content-Length',
                ),
                'ContentType' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Content-Type',
                ),
                'DeleteMarker' => array(
                    'type' => 'boolean',
                    'location' => 'header',
                    'sentAs' => 'x-scs-delete-marker',
                ),
                'ETag' => array(
                    'type' => 'string',
                    'location' => 'header',
                ),
                'Expiration' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-expiration',
                ),
                'Expires' => array(
                    'type' => 'string',
                    'location' => 'header',
                ),
                'LastModified' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Last-Modified',
                ),
                'Metadata' => array(
                    'type' => 'object',
                    'location' => 'header',
                    'sentAs' => 'x-scs-meta-',
                    'additionalProperties' => array(
                        'type' => 'string',
                    ),
                ),
                'MissingMeta' => array(
                    'type' => 'numeric',
                    'location' => 'header',
                    'sentAs' => 'x-scs-missing-meta',
                ),
                'Restore' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-restore',
                ),
                'ServerSideEncryption' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-server-side-encryption',
                ),
                'VersionId' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-version-id',
                ),
                'WebsiteRedirectLocation' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-website-redirect-location',
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'ListBucketsOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'Buckets' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'items' => array(
                        'name' => 'Bucket',
                        'type' => 'object',
                        'sentAs' => 'Bucket',
                        'properties' => array(
                            'CreationDate' => array(
                                'type' => 'string',
                            ),
                            'Name' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'Owner' => array(
                    'type' => 'object',
                    'location' => 'xml',
                    'properties' => array(
                        'DisplayName' => array(
                            'type' => 'string',
                        ),
                        'ID' => array(
                            'type' => 'string',
                        ),
                    ),
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'ListMultipartUploadsOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'Bucket' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'CommonPrefixes' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'data' => array(
                        'xmlFlattened' => true,
                    ),
                    'items' => array(
                        'type' => 'object',
                        'properties' => array(
                            'Prefix' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'EncodingType' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Encoding-Type',
                ),
                'IsTruncated' => array(
                    'type' => 'boolean',
                    'location' => 'xml',
                ),
                'KeyMarker' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'MaxUploads' => array(
                    'type' => 'numeric',
                    'location' => 'xml',
                ),
                'NextKeyMarker' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'NextUploadIdMarker' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'Prefix' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'UploadIdMarker' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'Uploads' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'sentAs' => 'Upload',
                    'data' => array(
                        'xmlFlattened' => true,
                    ),
                    'items' => array(
                        'type' => 'object',
                        'sentAs' => 'Upload',
                        'properties' => array(
                            'Initiated' => array(
                                'type' => 'string',
                            ),
                            'Initiator' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'DisplayName' => array(
                                        'type' => 'string',
                                    ),
                                    'ID' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                            'Key' => array(
                                'type' => 'string',
                            ),
                            'Owner' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'DisplayName' => array(
                                        'type' => 'string',
                                    ),
                                    'ID' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                            'StorageClass' => array(
                                'type' => 'string',
                            ),
                            'UploadId' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'ListObjectVersionsOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'CommonPrefixes' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'data' => array(
                        'xmlFlattened' => true,
                    ),
                    'items' => array(
                        'type' => 'object',
                        'properties' => array(
                            'Prefix' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'DeleteMarkers' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'sentAs' => 'DeleteMarker',
                    'data' => array(
                        'xmlFlattened' => true,
                    ),
                    'items' => array(
                        'type' => 'object',
                        'sentAs' => 'DeleteMarker',
                        'properties' => array(
                            'IsLatest' => array(
                                'type' => 'boolean',
                            ),
                            'Key' => array(
                                'type' => 'string',
                            ),
                            'LastModified' => array(
                                'type' => 'string',
                            ),
                            'Owner' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'DisplayName' => array(
                                        'type' => 'string',
                                    ),
                                    'ID' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                            'VersionId' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'EncodingType' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Encoding-Type',
                ),
                'IsTruncated' => array(
                    'type' => 'boolean',
                    'location' => 'xml',
                ),
                'KeyMarker' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'MaxKeys' => array(
                    'type' => 'numeric',
                    'location' => 'xml',
                ),
                'Name' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'NextKeyMarker' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'NextVersionIdMarker' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'Prefix' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'VersionIdMarker' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'Versions' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'sentAs' => 'Version',
                    'data' => array(
                        'xmlFlattened' => true,
                    ),
                    'items' => array(
                        'type' => 'object',
                        'sentAs' => 'Version',
                        'properties' => array(
                            'ETag' => array(
                                'type' => 'string',
                            ),
                            'IsLatest' => array(
                                'type' => 'boolean',
                            ),
                            'Key' => array(
                                'type' => 'string',
                            ),
                            'LastModified' => array(
                                'type' => 'string',
                            ),
                            'Owner' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'DisplayName' => array(
                                        'type' => 'string',
                                    ),
                                    'ID' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                            'Size' => array(
                                'type' => 'string',
                            ),
                            'StorageClass' => array(
                                'type' => 'string',
                            ),
                            'VersionId' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'ListObjectsOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'CommonPrefixes' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'data' => array(
                        'xmlFlattened' => true,
                    ),
                    'items' => array(
                        'type' => 'object',
                        'properties' => array(
                            'Prefix' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'Contents' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'data' => array(
                        'xmlFlattened' => true,
                    ),
                    'items' => array(
                        'type' => 'object',
                        'properties' => array(
                            'ETag' => array(
                                'type' => 'string',
                            ),
                            'Key' => array(
                                'type' => 'string',
                            ),
                            'LastModified' => array(
                                'type' => 'string',
                            ),
                            'Owner' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'DisplayName' => array(
                                        'type' => 'string',
                                    ),
                                    'ID' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                            'Size' => array(
                                'type' => 'numeric',
                            ),
                            'StorageClass' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'EncodingType' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'Encoding-Type',
                ),
                'IsTruncated' => array(
                    'type' => 'boolean',
                    'location' => 'xml',
                ),
                'Marker' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'MaxKeys' => array(
                    'type' => 'numeric',
                    'location' => 'xml',
                ),
                'Name' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'NextMarker' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'Prefix' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'ListPartsOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'Bucket' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'Initiator' => array(
                    'type' => 'object',
                    'location' => 'xml',
                    'properties' => array(
                        'DisplayName' => array(
                            'type' => 'string',
                        ),
                        'ID' => array(
                            'type' => 'string',
                        ),
                    ),
                ),
                'IsTruncated' => array(
                    'type' => 'boolean',
                    'location' => 'xml',
                ),
                'Key' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'MaxParts' => array(
                    'type' => 'numeric',
                    'location' => 'xml',
                ),
                'NextPartNumberMarker' => array(
                    'type' => 'numeric',
                    'location' => 'xml',
                ),
                'Owner' => array(
                    'type' => 'object',
                    'location' => 'xml',
                    'properties' => array(
                        'DisplayName' => array(
                            'type' => 'string',
                        ),
                        'ID' => array(
                            'type' => 'string',
                        ),
                    ),
                ),
                'PartNumberMarker' => array(
                    'type' => 'numeric',
                    'location' => 'xml',
                ),
                'Parts' => array(
                    'type' => 'array',
                    'location' => 'xml',
                    'sentAs' => 'Part',
                    'data' => array(
                        'xmlFlattened' => true,
                    ),
                    'items' => array(
                        'type' => 'object',
                        'sentAs' => 'Part',
                        'properties' => array(
                            'ETag' => array(
                                'type' => 'string',
                            ),
                            'LastModified' => array(
                                'type' => 'string',
                            ),
                            'PartNumber' => array(
                                'type' => 'numeric',
                            ),
                            'Size' => array(
                                'type' => 'numeric',
                            ),
                        ),
                    ),
                ),
                'StorageClass' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'UploadId' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'PutBucketAclOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'PutBucketCorsOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'PutBucketLifecycleOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'PutBucketLoggingOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'PutBucketNotificationOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'PutBucketPolicyOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'PutBucketRequestPaymentOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'PutBucketTaggingOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'PutBucketVersioningOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'PutBucketWebsiteOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'PutObjectOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'ETag' => array(
                    'type' => 'string',
                    'location' => 'header',
                ),
                'Expiration' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-expiration',
                ),
                'ServerSideEncryption' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-server-side-encryption',
                ),
                'VersionId' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-version-id',
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
                'ObjectURL' => array(
                ),
            ),
        ),
        'PutObjectAclOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'RestoreObjectOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'UploadPartOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'ETag' => array(
                    'type' => 'string',
                    'location' => 'header',
                ),
                'ServerSideEncryption' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-server-side-encryption',
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
        'UploadPartCopyOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'ETag' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'LastModified' => array(
                    'type' => 'string',
                    'location' => 'xml',
                ),
                'CopySourceVersionId' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-copy-source-version-id',
                ),
                'ServerSideEncryption' => array(
                    'type' => 'string',
                    'location' => 'header',
                    'sentAs' => 'x-scs-server-side-encryption',
                ),
                'RequestId' => array(
                    'location' => 'header',
                    'sentAs' => 'x-scs-request-id',
                ),
            ),
        ),
    ),
	'iterators' => array(
        'ListBuckets' => array(
            'result_key' => 'Buckets',
        ),
        'ListMultipartUploads' => array(
            'limit_key' => 'MaxUploads',
            'more_results' => 'IsTruncated',
            'output_token' => array(
                'NextKeyMarker',
                'NextUploadIdMarker',
            ),
            'input_token' => array(
                'KeyMarker',
                'UploadIdMarker',
            ),
            'result_key' => array(
                'Uploads',
                'CommonPrefixes',
            ),
        ),
        'ListObjectVersions' => array(
            'more_results' => 'IsTruncated',
            'limit_key' => 'MaxKeys',
            'output_token' => array(
                'NextKeyMarker',
                'NextVersionIdMarker',
            ),
            'input_token' => array(
                'KeyMarker',
                'VersionIdMarker',
            ),
            'result_key' => array(
                'Versions',
                'DeleteMarkers',
                'CommonPrefixes',
            ),
        ),
        'ListObjects' => array(
            'more_results' => 'IsTruncated',
            'limit_key' => 'MaxKeys',
            'output_token' => 'NextMarker',
            'input_token' => 'Marker',
            'result_key' => array(
                'Contents',
                'CommonPrefixes',
            ),
        ),
        'ListParts' => array(
            'more_results' => 'IsTruncated',
            'limit_key' => 'MaxParts',
            'output_token' => 'NextPartNumberMarker',
            'input_token' => 'PartNumberMarker',
            'result_key' => 'Parts',
        ),
    ),
    'waiters' => array(
        '__default__' => array(
            'interval' => 5,
            'max_attempts' => 20,
        ),
        'BucketExists' => array(
            'operation' => 'HeadBucket',
            'success.type' => 'output',
            'ignore_errors' => array(
                'NoSuchBucket',
            ),
        ),
        'BucketNotExists' => array(
            'operation' => 'HeadBucket',
            'success.type' => 'error',
            'success.value' => 'NoSuchBucket',
        ),
        'ObjectExists' => array(
            'operation' => 'HeadObject',
            'success.type' => 'output',
            'ignore_errors' => array(
                'NoSuchKey',
            ),
        ),
    ),
);
