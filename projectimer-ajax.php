<?
/* AJAX FUNCTIONS */
function projectimer_load_user_settings () {
	if(is_user_logged_in()) {
		header('Content-type: application/json');//CRUCIAL
		
		$focus_time = get_user_meta(get_current_user_id(), "focus_time", true);
		if(!$focus_time) $focus_time=25;
		$bgcolor = get_user_meta(get_current_user_id(), "bgcolor", true);
		if(!$bgcolor) $bgcolor="#999";

		$args = array(
			'author'        =>  $current_user->ID,
			'post_status' => "future",
			'posts_per_page' => 1,
		);
		$current_user_scheduled_cycle = get_posts( $args );
		//echo apply_filters( 'post_title', $current_user_scheduled_cycle ); // Do this instead
		//$user['currentCycle'] = $current_user_scheduled_cycle[0]=>['post_title'];
		//Derivated variables
		//$rest_time = round($focus_time/5);
		//$cycle_time = $focus_time + $rest_time;
		if ($current_user_scheduled_cycle) {
			//Scheduled cycle found
			$t = apply_filters( 'the_title', $current_user_scheduled_cycle[0]->post_title );
			$d = apply_filters( 'the_date_gmt', $current_user_scheduled_cycle[0]->post_date_gmt );
			$timeFirst  = strtotime("now");
			$timeSecond = strtotime($d);
			$secondsRemaing = $timeSecond - $timeFirst;	
			if($secondsRemaing<0) {
				//Missed schedule post, the cycle is still unpublished, need to change its status
				$args = array(
						'ID' => $current_user_scheduled_cycle[0]->ID,
						'post_status' => "draft"
					);
				wp_update_post($args);
				$user['secondsRemaing'] = $focus_time*60;
				$user['drafted_cycle_id'] = $current_user_scheduled_cycle[0]->ID;
			} else {
				//No problemns found, cycle scheduled
				$user['active_cycle_id'] = $current_user_scheduled_cycle[0]->ID;
				$user['user_active_cycle_title'] = $t;
				$user['secondsRemaing'] = $secondsRemaing;
			}

		} else {
			//No cycle scheduled
			$secondsRemaing = $focus_time*60;
		}
		
		//$myObj = getMyObject( $_POST['nextID'] );  // get an object
		global $current_user;
		get_currentuserinfo();
		$user['user_first_name'] = $current_user->user_firstname;
		$user['user_last_name'] = $current_user->user_lastname;
		$user['focus_time'] = $focus_time;
		$user['bgcolor'] = $bgcolor;
		
		
		$user['active_cycle_date_gmt'] = $d;
		//
		echo json_encode($user);
	} else {
		echo "user_not_logged";
		//_e("To view user settings you must login", "plugin-projectimer");
	}
	die();
}

function projectimer_update_user_meta () {
	//var_dump($_POST);die();
	if($_POST["value"]=="now") {
		$update_meta = update_user_meta(get_current_user_id(), $_POST["property"], date("Y-m-d H:i:s"));
	} else {
		$update_meta = update_user_meta(get_current_user_id(), $_POST["property"], $_POST["value"]);
	}
	//echo $_POST["property"] + " | " + $_POST["value"];
	//$update_meta = true;
	//$_POST["prop"]f
	//$_POST["value"]
	if($update_meta) {
		_e("Settings updated. ", "plugin-projectimer");
	} else {
		_e("Error, settings not updated. ", "plugin-projectimer");
	}

	die(); 
}

//date_default_timezone_set('America/Sao_Paulo');

function projectimer_schedule_cycle () {
	//$_POST['id_to_reset'];
	
	$tagsinput = explode(" ", $_POST['post_tags']);
	
	$timefromnow = $_POST["timefromnow"];
	$postdate = date("Y-m-d h:i:s", strtotime("now +$timefromnow minutes"));

	$my_post = array(
		'post_title' => $_POST['post_titulo'],
		'post_content' => $_POST['post_descri'],
		//'post_category' => array(1/*, $_POST['post_cat']*/),
		'post_author' => $current_user->ID,
		//'tags_input' => array($_POST['post_tags']),
		//'post_category' => array(0),
		'post_date_gmt' => $postdate,
		'post_date' => $postdate,
		'post_status' => 'future',
	);
	
	//echo "received:".$_POST["timefromnow"]." server time:";
	$post = wp_insert_post( $my_post );
	$resp = "id:$post";
	if($post) {
		header('Content-type: application/json');//CRUCIAL
		//_e("Published cycle. ", "projectimer-plugin");
		// Include WPML API
		include_once( WP_PLUGIN_DIR . '/sitepress-multilingual-cms/inc/wpml-api.php' );
	  	//echo ICL_LANGUAGE_CODE;
	  	global $sitepress;
	  	if(ICL_LANGUAGE_CODE!==$sitepress->get_default_language()) {
	  		GLOBAL $wpdb;
			$postl = $wpdb->update('wp_icl_translations', array('language_code'=>ICL_LANGUAGE_CODE), array('element_id'=> $post));
			$resp.=" altl:$postl; ";
			//($postl) ? _e("Language ok. ", "projectimer-plugin") : _e("Error changing language. ", "projectimer-plugin");	
	  	} else {
	  		//_e("Using default language.", "projectimer-plugin");
	  		$resp.=" dl; ";
	  	}
		
		//IP
		$postip = update_post_meta( $post, 'post_ip', $_SERVER['REMOTE_ADDR'] );
		//($postip) ? _e("IP attached. ", "projectimer-plugin") : _e("Error updating IP. ", "projectimer-plugin");
		$resp.= " ip:$postip; ";
		//DEVICE INFO//http://mobiledetect.net/
		//$postd = update_post_meta( $post, 'device_utilized',  );
		//($postd) ? _e("Device used was. ", "projectimer-plugin") : _e("Device not detected. ", "projectimer-plugin");
		
		update_user_meta(get_current_user_id(), "active_cycle_id", $post);
		//
		$data['active_cycle_id'] = $post;
		$data['response'] = $resp;
		echo json_encode($data);
	} else {
		_e("Error publishing cycle. ", "projectimer-plugin");
	}
	die(); 
}

function projectimer_update_cycle_status () {
	echo $_POST["cycle_id"];
	echo $_POST["newstatus"];
	/*if($_POST["newstatus"]=="trash") {
		$update = wp_delete_post($_POST["cycle_id"], true);
	} else {*/
		$my_post = array(
      		'ID'           => $_POST["cycle_id"],
      		'post_status' => $_POST["newstatus"],
  		);
		// Update the post into the database
  		$update = wp_update_post( $my_post );
	//}

	if($update) {
		echo "Cycle updated successfully";
	} else {
		echo "Cycle not updated";
	}
	die();
}

function projectimer_add_activie () {
	if($_POST["description"]!=="") {
		$ac = bp_activity_add( array(
			'user_id' => get_current_user_id(),
			'action' => $_POST["description"],
			'component' => 'blogs',
			'type' => 'new_blog_post'
			) );
		if($ac)
			_e("Activitie successfully added", "projectimer-plugin");
		else
			_e("Error adding activitie", "projectimer-plugin");
	} else {
		_e("Error updating activitie, no description", "projectimer-plugin");
	}
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

/* WORDPRESS HEARTBEAT API 
function projectimer_receive_wp_api_heartbeat( $response, $data, $screen_id) {
	if($data['receive_recent_activity'])
	$response['server'] = get_current_user_id();
	$response['atividade'] = "Atchim";
	
	//echo "Buscando atividade recente...";
	return $response; 
	die();
}
add_filter( 'heartbeat_received', 'projectimer_receive_wp_api_heartbeat', 10, 3 );*/