<?php 
/*
utility functions, mostly to set javascript variables based on the settings

*/
//relies on session! don't forget tto do session_start(); in the main page! 

include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'poker_get_settings.php');

function poker_get_main_url() //change for plugins!
{
	$url = "http".(!empty($_SERVER['HTTPS'])?"s":""). "://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	return($url); //real must end with '/'
}


function js_settings_out()
{
	global $maximum_bet; //so, between 1 and $maximum_bet
	global $minimum_initial_bonus; //not exposed for now
	global $maximum_initial_bonus; //not exposed for now
	global $bonus_wins_before_withdraw;
	global $maximum_deposit;
	global $minimum_deposit;
	global $balance_page_leave_confirm;
	global $bonuses_before_deposit;
	global $stop_if_adblock;
	$rand_bg_id = rand(1,9);
	echo("
	<script>
	var maximum_bet = $maximum_bet;
	var bonus_wins_before_withdraw = $bonus_wins_before_withdraw;
	var maximum_deposit = $maximum_deposit;
	var minimum_deposit = $minimum_deposit;
	var balance_page_leave_confirm = $balance_page_leave_confirm;
	var bonuses_before_deposit = $bonuses_before_deposit;
	var stop_if_adblock = $stop_if_adblock;
	var rand_bg_id = $rand_bg_id;
	var poker_main_url = '".poker_get_main_url()."'; 
	</script>
	");
}//js_settings_out



