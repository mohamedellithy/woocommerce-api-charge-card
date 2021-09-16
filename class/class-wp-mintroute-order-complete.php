<?php 
/**
 * @class
 * @for mintroute in case order completed 
 * @var 1.0
 **/
class wp_mintroute_order_complete{
    static $type_card = array(
        'free_fire'      => 'Free Fire',
        'razer_gold'     => 'Razer Gold',
        'jio_saavn'      => 'Jio Saavn',
        'net_dragon'     => 'Net Dragon',
        'mobile_legends' => 'Mobile Legends',
    );
    function __construct(){
        add_action( 'woocommerce_add_order_item_meta',array($this,'mintroute_add_order_item_meta'), 10, 3 );
        add_action( 'woocommerce_order_status_completed', array($this,'mintroute_payment_complete' ));
        add_filter('woocommerce_order_item_display_meta_key', array($this,'mintroute_wc_order_item_display_meta_key'), 20, 3 );
    }

    function mintroute_add_order_item_meta($item_id, $cart_item, $cart_item_key ){
        if( isset( $cart_item['_mintroute_denomination'] ) && isset( $cart_item['_mintroute_account_id'] ) ) {
            wc_add_order_item_meta( $item_id, '_mintroute_account_id',   $cart_item['_mintroute_account_id'] );
            wc_add_order_item_meta( $item_id, '_mintroute_denomination', $cart_item['_mintroute_denomination'] );
            wc_add_order_item_meta( $item_id, '_mintroute_type_card', $cart_item['_mintroute_type_card'] );
            if($cart_item['_mintroute_type_card'] == 'mobile_legends'){
                wc_add_order_item_meta( $item_id, '_mintroute_zone_id', $cart_item['_mintroute_zone_id'] );
            }
            
        }
    }

    function mintroute_payment_complete($order_id){
        $order = wc_get_order( $order_id );
        $items = $order->get_items();
        $check_if_order_topedup_before = get_post_meta($order_id,'_mintroute_topup',true);
        foreach($items as $key => $item){
            $mintroute_denomination = wc_get_order_item_meta($key, '_mintroute_denomination', true );
            $gamer_account_id       = wc_get_order_item_meta($key, '_mintroute_account_id', true );
            $type_card              = wc_get_order_item_meta($key, '_mintroute_type_card', true );
            if($type_card == 'mobile_legends'){
                $zone_id            = wc_get_order_item_meta($key, '_mintroute_zone_id', true );
            }
            
            if(!empty($mintroute_denomination) && !empty($gamer_account_id) ):
                if($check_if_order_topedup_before != 'completed'):
                    $data_mintroute = [
                        "account_id"      => $gamer_account_id,
                        "denomination_id" => $mintroute_denomination['_mintroute_denomination_ID'],
                        "type_card"       => $type_card
                    ];
                    if($type_card == 'mobile_legends'){
                        $data_mintroute['zone_id'] = $zone_id;
                    }
                    $mintroute_topup = new wp_mintroute_cards($order_id,$data_mintroute);
                    $mintroute_result = $mintroute_topup->result;
                    mintroute_topup_logs($mintroute_result['message'] ?? $mintroute_result['error'] ,$order_id,$mintroute_result['status']);
                    woocommerce_add_order_item_meta( $key, 'mintroute_top-up_status', $mintroute_result['message']);
                    woocommerce_add_order_item_meta( $key, 'mintroute_transaction_id',$mintroute_result['account_details']['transaction_id'] );
                    woocommerce_add_order_item_meta( $key, '_mintroute_type_card',self::$type_card[$type_card]);
                    update_post_meta($order_id,'_mintroute_topup','completed');
                endif;
            endif;
        }
    }

    function mintroute_wc_order_item_display_meta_key($display_key, $meta, $item){
        if( !is_admin() && $item->get_type() === 'line_item') {
            return $display_key;
        }

        if($meta->key === '_mintroute_account_id'){
             $display_key = __("Account ID", "mintroute" );
        }

        if($meta->key === 'mintroute_top-up_status'){
             $display_key = __("Top Up status ", "mintroute" );
        }

        if($meta->key === 'mintroute_transaction_id'){
             $display_key = __("Transaction Id ", "mintroute" );
        }

        if($meta->key === '_mintroute_denomination'){
             $display_key = __("Denomination Id ", "mintroute" );
        }

        if($meta->key == '_mintroute_type_card'){
            $display_key = __("Type Card ", "mintroute" );
        }

        if($meta->key == '_mintroute_zone_id'){
            $display_key = __("Zone ID : ", "mintroute" );
        }

        return $display_key;
        
    }
}

new wp_mintroute_order_complete();