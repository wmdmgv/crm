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
        } else {
            $roleId = null;
        }
        $result = new JsonModel(array('role' => $roleId ));
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
                $filter[] = " f.$key LIKE '$value%' ";
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
