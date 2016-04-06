<?php
//francisco matelli 2015-02-07
function projectimer_show_clock_simplist () {
	?>
	<div id="clock">
		<button onclick="action_button_press()" id="action_button" tabindex="1" disabled="disabled" />
			<div id="clock-label"><?php echo get_user_meta(get_current_user_id(),"focus_time", true); ?>:00</div>
		</button>
	</div>
	<div id="div_status">&nbsp;</div>

	<?php
}

function projectimer_show_setting_box () {
	if(is_user_logged_in()) {
		$focus_time = get_user_meta(get_current_user_id(), "focus_time", true);

		if(!$focus_time) $focus_time=25;
		$rest_time = round($focus_time/5);
		$cycle_time = $focus_time + $rest_time;
	?>
	<!--h3>
	Tempo do pomodoro:</h3>
	<sub>Recomendamos aos usuários não mudarem o tempo dos pomodoros, se esforce para se adaptar aos 25 minutos que vale a pena</sub-->
	<h2 style="margin-top:0"><?php _e("User settings", "plugin-projectimer"); ?></h2>
	
	<p>
		<label><?php _e("Clockwise", "plugin-projectimer"); ?></label><br />
		<input type="radio" name="clockwise" value="countdown" selected="selected">Countdown<br />
		<input type="radio" name="clockwise" value="lap">Free lap<br />
		<br />
		<?php _e("Countdown or lap", "plugin-projectimer"); ?>
	</p>
	<div id="countdown_box" class="setting_panel">
		<p>
			<label><?php _e("Adjust time focusing on work/study"); ?></label><br />
			<button id="down_minutes" onclick="change_minutes(-1)">-</button>
			<button id="up_minutes" onclick="change_minutes(+1)">+</button>
		</p>
		<p>
			<label><?php _e("Focus time", "plugin-projectimer"); ?></label><br />
			<button id="" disabled="disabled"><span id="focus_time_minutes"><?php echo $focus_time; ?></span>:00</button>
		</p>
		<p>
			<label><?php _e("Rest time", "plugin-projectimer"); ?></label><br />
			<button id="" disabled="disabled"><span id="rest_time_minutes"><?php echo $rest_time; ?></span>:00</button>
		</p>
		<p>
			<label><?php _e("Cycle time", "plugin-projectimer"); ?></label><br />
			<button id="" disabled="disabled"><span id="cycle_time_minutes"><?php echo $cycle_time; ?></span>:00</button>
		</p>
	</div>
	<div id="countdown_box" class="setting_panel">
		<p><?php _e("On countdown you determine your focus time during work, when you decide to stop the proportional rest time is loaded", "plugin-projectimer"); ?></p>
	</div>
	<p id="setting_message" style="background-color:#093;color:#FFF;font-weight:600;padding:3px 6px;">
		<?php 
		if($focus_time==25) {
			_e("You are using Pomodoro Technique oficial time", "plugin-projectimer");
		} else {
			_e("You are using custom settings.", "plugin-projectimer");
		} ?>
	</p>
	<script type="text/javascript">
		//document.getElementById("down_minutes").onclick = change_minutes(-1);
		/* SETTINGS BOX */
		
	</script>
	<!--p>Você pode utilizar nossos sitema para medir o tempo de diversas maneiras, mas lembre-se, para participar dos sorteios de prêmios é preciso usar a configuraćão oficial</p>
	<p>VOLUME: </p>
	<h3>Tipo de relógio</h3>
	<p>Técnica dos Pomodoros - Configuraćões oficiais [participa em sorteios]</p>
	<p>Técnica dos Pomodoros - ConfiguraćÕes do usuário</p>
	<div>
		<form>
		Tempo do pomodoro:
		Tempo do descanso:
		Intervalo entre pomodoros:
		checkbox - Declaro que não participarei dos sorteios
		</form>
	</div>
	<p>Crônometro convencional com intervalo regressivo</p>
	<p>Crônometro convencional sem intervalo</p>
	<h3>Marcador de ponto</h3>
	<p>Ativar marcador de entrada e saída de expediente?</p-->
	<?php
	} else {
		_e("To view user settings you must login", "plugin-projectimer");
	}
}

function projectimer_show_clock_vintage () {
	$pomoAtivoAgora = get_user_meta(get_current_user_id(), "pomodoroAtivo", true);
	//'$base_directory = dirname( __FILE__ ) . "/images/original-clock";
	$base_directory = plugin_dir_url( __FILE__ ) . "images/original-clock";
	
	//var_dump($stylesheet_directory);die;
	echo $base_directory;
	//echo '<img src="'.$base_directory.'/spacer.png" />';
	echo 
	'							
	<form><input type="button" value="FOCAR!" onclick="action_button()" id="action_button_id" tabindex="1" /></form>
	<div id="relogio">
		<div id="back">
		<div id="upperHalfBack">
			<img src="'.$base_directory.'/spacer.png" />
			<img id="minutesUpLeftBack" src="'.$base_directory.'/Double/Up/Left/0.png" class="asd" /><img id="minutesUpRightBack" src="'.$base_directory.'/Double/Up/Right/0.png"/>
			<img id="secondsUpLeftBack" src="'.$base_directory.'/Double/Up/Left/0.png" /><img id="secondsUpRightBack" src="'.$base_directory.'/Double/Up/Right/0.png"/>
		</div>
		<div id="lowerHalfBack">
			<img src="'.$base_directory.'/spacer.png" />
			<img id="minutesDownLeftBack" src="'.$base_directory.'/Double/Down/Left/0.png" /><img id="minutesDownRightBack" src="'.$base_directory.'/Double/Down/Right/0.png" />
			<img id="secondsDownLeftBack" src="'.$base_directory.'/Double/Down/Left/0.png" /><img id="secondsDownRightBack" src="'.$base_directory.'/Double/Down/Right/0.png" />
		</div>
		</div>
		<div id="front">
		<div id="upperHalf">
			<img src="'.$base_directory.'/spacer.png" />
			<img id="minutesUpLeft" src="'.$base_directory.'/Double/Up/Left/0.png" /><img id="minutesUpRight" src="'.$base_directory.'/Double/Up/Right/0.png"/>
			<img id="secondsUpLeft" src="'.$base_directory.'/Double/Up/Left/0.png" /><img id="secondsUpRight" src="'.$base_directory.'/Double/Up/Right/0.png"/>
		</div>
		<div id="lowerHalf">
			<img src="'.$base_directory.'/spacer.png" />
			<img id="minutesDownLeft" src="'.$base_directory.'/Double/Down/Left/0.png" /><img id="minutesDownRight" src="'.$base_directory.'/Double/Down/Right/0.png" />
			<img id="secondsDownLeft" src="'.$base_directory.'/Double/Down/Left/0.png" /><img id="secondsDownRight" src="'.$base_directory.'/Double/Down/Right/0.png" />
		</div>
		</div>
	</div><!--fecha relogio-->
	<input type="text" disabled="disabled" id="secondsRemaining_box">
	<ul id="pomolist">
		<li class="pomoindi" id="pomoindi1">&nbsp;</li>							
		<li class="pomoindi" id="pomoindi2">&nbsp;</li>
		<li class="pomoindi" id="pomoindi3">&nbsp;</li>
		<li class="pomoindi" id="pomoindi4">&nbsp;</li>
	</ul>
	<button onclick="reset_pomodoro_session()" style="margin: 13px 0 0 17px;">0</button>
	</div><!--fecha pomodoros painel-->
	<br />
	<div id="div_status" class="vintage_clock_status"><script>document.write(txt_mat_introducing)</script></div>
	<img src="$stylesheet_directory/mascote_foca.png" />
	<br />
	<form name="pomopainel">
		<label><script>document.write(txt_write_task_title)</script></label><br />
		<input type="text" size="46" id="title_box" maxlength="70" tabindex="2" name="ti33"></input><br />
		<label><script>document.write(txt_write_task_tags)</script></label>
		<!--select id="tags_box" tabindex="3">
			<option></option>
			<option value="AL">Alabama</option>
			<option value="WY">Wyoming</option>
			<option value="AL">Alabama</option>
			<option value="WY">Wyoming</option>
		</select-->
		<input type="text" size="46" id="tags_box" maxlength="20" tabindex="3"></input>
		<br />
		<label><script>document.write(txt_write_task_desc)</script></label>
		<textarea rows="4" cols="34" id="description_box" tabindex="4"></textarea><br />
		<input type="hidden" id="data_box">
		<input type="hidden" id="status_box">
		<input type="hidden" id="post_id_box">
		<input type="hidden" id="pomodoroAtivoBox" value="$pomoAtivoAgora">
		<br />
		<label><script>document.write(txt_write_task_category)</script></label><br />
		<ul>
			<li><input type="radio" name="cat_vl" value="26">Estudo</li>
			<li><input type="radio" name="cat_vl" value="27">Trabalho</li>
			<li><input type="radio" name="cat_vl" value="28">Pessoal</li>
		</ul>
		<label><script>document.write(txt_write_task_privacy)</script></label><br />
		<ul>
			<li><input type="radio" name="priv_vl" value="publish" CHECKED>Público - todos podem ver.</li>
			<li><input type="radio" name="priv_vl" value="private" >Privado - somente você pode ver. </li>
		</ul>
	</form>
	<input type="button" value="Guardar tarefa modelo" onclick="save_model()" id="botao-salvar-modelo" />';
	/*
	<h3>Tarefas modelo</h3>
	<p>Ficou mais fácil recomeçar uma tarefa, salve a tarefa como um modelo e reutilize quantas vezes quiser. Confira sua lista de modelos:</p>
	
	<div id="contem-modelos">';
	
	$args = array(
              "post_type" => "post",
              "post_status" => "pending",
              "author"   => get_current_user_id(),
              //"orderby"   => "title",
              "order"     => "ASC",
              "posts_per_page" => 14,
            );
	$the_query = new WP_Query( $args );
	
	while ( $the_query->have_posts() ) : $the_query->the_post();
		$counter = $post->ID;
		echo '<ul id="modelo-carregado-'.$counter.'">';
		the_title("<input type='text' value='","' disabled=disabled id=bxtitle$counter /><br />");

		echo "<input type='text' value='".get_the_content()."' disabled=disabled id=bxcontent$counter /><br />";
		//http://stackoverflow.com/questions/2809567/showing-tags-in-wordpress-without-link
		$posttags = get_the_tags();
		  if ($posttags) {
		    foreach($posttags as $tag) {
			echo "<input type='text' value='".$tag->name."' disabled=disabled  id=bxtag$counter />";
		    }
		}
		echo "<p><input type='button' value='usar modelo' onclick='load_model($counter)'>&nbsp; <input type='button' value='arquivar' onclick='delete_model($counter)'></p>";
		echo '</li>';
		echo '</ul>';
		
	endwhile;
	// Reset Post Data
	wp_reset_postdata();
	?>
	
	
	<?php
	get_currentuserinfo();
	if(current_user_can('administrator')) {
	echo '</div>';
	}
	?>

	<?php */
}

function projectimer_show_clock_futuristic () {
	//TODO: passar width e height por parametro
	echo 
	'<div id="shadow"></div>
	<canvas width="50%" height="50%" id="myCanvas"></canvas>
	<div id="shine"></div>
	<input type="button" value="focus" onclick="action_button()" id="action_button_id" />
	<div id="div_status" style="background:#FFF;">Teste</div>';
}

function projectimer_show_task_form () {
	?>
	<form name="pomopainel">
		<label><?php _e("Task", "plugin-projectimer"); ?></label><br />
		<input type="text" size="46" id="title_box" maxlength="70"></input><br />
		<label><?php _e("Project", "plugin-projectimer"); ?></label>
		<textarea rows="4" cols="34" id="description_box"></textarea><br />
		<label><?php _e("Description", "plugin-projectimer"); ?></label>
		<input type="text" size="46" id="tags_box" maxlength="20"></input><br />
		
		<br />
		<label><?php _e("Task", "plugin-projectimer"); ?></label><br />
		<ul>
			<li><input type="radio" name="cat_vl" value="" selected="selected"><?php _e("Not selected", "plugin-projectimer"); ?></li>
			<li><input type="radio" name="cat_vl" value="26"><?php _e("Study", "plugin-projectimer"); ?></li>
			<li><input type="radio" name="cat_vl" value="27"><?php _e("Work", "plugin-projectimer"); ?></li>
			<li><input type="radio" name="cat_vl" value="28"><?php _e("Personal", "plugin-projectimer"); ?></li>
		</ul>
		<p>You can change privacy on user settings.</p>
		<!--label><?php _e("Privacity", "plugin-projectimer"); ?></label><br />
		<ul>
			<li><input type="radio" name="priv_vl" value="publish" CHECKED><?php _e("Public - everyone can see", "plugin-projectimer"); ?></li>
			<li><input type="radio" name="priv_vl" value="private" ><?php _e("Private - only you can see", "plugin-projectimer"); ?></li>
		</ul-->
		
	</form>
	<?php
}