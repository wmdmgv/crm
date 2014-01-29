<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'myclient_entity' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(__DIR__ . '/../src/MyClient/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'MyClient\Entity' => 'myclient_entity',
                )
            )
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'MyClient\Controller\Clients' => 'MyClient\Controller\ClientController',
        ),
    ),

//    'view_helpers' => array(
//        'invokables' => array(
//            'showMessages' => 'MyClient\View\Helper\ShowMessages',
//        ),
//    ),


    'router' => array(
        'routes' => array(
            'clients' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/clients[/][page/:page][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'MyClient\Controller\Clients',
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