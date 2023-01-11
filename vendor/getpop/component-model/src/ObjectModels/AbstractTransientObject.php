<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ObjectModels;

use PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;
/**
 * A Transient Object is automatically added to the Object Dictionary
 * under the class of the object.
 */
abstract class AbstractTransientObject implements \PoP\ComponentModel\ObjectModels\TransientObjectInterface
{
    use StandaloneServiceTrait;
    /**
     * @var \PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface|null
     */
    private $objectDictionary;
    /**
     * @param \PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface $objectDictionary
     */
    public final function setObjectDictionary($objectDictionary) : void
    {
        $this->objectDictionary = $objectDictionary;
    }
    protected final function getObjectDictionary() : ObjectDictionaryInterface
    {
        /** @var ObjectDictionaryInterface */
        return $this->objectDictionary = $this->objectDictionary ?? InstanceManagerFacade::getInstance()->getInstance(ObjectDictionaryInterface::class);
    }
    /**
     * Static ID generator: all Transient Objects, from whatever class,
     * will have different IDs.
     * @var int
     */
    public static $counter = 0;
    /**
     * @readonly
     * @var int
     */
    public $id;
    public function __construct()
    {
        self::$counter++;
        $this->id = self::$counter;
        // Register the object in the registry
        $this->getObjectDictionary()->set(\get_called_class(), $this->getID(), $this);
    }
    /**
     * @return int|string
     */
    public function getID()
    {
        return $this->id;
    }
}
