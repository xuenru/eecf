<?php
namespace XBootstrap3\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormRow;

class Bs3FormRow extends FormRow
{
    protected $wrap = '<div class="form-group %s">%s%s</div>';
    protected $eleWrap = '<div class="%s">%s%s</div>';
    protected $errorWrap = '<div class="help-block">%s</div>';

    public function render(ElementInterface $element, $labelPosition = null)
    {
        $elementType = $element->getAttribute('type');
        $eleWrapClass = 'col-sm-10';

        //label
        $label = $element->getLabel();
        $labelHtml = '';
        if (!empty($label) && !in_array($elementType, array('submit', 'checkbox'))) {
            $labelhelper = $this->getLabelHelper();
            $element->setLabelAttributes(
                array(
                    'class' => 'col-sm-2 control-label'
                )
            );
            $labelHtml = $labelhelper($element);
        } else {
            $eleWrapClass .= ' col-sm-offset-2';
        }

        //element
        $elementHelper = $this->getElementHelper();
        if ($elementType == 'submit') {
            $element->setAttribute('class', 'btn btn-default');
        } elseif ($elementType != 'checkbox') {
            $element->setAttribute('class', 'form-control');
        }

        //error msg
        $errorHtml = '';
        $inputErrorClass = '';
        if (count($element->getMessages()) > 0) {
            $errorHelper = $this->getElementErrorsHelper();
            $errorHtml = sprintf($this->errorWrap, $errorHelper($element));
            $inputErrorClass = 'has-error';
        }

        if ($elementType == 'checkbox') {
            $elementHtmlContent = sprintf(
                '<div class="checkbox"><label>%s%s</label></div>',
                $elementHelper($element),
                $element->getLabel()
            );
        } else {
            $elementHtmlContent = $elementHelper($element);
        }
        $elementHtml = sprintf($this->eleWrap, $eleWrapClass, $elementHtmlContent, $errorHtml);


        return sprintf($this->wrap, $inputErrorClass, $labelHtml, $elementHtml);
    }

}