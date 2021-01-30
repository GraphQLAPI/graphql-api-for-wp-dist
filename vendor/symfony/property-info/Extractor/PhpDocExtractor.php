<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\PropertyInfo\Extractor;

use PrefixedByPoP\phpDocumentor\Reflection\DocBlock;
use PrefixedByPoP\phpDocumentor\Reflection\DocBlock\Tags\InvalidTag;
use PrefixedByPoP\phpDocumentor\Reflection\DocBlockFactory;
use PrefixedByPoP\phpDocumentor\Reflection\DocBlockFactoryInterface;
use PrefixedByPoP\phpDocumentor\Reflection\Types\Context;
use PrefixedByPoP\phpDocumentor\Reflection\Types\ContextFactory;
use PrefixedByPoP\Symfony\Component\PropertyInfo\PropertyDescriptionExtractorInterface;
use PrefixedByPoP\Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use PrefixedByPoP\Symfony\Component\PropertyInfo\Type;
use PrefixedByPoP\Symfony\Component\PropertyInfo\Util\PhpDocTypeHelper;
/**
 * Extracts data using a PHPDoc parser.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 *
 * @final
 */
class PhpDocExtractor implements \PrefixedByPoP\Symfony\Component\PropertyInfo\PropertyDescriptionExtractorInterface, \PrefixedByPoP\Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface, \PrefixedByPoP\Symfony\Component\PropertyInfo\Extractor\ConstructorArgumentTypeExtractorInterface
{
    public const PROPERTY = 0;
    public const ACCESSOR = 1;
    public const MUTATOR = 2;
    /**
     * @var DocBlock[]
     */
    private $docBlocks = [];
    /**
     * @var Context[]
     */
    private $contexts = [];
    private $docBlockFactory;
    private $contextFactory;
    private $phpDocTypeHelper;
    private $mutatorPrefixes;
    private $accessorPrefixes;
    private $arrayMutatorPrefixes;
    /**
     * @param string[]|null $mutatorPrefixes
     * @param string[]|null $accessorPrefixes
     * @param string[]|null $arrayMutatorPrefixes
     */
    public function __construct(\PrefixedByPoP\phpDocumentor\Reflection\DocBlockFactoryInterface $docBlockFactory = null, array $mutatorPrefixes = null, array $accessorPrefixes = null, array $arrayMutatorPrefixes = null)
    {
        if (!\class_exists(\PrefixedByPoP\phpDocumentor\Reflection\DocBlockFactory::class)) {
            throw new \LogicException(\sprintf('Unable to use the "%s" class as the "phpdocumentor/reflection-docblock" package is not installed.', __CLASS__));
        }
        $this->docBlockFactory = $docBlockFactory ?: \PrefixedByPoP\phpDocumentor\Reflection\DocBlockFactory::createInstance();
        $this->contextFactory = new \PrefixedByPoP\phpDocumentor\Reflection\Types\ContextFactory();
        $this->phpDocTypeHelper = new \PrefixedByPoP\Symfony\Component\PropertyInfo\Util\PhpDocTypeHelper();
        $this->mutatorPrefixes = null !== $mutatorPrefixes ? $mutatorPrefixes : \PrefixedByPoP\Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor::$defaultMutatorPrefixes;
        $this->accessorPrefixes = null !== $accessorPrefixes ? $accessorPrefixes : \PrefixedByPoP\Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor::$defaultAccessorPrefixes;
        $this->arrayMutatorPrefixes = null !== $arrayMutatorPrefixes ? $arrayMutatorPrefixes : \PrefixedByPoP\Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor::$defaultArrayMutatorPrefixes;
    }
    /**
     * {@inheritdoc}
     */
    public function getShortDescription(string $class, string $property, array $context = []) : ?string
    {
        /** @var $docBlock DocBlock */
        [$docBlock] = $this->getDocBlock($class, $property);
        if (!$docBlock) {
            return null;
        }
        $shortDescription = $docBlock->getSummary();
        if (!empty($shortDescription)) {
            return $shortDescription;
        }
        foreach ($docBlock->getTagsByName('var') as $var) {
            if ($var && !$var instanceof \PrefixedByPoP\phpDocumentor\Reflection\DocBlock\Tags\InvalidTag) {
                $varDescription = $var->getDescription()->render();
                if (!empty($varDescription)) {
                    return $varDescription;
                }
            }
        }
        return null;
    }
    /**
     * {@inheritdoc}
     */
    public function getLongDescription(string $class, string $property, array $context = []) : ?string
    {
        /** @var $docBlock DocBlock */
        [$docBlock] = $this->getDocBlock($class, $property);
        if (!$docBlock) {
            return null;
        }
        $contents = $docBlock->getDescription()->render();
        return '' === $contents ? null : $contents;
    }
    /**
     * {@inheritdoc}
     * @param string $class
     * @param string $property
     */
    public function getTypes($class, $property, array $context = []) : ?array
    {
        /** @var $docBlock DocBlock */
        [$docBlock, $source, $prefix] = $this->getDocBlock($class, $property);
        if (!$docBlock) {
            return null;
        }
        switch ($source) {
            case self::PROPERTY:
                $tag = 'var';
                break;
            case self::ACCESSOR:
                $tag = 'return';
                break;
            case self::MUTATOR:
                $tag = 'param';
                break;
        }
        $types = [];
        /** @var DocBlock\Tags\Var_|DocBlock\Tags\Return_|DocBlock\Tags\Param $tag */
        foreach ($docBlock->getTagsByName($tag) as $tag) {
            if ($tag && !$tag instanceof \PrefixedByPoP\phpDocumentor\Reflection\DocBlock\Tags\InvalidTag && null !== $tag->getType()) {
                $types = \array_merge($types, $this->phpDocTypeHelper->getTypes($tag->getType()));
            }
        }
        if (!isset($types[0])) {
            return null;
        }
        if (!\in_array($prefix, $this->arrayMutatorPrefixes)) {
            return $types;
        }
        return [new \PrefixedByPoP\Symfony\Component\PropertyInfo\Type(\PrefixedByPoP\Symfony\Component\PropertyInfo\Type::BUILTIN_TYPE_ARRAY, \false, null, \true, new \PrefixedByPoP\Symfony\Component\PropertyInfo\Type(\PrefixedByPoP\Symfony\Component\PropertyInfo\Type::BUILTIN_TYPE_INT), $types[0])];
    }
    /**
     * {@inheritdoc}
     */
    public function getTypesFromConstructor(string $class, string $property) : ?array
    {
        $docBlock = $this->getDocBlockFromConstructor($class, $property);
        if (!$docBlock) {
            return null;
        }
        $types = [];
        /** @var DocBlock\Tags\Var_|DocBlock\Tags\Return_|DocBlock\Tags\Param $tag */
        foreach ($docBlock->getTagsByName('param') as $tag) {
            if ($tag && null !== $tag->getType()) {
                $types = \array_merge($types, $this->phpDocTypeHelper->getTypes($tag->getType()));
            }
        }
        if (!isset($types[0])) {
            return null;
        }
        return $types;
    }
    private function getDocBlockFromConstructor(string $class, string $property) : ?\PrefixedByPoP\phpDocumentor\Reflection\DocBlock
    {
        try {
            $reflectionClass = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            return null;
        }
        $reflectionConstructor = $reflectionClass->getConstructor();
        if (!$reflectionConstructor) {
            return null;
        }
        try {
            $docBlock = $this->docBlockFactory->create($reflectionConstructor, $this->contextFactory->createFromReflector($reflectionConstructor));
            return $this->filterDocBlockParams($docBlock, $property);
        } catch (\InvalidArgumentException $e) {
            return null;
        }
    }
    private function filterDocBlockParams(\PrefixedByPoP\phpDocumentor\Reflection\DocBlock $docBlock, string $allowedParam) : \PrefixedByPoP\phpDocumentor\Reflection\DocBlock
    {
        $tags = \array_values(\array_filter($docBlock->getTagsByName('param'), function ($tag) use($allowedParam) {
            return $tag instanceof \PrefixedByPoP\phpDocumentor\Reflection\DocBlock\Tags\Param && $allowedParam === $tag->getVariableName();
        }));
        return new \PrefixedByPoP\phpDocumentor\Reflection\DocBlock($docBlock->getSummary(), $docBlock->getDescription(), $tags, $docBlock->getContext(), $docBlock->getLocation(), $docBlock->isTemplateStart(), $docBlock->isTemplateEnd());
    }
    private function getDocBlock(string $class, string $property) : array
    {
        $propertyHash = \sprintf('%s::%s', $class, $property);
        if (isset($this->docBlocks[$propertyHash])) {
            return $this->docBlocks[$propertyHash];
        }
        $ucFirstProperty = \ucfirst($property);
        switch (\true) {
            case $docBlock = $this->getDocBlockFromProperty($class, $property):
                $data = [$docBlock, self::PROPERTY, null];
                break;
            case [$docBlock] = $this->getDocBlockFromMethod($class, $ucFirstProperty, self::ACCESSOR):
                $data = [$docBlock, self::ACCESSOR, null];
                break;
            case [$docBlock, $prefix] = $this->getDocBlockFromMethod($class, $ucFirstProperty, self::MUTATOR):
                $data = [$docBlock, self::MUTATOR, $prefix];
                break;
            default:
                $data = [null, null, null];
        }
        return $this->docBlocks[$propertyHash] = $data;
    }
    private function getDocBlockFromProperty(string $class, string $property) : ?\PrefixedByPoP\phpDocumentor\Reflection\DocBlock
    {
        // Use a ReflectionProperty instead of $class to get the parent class if applicable
        try {
            $reflectionProperty = new \ReflectionProperty($class, $property);
        } catch (\ReflectionException $e) {
            return null;
        }
        try {
            return $this->docBlockFactory->create($reflectionProperty, $this->createFromReflector($reflectionProperty->getDeclaringClass()));
        } catch (\InvalidArgumentException $e) {
            return null;
        } catch (\RuntimeException $e) {
            return null;
        }
    }
    private function getDocBlockFromMethod(string $class, string $ucFirstProperty, int $type) : ?array
    {
        $prefixes = self::ACCESSOR === $type ? $this->accessorPrefixes : $this->mutatorPrefixes;
        $prefix = null;
        foreach ($prefixes as $prefix) {
            $methodName = $prefix . $ucFirstProperty;
            try {
                $reflectionMethod = new \ReflectionMethod($class, $methodName);
                if ($reflectionMethod->isStatic()) {
                    continue;
                }
                if (self::ACCESSOR === $type && 0 === $reflectionMethod->getNumberOfRequiredParameters() || self::MUTATOR === $type && $reflectionMethod->getNumberOfParameters() >= 1) {
                    break;
                }
            } catch (\ReflectionException $e) {
                // Try the next prefix if the method doesn't exist
            }
        }
        if (!isset($reflectionMethod)) {
            return null;
        }
        try {
            return [$this->docBlockFactory->create($reflectionMethod, $this->createFromReflector($reflectionMethod->getDeclaringClass())), $prefix];
        } catch (\InvalidArgumentException $e) {
            return null;
        } catch (\RuntimeException $e) {
            return null;
        }
    }
    /**
     * Prevents a lot of redundant calls to ContextFactory::createForNamespace().
     */
    private function createFromReflector(\ReflectionClass $reflector) : \PrefixedByPoP\phpDocumentor\Reflection\Types\Context
    {
        $cacheKey = $reflector->getNamespaceName() . ':' . $reflector->getFileName();
        if (isset($this->contexts[$cacheKey])) {
            return $this->contexts[$cacheKey];
        }
        $this->contexts[$cacheKey] = $this->contextFactory->createFromReflector($reflector);
        return $this->contexts[$cacheKey];
    }
}
