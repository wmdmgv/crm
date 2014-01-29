<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'myfirm_entity' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(__DIR__ . '/../src/MyFirm/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'MyFirm\Entity' => 'myfirm_entity',
                )
            )
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'MyFirm\Controller\Firms' => 'MyFirm\Controller\FirmController',
        ),
    ),

//    'view_helpers' => array(
//        'invokables' => array(
//            'showMessages' => 'MyFirm\View\Helper\ShowMessages',
//        ),
//    ),


    'router' => array(
        'routes' => array(
            'firms' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/firms[/][page/:page][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'MyFirm\Controller\Firms',
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