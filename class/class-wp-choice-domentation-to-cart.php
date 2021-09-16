<?php 
/**
 * @class
 * @for add product mintroute card to woocommerce cart 
 * @var 1.0
 **/
class wp_choice_domentation_to_cart{
    public function __construct(){
          
          add_action( 'wp_enqueue_scripts',array($this,'mintroute_script_add_script_js'),99);
          add_action( 'wp_ajax_domenation_price_cart',array($this,'action_domenation_price_add_cart'));
          add_action( 'wp_ajax_nopriv_domenation_price_cart',array($this,'action_domenation_price_add_cart'));
          add_filter( 'woocommerce_add_cart_item_data', array($this,'mintroute_filter_woocommerce_add_cart_item_data'), 10, 3 ); 
          add_action( 'woocommerce_before_calculate_totals', array($this,'mintroute_custom_price_to_cart_item'), 99 );
          add_filter('woocommerce_cart_item_price',array($this,'mintroute_modify_cart_product_price'),10,3);
    }

    public function mintroute_script_add_script_js(){
        wp_enqueue_script('mintroute-choice-domenation',
        WooMintroute_PLUGIN_URL.'assets/js/mintroute-choice-domenation-script-1.js',
        array(),'',true);
        wp_localize_script( 'mintroute-choice-domenation', 'mintroute_choice_domenation_price_cart_ajax',
            array( 
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
            )
        );
    }

    function action_domenation_price_add_cart(){
        $denominations_data =  get_post_meta( $_REQUEST['product_id'] , '_mintroute_card_denomination', true);
        $denomination       =  $denominations_data[$_REQUEST['denomenation_id']];
        $type_card          =  get_post_meta($_REQUEST['product_id'],'_mintroute_type_card',true);
        $ip                 =  $_SERVER['REMOTE_ADDR'];
        session_start();
        $_SESSION['denominations_'.$ip.'_'.$_REQUEST['product_id']]   = $denomination;
        $denomination['_mintroute_denomination_price']                = wc_price($denomination['_mintroute_denomination_price']);
        $_SESSION['account_id_'.$ip.'_'.$_REQUEST['product_id']]      = $_REQUEST['account_id'];
        $_SESSION['type_card_'.$ip.'_'.$_REQUEST['product_id']]       = $type_card;
        if($type_card == 'mobile_legends'){
            $_SESSION['zone_id_'.$ip.'_'.$_REQUEST['product_id']]       = $_REQUEST['zone_id'];
        }
        echo json_encode($denomination );
        wp_die();
    }

    function mintroute_filter_woocommerce_add_cart_item_data( $cart_item_data, $product_id){
        session_start();
        $product                =  wc_get_product( $product_id );
        $ip                     =  $_SERVER['REMOTE_ADDR'];
        if($product->get_type() === 'mintroute_card'):
            $denomination       =  $_SESSION['denominations_'.$ip.'_'.$product_id];
            $account_id         =  $_SESSION['account_id_'.$ip.'_'.$product_id];
            $type_card          =  $_SESSION['type_card_'.$ip.'_'.$product_id];
            if(!empty($denomination)):
                $cart_item_data['_mintroute_denomination'] = $denomination;
                $cart_item_data['_mintroute_account_id']   = $account_id;
                $cart_item_data['_mintroute_type_card']    = $type_card;
                if($type_card == 'mobile_legends'){
                    $zone_id        =  $_SESSION['zone_id_'.$ip.'_'.$product_id];
                    $cart_item_data['_mintroute_zone_id']      = $zone_id;
                }
            endif;
        endif;
        return $cart_item_data; 
    }

    function mintroute_custom_price_to_cart_item($cart_object ){
        if( !WC()->session->__isset( "reload_checkout" )) {
            foreach ( $cart_object->cart_contents as $key => $value ) {
                if( isset( $value["_mintroute_denomination"]['_mintroute_denomination_price'] ) ) {
                    $value['data']->set_price($value["_mintroute_denomination"]['_mintroute_denomination_price']);
                }
            }  
        }  
    }

    function mintroute_modify_cart_product_price( $price, $cart_item, $cart_item_key){
        if( isset( $cart_item["_mintroute_denomination"]['_mintroute_denomination_price'] ) ) {
            $price = $cart_item["_mintroute_denomination"]['_mintroute_denomination_price'];
        }
        return $price;
    }

}

new wp_choice_domentation_to_cart();