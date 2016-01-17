<?php
namespace Annonce\Factory;

use Annonce\Mapper\AnnonceMapper;
use Annonce\Model\Annonce;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class AnnonceMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AnnonceMapper(
            $serviceLocator->get('Zend\Db\Adapter\Adapter'),
            new ClassMethods(),
            new Annonce()
        );
    }
}