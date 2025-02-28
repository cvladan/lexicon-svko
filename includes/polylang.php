<?php

namespace SVKO\Lexicon;

use WP_Query;

abstract class Polylang
{
    public static function init(): void
    {
        add_filter('lexicon_translate', [static::class, 'filterTranslation'], 10, 3);
        add_filter('lexicon_available_prefix_filters', [static::class, 'filterAvailablePrefixFilters']);
    }

    public static function isActive(): bool
    {
        return defined('POLYLANG_VERSION');
    }

    public static function filterTranslation(bool $state, string $text, string $context = ''): bool
    {
        // if (static::isActive() && $context == 'URL Slug') {
        //     $state = false;
        // }

        return $state;
    }

    public static function filterAvailablePrefixFilters(array $arr_filter): array
    {
        if (static::isActive() && is_array($arr_filter)) {
            foreach ($arr_filter as $index => $filter) {
                # Check if there are posts behind this filter in this language
                $query = new WP_Query([
                    'post_type' => PostType::getPostTypeName(),
                    'post_title_like' => $filter->prefix . '%',
                    'posts_per_page' => 1,
                    'cache_results' => false,
                    'no_count_rows' => true
                ]);

                if (!$query->have_posts())
                    unset($arr_filter[$index]);
            }

            $arr_filter = array_values($arr_filter);
        }

        return $arr_filter;
    }
}

Polylang::init();
