<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser;

use PoP\GraphQLParser\Exception\FeatureNotSupportedException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorParserException;
use PoP\GraphQLParser\Exception\Parser\UnsupportedSyntaxErrorParserException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLParserErrorFeedbackItemProvider;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLUnsupportedFeatureErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Enum;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\MutationOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Ast\SubscriptionOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\Variable;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use stdClass;
class Parser extends \PoP\GraphQLParser\Spec\Parser\Tokenizer implements \PoP\GraphQLParser\Spec\Parser\ParserInterface
{
    /** @var OperationInterface[] */
    protected $operations;
    /** @var Fragment[] */
    protected $fragments;
    /** @var Variable[] */
    protected $variables;
    /**
     * @throws SyntaxErrorParserException
     * @throws FeatureNotSupportedException
     * @throws UnsupportedSyntaxErrorParserException
     * @param string $source
     */
    public function parse($source) : Document
    {
        $this->init($source);
        while (!$this->end()) {
            $token = $this->peek();
            $tokenType = $token->getType();
            switch ($tokenType) {
                case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_LBRACE:
                case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_QUERY:
                case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_MUTATION:
                case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_SUBSCRIPTION:
                    $this->operations[] = $this->parseOperation($tokenType);
                    break;
                case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_FRAGMENT:
                    $this->fragments[] = $this->parseFragment();
                    break;
                default:
                    throw new SyntaxErrorParserException(new FeedbackItemResolution(GraphQLParserErrorFeedbackItemProvider::class, GraphQLParserErrorFeedbackItemProvider::E_1, [$this->lookAhead->getData()]), $this->getLocation());
            }
        }
        return $this->createDocument($this->operations, $this->fragments);
    }
    /**
     * @param OperationInterface[] $operations
     * @param Fragment[] $fragments
     */
    protected function createDocument($operations, $fragments) : Document
    {
        return new Document($operations, $fragments);
    }
    /**
     * @param string $source
     */
    protected function init($source) : void
    {
        $this->initTokenizer($source);
        $this->resetState();
    }
    protected function resetState() : void
    {
        $this->operations = [];
        $this->fragments = [];
        $this->variables = [];
    }
    /**
     * @throws UnsupportedSyntaxErrorParserException
     * @param string $type
     */
    protected function parseOperation($type) : OperationInterface
    {
        $directives = [];
        $variables = [];
        $this->variables = [];
        $this->beforeParsingOperation();
        $isShorthandQuery = $this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_LBRACE);
        if ($isShorthandQuery) {
            $lbraceToken = $this->lex();
            /**
             * Query shorthand: it has no name, variables or directives
             * @see https://spec.graphql.org/draft/#sec-Language.Operations.Query-shorthand
             */
            $operationName = '';
            $operationLocation = $this->getTokenLocation($lbraceToken);
        } else {
            // Eat: $this->matchMulti([Token::TYPE_QUERY, Token::TYPE_MUTATION, Token::TYPE_SUBSCRIPTION])
            $this->lex();
            $operationToken = $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_IDENTIFIER);
            if ($operationToken !== null) {
                $operationName = (string) $operationToken->getData();
                $operationLocation = $this->getTokenLocation($operationToken);
            } else {
                $operationName = '';
                $operationLocation = $this->getLocation();
            }
            if ($this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_LPAREN)) {
                $variables = $this->parseVariables();
            }
            if ($this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_AT)) {
                $directives = $this->parseDirectiveList();
            }
            $lbraceToken = $this->lex();
        }
        $this->afterParsingOperation();
        $fieldsOrFragmentBonds = [];
        $this->beforeParsingFieldsOrFragmentBonds();
        while (!$this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_RBRACE) && !$this->end()) {
            $this->eatMulti([\PoP\GraphQLParser\Spec\Parser\Token::TYPE_COMMA]);
            $fieldOrFragmentBond = $this->parseBodyItem($type);
            $fieldsOrFragmentBonds[] = $fieldOrFragmentBond;
        }
        $this->afterParsingFieldsOrFragmentBonds();
        $this->expect(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_RBRACE);
        if ($type === \PoP\GraphQLParser\Spec\Parser\Token::TYPE_MUTATION) {
            return $this->createMutationOperation($operationName, $variables, $directives, $fieldsOrFragmentBonds, $operationLocation);
        }
        if ($type === \PoP\GraphQLParser\Spec\Parser\Token::TYPE_SUBSCRIPTION) {
            return $this->createSubscriptionOperation($operationName, $variables, $directives, $fieldsOrFragmentBonds, $operationLocation);
        }
        return $this->createQueryOperation($operationName, $variables, $directives, $fieldsOrFragmentBonds, $operationLocation);
    }
    /**
     * @param Variable[] $variables
     * @param Directive[] $directives
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @param string $name
     * @param \PoP\GraphQLParser\Spec\Parser\Location $location
     */
    protected function createQueryOperation($name, $variables, $directives, $fieldsOrFragmentBonds, $location) : QueryOperation
    {
        return new QueryOperation($name, $variables, $directives, $fieldsOrFragmentBonds, $location);
    }
    /**
     * @param Variable[] $variables
     * @param Directive[] $directives
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @param string $name
     * @param \PoP\GraphQLParser\Spec\Parser\Location $location
     */
    protected function createMutationOperation($name, $variables, $directives, $fieldsOrFragmentBonds, $location) : MutationOperation
    {
        return new MutationOperation($name, $variables, $directives, $fieldsOrFragmentBonds, $location);
    }
    /**
     * @param Variable[] $variables
     * @param Directive[] $directives
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @param string $name
     * @param \PoP\GraphQLParser\Spec\Parser\Location $location
     */
    protected function createSubscriptionOperation($name, $variables, $directives, $fieldsOrFragmentBonds, $location) : SubscriptionOperation
    {
        return new SubscriptionOperation($name, $variables, $directives, $fieldsOrFragmentBonds, $location);
    }
    /**
     * @return array<FieldInterface|FragmentBondInterface>
     * @param string $token
     */
    protected function parseBody($token) : array
    {
        $fieldsOrFragmentBonds = [];
        $this->lex();
        while (!$this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_RBRACE) && !$this->end()) {
            $this->eatMulti([\PoP\GraphQLParser\Spec\Parser\Token::TYPE_COMMA]);
            if ($this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_FRAGMENT_REFERENCE)) {
                $this->lex();
                if ($this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_ON)) {
                    $fieldsOrFragmentBonds[] = $this->parseBodyItem(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_INLINE_FRAGMENT);
                } else {
                    $fieldsOrFragmentBonds[] = $this->parseFragmentReference();
                }
            } else {
                $fieldsOrFragmentBonds[] = $this->parseBodyItem($token);
            }
        }
        $this->expect(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_RBRACE);
        return $fieldsOrFragmentBonds;
    }
    /**
     * @return Variable[]
     * @throws UnsupportedSyntaxErrorParserException
     */
    protected function parseVariables() : array
    {
        $variables = [];
        $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_LPAREN);
        while (!$this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_RPAREN) && !$this->end()) {
            $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_COMMA);
            /** @var Token */
            $variableToken = $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_VARIABLE);
            $nameToken = $this->eatIdentifierToken();
            $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_COLON);
            $isArray = \false;
            $isArrayElementRequired = \false;
            $isArrayOfArrays = \false;
            $isArrayOfArraysElementRequired = \false;
            if ($this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_LSQUARE_BRACE)) {
                $isArray = \true;
                $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_LSQUARE_BRACE);
                if ($this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_LSQUARE_BRACE)) {
                    $isArrayOfArrays = \true;
                    $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_LSQUARE_BRACE);
                    /**
                     * The GraphQL server currently supports receiving up to
                     * 2 levels of List cardinality (eg: [[String]]), so if any
                     * variable is defined surpassing this (eg: [[[String]]]),
                     * then return an error
                     */
                    if ($this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_LSQUARE_BRACE)) {
                        throw new UnsupportedSyntaxErrorParserException(new FeedbackItemResolution(GraphQLUnsupportedFeatureErrorFeedbackItemProvider::class, GraphQLUnsupportedFeatureErrorFeedbackItemProvider::E_4), $this->getTokenLocation($variableToken));
                    }
                    $type = $this->eatIdentifierToken()->getData();
                    if ($this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_REQUIRED)) {
                        $isArrayOfArraysElementRequired = \true;
                        $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_REQUIRED);
                    }
                    $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_RSQUARE_BRACE);
                } else {
                    $type = $this->eatIdentifierToken()->getData();
                }
                if ($this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_REQUIRED)) {
                    $isArrayElementRequired = \true;
                    $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_REQUIRED);
                }
                $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_RSQUARE_BRACE);
            } else {
                $type = $this->eatIdentifierToken()->getData();
            }
            $isRequired = \false;
            if ($this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_REQUIRED)) {
                $isRequired = \true;
                $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_REQUIRED);
            }
            $directives = [];
            if ($this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_AT)) {
                $directives = $this->parseDirectiveList();
            }
            $variable = $this->createVariable((string) $nameToken->getData(), (string) $type, $isRequired, $isArray, $isArrayElementRequired, $isArrayOfArrays, $isArrayOfArraysElementRequired, $directives, $this->getTokenLocation($variableToken));
            if ($this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_EQUAL)) {
                $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_EQUAL);
                /** @var InputList|InputObject|Literal|Enum */
                $defaultValueAst = $this->parseValue();
                $variable->setDefaultValueAST($defaultValueAst);
            }
            $this->variables[] = $variable;
            $variables[] = $variable;
        }
        $this->expect(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_RPAREN);
        return $variables;
    }
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Token $token
     */
    protected function getTokenLocation($token) : \PoP\GraphQLParser\Spec\Parser\Location
    {
        return new \PoP\GraphQLParser\Spec\Parser\Location($token->getLine(), $token->getColumn());
    }
    /**
     * @param Directive[] $directives
     * @param string $name
     * @param string $type
     * @param bool $isRequired
     * @param bool $isArray
     * @param bool $isArrayElementRequired
     * @param bool $isArrayOfArrays
     * @param bool $isArrayOfArraysElementRequired
     * @param \PoP\GraphQLParser\Spec\Parser\Location $location
     */
    protected function createVariable($name, $type, $isRequired, $isArray, $isArrayElementRequired, $isArrayOfArrays, $isArrayOfArraysElementRequired, $directives, $location) : Variable
    {
        return new Variable($name, $type, $isRequired, $isArray, $isArrayElementRequired, $isArrayOfArrays, $isArrayOfArraysElementRequired, $directives, $location);
    }
    /**
     * @param string[] $types
     * @throws SyntaxErrorParserException
     */
    protected function expectMulti($types) : \PoP\GraphQLParser\Spec\Parser\Token
    {
        if ($this->matchMulti($types)) {
            return $this->lex();
        }
        throw $this->createUnexpectedException($this->peek());
    }
    /**
     * @throws SyntaxErrorParserException
     */
    protected function parseVariableReference() : VariableReference
    {
        $startToken = $this->expectMulti([\PoP\GraphQLParser\Spec\Parser\Token::TYPE_VARIABLE]);
        if ($this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_IDENTIFIER) || $this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_QUERY)) {
            $name = $this->lex()->getData();
            $variable = $this->findVariable($name);
            return $this->createVariableReference($name, $variable, $this->getTokenLocation($startToken));
        }
        throw $this->createUnexpectedException($this->peek());
    }
    /**
     * @param string $name
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Variable|null $variable
     * @param \PoP\GraphQLParser\Spec\Parser\Location $location
     */
    protected function createVariableReference($name, $variable, $location) : VariableReference
    {
        return new VariableReference($name, $variable, $location);
    }
    /**
     * @param string $name
     */
    protected function findVariable($name) : ?Variable
    {
        foreach ($this->variables as $variable) {
            if ($variable->getName() === $name) {
                return $variable;
            }
        }
        return null;
    }
    /**
     * @throws SyntaxErrorParserException
     */
    protected function parseFragmentReference() : FragmentReference
    {
        $nameToken = $this->eatIdentifierToken();
        return $this->createFragmentReference($nameToken->getData(), $this->getTokenLocation($nameToken));
    }
    /**
     * @param string $name
     * @param \PoP\GraphQLParser\Spec\Parser\Location $location
     */
    protected function createFragmentReference($name, $location) : FragmentReference
    {
        return new FragmentReference($name, $location);
    }
    /**
     * @throws SyntaxErrorParserException
     */
    protected function eatIdentifierToken() : \PoP\GraphQLParser\Spec\Parser\Token
    {
        return $this->expectMulti([
            \PoP\GraphQLParser\Spec\Parser\Token::TYPE_IDENTIFIER,
            // Accept also field/directive arguments "query", "on", etc
            \PoP\GraphQLParser\Spec\Parser\Token::TYPE_QUERY,
            \PoP\GraphQLParser\Spec\Parser\Token::TYPE_MUTATION,
            \PoP\GraphQLParser\Spec\Parser\Token::TYPE_SUBSCRIPTION,
            \PoP\GraphQLParser\Spec\Parser\Token::TYPE_FRAGMENT,
            \PoP\GraphQLParser\Spec\Parser\Token::TYPE_ON,
        ]);
    }
    /**
     * @throws SyntaxErrorParserException
     * @return \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface|\PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface
     * @param string $type
     */
    protected function parseBodyItem($type)
    {
        $nameToken = $this->eatIdentifierToken();
        $alias = null;
        if ($this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_COLON)) {
            $alias = $nameToken->getData();
            $nameToken = $this->eatIdentifierToken();
        }
        $bodyLocation = $this->getTokenLocation($nameToken);
        $arguments = $this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_LPAREN) ? $this->parseArgumentList() : [];
        $directives = $this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_AT) ? $this->parseDirectiveList() : [];
        if ($this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_LBRACE)) {
            $this->beforeParsingFieldsOrFragmentBonds();
            /** @var array<FieldInterface|FragmentBondInterface> */
            $fieldsOrFragmentBonds = $this->parseBody($type === \PoP\GraphQLParser\Spec\Parser\Token::TYPE_INLINE_FRAGMENT ? \PoP\GraphQLParser\Spec\Parser\Token::TYPE_QUERY : $type);
            $this->afterParsingFieldsOrFragmentBonds();
            if (!$fieldsOrFragmentBonds) {
                throw $this->createUnexpectedTokenTypeException($this->lookAhead->getType());
            }
            if ($type === \PoP\GraphQLParser\Spec\Parser\Token::TYPE_INLINE_FRAGMENT) {
                return $this->createInlineFragment($nameToken->getData(), $fieldsOrFragmentBonds, $directives, $bodyLocation);
            }
            return $this->createRelationalField($nameToken->getData(), $alias, $arguments, $fieldsOrFragmentBonds, $directives, $bodyLocation);
        }
        return $this->createLeafField($nameToken->getData(), $alias, $arguments, $directives, $bodyLocation);
    }
    /**
     * Allow to override, to support ObjectResolvedFieldValueReferences
     */
    protected function beforeParsingOperation() : void
    {
    }
    /**
     * Allow to override, to support ObjectResolvedFieldValueReferences
     */
    protected function afterParsingOperation() : void
    {
    }
    /**
     * Allow to override, to support ObjectResolvedFieldValueReferences
     */
    protected function beforeParsingFieldsOrFragmentBonds() : void
    {
    }
    /**
     * Allow to override, to support ObjectResolvedFieldValueReferences
     */
    protected function afterParsingFieldsOrFragmentBonds() : void
    {
    }
    /**
     * @param Argument[] $arguments
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @param Directive[] $directives
     * @param string $name
     * @param string|null $alias
     * @param \PoP\GraphQLParser\Spec\Parser\Location $location
     */
    protected function createRelationalField($name, $alias, $arguments, $fieldsOrFragmentBonds, $directives, $location) : RelationalField
    {
        return new RelationalField($name, $alias, $arguments, $fieldsOrFragmentBonds, $directives, $location);
    }
    /**
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @param Directive[] $directives
     * @param string $typeName
     * @param \PoP\GraphQLParser\Spec\Parser\Location $location
     */
    protected function createInlineFragment($typeName, $fieldsOrFragmentBonds, $directives, $location) : InlineFragment
    {
        return new InlineFragment($typeName, $fieldsOrFragmentBonds, $directives, $location);
    }
    /**
     * @param Argument[] $arguments
     * @param Directive[] $directives
     * @param string $name
     * @param string|null $alias
     * @param \PoP\GraphQLParser\Spec\Parser\Location $location
     */
    protected function createLeafField($name, $alias, $arguments, $directives, $location) : LeafField
    {
        return new LeafField($name, $alias, $arguments, $directives, $location);
    }
    /**
     * @return Argument[]
     */
    protected function parseArgumentList() : array
    {
        $args = [];
        $this->expect(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_LPAREN);
        while (!$this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_RPAREN) && !$this->end()) {
            $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_COMMA);
            $args[] = $this->parseArgument();
        }
        $this->expect(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_RPAREN);
        return $args;
    }
    protected function parseArgument() : Argument
    {
        $nameToken = $this->eatIdentifierToken();
        $this->expect(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_COLON);
        $value = $this->parseValue();
        return $this->createArgument($nameToken->getData(), $value, $this->getTokenLocation($nameToken));
    }
    /**
     * @param string $name
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface $value
     * @param \PoP\GraphQLParser\Spec\Parser\Location $location
     */
    protected function createArgument($name, $value, $location) : Argument
    {
        return new Argument($name, $value, $location);
    }
    /**
     * @return Directive[]
     */
    protected function parseDirectiveList() : array
    {
        $directives = [];
        while ($this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_AT)) {
            $directives[] = $this->parseDirective();
            $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_COMMA);
        }
        return $directives;
    }
    protected function parseDirective() : Directive
    {
        $this->expect(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_AT);
        $this->beforeParsingDirectiveArgumentList();
        $nameToken = $this->eatIdentifierToken();
        $args = $this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_LPAREN) ? $this->parseArgumentList() : [];
        $this->afterParsingDirectiveArgumentList();
        return $this->createDirective($nameToken->getData(), $args, $this->getTokenLocation($nameToken));
    }
    /**
     * Allow to override, to support ObjectResolvedFieldValueReferences
     */
    protected function beforeParsingDirectiveArgumentList() : void
    {
    }
    /**
     * Allow to override, to support ObjectResolvedFieldValueReferences
     */
    protected function afterParsingDirectiveArgumentList() : void
    {
    }
    /**
     * @param Argument[] $arguments
     * @param string $name
     * @param \PoP\GraphQLParser\Spec\Parser\Location $location
     */
    protected function createDirective($name, $arguments, $location) : Directive
    {
        return new Directive($name, $arguments, $location);
    }
    /**
     * @throws SyntaxErrorParserException
     * @return \PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList|\PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject|\PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal|\PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Enum|\PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference
     */
    protected function parseValue()
    {
        switch ($this->lookAhead->getType()) {
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_LSQUARE_BRACE:
                return $this->parseList();
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_LBRACE:
                return $this->parseObject();
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_VARIABLE:
                return $this->parseVariableReference();
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_NUMBER:
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_BLOCK_STRING:
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_STRING:
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_NULL:
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_TRUE:
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_FALSE:
                $token = $this->lex();
                return $this->createLiteral($token->getData(), $this->getTokenLocation($token));
            case \PoP\GraphQLParser\Spec\Parser\Token::TYPE_IDENTIFIER:
                $token = $this->lex();
                return $this->createEnum($token->getData(), $this->getTokenLocation($token));
        }
        throw $this->createUnexpectedException($this->lookAhead);
    }
    /**
     * @param string|int|float|bool|null $value
     * @param \PoP\GraphQLParser\Spec\Parser\Location $location
     */
    public function createLiteral($value, $location) : Literal
    {
        return new Literal($value, $location);
    }
    /**
     * @param string $enumValue
     * @param \PoP\GraphQLParser\Spec\Parser\Location $location
     */
    public function createEnum($enumValue, $location) : Enum
    {
        return new Enum($enumValue, $location);
    }
    protected function parseList() : InputList
    {
        /** @var Token */
        $startToken = $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_LSQUARE_BRACE);
        $list = [];
        while (!$this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_RSQUARE_BRACE) && !$this->end()) {
            $list[] = $this->parseValue();
            $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_COMMA);
        }
        $this->expect(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_RSQUARE_BRACE);
        return $this->createInputList($list, $this->getTokenLocation($startToken));
    }
    /**
     * @param mixed[] $list
     * @param \PoP\GraphQLParser\Spec\Parser\Location $location
     */
    protected function createInputList($list, $location) : InputList
    {
        return new InputList($list, $location);
    }
    /**
     * @throws SyntaxErrorParserException
     */
    protected function parseObject() : InputObject
    {
        /** @var Token */
        $startToken = $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_LBRACE);
        // Use stdClass instead of array
        $object = new stdClass();
        while (!$this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_RBRACE) && !$this->end()) {
            $keyToken = $this->expectMulti([
                \PoP\GraphQLParser\Spec\Parser\Token::TYPE_STRING,
                \PoP\GraphQLParser\Spec\Parser\Token::TYPE_IDENTIFIER,
                // Accept also object keys "query", "on", etc
                \PoP\GraphQLParser\Spec\Parser\Token::TYPE_QUERY,
                \PoP\GraphQLParser\Spec\Parser\Token::TYPE_MUTATION,
                \PoP\GraphQLParser\Spec\Parser\Token::TYPE_SUBSCRIPTION,
                \PoP\GraphQLParser\Spec\Parser\Token::TYPE_FRAGMENT,
                \PoP\GraphQLParser\Spec\Parser\Token::TYPE_ON,
            ]);
            $key = $keyToken->getData();
            $this->expect(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_COLON);
            $value = $this->parseValue();
            $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_COMMA);
            // Validate no duplicated keys in InputObject
            if (\property_exists($object, $key)) {
                throw new SyntaxErrorParserException(new FeedbackItemResolution(GraphQLSpecErrorFeedbackItemProvider::class, GraphQLSpecErrorFeedbackItemProvider::E_5_6_3, [$key]), $this->getTokenLocation($keyToken));
            }
            $object->{$key} = $value;
        }
        $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_RBRACE);
        return $this->createInputObject($object, $this->getTokenLocation($startToken));
    }
    /**
     * @param \stdClass $object
     * @param \PoP\GraphQLParser\Spec\Parser\Location $location
     */
    protected function createInputObject($object, $location) : InputObject
    {
        return new InputObject($object, $location);
    }
    /**
     * @throws SyntaxErrorParserException
     */
    protected function parseFragment() : Fragment
    {
        $this->lex();
        $nameToken = $this->eatIdentifierToken();
        $this->eat(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_ON);
        $model = $this->eatIdentifierToken();
        $directives = $this->match(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_AT) ? $this->parseDirectiveList() : [];
        $this->beforeParsingFieldsOrFragmentBonds();
        $fieldsOrFragmentBonds = $this->parseBody(\PoP\GraphQLParser\Spec\Parser\Token::TYPE_QUERY);
        $this->afterParsingFieldsOrFragmentBonds();
        return $this->createFragment($nameToken->getData(), $model->getData(), $directives, $fieldsOrFragmentBonds, $this->getTokenLocation($nameToken));
    }
    /**
     * @param Directive[] $directives
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @param string $name
     * @param string $model
     * @param \PoP\GraphQLParser\Spec\Parser\Location $location
     */
    protected function createFragment($name, $model, $directives, $fieldsOrFragmentBonds, $location) : Fragment
    {
        return new Fragment($name, $model, $directives, $fieldsOrFragmentBonds, $location);
    }
    /**
     * @param string $type
     */
    protected function eat($type) : ?\PoP\GraphQLParser\Spec\Parser\Token
    {
        if ($this->match($type)) {
            return $this->lex();
        }
        return null;
    }
    /**
     * @param string[] $types
     */
    protected function eatMulti($types) : ?\PoP\GraphQLParser\Spec\Parser\Token
    {
        if ($this->matchMulti($types)) {
            return $this->lex();
        }
        return null;
    }
    /**
     * @param string[] $types
     */
    protected function matchMulti($types) : bool
    {
        foreach ($types as $type) {
            if ($this->peek()->getType() === $type) {
                return \true;
            }
        }
        return \false;
    }
}
