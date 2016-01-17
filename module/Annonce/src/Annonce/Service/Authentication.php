<?php
namespace Annonce\Service;

use Annonce\Storage\Session;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class Authentication implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function getAuthService()
    {
        $authAdapter = new CredentialTreatmentAdapter(
            $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'), 'annonce_user', 'username', 'password'
        );
        return new AuthenticationService(new Session('Annonce_Auth', 'username'), $authAdapter);

    }
}