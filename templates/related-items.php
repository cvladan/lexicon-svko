<?php /* Globals: $related_items */

global $post;

use SVKO\Lexicon\{ Core, I18n, PostTypeLabels };

if ($related_items) : ?>
    <h3><?php printf(I18n::__('Related %s'), PostTypeLabels::getItemPluralName()) ?></h3>
    <ul class="related-items">
        <?php while ($related_items->have_Posts()) : $related_items->the_Post() ?>
            <li class="item"><a href="<?php the_permalink() ?>" title="<?= esc_attr(Core::getCrossLinkItemTitle($post)) ?>" class="<?= PostType::getPostTypeName() ?>"><?php the_title() ?></a></li>
        <?php endwhile;
        wp_reset_postdata() ?>
    </ul>
<?php endif;
