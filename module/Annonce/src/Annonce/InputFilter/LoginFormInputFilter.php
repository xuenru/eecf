<?php
namespace Annonce\InputFilter;

use Zend\InputFilter\InputFilter;

class LoginFormInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(
            array(
                'name'     => 'username',
                'required' => true
            )
        );

        $this->add(
            array(
                'name'       => 'password',
                'required'   => true
            )
        );
    }
}