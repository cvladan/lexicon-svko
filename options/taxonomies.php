<?php

use SVKO\Lexicon\{
    I18n,
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
        $arr_taxonomies['category']->label = I18n::__('Post Categories');
    }
    if (isset($arr_taxonomies['post_tag'])) {
        $arr_taxonomies['post_tag']->label = I18n::__('Post Tags');
    }
    if (isset($arr_taxonomies[PostType::getPostTypeName() . '-tag'])) {
        $arr_taxonomies[PostType::getPostTypeName() . '-tag']->label = I18n::__(PostTypeLabels::getItemSingularName() . ' Tags');
    }
}
?>
<p>
    <?php printf(I18n::__('Please choose the taxonomies you want to use for your %s.'), PostTypeLabels::getItemPluralName()) ?>
    <?php I18n::_e('The choosen taxonomies will show a prefix filter above the term archives and ordered by post title. The posts per page limit will be applied from the archive option below.') ?>
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
                    <?php printf(I18n::__('Use this taxonomy for %s.'), PostTypeLabels::getItemPluralName()) ?>
                    <?php if ($taxonomy->name != PostType::getPostTypeName() . '-tag') MockingBird::printProNotice('unlock') ?>
                </label>
                <p class="help">
                    <?php printf(I18n::__('Enables %1$s for %2$s.'), $taxonomy->label, PostTypeLabels::getItemPluralName()) ?>
                    <?php if (!empty($taxonomy->post_types)) {
                        $arr_post_types = [];
                        foreach ($taxonomy->post_types as $post_type) $arr_post_types[] = $post_type->label;
                        echo wp_sprintf(I18n::__('This taxonomy is used for %l.'), $arr_post_types);
                    } ?>
                </p>
            </td>
        </tr>
    <?php endforeach ?>

</table>