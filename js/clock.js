//Configurantion vars, will be seted by user in future
var pomodoros_to_big_rest=4;

var focusTime = 150000;
var restTime = 300;
var intervalTime = 1800;

//Session control vars
var actualCicle = 1;
var is_focus_time = true;
var secondsRemaing = focusTime;
var is_countdown_active = false;

//What about declate functions up here?
function action_button(){};


window.onload=function() {
	//check_if_already_has_time_active();
	startClock();
}


//There only one button at the page, all the actions (trigglers) start here
function action_button() {
	//change_status("button pushed, start all");
	if(is_countdown_active) {
		//The user clicked on Interrupt button -> Check if the timmer (countdown_clock()) are running
		interrupt();
	} else {
		//The user clicked on Pomodoro or Rest button
		startClock();
		//The end of the big rest, the indicators light has to reset
		//if(pomodoro_actual==1)
		//turn_off_pomodoros_indicators();
	}
}


//Make the code more legible
function startClock() {
	//interval_clock = setInterval('countdown_clock()', intervalMiliseconds);
	//change_status('startClock');
	is_countdown_active = setInterval('count()', 1000);
	/*var div = $('#my-div');
	var leftValue = 0;
	var delay = (1000 / 30);
	var now, before = new Date();

	is_countdown_active = setInterval(function() {
		now = new Date();
		var elapsedTime = (now.getTime() - before.getTime());
		if(elapsedTime > delay) {
		    //Recover the motion lost while inactive.
		    //secondsRemaing--;
		    leftValue += Math.floor(elapsedTime/delay);
		    secondsRemaing-=Math.floor(elapsedTime/delay);
		} else {
		    secondsRemaing--;
		}
		secondsRemaing--;
		alert(secondsRemaing);
		count();
		div.css("left", leftValue);
		before = new Date();    
	}, delay);*/
	//active_sound.play();
	//Chage button to "interrupt"
	//change_button(textInterrupt, "#0F0F0F");
	//change_status(txt_started_countdown);
	//
	
}
function interrupt() {
	//change_status('int');
	
	
	//pomodoro_completed_sound.play();
	//change_status(txt_interrupted_countdowns);
	stop_clock();
	//convertSeconds(0);
	//flip_number();
	//change_button(textPomodoro, "#063");
	//secondsRemaing=0;
	//secondsRemaing = focusTime;
	//if(!is_pomodoro)is_pomodoro=true;
}

//Just stop de contdown_clock function at certains moments
function stop_clock() {
	window.clearInterval(is_countdown_active);
	is_countdown_active=false;
	//TODO Deletar draft
	//is_interrupt_button = false;
	//pomodoro_completed_sound.play();
}
/*window.onload=function(){
	var interval=setInterval(function(){count()},0);
};*/

var mitten = 200;
var backColor = 'rgba(50,50,50,.4)';
var alpha = 0.9;

//var ccanvas = document.getElementById('myCanvas');
//ccanvas.width = ccanvas.width;
//change_status(canvas.width);

var shadow ={
	color:"rgba(0,0,0,1)",
	offsetX:1,
	offsetY:0,
	blur:1
}

var hourSetup = {
	radie:175,
	lineWidth:25,
	back:48,
	color:"rgba(255,200,0,"+alpha+")",	
	counter:0,
	old:0,
}
var focusSetup = {
	radie:140,
	//radie = 140*400/canvas.width,
	lineWidth:35,
	back:45,
	color:"rgba(255,30,94,"+alpha+")",	
	counter:0,
	old:0,
}

var secSetup = {
	radie:90,
	lineWidth:55,
	back:65,
	color:"rgba(90,192,255,"+alpha+")",
	counter:0,
	old:0,
}

var milliSetup = {
	radie:55,
	lineWidth:5,
	back:20,
	color:"rgba(30,255,100,"+alpha+")",	
	counter:0,
	old:0,
}

var backgroundCheck = false;

//var count=function() { //is there any reason why these was wrote that way?
function count() {
	change_status("Abra alas");
	var canvas = document.getElementById('myCanvas');
	canvas.width = canvas.width;
	mitten = canvas.width/2;
	
  	var ctx = canvas.getContext('2d');
	varvara = (((secondsRemaing/focusTime)-1)*2);
	
	secondsRemaing--;
	change_status(secondsRemaing);
	
	if(secondsRemaing==0)
	interrupt();
	
	jQuery("#div_status").html(secondsRemaing);
	//focusSetup.radie = 200;
	/*jQuery("body").click(function() {
	 	if(!backgroundCheck){
			backgroundCheck = true;
		}
		else{
			backgroundCheck = false;
		}
	});*/
	jQuery("body").click(function() {
		action_button();
	});
	//----BACKGROUND
	var background=function(){
		update(milliSetup.radie,backColor,milliSetup.back,0,2,ctx);
		update(milliSetup.radie,backColor,milliSetup.lineWidth,0,2,ctx);

		update(secSetup.radie,backColor,secSetup.back,0,2,ctx);
		update(secSetup.radie,backColor,secSetup.lineWidth,0,2,ctx);

		update(focusSetup.radie,backColor,focusSetup.back,0,2,ctx);
		update(focusSetup.radie,backColor,focusSetup.lineWidth,0,2,ctx);

		update(hourSetup.radie,backColor,hourSetup.back,0,2,ctx);
		update(hourSetup.radie,backColor,hourSetup.lineWidth,0,2,ctx);
	}
	
	if (backgroundCheck){
		background();
	}
	
	//-----FOREGROUND
	/*var d = new Date();
	var milliSekunder = d.getMilliseconds();
	var sekunder = (d.getSeconds()+((d.getMilliseconds()/1000)));
	var minuter = (d.getMinutes()+(sekunder/60));
	var timmar = (d.getHours()+(minuter/60));
	if(timmar>12){
		timmar = timmar-12;
	}
	var hourCount = (2/12)*timmar;
	var minCount = (2/60)*minuter;
	var secCount = (2/60)*sekunder;
	var milliCount = (2/1000)*milliSekunder;*/
	
	update(focusSetup.radie,focusSetup.color,focusSetup.lineWidth,varvara,0,ctx);
	
	
	//change_status(secondsRemaing/focusTime);
	
	
	//check(milliCount,milliSetup,ctx);
	//check(secCount,secSetup,ctx);
	//check(minCount,focusSetup,ctx);
	//check(hourCount,hourSetup,ctx);
	
	/*milliSetup.old = milliCount-0.01;
	secSetup.old = secCount-0.01;
	focusSetup.old = minCount-0.01;
	hourSetup.old = hourCount-0.01;*/
}
/*var check=function(count, setup, ctx){
	if (count<setup.old){
		setup.counter++
	}
	if(setup.counter%2===0){
	  setup.counter = 0;
		update(setup.radie,setup.color,setup.lineWidth,0,count,ctx);
	}
	else{
		update(setup.radie,setup.color,setup.lineWidth,count,0,ctx);
	}
}*/

//Function to show status warnings at bottom of the clock
function change_status(txt) {
	var status=document.getElementById("div_status");
	status.innerHTML=txt; //status.innerHTML="Status: "+txt;
}

var update=function(radie,color,lineWidth,firstCount,secondCount,ctx){
	ctx.beginPath();
	ctx.arc(mitten, mitten, radie, firstCount*Math.PI, secondCount*Math.PI, false);
	ctx.lineWidth = lineWidth;
	ctx.shadowColor=shadow.color; 
	ctx.shadowOffsetX=shadow.offsetX; 
	ctx.shadowOffsetY=shadow.offsetY; 
	ctx.shadowBlur=shadow.blur;
	ctx.strokeStyle = color;
	ctx.stroke();
}
	
/*
soundManager.url = templateDir+'/pomodoro/soundmanager2.swf';
soundManager.onready(function() {
	// Ready to use; soundManager.createSound() etc. can now be called.
	active_sound = soundManager.createSound({id: 'mySound2',url: templateDir+'/pomodoro/sounds/crank-2.mp3',});
	pomodoro_completed_sound = soundManager.createSound({id:'mySound3',url: templateDir+'/pomodoro/sounds/telephone-ring-1.mp3',});
});			
*/
