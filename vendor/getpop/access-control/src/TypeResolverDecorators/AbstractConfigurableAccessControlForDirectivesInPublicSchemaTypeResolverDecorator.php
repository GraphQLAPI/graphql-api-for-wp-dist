<?php

declare (strict_types=1);
namespace PoP\AccessControl\TypeResolverDecorators;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
abstract class AbstractConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator extends \PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator
{
    use ConfigurableAccessControlForDirectivesTypeResolverDecoratorTrait;
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
