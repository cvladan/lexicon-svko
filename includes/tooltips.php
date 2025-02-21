<?php

namespace SVKO\Lexicon;

abstract class Tooltips
{
    public static function init(): void
    {
        add_action('init', [static::class, 'registerScripts']);
        add_action('wp_enqueue_scripts', [static::class, 'enqueueScripts']);
    }

    public static function registerScripts(): void
    {
        wp_register_script(PostType::getPostTypeName() . '-tooltips', Core::$base_url . '/assets/js/tooltips.js', [], null, true);

        $js_parameters = [ 'post_type_name' => PostType::getPostTypeName() ];
        $js_parameters = apply_filters('lexicon_tooltip_js_parameters', $js_parameters);

        wp_localize_script(PostType::getPostTypeName() . '-tooltips', 'arrTooltips', $js_parameters);
    }

    public static function enqueueScripts(): void
    {
        if (Options::get('activate_tooltips')) {
            wp_enqueue_style(PostType::getPostTypeName() . '-tooltips', Core::$base_url . '/assets/css/tooltips.css');
            wp_enqueue_script(PostType::getPostTypeName() . '-tooltips');
        }
    }
}

Tooltips::init();
