//Get user custom configuration
function loadCustomUserConfiguration {
	/*var data = {
		action: 'load_user_config'
	};
	jQuery.post(ajaxurl, data, function(response) {
		if(response) {
			//change_status(txt_deleting_model_sucess);
			jQuery("#modelo-carregado-"+qualmodelo).remove();
			alert(response);
		} else {
			//change_status(txt_save_error);
		}
	});*/
}

//Funtions to save pomodoros
function savepomo () {
	//change_status(txt_salving_progress);	
	
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

function delete_model(qualmodelo) {
	//PHP deletar post qualmodelo
	//change_status(txt_deleting_model);
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
