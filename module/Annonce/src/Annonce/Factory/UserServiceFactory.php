<?php
namespace Annonce\Factory;

use Annonce\Service\UserService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new UserService($serviceLocator->get('Annonce\Mapper\UserMapper'));
    }
}