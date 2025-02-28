<?php

namespace SVKO\Lexicon;

use WP_Query;

abstract class Polylang
{
    /**
     * Translatable option keys
     */
    private static $translatable_keys = [
        'item_dashboard_name',
        'item_singular_name',
        'item_plural_name',
        'item_slug',
        'archive_slug'
    ];

    public static function init(): void
    {
        add_filter('lexicon_available_prefix_filters', [static::class, 'filterAvailablePrefixFilters']);
        
        // Register strings for translation when plugin is loaded
        // This works with the wpml-config.xml file to ensure all strings are properly registered
        add_action('plugins_loaded', [static::class, 'registerStringsForTranslation'], 20);
        
        // Add filter to ensure translations are properly retrieved
        add_filter('pll_get_post_types', [static::class, 'addCustomPostTypeToPolylang'], 10, 2);
    }

    /**
     * Check if Polylang is active
     *
     * @return bool
     */
    public static function isActive(): bool
    {
        return defined('POLYLANG_VERSION');
    }

    /**
     * Filter available prefix filters based on current language
     *
     * @param array $arr_filter
     * @return array
     */
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
    
    /**
     * Register strings for translation with Polylang
     *
     * This ensures that strings are available for translation immediately
     * and helps preserve translations when values are updated.
     */
    public static function registerStringsForTranslation(): void
    {
        if (!static::isActive() || !function_exists('pll_register_string')) {
            return;
        }
        
        // Get current options
        $options = (array) get_option('wp_plugin_lexicon', []);
        
        // Register each translatable string
        foreach (self::$translatable_keys as $key) {
            if (isset($options[$key]) && is_string($options[$key])) {
                \pll_register_string($key, $options[$key], 'wp_plugin_lexicon', true);
            }
        }
    }
    
    /**
     * Add custom post type to Polylang
     *
     * @param array $post_types
     * @param bool $is_settings
     * @return array
     */
    public static function addCustomPostTypeToPolylang(array $post_types, bool $is_settings): array
    {
        if ($is_settings) {
            $post_type_name = PostType::getPostTypeName();
            $post_types[$post_type_name] = $post_type_name;
        }
        return $post_types;
    }
    
    /**
     * Get translatable keys
     *
     * @return array
     */
    public static function getTranslatableKeys(): array
    {
        return self::$translatable_keys;
    }
    
    /**
     * Preserve translations when updating a value in one language
     *
     * This method works with the wpml-config.xml file to ensure translations
     * are connected to keys, not values. When a value is updated in one language,
     * translations in other languages are preserved.
     *
     * @param string $key Option key
     * @param string $value New value
     * @param string $language Current language code
     * @return void
     */
    public static function preserveTranslations(string $key, string $value, string $language = ''): void
    {
        if (!static::isActive() || !in_array($key, self::$translatable_keys)) {
            return;
        }
        
        // Get current language if not provided
        if (empty($language) && function_exists('pll_current_language')) {
            $language = \pll_current_language();
        }
        
        if (empty($language)) {
            return;
        }
        
        // Register the string for translation
        // This ensures the new value is registered for the current language
        // while preserving translations in other languages
        if (function_exists('pll_register_string')) {
            \pll_register_string($key, $value, 'wp_plugin_lexicon', true);
        }
        
        // Force Polylang to reload strings if possible
        // This ensures the translations are immediately available
        if (isset($GLOBALS['polylang']) && isset($GLOBALS['polylang']->strings)) {
            $strings = $GLOBALS['polylang']->strings;
            
            if (method_exists($strings, 'register')) {
                $strings->register();
            }
        }
    }
}

Polylang::init();
