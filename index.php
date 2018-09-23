<?php session_start(); ?>
<html> 
<head> 
<meta charset='UTF-8'>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Satoshi Video Poker
</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>	
	
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>	


	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">	

	<?php 
		include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'poker_util.php');
		js_settings_out();
		if($stop_if_adblock)
			echo("\n<script src='".poker_get_main_url()."lib/advertisment.js'></script>\n");
		include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'poker_lang.php');	
		echo(poker_text_to_js());
	
	?>	

	<link href="<?php echo(poker_get_main_url());?>lib/messagebox.css" rel="stylesheet">
	<script src="<?php echo(poker_get_main_url());?>lib/messagebox.js"></script>
	
	<link href="<?php echo(poker_get_main_url());?>poker.css" rel="stylesheet">
    <script src="<?php echo(poker_get_main_url());?>poker.js"></script>	
    <script src="<?php echo(poker_get_main_url());?>poker_util.js"></script>	
	
</head>

<body>


<style>
body{
	margin:0;
	padding:25px;
	font-family:Arial;
	font-size:14px;
}
.flexblock{
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	justify-content: center;
	align-items: center;
	align-items: start;
	border:0px dotted magenta; /*remove after debugging*/
}
/* only for readability, replace with read ads*/
.h_ad_placeholder{ /*horizontal*/
	width:728px; height:90px; border:0px dotted grey;
}
.v_ad_placeholder{ /*vertical*/
	width:160px; height:600px; border:0px dotted grey;
}
</style>


<div class="main_container">



<!-- top ads start -->
<?php include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR . "ads". DIRECTORY_SEPARATOR ."_top_ads.php"); ?>
<!-- top ads end -->




<!-- center block start-->
<div id='center_block' class='flexblock' >
<!-- left ads start -->
<?php include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR . "ads". DIRECTORY_SEPARATOR ."_left_ads.php"); ?>
<!-- left ads end -->
<!-- faucet block start -->
<div id='faucet_wrap' class='flexblock' style='width:550px; border:0px outset green;'>



<!-- -->
<center>


<div class="maintable_wrapper">
<form action="none" method="post" id='vp_form'>
<table class="maintable" style="--poker-bg-img:url('<?php echo(poker_get_main_url()); ?>img/bg2.jpg');">

  <tr >
	<td colspan="5">
		<table border=0 width="100%">
			<tr>
		
	<td colspan="2" width="50%">
		<input id="cm_deposit"  onclick="trof_deposit()" type="button" value="<?php echo(poker_text('poker_deposit')); ?>" style="width:100%" />
	
	</td>
	<td colspan="2">
		<input id="cm_withdraw" onclick="trof_withdraw()" type="button" value="<?php echo(poker_text('poker_withdraw')); ?>" style="width:100%" />
	</td>
			</tr>
			<tr>
    <td colspan="2"><?php echo(poker_text('poker_bet')); ?>: <input type="text" name="bet" id="vp_bet" min="1" max="100" value="5" size="5" /></td>
    <td colspan="2"><?php echo(poker_text('poker_balance')); ?>: <input type="text" id='vp_balance' readonly="readonly" name="money" value="" size="10" /></td>
			</tr>
		</table>
	</td>
  </tr>  
  
  <tr>
    <td class="card" onclick="flipcard(0)"><img id="vp_c0" class="card"  /></td>
    <td class="card" onclick="flipcard(1)"><img id="vp_c1" class="card"  /></td>
    <td class="card" onclick="flipcard(2)"><img id="vp_c2" class="card"  /></td>
    <td class="card" onclick="flipcard(3)"><img id="vp_c3" class="card"  /></td>
    <td class="card" onclick="flipcard(4)"><img id="vp_c4" class="card"  /></td>
  </tr>

  <tr>
    <td class="control" colspan="5"><input  type="button" name="deal" id="deal" value="<?php echo(poker_text('poker_deal')); ?>" onclick="dealcards(this.form)" /></td>
  </tr>
  
  <tr>
    <td class="control" colspan="5"><input class="vp_msg" type="text" readonly="readonly" name="info" value="<?php echo(poker_text('poker_time_to_play')); ?>" size="50" /></td>
  </tr>
  
</table>
</form>

<!-- center ads start -->
<?php include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR . "ads". DIRECTORY_SEPARATOR ."_center_ads.php"); ?>
<!-- center ads end -->

<script>trof_set_bg();trof_update();</script>
</div>

</div>



</center>
<!-- -->


<!-- faucet block end -->
<!-- right ads start -->
<?php include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR . "ads". DIRECTORY_SEPARATOR ."_right_ads.php"); ?>
<!-- right ads end -->
</div>
<!-- center block end -->

<!-- bottom ads start -->
<?php include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR . "ads". DIRECTORY_SEPARATOR ."_bottom_ads.php"); ?>
<!-- bottom ads end -->


<hr>
Based on <a target=_blank href="https://cryptoo.me/api-doc/">Cryptoo.me API</a>.
<br>
Source code of Satoshi Video Poker released on the <a target=_blank href="https://github.com/gra4/Satoshi-Video-Poker">GitHub</a>, feel free to download and run.




</body>

</html>

