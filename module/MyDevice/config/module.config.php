<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'mydevice_entity' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(__DIR__ . '/../src/MyDevice/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'MyDevice\Entity' => 'mydevice_entity',
                )
            )
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'MyDevice\Controller\Devices' => 'MyDevice\Controller\DeviceController',
        ),
    ),

//    'view_helpers' => array(
//        'invokables' => array(
//            'showMessages' => 'MyDevice\View\Helper\ShowMessages',
//        ),
//    ),


    'router' => array(
        'routes' => array(
            'devices' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/devices[/][page/:page][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'MyDevice\Controller\Devices',
                        'action'     => 'index',
                        'page' => 1,
                    ),
                ),
            ),

        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ZfcTwigViewStrategy',
        ),
    ),

);