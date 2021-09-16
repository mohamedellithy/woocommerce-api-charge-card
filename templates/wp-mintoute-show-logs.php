<div class="wrap">
	<h1><?php _e('Mintroute Logs','mintroute'); ?></h1>
    <div style="padding:10px;width:100%">
        <form method="post"  novalidate="novalidate" style="display: inline-block;">
            <?php wp_nonce_field('mintroute_logs_setting','mintroute_logs_setting_field'); ?>
            <button class="button button-primary" type="submit" name="reset_logs"><?php _e('Reset Logs','mintroute'); ?></button>
        </form>
        <form method="post"  novalidate="novalidate" style="display: inline-block;">
            <?php wp_nonce_field('mintroute_test_api_card','mintroute_test_api_card_field'); ?>
            <button class="button button-info" type="submit" name="test_api_card"><?php _e('Mintroute Test Api','mintroute'); ?></button>
        </form>
    </div>
    <textarea width="100%" height="100%" class="" rows="30" cols="150" style="background-color:white;" readonly>
       <?php echo file_get_contents(WooMintroute_PLUGIN_DIR."mintroute_logs.log"); ?>
    </textarea>
</div>