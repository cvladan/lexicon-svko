<?php

namespace SVKO\Lexicon;

abstract class PostTypeLabels
{
    public static function getPostTypeSlug(): string
    {
        $slug = Options::get('post_type_slug');
        // $slug = WPML::t($slug, 'Post type slug');
        return $slug;
    }

    public static function getItemDashboardName(): string
    {
        $name = Options::get('item_dashboard_name');
        // $name = WPML::t($name, 'Item dashboard name');
        return $name;
    }

    public static function getArchiveSlug(): string
    {
        $slug = Options::get('archive_slug');
        // $slug = WPML::t($slug, 'Archive slug');
        $slug = trim($slug, '/');
        return $slug;
    }

    public static function getItemSlug(): string
    {
        $slug = Options::get('item_slug');
        // $slug = WPML::t($slug, 'Item slug');
        $slug = trim($slug, '/');
        return $slug;
    }

    public static function getItemSingularName(): string
    {
        $name = Options::get('item_singular_name');
        // $name = WPML::t($name, 'Item singular name');
        return $name;
    }

    public static function getItemPluralName(): string
    {
        $name = Options::get('item_plural_name');
        // $name = WPML::t($name, 'Item plural name');
        return $name;
    }
}
