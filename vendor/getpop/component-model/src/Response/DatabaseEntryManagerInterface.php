<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Response;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;
interface DatabaseEntryManagerInterface
{
    /**
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $entries
     * @return array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>>
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function moveEntriesWithIDUnderDBName($entries, $relationalTypeResolver) : array;
    /**
     * @param SplObjectStorage<FieldInterface,mixed> $entries
     * @return array<string,SplObjectStorage<FieldInterface,mixed>>
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function moveEntriesWithoutIDUnderDBName($entries, $relationalTypeResolver) : array;
}
