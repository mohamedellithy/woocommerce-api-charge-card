<?php 
/**
 * @class
 * @for mintroute api set params and credentials
 * @var 1.0
 **/
class wp_mintroute_cards extends wp_mintoute_api{
    public function __construct($order_id,$data){
        $this->card   = $data['type_card'] ?? 'free_fire';
        $Card_Class   = "wp_mintroute_card_".$this->card;
        $card         = new $Card_Class($order_id,$data);
        $this->result = $card->result;
    }
}