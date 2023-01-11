<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\ConditionalOnContext\Editor\SchemaServices\FieldResolvers\ObjectType;

use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

/**
 * These fields must be accessed by the plugin only,
 * they are unavailable otherwise (even to the admin
 * user in the wp-admin GraphiQL client).
 */
abstract class AbstractForPluginInternalUseListOfCPTEntitiesRootObjectTypeFieldResolver extends AbstractListOfCPTEntitiesRootObjectTypeFieldResolver
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface|null
     */
    private $userAuthorization;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface $userAuthorization
     */
    final public function setUserAuthorization($userAuthorization): void
    {
        $this->userAuthorization = $userAuthorization;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        /** @var UserAuthorizationInterface */
        return $this->userAuthorization = $this->userAuthorization ?? $this->instanceManager->getInstance(UserAuthorizationInterface::class);
    }

    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function resolveCanProcessField($objectTypeResolver, $field): bool
    {
        if (
            !parent::resolveCanProcessField($objectTypeResolver, $field)
        ) {
            return false;
        }
        return $this->getUserAuthorization()->canAccessSchemaEditor();
    }
}
