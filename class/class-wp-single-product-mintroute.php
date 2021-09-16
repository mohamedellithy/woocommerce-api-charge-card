<?php
/**
 * @class
 * @for mintroute single product show
 * @var 1.0
 **/
class wp_single_product_mintroute{
    protected $product_type = 'mintroute_card';
    public function __construct(){
      add_action( "woocommerce_{$this->product_type}_add_to_cart", array($this,'mintroute_card_add_to_cart') );
      add_filter( 'woocommerce_get_price_html', array($this,'mintroute_price_html'), 1, 2 );
      add_action( 'woocommerce_before_add_to_cart_button', array($this,'mintroute_show_select_donemination'),1);
      add_filter( 'woocommerce_is_sold_individually',array($this,'mintroute_remove_all_quantity_fields'), 10, 2 );
    }

    public function mintroute_price_html($price, $product){
        if($product->get_type() === 'mintroute_card'):
           $price = get_post_meta($product->get_id(),'_average_price',true);
           return $price;
        endif;
        return $price;
    }

    public function mintroute_show_select_donemination(){
        global $product;
        if($product->get_type() === 'mintroute_card'):
            $denominations_data =  get_post_meta( $product->get_id() , '_mintroute_card_denomination', true);
            $status    = get_post_meta($product->get_id(),'_status_avialable',true);
            $type_card = get_post_meta($product->get_id(),'_mintroute_type_card',true);
            $type_card = $type_card ?? 'free_fire';
           ?>
            <div class="container-domenations">
                <div class="row-domenations">
                   <p>
                      <?php _e('Choose Denomination','mintroute') ?>
                   </p>
                </div>
                 <div class="row-domenations">
                     <select class="choose-domentation" id="choice_domentation" product-id="<?php echo $product->get_id();  ?>"  required>
                        <option value=""> <?php _e('Domenations','mintroute') ?> </option>
                        <?php   if(!empty($denominations_data)): ?>
                            <?php foreach($denominations_data as $key => $denomination): ?>
                                    <option value="<?php echo $key ?? ''; ?>"> <?php echo $denomination['_mintroute_denomination'] ?? '' ?> </option>
                            <?php endforeach; ?>
                        <?php   endif; ?>
                     </select>
                </div>
            </div>
            <div class="container-domenations">
                <?php echo self::{"require_fields_for_".$type_card}($status); ?>
                <div class="row-prices" style="display: none">
                    <h2 class="show-price">0.0</h2>
                    <?php if($status == 'avialable')  {   ?> 
                            <p class="stock available-on-backorder">
                                <?php _e('Available for reservation (pre-order) ','mintroute') ?>
                            </p>
                    <?php } ?>
                </div>
                <?php if($status != 'avialable'){ ?> 
                    <p class="stock not-available-on-backorder">
                        <?php _e('Not Available for reservation (pre-order) ','mintroute') ?>
                    </p><br/>
                <?php } ?>
            </div>
            
           <?php 
        endif;
    }

    public function mintroute_card_add_to_cart(){
        wc_get_template( 'single-product/add-to-cart/simple.php' );

    }

    public function mintroute_remove_all_quantity_fields($return, $product){
        if($product->get_type() === 'mintroute_card'):
            return true;
        endif;
        return false;
    }

    public static function require_fields_for_free_fire($status){
        ?>
            <div class="row-domenations">
                <p>
                    <?php _e('Account ID','mintroute') ?>
                </p>
            </div>
            <div class="row-domenations">
                <input type="number" value="" class="account_id" name="account_id" <?php echo ( ($status != 'avialable') ? 'disabled':'') ?> required/>
                    <p>
                        <?php _e('Please write the Account of your Game account . The account must be global.','mintroute') ?>
                    </p>
            </div>
        <?php 
    } 

    public static function require_fields_for_razer_gold($status){
        ?>
            <div class="row-domenations">
                <p>
                    <?php _e('Account Email','mintroute') ?>
                </p>
            </div>
            <div class="row-domenations">
                <input type="email" value="" class="account_id" name="account_id" <?php echo ( ($status != 'avialable') ? 'disabled':'') ?> required/>
                    <p>
                        <?php _e('Please write the Account Email of your Game account . The account must be global.','mintroute') ?>
                    </p>
            </div>
        <?php 
    } 

    public static function require_fields_for_mobile_legends($status){
        ?>
            <div class="row-domenations">
                <p>
                    <?php _e('Account Email','mintroute') ?>
                </p>
            </div>
            <div class="row-domenations">
                <input type="number" value="" class="account_id" name="account_id" <?php echo ( ($status != 'avialable') ? 'disabled':'') ?> required/>
                    <p>
                        <?php _e('Please write the Account of your Game account . The account must be global.','mintroute') ?>
                    </p>
            </div>
            <div class="row-domenations">
                <p>
                    <?php _e('Zone ID','mintroute') ?>
                </p>
            </div>
            <div class="row-domenations">
                <input type="number" value="" class="zone_id" name="zone_id" <?php echo ( ($status != 'avialable') ? 'disabled':'') ?> required/>
                    <p>
                        <?php _e('Please write the Account Zone of your Game account . The account must be global.','mintroute') ?>
                    </p>
            </div>
        <?php 
    } 
 
}

new wp_single_product_mintroute();