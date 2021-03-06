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
		//将接收的消息存入日志
		
// $postxml="<xml><ToUserName><![CDATA[gh_5434e749fe7d]]></ToUserName>
// <FromUserName><![CDATA[o5OcZxPEZou-F8JDeYzJ-nh5gsi4]]></FromUserName>
// <CreateTime>1508549864</CreateTime>
// <MsgType><![CDATA[text]]></MsgType>
// <Content><![CDATA[你好]]></Content>
// <MsgId>6479172330694985900</MsgId>
// </xml>";
$this->logger($postxml);
		//将xml转成数组格式
		$postObj=simplexml_load_string($postxml,"SimpleXMLElement",LIBXML_NOCDATA);
		//接收消息
		switch ($postObj->MsgType) {
			case 'text':
			//echo "11111";
				$result=$this->testingText($postObj);
				break;
			case 'image':
				//$result=$this->testingText($postObj);
				break;
			case 'voice':
				$result=$this->testingVoice($postObj);
				break;
			case 'video':
				//$result=$this->testingText($postObj);
				break;
			case 'shortvideo':
				//$result=$this->testingText($postObj);
				break;
			case 'location':
				//$result=$this->testingText($postObj);
				break;
			case 'link':
				//$result=$this->testingText($postObj);
				break;
			default:
				$result=$this->undefindType($postObj);
				break;
		}

		echo $result;


	}
	//接收语音消息的方法
	public function testingVoice($postObj){
		$keyword=trim($postObj->Recognition);
		if(strstr($keyword,"干嘛") || strstr($keyword,"干啥") ){
			$backContent="想你那呗！";
		}else{
			$backContent="语音未识别,公众号使用方法如下\n输入1:查看新闻\n输入2:查看商品\n";
		}
		//$backContent=$postObj->Recognition;
		$result=$this->replyText($postObj,$backContent);
		return $result;
	}



	//回复文本消息的方法
	public function testingText($postObj){
		$keyword=trim($postObj->Content);
		if(strstr($keyword,"文本")){
			$backContent="回复的是文本消息";
		}else if(strstr($keyword,"单图文")){
			//$backContent="接收的是图文";
			$backContent[] = array('Title' =>"标题1",'Description'=>"描述1",'PicUrl' =>"https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1508566212510&di=fb755a9d0984e5fabf66ae260f4f2ad2&imgtype=0&src=http%3A%2F%2Ff.hiphotos.baidu.com%2Fimage%2Fpic%2Fitem%2F7dd98d1001e93901d41bc6fe72ec54e737d196d1.jpg",'Url' =>"http://slide.news.sina.com.cn/c/slide_1_2841_211740.html#p=1");
		}else if(strstr($keyword,"多图文")){
			$backContent[] = array('Title' =>"标题1",'Description'=>"描述1",'PicUrl' =>"https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1508566212510&di=fb755a9d0984e5fabf66ae260f4f2ad2&imgtype=0&src=http%3A%2F%2Ff.hiphotos.baidu.com%2Fimage%2Fpic%2Fitem%2F7dd98d1001e93901d41bc6fe72ec54e737d196d1.jpg",'Url' =>"http://slide.news.sina.com.cn/c/slide_1_2841_211740.html#p=1");

			$backContent[] = array('Title' =>"标题2",'Description'=>"描述2",'PicUrl' =>"https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1508566212510&di=fb755a9d0984e5fabf66ae260f4f2ad2&imgtype=0&src=http%3A%2F%2Ff.hiphotos.baidu.com%2Fimage%2Fpic%2Fitem%2F7dd98d1001e93901d41bc6fe72ec54e737d196d1.jpg",'Url' =>"http://slide.news.sina.com.cn/c/slide_1_2841_211740.html#p=1");

			$backContent[] = array('Title' =>"标题3",'Description'=>"描述3",'PicUrl' =>"https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1508566212510&di=fb755a9d0984e5fabf66ae260f4f2ad2&imgtype=0&src=http%3A%2F%2Ff.hiphotos.baidu.com%2Fimage%2Fpic%2Fitem%2F7dd98d1001e93901d41bc6fe72ec54e737d196d1.jpg",'Url' =>"http://slide.news.sina.com.cn/c/slide_1_2841_211740.html#p=1");

		}elseif(strstr($keyword,"音乐")){
			$backContent = array('Title' =>"张仲华话语金曲" ,'Description' =>"销量可绕地球3圈" ,'MusicUrl' =>"http://dx.sc.chinaz.com/Files/DownLoad/sound1/201706/8868.mp3" ,'HQMusicUrl' =>"http://dx.sc.chinaz.com/Files/DownLoad/sound1/201706/8868.mp3");
		}else{
			$backContent="关键词未识别,公众号使用方法如下\n输入1:查看新闻\n输入2:查看商品\n";
		}

		if(is_array($backContent)){
			if (isset($backContent['MusicUrl']) && !empty($backContent['MusicUrl']) ) {
				$result=$this->replyMusic($postObj,$backContent);
			}else{
				$result=$this->replyNews($postObj,$backContent);

			}
		}else{
			$result=$this->replyText($postObj,$backContent);
		}

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

		//回复图文消息的方法
		public function replyNews($postObj,$backContent){
		//回复图文消息的格式
			$back_item="";
			$item="<item>
				<Title><![CDATA[%s]]></Title> 
				<Description><![CDATA[%s]]></Description>
				<PicUrl><![CDATA[%s]]></PicUrl>
				<Url><![CDATA[%s]]></Url>
				</item>";
				foreach ($backContent as $key => $value) {
					$back_item.=sprintf($item,$value['Title'],$value['Description'],$value['PicUrl'],$value['Url']);
				}

		$xml="<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[news]]></MsgType>
				<ArticleCount>%s</ArticleCount>
				<Articles>%s</Articles>
				</xml>";
			$result=sprintf($xml,$postObj->FromUserName,$postObj->ToUserName,time(),count($backContent),$back_item);
		return $result;
		}

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


	public function logger($log_content){
		$maxsize=1000000;
		$file="log_th.xml";
		$log_content="\n".$log_content.date("Y-m-d H:i:s",time())."\n";
		if(file_exists($file) && (filesize($file)>$maxsize) ){
			unlink($file);
		}
		
		file_put_contents($file, $log_content,FILE_APPEND);
	}

}

		


 ?>