<?php
namespace Annonce\Factory;

use Annonce\Controller\WriteController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class WriteControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $annonceForm = $realServiceLocator->get('FormElementManager')->get('Annonce\Form\AnnonceForm');

        $inputfilter = $realServiceLocator->get('AnnonceFormInputFilter');
        $annonceForm->setInputFilter($inputfilter);
        return new WriteController(
            $realServiceLocator->get('Annonce\Service\AnnonceService'),
            $annonceForm
        );
    }
}