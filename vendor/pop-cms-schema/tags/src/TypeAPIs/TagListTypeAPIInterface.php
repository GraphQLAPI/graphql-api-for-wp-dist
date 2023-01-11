<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\TypeAPIs;

interface TagListTypeAPIInterface
{
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTags($query, $options = []) : array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTagCount($query = [], $options = []) : int;
}
