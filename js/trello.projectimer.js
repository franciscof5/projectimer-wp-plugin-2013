var authenticationSuccess = function() {
	alertify.success('Successful Trello Authentication');
	jQuery('#trello-container').html('Viewing your boards');
	jQuery("#trello-status").html('<span class="label label-success">Trello status: Authorized</span>');
	getBoards();
};
var authenticationFailure = function() {
	alertify.error('Trello Failed to Authenticate');
};
function askAuth () {
	Trello.authorize({
	  type: 'popup',
	  name: 'Focalizador_Brasil',
	  scope: {
	    read: 'true',
	    write: 'true' },
	  expiration: 'never',
	  success: authenticationSuccess,
	  error: authenticationFailure
	});
}
function checkAlreadyAuthenticatedTrello() {
	var success = function(successMsg) {
		//asyncOutput(successMsg);
	  	authenticationSuccess();
	};

	var error = function(errorMsg) {
jQuery('#trello-container').html('<p>For load your Trello tasks and fully integrate please</p><button  type="button" class="btn btn-primary" onclick="askAuth()">Load Trello Cards and Boards</button>');
	jQuery("#trello-status").html('<span class="label label-warning">Trello status: Not started</span><h4>More info</h4><small>Projectimer Trello integrations provides an easy and secure way to load your boards and cards, it also update and complete your tasks withouting leave our app, boards receive and update status when your timmer ends</small>');
	//authenticationFailure();
	};

	Trello.get('/member/me/boards', success, error);
}
checkAlreadyAuthenticatedTrello();
function getBoards() {
	// Get all of the information about the boards you have access to
	jQuery("#trello-status").html('<span class="label label-warning">Trello status: Attemping to get user boards</span>');
	
	var success = function(Boards) {
		//asyncOutput(successMsg);
		//alert(successMsg);
		//
		/*
		jQuery("#trello-container").html('<div class="panel-group" id="accordion">');
		
		jQuery.each(Boards, function(i) { 
			//alert(Boards[i].name);
			jQuery('#trello-container').append('<div class="panel panel-default">aaaa</div>');
				jQuery('#trello-container').append('<div class="panel-heading">');
					
					jQuery('#trello-container').append('<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse'+i+'">'+Boards[i].name+'</a></h4>');
					
				jQuery('#trello-container').append('</div>');
				jQuery('#trello-container').append('<div id="collapse'+i+'" class="panel-collapse collapse in">');
					jQuery('#trello-container').append('<div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex eacommodo consequat.</div>');
				jQuery('#trello-container').append('</div>');
			//jQuery('#trello-container').append("</div> ");
			
		});
  		jQuery('#trello-container').append("</div> ");
  		*/
  		var i_copy;
  		var i_dirty_copy = 0;
  		var successList = function(insideCards) {
	  	jQuery("#trello-status").html('<span class="label label-success">Trello status: Card loaded</span>');
			cardsInsideAppend = "";
			jQuery.each(insideCards, function(i) { 
				cardsInsideAppend += insideCards[i].name+"<br />";
			});
			//alert(cardsInsideAppend);
			jQuery("#card_container"+boards_array[i_dirty_copy]).html(cardsInsideAppend);
			i_dirty_copy++;
		};

		var errorList = function(errorMsg) {
			alertify.error("Problem read cards");
		};
  		//var varAppend = '<div class="panel-group" id="accordion"></div>';
  		boards_array = new Array();
  		jQuery("#trello-container").html('<div class="panel-group" id="accordion-boards"></div>');
  		//var varAppend;
  		jQuery.each(Boards, function(i) { 
  			i_copy = i;
  			varAppend = '';
  			varAppend += '<div class="panel panel-default">';
  			varAppend += '<div class="panel-heading">';
  			varAppend += '<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse'+i+'">'+Boards[i].name+'</a></h4>';
  			varAppend += '</div>';

  			varAppend += '<div id="collapse'+i+'" class="panel-collapse collapse">';
  			varAppend += '<div class="panel-body" id="card_container'+Boards[i].id+'"></div>';
  			varAppend += '</div>';
  			varAppend += '</div>';
  			boards_array.push(Boards[i].id);
  			jQuery("#accordion-boards").append(varAppend);
  			
  			//alert("i :"+i+"i_copy"+i_copy);
  			Trello.get('/boards/'+Boards[i].id+'/cards', successList, errorList);

  		});
  		//varAppend += '</div>';
  		//jQuery("#trello-container").html(varAppend);
  		jQuery("#trello-status").html('<span class="label label-success">Trello status: Boards loaded</span>');
    
 /* 
 <div class="panel-group" id="accordion">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Collapsible Group 1</a>
        </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse in">
        <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Collapsible Group 2</a>
        </h4>
      </div>
      <div id="collapse2" class="panel-collapse collapse">
        <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Collapsible Group 3</a>
        </h4>
      </div>
      <div id="collapse3" class="panel-collapse collapse">
        <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
      </div>
    </div>
  </div> */

		
		/*jQuery('#trello-container').html("<ul>");
		jQuery.each(Boards, function(i) { 
			jQuery('#trello-container').append("<li>"+Boards[i].name+"</li>");
		});
		jQuery('#trello-container').append("</ul>");*/
	};

	var error = function(errorMsg) {
	  //asyncOutput(errorMsg);
	  jQuery("#trello-status").html('<span class="label label-error">Trello status: error getting users boards</span>');
	};

	Trello.get('/member/me/boards', success, error);

}
