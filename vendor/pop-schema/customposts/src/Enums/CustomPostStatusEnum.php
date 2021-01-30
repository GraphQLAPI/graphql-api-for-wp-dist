<?php

declare (strict_types=1);
namespace PoPSchema\CustomPosts\Enums;

use PoPSchema\CustomPosts\Types\Status;
use PoP\ComponentModel\Enums\AbstractEnum;
class CustomPostStatusEnum extends \PoP\ComponentModel\Enums\AbstractEnum
{
    public const NAME = 'CustomPostStatus';
    protected function getEnumName() : string
    {
        return self::NAME;
    }
    public function getValues() : array
    {
        return [\PoPSchema\CustomPosts\Types\Status::PUBLISHED, \PoPSchema\CustomPosts\Types\Status::PENDING, \PoPSchema\CustomPosts\Types\Status::DRAFT, \PoPSchema\CustomPosts\Types\Status::TRASH];
    }
}
