<?php
namespace Annonce\Factory;

use Annonce\Controller\ListController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ListControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        return new ListController($realServiceLocator->get('Annonce\Service\AnnonceService'));
    }
}