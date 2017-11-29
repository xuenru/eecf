<?php
namespace Annonce\Form;

use Zend\Form\Form;

class RegisterForm extends Form
{
    public function __construct($name = null, array $options = array())
    {
        parent::__construct($name, $options);

        $this->add(
            array(
                'type'       => 'text',
                'name'       => 'username',
                'options'    => array(
                    'label' => 'username'
                ),
                'attributes' => array(
                    'required' => true,
                )
            )
        );

        $this->add(
            array(
                'type'       => 'password',
                'name'       => 'password',
                'options'    => array(
                    'label' => 'Password'
                ),
                'attributes' => array(
                    'required' => true
                )
            )
        );

        $this->add(
            array(
                'type'       => 'text',
                'name'       => 'lastname',
                'options'    => array(
                    'label' => 'Last name'
                ),
                'attributes' => array(
                    'required' => true,
                )
            )
        );

        $this->add(
            array(
                'type'       => 'text',
                'name'       => 'firstname',
                'options'    => array(
                    'label' => 'First name'
                ),
                'attributes' => array(
                    'required' => true,
                )
            )
        );

        $this->add(
            array(
                'type'       => 'text',
                'name'       => 'email',
                'options'    => array(
                    'label' => 'Email'
                ),
                'attributes' => array(
                    'required' => true,
                )
            )
        );

        $this->add(
            array(
                'type'    => 'text',
                'name'    => 'telephone',
                'options' => array(
                    'label' => 'Telephone'
                )
            )
        );

        $this->add(
            array(
                'type'       => 'text',
                'name'       => 'role',
                'options'    => array(
                    'label' => 'Role'
                ),
                'attributes' => array(
                    'required' => true,
                )
            )
        );


        $this->add(
            array(
                'type'       => 'submit',
                'name'       => 'submit',
                'attributes' => array(
                    'value' => 'Submit'
                )
            )
        );

    }
}