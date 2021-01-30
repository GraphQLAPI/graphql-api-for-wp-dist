<?php

declare (strict_types=1);
namespace PoP\APIMirrorQuery\DataStructureFormatters;

use PoP\ComponentModel\DataStructure\XMLDataStructureFormatterTrait;
class XMLMirrorQueryDataStructureFormatter extends \PoP\APIMirrorQuery\DataStructureFormatters\MirrorQueryDataStructureFormatter
{
    use XMLDataStructureFormatterTrait;
    public const NAME = 'xml';
    public static function getName() : string
    {
        return self::NAME;
    }
}
