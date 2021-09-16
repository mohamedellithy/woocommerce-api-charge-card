<?php 
 /**
 * Plugin Name:       Woocommerce-Mintroute-Api Plug
 * Plugin URI:        https://www.hisms.ws/
 * Description:       If you need any help just contact me on +201026051966 ( whatsapp too )  mohamedellithyfreelanc@gmail.com
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Mohamed Ellithy
 * Author URI:        https://mostaql.com/u/mohamed_mostaq
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       mintroute
 **/


    /**
    * WooMintroute_VERSION current version
    *
    * @since 1.0
    */
    if ( ! defined( 'WooMintroute_VERSION' ) ) {
        define( 'WooMintroute_VERSION', '1.0' );
    }




    /**
    * WooMintroute_VERSION DB current version DB
    * @since 1.0
    */
    if ( ! defined( 'WooMintroute_DB_VERSION' ) ) {
        define( 'WooMintroute_DB_VERSION', '1.0' );
    }




    /**
    * Absolute path to the plugin directory. 
    * eg - /var/www/html/wp-content/plugins/Woo-Mintroute/
    *
    * @since 1.0
    */
    if ( ! defined( 'WooMintroute_PLUGIN_DIR' ) ) {
        define( 'WooMintroute_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
    }


    /**
    * Absolute path to the plugin directory. 
    * eg - /var/www/html/wp-content/plugins/Woo-Mintroute/
    *
    * @since 1.0
    */
    if ( ! defined( 'WooMintroute_PLUGIN_URL' ) ) {
        define( 'WooMintroute_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
    }
    
    # call styles and scripts files
    add_action( 'admin_enqueue_scripts','mintroute_style',99);
    add_action( 'wp_enqueue_scripts','mintroute_style',99);
    function mintroute_style(){
        wp_enqueue_style('mintoute-style',WooMintroute_PLUGIN_URL.'assets/mintroute-3-1.css');
        wp_enqueue_script('mintoute-script',WooMintroute_PLUGIN_URL.'assets/js/script-mintroute-1-50.js',array(),'',true);
    }
    
    /**
     *  here call all classes for charge card
     **/
    require_once WooMintroute_PLUGIN_DIR.'class/class-wp-add-product-type-mintroute.php';
    require_once WooMintroute_PLUGIN_DIR.'class/class-wp-mintoute-api.php';
    require_once WooMintroute_PLUGIN_DIR.'class/class-wp-mintoute-account-validation.php';
    require_once WooMintroute_PLUGIN_DIR.'class/class-wp-mintoute-top-up.php';
    require_once WooMintroute_PLUGIN_DIR.'class/mintroute-cards-type/class-wp-mintroute-card-free-fire.php';
    require_once WooMintroute_PLUGIN_DIR.'class/mintroute-cards-type/class-wp-mintroute-card-razer-gold.php';
    require_once WooMintroute_PLUGIN_DIR.'class/mintroute-cards-type/class-wp-mintroute-card-mobile-legends.php';
    require_once WooMintroute_PLUGIN_DIR.'class/mintroute-cards-type/class-wp-mintroute-cards.php';
    require_once WooMintroute_PLUGIN_DIR.'class/class-wp-mintoute-setting-page.php';
    require_once WooMintroute_PLUGIN_DIR.'class/class-wp-single-product-mintroute.php';
    require_once WooMintroute_PLUGIN_DIR.'class/class-wp-choice-domentation-to-cart.php';
    require_once WooMintroute_PLUGIN_DIR.'class/class-wp-mintroute-order-complete.php';
    
    # @func to create log for process of charge card 
    function mintroute_topup_logs($message,$order_id,$status){
        if(is_array($message)) { 
            $message = json_encode($message); 
        } 
        #read form file
        $file = fopen(WooMintroute_PLUGIN_DIR."mintroute_logs.log","a"); 
        fwrite($file, "\n" . date('Y-m-d h:i:s') . " [ ".$status." ] :: Order => ( ".$order_id." )  : " . $message); 
        fclose($file); 
    }
    
    # override woocommerce template from plugin firstly
    add_filter( 'woocommerce_locate_template', 'woo_adon_plugin_template', 1, 3 );
    function woo_adon_plugin_template( $template, $template_name, $template_path ) {
        global $woocommerce;
        $_template = $template;
        if ( ! $template_path ) 
            $template_path = $woocommerce->template_url;
    
        $plugin_path  = untrailingslashit( plugin_dir_path( __FILE__ ) )  . '/woocommerce/';
    
        // Look within passed path within the theme - this is priority
        $template = locate_template(
            array(
                $template_path . $template_name,
                $template_name
            )
        );
    
        if( ! $template && file_exists( $plugin_path . $template_name ) )
            $template = $plugin_path . $template_name;
    
        if ( ! $template )
            $template = $_template;

        return $template;
    }