<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMutations\ConditionalOnModule\Users\SchemaHooks;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\SchemaHooks\FilterInputHookSet as UserCustomPostFilterInputHookSet;
abstract class AbstractRemoveAuthorFilterInputHookSet extends AbstractHookSet
{
    /**
     * @var UserCustomPostFilterInputHookSet|null
     */
    private $userCustomPostFilterInputHookSet;
    /**
     * @param UserCustomPostFilterInputHookSet $userCustomPostFilterInputHookSet
     */
    public final function setUserCustomPostFilterInputHookSet($userCustomPostFilterInputHookSet) : void
    {
        $this->userCustomPostFilterInputHookSet = $userCustomPostFilterInputHookSet;
    }
    protected final function getUserCustomPostFilterInputHookSet() : UserCustomPostFilterInputHookSet
    {
        /** @var UserCustomPostFilterInputHookSet */
        return $this->userCustomPostFilterInputHookSet = $this->userCustomPostFilterInputHookSet ?? $this->instanceManager->getInstance(UserCustomPostFilterInputHookSet::class);
    }
    protected function init() : void
    {
        App::addFilter($this->getHookNameToRemoveFilterInput(), \Closure::fromCallable([$this, 'getFilterInputComponents']));
    }
    protected abstract function getHookNameToRemoveFilterInput() : string;
    /**
     * Remove author fieldArgs from field "myCustomPosts"
     *
     * @param Component[] $filterInputComponents
     * @return Component[]
     */
    public function getFilterInputComponents($filterInputComponents) : array
    {
        $components = $this->getUserCustomPostFilterInputHookSet()->getAuthorFilterInputComponents();
        foreach ($components as $component) {
            $pos = \array_search($component, $filterInputComponents);
            if ($pos === \false) {
                continue;
            }
            /** @var int $pos */
            \array_splice($filterInputComponents, $pos, 1);
        }
        return $filterInputComponents;
    }
}
