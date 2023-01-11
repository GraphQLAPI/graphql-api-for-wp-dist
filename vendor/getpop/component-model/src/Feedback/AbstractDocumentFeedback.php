<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Feedback;

use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Feedback\FeedbackItemResolution;
/**
 * Error that concern the GraphQL document. The `$location` is where the error happens.
 */
abstract class AbstractDocumentFeedback extends \PoP\ComponentModel\Feedback\AbstractFeedback implements \PoP\ComponentModel\Feedback\DocumentFeedbackInterface
{
    /**
     * @var \PoP\GraphQLParser\Spec\Parser\Location
     */
    protected $location;
    /**
     * @param array<string,mixed> $extensions
     */
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        Location $location,
        /** @var array<string,mixed> */
        array $extensions = []
    )
    {
        $this->location = $location;
        parent::__construct($feedbackItemResolution, $extensions);
    }
    public function getLocation() : Location
    {
        return $this->location;
    }
}
