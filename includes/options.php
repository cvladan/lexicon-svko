<?php

namespace SVKO\Lexicon;

abstract class Options
{
    private static $arr_option_box = [], $page_slug = 'lexicon-options', $options_key = 'wp_plugin_lexicon';
    
    /**
     * Get the option name for the current context
     *
     * @return string Option name with language suffix if Polylang is active
     */
    private static function getOptionName(): string
    {
        if (Polylang::isActive()) {
            $current_language = Polylang::getCurrentLanguage();
            if (!empty($current_language)) {
                return static::$options_key . '_' . $current_language;
            }
        }
        
        return static::$options_key;
    }
    
    public static function getOptionBoxes(): array
    {
        return static::$arr_option_box;
    }

    public static function init(): void
    {
        # Option boxes
        static::$arr_option_box = [
            'main' => [],
            'side' => []
        ];

        # Add admin menu after post type is initialized
        add_action('init', function() {
            if ($post_type = PostType::getPostTypeName()) {
                self::$page_slug = $post_type . '-options';
                add_action('admin_menu', [static::class, 'addOptionsPage']);
            }
        }, 2);
    }

    public static function addOptionsPage(): void
    {
        $handle = add_options_page(
            sprintf(__('%s Settings', 'lexicon-svko'), PostTypeLabels::getItemDashboardName()),
            PostTypeLabels::getItemDashboardName(),
            'manage_options',
            static::$page_slug,
            [static::class, 'printOptionsPage']
        );

        # Add options page link to post type sub menu
        add_submenu_page('edit.php?post_type=' . PostType::getPostTypeName(), null, __('Settings', 'lexicon-svko'), 'manage_options', 'options-general.php?page=' . static::$page_slug);

        # Add JavaScript to this handle
        add_action('load-' . $handle, [static::class, 'loadOptionsPage']);

        # Add option boxes
        static::addOptionBox(__('Custom Post Type', 'lexicon-svko'), Core::$plugin_folder . '/options/custom-post-type.php');
        static::addOptionBox(__('Labels', 'lexicon-svko'), Core::$plugin_folder . '/options/post-type-labels.php');
        static::addOptionBox(__('Appearance', 'lexicon-svko'), Core::$plugin_folder . '/options/appearance.php');
        static::addOptionBox(__('Features', 'lexicon-svko'), Core::$plugin_folder . '/options/features.php');
        static::addOptionBox(__('Taxonomies', 'lexicon-svko'), Core::$plugin_folder . '/options/taxonomies.php');
        static::addOptionBox(__('Archives', 'lexicon-svko'), Core::$plugin_folder . '/options/archive-page.php');
        static::addOptionBox(__('Search', 'lexicon-svko'), Core::$plugin_folder . '/options/search.php');
        static::addOptionBox(__('Single View', 'lexicon-svko'), Core::$plugin_folder . '/options/single-page.php');
        static::addOptionBox(__('Related Items', 'lexicon-svko'), Core::$plugin_folder . '/options/related-items.php');
        static::addOptionBox(__('Cross Linking', 'lexicon-svko'), Core::$plugin_folder . '/options/cross-linking.php');
        static::addOptionBox(__('Tooltips', 'lexicon-svko'), Core::$plugin_folder . '/options/tooltips.php');
        static::addOptionBox(__('Archive URLs', 'lexicon-svko'), Core::$plugin_folder . '/options/archive-link.php', 'side');
    }

    public static function getOptionsPageUrl(array $args = []): string
    {
        $url = add_query_arg(['page' => static::$page_slug], admin_url('options-general.php'));

        if (!empty($args))
            $url = add_query_arg($args, $url);

        return $url;
    }

    public static function addOptionBox(string $title, string $include_file, string $column = 'main'): void
    {
        # Title cannot be empty
        if (empty($title)) $title = '&nbsp;';

        # Column (can be 'side' or 'main')
        if ($column != 'main') $column = 'side';

        # Add a new box
        if (is_file($include_file)) {
            static::$arr_option_box[$column][] = (object) [
                'title' => $title,
                'file' => $include_file,
                'slug' => pathinfo($include_file, PATHINFO_FILENAME)
            ];
        }
    }

    public static function loadOptionsPage(): void
    {
        flush_rewrite_rules();

        # If this is a Post request to save the options
        if (static::saveOptions()) {
            wp_redirect(static::getOptionsPageUrl(['options_saved' => 'true']));
        }

        wp_enqueue_style('dashboard');

        wp_enqueue_style(static::$page_slug, Core::$base_url . '/options/options.css');

        # Remove incompatible JS Libs
        wp_dequeue_script('post');
        
        # Add language switcher notice if Polylang is active
        if (Polylang::isActive()) {
            add_action('admin_notices', function() {
                $current_language = Polylang::getCurrentLanguage();
                $languages = Polylang::getLanguages();
                
                if (!empty($languages)) {
                    echo '<div class="notice notice-info"><p>';
                    
                    if (!empty($current_language) && isset($languages[$current_language])) {
                        // Display language with flag if available
                        $flag_html = '';
                        if (!empty($languages[$current_language]['flag'])) {
                            $flag_html = '<img src="' . esc_url($languages[$current_language]['flag']) . '" alt="' . esc_attr($languages[$current_language]['name']) . '" style="vertical-align: middle; margin-right: 5px; width: 16px; height: 11px;">';
                        }
                        
                        echo sprintf(__('%s You are currently editing options for the <strong>%s</strong> language. Options are stored separately per language.', 'lexicon-svko'), $flag_html, esc_html($languages[$current_language]['name']));
                    } else {
                        // No language selected
                        echo __('There is currently no language selected. Please use the language switcher in the admin bar to select a language. Options are stored separately per language.', 'lexicon-svko');
                    }
                    
                    echo '</p></div>';
                }
            });
        }
    }

    public static function printOptionsPage(): void
    {
        include Core::$plugin_folder . '/options/options.php';
    }

    public static function saveOptions(): bool
    {
        # Check if this is a post request
        if (empty($_POST)) return false;

        # Check the nonce
        check_admin_referer('save_' . PostType::getPostTypeName() . '_options');

        # Clean the Post array
        $options = stripslashes_deep($_POST);
        unset($options['_wpnonce'], $options['_wp_http_referer']);
        $options = array_filter($options, function ($value) {
            return $value == '0' || !empty($value);
        });
        
        # Save options
        $success = true;
        
        if (Polylang::isActive()) {
            # When Polylang is active, save all options to language-specific key
            $option_key = static::getOptionName();
            $language_options = (array) get_option($option_key, []);
            
            # Update language-specific options
            $language_options = array_merge($language_options, $options);
            $success = update_option($option_key, $language_options);
            
            # Register string options with Polylang
            foreach ($options as $key => $value) {
                if (is_string($value)) {
                    Polylang::registerString($key, $value);
                }
            }
        } else {
            # When Polylang is not active, save to the main option key
            $current_options = (array) get_option(static::$options_key, []);
            $current_options = array_merge($current_options, $options);
            $success = update_option(static::$options_key, $current_options);
        }
        
        return $success;
    }

    public static function getDefaultOptions(): array
    {
        return [
            'post_type_slug' => 'lexicon',
            'item_dashboard_name' => __('Lexicon', 'lexicon-svko'),
            'item_singular_name' => __('Entry', 'lexicon-svko'),
            'item_plural_name' => __('Entries', 'lexicon-svko'),
            'item_slug' => __('lexicon', 'lexicon-svko'),
            'archive_slug' => __('lexicon', 'lexicon-svko'),
            'embed_default_style' => true,
            'enable_editor' => true,
            'enable_block_editor' => false,
            'enable_excerpt' => true,
            'enable_featured_image' => true,
            'taxonomies' => [PostType::getPostTypeName() . '-tag'],
            'enable_archive' => true,
            'items_per_page' => 100,
            'prefix_filter_for_archives' => true,
            'prefix_filter_archive_depth' => 3,
            'prefix_filter_for_singulars' => true,
            'prefix_filter_singular_depth' => 3,
            'relation_taxonomy' => PostType::getPostTypeName() . '-tag',
            'min_relation_threshold' => 1,
            'cross_link_title_length' => apply_filters('excerpt_length', 55),
            'cross_linker_priority' => 'after_shortcodes',
            'activate_tooltips' => true,
        ];
    }

    /**
     * Get an option value, with support for translations
     *
     * @param string $key Option key
     * @param mixed $default Default value if option doesn't exist
     * @return mixed Option value
     */
    public static function get(string $key = '', $default = false)
    {
        // Get default options first
        $default_options = static::getDefaultOptions();
        
        // Get saved global options
        $saved_options = (array) get_option(static::$options_key, []);
        
        // Merge default with saved global options
        $arr_options = array_merge($default_options, $saved_options);
        
        // If Polylang is active, get language-specific options and overwrite
        if (Polylang::isActive()) {
            $option_key = static::getOptionName();
            $language_options = (array) get_option($option_key, []);
            
            // Overwrite with language-specific options
            if (!empty($language_options)) {
                $arr_options = array_merge($arr_options, $language_options);
            }
        }
        
        # Return all options if no key specified
        if (empty($key))
            return $arr_options;
            
        # Return option value if key exists
        if (isset($arr_options[$key])) {
            return $arr_options[$key];
        }
            
        # Return default value
        return $default;
    }
    
    /**
     * Save an option value with translation support
     *
     * @param string $key Option key
     * @param mixed $value Option value
     * @return bool Whether the option was updated
     */
    public static function saveOption(string $key, $value): bool
    {
        if (Polylang::isActive()) {
            // When Polylang is active, save all options to language-specific key
            $option_key = static::getOptionName();
            $language_options = (array) get_option($option_key, []);
            $language_options[$key] = $value;
            
            // Save to language-specific option
            $success = update_option($option_key, $language_options);
            
            // Also register with Polylang for compatibility if it's a string
            if (is_string($value)) {
                Polylang::registerString($key, $value);
            }
            
            return $success;
        } else {
            // When Polylang is not active, save to the main option key
            $options = (array) get_option(static::$options_key, []);
            $options[$key] = $value;
            return update_option(static::$options_key, $options);
        }
    }
}
