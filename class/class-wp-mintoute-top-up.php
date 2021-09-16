<?php 
/**
 * @class
 * @for mintroute top up card
 * @var 1.0
 **/
class wp_mintoute_top_up extends wp_mintoute_account_validation{
    public $order_id;
    function __construct($order_id,$data){
        $this->set_params_api($data);
        parent::__construct();
        self::$url_validate = 'api/account_topup';
        $this->order_id = $order_id;
        $status = $this->result['status'] ?'success':'failed';
        $this->{$status.'_validate_account'}();
    }

    public function success_validate_account(){
        $this->top_up();
    }

    public function failed_validate_account(){
        return $this->result;
    }

    public function top_up(){
        $this->top_up_data_request();
        $this->curl_request();
    }

    public function set_params_api($data){
        $this->account_id      = $data['account_id'] ?? '';
        $this->denomination_id = $data['denomination_id'] ?? '';
        if(!empty($data['test']) && ($data['test'] == true)):
            $this->log_test = $data['test'];
            $this->set_params_for_test();
        endif;
        
    }
}