//
var grupoDeComandos = [{
	    indexes:["iniciar", "focar", "começar", "interromper"], // These spoken words will trigger the execution of the command
	    action:function(){ // Action to be executed when a index match with spoken word
	        artyom.say("Pois não");//era: comando recebido
	        action_button();
	    }
	},
];

var groupOfCommands = [{
	    indexes:["start", "focus", "begin", "interrupt", "stop"], // These spoken words will trigger the execution of the command
	    action:function(){ // Action to be executed when a index match with spoken word
	        artyom.say("Ok");
	        action_button();
	    }
	},
];

// This function activates artyom and will listen all that you say forever (requires https conection, otherwise a dialog will request if you allow the use of the microphone)
function startContinuousArtyom(){
	artyom = new Artyom();
	//
    artyom.fatality();// use this to stop any of
    //
    //alert(data_from_php.php_locale);
    if(data_from_php.php_locale=="pt_BR")
    	artyom_lang = "pt-PT";
    else
    	artyom_lang = "en-US";
    //
    setTimeout(function(){// if you use artyom.fatality , wait 250 ms to initialize again.
         artyom.initialize({
            lang:artyom_lang,// A lot of languages are supported. Read the docs !
            continuous:true,// Artyom will listen forever
            listen:true, // Start recognizing
            debug:true, // Show everything in the console
            speed:1, // talk normally
            //name: "pomodoro",
        }).then(function(){
            console.log("Ready to work !");
        });
    },250);
    //
    if(data_from_php.php_locale=="pt_BR")
    	artyom.addCommands(grupoDeComandos);
    else
    	artyom.addCommands(groupOfCommands);

    artyom.addCommands(groupSwith);

    /*artyom.when("COMMAND_RECOGNITION_END",function(status){
	          startContinuousArtyom();
	   
	});
	artyom.when("SPEECH_SYNTHESIS_END",function(){
	          startContinuousArtyom();
	    
	});*/
}


var groupSwith = [{
    // note that if you're in spanish for example
    // the command should be instead : "Iniciar en ingles, Iniciar en alemán" etc...
    indexes:["switch to portuguese","switch to english"],
    action: function(i){
        switch(i){
            case 0: start("pt-PT"); break;
            case 1: start("en-US"); break;
        }
    }
}];

function startNoSleepWakeLock() {
	noSleep = new NoSleep();
	function enableNoSleep() {
	  noSleep.enable();
	  document.removeEventListener('touchstart', enableNoSleep, false);
	  document.removeEventListener('click', enableNoSleep, false);
	}
	document.addEventListener('touchstart', enableNoSleep, false);
	document.addEventListener('click', enableNoSleep, false);
}

//SLIDER VOLUME
function initSlider(){
	bar = document.getElementById('bar');
	slider = document.getElementById('slider');
	info = document.getElementById('info');
	bar.addEventListener('mousedown', startSlide, false);	
	bar.addEventListener('mouseup', stopSlide, false);
}

function startSlide(event){
	var set_perc = ((((event.clientX - bar.offsetLeft) / bar.offsetWidth)).toFixed(2));
	info.innerHTML = 'start' + set_perc + '%';	
	bar.addEventListener('mousemove', moveSlide, false);	
	slider.style.width = (set_perc * 100) + '%';	
}

function moveSlide(event){
	var set_perc = ((((event.clientX - bar.offsetLeft) / bar.offsetWidth)).toFixed(2));
	info.innerHTML = 'moving : ' + set_perc + '%';
	slider.style.width = (set_perc * 100) + '%';
}

function stopSlide(event){
	var set_perc = ((((event.clientX - bar.offsetLeft) / bar.offsetWidth)).toFixed(2));
	info.innerHTML = 'done : ' + set_perc + '%';
	bar.removeEventListener('mousemove', moveSlide, false);
	slider.style.width = (set_perc * 100) + '%';
}

initSlider();