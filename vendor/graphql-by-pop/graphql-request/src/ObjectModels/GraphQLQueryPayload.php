<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLRequest\ObjectModels;

class GraphQLQueryPayload
{
    /**
     * @readonly
     * @var string|null
     */
    public $query;
    /**
     * @readonly
     * @var mixed[]|null
     */
    public $variables;
    /**
     * @readonly
     * @var string|null
     */
    public $operationName;
    public function __construct(?string $query, ?array $variables, ?string $operationName)
    {
        $this->query = $query;
        /**
         * @var array<string,mixed>|null
         */
        $this->variables = $variables;
        $this->operationName = $operationName;
    }
}
