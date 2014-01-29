<?php

namespace MyDevice\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class DeviceInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'name',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 64,
                    ),
                ),
            ),
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),

        ));

        $this->add(array(
            'name' => 'comment',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 0,
                        'max' => 255,
                    ),
                ),
            ),
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name' => 'state',
            'required' => false,
        ));
    }
}