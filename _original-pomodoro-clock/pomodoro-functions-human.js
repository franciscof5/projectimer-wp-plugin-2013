//Obfuscado
//2014-03-08
//Configurantion vars, will be seted by user in future
{
	var pomodoros_to_big_rest=4;

	var pomodoroTime = 1500;
	var restTime = 300;
	var bigRestTime = 1800;
	var intervalMiliseconds = 1000;

	//Dynamic clock var
	//var is_interrupt_button;
	var m1;
	var m2;
	var m3;
	var m4;
	var m1_current = 9;
	var m2_current = 9;
	var s1_current = 9;
	var s2_current= 9;

	//Pomodoro session control vars
	var pomodoro_actual = 1;
	var is_pomodoro = true;
	var secondsRemaining = 0;//pomodoroTime;
	var interval_clock=false;
}
//With that line mootools can use the selector ($) and jQuery use the selector (jQuery), without conflict
jQuery.noConflict();

//Check if has running pomodoros
//function check_for_running_pomodoro () {};

jQuery(document).ready(function () {
	jQuery("#title_box").change(function() {
		update_pomodoro_clipboard();
	})
	jQuery("#description_box").change(function() {
		update_pomodoro_clipboard();
	})
	jQuery("#tags_box").change(function() {
		update_pomodoro_clipboard();
	})
	load_pomodoro_clipboard();
});

function load_pomodoro_clipboard () {
	////procura se já tiver algum post published
	change_status("Estou baixando os dados da sua conta...");	
	var data = {
		action: 'load_pomo'
	};
	jQuery.post(ajaxurl, data, function(response) {
		rex = response.split("$^$ ");
		change_status(rex[0]);
		
		title_box.value = rex[1];
		tags_box.value  = rex[2];
		description_box.value = rex[3];
		data_box.value = rex[4];
		status_box.value = rex[5];
		post_id_box.value = rex[6];
		secundos = rex[7].slice(0, -1);
		change_status(secundos);
		//alert("secundos");
		//secundos = secundos.substring(rex[5], str.length - 1);
		
		if(status_box.value=="pending") {
			if(secundos) {
				//pomodoroTime = 18000;
				if(secundos>pomodoroTime) {
					secondsRemaining = pomodoroTime;
					change_status("Você perdeu um pomodoro na última sessão. Você iniciou esse pomodoro há " + Math.round(((secundos/60)/60)) + " horas.");	
				} else {
					secondsRemaining = pomodoroTime - secundos;
					//alert(secondsRemaining + " d " + pomodoroTime);
					change_status("Você fechou o navegador com o pomodoro rolando, já se passaram " + Math.round(secundos/60) + " minutos");	
					
					//alert(secondsRemaining);
					start_clock();
				}
				//alert(secondsRemaining);
			}
		} else if (status_box.value=="draft") {
			secondsRemaining = pomodoroTime;
			change_status("Carreguei o seu pomodoro ativo, clique em FOCAR para iniciar a contagem. Última sessão há " +  Math.round(((secundos/60)/60)) + " horas.");	
		}
		document.getElementById("secondsRemaining_box").value=secondsRemaining + "s";
		//Functions to make the effect of flip on countdown_clock
		//change_status(response);
		//alert(secundos);
		//secondsRemaining -= secundos;
	});
	if(secondsRemaining==0)
	secondsRemaining = pomodoroTime;
	convertSeconds(secondsRemaining);
	flip_number(true);
	//se tiver um pomodoro rolando não pode alterar a data do rascunho
	/*if(secondsRemaining!=pomodoroTime) {
		alert("POMODORO ROLANDO"+secondsRemaining);
	}*/
	//alert(secundos);
}

function update_pomodoro_clipboard (post_stts) {
	//if(!title_box.value==undefined) { nao precisa porque só chama quando alterar o título
	change_status("Salvando modificações feitas no pomodoro ativo...");
	var postcat=getRadioCheckedValue("cat_vl");
	var privornot=getRadioCheckedValue("priv_vl");

	var data = {
		action: 'update_pomo',
		post_titulo: title_box.value,
		post_descri: description_box.value,
		post_tags: tags_box.value,
		post_cat: postcat,
		//post_id: post_id_box.value,
		post_data: data_box.value,
		post_priv: privornot
	}
	if(interval_clock) {
		//alert(interval_clock);
		data["ignora_data"]=true;
	}
	
	if(post_stts) {
		data["post_status"] = post_stts;
	} 
	
	jQuery.post(ajaxurl, data, function(response) {
		rex = response.split("$^$ ");
		change_status("Os dados foram salvados " + rex[0]);
		status_box.value = rex[1];
		data_box.value = rex[2].slice(0, -1);
		//title_box.value = rex[0];
		//tags_box.value  = rex[1];
		//description_box.value = rex[2];
	});
	/*} else {
		if(!$primeiroAviso) {
			$primeiroAviso=true;
			change_status("Você precisa colocar um título na tarefa, antes de salvar")
		} else {
			alert("Você precisa colocar um título na tarefa, antes de salvar")
		}
	}*/
}

//There only one button at the page, all the actions (trigglers) start here
function action_button() {
	if(interval_clock) {
		//The user clicked on Interrupt button 	-> Check if the timmer (countdown_clock()) are running
		pomodoro_completed_sound.play();
		update_pomodoro_clipboard();
		interrupt();
	} else {
		//The user clicked on Pomodoro or Rest button
		start_clock();
		update_pomodoro_clipboard();//Isso sim é a verdadeira gambiarra, aplicada ao nível extremo, como não salva a data quando usa "pending", então salva um rascunho com a data de agora e altera para pending que não mexe na data
		if(is_pomodoro) {
			
			update_pomodoro_clipboard("pending");
			change_status(txt_started_countdown);
		}
		else {
			change_status(txt_normalrest_countdown);
		}
		//The end of the big rest, the indicators light has to reset
		if(pomodoro_actual==1)
		turn_off_pomodoros_indicators();
	}
}

//Make the code more legible
function start_clock() {
	active_sound.play();
	//post_status="future;"
	//Chage button to "interrupt"
	//is_interrupt_button=true;
	change_button(textInterrupt, "#0F0F0F");
	//
	interval_clock = setInterval('countdown_clock()', intervalMiliseconds);
}

//Function called every second when pomodoros are running
function countdown_clock (){
	//Everty second of pomodoros running these functions are called
	secondsRemaining--;
	//Function user to convert number, like 140, into clock time, like 2:20
	convertSeconds(secondsRemaining);
	//Functions to make the effect of flip on countdown_clock
	flip_number();
	//Test the end of the time
	if(secondsRemaining==0)
	complete();
	//Change the title to time
	changeTitle();
	//
	document.getElementById("secondsRemaining_box").value=secondsRemaining + "s";

}

function getRadioCheckedValue(radio_name){
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

//This is the reason of all the code, the time when user complete a pomodoro, these satisfaction!
function complete() {
	//is_interrupt_button = false;
	pomodoro_completed_sound.play();
	update_pomodoro_clipboard();
	stop_clock();	
	changeTitle("Pomodoro completado!");
	if(is_pomodoro) {
		turn_on_pomodoro_indicator("pomoindi"+pomodoro_actual);
		savepomo();
		is_pomodoro = false;
		if(pomodoro_actual==pomodoros_to_big_rest) {
			//big rest
			pomodoro_actual=1;
			change_button(textBigRest, "#0F0");
			change_status(txt_bigrest_countdown);
			secondsRemaining=bigRestTime;
			changeTitle("GRANDE DESCANSO");
		} else {
			//normal rest
			pomodoro_actual++;
			change_button(textRest, "#990000");
			change_status(txt_normalrest_countdown);
			secondsRemaining=restTime;
			changeTitle("Hora do intervalo");
		}
		
	} else {
		change_button(textPomodoro, "#063");
		change_status(txt_completed_rest);
		is_pomodoro=true;
		secondsRemaining=pomodoroTime;
		changeTitle("Pomodoro completado!");
	}
	
}

//Change the <title> of the document
function changeTitle (novotity) {
	if(!novotity) {
		var task_name = document.getElementById('title_box');
		document.title = Math.round(m1)+""+Math.round(m2)+":"+s1+""+s2 + " - " + task_name.value;
	} else {
		document.title = novotity;
	}
}

//Just stop de contdown_clock function at certains moments
function stop_clock() {
	window.clearInterval(interval_clock);
	interval_clock=false;
	update_pomodoro_clipboard();
	//alert(is_pomodoro);
	//Functions to make the effect of flip on countdown_clock
	if(is_pomodoro) {
		convertSeconds(restTime);
	} else {
		convertSeconds(pomodoroTime);
	}
	flip_number(true);
	document.getElementById("secondsRemaining_box").value=pomodoroTime+"s";//pomodoroTime
	//is_interrupt_button = false;
	
}

//Function to show status warnings at bottom of the clock
function change_status(txt) {
	document.getElementById("div_status").innerHTML=txt;
}

//Function to change button text and color
function change_button (valueset, colorset) {
	var button = $("action_button_id");
	button.value=valueset;
	button.set('morph', {duration: 2000});
	button.morph({/*'border': '2px solid #F00',*/'background-color': colorset});
}

//
function interrupt() {
	//pomodoro_completed_sound.play();
	//document.getElementById("secondsRemaining_box").value = "";
	stop_clock();
	change_status(txt_interrupted_countdowns);
	//convertSeconds(0);
	//flip_number();
	change_button(textPomodoro, "#063");
	secondsRemaining=0;
	secondsRemaining = pomodoroTime;
	//is_interrupt_button=false;
	//if(!is_pomodoro)is_pomodoro=true;
	if(!is_pomodoro)is_pomodoro=true;
}

//Auxiliar function to countdown_clock() function
function convertSeconds(secs) {
	minutes=secs/60;
	
	if(minutes>10) {
		someValueString = '' + minutes;
		someValueParts = someValueString.split('');
		m1 = parseFloat(someValueParts[0]);
		m2 = parseFloat(someValueParts[1]);
	} else {
		m1 = parseFloat(0);
		m2 = parseFloat(minutes);
	}
	//seconds%=secs/60;
	if(secs%60!=0) {
		seconds=secs%60;
		otherValueString = '' + seconds;
		otherValueParts = otherValueString.split('');
		if(seconds>10) {
			s1 = parseFloat(otherValueParts[0]);
			s2 = parseFloat(otherValueParts[1]);
		} else {
			s1=0;
			s2=parseFloat(otherValueParts[0]);
		}
	} else {
		s1=0;
		s2=0;
	}
	//alert(m1+""+m2+":"+s1+""+s2);
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

//Functions to make the effect on the clock
function flip_number(force) {
	/*if(force) {
		var m1_current = 9;
		var m2_current = 9;
		var s1_current = 9;
		var s2_current = 9;
	}*/
	
	if( m2 != m2_current || force){
		flip('minutesUpRight', 'minutesDownRight', m2, 'http://pomodoros.com.br/wp-content/themes/darwin-buddypress-buddypack/pomodoro/Double/Up/Right/', 'http://pomodoros.com.br/wp-content/themes/darwin-buddypress-buddypack/pomodoro/Double/Down/Right/');
		m2_current = m2;
		
		flip('minutesUpLeft', 'minutesDownLeft', m1, 'http://pomodoros.com.br/wp-content/themes/darwin-buddypress-buddypack/pomodoro/Double/Up/Left/', 'http://pomodoros.com.br/wp-content/themes/darwin-buddypress-buddypack/pomodoro/Double/Down/Left/');
		m1_current = m1;
	}
	if (s2 != s2_current || force){
		flip('secondsUpRight', 'secondsDownRight', s2, 'http://pomodoros.com.br/wp-content/themes/darwin-buddypress-buddypack/pomodoro/Double/Up/Right/', 'http://pomodoros.com.br/wp-content/themes/darwin-buddypress-buddypack/pomodoro/Double/Down/Right/');
		s2_current = s2;
		
		flip('secondsUpLeft', 'secondsDownLeft', s1, 'http://pomodoros.com.br/wp-content/themes/darwin-buddypress-buddypack/pomodoro/Double/Up/Left/', 'http://pomodoros.com.br/wp-content/themes/darwin-buddypress-buddypack/pomodoro/Double/Down/Left/');
		s1_current = s1;
	}
}

function flip (upperId, lowerId, changeNumber, pathUpper, pathLower){
	var upperBackId = upperId+"Back";
	$(upperId).src = $(upperBackId).src;
	$(upperId).setStyle("height", "64px");
	$(upperId).setStyle("visibility", "visible");
	$(upperBackId).src = pathUpper+parseInt(changeNumber)+".png";
	
	$(lowerId).src = pathLower+parseInt(changeNumber)+".png";
	$(lowerId).setStyle("height", "0px");
	$(lowerId).setStyle("visibility", "visible");

	var flipUpper = new Fx.Tween(upperId, {duration: 200, transition: Fx.Transitions.Sine.easeInOut});
	flipUpper.addEvents({
		'complete': function(){
			var flipLower = new Fx.Tween(lowerId, {duration: 200, transition: Fx.Transitions.Sine.easeInOut});
				flipLower.addEvents({
					'complete': function(){	
						lowerBackId = lowerId+"Back";
						$(lowerBackId).src = $(lowerId).src;
						$(lowerId).setStyle("visibility", "hidden");
						$(upperId).setStyle("visibility", "hidden");
					}				});					
				flipLower.start('height', 64);
				
		}
	});
	flipUpper.start('height', 0);
}

//The real life at pomodoros: jQuery calling php function on functions.php
function savepomo () {
	change_status(txt_salving_progress);	
	
	var postcat=getRadioCheckedValue("cat_vl");
	var privornot=getRadioCheckedValue("priv_vl");
	
	//TODO: verificar se o último post publicado já faz mais que pomodoroTime (25min), evitando flood e 2 navegadores abertos
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
		change_status(txt_save_success);
		else
		change_status(txt_save_error);
		/*Append the fresh completed pomodoro at the end of the list, simulating the data
		var d=new Date();
		data = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+d.getUTCHours()+":"+d.getUTCMinutes()+":"+d.getUTCSeconds();//new Date(year, month, day, hours, minutes, seconds);
		if(response[0]=="1")
		jQuery("#points_completed").append('<li>'+data+" -> "+description.value+'</li>');*/
	});
}

//Load e Save model function
function save_model () {
	change_status(txt_salving_model);
	var data = {
		action: 'save_modelnow',
		post_titulo: title_box.value,
		post_descri: description_box.value,
		post_tags: tags_box.value
	};
	jQuery.post(ajaxurl, data, function(response) {
		if(response) {
			if(response==0) {
				change_status(txt_salving_model_task_null);
			} else {
				var sessao_atual=response;
				//primeiro salva o post, para depois pegar o id do mesmo
				jQuery("#contem-modelos").append('<ul id="modelo-carregado-'+sessao_atual+'"><li><input type="text" value="'+title_box.value+'" disabled="disabled" id="bxtitle'+sessao_atual+'"><br /><input type="text" value="'+description_box.value+'" disabled="disabled" id="bxcontent'+sessao_atual+'"><br /><input type="text" value="'+tags_box.value+'" disabled="disabled" id="bxtag'+sessao_atual+'"><p><input type="button" value="usar modelo" onclick="load_model('+sessao_atual+')"><input type="button" value="apaga" onclick="delete_model('+sessao_atual+')"></p></li></ul>');
				/*jQuery("#botao-salvar-modelo").val("sessão salvada com sucesso");
				jQuery("#botao-salvar-modelo").attr('disabled', 'disabled');*/
				document.getElementById("bxcontent"+sessao_atual).focus();
				change_status(txt_salving_model_success);
			}
		}
		else
		change_status(txt_save_error);
	});
}

function delete_model(qualmodelo) {
	//PHP deletar post qualmodelo
	change_status(txt_deleting_model);
	var data = {
		action: 'save_modelnow',
		post_para_deletar: qualmodelo
	};
	jQuery.post(ajaxurl, data, function(response) {
		if(response) {
			change_status(txt_deleting_model_sucess);
			jQuery("#modelo-carregado-"+qualmodelo).remove();
		} else {
			change_status(txt_save_error);
		}
	});
}

function load_model(qualmodelo) {
	document.getElementById("title_box").value = document.getElementById("bxtitle"+qualmodelo).value;
	document.getElementById("description_box").value = document.getElementById("bxcontent"+qualmodelo).value;
	
	if(document.getElementById("bxtag"+qualmodelo))
	document.getElementById("tags_box").value = document.getElementById("bxtag"+qualmodelo).value;
	else
	document.getElementById("tags_box").value = "";
	
	document.getElementById("action_button_id").focus();
	change_status(txt_loading_model);
}

//Sound configuration
soundManager.url = 'http://localhost/pomodoros.com.br/pomodoros/wp-content/themes/sistema-pomodoro/pomodoro/soundmanager2.swf';
soundManager.onready(function() {
	// Ready to use; soundManager.createSound() etc. can now be called.
	active_sound = soundManager.createSound({id: 'mySound2',url: 'http://localhost/pomodoros.com.br/pomodoros/wp-content/themes/sistema-pomodoro/pomodoro/sounds/crank-2.mp3'});
	pomodoro_completed_sound = soundManager.createSound({id:'mySound3',url: 'http://localhost/pomodoros.com.br/pomodoros/wp-content/themes/sistema-pomodoro/pomodoro/sounds/telephone-ring-1.mp3'});
	session_reseted_sound = soundManager.createSound({id:'mySound4',url: 'http://localhost/pomodoros.com.br/pomodoros/wp-content/themes/sistema-pomodoro/pomodoro/sounds/magic-chime-02.mp3'});
});darwin-buddypress-buddypack/pom
soundManager.onerror = function() {alert(txt_sound_error+"...");}

//Project Management (maybe that snippet deserves a exclusive file)
darwin-buddypress-buddypack/pom