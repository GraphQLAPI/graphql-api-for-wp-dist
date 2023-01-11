<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorParserException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLParserErrorFeedbackItemProvider;
use PoP\Root\Services\StandaloneServiceTrait;
class Tokenizer
{
    use StandaloneServiceTrait;
    /**
     * @var string
     */
    protected $source;
    /**
     * @var int
     */
    protected $pos = 0;
    /**
     * @var int
     */
    protected $line = 1;
    /**
     * @var int
     */
    protected $lineStart = 0;
    /**
     * @var \PoP\GraphQLParser\Spec\Parser\Token
     */
    protected $lookAhead;
    /**
     * @param string $source
     */
    protected function initTokenizer($source) : void
    {
        $this->resetTokenizer();
        $this->source = $source;
        $this->lookAhead = $this->next();
    }
    protected function resetTokenizer() : void
    {
        $this->pos = 0;
        $this->line = 1;
        $this->lineStart = 0;
    }
    protected function next() : \PoP\GraphQLParser\Spec\Parser\Token
    {
        $this->skipWhitespace();
        return $this->scan();
    }
    protected function skipWhitespace() : void
    {
        $sourceLength = \strlen($this->source);
        while ($this->pos < $sourceLength) {
            $ch = $this->source[$this->pos];
            if ($ch === ' ' || $ch === "\t" || $ch === ',') {
                $this->pos++;
            } elseif ($ch === '#') {
                $this->pos++;
                while ($this->pos < $sourceLength && ($code = \ord($this->source[$this->pos])) && $code !== 10 && $code !== 13 && $code !== 0x2028 && $code !== 0x2029) {
                    $this->pos++;
                }
            } elseif ($ch === "\r") {
                $this->pos++;
                if ($this->source[$this->pos] === "\n") {
                    $this->pos++;
                }
                $this->line++;
                $this->lineStart = $this->pos;
            } elseif ($ch === "\n") {
                $this->pos++;
                $this->line++;
                $this->lineStart = $this->pos;
            } else {
                break;
            }
        }
    }
    /**
     * @throws SyntaxErrorParserException
     */
    protected function scan() : \PoP\GraphQLParser\Spec\Parser\Token
    {
        $sourceLength = \strlen($this->source);
        if ($this->pos >= $sourceLength) {
            return new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_END, $this->getLine(), $this->getColumn());
        }
        $ch = $this->source[$this->pos];
        switch ($ch) {
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_LPAREN:
                ++$this->pos;
                return new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_LPAREN, $this->getLine(), $this->getColumn());
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_RPAREN:
                ++$this->pos;
                return new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_RPAREN, $this->getLine(), $this->getColumn());
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_LBRACE:
                ++$this->pos;
                return new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_LBRACE, $this->getLine(), $this->getColumn());
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_RBRACE:
                ++$this->pos;
                return new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_RBRACE, $this->getLine(), $this->getColumn());
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_COMMA:
                ++$this->pos;
                return new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_COMMA, $this->getLine(), $this->getColumn());
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_LSQUARE_BRACE:
                ++$this->pos;
                return new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_LSQUARE_BRACE, $this->getLine(), $this->getColumn());
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_RSQUARE_BRACE:
                ++$this->pos;
                return new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_RSQUARE_BRACE, $this->getLine(), $this->getColumn());
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_REQUIRED:
                ++$this->pos;
                return new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_REQUIRED, $this->getLine(), $this->getColumn());
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_AT:
                ++$this->pos;
                return new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_AT, $this->getLine(), $this->getColumn());
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_COLON:
                ++$this->pos;
                return new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_COLON, $this->getLine(), $this->getColumn());
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_EQUAL:
                ++$this->pos;
                return new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_EQUAL, $this->getLine(), $this->getColumn());
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_POINT:
                if ($this->checkFragment()) {
                    return new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_FRAGMENT_REFERENCE, $this->getLine(), $this->getColumn());
                }
                return new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_POINT, $this->getLine(), $this->getColumn());
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_VARIABLE:
                ++$this->pos;
                return new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_VARIABLE, $this->getLine(), $this->getColumn());
        }
        if ($ch === '_' || 'a' <= $ch && $ch <= 'z' || 'A' <= $ch && $ch <= 'Z') {
            return $this->scanWord();
        }
        if ($ch === '-' || '0' <= $ch && $ch <= '9') {
            return $this->scanNumber();
        }
        if ($this->pos + 2 < $sourceLength) {
            $chars = \substr($this->source, $this->pos, 3);
            if ($chars === '"""') {
                $this->pos += 2;
                return $this->scanString(\true);
            }
        }
        if ($ch === '"') {
            return $this->scanString(\false);
        }
        throw new SyntaxErrorParserException(new FeedbackItemResolution(GraphQLParserErrorFeedbackItemProvider::class, GraphQLParserErrorFeedbackItemProvider::E_5), $this->getLocation());
    }
    protected function checkFragment() : bool
    {
        $this->pos++;
        $ch = $this->source[$this->pos];
        $this->pos++;
        $nextCh = $this->source[$this->pos];
        $isset = $ch == \PoP\GraphQLParser\Spec\Parser\Token::TYPE_POINT && $nextCh == \PoP\GraphQLParser\Spec\Parser\Token::TYPE_POINT;
        if ($isset) {
            $this->pos++;
            return \true;
        }
        return \false;
    }
    protected function scanWord() : \PoP\GraphQLParser\Spec\Parser\Token
    {
        $start = $this->pos;
        $this->pos++;
        $sourceLength = \strlen($this->source);
        while ($this->pos < $sourceLength) {
            $ch = $this->source[$this->pos];
            if ($ch === '_' || $ch === '$' || 'a' <= $ch && $ch <= 'z' || 'A' <= $ch && $ch <= 'Z' || '0' <= $ch && $ch <= '9') {
                $this->pos++;
            } else {
                break;
            }
        }
        $value = \substr($this->source, $start, $this->pos - $start);
        return new \PoP\GraphQLParser\Spec\Parser\Token($this->getKeyword($value), $this->getLine(), $this->getColumn(), $value);
    }
    /**
     * @param string $name
     */
    protected function getKeyword($name) : string
    {
        switch ($name) {
            case 'null':
                return \PoP\GraphQLParser\Spec\Parser\Token::TYPE_NULL;
            case 'true':
                return \PoP\GraphQLParser\Spec\Parser\Token::TYPE_TRUE;
            case 'false':
                return \PoP\GraphQLParser\Spec\Parser\Token::TYPE_FALSE;
            case 'query':
                return \PoP\GraphQLParser\Spec\Parser\Token::TYPE_QUERY;
            case 'fragment':
                return \PoP\GraphQLParser\Spec\Parser\Token::TYPE_FRAGMENT;
            case 'mutation':
                return \PoP\GraphQLParser\Spec\Parser\Token::TYPE_MUTATION;
            case 'subscription':
                return \PoP\GraphQLParser\Spec\Parser\Token::TYPE_SUBSCRIPTION;
            case 'on':
                return \PoP\GraphQLParser\Spec\Parser\Token::TYPE_ON;
            default:
                return \PoP\GraphQLParser\Spec\Parser\Token::TYPE_IDENTIFIER;
        }
    }
    /**
     * @throws SyntaxErrorParserException
     * @param string $type
     */
    protected function expect($type) : \PoP\GraphQLParser\Spec\Parser\Token
    {
        if ($this->match($type)) {
            return $this->lex();
        }
        throw $this->createUnexpectedException($this->peek());
    }
    /**
     * @param string $type
     */
    protected function match($type) : bool
    {
        return $this->peek()->getType() === $type;
    }
    protected function scanNumber() : \PoP\GraphQLParser\Spec\Parser\Token
    {
        $start = $this->pos;
        if ($this->source[$this->pos] === '-') {
            ++$this->pos;
        }
        $this->skipInteger();
        if (isset($this->source[$this->pos]) && $this->source[$this->pos] === '.') {
            $this->pos++;
            $this->skipInteger();
        }
        $value = \substr($this->source, $start, $this->pos - $start);
        if (\strpos($value, '.') === \false) {
            $value = (int) $value;
        } else {
            $value = (float) $value;
        }
        return new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_NUMBER, $this->getLine(), $this->getColumn(), $value);
    }
    protected function skipInteger() : void
    {
        $sourceLength = \strlen($this->source);
        while ($this->pos < $sourceLength) {
            $ch = $this->source[$this->pos];
            if ('0' <= $ch && $ch <= '9') {
                $this->pos++;
            } else {
                break;
            }
        }
    }
    protected function getLocation() : \PoP\GraphQLParser\Spec\Parser\Location
    {
        return new \PoP\GraphQLParser\Spec\Parser\Location($this->getLine(), $this->getColumn());
    }
    protected function getColumn() : int
    {
        return $this->pos - $this->lineStart;
    }
    protected function getLine() : int
    {
        return $this->line;
    }
    /**
     * @throws SyntaxErrorParserException
     * @see http://facebook.github.io/graphql/October2016/#sec-String-Value
     * @param bool $isBlockString
     */
    protected function scanString($isBlockString) : \PoP\GraphQLParser\Spec\Parser\Token
    {
        $sourceLength = \strlen($this->source);
        $this->pos++;
        $value = '';
        $blockStringNewLines = 0;
        $blockStringLineStart = $this->lineStart;
        while ($this->pos < $sourceLength) {
            $ch = $this->source[$this->pos];
            if ($isBlockString) {
                if ($this->pos + 2 < $sourceLength) {
                    $chars = \substr($this->source, $this->pos, 3);
                    if ($chars === '"""') {
                        $token = new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_BLOCK_STRING, $this->getLine(), $this->getColumn(), $value);
                        $this->line += $blockStringNewLines;
                        $this->lineStart = $blockStringLineStart;
                        $this->pos += 3;
                        return $token;
                    }
                }
            } else {
                if ($ch === '"') {
                    $token = new \PoP\GraphQLParser\Spec\Parser\Token(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_STRING, $this->getLine(), $this->getColumn(), $value);
                    $this->pos++;
                    return $token;
                }
            }
            if ($ch === '\\' && $this->pos < $sourceLength - 1) {
                $this->pos++;
                $ch = $this->source[$this->pos];
                switch ($ch) {
                    case '"':
                    case '\\':
                    case '/':
                        break;
                    case 'b':
                        $ch = \sprintf("%c", 8);
                        break;
                    case 'f':
                        $ch = "\f";
                        break;
                    case 'n':
                        $ch = "\n";
                        break;
                    case 'r':
                        $ch = "\r";
                        break;
                    case 'u':
                        $codepoint = \substr($this->source, $this->pos + 1, 4);
                        if (!\preg_match('/[0-9A-Fa-f]{4}/', $codepoint)) {
                            throw new SyntaxErrorParserException(new FeedbackItemResolution(GraphQLParserErrorFeedbackItemProvider::class, GraphQLParserErrorFeedbackItemProvider::E_3, [$codepoint]), $this->getLocation());
                        }
                        $ch = \html_entity_decode("&#x{$codepoint};", \ENT_QUOTES, 'UTF-8');
                        $this->pos += 4;
                        break;
                    default:
                        throw new SyntaxErrorParserException(new FeedbackItemResolution(GraphQLParserErrorFeedbackItemProvider::class, GraphQLParserErrorFeedbackItemProvider::E_4, [$ch]), $this->getLocation());
                }
            }
            $value .= $ch;
            $this->pos++;
            if ($ch === \PHP_EOL && $isBlockString) {
                $blockStringNewLines++;
                $blockStringLineStart = $this->pos;
            }
        }
        throw $this->createUnexpectedTokenTypeException(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_END);
    }
    protected function end() : bool
    {
        return $this->lookAhead->getType() === \PoP\GraphQLParser\Spec\Parser\Token::TYPE_END;
    }
    protected function peek() : \PoP\GraphQLParser\Spec\Parser\Token
    {
        return $this->lookAhead;
    }
    protected function lex() : \PoP\GraphQLParser\Spec\Parser\Token
    {
        $prev = $this->lookAhead;
        $this->lookAhead = $this->next();
        return $prev;
    }
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Token $token
     */
    protected function createUnexpectedException($token) : SyntaxErrorParserException
    {
        return $this->createUnexpectedTokenTypeException($token->getType());
    }
    /**
     * @param string $tokenType
     */
    protected function createUnexpectedTokenTypeException($tokenType) : SyntaxErrorParserException
    {
        return new SyntaxErrorParserException(new FeedbackItemResolution(GraphQLParserErrorFeedbackItemProvider::class, GraphQLParserErrorFeedbackItemProvider::E_6, [\PoP\GraphQLParser\Spec\Parser\Token::tokenName($tokenType)]), $this->getLocation());
    }
}
