/* DONT CHANGE ANYTHING BELOT */

var mitten = 200;
var backColor = 'rgba(50,50,50,.4)';
var alpha = 0.9;

var canvas = document.getElementById('myCanvas');
canvas.width = canvas.width;
//change_status(canvas.width);
canvas_update_on_every_count();
var shadow = {
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


/*var check=function(count, setup, ctx){
	if (count<setup.old){
		setup.counter++
	}
	if(setup.counter%2===0){
	  setup.counter = 0;
		update_canvas(setup.radie,setup.color,setup.lineWidth,0,count,ctx);
	}
	else{
		update_canvas(setup.radie,setup.color,setup.lineWidth,count,0,ctx);
	}
}*/


var update_canvas=function(radie,color,lineWidth,firstCount,secondCount,ctx){
	ctx.beginPath();//intervalMiliseconds
	ctx.arc(mitten, mitten, radie, firstCount*Math.PI, ((secondCount)*Math.PI), false);
	ctx.lineWidth = lineWidth;
	ctx.shadowColor=shadow.color; 
	ctx.shadowOffsetX=shadow.offsetX; 
	ctx.shadowOffsetY=shadow.offsetY; 
	ctx.shadowBlur=shadow.blur;
	ctx.strokeStyle = color;
	ctx.stroke();
}
function canvas_update_on_every_count () {
	var canvas = document.getElementById('myCanvas');
	canvas.width = canvas.width;
	mitten = canvas.width/2;
	
  	var ctx = canvas.getContext('2d');
	//varvara = (((secondsRemaing/focusTime)-1)*2);//make the count stops after reach 360 degrees
	//jQuery("body").click(function() {
	//	action_button();
	//});
	//----BACKGROUND
	var background=function(){
		update_canvas(milliSetup.radie,backColor,milliSetup.back,0,2,ctx);
		update_canvas(milliSetup.radie,backColor,milliSetup.lineWidth,0,2,ctx);

		update_canvas(secSetup.radie,backColor,secSetup.back,0,2,ctx);
		update_canvas(secSetup.radie,backColor,secSetup.lineWidth,0,2,ctx);

		update_canvas(focusSetup.radie,backColor,focusSetup.back,0,2,ctx);
		update_canvas(focusSetup.radie,backColor,focusSetup.lineWidth,0,2,ctx);

		update_canvas(hourSetup.radie,backColor,hourSetup.back,0,2,ctx);
		update_canvas(hourSetup.radie,backColor,hourSetup.lineWidth,0,2,ctx);
	}
	
	if (backgroundCheck) {
		background();
	}
	
	//-----FOREGROUND
	var d = new Date();
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
	var milliCount = (2/1000)*milliSekunder;
	
	update_canvas(focusSetup.radie,focusSetup.color,focusSetup.lineWidth,varvara,0,ctx);
	
}
