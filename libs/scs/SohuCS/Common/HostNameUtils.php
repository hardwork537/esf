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

namespace SohuCS\Common;

use Guzzle\Http\Url;

/**
 * Utility class for parsing regions and services from URLs
 */
class HostNameUtils
{
    const DEFAULT_REGION = 'bjcnc';

    /**
     * Parse the SCS region name from a URL
     *
     *
     * @param Url $url HTTP URL
     *
     * @return string
     * @link http://docs.aws.amazon.com/general/latest/gr/rande.html
     */
    public static function parseRegionName(Url $url)
    {
        // If we don't recognize the domain, just return the default
        if (substr($url->getHost(), -15) != '.scs.sohucs.com') {
            return self::DEFAULT_REGION;
        }

        $serviceAndRegion = substr($url->getHost(), 0, -15);
        // Special handling for Scs regions
        $separator = strpos($serviceAndRegion, 'scs') === 0 ? '-' : '.';
        $separatorPos = strpos($serviceAndRegion, $separator);

        // If don't detect a separator, then return the default region
        if ($separatorPos === false) {
            return self::DEFAULT_REGION;
        }

        $region = substr($serviceAndRegion, $separatorPos + 1);

        return $region;
    }

    /**
     * Parse the SohuCS service name from a URL
     *
     * @param Url $url HTTP URL
     *
     * @return string Returns a service name (or empty string)
     * @link http://docs.aws.amazon.com/general/latest/gr/rande.html
     */
    public static function parseServiceName(Url $url)
    {
        // The service name is the first part of the host
        $parts = explode('.', $url->getHost(), 2);

        // Special handling for S3
        if (stripos($parts[0], 'scs') === 0) {
            return 'scs';
        }

        return $parts[0];
    }
}
