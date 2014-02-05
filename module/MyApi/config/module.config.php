<?php

return array(
    'doctrine' => array(
        'driver' => array(
            'myapi_entity' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(__DIR__ . '/../src/MyApi/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'MyApi\Entity' => 'myapi_entity',
                )
            )
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'MyApi\Controller\Api' => 'MyApi\Controller\ApiController',
        ),
    ),

//    'view_helpers' => array(
//        'invokables' => array(
//            'showMessages' => 'MyDevice\View\Helper\ShowMessages',
//        ),
//    ),






    'router' => array(
        'routes' => array(
            'api' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'MyApi\Controller\Api',
                        'action'     => 'index',
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
            'ViewJsonStrategy',
        ),
    ),

);