<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->model('user_model');
    }
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function login()
	{   
        //$this->printRequestHeader();
        Logger::getRootLogger()->debug("User::login");
        Logger::getRootLogger()->debug("get_http_raw:".Utils::get_http_raw());
        Logger::getRootLogger()->debug("_SERVER:".Utils::var2str($_SERVER));
        $response = Utils::validate_request();
        if(Utils::validate_request() !== null){
            echo Response::getResponseJson($response);
            return;
        }
        
        $user_login_infor = $_POST['request_json'];
        Logger::getRootLogger()->debug("user_login_infor = ".$user_login_infor);

        $user_infor_array = json_decode($user_login_infor, true);
        Logger::getRootLogger()->debug("dump user_infor_array:".Utils::var2str($user_infor_array));
        
        $response = $this->user_model->login($user_infor_array["DATA"]);

        
        echo Response::getResponseJson($response);
	}

    public function register()
	{
        Logger::getRootLogger()->debug("User::register");

        $response = Utils::validate_request();
        if(Utils::validate_request() !== null){
            echo Response::getResponseJson($response);
            return;
        }

       
        $user_register_infor = $_POST['request_json'];
        Logger::getRootLogger()->debug("user_register_infor = ".$user_register_infor);

        $user_infor_array = json_decode($user_register_infor, true);
        Logger::getRootLogger()->debug("dump user_infor_array:".Utils::var2str($user_infor_array));

        $response = $this->user_model->create_user($user_infor_array["DATA"]);
        
        echo Response::getResponseJson($response);
	}


    public function logout(){
        Logger::getRootLogger()->debug("User::logout");
        unset($_SESSION['user_id']);
        $response = new Response();
        $response->status = Response::STATUS_OK;
        $response->message = "注销成功";
        echo Response::getResponseJson($response);
    }



    public function queryCurrentLoginUserInfor(){
        Logger::getRootLogger()->debug("User::queryCurrentLoginUserInfor");
        Logger::getRootLogger()->debug("_SESSION['user_id'] = ".$_SESSION['user_id']);

        $response = $this->user_model->get_user_infor($_SESSION['user_id']);

        $response = new Response();
        $response->status = Response::STATUS_OK;
        $response->message = "_SESSION['user_id'] = ".$_SESSION['user_id'];
        echo Response::getResponseJson($response);
    }

    public function update(){
        Logger::getRootLogger()->debug("User::update");
        
        $response = Utils::validate_request();
        if(Utils::validate_request() !== null){
            echo Response::getResponseJson($response);
            return;
        }
      
        $user_update_infor = $_POST['request_json'];
        Logger::getRootLogger()->debug("user_update_infor = ".$user_update_infor);

        $user_infor_array = json_decode($user_update_infor, true);
        Logger::getRootLogger()->debug("dump user_infor_array:".Utils::var2str($user_infor_array));

        $response = $this->user_model->update_user_infor($user_infor_array["DATA"]);
        echo Response::getResponseJson($response);
    }

    public function get_user_infor(){
        Logger::getRootLogger()->debug("User::get_user_infor");
        Logger::getRootLogger()->debug("User::Utils::isCurrentUserLogin() = ".(Utils::isCurrentUserLogin() ? 'true' : 'false'));

        if(!Utils::isCurrentUserLogin()){
            $response = new Response(); 
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0019";
            $response->message = "当前用户未登录，没有操作权限";
            echo Response::getResponseJson($response);
            return;
        }


        $response = Utils::validate_request();
        if(Utils::validate_request() !== null){
            echo Response::getResponseJson($response);
            return;
        }
        

        $request_json = $_POST['request_json'];
        Logger::getRootLogger()->debug("request_json = ".$request_json);
        
        $request_json = json_decode($request_json, true);

        if(isset($request_json["DATA"]["uid"]) && $request_json["DATA"]["uid"] !== "")
            $response = $this->user_model->get_user_infor($request_json["DATA"]["uid"]);
        else
            $response = $this->user_model->get_user_infor(Utils::getCurrentUserID());
        echo Response::getResponseJson($response);
    }


    public function user_focus(){
        Logger::getRootLogger()->debug("User::user_focus");
        
        if(!Utils::isCurrentUserLogin()){ 
            $response = new Response(); 
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0019";
            $response->message = "当前用户未登录，没有操作权限";
            echo Response::getResponseJson($response);
            return;
        }

        $response = Utils::validate_request();
        if(Utils::validate_request() !== null){
            echo Response::getResponseJson($response);
            return;
        }

        $request_json = $_POST['request_json'];
        Logger::getRootLogger()->debug("request_json = ".$request_json);
        
        $request_json = json_decode($request_json, true);
        Logger::getRootLogger()->debug("request_json = ".Utils::var2str($request_json));
        if(isset($request_json["DATA"]["uid"]) && $request_json["DATA"]["uid"] !== "")

            $response = $this->user_model->user_focus($request_json["DATA"]["uid"]);

        else{
            $response = new Response(); 
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0018";
            $response->message = "用户ID有误";
            echo Response::getResponseJson($response);
            return;
        }
        echo Response::getResponseJson($response);
    }
    
    //取消关注
    public function user_unfocus(){
        Logger::getRootLogger()->debug("User::user_unfocus");
        
        if(!Utils::isCurrentUserLogin()){ 
            $response = new Response(); 
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0019";
            $response->message = "当前用户未登录，没有操作权限";
            echo Response::getResponseJson($response);
            return;
        }

        $response = Utils::validate_request();
        if(Utils::validate_request() !== null){
            echo Response::getResponseJson($response);
            return;
        }

        $request_json = $_POST['request_json'];
        Logger::getRootLogger()->debug("request_json = ".$request_json);
        
        $request_json = json_decode($request_json, true);

        if(isset($request_json["DATA"]["uid"]) && $request_json["DATA"]["uid"] !== "")

            $response = $this->user_model->user_unfocus($request_json["DATA"]["uid"]);

        else{
            $response = new Response(); 
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0018";
            $response->message = "用户ID有误";
            echo Response::getResponseJson($response);
            return;
        }
        echo Response::getResponseJson($response);
    }

    //收藏广告
    public function collect(){
        Logger::getRootLogger()->debug("User::collect");
        
        if(!Utils::isCurrentUserLogin()){ 
            $response = new Response(); 
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0019";
            $response->message = "当前用户未登录，没有操作权限";
            echo Response::getResponseJson($response);
            return;
        }

        $response = Utils::validate_request();
        if(Utils::validate_request() !== null){
            echo Response::getResponseJson($response);
            return;
        }

        $request_json = $_POST['request_json'];
        Logger::getRootLogger()->debug("request_json = ".$request_json);
        
        $request_json = json_decode($request_json, true);

        if(isset($request_json["DATA"]["adv_id"]) && $request_json["DATA"]["adv_id"] !== "")

            $response = $this->user_model->collect($request_json["DATA"]["adv_id"]);

        else{
            $response = new Response(); 
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0024";
            $response->message = "用户ID有误";
        }
        echo Response::getResponseJson($response);

    }

    //收藏广告
    public function uncollect(){
        Logger::getRootLogger()->debug("User::uncollect");
        
        if(!Utils::isCurrentUserLogin()){ 
            $response = new Response(); 
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0019";
            $response->message = "当前用户未登录，没有操作权限";
            echo Response::getResponseJson($response);
            return;
        }

        $response = Utils::validate_request();
        if(Utils::validate_request() !== null){
            echo Response::getResponseJson($response);
            return;
        }

        $request_json = $_POST['request_json'];
        Logger::getRootLogger()->debug("request_json = ".$request_json);
        
        $request_json = json_decode($request_json, true);

        if(isset($request_json["DATA"]["adv_id"]) && $request_json["DATA"]["adv_id"] !== "")

            $response = $this->user_model->uncollect($request_json["DATA"]["adv_id"]);

        else{
            $response = new Response(); 
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0024";
            $response->message = "用户ID有误";
        }
        echo Response::getResponseJson($response);

    }

    public function printRequestHeader()
    {
    	$headers = array();
    	foreach ($_SERVER as $key => $value) {
    		if ('HTTP_' == substr($key, 0, 5)) {
    			$headers[str_replace('_', '-', substr($key, 5))] = $value;
    			echo ("_SERVER[".$key."]  =".$value."<br>");
    		}
    	}
    }

    public function test(){
    }

    public function my_collect(){


    }

    public function get_my_focus(){
        Logger::getRootLogger()->debug("User::get_my_focus");
        
        if(!Utils::isCurrentUserLogin()){ 
            $response = new Response(); 
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0019";
            $response->message = "当前用户未登录，没有操作权限";
            echo Response::getResponseJson($response);
            return;
        }

        $response = Utils::validate_request();
        if(Utils::validate_request() !== null){
            echo Response::getResponseJson($response);
            return;
        }

        $request_json = $_POST['request_json'];
        Logger::getRootLogger()->debug("request_json = ".$request_json);
        
        $request_json = json_decode($request_json, true);

        
        $response = $this->user_model->get_my_focus();

        echo Response::getResponseJson($response);

    }

    public function get_my_collect(){
        Logger::getRootLogger()->debug("User::get_my_collect");
        
        if(!Utils::isCurrentUserLogin()){ 
            $response = new Response(); 
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0019";
            $response->message = "当前用户未登录，没有操作权限";
            echo Response::getResponseJson($response);
            return;
        }

        $response = Utils::validate_request();
        if(Utils::validate_request() !== null){
            echo Response::getResponseJson($response);
            return;
        }

        $request_json = $_POST['request_json'];
        Logger::getRootLogger()->debug("request_json = ".$request_json);
        
        $request_json = json_decode($request_json, true);

        $lat = floatval($request_json["DATA"]["lat"]);
        $lng = floatval($request_json["DATA"]["lng"]);
    
        Logger::getRootLogger()->debug("lat = ".$lat);
        Logger::getRootLogger()->debug("lng = ".$lng);
        
        if(abs($lat) > 90 || abs($lng) > 180){
            $response = new Response();
            $response->status = Response::STATUS_ERROR;
            $response->message = "经纬度有误";
            echo Response::getResponseJson($response);
            return;
        }
        
        $response = $this->user_model->get_my_collect($lat, $lng);
        echo Response::getResponseJson($response);

    }
}
