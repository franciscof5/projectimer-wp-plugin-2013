/*//Configurantion vars, will be seted by user in future
var pomodoros_to_big_rest=4;

var user_focus_time = 60*25;
var restTime = 60*5;
var intervalTime = 60*20;

//Session control vars
var actualCicle = 1;
var is_focus_time = true;
var secondsRemaing = user_focus_time;
var is_countdown_active = false;
var action_button;

//Vars used for development pouposes, temporary
var canvas = document.getElementById('myCanvas');
var backgroundCheck = false;
var intervalMiliseconds = 10;
*/

//What about declate functions up here?
//function load_user_settings(){};
function check_activity(){};
function action_button_press(){};
function start_count(timeleft){};
function count(){};
function complete_count(){};
function stop_count(){};
function interrupt_count(){};
function update_status_message(txt){};
function update_action_button_label(txt){};
/* AUXILIAR FUNCTION */
function convertSeconds(secs) {};
function reset_session() {};
function turn_on_cycle_indicator(pomo){};
function turn_off_cycle_indicator(){};
/* AJAX */

//function update_canvas(){};

//jQuery( document ).ready(function($) {
{
	//action_button.click(action_button_press);
	//
	//load_user_settings();
	//CHECK FOR USER SETTINGS IS LOADED
	
	//start_count();
	
	
	/* WORDPRESS HEARTBEAT END */

	/*$('input[type=radio][name=cat_vl]').change(function() {
        if (this.value == 26) {
            paint_site("#B2B200", 2000);
        } else if (this.value == 27) {
            paint_site("#0052A3", 2000);
        } else if (this.value == 28) {
            paint_site("#093", 2000);
        } else if (this.value == "") {
            paint_site("#666", 2000);
        }
    });
    function paint_site(newcolor, time) {
    	//if(!animated) {
    		//$("#header").css("background-color", newcolor);
    	//} else {
    		$("#header").animate({"background-color": newcolor}, time);
    	//}
		update_user_meta("bgcolor", newcolor);
		//.css("background-color", color);
	}
	/*
	//
	var element = $("#header");
	//
	function loop () {
		element.animate({ "background-color": "#066" }, 1000)
	           .animate({ "background-color": "#023" }, 1000, loop);
		//.hide("slow", arguments.callee);
		//}());
	}
	loop();
	*/
//});
}
	//console.log("projectimer.js ready()");
	//Starts jQuery vars
	//action_button = $('#action_button');
	//There only one button at the page, all the actions (trigglers) start here

function action_button_press() {
	console.log("action_button_press()");
	console.log("is_countdown_active:" + is_countdown_active);
	//update_status_message("action_button_press() + is_countdown_active:" + is_countdown_active);
	check_activity ();
	if(is_countdown_active) {
		//The user clicked on action button while focustin
		interrupt_count();
		//secondsRemaing = user_focus_time*60; ja tem em stop_count() interrupt_count()
		//update_user_meta("secondsRemaing", user_focus_time);
		update_action_button_label(convertSeconds(secondsRemaing));
		update_cycle_status(user_active_cycle_id, "trash");
		add_user_activitie("stopped timer");
		alertify.log(user_first_name + " stopped timer");
	} else {
		//The user was not focusing and just started timer
		//update_user_meta("start_time", "now");
		add_user_activitie("started timer");
		schedule_cycle(user_focus_time);
		
	}
}

//TODO: solve google chorme delay on setInterval

//Trigger, basically to check if is there another cycle runnig
function check_activity () {	
	/*console.log("check_activity()");
	update_status_message("Checking for recent user activity...");
	var data = {
		action: 'projectimer_php_check_activity',
	};
	jQuery.post(ajaxurl, data, function(response) {
		if(response) {
			update_status_message(response);
			action_button.prop( "disabled", false );
			console.log("check_activity(response): " + response);	
		} else {
			update_status_message("Failed to load user settings, try reload you browser...");
			console.log("check_activity(response): empty(): " + response);
		}
	});*/
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

//Make the code more legible
function start_count(timeleft) {
	console.log('start_count(timeleft):' + timeleft);
	
	if(timeleft) {
		secondsRemaing=timeleft;
	} else {
		secondsRemaing=user_focus_time*60;
	}
	is_countdown_active = setInterval('count()', 1000);
	/*var delay = (1000 / 30);
	var now, before = new Date();
	is_countdown_active = setInterval(function() {count()}, delay);*/
	//active_sound.play();
	//Chage button to "interrupt_count"
	//change_button(textinterrupt_count, "#0F0F0F");
	//update_status_message(txt_started_countdown);
	//	
}

//Probably the most important function in these file, called to make changes on the screens //var count=function() { //is there any reason why these was wrote that way?
function count() {
	// qupdate_status_message("count() + " + secondsRemaing);
	update_action_button_label(convertSeconds(secondsRemaing));
	//varvara = intervalMiliseconds;
	secondsRemaing--;
	if(secondsRemaing==0)
	complete_count();
}

//
function complete_count() {
	console.log("complete_count()");
	update_status_message('complete_count()');
	update_cycle_status(user_active_cycle_id, "publish");
	//pomodoro_completed_sound.play();
	stop_count();
	//canvas
	//secondsRemaing=0;
	//secondsRemaing = user_focus_time;
	//if(!is_pomodoro)is_pomodoro=true;
	//TODO remover do desenho do ciclo o tempo cancelado

}

//Just stop de contdown_clock function at certains moments
function stop_count() {
	console.log("stop_count()");
	window.clearInterval(is_countdown_active);
	is_countdown_active=false;
	secondsRemaing = user_focus_time*60;
	update_user_meta("secondsRemaing", user_focus_time);
	//TODO Deletar draft
	//is_interrupt_count_button = false;
	//pomodoro_completed_sound.play();
}

//The user need to stop the cycle couting because a unexpected task surge
function interrupt_count() {
	console.log('interrupt_count()');
	//pomodoro_completed_sound.play();
	//update_status_message(txt_interrupt_counted_countdowns);

	stop_count();
	//convertSeconds(0);
	//flip_number();
	//change_button(textPomodoro, "#063");
	//secondsRemaing=0;
	//secondsRemaing = user_focus_time;
	//if(!is_pomodoro)is_pomodoro=true;
}

//Function to show status warnings at bottom of the clock
function update_status_message(txt) {
	document.getElementById("div_status").innerHTML=txt;
}

//
function update_action_button_label(txt) {
	document.getElementById("clock-label").innerHTML=txt;
}

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
		$("#points_completed").append('<li>'+data+" -> "+description.value+'</li>');//*
	});
}*/

/* AUXILIAR FUNCTION */
//Auxiliar function to countdown_clock() function
function convertSeconds(secs) {
	minutes=Math.floor(secs/60);
	//console.log("convertSeconds(minutes)"+minutes);
	if(minutes>9) {
		someValueString = '' + minutes;
		someValueParts = someValueString.split('');
		m1 = parseInt(someValueParts[0]);
		m2 = parseInt(someValueParts[1]);
	} else {
		m1 = parseInt(0);
		m2 = parseInt(minutes);
	}
	seconds=Math.round(secs%60);
	otherValueString = '' + seconds;
	otherValueParts = otherValueString.split('');
	//console.log(secs + ":" + seconds + " | " + otherValueParts);
	if(seconds>9) {
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

function reset_session() {
	console.log("action_button_press()");
	//zerar_pomodoro()
	interrupt();
	pomodoro_actual=1;
	session_reseted_sound.play();
	turn_off_pomodoros_indicators();
	//changeTitle("Sessão de pomodoros reiniciada...");
	change_status("Pronto, sessão reiniciada. O sistema está pronto para uma nova contagem!");
}

//Function to "light" one pomodoro
function turn_on_cycle_indicator (pomo) {
	var pomo = $(pomo);
	pomo.set('morph', {duration: 2000});
	pomo.morph({'background-position': '-30px','background-color': '#FFF'});
}

//Function to restart the pomodoros
function turn_off_cycle_indicators () {
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

/* AJAX */

//accessory function
function getRadioCheckedValue(radio_name){
	console.log("getRadioCheckedValue()");
   var oRadio = document.forms["pomopainel"].elements[radio_name];
	//alert(oRadio.length);
   for(var i = 0; i < oRadio.length; i++)
   {
      if(oRadio[i].checked)
      {
         return oRadio[i].value;
      }
   }
   return '';
}

function delete_model(qualmodelo) {
	console.log("delete_model()");
	//PHP deletar post qualmodelo
	//update_status_message(txt_deleting_model);
	var data = {
		action: 'save_modelnow',
		post_para_deletar: qualmodelo
	};
	jQuery.post(ajaxurl, data, function(response) {
		if(response) {
			update_status_message(txt_deleting_model_sucess);
			$("#modelo-carregado-"+qualmodelo).remove();
		} else {
			update_status_message(txt_save_error);
		}
	});
}

function save_model () {
	console.log("save_model()");
	//update_status_message("save_model()");
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
				$("#contem-modelos").append('<ul id="modelo-carregado-'+sessao_atual+'"><li><input type="text" value="'+title_box.value+'" disabled="disabled" id="bxtitle'+sessao_atual+'"><br /><input type="text" value="'+description_box.value+'" disabled="disabled" id="bxcontent'+sessao_atual+'"><br /><input type="text" value="'+tags_box.value+'" disabled="disabled" id="bxtag'+sessao_atual+'"><p><input type="button" value="usar modelo" onclick="load_model('+sessao_atual+')"><input type="button" value="apaga" onclick="delete_model('+sessao_atual+')"></p></li></ul>');
				/*$("#botao-salvar-modelo").val("sessão salvada com sucesso");
				$("#botao-salvar-modelo").attr('disabled', 'disabled');*/
				document.getElementById("bxcontent"+sessao_atual).focus();
				update_status_message(txt_salving_model_success);
			}
		}
		else
		update_status_message(txt_save_error);
	});
}