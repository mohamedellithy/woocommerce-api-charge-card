<?php 
/**
 * @class
 * @for mintroute api set params and credentials
 * @var 1.0
 **/
class wp_mintoute_api{
    # POST Request 
    const Request_Method       = 'POST';

    # URL API
    static $sandbox_api_url    = 'https://sandbox.mintroute.com/top_up/';

    # type api url
    static  $url_validate      = '';

    # access token
    static  $mint_access_key   = '';

    # secret token
    static  $mint_secret_key   = '';

    # data sent
    protected $log_test        = '';

    # data sent
    protected $request_data    = '';

    # username
    protected $username        = '';

    # password
    protected $password        = '';

    # denomination_id
    protected $denomination_id ='';

    # Account ID
    protected $account_id      = '';

    # Date api
    protected $date            = '';

    # timestamp for signing
    protected $timestamp_for_signing = '';

    # datestamp
    protected $datestamp       = '';
    
    # card
    public $card               = 'free_fire';

    # result
    public $result             = '';

    public function __construct(){

        if( empty($this->log_test) && ($this->log_test != true) ):
           
            $api_data = get_option('WooMintroute_api_settings');
           
            array_walk($api_data,function($api_value,$api_attribute){
                if($api_attribute != 'api_url'):
                    $this->{$api_attribute}($api_value);
                endif;
            });

        endif;
    }
    
    # set api username 
    public function api_username($value){
         $this->username = $value;
    }
    
    # set api password
    public function api_password($value){
        $this->password = $value;
    }
    
    # set access api
    public function api_access_key($value){
        self::$mint_access_key = $value;
    }
    
    # set secret api
    public function api_secret_key($value){
        self::$mint_secret_key = $value;
    }
    
    # set api url
    public function api_mode($value){
        self::$sandbox_api_url = ($value == 'test' ? 'https://sandbox.mintroute.com/top_up/':'https://mintroute.com/top_up/');
    }
}