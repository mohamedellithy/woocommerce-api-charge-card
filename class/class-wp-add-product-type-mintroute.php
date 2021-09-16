<?php
/**
 * @class
 * @for add type mintroute to woocommerce products
 * @var 1.0
 **/
class wp_add_product_type_mintroute{
    static $type_card = array(
        'free_fire'      => 'Free Fire',
        'razer_gold'     => 'Razer Gold',
        'jio_saavn'      => 'Jio Saavn',
        'net_dragon'     => 'Net Dragon',
        'mobile_legends' => 'Mobile Legends',
    );
    public function __construct(){
        add_filter( 'product_type_selector', array($this,'add_mintroute_card_product') );
        add_filter( 'woocommerce_product_data_tabs', array($this,'mintroute_card_tab') );
        add_action( 'woocommerce_product_data_panels',array($this,'wcpt_mintroute_card_options_product_tab_content') );
        add_action( 'woocommerce_process_product_meta',array($this,'save_mintroute_card_options_field') );
    }

    /**
     * Add to product type drop down.
     */
    public function add_mintroute_card_product( $types ){
        // Key should be exactly the same as in the class
        $types[ 'mintroute_card' ] = __( 'mintroute card' );
        return $types;
    }

    function mintroute_card_tab( $tabs) {
        // Key should be exactly the same as in the class product_type
        $tabs['mintroute_card'] = array(
            'label'	 => __( 'Mintroute Card', 'mintroute' ),
            'target' => 'mintroute_card_options',
            'class'  => ('show_if_mintroute_card'),
        );

        $tabs['inventory']['class'][]      = 'hide_if_mintroute_card';
        $tabs['shipping']['class'][]       = 'hide_if_mintroute_card';
        $tabs['linked_product']['class'][] = 'hide_if_mintroute_card';
        $tabs['attribute']['class'][]      = 'hide_if_mintroute_card';
        $tabs['advanced']['class'][]       = 'hide_if_mintroute_card';
        
        return $tabs;
    }

    function wcpt_mintroute_card_options_product_tab_content() {
        global $post_id;
        $product = wc_get_product( $post_id );
        $prev_denominations = get_post_meta( $post_id, '_mintroute_card_denomination', true);
        $prev_denominations = (empty($prev_denominations) ? array() : $prev_denominations);
        ?><div id='mintroute_card_options' class='panel woocommerce_options_panel'><?php
            ?><div class='options_group'>
                <div class="input_fields_wrap">
                    <button class="add_field_button" data-count="<?php echo count($prev_denominations) != 0 ? count($prev_denominations) : 0;  ?>" style="background-color: #009879;color: #ffffff;padding: 10px;border: 1px solid #009879;margin: 11px 11px 0px;border-radius: 4px 4px 4px 4px;box-shadow: 1px 1px 2px 2px #d5d0d0;"><?php _e('Add More Fields','mintroute'); ?></button>
                    <select class="form-control" name="avialablility">
                         <option value="avialable" <?php echo ( ($this->is_available($post_id) == true ) ? 'selected':'' ) ?> ><?php _e('avialable','mintroute') ?></option>
                         <option value="not_avialable" <?php echo ( ($this->is_available($post_id) == false ) ? 'selected':'' ) ?> ><?php _e('not avialable','mintroute') ?></option>
                    </select>
                    <select class="form-control" name="card_type">
                        <?php foreach(self::$type_card as $key => $card_name): ?> 
                              <option value="<?php echo $key ?? ''; ?>" <?php echo ( ($this->get_type_card($post_id) == $key ) ? 'selected':'' ) ?> ><?php _e($card_name,'mintroute') ?></option>
                        <?php endforeach; ?>
                    </select>
                    <table style="border-collapse: collapse;box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);margin: 25px 0;">
                        <thead> 
                            <tr style="background-color: #009879;color: #ffffff;">
                                <th style=" padding: 12px 15px;"><?php  _e('Denimonation','mintroute') ?> </th>
                                <th style=" padding: 12px 15px;"><?php  _e('Denimonation ID','mintroute') ?> </th>
                                <th style=" padding: 12px 15px;"><?php  _e('Denimonation Price','mintroute') ?> </th>
                                <th colspan="2" style=" padding: 12px 15px;"><?php  _e('action','mintroute') ?> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if( count($prev_denominations) != 0 ): ?>
                                <?php foreach($prev_denominations as $key => $denomination): ?>
                                    <tr id="standard-row" style="border-bottom: 1px solid #dddddd;">
                                        <td style=" padding: 12px 15px;"> 
                                            <div>
                                                <label for="name" style="width: 100%;margin: 0px;display: block;"> <?php _e('Denimonation','mintroute') ?> </label>
                                                <input type="text" name="_mintroute_denomination[]" style="width: 100%;" value="<?php echo $denomination['_mintroute_denomination'] ?? ''; ?>" />
                                            </div>
                                        </td>
                                        <td style=" padding: 12px 15px;"> 
                                            <div>
                                                <label for="name" style="width: 100%;margin: 0px;display: block;"> <?php _e('Denimonation ID','mintroute') ?> </label>
                                                <input type="text" style="width: 100%;" name="_mintroute_denomination_ID[]" value="<?php echo $denomination['_mintroute_denomination_ID'] ?? ''; ?>" class=""/>
                                            </div>
                                        </td>
                                        <td style=" padding: 12px 15px;"> 
                                            <div>
                                                <label for="name" style="width: 100%;margin: 0px;display: block;"> <?php _e('Denimonation Price','mintroute') ?> </label>
                                                <input type="number" style="width: 100%;" step=".01" name="_mintroute_denomination_price[]" value="<?php echo $denomination['_mintroute_denomination_price'] ?? ''; ?>" class=""/>
                                            </div>
                                        </td>
                                        <?php if($key != 0): ?>
                                            <td style=" padding: 12px 15px;">
                                                <div>
                                                    <a href="#" class="remove_field">Remove</a>
                                                </div>
                                            </td>
                                        <?php endif; ?>
                                        <td style=" padding: 12px 15px;">
                                            <div>
                                                <a href="#" class="clear_field">clear</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php   elseif(empty($prev_denominations)): ?>
                                    <tr id="standard-row" style="border-bottom: 1px solid #dddddd;">
                                        <td style=" padding: 12px 15px;"> 
                                            <div>
                                                <label for="name" style="width: 100%;margin: 0px;display: block;"> <?php _e('Denimonation','mintroute') ?> </label>
                                                <input type="text" name="_mintroute_denomination[]" style="width: 100%;" />
                                            </div>
                                        </td>
                                        <td style=" padding: 12px 15px;"> 
                                            <div>
                                                <label for="name" style="width: 100%;margin: 0px;display: block;"> <?php _e('Denimonation ID','mintroute') ?> </label>
                                                <input type="text" style="width: 100%;" name="_mintroute_denomination_ID[]"  class=""/>
                                            </div>
                                        </td>
                                        <td style=" padding: 12px 15px;"> 
                                            <div>
                                                <label for="name" style="width: 100%;margin: 0px;display: block;"> <?php _e('Denimonation Price','mintroute') ?> </label>
                                                <input type="number" style="width: 100%;" step="0.01" name="_mintroute_denomination_price[]"  class=""/>
                                            </div>
                                        </td>
                                        <td style=" padding: 12px 15px;">
                                            <div>
                                                <a href="#" class="clear_field"><?php _e('clear','mintroute'); ?></a>
                                            </div>
                                        </td>
                                    </tr>	
                            <?php   endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><?php
    }

    function save_mintroute_card_options_field( $post_id ) {
        array_filter($_POST['_mintroute_denomination']);
        array_filter($_POST['_mintroute_denomination_ID']);
        array_filter($_POST['_mintroute_denomination_price']);
        if( isset( $_POST['_mintroute_denomination'] ) ) :
            $this->{"product_is_".$_POST['avialablility']}($post_id);
            $denomination_data = array();
            for($number_field = 0; $number_field < count($_POST['_mintroute_denomination']); $number_field++ ){
                if( !empty( $_POST['_mintroute_denomination'][$number_field] ) &&
                !empty( $_POST['_mintroute_denomination_ID'][$number_field] ) &&
                !empty( $_POST['_mintroute_denomination_price'][$number_field] ) ):
                    
                    $denomination_data[$number_field] = array(
                        '_mintroute_denomination'       => sanitize_text_field( $_POST['_mintroute_denomination'][$number_field] ),
                        '_mintroute_denomination_ID'    => sanitize_text_field( $_POST['_mintroute_denomination_ID'][$number_field] ),
                        '_mintroute_denomination_price' => sanitize_text_field( $_POST['_mintroute_denomination_price'][$number_field] )
                    );
                    
                endif;
            }
            
            $show_price = wc_price(min($_POST['_mintroute_denomination_price'])).' - '.wc_price(max($_POST['_mintroute_denomination_price']));
            update_post_meta( $post_id, '_average_price',$show_price);
            update_post_meta( $post_id, '_price', !empty($_POST['_mintroute_denomination_price'][0]) ? $_POST['_mintroute_denomination_price'][0] : $_POST['_mintroute_denomination_price'][1]  );
            update_post_meta( $post_id, '_mintroute_card_denomination', array_filter($denomination_data));
            update_post_meta( $post_id, '_mintroute_type_card',$_POST['card_type']);
            wc_delete_product_transients( $post_id );
        endif;
    }

    public function product_is_avialable($product_id){
        update_post_meta($product_id, '_status_avialable', 'avialable');
    }

    public function product_is_not_avialable($product_id){
        update_post_meta($product_id, '_status_avialable', 'not-avialable');
    }

    public function is_available($product_id){
        $status = get_post_meta($product_id,'_status_avialable',true);
        if($status == 'avialable'){
            return true;
        }
        return false;
    }
    public function get_type_card($product_id){
        $type_card = get_post_meta($product_id,'_mintroute_type_card',true);
        return $type_card;
    }
}

/**
 * Register the custom product type after init
 */
function register_mintroute_card_product_type() {
	class WC_Product_Mintroute_Card extends WC_Product {
		public function __construct( $product ) {
			$this->product_type = 'mintroute_card';
			parent::__construct( $product );
		}
	}
}
add_action( 'plugins_loaded', 'register_mintroute_card_product_type' );
new wp_add_product_type_mintroute();