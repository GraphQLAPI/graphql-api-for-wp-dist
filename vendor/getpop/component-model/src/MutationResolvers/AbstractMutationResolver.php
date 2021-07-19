<?php

declare (strict_types=1);
namespace PoP\ComponentModel\MutationResolvers;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
abstract class AbstractMutationResolver implements \PoP\ComponentModel\MutationResolvers\MutationResolverInterface
{
    /**
     * @var \PoP\Translation\TranslationAPIInterface
     */
    protected $translationAPI;
    /**
     * @var \PoP\Hooks\HooksAPIInterface
     */
    protected $hooksAPI;
    public function __construct(TranslationAPIInterface $translationAPI, HooksAPIInterface $hooksAPI)
    {
        $this->translationAPI = $translationAPI;
        $this->hooksAPI = $hooksAPI;
    }
    public function validateErrors(array $form_data) : ?array
    {
        return null;
    }
    public function validateWarnings(array $form_data) : ?array
    {
        return null;
    }
    public function getErrorType() : int
    {
        return \PoP\ComponentModel\MutationResolvers\ErrorTypes::DESCRIPTIONS;
    }
}
