<?php

declare (strict_types=1);
namespace PoPSchema\Pages\TypeResolvers;

use PoP\ComponentModel\ErrorHandling\ErrorProviderInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\AbstractCustomPostTypeResolver;
use PoPSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoPSchema\Pages\TypeDataLoaders\PageTypeDataLoader;
class PageTypeResolver extends AbstractCustomPostTypeResolver
{
    /**
     * @var \PoPSchema\Pages\TypeAPIs\PageTypeAPIInterface
     */
    protected $pageTypeAPI;
    public function __construct(TranslationAPIInterface $translationAPI, HooksAPIInterface $hooksAPI, InstanceManagerInterface $instanceManager, FeedbackMessageStoreInterface $feedbackMessageStore, FieldQueryInterpreterInterface $fieldQueryInterpreter, ErrorProviderInterface $errorProvider, SchemaDefinitionServiceInterface $schemaDefinitionService, PageTypeAPIInterface $pageTypeAPI)
    {
        $this->pageTypeAPI = $pageTypeAPI;
        parent::__construct($translationAPI, $hooksAPI, $instanceManager, $feedbackMessageStore, $fieldQueryInterpreter, $errorProvider, $schemaDefinitionService);
    }
    public function getTypeName() : string
    {
        return 'Page';
    }
    public function getSchemaTypeDescription() : ?string
    {
        return $this->translationAPI->__('Representation of a page', 'pages');
    }
    /**
     * @return string|int|null
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        $page = $resultItem;
        return $this->pageTypeAPI->getPageId($page);
    }
    public function getTypeDataLoaderClass() : string
    {
        return PageTypeDataLoader::class;
    }
}
