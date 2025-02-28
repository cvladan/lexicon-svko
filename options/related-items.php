<?php

use SVKO\Lexicon\{ MockingBird, Options, PostType, PostTypeLabels, Taxonomies };

$arr_taxonomies = Taxonomies::getTaxonomies();

?>
<table class="form-table">

    <tr>
        <th><label for="related_items"><?php printf(__('Display related %s', 'lexicon-svko'), PostTypeLabels::getItemPluralName()) ?></label></th>
        <td>
            <input type="radio" id="related_items_below" <?php checked(true) ?>> <label for="related_items_below"><?php _e('below the content', 'lexicon-svko') ?></label><br>
            <input type="radio" id="related_items_above" ?>> <label for="related_items_above"><?php _e('above the content', 'lexicon-svko') ?></label><?php MockingBird::printProNotice('unlock') ?><br>
            <input type="radio" id="related_items_none" ?>> <label for="related_items_none"><?php printf(__('Do not show related %s.', 'lexicon-svko'), PostTypeLabels::getItemPluralName()) ?></label><?php MockingBird::printProNotice('unlock') ?>
    
    </tr>

    <tr>
        <th><label for="relation_taxonomy"><?php _e('Relation Taxonomy', 'lexicon-svko') ?></label></th>
        <td>
            <select>
                <option value="" disabled="disabled">&mdash; <?php _e('Please choose a taxonomy', 'lexicon-svko') ?> &mdash;</option>
                <?php $relation_taxonomy = Options::get('relation_taxonomy');
                foreach ($arr_taxonomies as $taxonomy) :
                    $arr_post_type_labels = [];
                    foreach ($taxonomy->post_types as $post_type) $arr_post_type_labels[] = $post_type->label; ?>
                    <option <?php selected($relation_taxonomy, $taxonomy->name);
                            disabled($taxonomy->name != PostType::getPostTypeName() . '-tag') ?>>
                        <?php echo $taxonomy->label ?>
                        <?php if (!empty($arr_post_type_labels)) : ?>(<?php echo join(', ', $arr_post_type_labels) ?>)<?php endif ?>
                    </option>
                <?php endforeach ?>
            </select><?php MockingBird::printProNotice('unlock') ?>
        </td>
    </tr>

    <tr>
        <th><label><?php printf(__('Number of related %s', 'lexicon-svko'), PostTypeLabels::getItemPluralName()) ?></label></th>
        <td>
            <input type="number" value="10" <?php disabled(true) ?>><?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php printf(__('Number of related %s which should be shown.', 'lexicon-svko'), PostTypeLabels::getItemPluralName()) ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php _e('Relation Threshold', 'lexicon-svko') ?></label></th>
        <td>
            <input type="number" value="<?php echo Options::get('min_relation_threshold') ?>" <?php disabled(true) ?>><?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php printf(__('Minimum number of common taxonomy terms to generate a relation.', 'lexicon-svko'), PostTypeLabels::getItemPluralName()) ?></p>
        </td>
    </tr>

</table>