<?php
/**
 * Copyright 2010-2013 SOHUCS.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Inspired by Amazon AWS SDK.
 */

return array(
    'includes' => array('_scs'),
    'services' => array(

        'sdk1_settings' => array(
            'extends' => 'default_settings',
            'params'  => array(
                'certificate_authority' => false
            )
        ),

        'v1.scs'  => array(
            'extends' => 'sdk1_settings',
            'class'   => 'SohuCS'
        ),
    )
);
