<?php
//relies on session! don't forget tto do session_start(); in the main page! 
session_start();
include_once('cm_settings.php');

if($api_key == '')
{
	die('Please provide correct $api_key in the cm_settings.php');
}
//GET flags:
//	?amount=10 - ammount to deposit. 
//  ?check=1 - callback, peform the check of the invoice id from the session


if(isset($_GET['amount']))
{
	$amount = intval($_GET['amount']);
	if(($amount >= $minimum_deposit) && ($amount <= $maximum_deposit)) //correct?
	{
		step_one($amount);//start transaction
	}
	else
	{
		die("Amount must be between $minimum_deposit and $maximum_deposit");//go away
	}
}
else //no amount - we check
{
	step_two();//check transaction
}

function step_two()
{
	if(	( intval($_SESSION["cm_deposit_amount"]) > 0) && ($_SESSION["cm_deposit_invid"]) )
	{
		global $api_key;
		$fields = array(
			'key'=> $api_key,
			'invid'=>$_SESSION["cm_deposit_invid"]
		);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://cryptoo.me/api/v1/invoice/state/');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
		$out = curl_exec($curl);
		curl_close($curl);	
		$out_ison = json_decode($out);

		if($out_ison->success == true)
		{
			if($_SESSION["cm_deposit_amount"] == $out_ison->invoice->amount)
			{
				$balace = intval($_SESSION["cm_balance"]);
				$balace += intval($out_ison->invoice->amount);
				$_SESSION["cm_balance"] = $balace;
				$_SESSION["cm_bonuses_diven"] = 0;
				$ret = 'Balance: ' . $balace . ' satoshi';
				$deposits = intval($_SESSION["cm_deposits"]);
				$deposits++;
				$_SESSION["cm_deposits"] = $deposits;
				$_SESSION["cm_wins_after_bonus"] = 9999; //allow to withdraw
			}
			else
			{
				$ret = $out_ison->message . '<br>Amount error. Try again';
			}
		}
		else
		{
			$ret = 'Transaction error. Try again';
		}
		unset($_SESSION["cm_deposit_amount"]);
		unset($_SESSION["cm_deposit_invid"]);	
	}
	else //session check
	{
		$ret = 'Unexpected data. Try again';
	}
	$js = "<script>window.opener.postMessage(\"vp_deposit\",\"*\" );</script>";
	die("$js<center>$ret<br><button onclick='window.close()'>close</button></center>");
}//step_two

function step_one($amount)
{
	if(!function_exists('curl_version'))
	{
		die('cURL is not enabled on this hosting');
	}
	global $api_key;
	$self_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	if ($pos_get = strpos($self_url, '?')) 
	{
		$self_url = substr($self_url, 0, $pos_get);
	}
	$callback_url = $self_url . '';
	
	$fields = array(
		'key'=>$api_key,
		'amount'=>$amount,
		'notice'=>'Deposit for VP',
		'data'=>'VP'.$amount,
		'redirect_url'=>$callback_url,
	);

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://cryptoo.me/api/v1/invoice/create/');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
	$out = curl_exec($curl);
	if($errno = curl_errno($curl)) 
	{
		$error_message = curl_strerror($errno);
		die("cURL error ({$errno}):\n {$error_message},\nMake sure cURL is configured properly.");
	}	
	curl_close($curl);

	$out_ison = json_decode($out);
	$_SESSION["cm_deposit_amount"] = $amount;
	$_SESSION["cm_deposit_invid"] = $out_ison->invid;
 
	header('Location: https://cryptoo.me/api/v1/invoice/open/'.$out_ison->invid);
}//step_one

?>