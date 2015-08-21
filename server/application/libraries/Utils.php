<?php

class Utils{

    //判断是否为合法的json格式
    public static function isJson($string){
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function var2str($var){
        //echo '<pre>'; // This is for correct handling of newlines
        ob_start();
        var_dump($var);
        $a=ob_get_contents();
        ob_end_clean();
        //echo htmlspecialchars($a,ENT_QUOTES); // Escape every HTML special chars (especially > and < )
        //echo '</pre>';
	    return $a;
    }

    public static function setSessionKey($id, $username, $password){
        Logger::getRootLogger()->debug("Utils::getSessionKey");
        $key = md5($id.$username.$password);
        Logger::getRootLogger()->debug("username = ".$username);
        Logger::getRootLogger()->debug("password = ".$password);
        Logger::getRootLogger()->debug("key = ".$key);
        
        //设置会话key
        Logger::getRootLogger()->debug("key = ".$key);
        Logger::getRootLogger()->debug("uid = ".$id);
        //Yii::app()->session->add($key, $id);
        $_SESSION[$key] = $id;
        Logger::getRootLogger()->debug("session = ".Utils::var2str($_SESSION));
        
        return $key;
    }

    public static function getSeesionKey(){
    	if(isset($_POST['request_json'])){
    		Logger::getRootLogger()->debug("_POST['request_json'] = ".$_POST['request_json']);
    		$request = json_decode($_POST['request_json'], true);
    		Logger::getRootLogger()->debug("request = ".Utils::var2str($request));
    		$session_key = $request["SESSION_KEY"];
    		Logger::getRootLogger()->debug("session_key = ".$session_key);
    		return $session_key;
    	}
    	return null;
    }
    
    
    public static function printRequestHeader(){
    	Logger::getRootLogger()->debug("Utils::printRequestHeader ");
    	$headers = array();
    	foreach ($_SERVER as $key => $value) {
    		if ('HTTP_' == substr($key, 0, 5)) {
    			$headers[str_replace('_', '-', substr($key, 5))] = $value;
    			Logger::getRootLogger()->debug("_SERVER[".$key."]  =".$value);
    		}
    	}
    }

    
    /*
    *校验输入报文，如果POST请求不包含request_json或者非法的json格式，返回错误
    */
    public static function validate_request(){
        $response = new Response();
        if(!isset($_POST['request_json'])){
            $response->status = Response::STATUS_ERROR;
            $response->message = "无request_json项";
            $response->error_code = "0001";
            Logger::getRootLogger()->error("无request_json项");
            return $response;
        }
        
        //Logger::getRootLogger()->debug("request_json = ".$_POST['request_json']);

        if(!Utils::isJson($_POST['request_json'])){
            $response->status = Response::STATUS_ERROR;
            $response->message = "JSON文件格式错误";
            Logger::getRootLogger()->error("JSON文件格式错误:".$response->message);
            return $response;
        }
        
        return null;
    }


    public static function getCurrentUserID(){
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : -1;
    }
    
    public static function isCurrentUserLogin(){
        if(self::getCurrentUserID() === -1)
            return false;
        return true;
    }
    
    //计算两点之间的距离
    public static function distanceSimplify($lat1, $lng1, $lat2, $lng2) {
        $dx = $lng1 - $lng2; // 经度差值
        $dy = $lat1 - $lat2; // 纬度差值
        $b = ($lat1 + $lat2) / 2.0; // 平均纬度
        //$Lx = toRadians($dx) * 6367000.0*cos(toRadians($b)); // 东西距离
        $Lx = 2*M_PI*$dx/360 * 6367000.0*cos(2*M_PI*$b/360);
        $Ly = 6367000.0 * 2*M_PI*$dy/360; // 南北距离
        return sqrt($Lx * $Lx + $Ly * $Ly);  // 用平面的矩形对角距离公式计算总距离
    }
    
    //计算距离某个点若干距离的矩形的四个点
    public static function distance2points($L, $lat1, $lng1){
        $data = array();
        $Lx = $Ly = sqrt(2)*$L/2;
        $dy = $Ly/(6367000.0 * 2*M_PI/360);
        
        //右上角
        $lat2 = $lat1+$dy;
        $b = ($lat1 + $lat2) / 2.0;
        $dx = $Lx/(2*M_PI/360 * 6367000.0*cos(2*M_PI*$b/360));
        $lng2 = $lng1+$dx;
        
        $temp = array('lat' => $lat2, 'lng' => $lng2);
        array_push($data, $temp);
        
        //右下角
        $lat2 = $lat1-$dy;
        $b = ($lat1 + $lat2) / 2.0;
        $dx = $Lx/(2*M_PI/360 * 6367000.0*cos(2*M_PI*$b/360));
        $lng2 = $lng1+$dx;
        
        $temp = array('lat' => $lat2, 'lng' => $lng2);
        array_push($data, $temp);
        
        //左下角
        $lat2 = $lat1-$dy;
        $b = ($lat1 + $lat2) / 2.0;
        $dx = $Lx/(2*M_PI/360 * 6367000.0*cos(2*M_PI*$b/360));
        $lng2 = $lng1-$dx;
        
        $temp = array('lat' => $lat2, 'lng' => $lng2);
        array_push($data, $temp);
        //左上角
        $lat2 = $lat1+$dy;
        $b = ($lat1 + $lat2) / 2.0;
        $dx = $Lx/(2*M_PI/360 * 6367000.0*cos(2*M_PI*$b/360));
        $lng2 = $lng1-$dx;
        
        $temp = array('lat' => $lat2, 'lng' => $lng2);
        array_push($data, $temp);
        
        return $data;
    }

    /** 
    * 获取HTTP请求原文 
    * @return string 
    */
    public static function get_http_raw() { 
        $raw = ''; 
         
        // (1) 请求行 
        $raw .= $_SERVER['REQUEST_METHOD'].' '.$_SERVER['REQUEST_URI'].' '.$_SERVER['SERVER_PROTOCOL']."\r\n"; 
         
        // (2) 请求Headers 
        foreach($_SERVER as $key => $value) { 
            if(substr($key, 0, 5) === 'HTTP_') { 
                $key = substr($key, 5); 
                $key = str_replace('_', '-', $key); 
                 
                $raw .= $key.': '.$value."\r\n"; 
            } 
        } 
         
        // (3) 空行 
        $raw .= "\r\n"; 
         
        // (4) 请求Body 
        $raw .= file_get_contents('php://input'); 
         
        return $raw; 
    }

    /*public static function sort_adv_by_distance($unsorted){
        // 快速排序
        $num = count($unsorted);
        Logger::getRootLogger()->debug("num = ".$num);
        $l = $r = 0;
        // 从索引的第二个开始遍历数组
        for ($i = 1;$i < $num; $i++) {
            // 如果值小于索引1
            if ($unsorted[$i]['distance'] < $unsorted[0]['distance']) {
                // 装入左索引数组(小于索引1的数据)
                $left[] = $unsorted[$i];
                $l++;
            } else {
                // 否则装入右索引中(大于索引1的数据)
                $right[] = $unsorted[$i];
                $r++; //
            }        
        }
        // 如果左索引有值 则对左索引排序
        if($l > 1) {
            $left = self::sort_adv_by_distance($left);
        }
        // 排序后的数组
        $new_arr = $left;
        // 将当前数组第一个放到最后
        $new_arr[] = $unsorted[0];
        // 如果又索引有值 则对右索引排序
        if ($r > 1) {
            $right = self::sort_adv_by_distance($right);
        }
        // 根据右索引的长度再次增加数据
        for($i = 0;$i < $r; $i++) {
            $new_unsorted[] = $right[$i];
        }
        return $new_arr;
    }*/

    public static function sort_adv_by_distance($arr){
        $num = count($arr);
        // 遍历数组
        for ($i = 1;$i < $num; $i++) {
            // 获得当前值
            $iTemp = $arr[$i];
            // 获得当前值的前一个位置
            $iPos = $i - 1;
            // 如果当前值小于前一个值切未到数组开始位置
            while (($iPos >= 0) && ($iTemp['distance'] < $arr[$iPos]['distance'])) {
                // 把前一个的值往后放一位
                $arr[$iPos + 1] = $arr[$iPos];
                // 位置递减
                $iPos--;
            }
            $arr[$iPos+1] = $iTemp;
        }
        return $arr;
    }
}