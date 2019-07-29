//(function(){

//USER SETTINGS
{
	//current being loaded by ajax and setted by yser
	var user_first_name;
	var user_last_name;
	var user_focus_time;
	var user_rest_time;
	var user_active_cycle_id;
	var user_active_cycle_title;
	var user_active_cycle_post_status;
	var user_activy_session_number = 0;
	var drafted_cycle_id;
	var site_color;
	var current_team_url;
	var seconds_focus_time = 0; //total time, loaded from
	var seconds_lost_time = 0;
}
//TASK
var tags_list;
//TIMER SET
{
	var secondsRemaining;
}
//ENVIRORMENT SETTINGS
{
	var soundmanag = false;
	var action_button;
	var backgroundCheck = false;
	var intervalMiliseconds = 1000;
	var i=0;
	var is_countdown_active = false;
	var is_rest = false;
	var is_lost = false;
	var is_session_active = false;
	var communicator; //if false = offline
	var animatedinterval;
	var clock_like_timestamp;
	var original_document_title = document.title;
}

//OBJECTS
{
	var userSettingsObject_default = {
		user_first_name : "Anonymous",
		user_last_name : "User",
		user_focus_time : 25*60,
		secondsRemaining : 25*60,
		active_sound : "crank-2.mp3",
		interrupt : "telephone-ring-1.mp3",
		end_sound_rest : "23193__kaponja__10trump-tel.mp3",
		end_sound_focus : "23193__kaponja__10trump-tel.mp3"
	};
}			

function startTest() {
	user_focus_time = 25;
	user_rest_time = 5;
	intervalMiliseconds = 10;
}

/*var Foo = function() {

    var privateStaticMethod = function() {};
    var privateStaticVariable = "foo";

    var constructor = function Foo(foo, bar)
    {
        var privateMethod = function() {};
        this.publicMethod = function() {};
    };

    constructor.publicStaticMethod = function() {};

    return constructor;
}();*/

//LOADING USER DATA
jQuery( document ).ready(function($) {
	//
	console.log("jQuery().ready()");
	//
	action_button = jQuery("#action_button");
	//
	if(action_button) {//PAGE FOCUS
		//INTERFACE
		colunsHeightAlwaysTheSame();
		jQuery( window ).resize(function() {colunsHeightAlwaysTheSame()});

		add_click_action_on_interface_buttons();//removed from html

		recent_activities_add_bootstrap_stripes();//improve beuaty

		jQuery('[data-toggle="popover"]').popover({"trigger":"hover"});//pop info icons
		//
		load_task_by_id();
		//
		load_user_settings();
		//
		// Ready to use; soundManager.createSound() etc. can now be called.
		jQuery.getScript(pluginDir+"/js/soundmanager2.js", function(){
			console.log("soundManager.setup()");
			if(!userSettingsObject)
				userSettingsObject = userSettingsObject_default;
			soundManager.setup({			
				url: pluginDir+'/js/',
				flashVersion: 9, // optional: shiny features (default = 8)
				// optional: ignore Flash where possible, use 100% HTML5 mode
				preferFlash: false,
				onready: function() {
					soundmanag = true;
					active_sound = soundManager.createSound({id: 'active_sound',url: pluginDir+'/sounds/'+userSettingsObject["active_sound"],});
					interrupt = soundManager.createSound({id:'interrupt',url: pluginDir+'/sounds/'+userSettingsObject["interrupt"],});
					end_sound_rest = soundManager.createSound({id:'end_sound_rest',url: pluginDir+'/sounds/'+userSettingsObject["end_sound_rest"],});
					end_sound_focus = soundManager.createSound({id:'end_sound_focus',url: pluginDir+'/sounds/'+userSettingsObject["end_sound_focus"],});
					// Ready to use; soundManager.createSound() etc. can now be called.
				}
			});
		});
		jQuery.getScript(pluginDir+"/js/platform.js", function(){
			//alert('you are using ' + platform.description + ' on an ' + (platform.manufacturer || '') );
			jQuery("#user_device").html((platform.manufacturer || '') + platform.description);
		});
		//
		
		jQuery.get("https://ipinfo.io?token=e7e9316dfdc5fa", function (response) {
		    console.log("IP: " + response.ip);
			console.log("Location: " + response.city + ", " + response.region);
			console.log(JSON.stringify(response, null, 4));
			jQuery("#user_location").html(response.city + ", " + response.region + ", " + response.country);
		}, "jsonp");
		
		//
		/*
		jQuery.get("https://www.gstatic.com/firebasejs/3.6.0/firebase.js", function (response) {
		    var config = {
			    apiKey: "AIzaSyDvUC9hZ5nsVOg-wC5qu98g1SwoUnfpa7U",
			    authDomain: "focalizador-148302.firebaseapp.com",
			    databaseURL: "https://focalizador-148302.firebaseio.com",
			    storageBucket: "focalizador-148302.appspot.com",
			    messagingSenderId: "124631709462"
			};
			firebase.initializeApp(config);

		    /*console.log("IP: " + response.ip);
			console.log("Location: " + response.city + ", " + response.region);
			console.log(JSON.stringify(response, null, 4));
			jQuery("#user_location").html(response.city + ", " + response.region + ", " + response.country);*
		});	*/
	}
});

function add_click_action_on_interface_buttons() {
	if(action_button) {
		//FOCUS PÀGE (app)
		action_button.click(function(){ action_button_press(); })
	}
	//HEADER NAVBAR
	jQuery( "#button_login" ).click(function() {
		jQuery('#login_modal').modal('show');
	});
	jQuery('#button_team_settings').click(function() {
		jQuery('#team_settings_modal').modal('show');
	});
	//FOOTER
	jQuery('#sales_phone').on('click', function(){
		alertify.log("Finding best Sales number based in your location");
		blink_animation(jQuery('#sales_phone'));
		jQuery('#sales_phone').html("finding...");
		setTimeout(function(){
			//alertify.success("Temporary PREMIUM function activated");
			alertify.log("Best sales phone based in your location: " + jQuery("#user_location").text());
			jQuery('#sales_phone').html("+55 11 958 843 715");
		}, 2000);
	    
	});
	jQuery('#support_phone').on('click', function(){
	    alertify.log("Checking credentials...");
		blink_animation(jQuery('#support_phone'));
		jQuery('#support_phone').html("checking...");
		setTimeout(function(){
			alertify.success("Temporary PREMIUM function activated");
			jQuery('#support_phone').html("+55 11 958 843 715");
		}, 2000);
	}); 
	/*jQuery("#settings_button").click(function(){
		jQuery("#projectimer_settingsbox").toggle("slow");
	});
	jQuery( ".button_close" ).click(function() {
		jQuery( this ).parent().hide("slow");
	});
	jQuery( "#button_login" ).click(function() {
		//jQuery( "#loginlogbox" ).toggle("slow");
		jQuery('#login_modal').modal('show');
	});*/
}

function colunsHeightAlwaysTheSame() {
	//jQuery("body").height().onchange(function() {
	//jQuery("#default_sidebar").resize(function() {	
	/*jQuery("#task_tabs a").click(function() {
		alertify.log("changed");
		jQuery("#activity_sidebar").height(jQuery("#content_column").height());
		jQuery("#default_sidebar").height(jQuery("#content_column").height());
	});*/
	
		//alertify.log("changed");
		//hei = jQuery( document ).height()-jQuery(".navbar").height() - jQuery("#footer").height();
		heiElements = jQuery(".navbar").height()+jQuery("#footer").height();
		heiMin = jQuery("#task_form").height() + jQuery("#clock-container").height()-heiElements;// +20; //MARGIN NEGATIVE OF CLOCK-CONTAINER
		if(heiMin<800)
			heiMin=800;
		heiWieport = jQuery(window).height()-heiElements;
		//alert(hei);
		hei = heiWieport;
		if(heiWieport<heiMin)
			hei = heiMin-150;
		jQuery("#default_sidebar").height(hei);//ELEMENT PADING
		jQuery("#content_column").height(hei-40);
		jQuery("#activity_sidebar").height(hei+20);	
}

function load_user_settings() {
	console.log("load_user_settings()");
	//animation
	connect_sign();
	//prevent user excessive clicking behavior
	action_button.prop( "disabled", true );
	//
	var data = {
		action: 'projectimer_load_user_settings',
		dataType: "json",
	};
	//
	jQuery.post(ajaxurl, data, function(userSettingsObject) {
		console.log("load_user_settings(response):"+userSettingsObject);
		//
		if(userSettingsObject!=0) {
			last = localStorage.getItem("user_last_offline_session");
			if(last!=0) {
				alertify.success("You just become online!");
				//
				localStorage.setItem('user_last_offline_session', 0);
				//
				//CHECK LOCAL DATA
				
			}
	
			if(userSettingsObject["user_first_name"]=="" || userSettingsObject["user_first_name"]==undefined)
				alertify.success('What is your name? <br /><a href="#tab-settings" title="Settings" class="open_settings_modal" data-toggle="modal" data-target="#projectimer_settingsbox_modal"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>OPEN SETTINNGS</a>');
			//
			localStorage.setItem('userSettingsObject', JSON.stringify(userSettingsObject));
			//
			after_load_user_settings(userSettingsObject);
			//
			
			//var data_off = new Date(last);
			//var data_loaded_timer = new Date();
			
			//var data_become_off = new Date();
			//localStorage.setItem('user_last_offline_session', data_become_off);
		} else {
			alertify.error("Failed to load settings, default setting will be used");
			//
			after_load_user_settings(userSettingsObject_default);
		}
		//
		start_permanent_communication();
		//
	}).fail(function(){
		//
		become_offline();
		//
		var userSettingsObject = JSON.parse(localStorage.getItem('userSettingsObject'));
		//
		after_load_user_settings(userSettingsObject);
	});
}

function become_offline() {
	//if(jQuery("#offline_sign")) {
	console.log("become_offline()");
	alertify.error("You became OFFLINE, using local data storage");
	jQuery("#offline_sign").show();
	var data_become_off = new Date();
	localStorage.setItem('user_last_offline_session', data_become_off);
	//
	if(!localStorage.getItem('user_last_offline_session')) {
		console.log("no data found");
		//	
	} else {
		//We found previous data, we will preserv it
		console.debug(localStorage);
		alert(localStorage);
	}
}

function after_load_user_settings(userSettingsObject) {
	console.log("after_load_user_settings(); secondsRemaining:"+secondsRemaining);

	{		
		//set users vars (TODO: classes)
		user_first_name = userSettingsObject["user_first_name"];
		user_last_name = userSettingsObject["user_last_name"];
		user_display_name = userSettingsObject["user_display_name"];
		user_focus_time = parseInt(userSettingsObject["focus_time"]*60);
		user_rest_time = parseInt((user_focus_time/5));
		user_active_cycle_id = userSettingsObject["active_cycle_id"];
		user_active_cycle_title = userSettingsObject["user_active_cycle_title"];
		user_active_cycle_post_status = userSettingsObject["post_status"];
		secondsRemaining = userSettingsObject["secondsRemaining_from_server"];
		drafted_cycle_id = userSettingsObject["drafted_cycle_id"];
		site_color = userSettingsObject["bgcolor"];
		current_team_url = userSettingsObject["site_url"];
		tags_list = userSettingsObject['tags_list'];
	}
	if(action_button.length) {
		//
		console.log("User is on FOCUS page");
		//
		update_action_button_label(convertSeconds(secondsRemaining));
		//
		action_button.prop( "disabled", false );
		//
		//jQuery("#loading-message").animate({"opacity": 0}, 500, function() {jQuery("#loading-message").hide(0);});
		//the dots . .. ... animation on loading message
		//window.clearInterval(loading_animated);
		//dont need, its a cycle with old settings only

		if(secondsRemaining!=undefined) {
			//lert(secondsRemaining);
			//lert(user_focus_time);
			if(secondsRemaining<0) {
				update_status_message("ready to focus?");
				update_cycle_status("projectimer_focus", "trash");
				alertify.error(user_display_name + " you lost your Projectimer, you must be on FOCUS page when projectimer end.");
			} else {
				if(secondsRemaining==user_focus_time) {
					focus_set();
					//update_status_message("ready to focus?");
					//secondsRemaining = user_focus_time;
					alertify.log(user_display_name + ", you Projectimer is ready. Start focus anytime you want.");
				} else {
					update_status_message("focusing...");
					is_countdown_active = true;
					start_count(secondsRemaining);
					alertify.log("Timer loaded, " + user_first_name + " is focusing. Aprox. " + Math.round(secondsRemaining/60) + " minutes left." );
				}
			}
		} 
		//else {
		/*if(secondsRemaining<user_focus_time) {
			start_count(secondsRemaining);
			alertify.log("Timer loaded, " + user_first_name + " is focusing. Aprox. " + Math.round(secondsRemaining/60) + " minutes left." );
		} else {
			} /*

				//Its now checked on PHP
				else if(secondsRemaining>user_focus_time*60) {
				alertify.error("Cycle error, due date ( " + Math.round(secondsRemaining/60) + " mins) after " + user_first_name + " focus time ( " + user_focus_time + " mins )");
				//update_user_meta("secondsRemaining", user_focus_time*60);
				update_cycle_status(user_active_cycle_id, "trash");
			}  else {*/
			
		//
		//}
		/*if(drafted_cycle_id) {
			//var delay = alertify.get('notifier','delay');
			alertify.set({ delay: 10000 });
			//alertify.success('Current delay : ' + alertify.get('notifier','delay') + ' seconds');
			alertify.error(user_first_name + " you lost your last cycle, you must be on FOCUS page when time end.");
			alertify.set({ delay: 5000 });
		}*/
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
	        	//color = "#1597FE";
	            	paint_site(color, 2000);
	            	update_user_meta("bgcolor", color);
	        } else if (this.value == 28) {
	        	color = "#093";
	            	paint_site(color, 2000);
	            	update_user_meta("bgcolor", color);
	        } 
	    });
	} else {
		console.log("Not on FOCUS page, secondsRemaining: " + secondsRemaining + " user_focus_time:" + user_focus_time);
		if(secondsRemaining<user_focus_time)
		alertify.log("You shold be on <a href='/" + current_team_url + "/focus'>FOCUS page</a>, you timer is on, aprox. " + Math.round(secondsRemaining/60)+1 + " minutes left.");
	}
	
}

function load_task() {
	console.log("load_task();");
	//jQuery('#title_box').val("Asd2222");
	//jQuery('#description_box').val("Asd2");
	//jQuery('#tags_box').val("Asd3");
}

//BUTTONS ACTIONS
function action_button_press() {
	console.log("action_button_press(): is_countdown_active: " + is_countdown_active + "secondsRemaining: " + secondsRemaining);
	//blinkgreen connect_sign();
	//update_status_message("action_button_press() + is_countdown_active:" + is_countdown_active);
	//check_activity();
	if(is_countdown_active) {
		//The user clicked on action button while focustin
		if(is_lost) {
			//start_count();
			//secondsRemaining = user_focus_time*60; ja tem em stop_count() interrupt_count()
			//update_user_meta("secondsRemaining", user_focus_time);
			//update_action_button_label(convertSeconds(user_focus_time*60));
			//update_status_message("ready to focus");
			//lert(user_active_cycle_id);
			schedule_cycle("projectimer_focus");
			//
			update_status_message("focusing...");
			is_lost = false;
		} else {
			interrupt_count();
			//secondsRemaining = user_focus_time*60; ja tem em stop_count() interrupt_count()
			//update_user_meta("secondsRemaining", user_focus_time);
			update_action_button_label(convertSeconds(user_focus_time));
			update_status_message("ready to focus?");
			//lert(user_active_cycle_id);
			update_cycle_status("projectimer_focus", "trash");
			add_user_activitie("Stopped Projectimer", "projectimer_stop");//TODO: time lost?
			alertify.log(user_first_name + " stopped timer");
		}
		
	} else {
		//The user was not focusing and just started timer
		//update_user_meta("start_time", "now");
		
		if(is_rest) {
			//add_use_activitie("started resting");
			//alertify.log(user_first_name + " started timer");
			//start_count(user_rest_time);
			//update_status_message("resting...");
			schedule_cycle("projectimer_rest");
			update_status_message("resting...");
		} else {
			//add_use_activitie("started timer");
			//alertify.log(user_first_name + " started timer");
			schedule_cycle("projectimer_focus");
			//
			update_status_message("focusing...");
		}
	}
}

function ponto_button_press() {
	console.log("ponto_button_press();");
	is_session_active = true;
	//lert(jQuery("#ponto_button").html());
	//jQuery("#ponto_button").css("background-color", "#009900");
	start_count_time_lost();
	//jQuery("#session_info").show();
	
	glyp2 = jQuery("#ponto_button").find('span').find('span');
	glyp2.toggleClass('glyphicon-play').toggleClass('glyphicon-stop');
	
	if(glyp2.hasClass('glyphicon-play')) {
		is_session_active = false;
	}
	
	if(is_session_active) {
		//jQuery("#focus_timetotaln_color").animate({"background-color": "#069"}, 100);
		//jQuery("#restlost_timetotaln_color").animate({"background-color": "#069"}, 100);
		jQuery("#restlost_timetotaln_color").animate({"background-color": "#900"}, 100);
	} else {
		
		encerrateSession();
	}
	//jQuery("#session_info").hide();
	//session_info
	//pisca
	//blinkgreen(jQuery("#ponto_button")); connect_sign();
	//fica verde, 
	//start-time-lost-rest -> click para -> comeca tempo
}

function div_status_press() {
	//jQuery("#div_status").animate({"color": "transparent"}, 2500);
	//jQuery("#div_status").animate({"background-color": "transparent"}, 2500);
	var el = jQuery("#div_status");
	if(el.css("visibility")=="hidden")
		el.css("visibility", "visible");
	else
		el.css("visibility", "hidden");
}

function communication_press() {
	console.log("communication_press();");
	var element_pop = jQuery("#update_status_label");
	//
	//element_pop.popover('destroy');
	if(communicator) {
		element_pop.popover('hide');
		contentbef = element_pop.attr("data-content");
		element_pop.attr("data-original-title", element_pop.attr("data-alternative-title"));
		element_pop.attr("data-content", element_pop.attr("data-alternative-content"));

		clearInterval(animatedinterval);
		animatedinterval = false;
		
		elements = jQuery("#update_status_icon");
		elements.removeClass('glyphicon-cloud-download');
		elements.removeClass('glyphicon-cloud-upload');
		elements.addClass('glyphicon-eye-close');
		//elements.addClass('glyphicon-exclamation-sign');
		//elements.addClass('glyphicon-alert');
		elements.animate({"color": "#900"}, 500);
		kill_permanent_communication();
	} else {
		element_pop.popover('hide');
		element_pop.attr("data-original-title", element_pop.attr("data-title"));
		element_pop.attr("data-content", contentbef);
		start_permanent_communication();
	}
}

//INTERVALED COMMUNICATION
function start_permanent_communication() {
	console.log("start_permanent_communication();");
	elements = jQuery("#update_status_icon");
	elements.removeClass('glyphicon-cloud-upload');
	elements.removeClass('glyphicon-eye-close');
	elements.addClass('glyphicon-cloud-download');
	elements.animate({"color": "#CCC"}, 500);
	update_recent_activities();
	communicator = setInterval("update_recent_activities()", 11000);
}

function kill_permanent_communication() {
	console.log("kill_permanent_communication();");
	
	clearInterval(communicator);
	communicator = false;
	
}

function update_recent_activities() {
	//console.log("update_recent_activities()");
	var page = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
	
	var data = {
		action: 'projectimer_update_recent_activities',
		user_last_active_page: page,
		//user_status: communicator,
		//dataType: "json",
		//Encoding: "gzip",
	};
	//var prevmsg = jQuery("#div_status").text();

	//blinkgreen(jQuery("#update_status_icon"));
	connect_sign();
	jQuery.post(ajaxurl, data, function(response) {
		if(response=="NOTIN")window.location.href = "/";

		if(response["user_credentied"]==0) {
			alertify.error("Você foi removido do Time");
			window.location.href = "/removido";
		}
		//lert(response["secondsRemaining"]);
		//console.log("response[secondsRemaining_from_server]: " + response["secondsRemaining_from_server"]);
		//if(response!=0)
		jQuery("#default_sidebar_in").html(response["recent_members_html"]);
		//
		jQuery("#recent-activities").parent().html(response["recent_activity_html"]);
		jQuery(".addclick").parent().parent().click(function() {
				//lert(jQuery(this).find("span").text());
				//loadTaskModal(jQuery(this).find("span").text());
				var id = jQuery(this).find("span[class=hidden]").text();
				jQuery('#loadtask_modal').modal('show');
				//alert(id);
				jQuery('#erase_and_load').click(function(){
					load_task_by_id(id);
					jQuery('#loadtask_modal').modal('hide');
				})
				// data-toggle='modal' data-target='#myModal'
		});
		recent_activities_add_bootstrap_stripes();
		//
		secondsRemaining_from_server = response["secondsRemaining_from_server"];
		if(secondsRemaining_from_server) {
			difftime = secondsRemaining-secondsRemaining_from_server;
			//lert(difftime);
			//if(difftime<0)difftime*=-1;
			if(secondsRemaining_from_server!=user_focus_time) {
				if(difftime>60) {	
					//if(secondsRemaining-secondsRemaining_from_server>60)
					alertify.error("Time updated from server: " + parseInt(difftime/60) + " minutes difference");
					seconds_focus_time+=difftime;
				}
			}
			
			secondsRemaining = secondsRemaining_from_server;
		} else {
			if(is_countdown_active) {
				if(!is_rest && !is_lost) {
					interrupt_count();
					secondsRemaining = user_focus_time;
					update_action_button_label(convertSeconds(user_focus_time));
					update_status_message("ready to focus?");
					update_cycle_status("projectimer_focus", "trash");
					add_user_activitie("Stopped Projectimer (fault)", "projectimer_stop");//TODO: time lost?Projectimer data lost
					alertify.error("Projectimer data lost");
				}
			}
		}
		
	}).fail(function() {
		become_offline();
	});

	//update_user_meta("secondsRemaining", secondsRemaining);
}

function start_count(timeleft) {
	console.log('start_count(timeleft):' + timeleft);
	//
	if(soundmanag)
	active_sound.play();
	//
	if(timeleft) {
		secondsRemaining=timeleft;
	} else {
		secondsRemaining=user_focus_time;
	}
	clearInterval(is_countdown_active);
	
	is_countdown_active = setInterval('count()', intervalMiliseconds);
}

function count() {
	//
	
	
	//varvara = intervalMiliseconds;
	secondsRemaining--;
	if(secondsRemaining==0)
	complete_count();
	
	clock_like_timestamp = convertSeconds(secondsRemaining);
	update_action_button_label(clock_like_timestamp);
	document.title=clock_like_timestamp + " - " + jQuery("#title_box").attr("value");


	//TODO:PREMIUM FEATURE if(is_session_active) {
		//update_time_focus_label(clock_like_timestamp);
		//""+convertSeconds(seconds_focus_time/60);
		//console.log(clock_like_timestamp);
	//}

	seconds_focus_time++;
	
}

function start_count_time_lost() {
	is_lost = true;
	if(is_countdown_active) {
		clearInterval(is_countdown_active);
		is_countdown_active = false;
	}
	is_countdown_active = setInterval('countlost()', intervalMiliseconds);
}

function countlost() {
	seconds_lost_time++;
	if(seconds_lost_time%2)
	update_action_button_label(convertSeconds(seconds_lost_time));
	else
	update_action_button_label(convertSeconds(secondsRemaining*60));
	
	//focus_timetotaln
	if(is_session_active)//TODO:PREMIUM FEATURE
	update_time_rest_lost_label(convertSeconds(seconds_lost_time/60));
}

function complete_count() {
	console.log("complete_count()");
	
	if(is_rest) {
		update_cycle_status("projectimer_rest", "publish");//ajax response trigger next action
	} else {
		update_cycle_status("projectimer_focus", "publish");//ajax response trigger next action
	}

	if(is_session_active)
	start_count_time_lost();
}

function focus_set() {
	console.log("focus_set()");
	secondsRemaining = user_focus_time;
	//update_user_meta("secondsRemaining", user_focus_time);
	stop_count();
	if(soundmanag)
	end_sound_rest.play();
	//
	update_status_message("ready to focus?");
	is_rest = false;
	paint_site("#4b8394", 3500);//337ab7
}

function rest_set() {
	console.log("rest_set()");
	stop_count();
	if(soundmanag)
	end_sound_focus.play();
	//
	secondsRemaining = user_rest_time;
	update_status_message("get some rest");
	update_action_button_label(convertSeconds(secondsRemaining));
	user_activy_session_number++;
	blink_animation(jQuery("#session_current_cycle span"));
	jQuery("#session_current_cycle span").text(user_activy_session_number);
	is_rest = true;
	paint_site("##4C7F4C",3500);//5CB85C
}

//Just stop de contdown_clock function at certains moments
function stop_count() {
	console.log("stop_count()");
	original_document_title=original_document_title;
	
	//if(soundmanag)
	//end_sound.play();
	
	clearInterval(is_countdown_active);
	is_countdown_active=false;
	//update_action_button_label(convertSeconds(secondsRemaining));
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
	if(soundmanag)
	interrupt.play();
	//
	//convertSeconds(0);
	//flip_number();
	//change_button(textPomodoro, "#063");
	//secondsRemaining=0;
	//secondsRemaining = user_focus_time;
	//if(!is_pomodoro)is_pomodoro=true;
}

//POST TYPE CYCLE MANAGEMENT
function schedule_cycle (posttype) {
	console.log("schedule_cycle(posttype);"+posttype);
	
	//lert(jQuery("#task_form").width());
	//var privornot=getRadioCheckedValue("priv_vl");
	if(jQuery("#task_form").width()) {
		/*var postcat=getRadioCheckedValue("cat_vl");
		var data = {
			action: 'projectimer_schedule_cycle',
			dataType: "json",
			posttype: posttype,
			post_titulo: title_box.value,
			post_descri: description_box.value,
			post_tags: tags_box.value,
			post_cat: postcat,
			//post_priv: privornot,
		};*/
		//lert(posttype);
		data = get_current_task_data_object();
		data['action'] = 'projectimer_schedule_cycle';
		data['posttype'] = posttype;
		/*var type_r = getRadioCheckedValue("type_box");
		var pln_r = getRadioCheckedValue("planned_box");
		var virt_r = getRadioCheckedValue("virtual_box");
		var tags = jQuery("#tags_box").select2('val');
		
		var data = {
			action: 'projectimer_schedule_cycle',
			posttype: posttype,
			post_titulo: title_box.value,
			post_tags: tags,
			post_descri: description_box.value,
			post_type_type: type_r,
			post_planned: pln_r,
			post_virtual: virt_r,
		}*/
		//lert(data);
		jQuery.post(ajaxurl, data, function(response) {
			console.log("schedule_cycle(response):"+response);
			if(response) {
				//lert(user_focus_time);
				if(posttype=="projectimer_focus") {
					alertify.log(user_first_name + " is focusing. Projectimer started");
					//glypaviso = '<span class="glyphicon glyphicon-info-sign glyinfo" aria-hidden="true" data-toggle="popover" data-title="Join This Task" data-content="Simple way to work togueter, click task to load it and work to your timer" data-placement="left">';
					user_active_cycle_id = response["active_cycle_id"];

					if(title_box.value)
						add_user_activitie("Started " + title_box.value, "projectimer_start" );
					else
						add_user_activitie("Started a task", "projectimer_start");

					//console.log(response["active_cycle_id"]);
					
					//The end of the big rest, the indicators light has to reset
					//if(pomodoro_actual==1)
					//turn_off_pomodoros_indicators();
					start_count(user_focus_time);
					//alertify.log("Data saved");	
					is_rest_or_lost = true;
				}
				if(posttype=="projectimer_rest") {
					alertify.log(user_first_name + " is resting. Timer started");
					user_active_cycle_id = response["active_cycle_id"];
					start_count(user_rest_time);
					is_rest_or_lost = false;
				}
			} //else
			//console.log("schedule_cycle(no response)");
		});
	} else {
		//Annonymous user
		start_count(user_focus_time);
		alertify.log(user_first_name + " is focusing. Timer started");
	}
}

function update_cycle_status (posttype, status) {
	console.log("update_cycle_status(type, status):" + posttype + ", " + status);
	if(status!=="") {
		var data = {
			action: "projectimer_update_cycle_status",
			posttype: posttype,
			newstatus: status,
		};
		
		jQuery.post(ajaxurl, data, function(response) {
		if(response=="NOTIN")window.location.href = "/";
			console.log(response);
			//if(response==1) {
				//alertify.log("Activity published!");
			//}
			if(response==0) {
				if(status=="trash") {
					//PROJECTIMER INTERRUPTED
					alertify.log("Time lost, cycle is now on "+status);
					focus_set();
				} else {
					alertify.log("Time new status: "+status);

					if(posttype=="projectimer_focus") {
						//projectimer_add_activie($desc);
						//add_user_activitie("Completed " + response["title_task"]);
						//add_user_activitie("Completed a task");
						rest_set();
					}
					if(posttype=="projectimer_rest") {
						//add_user_activitie("Just rested");
						focus_set();	
					}
					
				}
			} else {
				alertify.error("Error, we received " + response + " seconds time difference from server");
				secondsRemaining = response;
			}
		});	
	} else {
		console.log("change_cycle_status() need status");
	}
}

//TASK MODELS
function load_model(postid) {
	console.log("load_model();");
	//PROMPT VOCE JA ESTA FAZENDO UMA TAREFA? DESEJA SALVA-LA COMO MODELO ANTES DE SOBRESCREVER?
	//jQuery('#title_box').val("ModelTitle");
	//jQuery('#tags_box').val("tag1, tag2");
	//jQuery('#description_box').val("Model Desc");
	
}

function delete_model(postid) {
	console.log("delete_model()");
	//PHP deletar post qualmodelo
	//update_status_message(txt_deleting_model);
	var data = {
		action: 'save_modelnow',
		post_para_deletar: postid
	};
	jQuery.post(ajaxurl, data, function(response) {
		if(response=="NOTIN")window.location.href = "/";
		if(response) {
			update_status_message(txt_deleting_model_sucess);
			$("#modelo-carregado-"+qualmodelo).remove();
		} else {
			update_status_message(txt_save_error);
		}
	});
}

function save_model (posttype) {
	console.log("save_model()");

	if(posttype==undefined)
		posttype=="projectimer_focus";

	//alertify.log("Saving task model...");
	//update_status_message("save_model()");
	/*var data = {
		action: 'projectimer_update_cycle_status',
		posttype: posttype,
		post_titulo: title_box.value,
		post_descri: description_box.value,
		post_tags: tags_box.value
	};*/
	data = get_current_task_data_object();
	data['action'] = 'projectimer_update_cycle_status';
	//console.log("data: " + data);
	jQuery.post(ajaxurl, data, function(response) {
		if(response=="NOTIN")window.location.href = "/";
		//console.log("save responde: " + response);
		if(response) {
			if(response==0) {
				//return 0;
				alertify.log("Error when trying to save task model...");
			} else {
				if(communicator) {
					add_user_activitie("Saved a task model", "projectimer_generic");
					alertify.log(user_first_name + " saved a task model");
				} else {
					alertify.log(user_first_name + " saved a task model (offline)");
				}
				
				jQuery("#table-task-models").append("<tr><td>"+title_box.value+"</td><td>"+tags_box.value+"</td><td><input type='button' class='btn' value='use' onclick='load_model()'></td><td><input type='button' class='btn' value='complete' onclick='delete_model()'></td></tr>");
				//<ul id="modelo-carregado-'+sessao_atual+'"><li><input type="text" value="'+title_box.value+'" disabled="disabled" id="bxtitle'+sessao_atual+'"><br /><input type="text" value="'+description_box.value+'" disabled="disabled" id="bxcontent'+sessao_atual+'"><br /><input type="text" value="'+tags_box.value+'" disabled="disabled" id="bxtag'+sessao_atual+'"><p><input type="button" value="usar modelo" onclick="load_model('+sessao_atual+')"><input type="button" value="apaga" onclick="delete_model('+sessao_atual+')"></p></li></ul>');
				//return response;
				//var sessao_atual=response;
				//primeiro salva o post, para depois pegar o id do mesmo
				/*$("#contem-modelos").append('<ul id="modelo-carregado-'+sessao_atual+'"><li><input type="text" value="'+title_box.value+'" disabled="disabled" id="bxtitle'+sessao_atual+'"><br /><input type="text" value="'+description_box.value+'" disabled="disabled" id="bxcontent'+sessao_atual+'"><br /><input type="text" value="'+tags_box.value+'" disabled="disabled" id="bxtag'+sessao_atual+'"><p><input type="button" value="usar modelo" onclick="load_model('+sessao_atual+')"><input type="button" value="apaga" onclick="delete_model('+sessao_atual+')"></p></li></ul>');
				/*$("#botao-salvar-modelo").val("sessão salvada com sucesso");
				$("#botao-salvar-modelo").attr('disabled', 'disabled');*
				document.getElementById("bxcontent"+sessao_atual).focus();
				update_status_message(txt_salving_model_success);*/
			}
		} else {
			alertify.log("Error, connection lost...");
		}
	});
}

//ACTIVITY STREAM
function add_user_activitie (desc, type) {
	console.log("add_user_activitie(desc):" + desc);
	action_button.prop( "disabled", true );
	//update_status_message("Loading user settings...");
	var data = {
		action: 'projectimer_add_activie',
		description: desc,
		type: type,
		hidden: communicator,
		//dataType: "json",
	};
	action_button.prop("disabled", "disabled");
	jQuery.post(ajaxurl, data, function(response) {
		console.log("add_user_activitie(response):"+response);
		action_button.prop( "disabled", false );
	});
}

function update_user_meta (prop, newvalue) {
	console.log("update_user_meta(prop, newvalue):"+prop+","+newvalue);
	var data = {
		action: 'projectimer_update_user_meta',
		property: prop, //THE PROP WILL ATTACH $site_url IN PHP, it is teamname-prop, like f5sites-user_last_heartbeat
		value: newvalue,
		//dataType: "json",
	};
	jQuery.post(ajaxurl, data, function(response) {
		console.log("update_user_meta(response):"+response);
	});
}

//SESSION MANAGEMENT
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

function encerrateSession() {
	seconds_focus_time = 0;
	seconds_lost_time = 0;
	clearInterval(is_countdown_active);
	jQuery("#focus_timetotaln_color").animate({"background-color": "#CCC"}, 100);
	jQuery("#restlost_timetotaln_color").animate({"background-color": "#CCC"}, 100);
	update_time_rest_lost_label(convertSeconds(0));
	//update_action_button_label(convertSeconds(seconds_lost_time));
}

//SETTINGS
function update_settings_message(txt) {
	console.log("update_settings_message()");
	jQuery("#setting_message").html(txt);
	//document.getElementById("div_status").innerHTML=txt;
}

//it's called directly on html click property
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
		if(action_button.length)
		update_action_button_label(convertSeconds(new_focus_time_minutes*60));
		//User is on FOCUS page
	} else {
		update_settings_message("<?php _e('Limits are 10 and 50 minutes for focus time', 'plugin-projectimer'); ?>");
	}
}

//LABEL AND TEXT SCREEN UPDATE
function blink_animation(obj, endBlack) {
	if(!endBlack) {
		jQuery(obj).animate({"color": "#111"}, 200);
		jQuery(obj).animate({"color": "#FFF"}, 2500);
	} else {
		jQuery(obj).animate({"color": "#FFF"}, 200);
		jQuery(obj).animate({"color": "#222"}, 2500);
	}
	//if(txt!=undefined)jQuery(obj).text(txt);
}

function blink_background_animation(obj) {
	jQuery(obj).animate({"background-color": "#FFF", "color": "#FFF", "border-color": "#FFF"}, 200);
	jQuery(obj).animate({"background-color": "#e4e4e4", "color": "#222", "border-color": "#AAA"}, 2500);
}

function update_status_message(txt) {
	blink_animation(jQuery("#div_status_label"));
	var appd = " Click on " + user_first_name + " profile link (top-right) to change timmer settings.";
	jQuery("#div_status span").text(txt);
	jQuery("#div_status").attr("data-content", txt + appd);
}

function update_action_button_label(txt) {
	//blink_animation(jQuery("#clock-label"));
	jQuery("#clock-label").text(txt);
}

function update_time_rest_lost_label(txt) {
	//
	jQuery("#restlost_timetotaln").text(txt);
}

function update_time_focus_label(txt) {
	jQuery("#focus_timetotaln").text(txt);
	//update_time_focus_label(convertSeconds(seconds_focus_time));
}

//AUXILIAR
function paint_site(newcolor, time) {
		console.log("paint_site()");
		//if(!animated) {
			//$("#header").css("background-color", newcolor);
		//} else {
		//	newcolor = "#4c4c4c";
		//jQuery("#header").animate({"background-color": newcolor}, time);
		//jQuery("#focus_timetotaln_color").animate({"background-color": newcolor}, time);
		if(newcolor=="" || newcolor==undefined) newcolor="#4b8394";
		jQuery("#action_button").animate({"background-color": newcolor}, time);
		jQuery('[title="Focus"]').animate({"background-color": newcolor}, time);
		//jQuery(".navbar").animate({"background-color": newcolor}, time);

		//jQuery("#action_button::after").animate({"background-color": newcolor}, time);
		
		//jQuery("#action_button").animate({"background-color": newcolor}, time);
		//jQuery("#footer").animate({"background-color": newcolor}, time);
		//}
		
		//.css("background-color", color);
}

//function blinkgreen(element) {
function connect_sign() {
	element = jQuery("#update_status_icon");
	element.animate({"color": "#222222"}, 10);
	
	//glyp = element.find('span');
	if(animatedinterval) {
		clearInterval(animatedinterval);
		animatedinterval = false;
	}
	element.animate({"color": "#222"}, 100);
	animatedinterval = setInterval(function() {
		i++;
		
		if(i<=8) {
			element.toggleClass('glyphicon-cloud-download').toggleClass('glyphicon-cloud-upload');
			//
			//element.toggleClass('glyphicon-arrow-right').toggleClass('glyphicon-arrow-left');
			/*if(element.hasClass('glyphicon-arrow-right')) {
				element.removeClass('glyphicon-arrow-right')
				element.addClass('glyphicon-arrow-left')
			} else {
				element.removeClass('glyphicon-arrow-left')
				element.addClass('glyphicon-arrow-right')
			}*/
		}
		if (i==9) {
			/*if(element.hasClass('glyphicon-arrow-upload')) {
				element.removeClass('glyphicon-arrow-upload');
				element.addClass('glyphicon-arrow-dowload');
			}*/
			element.removeClass('glyphicon-arrow-upload');
			element.removeClass('glyphicon-arrow-upload');
			element.addClass('glyphicon-dowload');
		}
		if (i>8 && i<15) {
			if(i%2==0)
			element.animate({"color": "#222"}, 200);
			else
			element.animate({"color": "#CCC"}, 200);

			//glyp.toggleClass('glyphicon-arrow-right').toggleClass('glyphicon-arrow-left');
		}
		/*if (i==15) {
			element.animate({"color": "#222"}, 100);
		}*/
		if (i==15) {
			//element.toggleClass('glyphicon-arrow-right')
			element.animate({"color": "#009900"}, 200);
			
		}
		if (i>20) {
			//element.toggleClass('glyphicon-arrow-right')
			element.animate({"color": "#FFF"}, 100);
			i=0;
			clearInterval(animatedinterval);
			animatedinterval = false;
		}
	}, 200);
	//setTimeout(function(){clearInterval(animatedinterval);animatedinterval = false;},10000);
}

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
	//lert(m1+""+m2+":"+s1+""+s2);
	//console.log(m1+""+m2+":"+s1+""+s2);
	return(m1+""+m2+":"+s1+""+s2);
}

function getRadioCheckedValue(radio_name){
	console.log("getRadioCheckedValue()");
	var oRadio = document.forms["task_form_current_task"].elements[radio_name];
	//$('input[name=radioName]:checked', '#myForm').val()
	//lert(oRadio.length);
	for(var i = 0; i < oRadio.length; i++) {
		if(oRadio[i].checked) {
		 return oRadio[i].value;
		}
	}
	return '';
}

function recent_activities_add_bootstrap_stripes() {
	//
	//if(!jQuery('.avatar').parent().children(0).hasClass("avatar_projectimer_clock"))
	//jQuery('.avatar').before('<span class="avatar_projectimer_clock">&nbsp;</span>');
	//if(!jQuery('.avatar').parent().hasClass("avatar_projectimer_clock"))
	//$(this).parent("li").length

	//if(!jQuery('.avatar').parent().find(".avatar_projectimer_clock").length)
	//jQuery('.avatar').wrap('<span class="avatar_projectimer_clock">&nbsp;</span>');
	/*jQuery(".avatar").each(function() {
		if(!jQuery(this).parent().hasClass("avatar_projectimer_clock"))
			jQuery(this).wrap('<span class="avatar_projectimer_clock">&nbsp;</span>');
	});*/
	//jQuery(".activity-item").addClass("progress-bar progress-bar-info progress-bar-striped");
	//jQuery(".activity-item").wrap('<span class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></span>');
	//jQuery(".activity-item").wrap('<div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div></div>');
	jQuery(".projectimer_complete .activity-avatar").before('<div class="progress"><div class="progress-bar progress-bar-success active progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div></div>');
	jQuery(".projectimer_stop .activity-avatar").before('<div class="progress"><div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div></div>');
	jQuery(".projectimer_start .activity-avatar").before('<div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div></div>');
	var a1 = jQuery(".activity-header a:nth-child(1)");
	var a2 = jQuery(".activity-header a:nth-child(2)");
	//alert(a2.html());
	jQuery(".activity_update .activity-header p").html('<span class="activity_title">'+a1.html()+'</span>'+a2.html());
	//jQuery(".time-since").html("Avenida Paulista, Sao Paulo - "+jQuery(".time-since").html());
	//jQuery(".activity-item").wrap('<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>');
	jQuery( ".projectimer_remove_user" ).click(function() {
		console.log("projectimer_remove_user()");
		$targetid = jQuery(this).attr("data-userid");

		//jQuery( "#loginlogbox" ).toggle("slow");
		jQuery('#remove_user_modal').modal('show');
		jQuery('#button_remove_user').click(function() {
			var data = {
				action: "projectimer_remove_user",
				target_user_id: $targetid
			}
			jQuery.post(ajaxurl, data, function(response) {
				console.log("response:" + response);
				if(response) {
					alertify.success("User removed from Team");
				} else {
					alertify.error("Cannot remover user from Team");
				}
			});
			
			jQuery('#remove_user_modal').modal('hide');
		});
	});
	jQuery( ".projectimer_make_user_admin" ).click(function() {
		console.log("projectimer_make_user_admin()");
		$targetid = jQuery(this).attr("data-userid");

		//jQuery( "#loginlogbox" ).toggle("slow");
		jQuery('#make_user_admin_modal').modal('show');
		jQuery('#button_make_user_admin').click(function() {
			var data = {
				action: "projectimer_make_user_admin",
				target_user_id: $targetid
			}
			jQuery.post(ajaxurl, data, function(response) {
				console.log("response:" + response);
				if(response && response!=0) {
					alertify.success("User is now admin");
				} else {
					alertify.error("Cannot make user admin");
				}
			});
			
			jQuery('#make_user_admin_modal').modal('hide');
		});
	});
}

function load_task_by_id(id) {

	console.log("load_task_by_id()");
	//update btn group
	jQuery('#tab-task .btn-group').click(function () {
		jQuery("#tab-task .btn-group button").removeClass("active");
		jQuery(this).find("input").attr("checked", false);
	});
	jQuery('form :input').change(function() {
		//alert("change");
		update_currentask_clipboard();
	});
	var data = {
		action: "projectimer_load_currentask_clipboard",
		dataType: "json",
		id: id,
	}
	if(!is_countdown_active) {
		jQuery.post(ajaxurl, data, function(task_object_received) {
			if(task_object_received!=false) {
				//lert(response['title']);
				load_task_object(task_object_received);
				//
				localStorage.setItem('task_object_stored', JSON.stringify(task_object_received));
				//alertify.log("Error connecting. Task loaded from browser cache.");
			} else {
				//alert(jQuery("#title_box").width());
				if(jQuery("#title_box").width()) {
					jQuery('#tags_box').select2({
						tags: true,
						//closeOnSelect: false,
						//maximumSelectionLength: 3
						tokenSeparators: [","],
						placeholder: function() {
							jQuery(this).data('placeholder');
						}
					});		
					alertify.log("Fill the Task Form for better time management");
				}
			}
		}).fail(function() {
			//
			var task_object_stored = JSON.parse(localStorage.getItem('task_object_stored'));
			//
			load_task_object(task_object_stored);
			alertify.log("Error connecting. Task loaded from browser cache.");
		});
	} else {

		alertify.error("Stop Projectimer before load a new task");
	}
	
}

function load_task_object(task_object_received) {
	jQuery('.nav-tabs a[href="#tab-task"]').tab('show');
	blink_animation(jQuery("#title_box"), true);
	jQuery("#title_box").attr("value", task_object_received['title']);
	//
	blink_animation(jQuery("#tags_box"), true);
	//jQuery("#tags_box").attr("value", response['tags']);
	//lert(response['tags_list']);
	jQuery('#tags_box').html("");
	
	tags = task_object_received['tags'];
	//alert(tags);
	if(tags) {
		jQuery('#tags_box').append('<optgroup label="In use">');
		jQuery.each(tags, function(i) {
			text_variable = tags[i];
			jQuery('#tags_box').append( '<option value="'+text_variable+'" selected=selected>'+text_variable+'</option>' );
		});
		jQuery('#tags_box').append('</optgroup>');
	}

	tags_list = task_object_received['tags_teams'];
	//alert(tags_list);
	if(tags_list) {
		jQuery('#tags_box').append('<optgroup label="Avaiable">');
		jQuery.each(tags_list, function(i) {
			text_variable = tags_list[i];
			jQuery('#tags_box').append( '<option value="'+text_variable+'">'+text_variable+'</option>' );
		});
		jQuery('#tags_box').append('</optgroup>');
	}
	//jQuery('#tags_box').html(response['tags_list']);

	//jQuery('#tags_box').append( '<option value="'+text_variable+'" selected=selected>'+text_variable+'</option>' );
	//jQuery("#tags_box").insertBefore("<option value=asd selected>asd</option>");
	//RESSSSSEEEEEEEEEEEEEEEEEETTTTTTTTTTTTTTTTTT BUTTONS BEFORE LOAD
	jQuery("#tab-task .btn-group input").attr("checked", "checked");
	jQuery("#tab-task .btn-group label").removeClass("active");
	//
	blink_animation(jQuery("#description_box"), true);
	jQuery("#description_box").attr("value", task_object_received['desc']);
	//
	blink_animation(jQuery("input[value='"+task_object_received['type']+"'").parent(), true);
	jQuery("input[value='"+task_object_received['type']+"'").attr("checked", "checked");
	jQuery("input[value='"+task_object_received['type']+"'").parent().addClass("active");
	//
	blink_animation(jQuery("input[value='"+task_object_received['planned']+"'").parent(), true);
	jQuery("input[value='"+task_object_received['planned']+"'").attr("checked", "checked");
	jQuery("input[value='"+task_object_received['planned']+"'").parent().addClass("active");
	//
	blink_animation(jQuery("input[value='"+task_object_received['virtual']+"'").parent(), true);
	jQuery("input[value='"+task_object_received['virtual']+"'").attr("checked", "checked");
	jQuery("input[value='"+task_object_received['virtual']+"'").parent().addClass("active");
	//type_box
	//jQuery("#title_box").attr("value", task_object_received['title']);
	//loadtask
	jQuery('#tags_box').select2({
		tags: true,
		//closeOnSelect: false,
		//maximumSelectionLength: 3
		tokenSeparators: [","],
		placeholder: function() {
			jQuery(this).data('placeholder');
		}
	});		
	blink_animation(jQuery(".select2-selection__choice__remove"), true);
	blink_background_animation(jQuery(".select2-selection__choice"));
}

function get_current_task_data_object() {
	var type_r = getRadioCheckedValue("type_box");
	var pln_r = getRadioCheckedValue("planned_box");
	var virt_r = getRadioCheckedValue("virtual_box");
	var tags = jQuery("#tags_box").select2('val');
	
	var data = {
		dataType: "json",
		post_titulo: title_box.value,
		post_tags: tags,
		post_descri: description_box.value,
		post_type_type: type_r,
		post_planned: pln_r,
		post_virtual: virt_r,
	}
	return data;
}

function update_currentask_clipboard () {
	console.log("update_pomodoro_clipboard();");
	data = get_current_task_data_object();
	localStorage.setItem('task_object_stored', JSON.stringify(data));
	data['action'] = 'projectimer_update_currentask_clipboard';
	jQuery.post(ajaxurl, data, function(response) {
		if(response=="NOTIN")window.location.href = "/";
		console.log("update_pomodoro_clipboard() response: " + response);
		if(response['response']=="curretask_updated" || response['response']=="curretask_updated_BUT_no_post_meta") {
			alertify.log("Current task updated");
		}
		/*} else {
			if(!$primeiroAviso) {
				$primeiroAviso=true;
				change_status("Você precisa colocar um título na tarefa, antes de salvar")
			} else {
				lert("Você precisa colocar um título na tarefa, antes de salvar")
			}
		}*/
	});
	
}

//EXTRA FUNCTION (settings modal)
function download_csv() {
	alertify.log("Função não habilitada");
}