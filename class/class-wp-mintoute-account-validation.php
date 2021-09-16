<?php 
/**
 * @class
 * @for mintroute account validation 
 * @var 1.0
 **/
class wp_mintoute_account_validation extends wp_mintoute_api{
    function __construct(){
        parent::__construct();
        self::$url_validate    = 'api/account_validation';
        $this->validation_data_request();
        $this->handle_date();
        $this->curl_request();
    } 

    public function signature(){
        $pay_load       = json_decode($this->request_data,true);
        $raw_pay_load   = http_build_query($pay_load);
        $str_to_sign    = self::Request_Method . $raw_pay_load . $this->timestamp_for_signing;
        $signature      = base64_encode(hash_hmac('sha256', $str_to_sign, self::$mint_secret_key,true));
        return $signature;
    }

    public function handle_date(){
        $this->date = date('Ymd\THis\Z',time());
        $this->timestamp_for_signing = substr($this->date, 0, 13);
        $this->datestamp = substr($this->date, 0, 8);
        
    }

    public function generate_header() {
        $headers['Accept']        = 'application/json';
        $headers['Content-Type']  = 'application/json';
        $headers['X-Mint-Date']   = $this->date;
        $headers['Authorization'] = sprintf('algorithm="%s",credential="%s",signature="%s"','hmac-sha256', self::$mint_access_key.'/'.$this->datestamp, $this->signature());
        $http_headers = array();
        foreach ($headers as $k => $v) {
            $http_headers[] = "$k: $v";
        }
        return  $http_headers;
    }

    public function curl_request(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_URL, $this->call_url());
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->generate_header());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request_data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response     = curl_exec($ch);
        $header_size  = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $response     = substr($response, $header_size);
        $this->result = json_decode($response,true);
        curl_close($ch);
    }

    public function call_url(){
        return self::$sandbox_api_url.self::$url_validate;
    }
}