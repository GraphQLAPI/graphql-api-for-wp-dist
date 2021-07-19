<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\TypeResolverPickers\TypeResolverPickerInterface;
interface UnionTypeResolverInterface
{
    // public function addTypeToID(string | int $resultItemID): string;
    /**
     * @param string|int $resultItemID
     */
    public function getTypeResolverClassForResultItem($resultItemID);
    /**
     * @param object $resultItem
     */
    public function getTargetTypeResolverPicker($resultItem) : ?TypeResolverPickerInterface;
    /**
     * @param object $resultItem
     */
    public function getTargetTypeResolver($resultItem) : ?TypeResolverInterface;
    /**
     * @param array<string|int> $ids
     */
    public function getResultItemIDTargetTypeResolvers(array $ids) : array;
    public function getTargetTypeResolverClasses() : array;
    public function getSchemaTypeInterfaceClass() : ?string;
    public function getTypeResolverPickers() : array;
}
