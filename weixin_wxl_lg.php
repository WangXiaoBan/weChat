<?php
$xml="<xml>
	<ToUserName><![CDATA[接受者]]></ToUserName>
	<FromUserName><![CDATA[发送者]]></FromUserName>
	<CreateTime>."time()".</CreateTime>
	<MsgType><![CDATA[text]]></MsgType>
	<Content><![CDATA[发送内容]]></Content>
	</xml>";
$file="weixin_wxl_lg_xml";
$handle=fopen($file,"a");
if($handle){
	fwrite($handle,$xml);
}else{
	
}

fclose($handle);
?>