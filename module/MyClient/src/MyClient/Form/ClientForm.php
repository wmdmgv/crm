<?php
namespace MyClient\Form;

use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class ClientForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('client');
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new \MyClient\Form\ClientInputFilter());
        $this->add(array(
            'name' => 'security',
            'type' => 'Zend\Form\Element\Csrf',
        ));
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

//        $this->add(array(
//            'name' => 'firm_id',
//            'type' => 'Text',
//            'options' => array(
//                'label' => 'Firm',
//            ),
//        ));

        $this->add(array(
            'type' => 'Select',
            'name' => 'firm_id',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Firm',
                'empty_option'    => '--- Select Firm ---',
                'value_options' => array(
                    '6' => 'French',
                    '1' => 'English',
                    '2' => 'Japanese',
                    '3' => 'Chinese',
                ),
            )
        ));

        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'min' => 3,
                'max' => 64,
                'label' => 'Name',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => 'required',
            )
        ));

        $this->add(array(
            'name' => 'phone',
            'type' => 'Text',
            'options' => array(
                'label' => 'Phone',
                'placeholder' => '+XXX-XX-XXXXXXX',
            ),
            'attributes' => array(
                'type' => 'tel',
                'required' => 'required',
                'pattern'  => '^\+[0-9]{3}-[0-9]{2}-[0-9]{7}$'
             )
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'Text',
            'options' => array(
                'label' => 'E-mail',
            ),
            'attributes' => array(
                'type' => 'email',
                'required' => 'required',
            )
        ));

        $this->add(array(
            'name' => 'address',
            'type' => 'Text',
            'options' => array(
                'label' => 'Address',
            ),
        ));

        $this->add(array(
            'name' => 'comment',
            'type' => 'Textarea',
            'options' => array(
                'label' => 'Comment',
            ),
        ));
        $this->add(array(
            'name' => 'state',
            'type' => 'Checkbox',
            'options' => array(
                'label' => 'Active',
            ),
        ));
        $this->add(array(
            'name' => 'balance',
            'type' => 'Text',
            'options' => array(
                'label' => 'Balance',
                'placeholder' => '0.00',
                'default'     => '0.00'
            ),
        ));
        $this->add(array(
            'name' => 'use_balance',
            'type' => 'Checkbox',
            'options' => array(
                'label' => 'Use Balance',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        ));
        $this->add(array(
            'name' => 'reset',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Reset',
                'id' => 'resetbutton',
            ),
        ));
    }
}
