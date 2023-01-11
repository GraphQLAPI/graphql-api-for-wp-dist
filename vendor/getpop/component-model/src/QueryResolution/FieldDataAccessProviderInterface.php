<?php

declare (strict_types=1);
namespace PoP\ComponentModel\QueryResolution;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Exception\ShouldNotHappenException;
interface FieldDataAccessProviderInterface
{
    /**
     * @return array<string,mixed>|null null if casting the fieldArgs produced an error
     * @throws ShouldNotHappenException
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface|null $objectTypeResolver
     */
    public function getFieldArgs($field, $objectTypeResolver = null, $object = null) : ?array;
    /**
     * Used by the nested directive resolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $fromField
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $toField
     */
    public function duplicateFieldData($fromField, $toField) : void;
}
