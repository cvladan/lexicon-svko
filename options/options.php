<?php

use SVKO\Lexicon\{ I18n, Options, PostType, PostTypeLabels };

$option_boxes = Options::getOptionBoxes();
$arr_columns = [
    'left' => (array) $option_boxes['main'],
    'right' => (array) $option_boxes['side']
];

$options_saved = isset($_GET['options_saved']);

?>
<div class="wrap">

    <h1><?php printf(__('%s Settings', 'lexicon-svko'), PostTypeLabels::getItemDashboardName()) ?></h1>

    <?php if ($options_saved) : ?>
        <div id="message" class="updated fade">
            <p><strong><?php _e('Settings saved.', 'lexicon-svko') ?></strong></p>
        </div>
    <?php endif ?>

    <form method="post" action="<?php echo remove_Query_Arg('options_saved') ?>">
        <div class="metabox-holder">
            <?php foreach ($arr_columns as $column => $boxes) : ?>
                <div class="postbox-container <?php echo $column ?>">
                    <?php foreach ($boxes as $box) : ?>
                        <div class="postbox <?php echo $box->slug ?>">
                            <div class="postbox-header">
                                <h2 class="hndle"><?php echo $box->title ?></h2>
                            </div>
                            <div class="inside"><?php include $box->file ?></div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endforeach ?>
        </div>
<p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'lexicon-svko') ?>">
</p>

<?php wp_nonce_field('save_' . PostType::getPostTypeName() . '_options') ?>
</form>

<h2><?php _e('Factory Reset', 'lexicon-svko') ?></h2>
<form method="post" action="<?php echo remove_Query_Arg('options_saved') ?>">
<label for="reset-all-options">
    <input type="checkbox" name="." id="reset-all-options" value="" required>
    <?php _e('Reset all settings to factory default state and delete all existing settings above.', 'lexicon-svko') ?>
</label>

<p class="submit">
    <input type="submit" class="button-secondary" value="<?php _e('Reset all options', 'lexicon-svko') ?>">
            <input type="submit" class="button-secondary" value="<?php I18n::_e('Reset all options') ?>">
        </p>

        <?php wp_nonce_field('save_' . PostType::getPostTypeName() . '_options') ?>
    </form>

</div>
