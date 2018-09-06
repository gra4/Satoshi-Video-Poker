<?php 
/*
language functions, to simplify localization

*/

include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'poker_lang.php');	

//must be change in plugins , like for WP __()
function poker_wrap_text($t)
{
	return($t);
}

function poker_text($key)
{
	global $a_poker_lang;
	return($a_poker_lang[$key]);
}

function poker_text_to_js()
{
	global $a_poker_lang;

	$ret = "\n<script>";
	foreach ($a_poker_lang as $k => $v) 
	{
		$ret .= "\n var poker_text_" . $k . " = \"" . $v . "\";";
	}
	$ret .= "\n</script>\n";
	return($ret);
}


$a_poker_lang = array(
//cm_deposit.php , cm_withdraw.php - not going to localize for now 
//index.php
	'poker_deposit' => poker_wrap_text('Deposit'),
	'poker_withdraw' => poker_wrap_text('Withdraw'),
	'poker_bet' => poker_wrap_text('Bet'),
	'poker_balance' => poker_wrap_text('Balance'),
	'poker_deal' => poker_wrap_text('Deal !'),
	'poker_time_to_play' => poker_wrap_text("It's time to play! Click button to deal!"),
//a_poker.php
	'poker_got_gonus' => poker_wrap_text("You've got bonus %n satoshi"),
	'royal_flush' => poker_wrap_text("Royal flush"),
	'straight_flush' => poker_wrap_text("Straight flush"),
	'four_of_a_kind' => poker_wrap_text("Four of a kind"),
	'full_house' => poker_wrap_text("Full house"),
	'a_flush' => poker_wrap_text("A flush"),
	'a_straight' => poker_wrap_text("A straight"),
	'three_of_a_kind' => poker_wrap_text("Three of a kind"),
	'two_pair' => poker_wrap_text("Two pair"),
	'jacks_or_better' => poker_wrap_text("Jacks or better"),
	'almost_deal' => poker_wrap_text("Almost! Deal to try again..."),
	'you_won' => poker_wrap_text("You win %n satoshi"),
//poker_util.js	
	'satoshi' => poker_wrap_text("satoshi"),
	'and' => poker_wrap_text("and"),
	'cancel' => poker_wrap_text("Cancel"),
	'must_win_1' => poker_wrap_text("While playing on the bonus you must win at least"),
	'must_win_2' => poker_wrap_text("times before withdraw."),
	'enter_address' => poker_wrap_text("Enter bitcoin address to withdraw your satoshi"),
	'enter_amount' => poker_wrap_text("Enter amount of satoshi to deposit"),
	'incorrect_amount' => poker_wrap_text("is incorrect value for satoshi amount"),
	'must_be_between' => poker_wrap_text("Must be between"),
	'something_went_wrong' => poker_wrap_text("Something went wrong, please reset"),
	'you_still_have' => poker_wrap_text("You still have"),
	'disable_adblock' => poker_wrap_text("Please disable AdBlock"),
//poker.js - pain in the ass
	'must_bet_value' => poker_wrap_text("You must enter bet value between"),
	'you_have_zero' => poker_wrap_text("You have 0 satoshi"),
	'deposit_some' => poker_wrap_text("Please deposit some"),
	'not_that_much' => poker_wrap_text("You don't have that much money to bet"),
	'bet_to_1' => poker_wrap_text("Bet set to 1 satoshi"),
	'click_cards_to_reade' => poker_wrap_text("Click the cards you want to trade"),
	'no_satoshi_reset' => poker_wrap_text("You've run out of satoshi! Click 'OK' to reset."),
	
);