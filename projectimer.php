<?php
/**
 * Plugin Name: Projectimer
 * Plugin URI: http://projectimer.com
 * Description: A timer management focused on projects
 * Version: 0.1
 * Author: Francisco Matelli
 * Author URI: http://franciscomatelli.com
 * License: Not licensed at all
 */

/* INIT */
add_action('init', 'projectimer_loadScripts');
add_action('init', 'projectimer_CheckActivity');

/* TEMPLATE TAGS */
add_action( 'projectimer_showClock', 'projectimer_showClock' );
add_action( 'projectimer_showTaskForm', 'projectimer_showTaskForm' );

/* AJAX */
add_action('wp_ajax_save_modelnow', 'save_modelnow');
add_action('wp_ajax_nopriv_save_modelnow', 'save_modelnow');
add_action('wp_ajax_save_progress', 'save_progress');
add_action('wp_ajax_nopriv_save_progress', 'save_progress');
//add_action('wp_ajax_load_user_config', 'load_user_config');
//add_action('wp_ajax_nopriv_load_user_config', 'load_user_config');


//projectimer_CheckActivity();
/* INITS */
function projectimer_loadScripts() {  
	//LOADED AFTER CHECK ACTIVIVY
	//wp_register_script( 'tips', get_template_directory_uri() . '/pomodoro/tips.js', array(), '0.1', true );
	wp_register_script('soundmanager', plugins_url('/js/soundmanager2-nodebug-jsmin.js', __FILE__) );
	wp_register_script('clockscript', plugins_url('/js/clock.js', __FILE__) );
	wp_register_script('clockAjax', plugins_url('/js/clock_ajax.js', __FILE__) );
	wp_enqueue_script( 'soundmanager' );
	wp_enqueue_script( 'clockscript' );  
	wp_enqueue_script( 'clockAjax' );  
	//
	wp_register_style('clockcss', plugins_url('/css/clock.css', __FILE__) );
	wp_enqueue_style('clockcss');
	wp_enqueue_script( 'jquery' );
	update_user_meta(get_current_user_id(), "focusTime", 150000);
	
}


//to check if the timmer is running
function projectimer_CheckActivity() {
	//Return posts from the last FOCUSED TIME minutes:
	// Create a new filtering function that will add our where clause to the query
	function filter_where( $where = '' ) {
		// posts in the last FOCUSED TIME minutes TODO
		$where .= " AND post_date > '" . date('Y-m-d H:i:s', strtotime("-$focusTime seconds")) . "'";
		return $where;
	}

	add_filter( 'posts_where', 'filter_where' );
	$query = new WP_Query( array('author=' => get_current_user_id(), 'post_status' => 'draft' ));
	remove_filter( 'posts_where', 'filter_where' );
	
	if($query->have_posts()) {
		//Found a cicle running, we must find how much seconds to the end
		while ( $query->have_posts() ) {
			$query->the_post();
			
			
			$focusTime = get_user_meta(get_current_user_id(), "focusTime", true);
			$start_date = new DateTime(get_the_time('Y-m-d H:i:s'));
			$since_start = $start_date->diff(new DateTime('NOW'));
			$secs = $since_start->days * 24 * 60;
			$secs += $since_start->h * 60;
			$secs += $since_start->i * 60;
			$secs += $since_start->s;
			$secs = $focusTime-$secs;
			
			echo '
				<script language="javascript" type="text/javascript">
					var focusTime = '.$focusTime.';
					var secondsRemaing = '.$secs.';
				</script>
				';
			//$minutes = $since_start->days * 24 * 60;
			////$minutes += $since_start->h * 60;
			//$minutes += $since_start->i;
			//echo $secs.' secs';
			//echo get_the_title();
			//die;
		}
			//projectimer_loadScripts();
	} else {
		//MESMA QUERY MAS DELETANDO TODOS OS DRAFTS
		/*$query = new WP_Query( array('author=' => get_current_user_id(), 'post_status' => 'draft' ));
		if($query->have_posts()) {
			while ( $query->have_posts() ) {
				$query->the_post();
				//echo '<li>del' . get_the_title() . '</li>';
				//wp_delete_post();
			}
		}
		die;*/
	}
	
	//var_dump($query);
	//die;
	/*if(alreadrunnig) {
		if(secondsRemaing>possivel) {
			delete draft;
		} else {
			secondsRemaing=asas;
			startClock();
		}
	}*/
}

/* TEMPLATE TAGS*/
function projectimer_showClock () {
	//TODO: passar width e height por parametro
	echo 
	'<div id="shadow"></div>
	<canvas width="400" height="400" id="myCanvas"></canvas>
	<div id="shine"></div>
	<input type="button" value="focus" onclick="action_button()" id="action_button_id" />
	<div id="div_status" style="background:#FFF;">Teste</div>';
}
function projectimer_showTaskForm () {
	echo 
	'<form name="pomopainel">
		<label>1</label><br />
		<input type="text" size="46" id="title_box" maxlength="70"></input><br />
		<label>2</label>
		<textarea rows="4" cols="34" id="description_box"></textarea><br />
		<label>3</label>
		<input type="text" size="46" id="tags_box" maxlength="20"></input><br />
		
		<br />
		<!--label><script>document.write(txt_write_task_category)</script></label><br />
		<ul>
			<li><input type="radio" name="cat_vl" value="26">Estudo</li>
			<li><input type="radio" name="cat_vl" value="27">Trabalho</li>
			<li><input type="radio" name="cat_vl" value="28">Pessoal</li>
		</ul>
		<label><script>document.write(txt_write_task_privacy)</script></label><br />
		<ul>
			<li><input type="radio" name="priv_vl" value="publish" CHECKED>Público - todos podem ver.</li>
			<li><input type="radio" name="priv_vl" value="private" >Privado - somente você pode ver. </li>
		</ul-->
		
	</form>';
}


/* AJAX FUNCTIONS */
function save_progress () {
	date_default_timezone_set("Brazil/East");
	$tagsinput = explode(" ", $_POST['post_tags']);	
	$my_post = array(
		'post_title' => $_POST['post_titulo'],
		'post_content' => $_POST['post_descri'],
		//'post_status' => $_POST['post_priv'],
		'post_status' => 'publish',
		'post_category' => array(1/*, $_POST['post_cat']*/),
		'post_author' => $current_user->ID,
		'tags_input' => array($_POST['post_tags'])
		//'post_category' => array(0)
	);
	wp_insert_post( $my_post );
	
	die(); 
}
/**/

function save_modelnow () {
	if(isset($_POST['post_para_deletar'])) {
		wp_delete_post($_POST['post_para_deletar']);
	} else {
		date_default_timezone_set("Brazil/East");
		$tagsinput = explode(" ", $_POST['post_tags']);	
		$my_post = array(
			'post_title' => $_POST['post_titulo'],
			'post_content' => $_POST['post_descri'],
			'post_status' => "pending",
			'post_author' => $current_user->ID,
			'tags_input' => array($_POST['post_tags'])
		);
		$idofpost = wp_insert_post( $my_post );
		echo $idofpost;
		die();
	}
}

function load_user_config () {
	
	/*if(isset($_POST['post_para_deletar'])) {
		wp_delete_post($_POST['post_para_deletar']);
	} else {
		date_default_timezone_set("Brazil/East");
		$tagsinput = explode(" ", $_POST['post_tags']);	
		$my_post = array(
			'post_title' => $_POST['post_titulo'],
			'post_content' => $_POST['post_descri'],
			'post_status' => "pending",
			'post_author' => $current_user->ID,
			'tags_input' => array($_POST['post_tags'])
		);
		$idofpost = wp_insert_post( $my_post );
		echo $idofpost;
		die();
	}*/
}
