<?php

declare(strict_types=1);

namespace PoPWPSchema\Media\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface;
use PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface;
use PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use WP_Post;

class MediaObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface|null
     */
    private $cmsHelperService;
    /**
     * @var \PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface|null
     */
    private $dateFormatter;
    /**
     * @var \PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver|null
     */
    private $queryableInterfaceTypeFieldResolver;

    /**
     * @param \PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface $cmsHelperService
     */
    final public function setCMSHelperService($cmsHelperService): void
    {
        $this->cmsHelperService = $cmsHelperService;
    }
    final protected function getCMSHelperService(): CMSHelperServiceInterface
    {
        /** @var CMSHelperServiceInterface */
        return $this->cmsHelperService = $this->cmsHelperService ?? $this->instanceManager->getInstance(CMSHelperServiceInterface::class);
    }
    /**
     * @param \PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface $dateFormatter
     */
    final public function setDateFormatter($dateFormatter): void
    {
        $this->dateFormatter = $dateFormatter;
    }
    final protected function getDateFormatter(): DateFormatterInterface
    {
        /** @var DateFormatterInterface */
        return $this->dateFormatter = $this->dateFormatter ?? $this->instanceManager->getInstance(DateFormatterInterface::class);
    }
    /**
     * @param \PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver
     */
    final public function setQueryableInterfaceTypeFieldResolver($queryableInterfaceTypeFieldResolver): void
    {
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }
    final protected function getQueryableInterfaceTypeFieldResolver(): QueryableInterfaceTypeFieldResolver
    {
        /** @var QueryableInterfaceTypeFieldResolver */
        return $this->queryableInterfaceTypeFieldResolver = $this->queryableInterfaceTypeFieldResolver ?? $this->instanceManager->getInstance(QueryableInterfaceTypeFieldResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            MediaObjectTypeResolver::class,
        ];
    }

    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getQueryableInterfaceTypeFieldResolver(),
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'url',
            'urlAbsolutePath',
            'slug',
        ];
    }

    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName): ?string
    {
        switch ($fieldName) {
            case 'url':
                return $this->__('Media element URL', 'pop-media');
            case 'urlAbsolutePath':
                return $this->__('Media element URL path', 'pop-media');
            case 'slug':
                return $this->__('Media element slug', 'pop-media');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
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
        /** @var WP_Post */
        $mediaItem = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'url':
            case 'urlAbsolutePath':
                $url = \get_permalink($mediaItem->ID);
                if ($url === false) {
                    return '';
                }
                if ($fieldDataAccessor->getFieldName() === 'url') {
                    return $url;
                }
                /** @var string */
                return $this->getCMSHelperService()->getLocalURLPath($url);
            case 'slug':
                return $mediaItem->post_name;
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
