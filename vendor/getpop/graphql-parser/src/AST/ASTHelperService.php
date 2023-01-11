<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\AST;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\Root\Services\BasicServiceTrait;
class ASTHelperService implements \PoP\GraphQLParser\AST\ASTHelperServiceInterface
{
    use BasicServiceTrait;
    /**
     * @var \PoP\GraphQLParser\AST\ASTNodeDuplicatorServiceInterface|null
     */
    private $astNodeDuplicatorService;
    /**
     * @param \PoP\GraphQLParser\AST\ASTNodeDuplicatorServiceInterface $astNodeDuplicatorService
     */
    public final function setASTNodeDuplicatorService($astNodeDuplicatorService) : void
    {
        $this->astNodeDuplicatorService = $astNodeDuplicatorService;
    }
    protected final function getASTNodeDuplicatorService() : \PoP\GraphQLParser\AST\ASTNodeDuplicatorServiceInterface
    {
        /** @var ASTNodeDuplicatorServiceInterface */
        return $this->astNodeDuplicatorService = $this->astNodeDuplicatorService ?? $this->instanceManager->getInstance(\PoP\GraphQLParser\AST\ASTNodeDuplicatorServiceInterface::class);
    }
    /**
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @param Fragment[] $fragments
     * @return FieldInterface[]
     */
    public function getAllFieldsFromFieldsOrFragmentBonds($fieldsOrFragmentBonds, $fragments) : array
    {
        /** @var FieldInterface[] */
        $fields = [];
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                /** @var FragmentReference */
                $fragmentReference = $fieldOrFragmentBond;
                $fragment = $this->getASTNodeDuplicatorService()->getExclusiveFragment($fragmentReference, $fragments);
                if ($fragment === null) {
                    continue;
                }
                $allFieldsFromFieldsOrFragmentBonds = $this->getAllFieldsFromFieldsOrFragmentBonds($fragment->getFieldsOrFragmentBonds(), $fragments);
                $fields = \array_merge($fields, $allFieldsFromFieldsOrFragmentBonds);
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                /** @var InlineFragment */
                $inlineFragment = $fieldOrFragmentBond;
                $allFieldsFromFieldsOrFragmentBonds = $this->getAllFieldsFromFieldsOrFragmentBonds($inlineFragment->getFieldsOrFragmentBonds(), $fragments);
                $fields = \array_merge($fields, $allFieldsFromFieldsOrFragmentBonds);
                continue;
            }
            /** @var FieldInterface */
            $field = $fieldOrFragmentBond;
            $fields[] = $field;
        }
        return $fields;
    }
    /**
     * @param Fragment[] $fragments
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $thisField
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $oppositeField
     */
    public function isFieldEquivalentToField($thisField, $oppositeField, $fragments) : bool
    {
        if (\get_class($thisField) !== \get_class($oppositeField)) {
            return \false;
        }
        if ($thisField instanceof LeafField) {
            /** @var LeafField */
            $thisLeafField = $thisField;
            /** @var LeafField */
            $againstLeafField = $oppositeField;
            return $thisLeafField->isEquivalentTo($againstLeafField);
        }
        /** @var RelationalField */
        $thisRelationalField = $thisField;
        /** @var RelationalField */
        $againstRelationalField = $oppositeField;
        return $thisRelationalField->isEquivalentTo($againstRelationalField, $fragments);
    }
}
