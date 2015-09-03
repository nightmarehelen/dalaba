<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Authentication extends CI_Controller {
    private $cellphone_pattern = "/1[3458]{1}\d{9}$/";

    public function __construct(){
        parent::__construct();
    }
    
    public function get_short_message_verification_code(){
        Logger::getRootLogger()->debug("Authentication::get_short_message_verification_code");
        //Logger::getRootLogger()->debug(Utils::var2str(getallheaders()));
        Logger::getRootLogger()->debug(Utils::get_http_raw());
        $response = Utils::validate_request();
        if(Utils::validate_request() !== null){
            echo Response::getResponseJson($response);
            return;
        }      

        $adv_infor = $_POST['request_json'];
        Logger::getRootLogger()->debug("adv_infor = ".$adv_infor);

        $adv_infor_array = json_decode($adv_infor, true);
        Logger::getRootLogger()->debug("dump adv_infor_array:".Utils::var2str($adv_infor_array));
        
        if(!isset($adv_infor_array['DATA']['cellphone'])){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0003";
            $response->message = "手机号不得为空";
            return $response;
        }

        if(!preg_match($this->cellphone_pattern,$adv_infor_array['DATA']['cellphone'])){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0005";
            $response->message = "无效的手机号码";
            return $response;
        }
        $cellphone = $adv_infor_array['DATA']['cellphone'];
        $code = sprintf("%06d",rand(0, 999999));

        Utils::set_sms_code($cellphone, $code);
        $sms = new Sms();
        $ret = $sms ->send($cellphone, $code);
        
        if($ret != 0){
            $response = new Response();
            $response->status = Response::STATUS_ERROR;
            $response->message = "短信校验码获取失败";
            $response->error_code = "0027";
            echo Response::getResponseJson($response);
            return;
        }else{
            $response = new Response();
            $response->status = Response::STATUS_OK;
            $response->message = "短信校验码获取成功";
            echo Response::getResponseJson($response);
            return;
        }
    }
    
    public function validate_short_message_verification_code(){
        Logger::getRootLogger()->debug("Authentication::validate_short_message_verification_code");

        $adv_infor = $_POST['request_json'];
        Logger::getRootLogger()->debug("adv_infor = ".$adv_infor);

        $adv_infor_array = json_decode($adv_infor, true);
        Logger::getRootLogger()->debug("dump adv_infor_array:".Utils::var2str($adv_infor_array));
        
        $cellphone = $adv_infor_array['DATA']['cellphone'];
        $code = $adv_infor_array['DATA']['code'];

        if(Utils::validate_sms_code($cellphone, $code)){
            $response = new Response();
            $response->status = Response::STATUS_OK;
            $response->message = "短信校验码校验成功";
            echo Response::getResponseJson($response);
        }else{
            $response = new Response();
            $response->status = Response::STATUS_ERROR;
            $response->message = "短信校验码失败";
            $response->error_code = "0034";
            echo Response::getResponseJson($response);
        }
    }
}