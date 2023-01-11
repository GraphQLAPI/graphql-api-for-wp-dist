<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Query;

use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;
class GraphQLQueryStringFormatter implements \PoP\GraphQLParser\Query\GraphQLQueryStringFormatterInterface
{
    /**
     * @param null|integer|float|boolean|string|mixed[]|stdClass $elem
     */
    public function getElementAsQueryString($elem) : string
    {
        if (\is_array($elem)) {
            return $this->getListAsQueryString($elem);
        }
        if ($elem instanceof stdClass) {
            return $this->getObjectAsQueryString($elem);
        }
        return $this->getLiteralAsQueryString($elem);
    }
    /**
     * @param mixed[] $list
     */
    public function getListAsQueryString($list) : string
    {
        $listStrElems = [];
        foreach ($list as $elem) {
            $listStrElems[] = $elem instanceof AstInterface ? $elem->asQueryString() : $this->getElementAsQueryString($elem);
        }
        return \sprintf('[%s]', \implode(', ', $listStrElems));
    }
    /**
     * @param \stdClass $object
     */
    public function getObjectAsQueryString($object) : string
    {
        $objectStrElems = [];
        foreach ((array) $object as $key => $value) {
            $objectStrElems[] = \sprintf('%s: %s', $key, $value instanceof AstInterface ? $value->asQueryString() : $this->getElementAsQueryString($value));
        }
        return \sprintf('{%s}', \implode(', ', $objectStrElems));
    }
    /**
     * @param null|int|float|bool|string $literal
     */
    public function getLiteralAsQueryString($literal) : string
    {
        if ($literal === null) {
            return 'null';
        }
        if (\is_bool($literal)) {
            return $literal ? 'true' : 'false';
        }
        if (\is_string($literal)) {
            // String, wrap between quotes
            return \sprintf('"%s"', $literal);
        }
        // Numeric: int or float
        return (string) $literal;
    }
}
