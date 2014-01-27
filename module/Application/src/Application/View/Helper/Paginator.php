<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Paginator extends AbstractHelper
{
    public function __construct() {

    }
//TODO : fignya
    public function __invoke($a)
    {
        print_r($a);
       //$pgCtrl= $this->sm->get('pgCtrl');
       //$paginator = $this->sm->get('paginator');
       //return $pgCtrl($paginator,'sliding', array('partial/paginator.twig', 'Users'), array('route' => 'users'));
        return "fffff";
    }
}