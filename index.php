<?php session_start(); ?>
<html> 
<head> 
<meta charset='UTF-8'>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Satoshi Video Poker
</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>	
	
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>	

	<link href="lib/messagebox.css" rel="stylesheet">
	<script src="lib/messagebox.js"></script>
	
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">	

	<?php 
		include_once("poker_util.php"); 
		if($stop_if_adblock)
			echo("\n<script src='lib/advertisment.js'></script>\n");
		js_settings_out();
	
	?>	

    <script src="poker.js"></script>	
    <script src="poker_util.js"></script>	
	
</head>

<body>


<style>
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
<style>
div #trof_text
{
position1: relative;
float1: left;
top: 50%;
left1: 50%;
transform1: translate(-50%, -50%);
color:red;
font-size: 170%;
cursor1:pointer;
width1:124px;
margin:5px;
}
body{
	margin:0;
	padding:25px;
	font-family:Arial;
	font-size:14px;
}
</style>


<div class="main_container">



<!-- top ads start -->
<?php include_once("ads/_top_ads.php"); ?>
<!-- top ads end -->




<!-- center block start-->
<div id='center_block' class='flexblock' >
<!-- left ads start -->
<?php include_once("ads/_left_ads.php"); ?>
<!-- left ads end -->
<!-- faucet block start -->
<div id='faucet_wrap' class='flexblock' style='width:450px; border:0px outset green;'>



<!-- -->
<center>
<style>
.maintable_wrapper{
margin: 5px;
}
.flip {cursor: alias;}
.noflip {cursor: not-allowed;}
.card{
	background: white;
	padding: 3px;
	border-radius: 5px;
	cursor: pointer;
}
table {
  position: relative;
  padding: 3px;
}
.maintable:after {
  content: "";
  background:url("img/bg2.jpg");
  opacity: 0.5;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  position: absolute;
  z-index: -1;   
  border-radius: 10px;
  border: 2px gold outset;
}
.vp_msg {
	border: 0;
	background: lightyellow;
	border-radius: 3px;
	width:100%;
}
#deal{
	width:50%;
}
</style>

<div class="maintable_wrapper">
<form action="none" method="post" id='vp_form'>
<table class="maintable" >

  <tr >
	<td colspan="5">
		<table border=0 width="100%">
			<tr>
		
	<td colspan="2" width="50%">
		<input id="cm_deposit"  onclick="trof_deposit()" type="button" value="Deposit" style="width:100%" />
	
	</td>
	<td colspan="2">
		<input id="cm_withdraw" onclick="trof_withdraw()" type="button" value="Withdraw" style="width:100%" />
	</td>
			</tr>
			<tr>
    <td colspan="2">Bet: <input type="text" name="bet" id="vp_bet" min="1" max="100" value="5" size="5" /></td>
    <td colspan="2">Balance: <input type="text" id='vp_balance' readonly="readonly" name="money" value="" size="10" /></td>
			</tr>
		</table>
	</td>
  </tr>  
  
  <tr>
    <td class="card" onclick="flipcard(0)"><img id="vp_c0" src1="img/b1.gif" width="73px" height="97px"  class="card"  /></td>
    <td class="card" onclick="flipcard(1)"><img id="vp_c1" src1="img/b1.gif" width="73px" height="97px"  class="card"  /></td>
    <td class="card" onclick="flipcard(2)"><img id="vp_c2" src1="img/b1.gif" width="73px" height="97px"  class="card"  /></td>
    <td class="card" onclick="flipcard(3)"><img id="vp_c3" src1="img/b1.gif" width="73px" height="97px"  class="card"  /></td>
    <td class="card" onclick="flipcard(4)"><img id="vp_c4" src1="img/b1.gif" width="73px" height="97px"  class="card"  /></td>
  </tr>

  <tr>
    <td class="control" colspan="5"><input  type="button" name="deal" id="deal" value="Deal !" onclick="dealcards(this.form)" /></td>
  </tr>
  
  <tr>
    <td class="control" colspan="5"><input class="vp_msg" type="text" readonly="readonly" name="info" value="It's time to play! Click button to deal!" size="50" /></td>
  </tr>
  
</table>
</form>

<!-- center ads start -->
<?php include_once("ads/_center_ads.php"); ?>
<!-- center ads end -->

<script>trof_set_bg();trof_update();</script>
</div>
</div>



</center>
<!-- -->


<!-- faucet block end -->
<!-- right ads start -->
<?php include_once("ads/_right_ads.php"); ?>
<!-- right ads end -->
</div>
<!-- center block end -->

<!-- bottom ads start -->
<?php include_once("ads/_bottom_ads.php"); ?>
<!-- bottom ads end -->


<hr>
Based on <a target=_blank href="https://cryptoo.me/api-doc/">Cryptoo.me API</a>.
<br>
Source code of Satoshi Video Poker released on the <a target=_blank href="https://github.com/gra4/Satoshi-Video-Poker">GitHub</a>, feel free to download and run.




</body>

</html>

