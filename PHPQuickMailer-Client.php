<?php
// script sends a json variable for the mail infomation 
//this script required the php-curl to be installed. 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$remoteAddress = "https://domain_of_server/Path/To/File/PHPQuickMailer-Server.php";
$secret = '395f4f5539da5154b44884848d990992';


//array for the stuff to send
$array['message'] = "Hi this is some <b>HMTL</b>";
$array['subject'] = "Testing email";
$array['to'] = "user@domain";
$array['from'] = "user@domain";
$array['secret'] = $secret;

$json = "json=".json_encode($array);

$ch = curl_init($remoteAddress);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_ENCODING,"");

header('Content-Type: text/html');
$data = curl_exec($ch);

//may as well echo anything we got back from the mailer script. 
echo $data;

?>
