<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use DateTime;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface;
use PoPCMSSchema\CustomPosts\FieldResolvers\InterfaceType\CustomPostInterfaceTypeFieldResolver;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
abstract class AbstractCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface|null
     */
    private $customPostTypeAPI;
    /**
     * @var \PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface|null
     */
    private $dateFormatter;
    /**
     * @var \PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver|null
     */
    private $queryableInterfaceTypeFieldResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\FieldResolvers\InterfaceType\CustomPostInterfaceTypeFieldResolver|null
     */
    private $customPostInterfaceTypeFieldResolver;
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface $customPostTypeAPI
     */
    public final function setCustomPostTypeAPI($customPostTypeAPI) : void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    protected final function getCustomPostTypeAPI() : CustomPostTypeAPIInterface
    {
        /** @var CustomPostTypeAPIInterface */
        return $this->customPostTypeAPI = $this->customPostTypeAPI ?? $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface $dateFormatter
     */
    public final function setDateFormatter($dateFormatter) : void
    {
        $this->dateFormatter = $dateFormatter;
    }
    protected final function getDateFormatter() : DateFormatterInterface
    {
        /** @var DateFormatterInterface */
        return $this->dateFormatter = $this->dateFormatter ?? $this->instanceManager->getInstance(DateFormatterInterface::class);
    }
    /**
     * @param \PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver
     */
    public final function setQueryableInterfaceTypeFieldResolver($queryableInterfaceTypeFieldResolver) : void
    {
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }
    protected final function getQueryableInterfaceTypeFieldResolver() : QueryableInterfaceTypeFieldResolver
    {
        /** @var QueryableInterfaceTypeFieldResolver */
        return $this->queryableInterfaceTypeFieldResolver = $this->queryableInterfaceTypeFieldResolver ?? $this->instanceManager->getInstance(QueryableInterfaceTypeFieldResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPosts\FieldResolvers\InterfaceType\CustomPostInterfaceTypeFieldResolver $customPostInterfaceTypeFieldResolver
     */
    public final function setCustomPostInterfaceTypeFieldResolver($customPostInterfaceTypeFieldResolver) : void
    {
        $this->customPostInterfaceTypeFieldResolver = $customPostInterfaceTypeFieldResolver;
    }
    protected final function getCustomPostInterfaceTypeFieldResolver() : CustomPostInterfaceTypeFieldResolver
    {
        /** @var CustomPostInterfaceTypeFieldResolver */
        return $this->customPostInterfaceTypeFieldResolver = $this->customPostInterfaceTypeFieldResolver ?? $this->instanceManager->getInstance(CustomPostInterfaceTypeFieldResolver::class);
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return [];
    }
    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers() : array
    {
        return [$this->getQueryableInterfaceTypeFieldResolver(), $this->getCustomPostInterfaceTypeFieldResolver()];
    }
    /**
     * @return mixed
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        $customPostTypeAPI = $this->getCustomPostTypeAPI();
        $customPost = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'url':
                return $customPostTypeAPI->getPermalink($customPost);
            case 'urlAbsolutePath':
                /** @var string */
                return $customPostTypeAPI->getPermalinkPath($customPost);
            case 'slug':
                return $customPostTypeAPI->getSlug($customPost);
            case 'content':
                return $customPostTypeAPI->getContent($customPost);
            case 'rawContent':
                return $customPostTypeAPI->getRawContent($customPost);
            case 'status':
                return $customPostTypeAPI->getStatus($customPost);
            case 'isStatus':
                return $fieldDataAccessor->getValue('status') === $customPostTypeAPI->getStatus($customPost);
            case 'date':
                /** @var string */
                $date = $customPostTypeAPI->getPublishedDate($customPost, $fieldDataAccessor->getValue('gmt') ?? \false);
                return new DateTime($date);
            case 'dateStr':
                /** @var string */
                $date = $customPostTypeAPI->getPublishedDate($customPost, $fieldDataAccessor->getValue('gmt') ?? \false);
                return $this->getDateFormatter()->format($fieldDataAccessor->getValue('format'), $date);
            case 'modifiedDate':
                /** @var string */
                $modifiedDate = $customPostTypeAPI->getModifiedDate($customPost, $fieldDataAccessor->getValue('gmt') ?? \false);
                return new DateTime($modifiedDate);
            case 'modifiedDateStr':
                /** @var string */
                $modifiedDate = $customPostTypeAPI->getModifiedDate($customPost, $fieldDataAccessor->getValue('gmt') ?? \false);
                return $this->getDateFormatter()->format($fieldDataAccessor->getValue('format'), $modifiedDate);
            case 'title':
                return $customPostTypeAPI->getTitle($customPost);
            case 'excerpt':
                return $customPostTypeAPI->getExcerpt($customPost);
            case 'customPostType':
                /** @var string */
                $customPostType = $customPostTypeAPI->getCustomPostType($customPost);
                return $customPostType;
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
