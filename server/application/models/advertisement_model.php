<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Advertisement_model extends CI_Model {
    
    public function __construct(){
        $this->load->database();
    }

    
  
    public function create($adv_infor){
        Logger::getRootLogger()->debug("Advertisement_model::create");

        
        $response = $this->create_adv_validate($adv_infor);
        if($response !== null){
            return $response;
        }
        
        
        $uid = Utils::getCurrentUserID();
        
        $publish_time = date('Y-m-d H:i:s');
        $db = new DB();
        $db->connect();
        
        $sql = "insert into advertisement(uid, type,publish_position, publish_time,title,text_content,image, fresh_content) values($uid, '"
                                                                           .$adv_infor['type']."', '"
                                                                           .$adv_infor["publish_position"]."','"
                                                                           .$publish_time."','"
                                                                           .$adv_infor["title"]."','"
                                                                           .$adv_infor["text_content"]."','"
                                                                           .$adv_infor["image"]."','"
                                                                           .$adv_infor["fresh_content"]."')";
        Logger::getRootLogger()->debug("sql = ".$sql);                                                                   
        $result = $db->executeUpdateAndInsert($sql);

        if($result instanceof Response){
            return $result;
        }
        
        return null;
    }

    
    public function update($adv_infor){
        Logger::getRootLogger()->debug("Advertisement_model::update");

        
        $response = $this->update_adv_validate($adv_infor);
        if($response !== null){
            return $response;
        }
        
        $uid = Utils::getCurrentUserID();

        $last_update_time = date('Y-m-d H:i:s');
        $db = new DB();
        $db->connect();       
        $sql = "update advertisement set type = '".$adv_infor['type']."',
                                         publish_position = '".$adv_infor["publish_position"]."', 
                                         title ='".$adv_infor["title"]."',
                                         text_content ='".$adv_infor["text_content"]."',
                                         image ='".$adv_infor["image"]."', 
                                         fresh_content ='".$adv_infor["fresh_content"]."'
                                         where id = ".$adv_infor["id"]." and uid = ".$uid;
                                                                          
        Logger::getRootLogger()->debug("sql = ".$sql);                                                                   
        $response = $db->executeUpdateAndInsert($sql);

        if($response instanceof Response){
            return $response;
        }
        
        if($response == 0){
            $response = new Response();
            $response->status = Response::STATUS_ERROR;
            $response->message = "未找到对应广告，广告信息更新失败";
            return $response;
        }

        return null;
    }



    public function update_adv_validate($adv_infor){
        Logger::getRootLogger()->debug("Advertisement_model::update_adv_validate");
        $response = new Response();

        if(!isset($adv_infor["title"]) || !isset($adv_infor["text_content"])  || empty($adv_infor["title"]) || empty($adv_infor["text_content"])){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0012";
            $response->message = "广告标题和广告文本内容不得为空";
            return $response;
        }
        
        if(!isset($adv_infor["id"]) || empty($adv_infor["id"])){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0015";
            $response->message = "用户ID不得为空";
            return $response;
        }
        return null;

    }

    public function create_adv_validate($adv_infor){
        $response = new Response();
        
        if(!Utils::isCurrentUserLogin()){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0013";
            $response->message = "用户尚未登陆，没有发布广告的权限";
            return $response;
        }

        

        if(!isset($adv_infor["title"]) || !isset($adv_infor["text_content"])  || empty($adv_infor["title"]) || empty($adv_infor["text_content"])){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0012";
            $response->message = "广告标题和广告文本内容不得为空";
            return $response;
        }
        
        return null;
    }

    public function get_published($adv_infor){
        Logger::getRootLogger()->debug("Advertisement_model::get_published");

        $response = $this->get_published_validate($adv_infor);
        if($response !== null){
            return $response;
        }
        
        $uid = Utils::getCurrentUserID();

        $db = new DB();
        $db->connect();       
        $sql = "select * from advertisement where uid = ".$uid." order by last_update_time desc  limit ".$adv_infor["start"].", ".$adv_infor["count"];
                                                                                 
        Logger::getRootLogger()->debug("sql = ".$sql);                                                                   
        $result = $db->executeQuery($sql);
        
        $ret = $result->fetch_all(MYSQLI_ASSOC);

        Logger::getRootLogger()->debug("ret:".Utils::var2str($ret));
        
        $adv_infor = json_encode($ret);
        Logger::getRootLogger()->debug("adv_infor:".$adv_infor);
        
        $response = new Response();
        $response->status = Response::STATUS_OK;
        $response->message = "请求广告列表成功";
        $response->response_data= $adv_infor;
        return $response;
    }

    public function get_published_validate($adv_infor){
        Logger::getRootLogger()->debug("Advertisement_model::get_published_validate");
        Logger::getRootLogger()->debug("dump adv_infor_array:".Utils::var2str($adv_infor));
        $response = new Response();
        if(!Utils::isCurrentUserLogin()){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0013";
            $response->message = "用户尚未登陆";
            return $response;
        }
        
        if(!isset($adv_infor["start"]) || !isset($adv_infor["count"])  || $adv_infor["start"]=="" || $adv_infor["count"] == ""){
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0017";
            $response->message = "起始索引和请求广告数不得为空";
            return $response;
        }

        return null;
    }

    public function thumb_up_for_adv($adv_infor){
        Logger::getRootLogger()->debug("Advertisement_model::thumb_up_for_adv");
        $uid = Utils::getCurrentUserID();
        $adv_id = $adv_infor['adv_id'];
        $ts = date('Y-m-d H:i:s');
        $db = new DB();
        $db->connect(); 
        
        $sql = "insert into thumb_up_for_adv(uid, adv_id,ts) values('".$uid."','".$adv_id."','".$ts."')";
        Logger::getRootLogger()->debug("sql = ".$sql); 

        $result = $db->executeUpdateAndInsert($sql);
        
        $response = new Response();
        if($result instanceof Response){
            if(strpos($result->message, "Duplicate entry") && strpos($result->message, "for key 'PRIMARY'")){
                $response->status = Response::STATUS_ERROR;
                $response->error_code = "0021";
                $response->message = "无法重复点赞";
                return $response;

            }else
                return $result;
        }
            

        $sql = "update advertisement set zan_num = zan_num + 1 where id = ".$adv_id;
        Logger::getRootLogger()->debug("sql = ".$sql);
        $result = $db->executeUpdateAndInsert($sql);


        $response->status = Response::STATUS_OK;
        $response->message = "恭喜您点赞成功";
        return $response;
    }
    
    //获取广告详情
    public function get_advertisement_infor($adv_id){
        Logger::getRootLogger()->debug("User_model::get_advertisement_infor");

        $response = new Response(); 

        $db = new DB();
        $db->connect();
        $sql = "select id,uid,type,publish_position,publish_time,title,text_content,image,read_count,zan_num from advertisement where id = ".$adv_id;
        Logger::getRootLogger()->debug("sql = ".$sql); 
        
        $res = $db->executeQuery($sql);
        
        if($res instanceof Response)
            return $res;
        Logger::getRootLogger()->debug("res = ".Utils::var2str($res));
        
        $adv_infor = "{";
        if($row = mysqli_fetch_assoc($res)){
           $adv_infor = $adv_infor.'"id":"'.$row['id'].'",';
           $adv_infor = $adv_infor.'"uid":"'.$row['uid'].'",';
           $adv_infor = $adv_infor.'"type":"'.$row['type'].'",';
           $adv_infor = $adv_infor.'"publish_position":"'.$row['publish_position'].'",';
           $adv_infor = $adv_infor.'"publish_time":"'.$row['publish_time'].'",';
           $adv_infor = $adv_infor.'"title":"'.$row['title'].'",';
           $adv_infor = $adv_infor.'"text_content":"'.$row['text_content'].'",';
           $adv_infor = $adv_infor.'"image":"'.$row['image'].'",';
           $adv_infor = $adv_infor.'"read_count":"'.$row['read_count'].'",';
           $adv_infor = $adv_infor.'"zan_num":"'.$row['zan_num'].'"';
        }else{
            $response->status = Response::STATUS_ERROR;
            $response->error_code = "0026";
            $response->message = "未找到符合条件的广告";
            return $response;
        }
        $adv_infor = $adv_infor."}";

        $response->status = Response::STATUS_OK;
        $response->message = "请求广告信息成功";
        $response->response_data = $adv_infor;
        return $response;
    }

    public function delete_advertisement($adv_id){
        Logger::getRootLogger()->debug("User_model::delete_advertisement");
        $uid = Utils::getCurrentUserID();
        $response = new Response(); 

        $db = new DB();
        $db->connect();
        $sql = "delete from advertisement where uid = ". $uid." and id = ".$adv_id;
        Logger::getRootLogger()->debug("sql = ".$sql); 
        
        $res = $db->executeUpdateAndInsert($sql);
        
        if($res instanceof Response)
            return $res;
        
        if($res == 0){
            $response->status = Response::STATUS_ERROR;
            $response->message = "删除广告失败";
            return $response;
        }
        
        $response->status = Response::STATUS_OK;
        $response->message = "恭喜您删除广告成功";
        return $response;
    }
}