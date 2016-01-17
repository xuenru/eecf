<?php
namespace Annonce\Storage;

use Zend\Authentication\Storage;

class Session extends Storage\Session
{
    public function setRememberMe($time = null)
    {
        $this->session->getManager()->rememberMe($time);
    }

    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }
}