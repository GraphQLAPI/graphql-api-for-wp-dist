<?php

/*
* This file is a part of graphql-youshido project.
*
* @author Portey Vasil <portey@gmail.com>
* created: 11/23/15 1:22 AM
*/
namespace GraphQLByPoP\GraphQLParser\Parser;

use GraphQLByPoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Argument;
use GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\InputList;
use GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\InputObject;
use GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\Literal;
use GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\VariableReference;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Directive;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Field;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Fragment;
use GraphQLByPoP\GraphQLParser\Parser\Ast\FragmentReference;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Mutation;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Query;
use GraphQLByPoP\GraphQLParser\Parser\Ast\TypedFragmentReference;
class Parser extends \GraphQLByPoP\GraphQLParser\Parser\Tokenizer
{
    /** @var array */
    private $data = [];
    public function parse($source = null)
    {
        $this->init($source);
        while (!$this->end()) {
            $tokenType = $this->peek()->getType();
            switch ($tokenType) {
                case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_LBRACE:
                    foreach ($this->parseBody() as $query) {
                        $this->data['queries'][] = $query;
                    }
                    break;
                case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_QUERY:
                    list($operationName, $queries) = $this->parseOperation(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_QUERY);
                    $this->data['queryOperations'][] = ['name' => $operationName, 'position' => \count($this->data['queries']), 'numberItems' => \count($queries)];
                    foreach ($queries as $query) {
                        $this->data['queries'][] = $query;
                    }
                    break;
                case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_MUTATION:
                    list($operationName, $mutations) = $this->parseOperation(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_MUTATION);
                    if ($operationName) {
                        $this->data['mutationOperations'][] = ['name' => $operationName, 'position' => \count($this->data['mutations']), 'numberItems' => \count($mutations)];
                    }
                    foreach ($mutations as $query) {
                        $this->data['mutations'][] = $query;
                    }
                    break;
                case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_FRAGMENT:
                    $this->data['fragments'][] = $this->parseFragment();
                    break;
                default:
                    throw new SyntaxErrorException('Incorrect request syntax', $this->getLocation());
            }
        }
        return $this->data;
    }
    private function init($source = null)
    {
        $this->initTokenizer($source);
        $this->data = ['queryOperations' => [], 'mutationOperations' => [], 'queries' => [], 'mutations' => [], 'fragments' => [], 'fragmentReferences' => [], 'variables' => [], 'variableReferences' => []];
    }
    protected function parseOperation($type = \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_QUERY)
    {
        $operation = null;
        $directives = [];
        $operationName = null;
        if ($this->matchMulti([\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_QUERY, \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_MUTATION])) {
            $this->lex();
            $operationInfo = $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_IDENTIFIER);
            if (!\is_null($operationInfo)) {
                $operationName = $operationInfo->getData();
            }
            if ($this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_LPAREN)) {
                $this->parseVariables();
            }
            if ($this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_AT)) {
                $directives = $this->parseDirectiveList();
            }
        }
        $this->lex();
        $fields = [];
        while (!$this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_RBRACE) && !$this->end()) {
            $this->eatMulti([\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_COMMA]);
            $operation = $this->parseBodyItem($type, \true);
            $operation->setDirectives(\array_merge($directives, $operation->getDirectives()));
            $fields[] = $operation;
        }
        $this->expect(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_RBRACE);
        return [$operationName, $fields];
    }
    protected function parseBody($token = \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_QUERY, $highLevel = \true)
    {
        $fields = [];
        $this->lex();
        while (!$this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_RBRACE) && !$this->end()) {
            $this->eatMulti([\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_COMMA]);
            if ($this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_FRAGMENT_REFERENCE)) {
                $this->lex();
                if ($this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_ON)) {
                    $fields[] = $this->parseBodyItem(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_TYPED_FRAGMENT, $highLevel);
                } else {
                    $fields[] = $this->parseFragmentReference();
                }
            } else {
                $fields[] = $this->parseBodyItem($token, $highLevel);
            }
        }
        $this->expect(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_RBRACE);
        return $fields;
    }
    protected function parseVariables()
    {
        $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_LPAREN);
        while (!$this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_RPAREN) && !$this->end()) {
            $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_COMMA);
            $variableToken = $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_VARIABLE);
            $nameToken = $this->eatIdentifierToken();
            $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_COLON);
            $isArray = \false;
            $arrayElementNullable = \true;
            if ($this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_LSQUARE_BRACE)) {
                $isArray = \true;
                $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_LSQUARE_BRACE);
                $type = $this->eatIdentifierToken()->getData();
                if ($this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_REQUIRED)) {
                    $arrayElementNullable = \false;
                    $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_REQUIRED);
                }
                $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_RSQUARE_BRACE);
            } else {
                $type = $this->eatIdentifierToken()->getData();
            }
            $required = \false;
            if ($this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_REQUIRED)) {
                $required = \true;
                $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_REQUIRED);
            }
            $variable = new Variable($nameToken->getData(), $type, $required, $isArray, $arrayElementNullable, new \GraphQLByPoP\GraphQLParser\Parser\Location($variableToken->getLine(), $variableToken->getColumn()));
            if ($this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_EQUAL)) {
                $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_EQUAL);
                $variable->setDefaultValue($this->parseValue());
            }
            $this->data['variables'][] = $variable;
        }
        $this->expect(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_RPAREN);
    }
    protected function expectMulti($types)
    {
        if ($this->matchMulti($types)) {
            return $this->lex();
        }
        throw $this->createUnexpectedException($this->peek());
    }
    protected function parseVariableReference()
    {
        $startToken = $this->expectMulti([\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_VARIABLE]);
        if ($this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_NUMBER) || $this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_IDENTIFIER) || $this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_QUERY)) {
            $name = $this->lex()->getData();
            $variable = $this->findVariable($name);
            if ($variable) {
                $variable->setUsed(\true);
            }
            $variableReference = new VariableReference($name, $variable, new \GraphQLByPoP\GraphQLParser\Parser\Location($startToken->getLine(), $startToken->getColumn()));
            $this->data['variableReferences'][] = $variableReference;
            return $variableReference;
        }
        throw $this->createUnexpectedException($this->peek());
    }
    protected function findVariable($name)
    {
        foreach ((array) $this->data['variables'] as $variable) {
            /** @var $variable Variable */
            if ($variable->getName() === $name) {
                return $variable;
            }
        }
        return null;
    }
    protected function parseFragmentReference()
    {
        $nameToken = $this->eatIdentifierToken();
        $fragmentReference = new FragmentReference($nameToken->getData(), new \GraphQLByPoP\GraphQLParser\Parser\Location($nameToken->getLine(), $nameToken->getColumn()));
        $this->data['fragmentReferences'][] = $fragmentReference;
        return $fragmentReference;
    }
    protected function eatIdentifierToken()
    {
        return $this->expectMulti([\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_IDENTIFIER, \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_MUTATION, \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_QUERY, \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_FRAGMENT]);
    }
    protected function parseBodyItem($type = \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_QUERY, $highLevel = \true)
    {
        $nameToken = $this->eatIdentifierToken();
        $alias = null;
        if ($this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_COLON)) {
            $alias = $nameToken->getData();
            $nameToken = $this->eatIdentifierToken();
        }
        $bodyLocation = new \GraphQLByPoP\GraphQLParser\Parser\Location($nameToken->getLine(), $nameToken->getColumn());
        $arguments = $this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_LPAREN) ? $this->parseArgumentList() : [];
        $directives = $this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_AT) ? $this->parseDirectiveList() : [];
        if ($this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_LBRACE)) {
            $fields = $this->parseBody($type === \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_TYPED_FRAGMENT ? \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_QUERY : $type, \false);
            if (!$fields) {
                throw $this->createUnexpectedTokenTypeException($this->lookAhead->getType());
            }
            if ($type === \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_QUERY) {
                return new Query($nameToken->getData(), $alias, $arguments, $fields, $directives, $bodyLocation);
            } elseif ($type === \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_TYPED_FRAGMENT) {
                return new TypedFragmentReference($nameToken->getData(), $fields, $directives, $bodyLocation);
            } else {
                return new Mutation($nameToken->getData(), $alias, $arguments, $fields, $directives, $bodyLocation);
            }
        } else {
            if ($highLevel && $type === \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_MUTATION) {
                return new Mutation($nameToken->getData(), $alias, $arguments, [], $directives, $bodyLocation);
            } elseif ($highLevel && $type === \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_QUERY) {
                return new Query($nameToken->getData(), $alias, $arguments, [], $directives, $bodyLocation);
            }
            return new Field($nameToken->getData(), $alias, $arguments, $directives, $bodyLocation);
        }
    }
    protected function parseArgumentList()
    {
        $args = [];
        $this->expect(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_LPAREN);
        while (!$this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_RPAREN) && !$this->end()) {
            $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_COMMA);
            $args[] = $this->parseArgument();
        }
        $this->expect(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_RPAREN);
        return $args;
    }
    protected function parseArgument()
    {
        $nameToken = $this->eatIdentifierToken();
        $this->expect(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_COLON);
        $value = $this->parseValue();
        return new Argument($nameToken->getData(), $value, new \GraphQLByPoP\GraphQLParser\Parser\Location($nameToken->getLine(), $nameToken->getColumn()));
    }
    protected function parseDirectiveList()
    {
        $directives = [];
        while ($this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_AT)) {
            $directives[] = $this->parseDirective();
            $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_COMMA);
        }
        return $directives;
    }
    protected function parseDirective()
    {
        $this->expect(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_AT);
        $nameToken = $this->eatIdentifierToken();
        $args = $this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_LPAREN) ? $this->parseArgumentList() : [];
        return new Directive($nameToken->getData(), $args, new \GraphQLByPoP\GraphQLParser\Parser\Location($nameToken->getLine(), $nameToken->getColumn()));
    }
    /**
     * @throws SyntaxErrorException
     * @return mixed[]|\GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\InputList|\GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\InputObject|\GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\Literal|\GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\VariableReference
     */
    protected function parseValue()
    {
        switch ($this->lookAhead->getType()) {
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_LSQUARE_BRACE:
                return $this->parseList();
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_LBRACE:
                return $this->parseObject();
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_VARIABLE:
                return $this->parseVariableReference();
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_NUMBER:
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_STRING:
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_IDENTIFIER:
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_NULL:
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_TRUE:
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_FALSE:
                $token = $this->lex();
                return new Literal($token->getData(), new \GraphQLByPoP\GraphQLParser\Parser\Location($token->getLine(), $token->getColumn()));
        }
        throw $this->createUnexpectedException($this->lookAhead);
    }
    protected function parseList($createType = \true)
    {
        $startToken = $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_LSQUARE_BRACE);
        $list = [];
        while (!$this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_RSQUARE_BRACE) && !$this->end()) {
            $list[] = $this->parseListValue();
            $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_COMMA);
        }
        $this->expect(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_RSQUARE_BRACE);
        return $createType ? new InputList($list, new \GraphQLByPoP\GraphQLParser\Parser\Location($startToken->getLine(), $startToken->getColumn())) : $list;
    }
    protected function parseListValue()
    {
        switch ($this->lookAhead->getType()) {
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_NUMBER:
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_STRING:
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_TRUE:
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_FALSE:
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_NULL:
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_IDENTIFIER:
                return $this->expect($this->lookAhead->getType())->getData();
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_VARIABLE:
                return $this->parseVariableReference();
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_LBRACE:
                return $this->parseObject(\true);
            case \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_LSQUARE_BRACE:
                return $this->parseList(\false);
        }
        throw new SyntaxErrorException('Can\'t parse argument', $this->getLocation());
    }
    protected function parseObject($createType = \true)
    {
        $startToken = $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_LBRACE);
        $object = [];
        while (!$this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_RBRACE) && !$this->end()) {
            $key = $this->expectMulti([\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_STRING, \GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_IDENTIFIER])->getData();
            $this->expect(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_COLON);
            $value = $this->parseListValue();
            $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_COMMA);
            $object[$key] = $value;
        }
        $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_RBRACE);
        return $createType ? new InputObject($object, new \GraphQLByPoP\GraphQLParser\Parser\Location($startToken->getLine(), $startToken->getColumn())) : $object;
    }
    protected function parseFragment()
    {
        $this->lex();
        $nameToken = $this->eatIdentifierToken();
        $this->eat(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_ON);
        $model = $this->eatIdentifierToken();
        $directives = $this->match(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_AT) ? $this->parseDirectiveList() : [];
        $fields = $this->parseBody(\GraphQLByPoP\GraphQLParser\Parser\Token::TYPE_QUERY, \false);
        return new Fragment($nameToken->getData(), $model->getData(), $directives, $fields, new \GraphQLByPoP\GraphQLParser\Parser\Location($nameToken->getLine(), $nameToken->getColumn()));
    }
    protected function eat($type)
    {
        if ($this->match($type)) {
            return $this->lex();
        }
        return null;
    }
    protected function eatMulti($types)
    {
        if ($this->matchMulti($types)) {
            return $this->lex();
        }
        return null;
    }
    protected function matchMulti($types)
    {
        foreach ((array) $types as $type) {
            if ($this->peek()->getType() === $type) {
                return \true;
            }
        }
        return \false;
    }
}
