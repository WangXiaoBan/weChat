<?php 
define(TOKEN,"unikee");

$weixin=new weichat();
if(isset($_GET['echostr']) && !empty($_GET['echostr']) ){
	$weixin->configure();
}else{
	$weixin->Handle();
}


class weichat{

	//开始前的接口配置方法
	public function configure(){
        $signature=$_GET["signature"];
        $timestamp=$_GET["timestamp"];
        $nonce=$_GET["nonce"];
        
		$tmpArr = array(TOKEN,$timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );

		if( $signature==$tmpStr ){
			echo $_GET['echostr'];
		}


	}

	public function Handle(){
		//接收post过来的xml格式数据
		$postxml=$GLOBALS['HTTP_RAW_POST_DATA'];
<<<<<<< HEAD
=======
		//将接收的消息存入日志
		
// $postxml="<xml><ToUserName><![CDATA[gh_5434e749fe7d]]></ToUserName>
// <FromUserName><![CDATA[o5OcZxPEZou-F8JDeYzJ-nh5gsi4]]></FromUserName>
// <CreateTime>1508549864</CreateTime>
// <MsgType><![CDATA[text]]></MsgType>
// <Content><![CDATA[你好]]></Content>
// <MsgId>6479172330694985900</MsgId>
// </xml>";
$this->logger($postxml);
>>>>>>> 123
		//将xml转成数组格式
		$postObj=simplexml_load_string($postxml,"SimpleXMLElement",LIBXML_NOCDATA);
		//接收消息
		switch ($postObj->MsgType) {
			case 'text':
<<<<<<< HEAD
=======
			//echo "11111";
>>>>>>> 123
				$result=$this->testingText($postObj);
				break;
			case 'image':
				//$result=$this->replyText($postObj);
				break;
			case 'voice':
				//$result=$this->replyText($postObj);
				break;
			case 'video':
				//$result=$this->replyText($postObj);
				break;
			case 'shortvideo':
				//$result=$this->replyText($postObj);
				break;
			case 'location':
				//$result=$this->replyText($postObj);
				break;
			case 'link':
				//$result=$this->replyText($postObj);
				break;
			default:
				$result=$this->undefindType($postObj);
				break;
		}

		echo $result;
/*		if($postObj->Content=="你好"){
			echo $this->replyText($postObj);
		}*/


	}
	//回复文本消息的方法
	public function testingText($postObj){
		$keyword=trim($postObj->Content);
<<<<<<< HEAD
		if(strstr($keyword,"你好")){
			$backContent="我是回复的消息";
			$result=$this->replyText($postObj,$backContent);
		}
=======
		//echo "21222";
		if(strstr($keyword,"你好")){
			$backContent="我是回复的消息";
		}
		if(strstr($keyword,"图文")){
			//$backContent="接收的是图文";
			$backContent = array('Title' =>"标题1",'Description'=>"描述1",'PicUrl' =>"https://mmbiz.qpic.cn/mmbiz/HsRflFHwB17NicBuv1TOFsOWaicEDaYBjJic8XuYBECPNIqic57hdnWhBrSFH6JK73rLJQdV1xyxyf2OI6jI1piaUIQ/0?wx_fmt=jpeg",'Url' =>"https://mp.weixin.qq.com/s?__biz=MzAwMTM0MzMyMA==&tempkey=OTI3XzZuS1hNWEl1ZWlPSzQrZUltU0NJVlZyakN3eDdxT1ZHbTR3ck93bzY4YXgyQU5HN2NUdk85OXYzYkYxVmNXU19zRXRyV2hlMnhjQ3ZTMVk5cTZBQmNSNDRaY01GaTV6WEROR0I3NW1jOFZxVll1YVFnY3dfU3M2SFJRTGg3R25YRTBOT0JWRU9PTXJrWlp0WmdZSHA3RHdiVW51VDg2MUgwVmpoT1F%2Bfg%3D%3D&chksm=0154aada362323cc7973e91acc2096f3fc8283bdc93933ce0059ff0470627c9d0293f3fc9b99#rd");
		}
		if(is_array($backContent)){
			$result=$this->replyNews($postObj,$backContent);
		}else{
			$result=$this->replyText($postObj,$backContent);
		}
			//$result=$this->replyText($postObj,$backContent);

		
		//echo "3333";
>>>>>>> 123
		return $result;

	}
		//回复文本格式数据的方法
		public function replyText($postObj,$backContent){
		//回复文本消息的格式
		$xml="<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[text]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			</xml>";
			$result=sprintf($xml,$postObj->FromUserName,$postObj->ToUserName,time(),$backContent);
		return $result;
		}

<<<<<<< HEAD
=======
		//回复图文消息的方法
		public function replyNews($postObj,$backContent){
		//回复图文消息的格式
		$xml="<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[news]]></MsgType>
				<ArticleCount>1</ArticleCount>
				<Articles>
				<item>
				<Title><![CDATA[%s]]></Title> 
				<Description><![CDATA[%s]]></Description>
				<PicUrl><![CDATA[%s]]></PicUrl>
				<Url><![CDATA[%s]]></Url>
				</item>
				</Articles>
				</xml>";
			$result=sprintf($xml,$postObj->FromUserName,$postObj->ToUserName,time(),$backContent['Title'],$backContent['Description'],$backContent['PicUrl'],$backContent['Url']);
		return $result;
		}

>>>>>>> 123

//类型不正确时出发的方法
	public function undefindType($postObj){
		$xml="<xml>
		<ToUserName>".$postObj->FromUserName."</ToUserName>
		<FromUserName>".$postObj->ToUserName."</FromUserName>
		<CreateTime>".time()."</CreateTime>
		<MsgType><![CDATA[text]]></MsgType>
		<Content><![CDATA[发送消息类型不正确,请重新尝试!]]></Content>
		</xml>";
		return $xml;
	}

<<<<<<< HEAD
=======

	public function logger($log_content){
		$maxsize=1000000;
		$file="log_th.xml";
		$log_content="\n".$log_content.date("Y-m-d H:i:s",time())."\n";
		if(file_exists($file) && (filesize($file)>$maxsize) ){
			unlink($file);
		}
		
		file_put_contents($file, $log_content,FILE_APPEND);
	}

>>>>>>> 123
}

		


 ?>