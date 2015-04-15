<?php
/**
 * Copyright 2010-2013 Sohu.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

namespace SohuCS\Scs;

use SohuCS\Common\Signature\SignatureInterface;
use SohuCS\Common\Credentials\CredentialsInterface;
use Guzzle\Http\Message\RequestInterface;

/**
 * Amazon Scs signature interface
 * @link http://docs.aws.amazon.com/AmazonScs/latest/dev/RESTAuthentication.html
 */
interface ScsSignatureInterface extends SignatureInterface
{
    /**
     * Create a pre-signed URL
     *
     * @param RequestInterface     $request Request to sign
     * @param CredentialsInterface $credentials Credentials used to sign
     * @param int|string|\DateTime $expires The time at which the URL should expire. This can be a Unix timestamp, a
     *                                      PHP DateTime object, or a string that can be evaluated by strtotime
     * @return string
     */
    public function createPresignedUrl(
        RequestInterface $request,
        CredentialsInterface $credentials,
        $expires
    );
}
