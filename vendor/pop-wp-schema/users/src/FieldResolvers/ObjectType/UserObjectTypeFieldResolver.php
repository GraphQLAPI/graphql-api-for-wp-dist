<?php

declare(strict_types=1);

namespace PoPWPSchema\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use DateTime;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\CommonFilterInputContainerComponentProcessor;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use WP_User;

class UserObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface|null
     */
    private $dateFormatter;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver|null
     */
    private $dateTimeScalarTypeResolver;

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
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver
     */
    final public function setStringScalarTypeResolver($stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver = $this->stringScalarTypeResolver ?? $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    /**
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver $dateTimeScalarTypeResolver
     */
    final public function setDateTimeScalarTypeResolver($dateTimeScalarTypeResolver): void
    {
        $this->dateTimeScalarTypeResolver = $dateTimeScalarTypeResolver;
    }
    final protected function getDateTimeScalarTypeResolver(): DateTimeScalarTypeResolver
    {
        /** @var DateTimeScalarTypeResolver */
        return $this->dateTimeScalarTypeResolver = $this->dateTimeScalarTypeResolver ?? $this->instanceManager->getInstance(DateTimeScalarTypeResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'nicename',
            'nickname',
            'locale',
            'registeredDate',
            'registeredDateStr',
        ];
    }

    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'nicename':
                return $this->getStringScalarTypeResolver();
            case 'nickname':
                return $this->getStringScalarTypeResolver();
            case 'locale':
                return $this->getStringScalarTypeResolver();
            case 'registeredDate':
                return $this->getDateTimeScalarTypeResolver();
            case 'registeredDateStr':
                return $this->getStringScalarTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }

    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName): int
    {
        switch ($fieldName) {
            case 'nicename':
            case 'nickname':
            case 'locale':
            case 'registeredDate':
            case 'registeredDateStr':
                return SchemaTypeModifiers::NON_NULLABLE;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }

    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName): ?string
    {
        switch ($fieldName) {
            case 'nicename':
                return $this->__('User\'s nicename', 'pop-users');
            case 'nickname':
                return $this->__('User\'s nickname', 'pop-users');
            case 'locale':
                return $this->__('Retrieves the locale of a user', 'pop-users');
            case 'registeredDate':
                return $this->__('The date the user registerd on the site', 'pop-users');
            case 'registeredDateStr':
                return $this->__('The date the user registerd on the site, in String format', 'pop-users');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }

    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName): ?Component
    {
        switch ($fieldName) {
            case 'registeredDateStr':
                return new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_DATE_AS_STRING);
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
        /** @var WP_User */
        $user = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'nicename':
                return $user->user_nicename;
            case 'nickname':
                return $user->nickname;
            case 'locale':
                return \get_user_locale($user);
            case 'registeredDate':
                return new DateTime($user->user_registered);
            case 'registeredDateStr':
                return $this->getDateFormatter()->format(
                    $fieldDataAccessor->getValue('format'),
                    $user->user_registered
                );
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
