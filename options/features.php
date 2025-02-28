<?php

use SVKO\Lexicon\{MockingBird, Options};

?>
<table class="form-table">

    <tr>
        <th><label for="enable_editor"><?php _e('Text Editor', 'lexicon-svko') ?></label></th>
        <td>
            <select name="enable_editor" id="enable_editor">
                <option value="1" <?php selected(Options::get('enable_editor')) ?>><?php _e('On', 'lexicon-svko') ?></option>
                <option value="0" <?php selected(!Options::get('enable_editor')) ?>><?php _e('Off', 'lexicon-svko') ?></option>
            </select>
            <p class="help"><?php _e('Enables or disables the text editor.', 'lexicon-svko') ?></p>
        </td>
    </tr>

    <tr>
        <th><label for="enable_block_editor"><?php _e('Block Editor', 'lexicon-svko') ?></label></th>
        <td>
            <select name="enable_block_editor" id="enable_block_editor">
                <option value="1" <?php selected(Options::get('enable_block_editor')) ?>><?php _e('On', 'lexicon-svko') ?></option>
                <option value="0" <?php selected(!Options::get('enable_block_editor')) ?>><?php _e('Off', 'lexicon-svko') ?></option>
            </select>
            <p class="help"><?php _e('Enables or disables the block editor (Gutenberg).', 'lexicon-svko') ?></p>
        </td>
    </tr>

    <tr>
        <th><label for="enable_excerpt"><?php _e('Excerpt', 'lexicon-svko') ?></label></th>
        <td>
            <select name="enable_excerpt" id="enable_excerpt">
                <option value="1" <?php selected(Options::get('enable_excerpt')) ?>><?php _e('On', 'lexicon-svko') ?></option>
                <option value="0" <?php selected(!Options::get('enable_excerpt')) ?>><?php _e('Off', 'lexicon-svko') ?></option>
            </select>
            <p class="help"><?php _e('Enables or disables the text excerpt input field.', 'lexicon-svko') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php _e('Revisions', 'lexicon-svko') ?></label></th>
        <td>
            <select>
                <option <?php disabled(true) ?>><?php _e('On', 'lexicon-svko') ?></option>
                <option <?php selected(true) ?>><?php _e('Off', 'lexicon-svko') ?></option>
            </select><?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php _e('Enables or disables revisions.', 'lexicon-svko') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php _e('Comments &amp; Trackbacks', 'lexicon-svko') ?></label></th>
        <td>
            <select>
                <option <?php disabled(true) ?>><?php _e('On', 'lexicon-svko') ?></option>
                <option <?php selected(true) ?>><?php _e('Off', 'lexicon-svko') ?></option>
            </select><?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php _e('Enables or disables comments and trackbacks.', 'lexicon-svko') ?></p>
        </td>
    </tr>

    <tr>
        <th><label for="enable_featured_image"><?php _e('Featured Image', 'lexicon-svko') ?></label></th>
        <td>
            <select name="enable_featured_image" id="enable_featured_image">
                <option value="1" <?php selected(Options::get('enable_featured_image')) ?>><?php _e('On', 'lexicon-svko') ?></option>
                <option value="0" <?php selected(!Options::get('enable_featured_image')) ?>><?php _e('Off', 'lexicon-svko') ?></option>
            </select>
            <p class="help"><?php _e('Enables or disables the featured image.', 'lexicon-svko') ?></p>
        </td>
    </tr>

    <tr>
        <th><label for="enable_custom_fields"><?php _e('Custom Fields', 'lexicon-svko') ?></label></th>
        <td>
            <select name="enable_custom_fields" id="enable_custom_fields">
                <option value="1" <?php selected(Options::get('enable_custom_fields')) ?>><?php _e('On', 'lexicon-svko') ?></option>
                <option value="0" <?php selected(!Options::get('enable_custom_fields')) ?>><?php _e('Off', 'lexicon-svko') ?></option>
            </select>
            <p class="help"><?php _e('Enables or disables custom fields.', 'lexicon-svko') ?></p>
        </td>
    </tr>

</table>