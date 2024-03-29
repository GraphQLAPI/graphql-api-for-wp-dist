<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser;

class Token
{
    public const TYPE_END = 'end';
    public const TYPE_IDENTIFIER = 'identifier';
    public const TYPE_NUMBER = 'number';
    public const TYPE_BLOCK_STRING = 'block string';
    public const TYPE_STRING = 'string';
    public const TYPE_QUERY = 'query';
    public const TYPE_MUTATION = 'mutation';
    public const TYPE_SUBSCRIPTION = 'subscription';
    public const TYPE_FRAGMENT = 'fragment';
    public const TYPE_FRAGMENT_REFERENCE = '...';
    public const TYPE_INLINE_FRAGMENT = 'inline fragment';
    public const TYPE_ON = 'on';
    public const TYPE_LBRACE = '{';
    public const TYPE_RBRACE = '}';
    public const TYPE_LPAREN = '(';
    public const TYPE_RPAREN = ')';
    public const TYPE_LSQUARE_BRACE = '[';
    public const TYPE_RSQUARE_BRACE = ']';
    public const TYPE_COLON = ':';
    public const TYPE_COMMA = ',';
    public const TYPE_VARIABLE = '$';
    public const TYPE_POINT = '.';
    public const TYPE_REQUIRED = '!';
    public const TYPE_EQUAL = '=';
    public const TYPE_AT = '@';
    public const TYPE_NULL = 'null';
    public const TYPE_TRUE = 'true';
    public const TYPE_FALSE = 'false';
    /**
     * @readonly
     * @var string
     */
    private $type;
    /**
     * @readonly
     * @var int
     */
    private $line;
    /**
     * @var int
     */
    private $column;
    /**
     * @var string|int|float|bool|null
     */
    private $data = null;
    /**
     * @param string|int|float|bool|null $data
     */
    public function __construct(string $type, int $line, int $column, $data = null)
    {
        $this->type = $type;
        $this->line = $line;
        $this->column = $column;
        $this->data = $data;
        if ($data) {
            $tokenLength = \mb_strlen((string) $data);
            $tokenLength = $tokenLength > 1 ? $tokenLength - 1 : 0;
            $this->column = $column - $tokenLength;
        }
        if ($this->getType() === self::TYPE_TRUE) {
            $this->data = \true;
        }
        if ($this->getType() === self::TYPE_FALSE) {
            $this->data = \false;
        }
        if ($this->getType() === self::TYPE_NULL) {
            $this->data = null;
        }
    }
    /**
     * @param string $tokenType
     */
    public static function tokenName($tokenType) : string
    {
        return [self::TYPE_END => 'END', self::TYPE_IDENTIFIER => 'IDENTIFIER', self::TYPE_NUMBER => 'NUMBER', self::TYPE_BLOCK_STRING => 'BLOCK_STRING', self::TYPE_STRING => 'STRING', self::TYPE_ON => 'ON', self::TYPE_QUERY => 'QUERY', self::TYPE_MUTATION => 'MUTATION', self::TYPE_SUBSCRIPTION => 'SUBSCRIPTION', self::TYPE_FRAGMENT => 'FRAGMENT', self::TYPE_FRAGMENT_REFERENCE => 'FRAGMENT_REFERENCE', self::TYPE_INLINE_FRAGMENT => 'TYPED_FRAGMENT', self::TYPE_LBRACE => 'LBRACE', self::TYPE_RBRACE => 'RBRACE', self::TYPE_LPAREN => 'LPAREN', self::TYPE_RPAREN => 'RPAREN', self::TYPE_LSQUARE_BRACE => 'LSQUARE_BRACE', self::TYPE_RSQUARE_BRACE => 'RSQUARE_BRACE', self::TYPE_COLON => 'COLON', self::TYPE_COMMA => 'COMMA', self::TYPE_VARIABLE => 'VARIABLE', self::TYPE_POINT => 'POINT', self::TYPE_NULL => 'NULL', self::TYPE_TRUE => 'TRUE', self::TYPE_FALSE => 'FALSE', self::TYPE_REQUIRED => 'REQUIRED', self::TYPE_AT => 'AT'][$tokenType] ?? $tokenType;
    }
    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
    public function getType() : string
    {
        return $this->type;
    }
    public function getLine() : int
    {
        return $this->line;
    }
    public function getColumn() : int
    {
        return $this->column;
    }
}
