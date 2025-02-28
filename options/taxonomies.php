<?php

use SVKO\Lexicon\{
    MockingBird,
    Options,
    PostType,
    PostTypeLabels,
    Taxonomies
};

// Ensure taxonomies are loaded after registration
$arr_taxonomies = [];
$tax_options = (array) Options::get('taxonomies');

if (did_action('init')) {
    // Get registered taxonomies
    $arr_taxonomies = Taxonomies::getTaxonomies();
    
    // Add our custom tag taxonomy if it exists
    $tag_taxonomy = get_taxonomy(PostType::getPostTypeName() . '-tag');
    if ($tag_taxonomy) {
        $arr_taxonomies[PostType::getPostTypeName() . '-tag'] = $tag_taxonomy;
    }
    
    // Change default labels if taxonomies exist
    if (isset($arr_taxonomies['category'])) {
        $arr_taxonomies['category']->label = __('Post Categories', 'lexicon-svko');
    }
    if (isset($arr_taxonomies['post_tag'])) {
        $arr_taxonomies['post_tag']->label = __('Post Tags', 'lexicon-svko');
    }
    if (isset($arr_taxonomies[PostType::getPostTypeName() . '-tag'])) {
        $arr_taxonomies[PostType::getPostTypeName() . '-tag']->label = __(PostTypeLabels::getItemSingularName() . ' Tags', 'lexicon-svko');
    }
}
?>
<p>
    <?php printf(__('Please choose the taxonomies you want to use for your %s.', 'lexicon-svko'), PostTypeLabels::getItemPluralName()) ?>
    <?php _e('The choosen taxonomies will show a prefix filter above the term archives and ordered by post title. The posts per page limit will be applied from the archive option below.', 'lexicon-svko') ?>
</p>

<input type="hidden" name="taxonomies" value="0">

<table class="form-table">

    <?php foreach ($arr_taxonomies as $taxonomy) : ?>
        <tr>
            <th>
                <label for="use_taxonomy_<?php echo $taxonomy->name ?>"><?php echo $taxonomy->label ?></label>
                <div class="taxonomy-name"><code>(<?php echo $taxonomy->name ?>)</code></div>
            </th>
            <td>
                <label for="register_<?= $taxonomy->name ?>_taxonomy_for_<?= PostType::getPostTypeName() ?>">
                    <?php if ($taxonomy->name == PostType::getPostTypeName() . '-tag') : ?>
                        <input type="checkbox" name="taxonomies[]" value="<?php echo $taxonomy->name ?>" id="register_<?php echo $taxonomy->name ?>_taxonomy_for_<?= PostType::getPostTypeName() ?>" <?php checked(in_array($taxonomy->name, $tax_options)) ?>>
                    <?php else : ?>
                        <input type="checkbox" <?php disabled(true) ?>>
                    <?php endif ?>
                    <?php printf(__('Use this taxonomy for %s.', 'lexicon-svko'), PostTypeLabels::getItemPluralName()) ?>
                    <?php if ($taxonomy->name != PostType::getPostTypeName() . '-tag') MockingBird::printProNotice('unlock') ?>
                </label>
                <p class="help">
                    <?php printf(__('Enables %1$s for %2$s.', 'lexicon-svko'), $taxonomy->label, PostTypeLabels::getItemPluralName()) ?>
                    <?php if (!empty($taxonomy->post_types)) {
                        $arr_post_types = [];
                        foreach ($taxonomy->post_types as $post_type) $arr_post_types[] = $post_type->label;
                        echo wp_sprintf(__('This taxonomy is used for %l.', 'lexicon-svko'), $arr_post_types);
                    } ?>
                </p>
            </td>
        </tr>
    <?php endforeach ?>

</table>