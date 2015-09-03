<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Sms{
    /*--------------------------------
    功能:		HTTP接口 发送短信
    修改日期:	2011-03-04
    说明:		http://api.sms.cn/mt/?uid=用户账号&pwd=MD5位32密码&mobile=号码&mobileids=号码编号&content=内容
    状态:
        100 发送成功
        101 验证失败
        102 短信不足
        103 操作失败
        104 非法字符
        105 内容过多
        106 号码过多
        107 频率过快
        108 号码内容空
        109 账号冻结
        110 禁止频繁单条发送
        112 号码不正确
        120 系统升级
    --------------------------------*/
    //$http = 'http://api.sms.cn/mt/';		//短信接口
    private $http = 'http://api.sms.cn/mtutf8/';
    private $uid = 'weichushun';	//						//用户账号
    private $pwd = 'dawei100274!';	//						//密码
    //private $uid = 'lovemutex';	//						//用户账号
    //private $pwd = '123456';	//						//密码
    //$mobile	 = '13900001111,13900001112,13900001113';	//号码
    //$mobileids	 = '1390000111112345666688,139000011121112345666688,139000011131112345666688';	//号码唯一编号
    //$mobile	 = '13426370450';	//号码
    private $mobileids	 = '1342637045012345666688';	//号码唯一编号

    private $content = '欢迎使用大喇叭短信认证服务，您的验证码是XXXX，请您妥善保管！';		//内容
    //即时发送
 
    public function __construct(){
   
    }
    //定时发送
    /*
    $time = '2010-05-27 12:11';
    $res = sendSMS($uid,$pwd,$mobile,$content,$time);
    echo $res;
    */
    public function send($mobile, $content){
        $content = str_replace('XXXX', $content, $this->content); 
        $ret = $this->sendSMS($this->http,
                        $this->uid,
                        $this->pwd,
                        $mobile,
                        $content,
                        $this->mobileids,
                        $time='',
                        $mid='');
        return $ret;
    }

    private function sendSMS($http,$uid,$pwd,$mobile,$content,$mobileids,$time='',$mid='')
    {
        
        $data = array
            (
            'uid'=>$uid,					//用户账号
            'pwd'=>md5($pwd.$uid),			//MD5位32密码,密码和用户名拼接字符
            'mobile'=>$mobile,				//号码
            'content'=>$content,			//内容
            'mobileids'=>$mobileids,
            'time'=>$time,					//定时发送
            );
        $re= $this->postSMS($http,$data);			//POST方式提交
        //if( trim($re) == '100' )
        if( trim($re) == 'sms&stat=100&message=发送成功' )	
        {
            Logger::getRootLogger()->debug("Sms:发送成功");
            return 0;
        }
        else 
        {
            Logger::getRootLogger()->debug("发送失败! 状态：".$re);
            return 1;
        }
    }

    private function postSMS($url,$data='')
    {
        $port="";
        $post="";
            
        $row = parse_url($url);
        $host = $row['host'];
        $port = isset($row['port']) ? $row['port']:80;
        $file = $row['path'];
        while (list($k,$v) = each($data)) 
        {
            $post .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
        }
        $post = substr( $post , 0 , -1 );
        $len = strlen($post);
        $fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
        if (!$fp) {
            return "$errstr ($errno)\n";
        } else {
            $receive = '';
            $out = "POST $file HTTP/1.1\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Content-type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Content-Length: $len\r\n\r\n";
            $out .= $post;		
            fwrite($fp, $out);
            while (!feof($fp)) {
                $receive .= fgets($fp, 128);
            }
            fclose($fp);
            $receive = explode("\r\n\r\n",$receive);
            unset($receive[0]);
            return implode("",$receive);
        }
    }
}