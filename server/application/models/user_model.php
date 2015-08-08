<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    private $email_pattern = "/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i";
    private $cellphone_pattern = "/1[3458]{1}\d{9}$/";
    public function __construct(){
        
    }

    
    /*user information format
    *
            "id"  : "ID",
            "name"  : "Name",
            "password"  : "Password",
            "cellphone"  : "Cellphone",
            "email"  : "Email",
            "position"  : "Position",
            "type"  : "Type",
            "credit_values"  : "Credit Values",
            "register_time"  : "Register Time",
            "last_login_time"  : "Last Login Time"
    */
    public function create_user($user_infor){
        Logger::getRootLogger()->debug("User_model::create_user");

        $response = $this->create_user_validate($user_infor);
        if($response !== null){
            return $response;
        }
        
        
        $user_infor["register_time"] = date('Y-m-d H:i:s');
        $user_infor['credit_values'] = 0;
        
        $db = new DB();
        $db->connect();
        $sql = "insert into user(name, password,cellphone, email,credit_values,register_time) values('".$user_infor["name"]."', '"
                                                                           .md5($user_infor["password"])."', '"
                                                                           .$user_infor["cellphone"]."', '"
                                                                           .$user_infor["email"]."',0,'"
                                                                           .$user_infor["register_time"]."')";
        Logger::getRootLogger()->debug("sql = ".$sql);                                                                   
        $response = $db->executeUpdateAndInsert($sql);
        if(!is_numeric($response)){
            if(strpos($response->message, "mysqli->errno=1062")){
                $response->error_code = "0009";
                $response->message = $response->message."用户名已存在或者邮箱已被注册";
            }
            return $response;
        }
        
        $response = new Response();
        $response->status = Response::STATUS_OK;
        $response->message = "恭喜您注册成功";
        return $response;
    }

    //创建用户信息校验,如果没有错误，返回null
    public function create_user_validate($user_infor){
        $response = new Response();
        
        if(!isset($user_infor["name"]) || !isset($user_infor["password"]) ){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0006";
            $response->message = "用户名密码不得为空";
            return $response;
        }

        if(!isset($user_infor["email"])){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0002";
            $response->message = "email地址不得为空";
            return $response;
        }

        if(!isset($user_infor['cellphone'])){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0003";
            $response->message = "手机号不得为空";
            return $response;
        }
        
        if(!preg_match($this->email_pattern,$user_infor['email'])){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0004";
            $response->message = "邮箱格式不合法";
            return $response;
        }

        if(!preg_match($this->cellphone_pattern,$user_infor['cellphone'])){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0005";
            $response->message = "无效的手机号码";
            return $response;
        }
        
        return null;
    }
    
    public function login($user_infor){
        Logger::getRootLogger()->debug("User_model::login");

        $response = $this->login_validate($user_infor);
        if($response !== null){
            return $response;
        }

        $db = new DB();
        $db->connect();
        $sql = "select * from user where name ='".$user_infor["name"]."'";
        Logger::getRootLogger()->debug("sql = ".$sql); 
        
        $res = $db->executeQuery($sql);
        if ($res->num_rows == 0){    
            $res->close();
            $response = new Response();
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0010";
            $response->message = "用户名不存在";
            return $response;
        }
        Logger::getRootLogger()->debug("res = ".Utils::var2str($res));
        

        $sql = "select id from user where name ='".$user_infor["name"]."' and password = '".md5($user_infor['password'])."'";
        Logger::getRootLogger()->debug("sql = ".$sql); 
        
        $res = $db->executeQuery($sql);
        if ($res->num_rows == 0){    
            $res->close();
            $response = new Response();
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0011";
            $response->message = "密码错误";
            return $response;
        }else{
            $row = $res->fetch_assoc();
            $_SESSION['user_id'] = $row['id'];
            Logger::getRootLogger()->debug("set session[user_id] = ".$_SESSION['user_id']);
            
            $last_login_time = date('Y-m-d H:i:s');
            $sql = "update user set last_login_time = '".$last_login_time."' where id = ".$row['id'];
            $db->executeUpdateAndInsert($sql);
        }
        
        
        Logger::getRootLogger()->debug("res = ".Utils::var2str($res));
        

        $response = new Response();
        $response->status = Response::STATUS_OK;
        $response->message = "恭喜您登陆成功";
        return $response;

    }
    
     //创建用户信息校验,如果没有错误，返回null
    public function login_validate($user_infor){
        $response = new Response();
        
        if(!isset($user_infor["name"]) || !isset($user_infor["password"]) ){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0006";
            $response->message = "用户名密码不得为空";
            return $response;
        }

        return null;
    }

    public function getUserID($user_infor){
        $db = new DB();
        $db->connect();
        $sql = "select * from user where name ='".$user_infor["name"]."' and password = '".md5($user_infor['password'])."'";
        Logger::getRootLogger()->debug("sql = ".$sql); 
        
        $res = $db->executeQuery($sql);
        if ($res->num_rows == 0){    
            $res->close();
            $response = new Response();
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0011";
            $response->message = "密码错误";
            return $response;
        }
        Logger::getRootLogger()->debug("res = ".Utils::var2str($res));

    }

    public function update_user_infor($user_infor){
        Logger::getRootLogger()->debug("User_model::update_user_infor");
        $response = $this->update_user_validate($user_infor);
        if($response !== null){
            return $response;
        }
        
        $db = new DB();
        $db->connect();
        
        $response = null;
        if(isset($user_infor['email'])){
            $sql = "update user set email = '".$user_infor['email']."' where id = ".Utils::getCurrentUserID();
            Logger::getRootLogger()->debug($sql);
            $response = $db->executeUpdateAndInsert($sql);
            if($response instanceof Response)
                return $response;
        }
        
        if(isset($user_infor['cellphone'])){
            $sql = "update user set cellphone = '".$user_infor['cellphone']."' where id = ".Utils::getCurrentUserID();
            Logger::getRootLogger()->debug($sql);
            $response = $db->executeUpdateAndInsert($sql);
            if($response instanceof Response)
                return $response;
        }
        
        if($response == 0){
            $response = new Response();
            $response->status = Response::STATUS_ERROR;
            $response->message = "未找到对应用户，用户信息更新失败";
        }


        $response = new Response();
        $response->status = Response::STATUS_OK;
        $response->message = "恭喜您信息更新成功";
        return $response;
    }

    public function update_user_validate($user_infor){
        Logger::getRootLogger()->debug("User_model::update_user_validate");
        $response = new Response();
        if(isset($user_infor['email']) && !preg_match($this->email_pattern,$user_infor['email'])){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0004";
            $response->message = "邮箱格式不合法";
            return $response;
        }

        if(isset($user_infor['cellphone']) && !preg_match($this->cellphone_pattern,$user_infor['cellphone'])){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0005";
            $response->message = "无效的手机号码";
            return $response;
        }
         return null;
    }

    public function get_user_infor($uid){
        Logger::getRootLogger()->debug("User_model::get_user_infor");

        $response = new Response(); 

        if($uid === ""){           
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0018";
            $response->message = "请求用户信息时用户ID有误";
        }
        

        $db = new DB();
        $db->connect();
        $sql = "select name,cellphone,email,type,register_time,last_login_time  from user where id = ".$uid;
        Logger::getRootLogger()->debug("sql = ".$sql); 
        
        $res = $db->executeQuery($sql);
        
        if($res instanceof Response)
            return $res;
        Logger::getRootLogger()->debug("res = ".Utils::var2str($res));
        
        $user_infor = "{";
        if($row = mysqli_fetch_assoc($res)){
           $user_infor = $user_infor.'"name":"'.$row['name'].'",';
           $user_infor = $user_infor.'"cellphone":"'.$row['cellphone'].'",';
           $user_infor = $user_infor.'"email":"'.$row['email'].'",';
           $user_infor = $user_infor.'"type":"'.$row['type'].'",';
           $user_infor = $user_infor.'"register_time":"'.$row['register_time'].'",';
           $user_infor = $user_infor.'"last_login_time":"'.$row['last_login_time'].'"';
        }else{
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0020";
            $response->message = "未找到符合条件的用户";
            return $response;
        }
        $user_infor = $user_infor."}";

        $response->status = Response::STATUS_OK;
        $response->message = "请求用户信息成功";
        $response->response_data = $user_infor;
        return $response;
    }


    public function user_focus($uid){
        Logger::getRootLogger()->debug("User_model::user_focus");
        $response = new Response();
        
        $db = new DB();
        $db->connect();

        $uid_a = Utils::getCurrentUserID();
        $uid_b = $uid;
        $ts = date('Y-m-d H:i:s');
        $sql = "insert into user_focus(uid_a, uid_b,ts) values(".$uid_a.",".$uid_b.",'".$ts."')";
        Logger::getRootLogger()->debug("sql = ".$sql); 

        $result = $db->executeUpdateAndInsert($sql);

        $response = new Response();
        if($result instanceof Response){
            if(strpos($result->message, "Duplicate entry") && strpos($result->message, "for key 'PRIMARY'")){
                $response->status = Response::STATUS_ERROR;
                $response->error_code = "0022";
                $response->message = "无法重复关注";
                return $response;

            }else
                return $result;
        }

        $sql = "update user set fans_num = fans_num + 1 where id = ".$uid_b;
        Logger::getRootLogger()->debug("sql = ".$sql);
        $result = $db->executeUpdateAndInsert($sql);


        $response->status = Response::STATUS_OK;
        $response->message = "恭喜您关注成功";
        return $response;
    }
    
    public function user_unfocus($uid){
        Logger::getRootLogger()->debug("User_model::user_unfocus");
        $response = new Response();
        
        $db = new DB();
        $db->connect();

        $uid_a = Utils::getCurrentUserID();
        $uid_b = $uid;
        $ts = date('Y-m-d H:i:s');
        $sql = "delete from  user_focus where uid_a = ".$uid_a." and uid_b = ".$uid_b;
        Logger::getRootLogger()->debug("sql = ".$sql); 

        $result = $db->executeUpdateAndInsert($sql);

        $response = new Response();
        if($result instanceof Response)
            return $result;

        
        if($result == 0){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0023";
            $response->message = "取消关注失败";
        }

        $sql = "update user set fans_num = fans_num - 1 where id = ".$uid_b;
        Logger::getRootLogger()->debug("sql = ".$sql);
        $result = $db->executeUpdateAndInsert($sql);


        $response->status = Response::STATUS_OK;
        $response->message = "取消关注成功";
        return $response;
    }

    public function collect($adv_id){
        Logger::getRootLogger()->debug("User_model::collect");
        $response = new Response();
        
        $db = new DB();
        $db->connect();

        $uid = Utils::getCurrentUserID();
        $ts = date('Y-m-d H:i:s');
        $sql = "insert into user_collect(uid, adv_id,ts) values('".$uid."','".$adv_id."','".$ts."')";
        Logger::getRootLogger()->debug("sql = ".$sql); 
        
        $result = $db->executeUpdateAndInsert($sql);
        
        $response = new Response();
        if($result instanceof Response){
            if(strpos($result->message, "Duplicate entry") && strpos($result->message, "for key 'PRIMARY'")){
                $response->status = Response::STATUS_ERROR;
                $response->error_code = "0021";
                $response->message = "无法重复收藏";
                return $response;

            }else
                return $result;
        }

        $response = new Response();
        $response->status = Response::STATUS_OK;
        $response->message = "收藏成功";
        return $response;
    }

    public function uncollect($adv_id){
        Logger::getRootLogger()->debug("User_model::uncollect");
        $response = new Response();
        
        $db = new DB();
        $db->connect();

        $uid = Utils::getCurrentUserID();
        $ts = date('Y-m-d H:i:s');
        $sql = "delete from  user_collect where uid = ".$uid." and adv_id = ".$adv_id;
        Logger::getRootLogger()->debug("sql = ".$sql); 
        
        $result = $db->executeUpdateAndInsert($sql);
        if($result instanceof Response)
            return $result;

        $response = new Response();
        if($result == 0){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0025";
            $response->message = "撤销收藏失败";
            return $response;
        }

        $response = new Response();
        $response->status = Response::STATUS_OK;
        $response->message = "取消收藏成功";
        return $response;
    }
}