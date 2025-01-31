<?php

namespace SVKO\Lexicon;

use WP_Query;

abstract class Search
{
    /*
    public static function init(): void
    {
    }
    */

    public static function isEncyclopediaSearch(WP_Query $query): bool
    {
        if ($query->is_search) {
            # Check post type
            if ($query->get('post_type') == PostType::getPostTypeName()) return true;

            # Check taxonomies
            $encyclopedia_taxonomies = get_Object_Taxonomies(PostType::getPostTypeName());
            if (!empty($encyclopedia_taxonomies) && $query->is_Tax($encyclopedia_taxonomies)) return true;
        }

        return false;
    }
}
