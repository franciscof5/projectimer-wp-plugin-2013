//Configurantion vars, will be seted by user in future

//var restTime = 60*5;
//var intervalTime = 60*20;

//Session control vars
//var is_focus_time = true;
//var secondsRemaing = 0;

//Vars used for development pouposes, temporary
//var canvas = document.getElementById('myCanvas');
var backgroundCheck = false;
var intervalMiliseconds = 10;
var user_first_name;
var user_last_name;
var user_focus_time;
var user_rest_time;
var user_active_cycle_id;
var user_active_cycle_title;
var	secondsRemaing;
var	drafted_cycle_id;

//
var actualCycle = 1;
var pomodoros_to_big_rest=4;

var is_countdown_active = false;
var action_button;


jQuery( document ).ready(function($) {
	//
	console.log("projectimer-user-settings.js ready()");
	//
	load_user_settings();
	//
	load_task();
});

function load_task() {
	console.log("load_task()");
	jQuery('#title_box').val("Asd");
	jQuery('#description_box').val("Asd2");
	jQuery('#tags_box').val("Asd3");
}

function after_load_user_settings () {
	if(jQuery('#action_button').length) {
		//User is on FOCUS page
		action_button.prop( "disabled", false );
		//
		jQuery("#loading-message").animate({"opacity": 0}, 500, function() {jQuery("#loading-message").hide(0);});
		//the dots . .. ... animation on loading message
		window.clearInterval(loading_animated);
		//dont need, its a cycle with old settings only
		if(secondsRemaing<user_focus_time*60) {
			start_count(secondsRemaing);
			alertify.log("Timer loaded, " + user_first_name + " is focusing. Aprox. " + Math.round(secondsRemaing/60) + " minutes left." );
		} else {
		/*} /*

			//Its now checked on PHP
			else if(secondsRemaing>user_focus_time*60) {
			alertify.error("Cycle error, due date ( " + Math.round(secondsRemaing/60) + " mins) after " + user_first_name + " focus time ( " + user_focus_time + " mins )");
			//update_user_meta("secondsRemaing", user_focus_time*60);
			update_cycle_status(user_active_cycle_id, "trash");
		}  else {*/
			secondsRemaing = user_focus_time*60;
			alertify.log(user_first_name + " timer loaded. Start focus anytime you want.");
		}
		//
		if(drafted_cycle_id) {
			//var delay = alertify.get('notifier','delay');
			alertify.set({ delay: 10000 });
			//alertify.success('Current delay : ' + alertify.get('notifier','delay') + ' seconds');
			alertify.error(user_first_name + " you lost your last cycle, you must be on FOCUS page when time end.");
			alertify.set({ delay: 5000 });
		}
		//
		
		//start_count();
		jQuery('input[type=radio][name=cat_vl]').change(function() {
			var color;
	        if (this.value == "") {
	        	color = "#999";
	            paint_site(color, 2000);
	            update_user_meta("bgcolor", color);
	        } else if (this.value == 26) {
	        	color = "#B2B200";
	            paint_site(color, 2000);
	            update_user_meta("bgcolor", color);
	        } else if (this.value == 27) {
	        	color = "#1947A3";
	            paint_site(color, 2000);
	            update_user_meta("bgcolor", color);
	        } else if (this.value == 28) {
	        	color = "#093";
	            paint_site(color, 2000);
	            update_user_meta("bgcolor", color);
	        } 
	    });
	} else {
		//Not on FOCUS page
		if(secondsRemaing<user_focus_time*60)
		alertify.log("You shold be on FOCUS page, you timer is on. There is aprox. " + Math.round(secondsRemaing/60) + " minutes left. <a href='/?p=35898'>Take me to focus page</a>");
	}
}

//Get user custom configuration //must be in ready()
function load_user_settings () {
	console.log("load_user_settings()");
	//alertify.log("Connecting to server...");
	//update_status_message("Loading user settings...");
	action_button = jQuery("#action_button");
	action_button.prop( "disabled", true );
	var data = {
		action: 'projectimer_load_user_settings',
		dataType: "json",
	};

	jQuery.post(ajaxurl, data, function(response) {
		//console.log("load_user_settings(response):focus_time:" + response["focus_time"] + ", bgcolor:" + response["bgcolor"] + ", secondsRemaing:" +response["secondsRemaing"]);
		if(response) {
			//update_status_message("User settings loaded successfully");
			console.log("load_user_settings(response):");
			console.log(response);
			//
			user_first_name = response["user_first_name"];
			user_last_name = response["user_last_name"];
			user_focus_time = response["focus_time"];
			user_active_cycle_id = response["active_cycle_id"];
			user_active_cycle_title = response["user_active_cycle_title"];
			secondsRemaing = response["secondsRemaing"];
			drafted_cycle_id = response["drafted_cycle_id"];
			//
			//paint_site(response["bgcolor"], 500);
			//
			after_load_user_settings ();
			//check if is on clock page
			//if($('#action_button')) {
				/*settings loaded*/
				//start_count();
			//}
			//update_action_button_label(convertSeconds(user_focus_time));
			//check_activity();
		} else {
			console.log("Failed to load user settings, try reload you browser...");
		}
	});
}

//Used outside by projectimer.js
function update_user_meta (prop, newvalue) {
	console.log("update_user_meta(prop, newvalue):"+prop+","+newvalue);
	//update_status_message("Loading user settings...");
	var data = {
		action: 'projectimer_update_user_meta',
		property: prop,
		value: newvalue,
		//dataType: "json",
	};
	jQuery.post(ajaxurl, data, function(response) {
		console.log("update_user_meta(response):"+response);
		//console.log();
	});
}

//
function add_user_activitie (desc) {
	console.log("add_user_activitie(desc):" + desc);
	action_button.prop( "disabled", true );
	//update_status_message("Loading user settings...");
	var data = {
		action: 'projectimer_add_activie',
		description: desc,
		//dataType: "json",
	};
	action_button.prop("disabled", "disabled");
	jQuery.post(ajaxurl, data, function(response) {
		console.log("add_user_activitie(response):"+response);
		action_button.prop( "disabled", false );
	});
}

//related to above function
function update_settings_message(txt) {
	console.log("update_settings_message()");
	jQuery("#setting_message").html(txt);
	//document.getElementById("div_status").innerHTML=txt;
}

//must be sent before ready() beause will be used directly on element action property
function change_minutes(qtty) {
	console.log("change_minutes(qtty)"+qtty);
	var atual_focus_time_minutes = parseInt(jQuery("#focus_time_minutes").html());
	if ((atual_focus_time_minutes>10 && atual_focus_time_minutes<50)  || (atual_focus_time_minutes==10 && qtty>0) || (atual_focus_time_minutes==50 && qtty<0)) {
		var new_focus_time_minutes = atual_focus_time_minutes + qtty;
		jQuery("#focus_time_minutes").html(new_focus_time_minutes);
		jQuery("#rest_time_minutes").html(Math.round((new_focus_time_minutes)/5));
		jQuery("#cycle_time_minutes").html(Math.round((new_focus_time_minutes)/5)+new_focus_time_minutes);
		update_settings_message("<?php _e('updating user settings...', 'plugin-projectimer'); ?>");
		/*var data = {
			action: "projectimer_update_user_cycle_time",
			new_focus_time_minutes: new_focus_time_minutes,
		};/
		if(typeof is_countdown_active !== 'undefined') {
			update_settings_message("Change settings on focus page, please wait...");
			if(is_countdown_active) {
				update_settings_message("You cannot change time setting when countdown is active");
			} else {
				update_user_meta("focus_time", new_focus_time_minutes)
			}
		} else {
			update_settings_message("Change settings on non focus page, please wait...");
			update_user_meta("focus_time", new_focus_time_minutes)
		}*/
		//Why cant change settings when countdown active?
		update_user_meta("focus_time", new_focus_time_minutes);
		user_focus_time = new_focus_time_minutes;
		if(jQuery('#action_button').length)
		update_action_button_label(convertSeconds(new_focus_time_minutes*60));
		//User is on FOCUS page
	} else {
		update_settings_message("<?php _e('Limits are 10 and 50 minutes for focus time', 'plugin-projectimer'); ?>");
	}
}

//
function paint_site(newcolor, time) {
		//if(!animated) {
			//$("#header").css("background-color", newcolor);
		//} else {
			jQuery("#header").animate({"background-color": newcolor}, time);
			//jQuery("#footer").animate({"background-color": newcolor}, time);
		//}
		
		//.css("background-color", color);
}

//Funtions to save pomodoros
function schedule_cycle (timefromnowreceived) {
	console.log("schedule_cycle(timefromnowreceived):"+timefromnowreceived);
	if(timefromnowreceived=="")
		timefromnowreceived = user_focus_time;
	//update_status_message(txt_salving_progress);	
	var postcat=getRadioCheckedValue("cat_vl");
	//var privornot=getRadioCheckedValue("priv_vl");
	var data = {
		action: 'projectimer_schedule_cycle',
		dataType: "json",
		post_titulo: title_box.value,
		post_descri: description_box.value,
		post_tags: tags_box.value,
		post_cat: postcat,
		//post_priv: privornot,
		post_status: status,
		timefromnow: timefromnowreceived,
	};
	jQuery.post(ajaxurl, data, function(response) {
		console.log("schedule_cycle(response):");
		console.log(response);
		if(response) {
			alertify.log(user_first_name + " is focusing. Timer started");
			//console.log(response["active_cycle_id"]);
			user_active_cycle_id = response["active_cycle_id"];
			//The end of the big rest, the indicators light has to reset
			//if(pomodoro_actual==1)
			//turn_off_pomodoros_indicators();
			start_count(timefromnowreceived*60);
			//alertify.log("Data saved");
			/*bp_activity_add(array(
				'action' => $user_fullname.' updated ' . $title . ':',
				'component' => 'new_blog_post',
				'type' => 'activity_update',
				'primary_link' => get_permalink($post_id),
				'user_id' => $user_id
				));*/
		}
		//update_status_message(response);
		//update_status_message("Cycle started");
		/*Append the fresh completed pomodoro at the end of the list, simulating the data
		var d=new Date();
		data = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+d.getUTCHours()+":"+d.getUTCMinutes()+":"+d.getUTCSeconds();//new Date(year, month, day, hours, minutes, seconds);
		if(response[0]=="1")
		$("#points_completed").append('<li>'+data+" -> "+description.value+'</li>');*/
	});
}

//trash_cycle
function update_cycle_status (id, status) {
	console.log("update_cycle_status(id, status):"+id+", "+status);
	if(status!=="") {
		if(id>0) {
			var data = {
				action: "projectimer_update_cycle_status",
				cycle_id: id,
				newstatus: status,
			};
			jQuery.post(ajaxurl, data, function(response) {
				console.log(response);
				if(response) {
					if(status=="trash")
					alertify.log("Time lost, cycle is now on "+status);
					else
					alertify.log("Cycle new status: "+status);
				} else {
					alertify.error("Error updating cycle status.");
				}
			});	
		} else {
			//no
			console.log("change_cycle_status() need id");
		}	
	} else {
		console.log("change_cycle_status() need id");
	}
	
}