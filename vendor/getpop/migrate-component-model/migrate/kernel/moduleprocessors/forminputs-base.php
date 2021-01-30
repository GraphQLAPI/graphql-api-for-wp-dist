<?php

namespace PoP\ComponentModel;

use PoP\ComponentModel\ModuleProcessors\AbstractQueryDataModuleProcessor;
abstract class AbstractFormInputs extends \PoP\ComponentModel\ModuleProcessors\AbstractQueryDataModuleProcessor implements \PoP\ComponentModel\FormComponent
{
    use FormInputsTrait;
}
