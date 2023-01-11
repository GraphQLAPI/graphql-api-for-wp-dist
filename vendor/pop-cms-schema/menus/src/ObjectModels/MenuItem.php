<?php

declare (strict_types=1);
namespace PoPCMSSchema\Menus\ObjectModels;

/**
 * Make properties public so they can be accessed directly
 */
class MenuItem
{
    /**
     * @readonly
     * @var string|int
     */
    public $id;
    /**
     * @readonly
     * @var string|int
     */
    public $objectID;
    /**
     * @readonly
     * @var string|int|null
     */
    public $parentID;
    /**
     * @readonly
     * @var string
     */
    public $label;
    /**
     * @readonly
     * @var string
     */
    public $title;
    /**
     * @readonly
     * @var string
     */
    public $url;
    /**
     * @readonly
     * @var string
     */
    public $description;
    /**
     * @var string[]
     * @readonly
     */
    public $classes;
    /**
     * @readonly
     * @var string
     */
    public $target;
    /**
     * @readonly
     * @var string
     */
    public $linkRelationship;
    /**
     * @param string[] $classes
     * @param string|int $id
     * @param string|int $objectID
     * @param string|int|null $parentID
     */
    public function __construct($id, $objectID, $parentID, string $label, string $title, string $url, string $description, array $classes, string $target, string $linkRelationship)
    {
        $this->id = $id;
        $this->objectID = $objectID;
        $this->parentID = $parentID;
        $this->label = $label;
        $this->title = $title;
        $this->url = $url;
        $this->description = $description;
        $this->classes = $classes;
        $this->target = $target;
        $this->linkRelationship = $linkRelationship;
    }
}
