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

        $this->add(array(
            'name' => 'firm_id',
            'type' => 'Text',
            'options' => array(
                'label' => 'Firm',
            ),
        ));

        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'min' => 3,
                'max' => 64,
                'label' => 'Name',
            ),
        ));

        $this->add(array(
            'name' => 'phone',
            'type' => 'Text',
            'options' => array(
                'label' => 'Phone',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'Text',
            'options' => array(
                'label' => 'E-mail',
            ),
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
                'label' => 'active',
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
    }
}
