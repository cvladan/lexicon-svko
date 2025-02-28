<?php

use SVKO\Lexicon\{
    MockingBird,
    Options
};

?>
<table class="form-table">

    <tr>
        <th><label for="activate_tooltips"><?php _e('Tooltips', 'lexicon-svko') ?></label></th>
        <td>
            <select name="activate_tooltips" id="activate_tooltips">
                <option value="1" <?php selected(Options::get('activate_tooltips')) ?>><?php _e('On', 'lexicon-svko') ?></option>
                <option value="0" <?php selected(!Options::get('activate_tooltips')) ?>><?php _e('Off', 'lexicon-svko') ?></option>
            </select>
            <p class="help"><?php _e('Enables or disables the tooltips for item links on the frontend.', 'lexicon-svko') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php _e('Animation duration', 'lexicon-svko') ?></label></th>
        <td>
            <input type="number" value="350" <?php disabled(true) ?>>
            <?php _e('ms', 'lexicon-svko') ?>
            <?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php _e('The duration for the opening and closing animations, in milliseconds.', 'lexicon-svko') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php _e('Delay', 'lexicon-svko') ?></label></th>
        <td>
            <input type="number" value="300" <?php disabled(true) ?>>
            <?php _e('ms', 'lexicon-svko') ?>
            <?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php _e('Upon mouse interaction, this is the delay before the tooltip starts its opening and closing animations, in milliseconds.', 'lexicon-svko') ?></p>
        </td>
    </tr>

    <tr>
        <th><?php _e('Click-Event', 'lexicon-svko') ?></th>
        <td>
            <label>
                <input type="checkbox" <?php disabled(true) ?>>
                <?php _e('Show the tooltips only if the user <strong>clicks</strong> on it. This option will <strong>disable the link</strong> to the cross linked entry.', 'lexicon-svko') ?>
            </label><?php MockingBird::printProNotice('unlock') ?>
        </td>
    </tr>

</table>