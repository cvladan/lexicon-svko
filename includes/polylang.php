<?php

namespace SVKO\Lexicon;

use WP_Query;

abstract class Polylang
{
    // All keys are now translatable, no need for a specific list

    public static function init(): void
    {
        add_filter('lexicon_available_prefix_filters', [static::class, 'filterAvailablePrefixFilters']);
        
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
     * Get current language code
     *
     * @return string Current language code or empty string if not available
     */
    public static function getCurrentLanguage(): string
    {
        if (!static::isActive()) {
            return '';
        }
        
        if (function_exists('pll_current_language')) {
            return \pll_current_language();
        }
        
        return '';
    }
    
    /**
     * Get all available languages with their details
     *
     * @return array Array of language details
     */
    public static function getLanguages(): array
    {
        if (!static::isActive() || !function_exists('pll_the_languages')) {
            return [];
        }
        
        return \pll_the_languages(['raw' => 1]);
    }
    
    /**
     * Register a string for translation with Polylang
     *
     * @param string $key Option key
     * @param string $value String value to register
     * @return void
     */
    public static function registerString(string $key, string $value): void
    {
        if (!static::isActive()) {
            return;
        }
        
        if (function_exists('pll_register_string')) {
            \pll_register_string($key, $value, 'Lexicon Plugin', true);
            
            // Force Polylang to reload strings if possible
            if (isset($GLOBALS['polylang']) && isset($GLOBALS['polylang']->strings)) {
                $strings = $GLOBALS['polylang']->strings;
                
                if (method_exists($strings, 'register')) {
                    $strings->register();
                }
            }
        }
    }
}

Polylang::init();
