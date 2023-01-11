<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\FormInputs\FormInput;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
abstract class AbstractFormInputComponentProcessor extends \PoP\ComponentModel\ComponentProcessors\AbstractQueryDataComponentProcessor implements \PoP\ComponentModel\ComponentProcessors\FormInputComponentProcessorInterface
{
    /**
     * @var array<string,FormInput>
     */
    private $formInputs = [];
    // This function CANNOT have $props, since multiple can change the value of the input (eg: from Select to MultiSelect => from '' to array())
    // Yet we do not always go through initModelProps to initialize it, then changing the multiple in the form through $props, and trying to retrieve the value in an actionexecuter will fail
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function isMultiple($component) : bool
    {
        return \false;
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getInputName($component) : string
    {
        $name = $this->getName($component);
        return $name . ($this->isMultiple($component) ? '[]' : '');
    }
    /**
     * @return class-string<FormInput>
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getInputClass($component) : string
    {
        if ($this->isMultiple($component)) {
            return FormMultipleInput::class;
        }
        return FormInput::class;
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public final function getInput($component) : FormInput
    {
        $inputName = $this->getName($component);
        if (!isset($this->formInputs[$inputName])) {
            $inputClass = $this->getInputClass($component);
            $this->formInputs[$inputName] = new $inputClass($inputName, $this->getInputSelectedValue($component), $this->getInputOptions($component));
        }
        return $this->formInputs[$inputName];
    }
    // This function CANNOT have $props, since we do not always go through initModelProps to set the name of the input
    // Eg: we change the input name through $props 'name' when displaying the form, however in the actionexecuter, it doesn't
    // load that same component (it just accesses directly its value), then it fails retrieving the value since it tries get it from a different field name
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getName($component) : string
    {
        return $this->getComponentHelpers()->getComponentOutputName($component);
    }
    /**
     * @param array<string,mixed>|null $source
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getValue($component, $source = null)
    {
        return $this->getInput($component)->getValue($source);
    }
    /**
     * @param array<string,mixed>|null $source
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function isInputSetInSource($component, $source = null)
    {
        return $this->getInput($component)->isInputSetInSource($source);
    }
    /**
     * @param array<string,mixed> $props
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getInputDefaultValue($component, &$props)
    {
        return null;
    }
    /**
     * @param array<string,mixed> $props
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getDefaultValue($component, &$props)
    {
        $value = $this->getProp($component, $props, 'default-value');
        if (!\is_null($value)) {
            return $value;
        }
        return $this->getInputDefaultValue($component, $props);
    }
    /**
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getInputSelectedValue($component)
    {
        return null;
    }
    /**
     * @return array<string,mixed>
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getInputOptions($component) : array
    {
        return [];
    }
}
