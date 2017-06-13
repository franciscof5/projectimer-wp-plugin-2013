//created by Francisco Matelli Matulovic: 2017-06-09
function loadTaskFromTrello(taskName, taskTags, taskDesc) {
	//alert("parent.name")
	alertify.log("Loading "+taskName+" from Trello");
	var array = taskTags.split(',');
	var array_unique = Array();
	//UNIQUENESS
	jQuery.each(array, function(i, e) {
	    if (jQuery.inArray(e, array_unique) == -1) array_unique.push(e);
	});
	//
	var task_object_from_trello_single_task = Array();
	task_object_from_trello_single_task['title'] = taskName;
	task_object_from_trello_single_task['tags'] = array_unique;
	task_object_from_trello_single_task['desc'] = taskDesc;
	load_task_object(task_object_from_trello_single_task);
}

//Login
function checkAlreadyAuthenticatedTrello() {
	var success = function(successMsg) {
		//asyncOutput(successMsg);
		authenticationSuccess();
	};
	//not attemped to connect yet
	var error = function(errorMsg) {
		jQuery('#trello-container').html('<p>For load your Trello tasks and fully integrate please</p><button  type="button" class="btn btn-primary" onclick="askAuth()">Load Trello Boards</button>');
		jQuery("#trello-status").html('<span class="label label-warning">Trello status: Not started</span><h4>More info</h4><small>Projectimer Trello integrations provides an easy and secure way to load your boards and cards, it also update and complete your tasks withouting leave our app, boards receive and update status when your timmer ends</small>');
	
	};
	//Try to connect
	Trello.get('/member/me/boards', success, error);
}
checkAlreadyAuthenticatedTrello();

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
var authenticationSuccess = function() {
	alertify.success('Successful Trello Authentication');
	jQuery('#trello-container').html('Viewing your boards');
	jQuery("#trello-status").html('<span class="label label-success">Trello status: Authorized</span>');
	jQuery("#trelloLoadOptions").hide(3000);
	//jQuery("#trelloLoadOptions").html('<br /><button class="btn btn-default col-md-4">Reload Trello</button>');
	getBoards();
};
var authenticationFailure = function() {
	alertify.error('Trello Failed to Authenticate');
};

//Trello Objects Management
var currentBoardIdLoading;
var currentListIdCounter=0;
var listContainerOfCardsArray = new Array();
function getBoards() {
	// Get all of the information about the boards you have access to
	jQuery("#trello-status").html('<span class="label label-warning">Trello status: Attemping to get user boards</span>');
	
	var success = function(Boards) {
  		var i_copy;
  		var i_dirty_copy = 0;
  		boards_array = new Array();  		
  		
  		jQuery("#trello-container").html('<div class="panel-group" id="accordion-boards"></div>');
  		//var varAppend;
  		jQuery.each(Boards, function(i) { 
  			varAppend = '';
  			varAppend += '<div class="panel panel-default">';
  			varAppend += '<div class="panel-heading">';
  			varAppend += '<h4 class="panel-title board-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse'+Boards[i].id+'">'+Boards[i].name+'</a></h4>';
  			varAppend += '</div>';

  			varAppend += '<div id="collapse'+Boards[i].id+'" class="panel-collapse collapse">';
  			loadBoardListsButton = Boards[i].name+"<br /><button class='btn btn-primary' onclick='loadList(\""+Boards[i].id+"\");'>Load Lists and Cards</button>";
  			varAppend += '<div class="panel-body" id="card_container'+Boards[i].id+'">'+loadBoardListsButton+'</div>';
  			varAppend += '</div>';
  			varAppend += '</div>';
  			jQuery("#accordion-boards").append(varAppend);
  		});
  		//varAppend += '</div>';
  		//jQuery("#trello-container").html(varAppend);
  		jQuery("#trello-status").html('<span class="label label-success">Trello status: Boards loaded</span>');
	};

	var error = function(errorMsg) {
		//
		jQuery("#trello-status").html('<span class="label label-error">Trello status: error getting users boards</span>');
	};

	Trello.get('/member/me/boards', success, error);
}

function loadList(boardId) {
	//
	currentBoardIdLoading=boardId;
	var successList = function(insideList) {
		jQuery("#trello-status").html('<span class="label label-success">Trello status: Card loaded</span>');
		listInsideAppend = '<div class="container-fluid" style="padding: 0"><div class="row"><div class="col-lg-12" style="padding: 0"><div class="accordion-wrapper" style="margin: 0"><div class="panel-g-horizonta" id="accordion" role="tablist" aria-multiselectable="true" style="margin: 0">';

		jQuery.each(insideList, function(i) { 
				//listInsideAppend += insideList[i].name+'<div class="panel panel-default">';
				id = insideList[i].id;
				currentListIdLoading = id;
				listInsideAppend += '<div class="panel panel-default">';
				listInsideAppend += '<div class="panel-heading" role="tab" id="heading'+id+'">';
				listInsideAppend += '<h4 class="panel-title list-title">';
				listInsideAppend += '<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'+id+'" aria-expanded="true" aria-controls="collapse'+id+'">'+insideList[i].name+'</a>';
				listInsideAppend += '';
				listInsideAppend += '</h4></div>';
				listInsideAppend += '<div id="collapse'+id+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading'+id+'" style="height:14rem !important;">';
				listInsideAppend += '<div class="panel-b-horizontal">';
				//listInsideAppend += '<h4>'+insideList[i].name+'</h4>'
				listInsideAppend += '<ul id="insideListList'+id+'" class="pbginsideListList"><li>Empty list</li></ul>';
				listInsideAppend += '</div>';
				listInsideAppend += '</div></div>';
				listContainerOfCardsArray.push(id);
				Trello.get('/lists/'+id+'/cards', successListCards, errorListCards);		
			});
		listInsideAppend += '</div></div></div></div></div>';
		//
		jQuery("#card_container"+currentBoardIdLoading).html(listInsideAppend);
	};

	var errorList = function(errorMsg) {
		//
		alertify.error("Problem reading Trello lists");
	};
	//
	Trello.get('/boards/'+boardId+'/lists', successList, errorList);
}

//LoadList callbacks
function successListCards(cardsInside) {
	//
	var cardsInsideAppend = "";
	var idList;
	var tagList=Array();
	var tagListName="";
	var tagBoardName="";
	//
	if(typeof cardsInside[0].idList != "undefined")
		idList = cardsInside[0].idList;//setted more times than need, dirty solution
	else
		idList="";//empty return

	//
	if(jQuery("#check-boards").is(':checked'))
	tagList.push(jQuery("#insideListList"+idList).parent().parent().parent().find(".list-title").text());
	if(jQuery("#check-lists").is(':checked'))
	tagList.push(jQuery("#insideListList"+idList).parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().find('.board-title').text());
	//
	jQuery.each(cardsInside, function(i){
		jQuery.each(cardsInside[i].labels, function(ia) {
			tagList.push(this.name);
		});	
		if(cardsInside[i].desc) {
			//desc = cardsInside[i].desc.replace(/\'/g,"");
			//desc = cardsInside[i].desc.replace(/\"/g,"");
			desc = cardsInside[i].desc.replace(/\n/g,". ");
			desc = desc.replace(/\"/g,"");
			desc = desc.replace(/\'/g,"");
		}
		else
			desc = "";
		cardsInsideAppend += '<li><a href="#" onclick="loadTaskFromTrello(\''+cardsInside[i].name+'\',\''+tagList+'\',\''+desc+'\')">'+cardsInside[i].name+'</a></li>';
	});

	jQuery("#insideListList"+idList).html(cardsInsideAppend);
}

function errorListCards() {
	alertify.error("Can't get Trello cards");
}

