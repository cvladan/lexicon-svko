<?php

namespace SVKO\Lexicon;

use WP_Post;

abstract class PostType
{
    private static $post_type_name;

    public static function init()
    {
        // Initialize post type name early but after options are loaded
        add_action('init', function() {
            self::$post_type_name = PostTypeLabels::getPostTypeSlug(); # Initialize post type name
        }, 1);

        // Register post type and add filters after name is set
        add_action('init', function() {
            if (!self::$post_type_name) {
                return; // Ensure post type name is set
            }
            static::registerPostType();
            
            // Add filters after post type is registered
            add_filter(self::$post_type_name . '_rewrite_rules', [static::class, 'addPrefixFilterRewriteRules']);
            add_filter('post_updated_messages', [static::class, 'filterUpdatedMessages']);
            add_filter('post_type_link', [static::class, 'filterPostTypeLink'], 1, 2);
            add_filter('use_block_editor_for_post_type', [static::class, 'enableBlockEditor'], 10, 2);
        }, 5);
    }

    public static function getPostTypeName()
    {
        return self::$post_type_name;
    }

    public static function registerPostType(): void
    {
        $labels = [
            'name' => PostTypeLabels::getItemDashboardName(),
            'singular_name' => PostTypeLabels::getItemSingularName(),
            'add_new' => sprintf(__('Add %s', 'lexicon-svko'), PostTypeLabels::getItemSingularName()),
            'add_new_item' => sprintf(__('New %s', 'lexicon-svko'), PostTypeLabels::getItemSingularName()),
            'edit_item' => sprintf(__('Edit %s', 'lexicon-svko'), PostTypeLabels::getItemSingularName()),
            'view_item' => sprintf(__('View %s', 'lexicon-svko'), PostTypeLabels::getItemSingularName()),
            'search_items' => sprintf(__('Search %s', 'lexicon-svko'), PostTypeLabels::getItemPluralName()),
            'not_found' =>  sprintf(__('No %s found', 'lexicon-svko'), PostTypeLabels::getItemPluralName()),
            'not_found_in_trash' => sprintf(__('No %s found in Trash', 'lexicon-svko'), PostTypeLabels::getItemPluralName()),
            'all_items' => sprintf(__('All %s', 'lexicon-svko'), PostTypeLabels::getItemPluralName()),
            'archives' => sprintf(__('%s Index Page', 'lexicon-svko'), PostTypeLabels::getItemDashboardName())
        ];

        $post_type_args = [
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'menu_icon' => 'dashicons-welcome-learn-more',
            'register_meta_box_cb' => [static::class, 'addMetaBoxes'],
            'has_archive' => Options::get('enable_archive') ? PostTypeLabels::getArchiveSlug() : false,
            'map_meta_cap' => true,
            'hierarchical' => false,
            'rewrite' => [
                'slug' => PostTypeLabels::getItemSlug(),
                'with_front' => false
            ],
            'supports' => ['title', 'author'],
            'menu_position' => 20, # below Pages
            'show_in_rest' => true,

            'show_in_graphql' => true,
            'graphql_single_name' => self::$post_type_name . 'Entry',
            'graphql_plural_name' => self::$post_type_name . 'Entries'
        ];

        register_post_type(self::$post_type_name, $post_type_args);

        # Add optionally post type support
        if (Options::get('enable_editor'))
            add_post_type_support(self::$post_type_name, 'editor');

        if (Options::get('enable_excerpt'))
            add_post_type_support(self::$post_type_name, 'excerpt');

        if (Options::get('enable_custom_fields'))
            add_post_type_support(self::$post_type_name, 'custom-fields');

        if (Options::get('enable_featured_image'))
            add_post_type_support(self::$post_type_name, 'thumbnail');
    }

    public static function addPrefixFilterRewriteRules(array $rules): array
    {
        $post_type = get_post_type_object(self::$post_type_name);
        $new_rules = [];

        # Add filter permalink structure for post type archive
        if ($post_type->has_archive) {
            $archive_url_path = (true === $post_type->has_archive) ? $post_type->rewrite['slug'] : $post_type->has_archive;
            $new_rules[ltrim(sprintf('%s/prefix:([^/]+)/?$', $archive_url_path), '/')] = sprintf('index.php?post_type=%s&prefix=$matches[1]', PostType::getPostTypeName());
            $new_rules[ltrim(sprintf('%s/prefix:([^/]+)/page/([0-9]{1,})/?$', $archive_url_path), '/')] = sprintf('index.php?post_type=%s&prefix=$matches[1]&paged=$matches[2]', PostType::getPostTypeName());
        }

        $rules = array_merge($new_rules, $rules);

        return $rules;
    }

    public static function addMetaBoxes(): void
    {
        # There wont be added other meta boxes yet
    }

    public static function getAssociatedTaxonomies()
    {
        $arr_all_taxonomies = get_taxonomies(null, 'objects');
        if (empty($arr_all_taxonomies)) return false;

        $arr_associated_taxonomies = [];

        foreach ($arr_all_taxonomies as $taxonomy) {
            if (in_array(PostType::getPostTypeName(), $taxonomy->object_type)) {
                $arr_associated_taxonomies[] = $taxonomy;
            }
        }

        return empty($arr_associated_taxonomies) ? false : $arr_associated_taxonomies;
    }

    public static function filterUpdatedMessages($arr_messages): array
    {
        $revision_id = empty($_GET['revision']) ? false : intval($_GET['revision']);

        $arr_messages[self::$post_type_name] = [
            1 => sprintf(__('%1$s updated. (<a href="%2$s">View %1$s</a>)', 'lexicon-svko'), PostTypeLabels::getItemSingularName(), get_permalink()),
            2 => __('Custom field updated.', 'lexicon-svko'),
            3 => __('Custom field deleted.', 'lexicon-svko'),
            4 => sprintf(__('%s updated.', 'lexicon-svko'), PostTypeLabels::getItemSingularName()),
            5 => sprintf(__('%1$s restored to revision from %2$s', 'lexicon-svko'), PostTypeLabels::getItemSingularName(), wp_post_revision_title($revision_id, false)),
            6 => sprintf(__('%1$s published. (<a href="%2$s">View %1$s</a>)', 'lexicon-svko'), PostTypeLabels::getItemSingularName(), get_permalink()),
            7 => sprintf(__('%s saved.', 'lexicon-svko'), PostTypeLabels::getItemSingularName()),
            8 => sprintf(__('%s submitted.', 'lexicon-svko'), PostTypeLabels::getItemSingularName()),
            9 => sprintf(__('%1$s scheduled. (<a target="_blank" href="%2$s">View %1$s</a>)', 'lexicon-svko'), PostTypeLabels::getItemSingularName(), get_permalink()),
            10 => sprintf(__('Draft updated. (<a target="_blank" href="%1$s">Preview %2$s</a>)', 'lexicon-svko'), add_query_arg(['preview' => 'true'], get_permalink()), PostTypeLabels::getItemSingularName())
        ];

        return $arr_messages;
    }

    public static function getArchiveLink(string $filter = '', $taxonomy_term = null)
    {
        if (apply_filters('lexicon_use_plain_prefix_url_structure', false))
            $permalink_structure = '';
        else
            $permalink_structure = get_option('permalink_structure');

        # Get base url
        if ($taxonomy_term)
            $base_url = get_term_link($taxonomy_term);
        else
            $base_url = get_post_type_archive_link(self::$post_type_name);

        if (empty($base_url))
            return false;

        if (empty($permalink_structure))
            return add_query_arg(['prefix' => rawurlencode($filter)], $base_url);
        else
            return user_trailingslashit(sprintf('%1$s/prefix:%2$s', rtrim($base_url, '/'), rawurlencode($filter)));
    }

    public static function filterPostTypeLink(string $link, WP_Post $post): string
    {
        static $associated_taxonomies;

        if (!empty($post->post_type) && $post->post_type == self::$post_type_name) {
            # Get the taxonomies for this post type
            if (empty($associated_taxonomies))
                $associated_taxonomies = static::getAssociatedTaxonomies();

            if ($associated_taxonomies) {
                foreach ($associated_taxonomies as $taxonomy) {
                    $virtual_slug = "%{$taxonomy->name}%";
                    if (strpos($link, $virtual_slug)) {
                        $terms = wp_get_object_terms($post->ID, $taxonomy->name);
                        if ($terms) {
                            $first_term = reset($terms);
                            $term_slug = $first_term->slug;
                        } else {
                            $term_slug = sanitize_title(__('Uncategorized', 'lexicon-svko'));
                        }

                        $link = str_replace($virtual_slug, $term_slug, $link);
                    }
                }
            }
        }

        return $link;
    }

    public static function enableBlockEditor(bool $editable, string $post_type_name): bool
    {
        if (PostType::getPostTypeName() == $post_type_name) {
            return (bool) Options::get('enable_block_editor');
        }
        return $editable;
    }
}

PostType::init();
