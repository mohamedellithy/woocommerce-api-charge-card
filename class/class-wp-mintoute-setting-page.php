<?php
/**
 * @class
 * @for mintroute api set params and credentials from admin section
 * @var 1.0
 **/
class wp_mintoute_setting_page{
    public $pages     = array();
    public $sub_pages = array();
    function __construct(){
        $this->pages = [
            [
                'Page-name'      => __( 'mintroute setting', 'mintroute' ),
                'Page-title'     => __( 'mintroute setting', 'mintroute' ),
                'Page-capability'=> 'manage_options',
                'Page-slug'      => 'mintoute-setting',
                'Page-template'  => array($this,'template_setting_page'),
                'Page-icon'      => WooMintroute_PLUGIN_URL.'assets/img/Mintroute.png',
                'Page-position'  => 20,
            ],
        ];
        
        $this->sub_pages = [
            [
                'Page-Parent'    => 'mintoute-setting',
                'Page-title'     => __( 'Mintroute Logs', 'mintroute' ),
                'Page-name'      => __( 'Mintroute Logs', 'mintroute' ),
                'Page-capability'=> 'manage_options',
                'Page-slug'      => 'mintoute-logs',
                'Page-template'  => array($this,'template_mintroute_logs'),
                'Page-icon'      => WooMintroute_PLUGIN_URL.'assets/img/Mintroute.png',
                'Page-position'  => 20,
            ],
        ];
        add_action('admin_menu',array($this,'mintoute_setting_page'),99);
        add_action('admin_menu',array($this,'mintoute_setting_sub_pages'),99);
        add_action('init',array($this,'WooMintroute_api_settings'),99);
        add_action('init',array($this,'WooMintroute_reset_logs'),99);
        add_action('init',array($this,'WooMintroute_test_api_card'),99);
    }

    function mintoute_setting_page(){
        if( !empty(  $this->pages ) ) :
            foreach( $this->pages as $page):
                add_menu_page(
                    $page['Page-name'],
                    $page['Page-title'],
                    $page['Page-capability'],
                    $page['Page-slug'],
                    $page['Page-template'],
                    $page['Page-icon'],
                    $page['Page-position'],
                );
            endforeach;
        endif; 
    }

    function mintoute_setting_sub_pages(){
        if( !empty(  $this->sub_pages ) ) :
            foreach( $this->sub_pages as $sub_page):
                add_submenu_page(
                    $sub_page['Page-Parent'],
                    $sub_page['Page-name'],
                    $sub_page['Page-title'],
                    $sub_page['Page-capability'],
                    $sub_page['Page-slug'],
                    $sub_page['Page-template'],
                    $sub_page['Page-icon'],
                    $sub_page['Page-position'],
                );
            endforeach;
        endif; 
    }

    function template_setting_page(){
        echo require_once WooMintroute_PLUGIN_DIR.'templates/wp-mintoute-setting-page.php';
    }

    function WooMintroute_api_settings(){
        if(isset($_POST['WooMintroute_api_settings'])){
            $WooMintroute_api_settings = [
                'api_username'    => $_POST['api_username'],
                'api_password'    => $_POST['api_password'],
                'api_mode'        => $_POST['api_mode'],
                'api_access_key'  => $_POST['api_access_key'],
                'api_secret_key'  => $_POST['api_secret_key'],
            ];

            update_option('WooMintroute_api_settings', $WooMintroute_api_settings);
        }
    }

    function WooMintroute_reset_logs(){
        if(isset($_POST['reset_logs'])){
            file_put_contents(WooMintroute_PLUGIN_DIR."mintroute_logs.log",'');
        }
    }

    function WooMintroute_test_api_card(){
        if(isset($_POST['test_api_card'])){
            $order_id = rand(1,10000);
            $mintroute_topup_test   = new wp_mintroute_cards($order_id,array('type_card'=>'mobile_legends','test'=>true));
            $mintroute_result       = $mintroute_topup_test->result;
            mintroute_topup_logs($mintroute_result['message'] ?? $mintroute_result['error'] ,$order_id,$mintroute_result['status']);
            add_action( 'admin_notices', function() use($mintroute_result){
                $class = 'notice '.($mintroute_result['status'] == 1 ? 'notice-success':'notice-error');
                printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $mintroute_result['message'] );
            });
        }
    }
    

    function template_mintroute_logs(){
        echo require_once WooMintroute_PLUGIN_DIR.'templates/wp-mintoute-show-logs.php';
    }
}
# run setting page
new wp_mintoute_setting_page();