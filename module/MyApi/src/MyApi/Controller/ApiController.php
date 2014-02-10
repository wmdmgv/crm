<?php
namespace MyApi\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

use MyDevice\Entity;
use MyApi\Entity as MyApiEntity;

//use Zend\Mvc\Controller\ActionController;

use Zend\Http\Request;
use Zend\Uri\UriFactory;
use Zend\Stdlib;

use Application\Controller;

use Zend\Filter;

class ApiController extends AbstractActionController
{
    private $repository;

    public function indexAction()
    {
        /** @var \MyUser\Entity\User $user */
        $user = $this->zfcUserAuthentication()->getIdentity();
        if ($user) {
            $roleId = $user->getRoles()[0]->getRoleId();
            $userName = $user->getUsername();
            $userId = $user->getId();
        } else {
            $roleId = null;
            $userName = '';
            $userId = '';
        }
        // WMD TODO: refactore this hack
        $firmUser = array(
            1 => 3,
            2 => 2,
            3 => 3
        );
        $result = new JsonModel(array('role' => $roleId , 'name' => $userName, 'id' => $userId, 'firmuser' => $firmUser[$userId]));
        return $result;
    }

    /** Get job object
     * @return JsonModel
     */
    public function jobAction()
    {
        //$orderId = 1;
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        // TO GET RAW POST DATA var_dump($this->getRequest()->getContent());
        $jobId = $this->params()->fromRoute('id');
        // Get params from uri  ?param=value
//        if (!$orderId) {
//            $uri = UriFactory::factory($_SERVER['REQUEST_URI'])->getQueryAsArray();
//            // var_dump($uri);
//            $orderId = $uri['orderId'];
//        }
        // Check for raw post data
        $plugin = $this->JSONParams();
        $params = $plugin->fromJson();


        // IF POST data in JSON RAW
        if ($params) {
            $orderForm = $params['job'];
            if (isset($params['jobId'])) {
                $jobId = $params['jobId'];
                /** @var \MyApi\Entity\Job $job */
                $job = $objectManager->getRepository('MyApi\Entity\Job')->find($jobId);
                if (isset($params['jobdelete'])) {
                    //Delete job
                    $objectManager->remove($job);
                    $objectManager->flush();

                } else {
                    //var_dump($params);
                    // Edit
                    $jobId = $params['jobId'];
//var_dump($orderForm);
//
//                    array (size=6)
//  'id' => int 18
//  'device_id' => string '12' (length=2)
//  'name' => string 'Test Message' (length=12)
//  'comment' => string 'Comment' (length=7)
//  'price' => string '0.00' (length=4)
//  'state' => int 1
                    /** @var \MyApi\Entity\Job $job */
                    $job = $objectManager->getRepository('MyApi\Entity\Job')->find($jobId);
                    $job->setState((int)$orderForm['state']);
                    $job->setName($orderForm['name']);
                    $job->setComment($orderForm['comment']);
                    $job->setPrice($orderForm['price']);
                    $job->setDevice_Id((int)$orderForm['device_id']);
                    // If change Firm
                    if ($job->getDevice_Id() != $orderForm['device_id']) {
                        $device = $objectManager->getRepository('MyDevice\Entity\Device')->find((int)$orderForm['device_id']);
                        $job->setDevice($device);
                    }
                    //............//
                    $objectManager->persist($job);
                    $objectManager->flush();
                }

            } else {
                // Add
                $job = new \MyApi\Entity\Job();
                // $order->exchangeArray($form->getData());

                $job->setState($orderForm['state']);
                $job->setName($orderForm['name']);
                $job->setComment($orderForm['comment']);
                $job->setPrice(($orderForm['price'] ? $orderForm['price'] : 0));

                $order = $objectManager->getRepository('MyApi\Entity\Order')->find((int)$orderForm['order_id']);
                $job->setOrder($order);

                $device = $objectManager->getRepository('\MyDevice\Entity\Device')->findOneBy(array('id' => 1));
                $job->setDevice($device);
                $job->setUpdated(new \DateTime("now"));
                $objectManager->persist($job);
                $objectManager->flush();
                $jobId = $job->getId();

            }

        }
        if (isset($params['jobdelete'])) {
            $job = null;
        } else {
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $job = $objectManager->getRepository('MyApi\Entity\Job')->find($jobId);

            $job->order = $job->order->getArrayCopy();
            $job->device = $job->device->getArrayCopy();
            $job = $job->getArrayCopy();
        }
        $result = new JsonModel(array(
                'result' => $job,
                'total' => count($job))
        );
        return $result;
    }

//    protected function object_to_array($obj)
//    {
//        $arrObj = is_object($obj) ? get_object_vars($obj) : $obj;
//        foreach ($arrObj as $key => $val) {
//            $val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
//            $arr[$key] = $val;
//        }
//        return $arr;
//    }
//
//    public static function object_to_array($d) {
//        if (is_object($d))
//            $d = get_object_vars($d);
//
//        return is_array($d) ? array_map(__METHOD__, $d) : $d;
//    }
//
//    public static function array_to_object($d) {
//        return is_array($d) ? (object) array_map(__METHOD__, $d) : $d;
//    }

    /** Get List of Jobs
     * @return JsonModel
     */
    public function jobsAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $orderId = $this->params()->fromRoute('id');
        $data = array();
        $jobs = $objectManager->getRepository('MyApi\Entity\Job')->findBy(array('order_id' => $orderId));
        foreach ($jobs as $key => $value) {
            /** @var \MyApi\Entity\Job $value */
            $data[$key] = array(
                'id' => $value->getId(),
                'order_id' => $value->getOrder_Id(),
                'device_id' => $value->getDevice_Id(),
                'name' => $value->getName(),
                'comment' => $value->getComment(),
                'price' => $value->getPrice(),
                'state' => $value->getState(),
                'created' => $value->getCreated(),
                'updated' => $value->getUpdated()
            );
        }

        $result = new JsonModel(array(
                'result' => $data,
                'total' => count($data))
        );
        return $result;
    }

    /** Get order object
     * @return JsonModel
     */
    public function orderAction()
    {
        //$orderId = 1;
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        // TO GET RAW POST DATA var_dump($this->getRequest()->getContent());
        $orderId = $this->params()->fromRoute('id');
        // Get params from uri  ?param=value
//        if (!$orderId) {
//            $uri = UriFactory::factory($_SERVER['REQUEST_URI'])->getQueryAsArray();
//            // var_dump($uri);
//            $orderId = $uri['orderId'];
//        }
        // Check for raw post data
        $plugin = $this->JSONParams();
        $params = $plugin->fromJson();
        // IF POST data in JSON RAW
        if ($params) {
            $orderForm = $params['order'];
            if (isset($params['orderId'])) {
                //var_dump($params);
                // Edit
                $orderId = $params['orderId'];

                /** @var \MyApi\Entity\Order $order */
                $order = $objectManager->getRepository('MyApi\Entity\Order')->find($orderId);
                $order->setState($orderForm['state']);
                $order->setName($orderForm['name']);
                $order->setComment($orderForm['comment']);
                $order->setAmount($orderForm['amount']);
                // If change Firm
                if ($order->getFirm_Id() != $orderForm['firm']['id']) {
                    $firm = $objectManager->getRepository('MyFirm\Entity\Firm')->find((int)$orderForm['firm']['id']);
                    $order->setFirm($firm);
                }
                // If change Client
                if ($order->getClient_Id()!= $orderForm['client']['id']) {
                    $client = $objectManager->getRepository('MyClient\Entity\Client')->find((int)$orderForm['client']['id']);
                    $order->setClient($client);
                }

                //............//
                $objectManager->persist($order);
                $objectManager->flush();

            } else {
                // Add
                $user = $this->zfcUserAuthentication()->getIdentity();

                $order = new \MyApi\Entity\Order();
               // $order->exchangeArray($form->getData());

                $order->setState((isset($orderForm['state']) ? $orderForm['state'] : 1));
                $order->setName((isset($orderForm['name']) ? $orderForm['name'] : ""));
                $order->setComment((isset($orderForm['comment']) ? $orderForm['comment'] : ""));
                $order->setAmount((isset($orderForm['amount']) ? $orderForm['amount'] : 0));

                $client = $objectManager->getRepository('MyClient\Entity\Client')->find((int)$orderForm['client']['id']);
                $order->setClient($client);

                $firm = $objectManager->getRepository('\MyFirm\Entity\Firm')->findOneBy(array('id' => $orderForm['firm']['id']));
                $order->setFirm($firm);

                // SET user from DB or request , now is from current logged
                $order->setUser($user);

                $objectManager->persist($order);
                $objectManager->flush();
                $orderId = $order->getId();

            }

        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $order = $objectManager->getRepository('MyApi\Entity\Order')->find($orderId);;

//        /** @var \MyClient\Entity\Client $user */
//        foreach ($list as $key => $user) {
//            $data[] = array(
//                'id' => $user->getId(),
//                'name' => htmlspecialchars($user->getName()) . ', ' . htmlspecialchars($user->getAddress()). ', ' . htmlspecialchars($user->getComment())
//            );
//        }
        //WMD TODO: WTF??? HOW TO CONVERT OR OUTPUT IN JSON OBJECTS?
        $order->user = $order->user->getArrayCopy();
        $order->firm = $order->firm->getArrayCopy();
        $order->client = $order->client->getArrayCopy();

        $jobs = [];
        foreach ($order->jobs as $key => $value) {
            $jobs[] = $value->getArrayCopy();
        }
        $order->jobs = $jobs;
        $order = $order->getArrayCopy();
       // var_dump($order->jobs);
        //var_dump($order);
        $result = new JsonModel(array(
                'result' => $order,
                'total' => count($order))
        );
        return $result;
    }


    /** Get statuses list
     * @return JsonModel
     */
    public function statusesAction()
    {
        $translate = $this->getServiceLocator()->get('viewhelpermanager')->get('translate');
        $data = array(
            array('id' => 0, 'name' => $translate('none')),
            array('id' => 1, 'name' => $translate('received')),
            array('id' => 2, 'name' => $translate('in job')),
            array('id' => 3, 'name' => $translate('done')),
            array('id' => 4, 'name' => $translate('given')),
            array('id' => 5, 'name' => $translate('WTF?'))
        );
        $result = new JsonModel(array(
                'result' => $data,
                'total' => count($data))
        );
        return $result;
    }

    /** Get users list
     * @return JsonModel
     */
    public function usersAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $list = $objectManager->getRepository('MyUser\Entity\User')->findAll();;

        /** @var \MyUser\Entity\User $user */
        foreach ($list as $key => $user) {
            $data[] = array(
                'id' => $user->getId(),
                'name' => htmlspecialchars($user->getUsername())
            );
        }
        $result = new JsonModel(array(
                'result' => $data,
                'total' => count($data))
        );
        return $result;
    }

    /** Get clients list
     * @return JsonModel
     */
    public function clientsAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $list = $objectManager->getRepository('MyClient\Entity\Client')->findAll();;

        /** @var \MyClient\Entity\Client $user */
        foreach ($list as $key => $user) {
            $data[] = array(
                'id' => $user->getId(),
                'name' => htmlspecialchars($user->getName()) . ', ' . htmlspecialchars($user->getAddress()). ', ' . htmlspecialchars($user->getComment())
            );
        }
        $result = new JsonModel(array(
                'result' => $data,
                'total' => count($data))
        );
        return $result;
    }

    /** Get devices list
     * @return JsonModel
     */
    public function devicesAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $list = $objectManager->getRepository('MyDevice\Entity\Device')->findAll();;
        $data = array();
        /** @var \MyClient\Entity\Client $device */
        foreach ($list as $key => $device) {
            $data[] = array(
                'id' => $device->getId(),
                'name' => htmlspecialchars($device->getName())
            );
        }
        $result = new JsonModel(array(
                'result' => $data,
                'total' => count($data))
        );
        return $result;
    }

    public function firmsAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');


        //---Paging with query
        $sql = 'SELECT f FROM \MyFirm\Entity\Firm f ORDER by f.state DESC, f.id ASC';
        //print_r($sql);
        $query = $objectManager->createQuery($sql);
        $adapter = new DoctrinePaginator(new ORMPaginator($query));
        /** @var Paginator $paginator */
        $paginator = new Paginator($adapter);
        //  var_dump($page);
        $paginator
            ->setCurrentPageNumber(1)
            ->setItemCountPerPage(1000);

        $data = [];
        // print_r($paginator->getItemsByPage($page));
        foreach ($paginator->getItemsByPage(1) as $key => $value) {
            //var_dump((array)$value);
            $data[] = array(
                'id' => $value->getId(),
                'name' => htmlspecialchars($value->getName())
            );
        }
        // print_r($data);

        $result = new JsonModel(array(
                'result' => $data, //(array) $paginator->getItemsByPage($page),
                'total' => $paginator->getPages()->totalItemCount)
        );

        return $result;
    }

    /** Get List of Orders
     * @return JsonModel
     */
    public function ordersAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        //$page = $this->params()->fromQuery('page');
        //$max = $this->params()->fromQuery('count');

        // Some hack for params , TODO: how can i get params from ?param=value ?
        $uri = UriFactory::factory($_SERVER['REQUEST_URI'])->getQueryAsArray();
        $page = $uri['page'];
        $count = $uri['count'];

        $sorting = [];
        if (isset($uri['sorting']) && count($uri['sorting'])) {
        //print_r($uri);
            foreach($uri['sorting'] as $key => $value) {
                $sorting[] = " f.$key $value ";
            }
        }
        $filter = [];
        if (isset($uri['filter']) && count($uri['filter'])) {
            //print_r($uri);
            foreach($uri['filter'] as $key => $value) {
                $value = urldecode($value);
                $filter[] = " LOWER(f.$key) LIKE '$value%' ";
            }
        }

        $orderBy = (count($sorting) > 0 ? " ORDER BY " . implode(",", $sorting) : " ");
        $filterBy = (count($filter) > 0 ? " WHERE " . implode(" AND ", $filter) : " ");
        //---Paging with query  //ORDER by f.state DESC, f.id ASC
        $sql = 'SELECT f FROM \MyApi\Entity\Order f ' .$filterBy. $orderBy;
        //print_r($sql);
        $query = $objectManager->createQuery($sql);
        $adapter = new DoctrinePaginator(new ORMPaginator($query));
        /** @var Paginator $paginator */
        $paginator = new Paginator($adapter);
        //  var_dump($page);
        $paginator
            ->setCurrentPageNumber($page)
            ->setItemCountPerPage($count);

       // $filter = new Filter\Callback(array($this, 'filterCallback'));

       // $paginator->setFilter($filter);

        // WTF ??????
        // print_r($paginator->toJson());
        //var_dump($paginator->getItemsByPage($page));

        // Some hack to convert for JSON, because json is = {"0":{},"1":{},"2":{},"3":{},"4":{},"5":{},"6":{},"7":{},"8":{},"9":{}}
        // WTF???
        $data = [];
       // print_r($paginator->getItemsByPage($page));
        foreach ($paginator->getItemsByPage($page) as $key => $value) {
            //var_dump((array)$value);
            $data[] = array(
                'id' => $value->getId(),
                'name' => htmlspecialchars($value->getName())
            );
        }
       // print_r($data);

        $result = new JsonModel(array(
                'result' => $data, //(array) $paginator->getItemsByPage($page),
                'total' => $paginator->getPages()->totalItemCount)
        );                                                                                      

        return $result;
    }


    /** Get List of Orders123
     * @return JsonModel
     */
    public function orders1235Action()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        //$page = $this->params()->fromQuery('page');
        //$max = $this->params()->fromQuery('count');

        // Some hack for params , TODO: how can i get params from ?param=value ?
        $uri = UriFactory::factory($_SERVER['REQUEST_URI'])->getQueryAsArray();
        $page = $uri['page'];
        $count = $uri['count'];

        $sorting = [];
        if (isset($uri['sorting']) && count($uri['sorting'])) {
            //print_r($uri);
            foreach($uri['sorting'] as $key => $value) {
                $sorting[] = " f.$key $value ";
            }
        }
        $filter = [];
        if (isset($uri['filter']) && count($uri['filter'])) {
            //print_r($uri);
            foreach($uri['filter'] as $key => $value) {
                $value = urldecode($value);
                $filter[] = " LOWER(f.$key) LIKE '$value%' ";
            }
        }

        $orderBy = (count($sorting) > 0 ? " ORDER BY " . implode(",", $sorting) : " ");
        $filterBy = (count($filter) > 0 ? " WHERE " . implode(" AND ", $filter) : " ");
        //---Paging with query  //ORDER by f.state DESC, f.id ASC
        $sql = 'SELECT f FROM \MyDevice\Entity\Device f ' .$filterBy. $orderBy;
        //print_r($sql);
        $query = $objectManager->createQuery($sql);
        $adapter = new DoctrinePaginator(new ORMPaginator($query));
        /** @var Paginator $paginator */
        $paginator = new Paginator($adapter);
        //  var_dump($page);
        $paginator
            ->setCurrentPageNumber($page)
            ->setItemCountPerPage($count);

        // $filter = new Filter\Callback(array($this, 'filterCallback'));

        // $paginator->setFilter($filter);

        // WTF ??????
        // print_r($paginator->toJson());
        //var_dump($paginator->getItemsByPage($page));

        // Some hack to convert for JSON, because json is = {"0":{},"1":{},"2":{},"3":{},"4":{},"5":{},"6":{},"7":{},"8":{},"9":{}}
        // WTF???
        $data = [];
        // print_r($paginator->getItemsByPage($page));
        foreach ($paginator->getItemsByPage($page) as $key => $value) {
            //var_dump((array)$value);
            $data[] = array(
                'id' => $value->getId(),
                'name' => htmlspecialchars($value->getName())
            );
        }
        // print_r($data);

        $result = new JsonModel(array(
                'result' => $data, //(array) $paginator->getItemsByPage($page),
                'total' => $paginator->getPages()->totalItemCount)
        );

        return $result;
    }
}
