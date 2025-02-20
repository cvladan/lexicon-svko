<?php

namespace SVKO\Lexicon;
defined('ABSPATH') || exit;

# Initialize the ACF integration
add_action("plugins_loaded", function (): void {
  if (!is_admin()) {
    # Determine cross linker priority based on options
    $cross_linker_priority = Options::get("cross_linker_priority") === "before_shortcodes" ? 10.5 : 15;

    # Register filters for different ACF field types
    $field_types = ["wysiwyg", "textarea", "text"];
    foreach ($field_types as $type) {
      add_filter("acf/format_value/type={$type}", fn(?string $content, $post_id, array $field) => filter_field_value($content, $post_id, $field), $cross_linker_priority, 3 );
    }
  }
});

# Filter ACF field value to add cross-links
function filter_field_value(?string $content, $post_id, array $field): string
{
  if (empty($content)) { return ""; }

  $post = is_numeric($post_id) ? get_post($post_id) : null;

  if (!$post) {return $content; }

  # Check if Cross-Linking is activated for this post type
  return apply_filters("encyclopedia_link_items_in_post", true, $post) ? Core::addCrossLinks($content, $post) : $content;
}

# Filter text field value with formatting consideration
function filter_text_value(?string $content, $post_id, array $field): string
{
  if (empty($content)) { return ""; }

  $compatible_formattings = ["html", "br"];

  return in_array($field["formatting"], $compatible_formattings, true) ? filter_field_value($content, $post_id, $field) : $content;
}
