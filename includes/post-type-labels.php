<?php

namespace SVKO\Lexicon;

abstract class PostTypeLabels
{
    public static function getPostTypeSlug(): string
    {
        $type = Options::get('post_type_slug');
        // $type = WPML::t($type, 'Post type slug');
        return $type;
    }

    public static function getItemDashboardName(): string
    {
        $type = Options::get('item_dashboard_name');
        $type = WPML::t($type, 'Item dashboard name');
        return $type;
    }

    public static function getArchiveSlug(): string
    {
        $slug = Options::get('archive_slug');
        $slug = WPML::t($slug, 'Archive slug');
        $slug = trim($slug, '/');
        return $slug;
    }

    public static function getItemSlug(): string
    {
        $slug = Options::get('item_slug');
        $slug = WPML::t($slug, 'Item slug');
        $slug = trim($slug, '/');
        return $slug;
    }

    public static function getItemSingularName(): string
    {
        $name = Options::get('item_singular_name');
        $name = WPML::t($name, 'Item singular name');
        return $name;
    }

    public static function getItemPluralName(): string
    {
        $name = Options::get('item_plural_name');
        $name = WPML::t($name, 'Item plural name');
        return $name;
    }
}
