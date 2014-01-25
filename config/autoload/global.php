<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Log\Logger' => function($sm){
                    $logger = new Zend\Log\Logger;
                    $writer = new Zend\Log\Writer\Stream('./data/log/'.date('Y-m-d').'-error.log');

                    $logger->addWriter($writer);

                    return $logger;
                },
        ),
    ),
    'languages'=> array(
        'ru' => array(
            'name' => 'russian',
            'locale' => 'ru_RU',
        ),
        'en' => array(
            'name' => 'english',
            'locale' => 'en_US',
        ),
    ),
);