

var trof_wins_after_bonus = 0; 	//

var newdeal    = 1;                                    //  1 if ready for new deal, 0 if ready to trade, 2 if out of money
var bet        = 5;                                    //  Stores the amount of next bet
var winnings   = 0;                                  //  Stores cumulative winnings
var back       = new Image(73,97);                     //  Image for back of card
var imgoffset     = 0;                                 //  First card should be (imgoffset + 1)th image on the HTML page

var picked     = new Array(52);                        //  To tell which cards already picked when dealing, 0 if unpicked
var flipped    = new Array(5);                         //  To tell which cards are flipped when trading, 0 if unflipped
var cards      = new Array(5);                         //  Stores images for dealt cards
var cardvals   = new Array(5);                         //  Stores values of dealt cards
var cardimg    = new Array(52);                        //  Stores images of entire deck


for ( var i = 0; i < 5; i++ ) {
  cards[i]   = new Image(73,97);                       //  Create array of five card images
  flipped[i] = 0;                                      //  Set flipped flags to unflipped
}

for ( var i = 0; i < 52; i++ ) {
  cardimg[i]     = new Image(73,97);                   //  Create image array for whole deck
  cardimg[i].src = "img/" + (i+1) + ".gif";   //  Precache all the card images
  picked[i]      = 0;                                  //  Set picked flags to unpicked
}

back.src = "img/b"+rand_bg_id+".gif";                        //  Store image for back of cards


$(document).ready(function(){
	setTimeout(function(){
		if($("#vp_balance").val() == 0){
			animate_deposit();
		}	
	},1000);
});

animate_deposit = function()
{
	$("#vp_balance").effect( "highlight", {color:'red'}, 800,function(){
		$("#cm_deposit").effect("shake", { direction: 'up',times:3,distance:5 },1000 ).effect( "highlight", {color:'lightgreen'}, 1000 );
	});
}

//  function dealcards() - randomly deals five cards or trade selected cards, called by clicking button

function dealcards(form) {
  if ( !newdeal ) {                                                 //  Player has flipped his unwanted cards
    newdeal = 1;                                                    //  Switch newdeal flag
    form.deal.value = "Deal !";                              //  Change description on button
    form.bet.disabled = false;                                      //  Make 'bet' text box read only
  }
  else {
	var b = parseInt(0 + form.bet.value);
    if ( (b < 1) || (b > maximum_bet) ) {                         //  Check that 'bet' text box contains valid integer
 //     alert("You must enter an integer in the 'bet' field!");       //  Alert if it doesn't...
	  $.MessageBox("You must enter bet value between <b>1</b> and <b>" + maximum_bet + "</b>"
	  ).done(function(data){
	    $("#vp_bet").effect( "highlight", {color:'red'}, 800, function(){
			form.bet.focus();                                             //  ...then return focus to text box...
			form.bet.select();                                            //  ...then highlight the invalid text...
			if(b < 1) form.bet.value = 1;
			if(b > maximum_bet) form.bet.value = maximum_bet;
		})
	  });
	  return;                                                       //  ...then go back for another try
    }

	if(parseInt(form.money.value) == 0 )
	{
		$("#vp_balance").effect( "highlight", {color:'red'}, 800,function(){
			$.MessageBox("You have 0 satoshi!"+"<br>"+"Please deposit some"
			).done(function(data){
				animate_deposit();
			});
		});
		return;
	}
	
    if ( parseInt(form.bet.value) > parseInt(form.money.value) ) {  //  Check we aren't betting more than we have
//      alert("You don't have that much money to bet!");              //  Alert if we are...
	  $.MessageBox("You don't have that much money to bet!"+"<br>"+"Bet set to 1 satoshi"
	  ).done(function(data){
		$("#vp_bet").effect( "highlight", {color:'red'}, 800,function(){
			form.bet.focus();                                             //  ...then return focus to text box...
			form.bet.select();                                            //  ...then highlight the invalid text...
			form.bet.value = 1;
		});
	  });
      return;                                                       //  ...then go back for another try
    }
       
    newdeal = 0;                                                    //  This is a new hand, so do some stuff

    for ( var i = 0; i < 5; i++ ) {
      flipped[i] = 0;                                               //  Reset flipped flags for all five cards
    }
    for ( var i = 0; i < 52; i++ ) {
      picked[i] = 0;                                                //  Reset picked status of entire deck
    }

    form.info.value   = "Click the cards you want to trade";        //  Update info box
    bet               = form.bet.value;                             //  Get amount to bet...
    winnings         -= bet;                                        //  ...deduct it from winnings...
    form.money.value  = winnings;                                   //  ...and update winnings
    form.deal.value   = "Trade !";                           //  Change description on button
    form.bet.disabled = true;                                       //  Make 'bet' text box editable again
  }

  form.deal.disabled = true; 
  var trof_n = [];
  var e_params = '';

  if(!newdeal)
  {
	e_params = "bet="+bet;
  }
  else
  {
	var pat = '';
    for ( var i = 0; i < 5; i++ ) {
	if ( !newdeal || flipped[i] )
		pat += '1';
	else
		pat += '0';
	}
	e_params = "pat="+pat;
  }
 
  $.post("a_poker.php?action=deal&"+e_params, {async:true},function(data, status){
//console.log(data);
    trof_n = data.split(',');
		
	winnings = parseInt(trof_n[5]);
	if(winnings > 0)
	{
		form.money.value = winnings;
		bet = parseInt(trof_n[6]);
		form.bet.value = bet;
	}
		

	for ( var i = 0; i < 5; i++ ) {                                   //  Deal five random cards
		if ( !newdeal || flipped[i] ) {                                 //  If not a new hand, replace only the flipped cards
			var n = parseInt(trof_n[i]);                     //  Pick random card
		picked[n] = 1;                                                //  Got it, so set picked flag
		cards[i].src = cardimg[n].src;                                //  Update card image array
//update screen
		document.images[i+imgoffset].src = cardimg[n].src;               //  Update on screen

/*	
		$('#vp_c'+i).effect( "size", {to: { width: 1, height: 97 }}, 1, function(){
//alert(i)
			document.images[i+imgoffset].src = cardimg[n].src;
			$('#vp_c'+i).effect( "size", {to: { width: 73, height: 97 }}, 1);
		});
*/
		
		cardvals[i] = n;                                              //  Store value of card
		}
	}

	if ( newdeal )                                                    //  We've replaced the flipped cards...
		checkwin(form);                                                 //  So check if we've won

	form.deal.disabled = false; 

	});
}


//  function flipcard() - flips a clicked card, called by clicking a card

function flipcard(i) {
  if ( newdeal )                              //  If it's the end of a hand, we can't flip, so just return
  {
	$("#deal").effect('pulsate').effect( "highlight", {color:'lightgreen'}, 800 );
    return;
  }


  if ( !flipped[i] ) {                        //  Either flip card over...
    document.images[i+imgoffset].src = back.src;
    flipped[i] = 1;
  }
  else {                                      //  ...or flip it back
    document.images[i+imgoffset].src = cards[i].src;
    flipped[i] = 0;
  }
}


function checkwin(form) {
	if(!abcheck())
	{
		return;
	}
	var won = 0;                                //  1 if there's a winning hand, 0 if not
	$.post("a_poker.php?action=trade", {async:true},function(data, status){
//console.info(data);
	
		trof_n = data.split(',');
		won = parseInt(trof_n[0]);
		winnings = parseInt(trof_n[1]);
		trof_wins_after_bonus = parseInt(trof_n[2]);
		form.info.value = trof_n[3];
		form.money.value = winnings;
    
  //  Check to see if we're out of money
    
		if ( !winnings ) {
			newdeal = 2;
			$.MessageBox("You've run out of satoshi! Click 'OK' to reset."
			).done(function(data){
				window.location.reload();
				return;
			});
/*
			trof_update();
			form.info.value = "It's time to play! Click button to deal!";
			for ( var i = 0; i < 5; ++i ) {
				flipped[i] = 0;
				flipcard(i);
			}
*/
		}
		if ( won > 0 ) 
		{
			$("#vp_balance").effect( "highlight", {color:'lightgreen'}, 1000 );		
		}
	}); //ajax
}//checkwin2
