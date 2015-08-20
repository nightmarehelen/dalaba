package xiaolaba;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;

import org.apache.commons.httpclient.Header;
import org.apache.commons.httpclient.HttpClient;
import org.apache.commons.httpclient.HttpException;
import org.apache.commons.httpclient.methods.PostMethod;
import org.apache.commons.httpclient.methods.multipart.MultipartRequestEntity;
import org.apache.commons.httpclient.methods.multipart.Part;
import org.apache.commons.httpclient.methods.multipart.FilePart;
import org.apache.commons.httpclient.methods.multipart.StringPart;
import org.apache.commons.httpclient.params.HttpMethodParams;

public class DalabaClient
{
	public static HttpClient client = new HttpClient(); 
	public static String phpSessionID = "";
	public static void main(String[] args) throws IOException {  
		//test();
		//register();
		login(); 
		//queryCurrentLoginUserInfor();
		//updateUserInfor();
		createAdvertisement();
		//updateAdvertisement();
		//get_published();
		//get_advertisement_type();
		//get_user_infor();
		//thumb_up_for_adv();
		//user_focus();
		//user_unfocus();
		//collect();
		//uncollect();
		//get_advertisement_infor();
		//delete_advertisement();
	 }  
	
	private static void register(){
		String json = getStringFromFile("data/user_register.json");
		StringPart simcard = new StringPart( "request_json" , json); 
		StringPart[] pairs = new StringPart[]{simcard};
		call("/index.php/user/register", pairs, null);
	}
	
	private static void login(){
		String json = getStringFromFile("data/login.json");
		StringPart simcard = new StringPart( "request_json" , json); 
		StringPart[] pairs = new StringPart[]{simcard};
		//files[1] = new File("data/ti2.png");
		call("/index.php/user/login", pairs, null);
	}
	
	private static void queryCurrentLoginUserInfor(){
		String json = getStringFromFile("data/query_user_infor.json");
		StringPart simcard = new StringPart( "request_json" , json);
		StringPart[] pairs = new StringPart[]{simcard};
		call("/index.php/user/queryCurrentLoginUserInfor", pairs, null);
	}
	
	
	private static void updateUserInfor(){
		String json = getStringFromFile("data/user_infor_update.json");
		StringPart simcard = new StringPart( "request_json" , json);
		StringPart[] pairs = new StringPart[]{simcard};
		call("/index.php/user/updateUserInfor", pairs, null);
	}
	
	private static void createAdvertisement() throws FileNotFoundException{
		String json = getStringFromFile("data/createAdvertisement.json");
		StringPart simcard = new StringPart( "request_json" , json);
		StringPart[] pairs = new StringPart[]{simcard};
		
		
		File[] files = new File[1];
		files[0] = new File("data/kobe.jpg");
		//FilePart part = new FilePart("adv_img1", files[0]);
		FilePart part = new FilePart("adv_12313img1", files[0]);
		
		FilePart[] parts = new FilePart[1];
		parts[0] = part;
		call("/index.php/advertisement/create", pairs, parts);
		//call("/index.php/advertisement/upload", pairs, files);
	}
	
	private static void updateAdvertisement() throws FileNotFoundException{
		String json = getStringFromFile("data/updateAdvertisement.json");
		StringPart simcard = new StringPart( "request_json" , json);
		StringPart[] pairs = new StringPart[]{simcard};
		
		
		File[] files = new File[1];
		files[0] = new File("data/kobe.jpg");
		FilePart part = new FilePart("adv_img1", files[0]);
		
		FilePart[] parts = new FilePart[1];
		parts[0] = part;
		call("/index.php/advertisement/update", pairs, parts);
		//call("/index.php/advertisement/upload", pairs, files);
	}
	
	
	private static void get_published() throws FileNotFoundException{
		String json = getStringFromFile("data/get_published.json");
		StringPart simcard = new StringPart( "request_json" , json);
		StringPart[] pairs = new StringPart[]{simcard};
		
		call("/index.php/advertisement/get_published", pairs, null);
		//call("/index.php/advertisement/upload", pairs, files);
	}
	
	
	private static void get_user_infor() throws FileNotFoundException{
		String json = getStringFromFile("data/get_user_infor.json");
		StringPart simcard = new StringPart( "request_json" , json);
		StringPart[] pairs = new StringPart[]{simcard};
		call("/index.php/user/get_user_infor", pairs, null);
		//call("/index.php/advertisement/upload", pairs, files);
	}
	
	
	private static void get_advertisement_type() throws FileNotFoundException{
		
		StringPart simcard = new StringPart( "request_json" , "");
		StringPart[] pairs = new StringPart[]{simcard};
		call("/index.php/advertisement/get_advertisement_type", pairs, null);
	}
	
	private static void thumb_up_for_adv() throws FileNotFoundException{
		String json = getStringFromFile("data/thumb_up_for_adv.json");
		StringPart simcard = new StringPart( "request_json" , json);
		StringPart[] pairs = new StringPart[]{simcard};
		call("/index.php/advertisement/thumb_up_for_adv", pairs, null);
	}
	
	
	private static void user_focus() throws FileNotFoundException{
		String json = getStringFromFile("data/user_focus.json");
		StringPart simcard = new StringPart( "request_json" , json);
		StringPart[] pairs = new StringPart[]{simcard};
		call("/index.php/user/user_focus", pairs, null);
	}
		
	private static void user_unfocus() throws FileNotFoundException{
		String json = getStringFromFile("data/user_focus.json");
		StringPart simcard = new StringPart( "request_json" , json);
		StringPart[] pairs = new StringPart[]{simcard};
		call("/index.php/user/user_unfocus", pairs, null);
	}
	
	private static void collect() throws FileNotFoundException{
		String json = getStringFromFile("data/collect.json");
		StringPart simcard = new StringPart( "request_json" , json);
		StringPart[] pairs = new StringPart[]{simcard};
		call("/index.php/user/collect", pairs, null);
	}
	
	private static void uncollect() throws FileNotFoundException{
		String json = getStringFromFile("data/collect.json");
		StringPart simcard = new StringPart( "request_json" , json);
		StringPart[] pairs = new StringPart[]{simcard};
		call("/index.php/user/uncollect", pairs, null);
	}
	
	private static void get_advertisement_infor() throws FileNotFoundException{
		String json = getStringFromFile("data/get_advertisement_infor.json");
		StringPart simcard = new StringPart( "request_json" , json);
		StringPart[] pairs = new StringPart[]{simcard};
		call("/index.php/advertisement/get_advertisement_infor", pairs, null);
	}
	
	private static void delete_advertisement() throws FileNotFoundException{
		String json = getStringFromFile("data/delete_advertisement.json");
		StringPart simcard = new StringPart( "request_json" , json);
		StringPart[] pairs = new StringPart[]{simcard};
		call("/index.php/advertisement/delete_advertisement", pairs, null);
	}
	
	//httpclient 调用，是否包含
	private static void call(String url, StringPart[] pairs, FilePart[] files){
		System.out.println("call url"+url+"**********************************************");
		try{
			client.getHostConfiguration().setHost( "127.0.0.1" , 80, "http" );
			PostMethod post = new PostMethod(url);
			
			if(!phpSessionID.equals(""))
				post.addRequestHeader("PHPSESSID", phpSessionID);
			
			post.getParams().setBooleanParameter(HttpMethodParams.USE_EXPECT_CONTINUE,false);
			
			int pairs_length = pairs == null ? 0 : pairs.length;
			int files_length = files == null ? 0 : files.length;
			
			Part[] parts = new Part[pairs_length + files_length];
				
			if(files != null){
				for(int i=0; i<files_length; i++)
					parts[i] = files[i];
			}
				
			if(pairs != null){
				for(int j=0; j < pairs_length; j++)
					parts[files_length+j] = pairs[j];
			}
				
				
				post.setRequestEntity(new MultipartRequestEntity(parts, post.getParams()));     
				client.getHttpConnectionManager().getParams().setConnectionTimeout(5000);
			
			
			client.executeMethod(post);   //��ӡ���������ص�״̬   
			System.out.println(post.getStatusLine());   //��ӡ���ҳ��  
			String response=new String(post.getResponseBodyAsString().getBytes("UTF-8"));
			for(Header hdr: post.getResponseHeaders()){
				System.out.println("Header["+hdr.getName()+"]="+hdr.getValue());
			}
			Header cookie = post.getResponseHeader("Set-Cookie");
			if(cookie != null)
				phpSessionID = cookie.getValue().split("=")[1];
			System.out.println(response);
			post.releaseConnection();
		}
		catch(IOException e){
			e.printStackTrace();
		}
	}
	
	private static String getStringFromFile(String fileName){
		StringBuffer buffer = new StringBuffer();
		InputStream is;
		try {
			is = new FileInputStream(fileName);
			String line; // ��������ÿ�ж�ȡ������
	        BufferedReader reader = new BufferedReader(new InputStreamReader(is));
	        line = reader.readLine(); // ��ȡ��һ��
	        while (line != null) { // ��� line Ϊ��˵��������
	            buffer.append(line); // ��������������ӵ� buffer ��
	            buffer.append("\n"); // ��ӻ��з�
	            line = reader.readLine(); // ��ȡ��һ��
	        }
	        reader.close();
	        is.close();
		} catch (FileNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
        
		return buffer.toString();
	}
	
	private static void test() throws HttpException, IOException{
		//client.getHostConfiguration().setHost( "www.baidu.com" , 80, "http" );
		PostMethod post = new PostMethod("http://127.0.0.1");
		client.executeMethod(post);
		String response=new String(post.getResponseBodyAsString().getBytes("UTF-8"));
		System.out.println(response);
		post.releaseConnection();
	}
}