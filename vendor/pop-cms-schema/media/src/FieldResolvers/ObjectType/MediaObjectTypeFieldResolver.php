<?php

declare (strict_types=1);
namespace PoPCMSSchema\Media\FieldResolvers\ObjectType;

use DateTime;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\CommonFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLAbsolutePathScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
class MediaObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface|null
     */
    private $mediaTypeAPI;
    /**
     * @var \PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface|null
     */
    private $dateFormatter;
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver|null
     */
    private $urlScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver|null
     */
    private $intScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver|null
     */
    private $dateTimeScalarTypeResolver;
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLAbsolutePathScalarTypeResolver|null
     */
    private $urlAbsolutePathScalarTypeResolver;
    /**
     * @param \PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface $mediaTypeAPI
     */
    public final function setMediaTypeAPI($mediaTypeAPI) : void
    {
        $this->mediaTypeAPI = $mediaTypeAPI;
    }
    protected final function getMediaTypeAPI() : MediaTypeAPIInterface
    {
        /** @var MediaTypeAPIInterface */
        return $this->mediaTypeAPI = $this->mediaTypeAPI ?? $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
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
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver $urlScalarTypeResolver
     */
    public final function setURLScalarTypeResolver($urlScalarTypeResolver) : void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    protected final function getURLScalarTypeResolver() : URLScalarTypeResolver
    {
        /** @var URLScalarTypeResolver */
        return $this->urlScalarTypeResolver = $this->urlScalarTypeResolver ?? $this->instanceManager->getInstance(URLScalarTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver $intScalarTypeResolver
     */
    public final function setIntScalarTypeResolver($intScalarTypeResolver) : void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    protected final function getIntScalarTypeResolver() : IntScalarTypeResolver
    {
        /** @var IntScalarTypeResolver */
        return $this->intScalarTypeResolver = $this->intScalarTypeResolver ?? $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver
     */
    public final function setStringScalarTypeResolver($stringScalarTypeResolver) : void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected final function getStringScalarTypeResolver() : StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver = $this->stringScalarTypeResolver ?? $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    /**
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver $dateTimeScalarTypeResolver
     */
    public final function setDateTimeScalarTypeResolver($dateTimeScalarTypeResolver) : void
    {
        $this->dateTimeScalarTypeResolver = $dateTimeScalarTypeResolver;
    }
    protected final function getDateTimeScalarTypeResolver() : DateTimeScalarTypeResolver
    {
        /** @var DateTimeScalarTypeResolver */
        return $this->dateTimeScalarTypeResolver = $this->dateTimeScalarTypeResolver ?? $this->instanceManager->getInstance(DateTimeScalarTypeResolver::class);
    }
    /**
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLAbsolutePathScalarTypeResolver $urlAbsolutePathScalarTypeResolver
     */
    public final function setURLAbsolutePathScalarTypeResolver($urlAbsolutePathScalarTypeResolver) : void
    {
        $this->urlAbsolutePathScalarTypeResolver = $urlAbsolutePathScalarTypeResolver;
    }
    protected final function getURLAbsolutePathScalarTypeResolver() : URLAbsolutePathScalarTypeResolver
    {
        /** @var URLAbsolutePathScalarTypeResolver */
        return $this->urlAbsolutePathScalarTypeResolver = $this->urlAbsolutePathScalarTypeResolver ?? $this->instanceManager->getInstance(URLAbsolutePathScalarTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [MediaObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['src', 'srcPath', 'srcSet', 'width', 'height', 'sizes', 'title', 'caption', 'altText', 'description', 'date', 'dateStr', 'modifiedDate', 'modifiedDateStr', 'mimeType'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'src':
                return $this->getURLScalarTypeResolver();
            case 'srcPath':
                return $this->getURLAbsolutePathScalarTypeResolver();
            case 'srcSet':
                return $this->getStringScalarTypeResolver();
            case 'width':
                return $this->getIntScalarTypeResolver();
            case 'height':
                return $this->getIntScalarTypeResolver();
            case 'sizes':
                return $this->getStringScalarTypeResolver();
            case 'title':
                return $this->getStringScalarTypeResolver();
            case 'caption':
                return $this->getStringScalarTypeResolver();
            case 'altText':
                return $this->getStringScalarTypeResolver();
            case 'description':
                return $this->getStringScalarTypeResolver();
            case 'date':
                return $this->getDateTimeScalarTypeResolver();
            case 'dateStr':
                return $this->getStringScalarTypeResolver();
            case 'modifiedDate':
                return $this->getDateTimeScalarTypeResolver();
            case 'modifiedDateStr':
                return $this->getStringScalarTypeResolver();
            case 'mimeType':
                return $this->getStringScalarTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        switch ($fieldName) {
            case 'src':
            case 'srcPath':
            case 'date':
            case 'dateStr':
            case 'modifiedDate':
            case 'modifiedDateStr':
                return SchemaTypeModifiers::NON_NULLABLE;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'src':
                return $this->__('Media item URL source', 'pop-media');
            case 'srcPath':
                return $this->__('Media item URL source path', 'pop-media');
            case 'srcSet':
                return $this->__('Media item URL srcset', 'pop-media');
            case 'width':
                return $this->__('Media item\'s width', 'pop-media');
            case 'height':
                return $this->__('Media item\'s height', 'pop-media');
            case 'sizes':
                return $this->__('Media item\'s ‘sizes’ attribute value for an image', 'pop-media');
            case 'title':
                return $this->__('Media item title', 'pop-media');
            case 'caption':
                return $this->__('Media item caption', 'pop-media');
            case 'altText':
                return $this->__('Media item alt text', 'pop-media');
            case 'description':
                return $this->__('Media item description', 'pop-media');
            case 'date':
                return $this->__('Media item\'s published date', 'pop-media');
            case 'dateStr':
                return $this->__('Media item\'s published date, in String format', 'pop-media');
            case 'modifiedDate':
                return $this->__('Media item\'s modified date', 'pop-media');
            case 'modifiedDateStr':
                return $this->__('Media item\'s modified date, in String format', 'pop-media');
            case 'mimeType':
                return $this->__('Media item\'s mime type', 'pop-media');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        switch ($fieldName) {
            case 'src':
            case 'srcPath':
            case 'srcSet':
            case 'width':
            case 'height':
            case 'sizes':
                return ['size' => $this->getStringScalarTypeResolver()];
            default:
                return parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName) : ?string
    {
        switch ($fieldArgName) {
            case 'size':
                return $this->__('Size of the image', 'pop-media');
            default:
                return parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName) : ?Component
    {
        switch ($fieldName) {
            case 'date':
                return new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE);
            case 'dateStr':
                return new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING);
            case 'modifiedDate':
                return new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE);
            case 'modifiedDateStr':
                return new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING);
            default:
                return parent::getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName);
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
        $media = $object;
        $size = $this->obtainImageSizeFromParameters($fieldDataAccessor);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'src':
                // The media item may be an image, or a video or audio.
                // If image, $imgSrc will have a value. Otherwise, get the URL
                $imgSrc = $this->getMediaTypeAPI()->getImageSrc($media, $size);
                if ($imgSrc !== null) {
                    return $imgSrc;
                }
                return $this->getMediaTypeAPI()->getMediaItemSrc($media);
            case 'srcPath':
                // The media item may be an image, or a video or audio.
                // If image, $imgSrc will have a value. Otherwise, get the URL
                $imgSrcPath = $this->getMediaTypeAPI()->getImageSrcPath($media, $size);
                if ($imgSrcPath !== null) {
                    return $imgSrcPath;
                }
                return $this->getMediaTypeAPI()->getMediaItemSrcPath($media);
            case 'width':
            case 'height':
                $properties = $this->getMediaTypeAPI()->getImageProperties($media, $size);
                return $properties[$fieldDataAccessor->getFieldName()] ?? null;
            case 'srcSet':
                return $this->getMediaTypeAPI()->getImageSrcSet($media, $size);
            case 'sizes':
                return $this->getMediaTypeAPI()->getImageSizes($media, $size);
            case 'title':
                return $this->getMediaTypeAPI()->getTitle($media);
            case 'caption':
                return $this->getMediaTypeAPI()->getCaption($media);
            case 'altText':
                return $this->getMediaTypeAPI()->getAltText($media);
            case 'description':
                return $this->getMediaTypeAPI()->getDescription($media);
            case 'date':
                /** @var string */
                $date = $this->getMediaTypeAPI()->getDate($media, $fieldDataAccessor->getValue('gmt'));
                return new DateTime($date);
            case 'dateStr':
                /** @var string */
                $date = $this->getMediaTypeAPI()->getDate($media, $fieldDataAccessor->getValue('gmt'));
                return $this->getDateFormatter()->format($fieldDataAccessor->getValue('format'), $date);
            case 'modifiedDate':
                /** @var string */
                $modifiedDate = $this->getMediaTypeAPI()->getModified($media, $fieldDataAccessor->getValue('gmt'));
                return new DateTime($modifiedDate);
            case 'modifiedDateStr':
                /** @var string */
                $modifiedDate = $this->getMediaTypeAPI()->getModified($media, $fieldDataAccessor->getValue('gmt'));
                return $this->getDateFormatter()->format($fieldDataAccessor->getValue('format'), $modifiedDate);
            case 'mimeType':
                return $this->getMediaTypeAPI()->getMimeType($media);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function obtainImageSizeFromParameters($fieldDataAccessor) : ?string
    {
        return $fieldDataAccessor->getValue('size');
    }
}
