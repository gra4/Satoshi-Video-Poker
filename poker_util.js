

var withdraw_popup;
trof_withdraw = function(){
	var url = poker_main_url + 'cm_withdraw.php?address=';
	if(withdraw_popup)
	{
		withdraw_popup.close();
	}
	if(trof_wins_after_bonus < bonus_wins_before_withdraw)
	{
		$.MessageBox(poker_text_must_win_1+' '+bonus_wins_before_withdraw+' '+poker_text_must_win_2);	
		return;
	}
	var address = localStorage.getItem("bitcoin_addr");
	if(!address)
	{
		address = '';
	}
	$('body').on('click', '.messagebox_button_done', function(){
		withdraw_popup = window.open(url,'withdraw_popup');
	});	
	$.MessageBox({
		input    : address,
		message  : poker_text_enter_address,
		buttonFail  : poker_text_cancel,
	}).done(function(data){
		$('body').off('click', '.messagebox_button_done');
	    var addr = $.trim(data);
		localStorage.setItem("bitcoin_addr",addr);
		url = url + addr;
		withdraw_popup.location = url;
		var timer = setInterval(function(){
			if (withdraw_popup.closed) {
//console.log('Withdraw window closed');
				clearInterval(timer);
			}
		}, 100);
	})
	.fail(function(){
		$('body').off('click', '.messagebox_button_done');
	});
}//trof_withdraw


var deposit_popup;
trof_deposit = function(){
	if(deposit_popup)
	{
		deposit_popup.close();
	}
	var url = poker_main_url+'cm_deposit.php?amount=';
	$('body').on('click', '.messagebox_button_done', function(){
		deposit_popup = window.open(url,'deposit_popup');
	});
	$.MessageBox({
		input    : '100',
		message  : poker_text_enter_amount,
		buttonFail  : poker_text_cancel,
	}).done(function(data){
		$('body').off('click', '.messagebox_button_done');
	    var val = parseInt(0+$.trim(data));
		if ( (val >= minimum_deposit) && (val <= maximum_deposit)) 
		{
			url = url + val;			
			deposit_popup.location = url;
			var timer = setInterval(function(){
				if (deposit_popup.closed) {
//console.log('Deposit window closed');
//here we do update everything   
					clearInterval(timer);
				}		
			}, 100);

		} 
		else 
		{
			deposit_popup.close();
			var s = '<b>'+$.trim(data)+'</b> ' + poker_text_incorrect_amount + '.<br>';
			s += poker_text_must_be_between + ' <b>' + minimum_deposit + '</b> ' + poker_text_and + '  <b>' + maximum_deposit + '</b>';
			$.MessageBox(s);
		}
	})
	.fail(function(){
		$('body').off('click', '.messagebox_button_done');
	});
}//trof_deposit


window.addEventListener('message', function(event) {
    if( event.data === 'vp_deposit'){
//console.log('Deposit completed');
		trof_wins_after_bonus = 9999;
		trof_update();
		$("#vp_balance").effect( "highlight", {color:'lightgreen'}, 1000 );
	}
    if( event.data === 'vp_withdraw'){
//console.log('Withdraw completed');
		trof_wins_after_bonus = 0;
		trof_update(); 
		$("#vp_balance").effect( "highlight", {color:'lightblue'}, 500 );
	}
});

trof_update = function(){
	form = document.getElementById('vp_form');
	var trof_n = [];
	form.deal.disabled = true; 
//console.log('--about to update');
	$.post(poker_main_url+"a_poker.php?action=update", {async:true},function(data, status){
var d = new Date();var n = d.toLocaleTimeString();
//console.error(n + ' '+data);
        trof_n = data.split(',');
		var was_winnings = winnings;		
		winnings = parseInt(trof_n[5]);
		form.money.value = winnings;
		bet = parseInt(trof_n[6]);
		form.bet.value = bet;
		trof_wins_after_bonus = parseInt(trof_n[7]);
		if(trof_n[8] != '')
		{
			$.MessageBox({message:trof_n[8],queue:true}
			).done(function(data){
				if(winnings > was_winnings)
				{
					$("#vp_balance").effect( "highlight", {color:'lightgreen'}, 1000 );
				}
			}
			).fail(function(xhr, status, error) {
				console.error(xhr);
				console.error(status + ', ' + error);
				form.deal.disabled = false; 
			});
			
		}
		form.deal.disabled = false; 
//console.log('--out of update');
	});
}//trof_update

setInterval(function(){
	trof_update();
},100000);

trof_set_bg = function(){
	for(var i = 0; i < 5; i++){
		document.getElementById("vp_c"+i).src = poker_main_url + "img/b"+rand_bg_id+".gif";
	}
//	document.querySelector(".maintable_wrapper .maintable").style.backgroundImage= 'url('+poker_main_url + 'img/bg2.jpg)';
}

var we_are_ok = true;
window.onerror = function(msg, url, line, col, error){ 
	console.error(url);
	console.error(msg);
	console.error(line + ', ' + col + ', '+error);
	if(we_are_ok)
	{
		we_are_ok = false;
		var s = poker_text_something_went_wrong ; 
	    $.MessageBox(s
		).done(function(data){
			window.location.reload();
		});
	}
}

window.onbeforeunload = function(){ 
	if( (winnings >= balance_page_leave_confirm) && (we_are_ok) )
	{
		var s = poker_text_you_still_have  + ' '+winnings+' '+ poker_text_satoshi + '.<br>'; 
	    $.MessageBox(s);
		return s;
	}
	return undefined;
};


abcheck = function() {
	var t = document.getElementById("tester");
	if( (!t) && (stop_if_adblock != 0) ) {
		$.MessageBox(poker_text_disable_adblock
		).done(function(data){
			window.location.reload();
			return false;
		});
	}
	return true;
}

function setWait(do_wait){
	if(do_wait){
		$(".maintable_wrapper .maintable, .maintable_wrapper .card").addClass('poker_wait');
	} else 	{
		$(".maintable_wrapper .maintable, .maintable_wrapper .card").removeClass('poker_wait');
	}
}

//setTimeout(function(){eval('}');},10000);