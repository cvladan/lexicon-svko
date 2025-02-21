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

    public static function isThisSearched(WP_Query $query): bool
    {
        if ($query->is_search) {
            # Check post type
            if ($query->get('post_type') == PostType::getPostTypeName()) return true;

            # Check taxonomies
            $taxonomies = get_object_taxonomies(PostType::getPostTypeName());
            if (!empty($taxonomies) && $query->is_tax($taxonomies)) return true;
        }

        return false;
    }
}
