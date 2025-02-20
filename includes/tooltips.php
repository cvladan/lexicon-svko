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
        wp_register_script('tooltipster', Core::$base_url . '/assets/js/tooltipster.bundle.min.js', ['jquery'], '4.2.6', true);
        wp_register_script('encyclopedia-tooltips', Core::$base_url . '/assets/js/tooltips.js', ['tooltipster'], null, true);

        $js_parameters = [];
        $js_parameters = apply_filters('encyclopedia_tooltip_js_parameters', $js_parameters);

        wp_localize_script('encyclopedia-tooltips', 'Encyclopedia_Tooltips', $js_parameters);
    }

    public static function enqueueScripts(): void
    {
        if (Options::get('activate_tooltips')) {
            wp_enqueue_style('encyclopedia-tooltips', Core::$base_url . '/assets/css/tooltips.css');
            wp_enqueue_script('encyclopedia-tooltips');
        }
    }
}

Tooltips::init();
