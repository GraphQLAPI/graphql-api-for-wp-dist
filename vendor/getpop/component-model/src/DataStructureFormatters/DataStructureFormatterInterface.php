<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DataStructureFormatters;

interface DataStructureFormatterInterface
{
    public function getName() : string;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $data
     */
    public function getFormattedData($data) : array;
    public function getContentType() : string;
    /**
     * @param array<string,mixed> $data
     */
    public function getOutputContent(&$data) : string;
}
