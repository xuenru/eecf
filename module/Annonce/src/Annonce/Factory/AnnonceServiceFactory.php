<?php
namespace Annonce\Factory;

use Annonce\Service\AnnonceService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AnnonceServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AnnonceService($serviceLocator->get('Annonce\Mapper\AnnonceMapper'));
    }
}