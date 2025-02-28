<?php

namespace SVKO\Lexicon;
defined('ABSPATH') || exit;

abstract class Shortcodes
{
    public static function init(): void
    {
        # use getPostTypeName() method SVKO\Lexicon\PostType
        add_shortcode(PostType::getPostTypeName() . '_related_items', [static::class, 'Related_Items']);
    }

    public static function Related_Items($attributes = [])
    {
        $attributes = is_array($attributes) ? $attributes : [];

        $attributes = array_merge([
            'number' => 5
        ], $attributes);

        $related_items = PostRelations::getTermRelatedItems($attributes);

        return Template::load('related-items.php', [
            'attributes' => $attributes,
            'related_items' => $related_items
        ]);
    }
}

Shortcodes::init();
