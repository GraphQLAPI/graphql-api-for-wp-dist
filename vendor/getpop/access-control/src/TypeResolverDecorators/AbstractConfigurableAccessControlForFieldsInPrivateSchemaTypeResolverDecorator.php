<?php

declare (strict_types=1);
namespace PoP\AccessControl\TypeResolverDecorators;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\AccessControl\TypeResolverDecorators\ConfigurableAccessControlForFieldsTypeResolverDecoratorTrait;
abstract class AbstractConfigurableAccessControlForFieldsInPrivateSchemaTypeResolverDecorator extends \PoP\AccessControl\TypeResolverDecorators\AbstractPrivateSchemaTypeResolverDecorator
{
    use ConfigurableAccessControlForFieldsTypeResolverDecoratorTrait;
    /**
     * @var \PoP\AccessControl\Services\AccessControlManagerInterface
     */
    protected $accessControlManager;
    public function __construct(InstanceManagerInterface $instanceManager, FieldQueryInterpreterInterface $fieldQueryInterpreter, AccessControlManagerInterface $accessControlManager)
    {
        $this->accessControlManager = $accessControlManager;
        parent::__construct($instanceManager, $fieldQueryInterpreter);
    }
}
