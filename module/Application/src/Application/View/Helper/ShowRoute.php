<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ShowRoute extends AbstractHelper
{
    private $sm;

    public function __construct($sm) {
        $this->sm = $sm;
    }

    public function __invoke()
    {
        $router = $this->sm->get('router');
        $request = $this->sm->get('request');

        $routeMatch = $router->match($request);
        if (!is_null($routeMatch))
            return $routeMatch->getMatchedRouteName();
        return "";
    }
}