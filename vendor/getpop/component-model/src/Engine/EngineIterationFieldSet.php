<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Engine;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;
class EngineIterationFieldSet
{
    /**
     * @var FieldInterface[]
     */
    public $fields = [];
    /**
     * @var SplObjectStorage<FieldInterface, FieldInterface[]>
     */
    public $conditionalFields;
    /**
     * @param FieldInterface[] $fields
     * @param SplObjectStorage<FieldInterface,FieldInterface[]> $conditionalFields
     */
    public function __construct(array $fields = [], SplObjectStorage $conditionalFields = null)
    {
        $conditionalFields = $conditionalFields ?? new SplObjectStorage();
        $this->fields = $fields;
        $this->conditionalFields = $conditionalFields;
    }
    /**
     * @param FieldInterface[] $fields
     */
    public function addFields($fields) : void
    {
        $this->fields = \array_values(\array_unique(\array_merge($this->fields, $fields)));
    }
    /**
     * @param FieldInterface[] $conditionalFields
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $conditionField
     */
    public function addConditionalFields($conditionField, $conditionalFields) : void
    {
        $this->conditionalFields[$conditionField] = \array_values(\array_unique(\array_merge($this->conditionalFields[$conditionField] ?? [], $conditionalFields)));
    }
}
