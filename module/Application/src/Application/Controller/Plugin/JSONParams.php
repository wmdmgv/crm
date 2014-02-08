<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\Params as ZendParams;

class JSONParams extends ZendParams {

    public function fromJson() {
        $body = $this->getController()->getRequest()->getContent();
        if (!empty($body)) {
            $json = json_decode($body, true);
            if (!empty($json)) {
                return $json;
            }
        }

        return false;
    }

}