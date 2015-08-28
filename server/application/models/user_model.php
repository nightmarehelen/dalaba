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
        $sql = "select name,cellphone,email,type,register_time,last_login_time,fans_num  from user where id = ".$uid;
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
           $user_infor = $user_infor.'"last_login_time":"'.$row['last_login_time'].'",';
           $user_infor = $user_infor.'"fans_num":"'.$row['fans_num'].'"';
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
        
        $uid_a = Utils::getCurrentUserID();
        if($uid == $uid_a){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0028";
            $response->message = "用户无法关注自己";
            return $response;
        }
        
        $db = new DB();
        $db->connect();

        
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
    
        $sql = "select uid from advertisement where id = $adv_id";
        $res = $db->executeQuery($sql);
        while ($row = mysqli_fetch_assoc($res)) {
            $adv_uid =  $row['uid'];
        }

        if($adv_uid == $uid){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0029";
            $response->message = "用户无法收藏自己的广告";
            return $response;
        }
         
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

    public function get_my_focus(){
        Logger::getRootLogger()->debug("User_model::uncollect");
        $response = new Response();
        
        $db = new DB();
        $db->connect();

        $uid = Utils::getCurrentUserID();

        $sql = "select user.id id, user.name name from user_focus inner join user on user.id = user_focus.uid_b where uid_a = $uid";
        Logger::getRootLogger()->debug("sql = ".$sql); 
        $res = $db->executeQuery($sql);
        $user_infor = "[";
        while ($row = mysqli_fetch_assoc($res)) {
            $user_infor = $user_infor."{";
            $user_infor = $user_infor.'"id" :"'.$row["id"].'",';
            $user_infor = $user_infor.'"name" :"'.$row["name"].'",';

            $sql_focused_cnt = "select count(*) focused_cnt from user_focus where uid_b = {$row['id']}";
            Logger::getRootLogger()->debug("sql = ".$sql_focused_cnt); 
            $res_focused_cn = $db->executeQuery($sql_focused_cnt);
            while ($row_focus_cnt = mysqli_fetch_assoc($res_focused_cn)){
                $user_infor = $user_infor.'"focused_cnt" :"'.$row_focus_cnt["focused_cnt"].'",';
            }

            $sql_published_cnt = "select count(*) published_cnt from advertisement where uid = {$row['id']}";
            Logger::getRootLogger()->debug("sql = ".$sql_published_cnt); 
            $res_published_cnt = $db->executeQuery($sql_published_cnt);
            while ($row_published_cnt = mysqli_fetch_assoc($res_published_cnt)){
                $user_infor = $user_infor.'"published_cnt" :"'.$row_published_cnt["published_cnt"].'",';
            }

            $sql_thumbed_up_cnt = "select count(*) thumbed_up_cnt from thumb_up_for_adv inner join advertisement on  thumb_up_for_adv.adv_id =              
                advertisement.id inner join user on user.id = advertisement.uid 
                where user.id = {$row['id']}";
            Logger::getRootLogger()->debug("sql = ".$sql_published_cnt); 
            $res_thumbed_up_cnt = $db->executeQuery($sql_thumbed_up_cnt);
            while ($row_thumbed_up_cnt = mysqli_fetch_assoc($res_thumbed_up_cnt)){
                $user_infor = $user_infor.'"thumbed_up_cnt" :"'.$row_thumbed_up_cnt["thumbed_up_cnt"].'",';
            }

            
            $sql_collected_cnt = "select count(*) collected_cnt from user_collect 
                inner join advertisement on  user_collect.adv_id = advertisement.id 
                inner join user on user.id = advertisement.uid 
                where user.id = {$row['id']}";
            Logger::getRootLogger()->debug("sql = ".$sql_collected_cnt); 
            $res_collected_cnt = $db->executeQuery($sql_collected_cnt);
            while ($row_collected_cnt = mysqli_fetch_assoc($res_collected_cnt)){
                $user_infor = $user_infor.'"collected_cnt" :"'.$row_collected_cnt["collected_cnt"].'"';
            }

            $user_infor = $user_infor."},";
        }
        
        if($res->num_rows > 0)
            $user_infor = substr($user_infor,0, -1);
        $user_infor = $user_infor."]";

        $response->status = Response::STATUS_OK;
        $response->message = "获取关注用户信息成功";
        $response->response_data = $user_infor;
        return $response;
    }

    public function get_my_collect($lat, $lng){
        Logger::getRootLogger()->debug("user_model::get_my_collect");
        $response = new Response(); 

        $db = new DB();
        $db->connect();

        $cuid = Utils::getCurrentUserID();
        $sql = "select advertisement.id id,advertisement.uid,advertisement.type type,
            publish_time,title,text_content,image,read_count,zan_num,address,user.name user_name,lat,lng,
            user_focus.uid_b focused,thumb_up_for_adv.adv_id zaned 
            from advertisement 
            inner join user_collect user_collect on user_collect.adv_id = advertisement.id 
            inner join user on user.id = user_collect.uid   
            left join user_focus on user_focus.uid_b = advertisement.uid and user_focus.uid_a = $cuid 
            left join thumb_up_for_adv on thumb_up_for_adv.adv_id = advertisement.id and thumb_up_for_adv.uid = $cuid
            where user.id = $cuid";
               
        Logger::getRootLogger()->debug("sql = ".$sql); 
        
        $res = $db->executeQuery($sql);
        
        if($res instanceof Response)
            return $res;
        Logger::getRootLogger()->debug("res = ".Utils::var2str($res));
        
        $unsorted = array();
        while ($row = mysqli_fetch_assoc($res)) {
            $distance = Utils::distanceSimplify($lat, $lng, $row['lat'], $row['lng']);
            $row['distance'] = $distance;
            array_push($unsorted, $row);
        }
        /* free result set */
        mysqli_free_result($res);
        
        $sorted = Utils::sort_adv_by_distance($unsorted);

        $adv_infor = "[";
        foreach($sorted as $item) {
            $adv_infor = $adv_infor."{";
            $adv_infor = $adv_infor.'"id":"'.$item['id'].'",';
            $adv_infor = $adv_infor.'"uid":"'.$item['uid'].'",';
            $adv_infor = $adv_infor.'"user_name":"'.$item['user_name'].'",';
            $adv_infor = $adv_infor.'"type":"'.$item['type'].'",';
            $adv_infor = $adv_infor.'"publish_time":"'.$item['publish_time'].'",';
            $adv_infor = $adv_infor.'"title":"'.$item['title'].'",';
            $adv_infor = $adv_infor.'"text_content":"'.$item['text_content'].'",';
            $adv_infor = $adv_infor.'"image":"'.$item['image'].'",';
            $adv_infor = $adv_infor.'"read_count":"'.$item['read_count'].'",';
            $adv_infor = $adv_infor.'"zan_num":"'.$item['zan_num'].'",';
            $adv_infor = $adv_infor.'"addr":"'.$item['address'].'",';
            $adv_infor = $adv_infor.'"distance":"'.$item['distance'] .'",';
            $adv_infor = $adv_infor.'"focused":"'.($item['focused'] == "" ? "false" : "true") .'",';
            $adv_infor = $adv_infor.'"zaned":"'.($item['zaned'] == "" ? "false" : "true") .'"';
            $adv_infor = $adv_infor."},";
        }

        $adv_infor = substr($adv_infor, 0, -1);
        
        $adv_infor = $adv_infor."]";

        $response->status = Response::STATUS_OK;
        $response->message = "获取我的收藏成功";
        $response->response_data = $adv_infor;
        return $response;
        
    }
}