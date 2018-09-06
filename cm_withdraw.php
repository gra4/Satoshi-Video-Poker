<?php
//relies on session! don't forget tto do session_start(); in the main page! 
session_start();
include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'poker_get_settings.php');

if($api_key == '')
{
	die('Please provide correct $api_key in the cm_settings.php');
}

//GET flags:
//	?address=1GHrzqB6Ngab1gvZDd2tyTXxigziy26L6s - adress to withdraw. 



if(isset($_GET['address']))
{
	if(strlen($_GET['address']) > 0) //correct?
	{
		do_withdraw($_GET['address']);//start transaction
	}
	else
	{
		die('Address must me set');//go away
	}
}


function do_withdraw($address)
{
//print_r($_SESSION);
	$balance = intval($_SESSION["cm_balance"]);
	if($balance <= 0)
	{
		$msg = "Your balance is $balance satoshi. Nothing to withdraw yet!";
		die("<br><center>$msg<br><button onclick='window.close()'>close</button></center>");
	}

	global $bonus_wins_before_withdraw;
	if($_SESSION["cm_wins_after_bonus"] < $bonus_wins_before_withdraw)
	{
		$js = "<script>window.opener.postMessage(\"vp_withdraw\",\"*\" );</script>";
		$msg = "When playing on the bonus you must win at least ".$bonus_wins_before_withdraw." times before withdraw.";
		die("<br><center>$msg<br><button onclick='window.close()'>close</button></center>");
	}	
	
	global $api_key;
	$fields = array(
	'api_key'=> $api_key,
	'to'=>$address,
	'amount'=>$balance
	);
//print_r($fields); 
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://cryptoo.me/api/v1/send');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
	$res = curl_exec($curl);
//print_r($res); die(' 0');
	if($errno = curl_errno($curl)) 
	{
		$error_message = curl_strerror($errno);
		die("cURL error ({$errno}):\n {$error_message},\nMake sure cURL is configured properly.");
	}

	curl_close($curl);	

	$out_ison = json_decode($res,true);
//print_r($out_ison); die(' 1');
	if($out_ison["status"] == 200)
	{
		$_SESSION["cm_balance"] = 0;
		$_SESSION["cm_real_mode"] = false;
		$js = "<script>window.opener.postMessage(\"vp_withdraw\",\"*\" );";
		$js .= "window.location='https://cryptoo.me/check/$address'</script>";
		die("$js");
	}
//var_dump($out_ison); 
//var_dump($res);
	$msg = $out_ison["message"];//$out_ison->message;
	die("<br><center>$msg<br><button onclick='window.close()'>close</button></center>");
//if we here - no success, error!	
}//do_withdraw


?>