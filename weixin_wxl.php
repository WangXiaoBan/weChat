<?php
// define(TOKEN,"wangxilong");

// $weixin=new weichat();
// if(isset($_GET['echostr']) && !empty($_GET['echostr']) ){
// 	$weixin->configure();
// }else{
// 	$weixin->Handle();
// }


// class weichat{

// 	//开始前的接口配置方法
// 	public function configure(){
//         $signature=$_GET["signature"];
//         $timestamp=$_GET["timestamp"];
//         $nonce=$_GET["nonce"];
        
// 		$tmpArr = array(TOKEN,$timestamp, $nonce);
// 		sort($tmpArr, SORT_STRING);
// 		$tmpStr = implode( $tmpArr );
// 		$tmpStr = sha1( $tmpStr );

// 		if( $signature==$tmpStr ){
// 			echo $_GET['echostr'];
// 		}


// 	}

// 	public function Handle(){
// 		//接收post过来的xml格式数据
// 		$postxml=$GLOBALS['HTTP_RAW_POST_DATA'];
// 		//将接收的消息存入日志
// 		$this->logger($postxml);

// 		//将xml转成数组格式
// 		$postObj=simplexml_load_string($postxml,"SimpleXMLElement",LIBXML_NOCDATA);
// 		//接收消息
// 		switch ($postObj->MsgType) {
// 			case 'text':
// 				$result=$this->testingText($postObj);
// 				break;
// 			case 'image':
// 				//$result=$this->replyText($postObj);
// 				break;
// 			case 'voice':
// 				//$result=$this->replyText($postObj);
// 				break;
// 			case 'video':
// 				//$result=$this->replyText($postObj);
// 				break;
// 			case 'shortvideo':
// 				//$result=$this->replyText($postObj);
// 				break;
// 			case 'location':
// 				//$result=$this->replyText($postObj);
// 				break;
// 			case 'link':
// 				//$result=$this->replyText($postObj);
// 				break;
// 			default:
// 				$result=$this->undefindType($postObj);
// 				break;
// 		}

// 		echo $result;
// /*		if($postObj->Content=="你好"){
// 			echo $this->replyText($postObj);
// 		}*/


// 	}
// 	//回复文本消息的方法
// 	public function testingText($postObj){
// 		$keyword=trim($postObj->Content);
// 		if(strstr($keyword,"你好")){
// 			$backContent="我是回复的消息";
// 			$result=$this->replyText($postObj,$backContent);
// 		}
// 		return $result;

// 	}
// 		//回复文本格式数据的方法
// 		public function replyText($postObj,$backContent){
// 		//回复文本消息的格式
// 		$xml="<xml>
// 			<ToUserName><![CDATA[%s]]></ToUserName>
// 			<FromUserName><![CDATA[%s]]></FromUserName>
// 			<CreateTime>%s</CreateTime>
// 			<MsgType><![CDATA[text]]></MsgType>
// 			<Content><![CDATA[%s]]></Content>
// 			</xml>";
// 			$result=sprintf($xml,$postObj->FromUserName,$postObj->ToUserName,time(),$backContent);
// 		return $result;
// 		}


// //类型不正确时出发的方法
// 	public function undefindType($postObj){
// 		$xml="<xml>
// 		<ToUserName>".$postObj->FromUserName."</ToUserName>
// 		<FromUserName>".$postObj->ToUserName."</FromUserName>
// 		<CreateTime>".time()."</CreateTime>
// 		<MsgType><![CDATA[text]]></MsgType>
// 		<Content><![CDATA[发送消息类型不正确,请重新尝试!]]></Content>
// 		</xml>";
// 		return $xml;
// 	}


// 	public function logger($log_content){
// 		$maxsize=1000000;
// 		$file="log_wxl.xml";
// 		$log_content="\n".$log_content.date("Y-m-d H:i:s",time())."\n";
// 		if(file_exists($file) && (filesize($file)>$maxsize) ){
// 			unlink($file);
// 		}
		
// 		file_put_contents($file, $log_content,FILE_APPEND);
// 	}

// }

		



	define(TOKEN,"wangxilong");
	$weixin=new wechat();
	if( isset($_GET['echostr']) && !empty($_GET['echostr']) ){
		$weixin->configure();
	}else{
		$weixin->handle();
	}


	class wechat{

		//接口文件配置
		function configure(){
			$signature=$_GET["signature"];
			$timestamp=$_GET["timestamp"];
			$nonce=$_GET["nonce"];
			        
			$tmpArr = array(TOKEN ,$timestamp, $nonce );
			sort($tmpArr, SORT_STRING);
			$tmpStr = implode( $tmpArr );
			$tmpStr = sha1( $tmpStr );

			if( $signature==$tmpStr ){
				echo $_GET['echostr'];
			}
		}


		function handle(){
			//接受post传递的xml数据
			$postxml=$GLOBALS['HTTP_RAW_POST_DATA'];
			//写日志
			$this->logger($postxml);
			//xml数据转化成数组
			$postobj=simplexml_load_string($postxml,"SimpleXMLElement",LIBXML_NOCDATA);

			switch ($postobj->MsgType) {
				case 'text':
					$result=$this->checkText($postobj);
					break;
				// case 'image':
				// 	$result=$this->checkImage($postobj);
				// 	break;
				case 'voice':
					$result=$this->checkVoice($postobj);
					break;
				// case 'video':
				// 	$result=$this->checkVideo($postobj);
				// 	break;
				default:
					$result=$this->replyFindNoneType($postobj);
					break;
			}
			echo $result;
		}


		//发送类型不正确时的处理函数
		function replyFindNoneType($postobj){
			$xml="<xml>
				<ToUserName>".$postobj->FromUserName."</ToUserName>
				<FromUserName>".$postobj->ToUserName."</FromUserName>
				<CreateTime>".time()."</CreateTime>
				<MsgType><![CDATA[text]]></MsgType>
				<Content><![CDATA[发送的类型不正确]]></Content>
				</xml>";
			return $xml;
		}


		//接受语音
		function checkVoice($postobj){
			$keyword=trim($postObj->Recognition);
			if($keyword=="我是你爸爸" || $keyword=="我是你爹"){
				$backword="我是你爷爷";
			}else{
				$backword="我是你爸爸";
			}
			$rt=$this->replyText($backword,$postobj);
			return $rt;
		}


		//检验处理文本消息函数
		function checkText($postobj){
			//去空格，后面还可以有多个参数
			$word=trim($postobj->Content);
			//回复条件
			if(strstr($word,"你好")){
				$backWord="你也好";
				$rt=$this->replyText($backWord,$postobj);
			}else if(strstr($word,"单图文")){
				$backWord[]=array('Title' =>"标题1",'Description'=>"描述1",'PicUrl' =>"https://mmbiz.qpic.cn/mmbiz_png/M0rWuibMWRpy0xEctG863ma0HJRf9UEAOZJBO3SHLa0louLicia9tDb0x12dzicwgSiakkJ8OdljxpsaibkRMMBmf3AA/0?wx_fmt=png",'Url' =>"https://mp.weixin.qq.com/s?__biz=MzU0NzQxMzQxMw==&tempkey=OTI3X2Fhcno5R21tS0t4K3J6YkVEZTlrV3RnQ3Jrem1rbjF3Z25FV0Y2WE4zYm1Bc2V1MjV5MWxjY0hSdEdBVWZ5RnhFeTF5WGZHQ08wT1dqUE5vb24wQjk3aGxvaVVPT2l0YS0xalBnZGZXN1U1QjBuUV9jLTUxbGo4UVAwd18wWEZVMk8tUm01Ymo4X3VKYkpRMmVuZzk5MGNFU2txWjhaSW9xZnY2RVF%2Bfg%3D%3D&chksm=7b4f8dc34c3804d59765588049119d2319de296d3133c83b1bd9bf49f17baed7fe48da69ca3d#rd");
		
			}else if(strstr($word,"多图文")){
				$backWord[]=array('Title' =>"标题1",'Description'=>"描述1",'PicUrl' =>"https://mmbiz.qpic.cn/mmbiz_png/M0rWuibMWRpy0xEctG863ma0HJRf9UEAOZJBO3SHLa0louLicia9tDb0x12dzicwgSiakkJ8OdljxpsaibkRMMBmf3AA/0?wx_fmt=png",'Url' =>"https://mp.weixin.qq.com/s?__biz=MzU0NzQxMzQxMw==&tempkey=OTI3X2Fhcno5R21tS0t4K3J6YkVEZTlrV3RnQ3Jrem1rbjF3Z25FV0Y2WE4zYm1Bc2V1MjV5MWxjY0hSdEdBVWZ5RnhFeTF5WGZHQ08wT1dqUE5vb24wQjk3aGxvaVVPT2l0YS0xalBnZGZXN1U1QjBuUV9jLTUxbGo4UVAwd18wWEZVMk8tUm01Ymo4X3VKYkpRMmVuZzk5MGNFU2txWjhaSW9xZnY2RVF%2Bfg%3D%3D&chksm=7b4f8dc34c3804d59765588049119d2319de296d3133c83b1bd9bf49f17baed7fe48da69ca3d#rd");
				$backWord[]=array('Title' =>"标题2",'Description'=>"描述2",'PicUrl' =>"https://mmbiz.qpic.cn/mmbiz_png/M0rWuibMWRpy0xEctG863ma0HJRf9UEAOZJBO3SHLa0louLicia9tDb0x12dzicwgSiakkJ8OdljxpsaibkRMMBmf3AA/0?wx_fmt=png",'Url' =>"https://mp.weixin.qq.com/s?__biz=MzU0NzQxMzQxMw==&tempkey=OTI3X2Fhcno5R21tS0t4K3J6YkVEZTlrV3RnQ3Jrem1rbjF3Z25FV0Y2WE4zYm1Bc2V1MjV5MWxjY0hSdEdBVWZ5RnhFeTF5WGZHQ08wT1dqUE5vb24wQjk3aGxvaVVPT2l0YS0xalBnZGZXN1U1QjBuUV9jLTUxbGo4UVAwd18wWEZVMk8tUm01Ymo4X3VKYkpRMmVuZzk5MGNFU2txWjhaSW9xZnY2RVF%2Bfg%3D%3D&chksm=7b4f8dc34c3804d59765588049119d2319de296d3133c83b1bd9bf49f17baed7fe48da69ca3d#rd");
				$backWord[]=array('Title' =>"标题3",'Description'=>"描述3",'PicUrl' =>"https://mmbiz.qpic.cn/mmbiz_png/M0rWuibMWRpy0xEctG863ma0HJRf9UEAOZJBO3SHLa0louLicia9tDb0x12dzicwgSiakkJ8OdljxpsaibkRMMBmf3AA/0?wx_fmt=png",'Url' =>"https://mp.weixin.qq.com/s?__biz=MzU0NzQxMzQxMw==&tempkey=OTI3X2Fhcno5R21tS0t4K3J6YkVEZTlrV3RnQ3Jrem1rbjF3Z25FV0Y2WE4zYm1Bc2V1MjV5MWxjY0hSdEdBVWZ5RnhFeTF5WGZHQ08wT1dqUE5vb24wQjk3aGxvaVVPT2l0YS0xalBnZGZXN1U1QjBuUV9jLTUxbGo4UVAwd18wWEZVMk8tUm01Ymo4X3VKYkpRMmVuZzk5MGNFU2txWjhaSW9xZnY2RVF%2Bfg%3D%3D&chksm=7b4f8dc34c3804d59765588049119d2319de296d3133c83b1bd9bf49f17baed7fe48da69ca3d#rd");
			}else if(strstr($word,"音乐")){
				$backWord = array('Title' =>"张仲华话语金曲" ,'Description' =>"销量可绕地球3圈" ,'MusicUrl' =>"http://dx.sc.chinaz.com/Files/DownLoad/sound1/201706/8868.mp3" ,'HQMusicUrl' =>"http://dx.sc.chinaz.com/Files/DownLoad/sound1/201706/8868.mp3");
			}

			if(is_array($backWord)){
				if(isset($backWord['MusicUrl']) && !empty($backWord['MusicUrl']) ){
					$rt=$this->replyMusic($postobj,$backWord);
				}else{
					$rt=$this->replyNews($backWord,$postobj);
				}
			}else{
				$rt=$this->replyText($backWord,$postobj);
			}
			return $rt;
		}


		//回复音乐
		public function replyMusic($postObj,$backContent){
			$xml="<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[music]]></MsgType>
				<Music>
				<Title><![CDATA[%s]]></Title>
				<Description><![CDATA[%s]]></Description>
				<MusicUrl><![CDATA[%s]]></MusicUrl>
				<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
				</Music>
				</xml>";
				$result=sprintf($xml,$postObj->FromUserName,$postObj->ToUserName,time(),$backContent['Title'],$backContent['Description'],$backContent['MusicUrl'],$backContent['HQMusicUrl']);
			return $result;

		}


		//恢复文本消息函数
		function replyText($backWord,$postobj){
			$xml="<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					</xml>";
			$rt=sprintf($xml,$postobj->FromUserName,$postobj->ToUserName,time(),$backWord);
			return $rt;
		}
		//回复图文消息
		function replyNews($backWord,$postobj){
			$item="<item>
				<Title><![CDATA[%s]]></Title> 
				<Description><![CDATA[%s]]></Description>
				<PicUrl><![CDATA[%s]]></PicUrl>
				<Url><![CDATA[%s]]></Url>
				</item>";
			$back_item="";
			foreach($backWord as $v){
				$back_item.=sprintf($item,$v['Title'],$v['Description'],$v['PicUrl'],$v['Url']);
			}

			$xml="<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[news]]></MsgType>
				<ArticleCount>%s</ArticleCount>
				<Articles>%s</Articles>
				</xml>";
			$result=sprintf($xml,$postobj->FromUserName,$postobj->ToUserName,time(),count($backWord),$back_item);
			return $result;
		}


		//填写日志函数
		public function logger($log_content){
			$maxsize=1000000;
			$file="log_wxl.xml";
			$log_content="\n".$log_content.date("Y-m-d H:i:s",time())."\n";
			if(file_exists($file) && (filesize($file)>$maxsize) ){
				unlink($file);
			}
			
			file_put_contents($file, $log_content,FILE_APPEND);
		}

	}
?>