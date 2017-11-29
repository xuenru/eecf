<?php
namespace Annonce\Form;

use Zend\Form\Form;

class AnnonceForm extends Form
{
    public function __construct($name = null, array $options = array())
    {
        parent::__construct($name, $options);

        $this->add(
            array(
                'type' => 'hidden',
                'name' => 'id'
            )
        );

        $this->add(
            array(
                'type'       => 'date',
                'name'       => 'worship_date',
                'options'    => array(
                    'label'  => 'Worship date',
                    'format' => 'Y-m-d'
                ),
                'attributes' => array(
                    'required' => true,
                    'id'       => 'datepicker'
                )
            )
        );

        $this->add(
            array(
                'type'       => 'textarea',
                'name'       => 'message',
                'options'    => array(
                    'label' => 'Message'
                ),
                'attributes' => array(
                    'required' => true,
                    'rows'     => 10
                )
            )
        );

        $this->add(
            array(
                'name'       => 'submit',
                'type'       => 'submit',
                'attributes' => array(
                    'value' => 'submit'
                )
            )
        );

    }
}