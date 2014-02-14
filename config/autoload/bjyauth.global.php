<?php

return array(
    'bjyauthorize' => array(

        'default_role' => 'guest',

        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',

        'authenticated_role' => 'user',

        'role_providers'        => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager'    => 'doctrine.entity_manager.orm_default',
                'role_entity_class' => 'MyUser\Entity\Role',
            ),
        ),

        'guards' => array(
            /* If this guard is specified here (i.e. it is enabled), it will block
             * access to all controllers and actions unless they are specified here.
             * You may omit the 'action' index to allow access to the entire controller
             */
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'zfcuser',
                    'action' => array('index'),
                    'roles' => array('guest', 'user'),
                ),
                array(
                    'controller' => 'zfcuser',
                    'action' => array('login', 'authenticate', 'register'),
                    'roles' => array('guest'),
                ),
                array(
                    'controller' => 'zfcuser',
                    'action' => array('logout','changepassword', 'changeemail'),
                    'roles' => array('user','guest'),
                ),

                array('controller' => 'Application\Controller\Index', 'roles' => array()),

                array(
                    'controller' => 'MyBlog\Controller\BlogPost',
                    'action' => array('index', 'view'),
                    'roles' => array('guest', 'user', 'administrator'),
                ),

                array(
                    'controller' => 'MyBlog\Controller\BlogPost',
                    'action' => array('add', 'edit', 'delete'),
                    'roles' => array('administrator'),
                ),

                //WMD
                array(
                    'controller' => 'DoctrineORMModule\Yuml\YumlController',
                    'action' => array('index'),
                    'roles' => array('administrator')
                ),
                array(
                    'controller' => 'Application\Controller\Locale',
                    'action' => array('index', 'ru', 'en'),
                    'roles' => array()
                ),
                array(
                    'controller' => 'MyUser\Controller\Users',
                    'action' => array('index', 'view', 'add', 'edit', 'delete','restore','page'),
                    'roles' => array('administrator'),
                ),
                array(
                    'controller' => 'MyDevice\Controller\Devices',
                    'action' => array('index', 'view', 'add', 'edit', 'delete','restore','page'),
                    'roles' => array('administrator'),
                ),
                array(
                    'controller' => 'MyFirm\Controller\Firms',
                    'action' => array('index', 'view', 'add', 'edit', 'delete','restore','page'),
                    'roles' => array('administrator'),
                ),
                array(
                    'controller' => 'MyClient\Controller\Clients',
                    'action' => array('index', 'view', 'add', 'edit', 'delete','restore','page'),
                    'roles' => array('administrator','moderator'),
                ),
                array(
                    'controller' => 'MyApi\Controller\Api',
                    'action' => array('orders', 'firms', 'users', 'statuses','clients', 'devices'),
                    'roles' => array('administrator','moderator')
                ),
                array(
                    'controller' => 'MyApi\Controller\Api',
                    'action' => array('order', 'job', 'jobs', 'invoice'),
                    'roles' => array('administrator','moderator','user')
                ),
                array(
                    'controller' => 'MyApi\Controller\Api',
                    'action' => array('index'),
                    'roles' => array()
                ),
            ),
        ),
    ),
);