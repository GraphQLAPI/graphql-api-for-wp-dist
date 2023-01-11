<?php

declare (strict_types=1);
namespace PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;
use stdClass;
class DateQueryInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver
{
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver|null
     */
    private $dateScalarTypeResolver;
    /**
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver $dateScalarTypeResolver
     */
    public final function setDateScalarTypeResolver($dateScalarTypeResolver) : void
    {
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
    }
    protected final function getDateScalarTypeResolver() : DateScalarTypeResolver
    {
        /** @var DateScalarTypeResolver */
        return $this->dateScalarTypeResolver = $this->dateScalarTypeResolver ?? $this->instanceManager->getInstance(DateScalarTypeResolver::class);
    }
    public function getTypeName() : string
    {
        return 'DateQueryInput';
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return ['after' => $this->getDateScalarTypeResolver(), 'before' => $this->getDateScalarTypeResolver()];
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case 'after':
                return $this->__('Retrieve entities from after this date', 'schema-commons');
            case 'before':
                return $this->__('Retrieve entities from before this date', 'schema-commons');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }
    /**
     * Integrate parameters into the "date_query" WP_Query arg
     *
     * @see https://developer.wordpress.org/reference/classes/wp_query/#date-parameters
     *
     * @param array<string,mixed> $query
     * @param stdClass|stdClass[]|array<stdClass[]> $inputValue
     */
    public function integrateInputValueToFilteringQueryArgs(&$query, $inputValue) : void
    {
        if (\is_array($inputValue)) {
            parent::integrateInputValueToFilteringQueryArgs($query, $inputValue);
            return;
        }
        if (isset($inputValue->before)) {
            $query['date-to'] = $this->getDateScalarTypeResolver()->serialize($inputValue->before);
        }
        if (isset($inputValue->after)) {
            $query['date-from'] = $this->getDateScalarTypeResolver()->serialize($inputValue->after);
        }
    }
}
