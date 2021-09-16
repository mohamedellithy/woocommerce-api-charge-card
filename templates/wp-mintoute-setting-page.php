<?php $WooMintroute_api_settings = get_option('WooMintroute_api_settings'); ?>
<div class="wrap">
	<h1><?php _e('General Settings','mintroute'); ?></h1>

	<form method="post"  novalidate="novalidate">
        <?php wp_nonce_field('creservation_settings_options','creservation_settings_options_field'); ?>
        <table class="form-table" role="presentation">
            <tbody>
                
                <tr>
                    <th scope="row"><label for="username"><?php _e('Username','mintroute'); ?> </label></th>
                    <td>
                        <input name="api_username" type="text" id="username" value="<?php echo $WooMintroute_api_settings['api_username'] ?? ''; ?>"  class="regular-text" >
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="Password"><?php _e('Password','mintroute'); ?> </label></th>
                    <td>
                        <input name="api_password" type="text" id="Password" value="<?php echo $WooMintroute_api_settings['api_password'] ?? ''; ?>" class="regular-text" >
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="Password"><?php _e('Access Key','mintroute'); ?> </label></th>
                    <td>
                        <input name="api_access_key" type="text" id="Password" value="<?php echo $WooMintroute_api_settings['api_access_key'] ?? ''; ?>" class="regular-text" >
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="Password"><?php _e('Secret Key','mintroute'); ?> </label></th>
                    <td>
                        <input name="api_secret_key" type="text" id="Password" value="<?php echo $WooMintroute_api_settings['api_secret_key'] ?? ''; ?>" class="regular-text" >
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="Mode"><?php _e('Mode','mintroute'); ?> </label></th>
                    <td>
                        <select name="api_mode" type="text" id="Mode" value="" class="regular-text">
                            <option value="test"       <?php echo !empty($WooMintroute_api_settings['api_mode']) && $WooMintroute_api_settings['api_mode'] == 'test'      ? 'selected' : ''; ?> > <?php _e('Test','mintroute') ?>      </option>	
                            <option value="production" <?php echo !empty($WooMintroute_api_settings['api_mode']) && $WooMintroute_api_settings['api_mode'] == 'production'? 'selected' : ''; ?> > <?php _e('Production','mintroute') ?></option>	
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="url"><?php _e('Url','mintroute'); ?> </label></th>
                    <td>
                        <input value="<?php echo $WooMintroute_api_settings['api_url'] ?? 'https://sandbox.mintroute.com/api'; ?>" name="api_url" type="url" id="url"   class="regular-text" readonly>
                    </td>
                </tr>
                
            </tbody>
        </table>
		<p class="submit"><input type="submit" name="WooMintroute_api_settings" id="submit" class="button button-primary" value="<?php _e('Save Changes','mintroute'); ?>"></p></form>
    </form>
</div>