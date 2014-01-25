<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend;//\Http\Header;

class LocaleController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function ruAction()
    {
        $cookie = new Zend\Http\Header\SetCookie('lang', 'ru_RU', time() + 365 * 60 * 60 * 24, "/"); // now + 1 year
        $response = $this->getResponse()->getHeaders();
        $response->addHeader($cookie);
        return new ViewModel();
    }

    public function enAction()
    {
        $cookie = new Zend\Http\Header\SetCookie('lang', 'en_US', time() + 365 * 60 * 60 * 24, "/"); // now + 1 year
        $response = $this->getResponse()->getHeaders();
        $response->addHeader($cookie);
        return new ViewModel();
    }
}
