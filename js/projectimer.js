//Configurantion vars, will be seted by user in future
var pomodoros_to_big_rest=4;

var focusTime = 60*11;
var restTime = 60*5;
var intervalTime = 60*20;

//Session control vars
var actualCicle = 1;
var is_focus_time = true;
var secondsRemaing = focusTime;
var is_countdown_active = false;

//Vars used for development pouposes, temporary
var canvas = document.getElementById('myCanvas');
var backgroundCheck = false;
var intervalMiliseconds = 10;

//What about declate functions up here?
function has_active_cycle(){};
function action_button(){};
function start_cycle(){};
function count(){};
function complete_cycle(){};
function stop_cycle(){};
function interrupt_cycle(){};
function update_status_message(){};
function update_canvas(){};

//Trigger, basically to check if is there another cycle runnig
//window.onload=function() {
jQuery( document ).ready(function() {
	check_activity(); //the function is not ready
	//jQuery("#down_minutes").click(function() {
	//	change_minutes(-1);
	//});
	//alert("asd");
	//alert(jQuery("#down_minutes").length + "asda");
	//jQuery('#pg_menu_content').on('click', '#btn_a', function(){
	//    console.log(this.value);
	//});
	//start_cycle();
	//window.onload=function(){	var interval=setInterval(function(){count()},0);};
});


//That function is under construction, is to solve google chorme delay on setInterval
function check_activity () {
	update_status_message("check_activity()");
	
	var data = {
		action: 'projectimer_php_check_activity',
	};

	jQuery.post(ajaxurl, data, function(response) {
		if(response)		
		update_status_message("check_activity(): " + response);
		else
		update_status_message("check_activity(): empty(): " + response);
	});
	//var div = $('#my-div');
	//var leftValue = 0;
	/*var delay = (1000 / 30);
	var now, before = new Date();
	var elapsedTime;
	is_countdown_active = setInterval(function() {
		now = new Date();
		elapsedTime = (now.getTime() - before.getTime());
		update_status_message("check_activity() + elapsedTime:" + elapsedTime + " delay:" + delay);
		if(elapsedTime > delay) {
		    //Recover the motion lost while inactive.
		    //secondsRemaing--;
		    leftValue += Math.floor(elapsedTime/delay);
		    secondsRemaing-=Math.floor(leftValue);
		    alert(leftValue);
		} else {
		    secondsRemaing--;
		    alert(dasdasd);
		}

		secondsRemaing--;
		//alert(secondsRemaing);
		//count();
		//div.css("left", leftValue);
		before = new Date();    
	}, delay);*/
}

//There only one button at the page, all the actions (trigglers) start here
function action_button() {
	update_status_message("action_button() + is_countdown_active:" + is_countdown_active);
	check_activity ();
	if(is_countdown_active) {
		//The user clicked on interrupt_cycle button -> Check if the timmer (countdown_clock()) are running
		interrupt_cycle();
	} else {
		//The user clicked on Pomodoro or Rest button
		start_cycle();
		//The end of the big rest, the indicators light has to reset
		//if(pomodoro_actual==1)
		//turn_off_pomodoros_indicators();
	}
}

//Make the code more legible
function start_cycle(timeleft) {
	update_status_message('start_cycle()');
	if(timeleft) {
		secondsRemaing=timeleft;
	}
	is_countdown_active = setInterval('count()', intervalMiliseconds);

	/*var delay = (1000 / 30);
	var now, before = new Date();
	is_countdown_active = setInterval(function() {count()}, delay);*/
	//active_sound.play();
	//Chage button to "interrupt_cycle"
	//change_button(textinterrupt_cycle, "#0F0F0F");
	//update_status_message(txt_started_countdown);
	//	
}

//Probably the most important function in these file, called to make changes on the screens //var count=function() { //is there any reason why these was wrote that way?
function count() {
	update_status_message("count() + " + secondsRemaing);
	update_action_button_label(convertSeconds(secondsRemaing));
	//varvara = intervalMiliseconds;
	secondsRemaing--;
	if(secondsRemaing==0)
	complete_cycle();
}

//
function complete_cycle() {
	update_status_message('complete_cycle()');
	//pomodoro_completed_sound.play();
	stop_cycle();
	//canvas
	//secondsRemaing=0;
	//secondsRemaing = focusTime;
	//if(!is_pomodoro)is_pomodoro=true;
	//TODO remover do desenho do ciclo o tempo cancelado

}

//Just stop de contdown_clock function at certains moments
function stop_cycle() {
	//update_status_message('stop_cycle()');
	window.clearInterval(is_countdown_active);
	is_countdown_active=false;
	//TODO Deletar draft
	//is_interrupt_cycle_button = false;
	//pomodoro_completed_sound.play();
}

//The user need to stop the cycle couting because a unexpected task surge
function interrupt_cycle() {
	update_status_message('interrupt_cycle()');
	//pomodoro_completed_sound.play();
	//update_status_message(txt_interrupt_cycleed_countdowns);
	stop_cycle();
	//convertSeconds(0);
	//flip_number();
	//change_button(textPomodoro, "#063");
	//secondsRemaing=0;
	//secondsRemaing = focusTime;
	//if(!is_pomodoro)is_pomodoro=true;
}



//Function to show status warnings at bottom of the clock
function update_status_message(txt) {
	document.getElementById("div_status").innerHTML=txt;
}
function update_action_button_label(txt) {
	document.getElementById("clock-label").innerHTML=txt;
}

//Get user custom configuration
//function loadCustomUserConfiguration {
	/*var data = {
		action: 'load_user_config'
	};
	jQuery.post(ajaxurl, data, function(response) {
		if(response) {
			//update_status_message(txt_deleting_model_sucess);
			jQuery("#modelo-carregado-"+qualmodelo).remove();
			alert(response);
		} else {
			//update_status_message(txt_save_error);
		}
	});*/
//}
/*function starttimer () {
	var data = {
		action: 'check_activity',
	};
	jQuery.post(ajaxurl, data, function(response) {
		if(response)		
		update_status_message(txt_save_success);
		else
		update_status_message(txt_save_error);
		/*Append the fresh completed pomodoro at the end of the list, simulating the data
		var d=new Date();
		data = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+d.getUTCHours()+":"+d.getUTCMinutes()+":"+d.getUTCSeconds();//new Date(year, month, day, hours, minutes, seconds);
		if(response[0]=="1")
		jQuery("#points_completed").append('<li>'+data+" -> "+description.value+'</li>');//*
	});
}*/

/* AJAX */
//Funtions to save pomodoros
function savepomo () {
	//update_status_message(txt_salving_progress);	
	var postcat=getRadioCheckedValue("cat_vl");
	var privornot=getRadioCheckedValue("priv_vl");
	var data = {
		action: 'save_progress',
		post_titulo: title_box.value,
		post_descri: description_box.value,
		post_tags: tags_box.value,
		post_cat: postcat,
		post_priv: privornot
	};
	jQuery.post(ajaxurl, data, function(response) {
		if(response)		
		update_status_message(txt_save_success);
		else
		update_status_message(txt_save_error);
		/*Append the fresh completed pomodoro at the end of the list, simulating the data
		var d=new Date();
		data = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+d.getUTCHours()+":"+d.getUTCMinutes()+":"+d.getUTCSeconds();//new Date(year, month, day, hours, minutes, seconds);
		if(response[0]=="1")
		jQuery("#points_completed").append('<li>'+data+" -> "+description.value+'</li>');*/
	});
}

function delete_model(qualmodelo) {
	//PHP deletar post qualmodelo
	//update_status_message(txt_deleting_model);
	var data = {
		action: 'save_modelnow',
		post_para_deletar: qualmodelo
	};
	jQuery.post(ajaxurl, data, function(response) {
		if(response) {
			update_status_message(txt_deleting_model_sucess);
			jQuery("#modelo-carregado-"+qualmodelo).remove();
		} else {
			update_status_message(txt_save_error);
		}
	});
}
function save_model () {
	update_status_message("save_model()");
	var data = {
		action: 'save_modelnow',
		post_titulo: title_box.value,
		post_descri: description_box.value,
		post_tags: tags_box.value
	};
	jQuery.post(ajaxurl, data, function(response) {
		if(response) {
			if(response==0) {
				update_status_message(txt_salving_model_task_null);
			} else {
				var sessao_atual=response;
				//primeiro salva o post, para depois pegar o id do mesmo
				jQuery("#contem-modelos").append('<ul id="modelo-carregado-'+sessao_atual+'"><li><input type="text" value="'+title_box.value+'" disabled="disabled" id="bxtitle'+sessao_atual+'"><br /><input type="text" value="'+description_box.value+'" disabled="disabled" id="bxcontent'+sessao_atual+'"><br /><input type="text" value="'+tags_box.value+'" disabled="disabled" id="bxtag'+sessao_atual+'"><p><input type="button" value="usar modelo" onclick="load_model('+sessao_atual+')"><input type="button" value="apaga" onclick="delete_model('+sessao_atual+')"></p></li></ul>');
				/*jQuery("#botao-salvar-modelo").val("sessão salvada com sucesso");
				jQuery("#botao-salvar-modelo").attr('disabled', 'disabled');*/
				document.getElementById("bxcontent"+sessao_atual).focus();
				update_status_message(txt_salving_model_success);
			}
		}
		else
		update_status_message(txt_save_error);
	});
}

/* AUXILIAR FUNCTION */
//Auxiliar function to countdown_clock() function
function convertSeconds(secs) {
	minutes=(secs/60).toFixed(0);
	if(minutes>9) {
		someValueString = '' + minutes;
		someValueParts = someValueString.split('');
		m1 = parseFloat(someValueParts[0]);
		m2 = parseFloat(someValueParts[1]);
	} else {
		m1 = parseFloat(0);
		m2 = parseFloat(minutes);
	}
	seconds=(secs%60).toFixed(2);
	otherValueString = '' + seconds;
	otherValueParts = otherValueString.split('');
	//console.log(secs + ":" + seconds + " | " + otherValueParts);
	if(seconds>10) {
		s1 = otherValueParts[0];
		s2 = otherValueParts[1];
	} else {
		s1=0;
		s2=otherValueParts[0];
	}
	//alert(m1+""+m2+":"+s1+""+s2);
	//console.log(m1+""+m2+":"+s1+""+s2);
	return(m1+""+m2+":"+s1+""+s2);
}

function reset_pomodoro_session() {
	//zerar_pomodoro()
	interrupt();
	pomodoro_actual=1;
	session_reseted_sound.play();
	turn_off_pomodoros_indicators();
	//changeTitle("Sessão de pomodoros reiniciada...");
	change_status("Pronto, sessão reiniciada. O sistema está pronto para uma nova contagem!");
}

//Function to "light" one pomodoro
function turn_on_pomodoro_indicator (pomo) {var pomo = $(pomo);pomo.set('morph', {duration: 2000});pomo.morph({'background-position': '-30px','background-color': '#FFF'});}

//Function to restart the pomodoros
function turn_off_pomodoros_indicators () {
	var pomo1 = $("pomoindi1");var pomo2 = $("pomoindi2");var pomo3 = $("pomoindi3");var pomo4 = $("pomoindi4");
	pomo1.set('morph', {duration: 4000});pomo2.set('morph', {duration: 2000});pomo3.set('morph', {duration: 3000});pomo4.set('morph', {duration: 1200});
	pomo1.morph({'background-position': '0px','background-color': '#EEEEEE'});pomo2.morph({'background-position': '0px','background-color': '#EEEEEE'});pomo3.morph({'background-position': '0px','background-color': '#EEEEEE'});pomo4.morph({'background-position': '0px','background-color': '#EEEEEE'});
}	



/*
soundManager.url = templateDir+'/pomodoro/soundmanager2.swf';
soundManager.onready(function() {
	// Ready to use; soundManager.createSound() etc. can now be called.
	active_sound = soundManager.createSound({id: 'mySound2',url: templateDir+'/pomodoro/sounds/crank-2.mp3',});
	pomodoro_completed_sound = soundManager.createSound({id:'mySound3',url: templateDir+'/pomodoro/sounds/telephone-ring-1.mp3',});
});			
*/
