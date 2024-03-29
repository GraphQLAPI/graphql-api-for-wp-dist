<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyQueryWP\Helpers;

class TaxonomyQueryHelpers
{
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     */
    public static function convertTaxonomyQuery($query): array
    {
        if (isset($query['tax-query'])) {
            $query['tax_query'] = $query['tax-query'];
            // // Make sure the "relation" has not become an array from merging the tax_query values together
            // if (isset($query['tax_query']['relation']) && is_array($query['tax_query']['relation'])) {
            //     $query['tax_query']['relation'] = $query['tax_query']['relation'][0];
            // }
            unset($query['tax-query']);
        }
        return $query;
    }
}
