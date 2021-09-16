<?php 
/**
 * @class
 * @for mintroute api set params and credentials
 * @var 1.0
 **/
class wp_mintroute_card_mobile_legends extends wp_mintoute_top_up{
    public $zone_id;
    public function __construct($order_id,$data){
        $this->zone_id = $data['zone_id'];
        parent::__construct($order_id,$data);
    }

    public function validation_data_request(){
        $this->request_data = '{"username":"'.$this->username.'","password":"'.$this->password.'","data":{"denomination_id":'.$this->denomination_id.',"account_id":"'.$this->account_id.'","zone_id":"'.$this->zone_id.'" } }';
    }

    public function top_up_data_request(){
        $this->request_data = '{"username":"'.$this->username.'","password":"'.$this->password.'","data":{"denomination_id":'.$this->denomination_id.',"account_id":"'.$this->account_id.'","customer_id":"'.$this->result['account_details']['customer_id'].'","flow_id":"'.$this->result['account_details']['flow_id'].'","order_id":"'.$this->order_id.'"} }';
    }

    public function set_params_for_test(){
        $this->account_id = '27295';
        $this->denomination_id = 2684;
        $this->zone_id  = '7000';
        $this->username = 'cha7n';
        $this->password = 'dMRFrYsZ';
        self::$mint_access_key = "zcAhspmM";
        self::$mint_secret_key = "01ff0dbbb1ef3456b67a519756902472";
        self::$sandbox_api_url = "https://sandbox.mintroute.com/top_up/";
    }
}