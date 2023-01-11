<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Exception;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Exception\AbstractClientException;
abstract class AbstractLocationableException extends AbstractClientException
{
    /**
     * @readonly
     * @var \PoP\Root\Feedback\FeedbackItemResolution
     */
    private $feedbackItemResolution;
    /**
     * @readonly
     * @var \PoP\GraphQLParser\Spec\Parser\Location
     */
    private $location;
    public function __construct(FeedbackItemResolution $feedbackItemResolution, Location $location)
    {
        $this->feedbackItemResolution = $feedbackItemResolution;
        $this->location = $location;
        parent::__construct($feedbackItemResolution->getMessage());
    }
    public function getFeedbackItemResolution() : FeedbackItemResolution
    {
        return $this->feedbackItemResolution;
    }
    public function getLocation() : Location
    {
        return $this->location;
    }
}
