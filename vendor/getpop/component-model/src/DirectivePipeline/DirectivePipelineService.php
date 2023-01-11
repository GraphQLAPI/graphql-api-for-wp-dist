<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DirectivePipeline;

use PrefixedByPoP\League\Pipeline\PipelineBuilder;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
class DirectivePipelineService implements \PoP\ComponentModel\DirectivePipeline\DirectivePipelineServiceInterface
{
    /**
     * @param FieldDirectiveResolverInterface[] $directiveResolvers
     */
    public function getDirectivePipeline($directiveResolvers) : \PoP\ComponentModel\DirectivePipeline\DirectivePipelineDecorator
    {
        // From the ordered directives, create the pipeline
        $pipelineBuilder = new PipelineBuilder();
        foreach ($directiveResolvers as $directiveResolver) {
            // This is the method to be invoked,
            // equivalent to `__invoke` in League\Pipeline\StageInterface
            $pipelineBuilder->add(\Closure::fromCallable([$directiveResolver, 'resolveDirectivePipelinePayload']));
        }
        $directivePipeline = new \PoP\ComponentModel\DirectivePipeline\DirectivePipelineDecorator($pipelineBuilder->build());
        return $directivePipeline;
    }
}
