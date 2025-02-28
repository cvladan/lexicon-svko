<?php

use SVKO\Lexicon\Options;

?>
<table class="form-table">

    <tr>
        <th><label for="embed_default_style"><?php _e('Default style', 'lexicon-svko') ?></label></th>
        <td>
            <select name="embed_default_style" id="embed_default_style">
                <option value="1" <?php selected(Options::get('embed_default_style')) ?>><?php _e('On', 'lexicon-svko') ?></option>
                <option value="0" <?php selected(!Options::get('embed_default_style')) ?>><?php _e('Off', 'lexicon-svko') ?></option>
            </select>
            <p class="help"><?php _e('Enables or disables the default CSS on the frontend.', 'lexicon-svko') ?></p>
        </td>
    </tr>

</table>