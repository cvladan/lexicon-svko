<?php

use SVKO\Lexicon\MockingBird;

?>
<table class="form-table">

    <tr>
        <th><label><?php _e('Query matches directly', 'lexicon-svko') ?></label></th>
        <td>
            <select <?php disabled(true) ?>>
                <option <?php disabled(true) ?>><?php _e('On', 'lexicon-svko') ?></option>
                <option <?php selected(true) ?>><?php _e('Off', 'lexicon-svko') ?></option>
            </select><?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php _e('Enable this feature to redirect the user to the matched item if the user searched for an exact title.', 'lexicon-svko') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php _e('Autocomplete min length', 'lexicon-svko') ?></label></th>
        <td>
            <input type="number" value="2" <?php disabled(true) ?>>
            <?php _e('characters', 'characters unit', 'lexicon-svko') ?><?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php _e('The minimum number of characters a user must type before suggestions will be shown.', 'lexicon-svko') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php _e('Autocomplete delay', 'lexicon-svko') ?></label></th>
        <td>
            <input type="number" value="400" <?php disabled(true) ?>>
            <?php _e('ms', 'milliseconds time unit', 'lexicon-svko') ?><?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php _e('The delay in milliseconds between a keystroke occurs and the suggestions will be shown.', 'lexicon-svko') ?></p>
        </td>
    </tr>

</table>