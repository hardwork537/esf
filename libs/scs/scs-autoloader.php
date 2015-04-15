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

require_once __DIR__ . '/Symfony/Component/ClassLoader/UniversalClassLoader.php';

if (!defined('SCS_FILE_PREFIX')) {
    define('SCS_FILE_PREFIX', __DIR__);
}

$classLoader = new Symfony\Component\ClassLoader\UniversalClassLoader();
$classLoader->registerNamespaces(array(
    'SohuCS'   => SCS_FILE_PREFIX,
    'Guzzle'   => SCS_FILE_PREFIX,
    'Symfony'  => SCS_FILE_PREFIX,
    'Doctrine' => SCS_FILE_PREFIX,
    'Psr'      => SCS_FILE_PREFIX,
    'Monolog'  => SCS_FILE_PREFIX
));

$classLoader->register();

return $classLoader;
