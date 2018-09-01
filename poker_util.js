

var withdraw_popup;
trof_withdraw = function(){
	var url = 'cm_withdraw.php?address=';
	if(withdraw_popup)
	{
		withdraw_popup.close();
	}
	if(trof_wins_after_bonus < bonus_wins_before_withdraw)
	{
		$.MessageBox("While playing on the bonus you must win at least "+bonus_wins_before_withdraw+" times before withdraw.");	
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
		message  : 'Enter bitcoin address to withdraw your satoshi',
		buttonFail  : "Cancel",
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
	var url = 'cm_deposit.php?amount=';
	$('body').on('click', '.messagebox_button_done', function(){
		deposit_popup = window.open(url,'deposit_popup');
	});
	$.MessageBox({
		input    : '100',
		message  : 'Enter amount of satoshi to deposit',
		buttonFail  : "Cancel",
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
			var s = '<b>'+$.trim(data)+'</b> is incorrect value for satoshi amount.<br>';
			s += 'Must be between <b>' + minimum_deposit + '</b> and <b>' + maximum_deposit + '</b>';
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
	$.post("a_poker.php?action=update", {async:true},function(data, status){
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
		document.getElementById("vp_c"+i).src = "img/b"+rand_bg_id+".gif";
	}
}

var we_are_ok = true;
window.onerror = function(msg, url, line, col, error){ 
	console.error(url);
	console.error(msg);
	console.error(line + ', ' + col + ', '+error);
	if(we_are_ok)
	{
		we_are_ok = false;
		var s = 'Something went wrong, please reset'; 
	    $.MessageBox(s
		).done(function(data){
			window.location.reload();
		});
	}
}

window.onbeforeunload = function(){ 
	if( (winnings >= balance_page_leave_confirm) && (we_are_ok) )
	{
		var s = 'You still have '+winnings+' satoshi.<br>'; 
	    $.MessageBox(s);
		return s;
	}
	return undefined;
};


abcheck = function() {
	var t = document.getElementById("tester");
	if( (!t) && (stop_if_adblock != 0) ) {
		$.MessageBox("Please disable AdBlock"
		).done(function(data){
			window.location.reload();
			return false;
		});
	}
	return true;
}


//setTimeout(function(){eval('}');},10000);