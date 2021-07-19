<?php

declare (strict_types=1);
namespace PoP\ComponentModel\CheckpointProcessors;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
abstract class AbstractCheckpointProcessor
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
    public abstract function getCheckpointsToProcess();
    public function process(array $checkpoint)
    {
        // By default, no problem at all, so always return true
        return \true;
    }
}
