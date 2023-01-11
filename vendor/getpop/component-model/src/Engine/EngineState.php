<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
class EngineState
{
    /**
     * @var array<string, mixed>
     */
    public $data = [];
    /**
     * @var array<string, mixed>
     */
    public $helperCalculations = [];
    /**
     * @var array<string, mixed>
     */
    public $model_props = [];
    /**
     * @var array<string, mixed>
     */
    public $props = [];
    /**
     * @var string[]
     */
    public $nocache_fields = [];
    /**
     * @var array<string, mixed>|null
     */
    public $componentdata;
    /**
     * @var array<string, array<string, mixed>>
     */
    public $dbdata = [];
    /**
     * @var array<string, string[]>
     */
    public $backgroundload_urls = [];
    /**
     * @var string[]|null
     */
    public $extra_routes;
    /**
     * @var bool|null
     */
    public $cachedsettings;
    /**
     * @var array<string, mixed>
     */
    public $outputData = [];
    /**
     * @var \PoP\ComponentModel\Component\Component|null
     */
    public $entryComponent;
    /**
     * @var array<string, array<string, (RelationalTypeResolverInterface | array<(string | int), EngineIterationFieldSet>)>>
     */
    public $relationalTypeOutputKeyIDFieldSets = [];
    /**
     * @param array<string,mixed> $data
     * @param array<string,mixed> $helperCalculations
     * @param array<string,mixed> $model_props
     * @param array<string,mixed> $props
     * @param string[] $nocache_fields
     * @param array<string,mixed>|null $componentdata
     * @param array<string,array<string,mixed>> $dbdata
     * @param array<string,string[]> $backgroundload_urls
     * @param string[]|null $extra_routes
     * @param array<string,mixed> $outputData
     * @param array<string,array<string,RelationalTypeResolverInterface|array<string|int,EngineIterationFieldSet>>> $relationalTypeOutputKeyIDFieldSets `mixed` could be string[] for "direct", or array<string,string[]> for "conditional"
     */
    public function __construct(array $data = [], array $helperCalculations = [], array $model_props = [], array $props = [], array $nocache_fields = [], ?array $componentdata = null, array $dbdata = [], array $backgroundload_urls = [], ?array $extra_routes = null, ?bool $cachedsettings = null, array $outputData = [], ?Component $entryComponent = null, array $relationalTypeOutputKeyIDFieldSets = [])
    {
        $this->data = $data;
        $this->helperCalculations = $helperCalculations;
        $this->model_props = $model_props;
        $this->props = $props;
        $this->nocache_fields = $nocache_fields;
        $this->componentdata = $componentdata;
        $this->dbdata = $dbdata;
        $this->backgroundload_urls = $backgroundload_urls;
        $this->extra_routes = $extra_routes;
        $this->cachedsettings = $cachedsettings;
        $this->outputData = $outputData;
        $this->entryComponent = $entryComponent;
        $this->relationalTypeOutputKeyIDFieldSets = $relationalTypeOutputKeyIDFieldSets;
    }
}
