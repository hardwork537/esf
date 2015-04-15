<?php
/**
 * Copyright 2010-2013 SOHUCS.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Inspired by Amazon AWS SDK.
 */

return array(
    'class' => 'SohuCS\Common\SohuCS',
    'services' => array(

        'default_settings' => array(
            'params' => array()
        ),

        'scs' => array(
            'alias'   => 'scs',
            'extends' => 'default_settings',
            'class'   => 'SohuCS\Scs\ScsClient'
        ),
    )
);
