<?php

declare (strict_types=1);
namespace PoP\Engine\TypeResolvers\ObjectType;

use PoP\Root\App;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\Engine\ObjectModels\Root;
use PoP\Engine\RelationalTypeDataLoaders\ObjectType\RootTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\CanonicalTypeNameTypeResolverTrait;
class RootObjectTypeResolver extends AbstractObjectTypeResolver
{
    use CanonicalTypeNameTypeResolverTrait;
    public const HOOK_DESCRIPTION = __CLASS__ . ':description';
    /**
     * @var \PoP\Engine\RelationalTypeDataLoaders\ObjectType\RootTypeDataLoader|null
     */
    private $rootTypeDataLoader;
    /**
     * @param \PoP\Engine\RelationalTypeDataLoaders\ObjectType\RootTypeDataLoader $rootTypeDataLoader
     */
    public final function setRootTypeDataLoader($rootTypeDataLoader) : void
    {
        $this->rootTypeDataLoader = $rootTypeDataLoader;
    }
    protected final function getRootTypeDataLoader() : RootTypeDataLoader
    {
        /** @var RootTypeDataLoader */
        return $this->rootTypeDataLoader = $this->rootTypeDataLoader ?? $this->instanceManager->getInstance(RootTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'Root';
    }
    public function getTypeDescription() : ?string
    {
        return App::applyFilters(self::HOOK_DESCRIPTION, $this->__('Root type, starting from which the query is executed', 'engine'));
    }
    /**
     * @return string|int|null
     * @param object $object
     */
    public function getID($object)
    {
        /** @var Root */
        $root = $object;
        return $root->getID();
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getRootTypeDataLoader();
    }
}
