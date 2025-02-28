<?php

use SVKO\Lexicon\{ MockingBird, Options };

$link_target = Options::get('link_item_target');
$exclude_post_type = ['attachment', 'wp_block'];
$post_types = get_Post_Types(['show_ui' => true], 'objects');

if (!Class_Exists('DOMDocument')) : ?>
    <p class="warning box"><?php _e('This feature won\'t work because the <a href="https://secure.php.net/manual/en/book.dom.php" target="_blank">DOM PHP extension</a> is not available on your webserver.', 'lexicon-svko') ?></p>
<?php endif ?>

<table class="form-table">

    <?php foreach ($post_types as $type) : if (in_Array($type->name, $exclude_post_type)) continue; ?>
        <tr>
            <th><?php echo $type->label ?></th>
            <td>
                <label>
                    <input type="checkbox" <?php checked(true) ?>>
                    <?php printf(__('Add links in %s', 'lexicon-svko'), $type->label) ?>
                    <?php MockingBird::printProNotice('unlock') ?>
                </label><br>

                <label>
                    <input type="checkbox" <?php disabled(true) ?>>
                    <?php _e('Open link in a new window/tab', 'lexicon-svko') ?>
                </label>
            </td>
        </tr>
    <?php endforeach ?>

    <tr>
        <th><?php _e('Text Widget', 'lexicon-svko') ?></th>
        <td>
            <label>
                <input type="checkbox" <?php disabled(true);
                                        checked(true) ?>>
                <?php _e('Add links in the default text widget', 'lexicon-svko') ?>
                <?php MockingBird::printProNotice('unlock') ?>
            </label><br>
        </td>
    </tr>

    <tr>
        <th><label><?php _e('Minimum length', 'lexicon-svko') ?></label></th>
        <td>
            <input type="number" value="1" <?php disabled(true) ?>>
            <?php _e('characters', 'lexicon-svko') ?>
            <?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php _e('The minimum length of cross linked words. Shorter words <u>will not be</u> cross linked automatically.', 'lexicon-svko') ?></p>
        </td>
    </tr>

    <tr>
        <th><?php _e('Filter order', 'lexicon-svko') ?></th>
        <td>
            <label for="cross_linker_priority">
                <select id="cross_linker_priority" name="cross_linker_priority">
                    <option value="before_shortcodes" <?php selected(Options::get('cross_linker_priority') == 'before_shortcodes') ?>><?php _e('Before shortcodes', 'lexicon-svko') ?></option>
                    <option value="" <?php selected(Options::get('cross_linker_priority') == 'after_shortcodes') ?>><?php _e('After shortcodes', 'lexicon-svko') ?></option>
                </select>
            </label>
            <p class="help"><?php _e('By default the cross links should be added to the content after rendering all shortcodes. This works not for shortcodes which are calling the "the_content" filter while rendering. In this case please change this setting to "Before shortcodes".', 'lexicon-svko') ?></p>
        </td>
    </tr>

    <tr>
        <th><?php _e('Complete words', 'lexicon-svko') ?></th>
        <td>
            <label>
                <input type="checkbox" <?php disabled(true) ?>>
                <?php _e('Link complete words only.', 'lexicon-svko') ?>
                <?php MockingBird::printProNotice('unlock') ?>
            </label>
        </td>
    </tr>

    <tr>
        <th><?php _e('Case sensitivity', 'lexicon-svko') ?></th>
        <td>
            <label>
                <input type="checkbox" <?php disabled(true) ?>>
                <?php _e('Link items case sensitive.', 'lexicon-svko') ?>
                <?php MockingBird::printProNotice('unlock') ?>
            </label>
        </td>
    </tr>

    <tr>
        <th><?php _e('First match only', 'lexicon-svko') ?></th>
        <td>
            <label>
                <input type="checkbox" <?php disabled(true) ?>>
                <?php _e('Link the first match of each item only.', 'lexicon-svko') ?>
                <?php MockingBird::printProNotice('unlock') ?>
            </label>
        </td>
    </tr>

    <tr>
        <th><?php _e('Recursion', 'lexicon-svko') ?></th>
        <td>
            <label>
                <input type="checkbox" <?php disabled(true) ?>>
                <?php _e('Link the item in its own content.', 'lexicon-svko') ?>
                <?php MockingBird::printProNotice('unlock') ?>
            </label>
        </td>
    </tr>

    <tr>
        <th><label><?php _e('Link title length', 'lexicon-svko') ?></label></th>
        <td>
            <input type="number" value="<?php echo esc_Attr(Options::get('cross_link_title_length')) ?>" <?php disabled(true) ?>> <?php _e('words', 'lexicon-svko') ?>
            <?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php _e('The number of words of the linked item used as link title. This option does not affect manually created excerpts.', 'lexicon-svko') ?></p>
        </td>
    </tr>

</table>