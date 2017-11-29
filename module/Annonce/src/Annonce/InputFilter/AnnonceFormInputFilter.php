<?php
namespace Annonce\InputFilter;

use Zend\Db\Adapter\AdapterInterface;
use Zend\InputFilter\InputFilter;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class AnnonceFormInputFilter extends InputFilter implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->add(
            array(
                'name'       => 'worship_date',
                'required'   => true,
                'filters'    => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )/*,
                'validators' => array(
                    array('name'    => 'dbnorecordexists',
                          'options' => array(
                              'table'   => 'annonce_entity',
                              'field'   => 'worship_date',
                              'adapter' => $dbAdapter
                          ))
                )*/
            )
        );

        $this->add(
            array(
                'name'     => 'message',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            )
        );
    }
}