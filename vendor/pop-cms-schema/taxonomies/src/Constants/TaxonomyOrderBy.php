<?php

declare (strict_types=1);
namespace PoPCMSSchema\Taxonomies\Constants;

/**
 * Same list as defined for WordPress
 *
 * @see https://developer.wordpress.org/reference/classes/wp_term_query/__construct/#parameters
 */
class TaxonomyOrderBy
{
    public const NAME = 'NAME';
    public const SLUG = 'SLUG';
    public const ID = 'ID';
    public const PARENT = 'PARENT';
    public const COUNT = 'COUNT';
    public const NONE = 'NONE';
    public const INCLUDE = 'INCLUDE';
    public const SLUG__IN = 'SLUG__IN';
    public const DESCRIPTION = 'DESCRIPTION';
    // public final const TERM_GROUP = 'TERM_GROUP';
    // public final const TERM_ID = 'TERM_ID';
    // public final const TERM_ORDER = 'TERM_ORDER';
}
