<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\App;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\CommentMutations\Module;
use PoPCMSSchema\CommentMutations\ModuleConfiguration;
use PoPCMSSchema\CommentMutations\Constants\MutationInputProperties;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
abstract class AbstractAddCommentToCustomPostFilterInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver|null
     */
    private $emailScalarTypeResolver;
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver|null
     */
    private $urlScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver $idScalarTypeResolver
     */
    public final function setIDScalarTypeResolver($idScalarTypeResolver) : void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    protected final function getIDScalarTypeResolver() : IDScalarTypeResolver
    {
        /** @var IDScalarTypeResolver */
        return $this->idScalarTypeResolver = $this->idScalarTypeResolver ?? $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    /**
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver $emailScalarTypeResolver
     */
    public final function setEmailScalarTypeResolver($emailScalarTypeResolver) : void
    {
        $this->emailScalarTypeResolver = $emailScalarTypeResolver;
    }
    protected final function getEmailScalarTypeResolver() : EmailScalarTypeResolver
    {
        /** @var EmailScalarTypeResolver */
        return $this->emailScalarTypeResolver = $this->emailScalarTypeResolver ?? $this->instanceManager->getInstance(EmailScalarTypeResolver::class);
    }
    /**
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver $urlScalarTypeResolver
     */
    public final function setURLScalarTypeResolver($urlScalarTypeResolver) : void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    protected final function getURLScalarTypeResolver() : URLScalarTypeResolver
    {
        /** @var URLScalarTypeResolver */
        return $this->urlScalarTypeResolver = $this->urlScalarTypeResolver ?? $this->instanceManager->getInstance(URLScalarTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver
     */
    public final function setStringScalarTypeResolver($stringScalarTypeResolver) : void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected final function getStringScalarTypeResolver() : StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver = $this->stringScalarTypeResolver ?? $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return \array_merge([MutationInputProperties::COMMENT => $this->getStringScalarTypeResolver()], $this->addCustomPostInputField() ? [MutationInputProperties::CUSTOMPOST_ID => $this->getIDScalarTypeResolver()] : [], $this->addParentCommentInputField() ? [MutationInputProperties::PARENT_COMMENT_ID => $this->getIDScalarTypeResolver()] : [], !$moduleConfiguration->mustUserBeLoggedInToAddComment() ? [MutationInputProperties::AUTHOR_NAME => $this->getStringScalarTypeResolver(), MutationInputProperties::AUTHOR_EMAIL => $this->getEmailScalarTypeResolver(), MutationInputProperties::AUTHOR_URL => $this->getURLScalarTypeResolver()] : []);
    }
    protected abstract function addCustomPostInputField() : bool;
    protected abstract function addParentCommentInputField() : bool;
    protected abstract function isParentCommentInputFieldMandatory() : bool;
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case MutationInputProperties::COMMENT:
                return $this->__('The comment to add', 'comment-mutations');
            case MutationInputProperties::PARENT_COMMENT_ID:
                return $this->__('The ID of the parent comment', 'comment-mutations');
            case MutationInputProperties::CUSTOMPOST_ID:
                return $this->__('The ID of the custom post to add a comment to', 'comment-mutations');
            case MutationInputProperties::AUTHOR_NAME:
                return $this->__('The comment author\'s name', 'comment-mutations');
            case MutationInputProperties::AUTHOR_EMAIL:
                return $this->__('The comment author\'s email', 'comment-mutations');
            case MutationInputProperties::AUTHOR_URL:
                return $this->__('The comment author\'s site URL', 'comment-mutations');
            default:
                return parent::getInputFieldDefaultValue($inputFieldName);
        }
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldTypeModifiers($inputFieldName) : int
    {
        switch ($inputFieldName) {
            case MutationInputProperties::COMMENT:
                return SchemaTypeModifiers::MANDATORY;
            case MutationInputProperties::PARENT_COMMENT_ID:
                return $this->isParentCommentInputFieldMandatory() ? SchemaTypeModifiers::MANDATORY : SchemaTypeModifiers::NONE;
            case MutationInputProperties::CUSTOMPOST_ID:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getInputFieldTypeModifiers($inputFieldName);
        }
    }
}
