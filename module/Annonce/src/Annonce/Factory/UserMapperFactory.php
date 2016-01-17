<?php
namespace Annonce\Factory;

use Annonce\Mapper\UserMapper;
use Annonce\Model\User\User;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class UserMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new UserMapper(
            $serviceLocator->get('Zend\Db\Adapter\Adapter'),
            new ClassMethods(),
            new User()
        );
    }
}