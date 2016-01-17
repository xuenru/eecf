<?php
namespace XBootstrap3;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;

class Module implements AutoloaderProviderInterface, ViewHelperProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function getViewHelperConfig()
    {
        return array(
          'invokables' => array(
              'Bs3Form' => 'XBootstrap3\Form\View\Helper\Bs3Form',
              'Bs3FormRow' => 'XBootstrap3\Form\View\Helper\Bs3FormRow'
          )
        );
    }
}
