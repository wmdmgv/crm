<?php
namespace MyBlog\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\FlashMessenger;

class ShowMessages extends AbstractHelper
{
    public function __invoke()
    {
        $messenger = new FlashMessenger();
        $error_messages = $messenger->getErrorMessages();
        $messages = $messenger->getMessages();
        $result = '';
        if (count($error_messages)) {
            $result .= '<div class="alert alert-danger">';
            foreach ($error_messages as $message) {
                $result .= '<span>' . $message . '</span>';
            }
            $result .= '</div>';
        }

        if (count($messages)) {
            $result .= '<div class="alert alert-success">';
            foreach ($messages as $message) {
                $result .= '<span>' . $message . '</span>';
            }
            $result .= '</div>';
        }

        return $result;
    }
}