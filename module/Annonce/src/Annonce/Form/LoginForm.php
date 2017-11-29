<?php
namespace Annonce\Form;

use Zend\Form\Form;

class LoginForm extends Form
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
                /*'attributes' => array(
                    'required' => true,
                )*/
            )
        );

        $this->add(
            array(
                'type'       => 'password',
                'name'       => 'password',
                'options'    => array(
                    'label' => 'Password'
                ),
                /*'attributes' => array(
                    'required' => true
                )*/
            )
        );

        $this->add(
            array(
                'type'    => 'checkbox',
                'name'    => 'remember_me',
                'options' => array(
                    'label' => 'Remember me'
                )
            )
        );

        $this->add(
            array(
                'type'       => 'submit',
                'name'       => 'login',
                'attributes' => array(
                    'value' => 'Login'
                )
            )
        );

    }
}