<?php

namespace Application\View\Helper;

use Twig_Extension;

class TwigHelper extends Twig_Extension
{
    public function getName()
    {
        return 'text';
    }

    public function getFilters()
    {
        return array(
            'truncate' => new \Twig_Filter_Method($this, 'truncate')
        );
    }

    public function truncate($text, $max = 30)
    {
        $lastSpace = 0;

        if (strlen($text) >= $max) {
            $text = substr($text, 0, $max);
            $lastSpace = strrpos($text, ' ');
            $text = substr($text, 0, $lastSpace) . '...';
        }

        return $text;
    }

    public function getFunctions()
    {
        return array(
            'nl2br' => new \Twig_Function_Method($this, 'nl2br', array('is_safe' => array('html')))
        );
    }

    public function nl2br($text)
    {
        return nl2br($text);
    }
}