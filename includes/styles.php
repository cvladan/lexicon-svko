<?php

namespace SVKO\Lexicon;

abstract class Styles
{
    public static function init(): void
    {
        add_action('wp_enqueue_scripts', [static::class, 'enqueueDefaultStyle']);
        add_action('admin_enqueue_scripts', [static::class, 'registerDashboardStyles']);
    }

    public static function enqueueDefaultStyle(): void
    {
        if (Options::get('embed_default_style'))
            wp_enqueue_style(PostType::getPostTypeName(), Core::$base_url . '/assets/css/styles.css');
    }

    public static function registerDashboardStyles()
    {
        wp_enqueue_style(PostType::getPostTypeName() . '-dashboard-extension', Core::$base_url . '/assets/css/dashboard.css');
    }
}

Styles::init();
