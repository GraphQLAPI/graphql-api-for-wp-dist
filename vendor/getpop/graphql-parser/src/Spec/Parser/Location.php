<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser;

class Location
{
    /**
     * @var int
     */
    protected $line;
    /**
     * @var int
     */
    protected $column;
    public function __construct(int $line, int $column)
    {
        $this->line = $line;
        $this->column = $column;
    }
    public function getLine() : int
    {
        return $this->line;
    }
    public function getColumn() : int
    {
        return $this->column;
    }
    /**
     * @return array<string,int>
     */
    public function toArray() : array
    {
        return ['line' => $this->getLine(), 'column' => $this->getColumn()];
    }
}
