<?php

namespace SVKO\Lexicon;

abstract class TaxonomyFallbacks
{
    public static function init()
    {
        add_filter('get_the_terms', [static::class, 'Filter_Get_The_Terms'], 10, 3);
        add_filter('the_category', [static::class, 'Filter_The_Category'], 10, 2);
    }

    public static function Filter_Get_The_Terms($arr_terms, int $post_id, string $taxonomy_name)
    {
        static $term_remap;
        if ($term_remap === null) {
            $term_remap = [
                'category' => PostType::getPostTypeName() . '-category',
                'post_tag' => PostType::getPostTypeName() . '-tag'
            ];
        }

        $map_to_taxonomy = $term_remap[$taxonomy_name] ?? false;

        $post = get_post($post_id);

        if (
            !is_admin() &&
            $post && $post->post_type === PostType::getPostTypeName() &&
            !is_object_in_taxonomy($post->post_type, $taxonomy_name) &&
            $map_to_taxonomy && taxonomy_exists($map_to_taxonomy) && is_object_in_taxonomy($post->post_type, $map_to_taxonomy)
        ) {
            $arr_terms = get_the_terms($post, $map_to_taxonomy);
        }

        return $arr_terms;
    }

    public static function Filter_The_Category(string $category_list, string $separator = '' /* , string $parents */): string
    {
        global $post;
        $taxonomy = PostType::getPostTypeName() . '-category';

        if (
            empty($category_list) &&
            !is_admin() &&
            $post && $post->post_type === PostType::getPostTypeName() &&
            !is_object_in_taxonomy($post->post_type, 'category') &&
            taxonomy_exists($taxonomy) && is_object_in_taxonomy($post->post_type, $taxonomy)
        ) {
            $category_list = get_the_term_list($post->ID, $taxonomy, null, $separator, null);
            if (empty($category_list)) $category_list = I18n::__('Uncategorized');
        }

        return $category_list;
    }
}

TaxonomyFallbacks::init();
