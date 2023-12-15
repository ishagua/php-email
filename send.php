<?php

	require_once('Mail.php'); 
	require_once('Mail/mime.php'); 
	require_once('Net/SMTP.php'); 
	header("content-Type: text/html; charset=Utf-8"); 

	$name = "xxxxx";	 							//网站名称
	$url = "xxx.com";								//网站地址
	
	$email = "admin@xxx.com";							//邮箱地址
	$pwd = "passwd"; 								//邮箱密码

	$smtpinfo = array();     
	$smtpinfo["host"] = "ssl://smtp.gmail.com";					//SMTP服务器 
	$smtpinfo["port"] = "465"; 							//SMTP服务器端口 
	$smtpinfo["username"] = $email; 						//发件人邮箱 
	$smtpinfo["password"] = $pwd;							//发件人邮箱密码 
	$smtpinfo["timeout"] = 10;							//网络超时时间，秒 
	$smtpinfo["auth"] = true;							//登录验证

	$from = "$name <$email>";   							//发件人显示信息 
	$contentType = "text/html; charset=utf-8"; 					//邮件正文类型，格式和编码
	$crlf = "\n"; 									//换行符号 Linux: \n Windows: \r\n 

	$no = rand(1000,9999);

	$subject = $name."，邮件通知：$no"; 	 				 
	$content = "<font size='4'><br />您好，</font><br /> <br />
	<font color=Red size='4'>内容第一行 </font><br /> <br /> 
	<font color=Red size='4'>内容第二行</font><br /> <br />
	<font color=Red size='4'>内容第三行</font><br /> <br /><br /> 
	<font color=Blue size='4'>通知编号：$no 是随机生成的数字，请忽略它</font> <br /> <br />
	<font color=Red size='4'>官方网址：<a href='https://".$url."'>http://$url</a></font><br /> <br />
	<font color=Blue size='4'>这是系统发送的邮件，请勿回复！</font> <br /> <br />";

	$param['text_charset'] = 'utf-8'; 
	$param['html_charset'] = 'utf-8'; 
	$param['head_charset'] = 'utf-8';

	$mime = new Mail_mime($crlf); 
	$mime->setHTMLBody($content);  
	$body = $mime->get($param); 

	$emailist = explode(',',file_get_contents('email.txt'));			//所有邮件列表	

	if ( $emailist[0] == "" ){
		file_put_contents('log.txt',"no email addr \n",FILE_APPEND);
		die();
	}

	$headers = array(); 
	$headers["From"] = $from; 
	$headers["To"] = $emailist[0];     
	$headers["Subject"] = $subject; 
	$headers["Content-Type"] = $contentType; 
	$headers = $mime->headers($headers); 
 
	$smtp =& Mail::factory("smtp", $smtpinfo); 
	$mail = $smtp->send($emailist[0], $headers, $body); 

	$smtp->disconnect(); 

	if (PEAR::isError($mail)) {  
		$text =  $emailist[0].' failed: '.$mail->getMessage()."\n"; 
		file_put_contents('log.txt',date("Y-m-d H:i:s")."  ".$text,FILE_APPEND);die();
	} else{ 
		$text = $emailist[0]." success!"."\n"; 
		file_put_contents('log.txt',date("Y-m-d H:i:s")."  ".$text,FILE_APPEND);
	}
	array_shift($emailist);
	file_put_contents('email.txt',implode(',',$emailist));
?>
