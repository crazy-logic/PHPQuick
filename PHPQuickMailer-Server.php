<?php
//script recieves a json variable for the mail infomation 
$secret = '395f4f5539da5154b44884848d990992';
$allowedAddresses = array('1.1.1.1','127.0.0.1','8.8.8.8');


function mailmessage($message, $to, $subject, $from)
{
	$fromEmail = "<$from>\r\n";
	$headers = 'From: '.$fromEmail;
	$headers .= 'Reply-To: '.$fromEmail; 
	$headers .= 'Return-Path: '.$fromEmail;
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; //charset=UTF-8 //charset=iso-8859-1
	$headers .= "X-Mailer: PHPRemoteMailer";
	
	mail($to, $subject, $message, $headers);
}

function validclient($allowedAddresses,$address)
{
	if(in_array($address,$allowedAddresses))
	{return true;}
	else
	{return false;}
}

//if behind a proxy you may need to change this to 
// $_SERVER['HTTP_CLIENT_IP'] or $_SERVER['HTTP_X_FORWARDED_FOR']
if(isset($_POST['json']) && validclient($allowedAddresses,$_SERVER['REMOTE_ADDR']))
{
    $array = json_decode($_POST['json'],true);
    if(isset($array['secret']) && $array['secret']===$secret)
    {
    	$to = $array['to'];
	    $from = $array['from'];
	    $subject = $array['subject'];
	    $message = $array['message'];
	    mailmessage($message, $to, $subject, $from);
	    echo "mail sent";
    }
    else
    {
    	echo "secret not correct. ";
    }
}
else
{
    echo "no data passed to mail.php or not a valid client: ".$_SERVER['REMOTE_ADDR'];
}

?>
