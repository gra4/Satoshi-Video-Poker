<?php
/*
called via ajax
?action=deal&bet=5
?action=trade
?action=update
	
*/
session_start();
session_cache_expire(180); //minutes
include_once('cm_settings.php');

$action = $_GET['action'];

if($action == 'deal')
{
//$_SESSION["cm_balance"] = 100; 
//print_r($_SESSION); //die;
	$pat = '11111'; //change all, 0 - leave as is
	if(isset($_GET['pat']))
	{
		$pat = $_GET['pat'];
	}
	if( isset($_GET['bet']) )
	{
		$pat = '11111'; //reset. no cheating!
		$bet = intval($_GET['bet']);
		if( ($bet > 0) && ( ($_SESSION["cm_balance"] - $bet) > 0) )
		{
			$_SESSION["cm_bet"] = $bet;
		}
		else
		{
			$_SESSION["cm_bet"] = 1;
		}
		$_SESSION["cm_balance"] = $_SESSION["cm_balance"] - $_SESSION["cm_bet"];
		if($_SESSION["cm_balance"] < 0)
		{
			$_SESSION["cm_balance"] = 0;
		}
	}
	$cardvals = deal($pat,$_SESSION["cm_cardvals"]);
	$_SESSION["cm_cardvals"] = $cardvals;
	echo($cardvals . ',' . $_SESSION["cm_balance"] . ',' . $_SESSION["cm_bet"]);
	exit;
}

if($action == 'update')
{
	$msg = '';
	$bet = intval($_SESSION["cm_bet"]);
	if($bet < 1)
	{
		$_SESSION["cm_bet"] = 1;
	}
	$balance = intval($_SESSION["cm_balance"]);
	if(!isset($_SESSION["cm_bonuses_diven"]))
	{
		$_SESSION["cm_bonuses_diven"] = 0;
	}
	if( ($balance == 0) && ($_SESSION["cm_bonuses_diven"] < $bonuses_before_deposit))
	{
		$initial_bonus = rand($minimum_initial_bonus,$maximum_initial_bonus);
		$_SESSION["cm_balance"] = $initial_bonus;
		$msg = "You've got bonus $initial_bonus satoshi";
		$_SESSION["cm_wins_after_bonus"] = 0; //no withdraw until  >= $bonus_wins_before_withdraw
		$_SESSION["cm_bonuses_diven"] = $_SESSION["cm_bonuses_diven"] + 1;
	}	
	echo('0,0,0,0,0,' .$_SESSION["cm_balance"] . ',' . $_SESSION["cm_bet"] .',' . $_SESSION["cm_wins_after_bonus"] .','. $msg);
	exit;
}

if($action == 'trade')	
{
	echo(checkwin());
	exit;
}
	
	
function deal($pat,$cardvals)
{
	$a = array();
	$acv = explode(',',$cardvals);
	$a = $acv;
	for($i=0; $i<5; $i++)
	{
		if($pat[$i] == 1)
		{
			do{
				$n = rand(0,51);
			} while(in_array($n,$a));
			$a[$i] = $n;
		}
		else
		{
			$a[$i] = $acv[$i];
		}

	}
//print_r($a);	
	$ret = implode( ',' , $a ); 
	return($ret);
}//deal

function compare($a, $b) {
  return($a-$b);
}

function checkwin()
{
// https://en.wikipedia.org/wiki/Video_poker
//print_r($_SESSION); return;
	$trof_b_jacks = 1; 			//bonus
	$trof_b_twopair = 2; 		//bonus
	$trof_b_threes = 3; 		//bonus
	$trof_b_straight = 4; 		//bonus
	$trof_b_flush = 6; 			//bonus
	$trof_b_fullhouse = 9; 		//bonus  
	$trof_b_four = 25;			//bonus
	$trof_b_straitflush = 50; 	//bonus 
	$trof_b_royalflush = 250; 	//bonus 800?

	if($_SESSION["cm_cardvals"] == '') //shall never happen
	{
		return(0 . ',' . $_SESSION["cm_balance"] . ',' . 0 .  ',' . 'Internal error');
	}
	
    $msg = '';
	$bet = $_SESSION["cm_bet"];
	$cardvals = explode(',',$_SESSION["cm_cardvals"]);

	$suits = array();                   // 4 Used to check for a flush
	$matched = array();                // 13 Used to check for pairs, threes, fours
	$pairs = 0; $threes = 0; $fours = 0;       //  1 or 2 if we have any of these, 0 if not
	$flush = false; $straight = false;        //  true if we have a flush or straight, false if not
	$won = 0;                                //  1 if there's a winning hand, 0 if not

  usort($cardvals, "compare");                     //  Sort the cards using the compare() function

  for ( $i = 0; $i < 4; $i++ ) {
    $suits[$i] = 0;                             //  Initialise suits array to zero
  }

  for ( $i = 0; $i < 13; $i++ ) {
    $matched[$i] = 0;                           //  Initialise matched array to zero  
  }

  for ( $i = 0; $i < 5; $i++ ) {
    $matched[$cardvals[$i] % 13]++;              //  Update matched for cards
    $suits[floor($cardvals[$i]/13)]++;      //  Update suits for cards
  }

  for ( $i = 0; $i < 13; $i++ ) {
    if ( $matched[$i] == 2 ) {                  //  Check for pairs
      $pairs++;
    }
    else if ( $matched[$i] == 3 ) {             //  Check for three of a kind
      $threes++;
    }
    else if ( $matched[$i] == 4 ) {             //  Check for four of a kind
      $fours++;
    }
  }

  for ( $i = 0; $i < 4; $i++ ) {                        //  Check for a flush
    if ( $suits[$i] == 5 ) {
      $flush = true;
    }
  }

  if ( $cardvals[4] - $cardvals[1] == 3  &&                //  Consistent with 
       $cardvals[4] - $cardvals[0] == 12 &&                //  A, T, J, Q, K...
       $flush ) {                                       
    $msg = "Royal flush!";
    $won = $bet * $trof_b_royalflush;
  }
  else if ( $cardvals[4] - $cardvals[0] == 4 && $flush ) {  //  If we also have a flush, then its a royal flush
    $msg = "Straight flush!";
    $won = $bet * $trof_b_straitflush;
  }


  //  Sort cards by face value (necessary to check for a straight)

  for ( $i = 0; $i < 5; $i++ )
    $cardvals[$i] = $cardvals[$i] % 13;
  usort($cardvals, "compare"); //do we need it?


  if ( $won == 0 ) {                                      // Don't check further if we've already won
    if ( $fours > 0 ) {
      $msg = "Four of a kind!";
      $won = $bet * $trof_b_four;
    }
    else if ( $threes && $pairs ) {
      $msg = "Full house!";
      $won = $bet * $trof_b_fullhouse;
    }
    else if ( $flush ) {
      $msg = "A flush!";
      $won = $bet * $trof_b_flush;
    }
    else if ( $cardvals[4] - $cardvals[3] == 1 && $cardvals[3] - $cardvals[2] == 1 &&
              $cardvals[2] - $cardvals[1] == 1 && ( $cardvals[1] - $cardvals[0] == 1 ||
              $cardvals[4] - $cardvals[0] == 12 ) ) {
      $msg = "A straight!";
      $won = $bet * $trof_b_straight;
    }
    else if ( $threes ) {
      $msg = "Three of a kind!";
      $won = $bet * $trof_b_threes;
    }
    else if ( $pairs == 2 ) {
      $msg = "Two pair!";
      $won = $bet * $trof_b_twopair;
    }
    else if ( $matched[0]  == 2 ||
              $matched[10] == 2 ||             
              $matched[11] == 2 ||             
              $matched[12] == 2 ) {
      $msg = "Jacks or better!";
      $won = $bet * $trof_b_jacks;
    }
    else {
      $msg = "Almost! Deal to try again...";
    }
  }	
	
	$win_after_bonus = intval($_SESSION["cm_wins_after_bonus"]);
	$balance = intval($_SESSION["cm_balance"]);
	if($won > 0)
	{
		$win_after_bonus++;
		$_SESSION["cm_wins_after_bonus"] = $win_after_bonus;
		$balance += $won;
		$_SESSION["cm_balance"] = $balance;
		$msg .= " You win $won satoshi !";
	}
	
	//drop cardvals
	$_SESSION["cm_cardvals"] = '';

	return($won . ',' . $balance . ',' . $win_after_bonus .  ',' .  $msg);
	
}//checkwin


