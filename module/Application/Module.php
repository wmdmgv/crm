<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend;
use Zend\Http\Header;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

use BjyAuthorize\Provider\Identity\ZfcUserZendDb;
use ZendTest\XmlRpc\Server\Exception;


class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'initLocale'), 1);

        $logger = new Zend\Log\Logger;
        //$writer = new Zend\Log\Writer\Stream('php://output');
        $writer = new Zend\Log\Writer\Stream('./data/log/' . date('Y-m-d') . '-error.log');
        $logger->addWriter($writer);
        Zend\Log\Logger::registerErrorHandler($logger);
//        $logger = new Zend\Log\Logger;
        $logger->log(Zend\Log\Logger::EMERG, 'Emergency message');
        //  $logger->info("AAAAAAAAAAAAAAAAAAAa");

        //------------- LOGGER WMD
        $application = $e->getApplication();
        $sm = $application->getServiceManager();
        $sharedManager = $application->getEventManager()->getSharedManager();

        $sharedManager->attach('Zend\Mvc\Application', 'dispatch.error',
            function ($e) use ($sm) {
                if ($e->getParam('exception')) {
                    $sm->get('Zend\Log\Logger')->crit($e->getParam('exception'));
                }
            }
        );
        //--------------



//        $app = $e -> getApplication ();
//        $events = $app -> getEventManager ();
//        $shared = $events -> getSharedManager ();
//        $sm = $app -> getServiceManager ();
//
//        $shared -> attach ( 'ZfcUser\Service\User' ,  'register.post' ,  function  ( $e )  use  ( $sm )  {
//            $userService = $e -> getTarget ();
//            /** @var User $newUser */
//            $newUser = $e -> getParam ( 'user' );
//            $registrationForm = $e -> getParam ( 'form' );
//            print_r("AAAAAAAAAAAAAa");
//            print_r($newUser);
//            // сделать что-то с новой информации пользователя, например, назначить новую роль пользователя ...
//        });

        //----keep what was there before--------
        $serviceManager = $e->getApplication()->getServiceManager();
        //zfcuser_user_service --- provided by ZfcUser module
        $userService = $serviceManager->get('zfcuser_user_service');
        //handling "register.post" event

        $userService->getEventManager()->attach('register.post',
            function (\Zend\EventManager\Event $e) use ($serviceManager) {
                //get user entity from event params
                /** @var \MyUser\Entity\User $user */
                $user = $e->getParam('user');

                //this is my own "userrole" service, you can target to "userrolelinker" service
                //     UserRole\Service\UserRoleService
//                $userRoleService = $serviceManager->get('UserRole\Service\UserRoleService');
                //below line is actually calling the insert functionallity via service
                //if you would like can also be done by "mapper" class also without
                //the involvement of "service" class
//                $userRoleService->insertUserRole(array(
//                    'userId' => $user->getId(),
//                    'roleId' => 3 //my target role id for the new users
//                ));


//   'BjyAuthorize\Provider\Identity\ZfcUserZendDb'

                $logger = new Zend\Log\Logger;
                //$writer = new Zend\Log\Writer\Stream('php://output');
                $writer = new Zend\Log\Writer\Stream('./data/log/' . date('Y-m-d') . '-error1.log');
                $logger->addWriter($writer);
                Zend\Log\Logger::registerErrorHandler($logger);
//        $logger = new Zend\Log\Logger;


                $logger->log(Zend\Log\Logger::EMERG, "0001a\n");


                /* @var $adapter \Zend\Db\Adapter\Adapter */
                $adapter = $serviceManager->get('zfcuser_zend_db_adapter');
                /* @var $userService \ZfcUser\Service\User */
                $userService = $serviceManager->get('zfcuser_user_service');
                $config = $serviceManager->get('BjyAuthorize\Config');

                $logger->log(Zend\Log\Logger::EMERG, "0001b\n");

                $provider = new ZfcUserZendDb($adapter, $userService);
                $logger->log(Zend\Log\Logger::EMERG, "0001c\n");

                $provider->setDefaultRole($config['default_role']);
                $logger->log(Zend\Log\Logger::EMERG, "0001d\n");

                //print_r($provider);

                $logger->log(Zend\Log\Logger::EMERG, "0001\n");

                try {
                    $objectManager = $serviceManager->get('Doctrine\ORM\EntityManager');
                    $logger->log(Zend\Log\Logger::EMERG, "0002\n");
                    $obj = new \MyUser\Entity\UserRoleLinker();
//
//                $blogpost->exchangeArray($form->getData());
//
//                $blogpost->setCreated(time());
//                $blogpost->setUserId(0);
                    $logger->log(Zend\Log\Logger::EMERG, "0003\n");
                    $obj->setUserId($user->getId());
                    $obj->setRoleId(3);
                    $logger->info(print_r($obj, true));

                    $logger->log(Zend\Log\Logger::EMERG, "0004\n");
                    $objectManager->persist($obj);
                    $objectManager->flush();
                    $logger->log(Zend\Log\Logger::EMERG, "0005\n");
                    $logger->log(Zend\Log\Logger::EMERG, print_r(array($obj, $user), true));

                } catch (Exception $e) {
                    print_r($e);
                    die;
                };


            });


    }
    public function initLocale(MvcEvent $e)
    {
        $app = $e->getApplication();
        // Cookie:
        $translator = $app->getServiceManager()->get('translator');
        //Получаем конфигурацию нашего приложения
        $config =  $app->getServiceManager()->get('Config');
        $cookies = $app->getRequest()->getCookie();
        if (!$cookies['lang']) {
            $cookie = new Zend\Http\Header\SetCookie('lang', 'en_US', time() + 365 * 60 * 60 * 24, "/"); // now + 1 year
            $response = $app->getResponse()->getHeaders();
            $response->addHeader($cookie);
        } else {
            $translator->setLocale($cookies['lang']); // ru_RU, en_US, etc...
        }

        // Session:
//        //Получаем объект translator'a
//        $translator = $e->getApplication()->getServiceManager()->get('translator');
//        $container = new Container('main',Container::getDefaultManager()); // Zend\Session\Container;
//        $translator->setLocale($container['lang']); // ru_RU, en_US, etc..

        // Route:
//        $translator = $e->getApplication()->getServiceManager()->get('translator');
//        //Получаем конфигурацию нашего приложения
//        $config =  $e->getApplication()->getServiceManager()->get('Config');
//        //Вытягиваем короткое имя языка из роута (если он определился)
//        $shotLang = $e->getRouteMatch()->getParam('lang'); //или $e->getApplication()->getMvcEvent()->getRouteMatch();
//        if (isset($config['languages'][$shotLang])){
//            //устанавливаем локаль на определенный язык
//            $translator->setLocale($config['languages'][$shotLang]['locale']);
//        } else {
//            //устанавливаем локаль на неизвестный/не существующий язык - по дефолту
//            $lang = array_shift($config['languages']);
//            $translator->setLocale($lang['locale']);
//        }
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(

                'curRoute' => function ($sm) {
                        $locator = $sm->getServiceLocator();
                        $viewHelper = new View\Helper\ShowRoute($locator);
                        return $viewHelper;
                    },
            ),
        );

    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        // TODO: Implement getServiceConfig() method.
    }
}
