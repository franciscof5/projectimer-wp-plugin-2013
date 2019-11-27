<?php
/**/
function checkLogin() {
	if(!get_current_user_id()) {
		header('Content-type: application/json');//CRUCIAL
		echo json_encode("NOTIN");
		die();
	}
}

/* AJAX FUNCTIONS */
function get_current_postid_for_user($userid) {
	$args = array(
		'post_type' => array("projectimer_focus", "projectimer_rest"),
		//'author'        =>  get_current_user_id(),
		'post_status' => array("publish", "future"),
		'posts_per_page' => 1,
		'author' => $userid,
	);
	$current_user_scheduled_cycle = get_posts( $args );
	return $current_user_scheduled_cycle[0]->ID;
}

function get_post_object($id) {
	if(!isset($id))
		die;
	$post = get_post($id);
	return $post;
}

function get_seconds_remaining() {
	//$args = array(
		//'post_type' => $posttype,
		//'ID'		=> $id,
		//'author'        =>  get_current_user_id(),
		//'post_status' => "future",
		//'posts_per_page' => 1,
	//);
	//if(!isset($current_user_scheduled_cycle))
	$po = get_current_postid_for_user(get_current_user_id());
	if($po)
	$current_user_scheduled_cycle = get_post_object($po);
	//$current_user_scheduled_cycle = $current_user_scheduled_cycle[0];
	//var_dump($current_user_scheduled_cycle);
	if ($current_user_scheduled_cycle->post_status=="future") {
		//if($current_user_scheduled_cycle) {
			$d = apply_filters( 'the_date_gmt', $current_user_scheduled_cycle->post_date_gmt );
			$timeFirst  = strtotime("now");
			$timeSecond = strtotime($d);
			$secondsRemaing = $timeSecond - $timeFirst;
			//var_dump($secondsRemaing);die;	
		//} else
		//$secondsRemaing = 0;
		return $secondsRemaing;
	} else {
		return 0;//$current_user_scheduled_cycle;
	}
}

function get_community_data_object() {
	/*$args = array(
		'post_type' => "projectimer_cycle",
		'author'        =>  get_current_user_id(),
		'post_status' => "publish",
		//'post_date' => "",
		//'posts_per_page' => 1,
	);
	$day_duration = 0;
	$current_user_day_posts = get_posts( $args );
	foreach ($posts as $post) {
		$day_duration +=get_post_meta($post->ID, "post_duration", true);
	}
	//if 3 rests->big rest, zerar rest
	//'user_cyclen' = count
	var $data_total {
		'user_cyclen'
		
	$current_user_day_posts = get_posts( $args );*/
}

function projectimer_load_user_settings() {
	checkLogin();
	$site_url = basename(get_bloginfo('url'));
	
	//DONT AJAX ONLY WORKS WITH LOGGED IN USERS?
	if(is_user_logged_in()) {

		global $current_user;
		get_currentuserinfo();
		
		//$author_tags = get_posts_by_author_sql( 'projectimer_focus', true, $current_user->ID );	
		$user_id = $current_user->ID;

		$focus_time = get_user_meta($user_id, $site_url."-focus_time", true);
		if(!is_int($focus_time)) $focus_time=25;
		
		$bgcolor = get_user_meta($user_id, $site_url."-bgcolor", true);
		if(!$bgcolor) $bgcolor="#999";
		#var_dump($bgcolor );die;
		//file_put_contents('php://stderr', print_r("user_id".$user_id, TRUE));
		$current_post_id = get_current_postid_for_user($user_id);
		//file_put_contents('php://stderr', print_r("AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA $current_post_id", TRUE));
		//file_put_contents('php://stderr', print_r("7987987987987", TRUE));
		if($current_post_id)
		$current_user_scheduled_cycle = get_post_object($current_post_id);

		//file_put_contents('php://stderr', print_r("current_post_id".$current_post_id, TRUE));
		if($current_post_id)
		$d = apply_filters( 'the_date_gmt', $current_user_scheduled_cycle->post_date_gmt );
		//file_put_contents('php://stderr', print_r("BBBBBBBBBBBBBBBBBBBBsite_url $d", TRUE));
		if ($current_user_scheduled_cycle->post_status=="future") {
			//Scheduled cycle found
			$secondsRemaing = get_seconds_remaining();
			//if($secondsRemaing>0) {
				//Missed schedule post, the cycle is still unpublished, need to change its status
					//$args = array(
					//	'post_type' => "projectimer_cycle",
					//	'ID' => $current_user_scheduled_cycle[0]->ID,
					//	'post_status' => "draft"
					//);
				//wp_update_post($args);
				//$user['secondsRemaining_from_server'] = $secondsRemaing;
				//$user['secondsRemaining_from_server'] = $secondsRemaing;
				
			//} else {
				//No problemns found, cycle scheduled
				//$user['active_cycle_id'] = $current_user_scheduled_cycle[0]->ID;
				//$user['user_active_cycle_title'] = $t;
				//$user['secondsRemaining_from_server'] = $secondsRemaing;
				//$user['secondsRemaining_from_server'] = 0;
				//$user['secondsRemaining_from_server'] = $secondsRemaing;
			//}

		} else {
			//No cycle scheduled
			$secondsRemaing = $focus_time*60;
		}
		$user['current_post_id'] = $current_post_id;
		//$myObj = getMyObject( $_POST['nextID'] );  // get an object
		
		//$user = get_current_user();
		//$asdasdasd = get_posts_by_author_sql( 'projectimer_focus', true, $user_id );
		/*$sql = "select id from wp_posts ".get_posts_by_author_sql( 'projectimer_focus', true, $user_id );
		
		global $wpdb;
		$posts = $wpdb->get_results( $sql );
		$user['extra2'] = $sql;
		// Filter out only the post IDs and build an array from the result:
		$posts = wp_list_pluck( $posts, 'id' );

		$tags = [];
		foreach ( $posts as $post_id )
		    $tags[ $post_id ] = get_the_terms( $post_id );
		*/
		//var_dump( $tags );

		$user['user_first_name'] = $current_user->display_name;
		$user['user_last_name'] = $current_user->user_lastname;
		$user['user_display_name'] = $current_user->display_name;
		$user['user_actual_page'] = get_user_meta($current_user->ID, $site_url."-user_actual_page", true);
		$user['focus_time'] = $focus_time;
		$user['post_status'] = $current_user_scheduled_cycle->post_status;
		$user['bgcolor'] = $bgcolor;
		$user['secondsRemaining_from_server'] = $secondsRemaing;
		$user['active_cycle_date_gmt'] = $d;
		$user['site_url'] = basename(get_bloginfo('url'));
		$user['data_total'] = get_community_data_object();
		$user['tipe'] =  get_post_type($id);
		$user['tags_list'] = get_projectimer_tags(NULL);
		$user['extra'] = $current_user_scheduled_cycle;
		$user['active_sound'] = "crank-2.mp3";
		$user['interrupt'] = "telephone-ring-1.mp3";
		$user['end_sound_rest'] = "telephone-ring-1.mp3";
		$user['end_sound_focus'] = "telephone-ring-1.mp3";
		//
		file_put_contents('php://stderr', print_r($user, TRUE));
		header('Content-type: application/json');//CRUCIAL
		echo json_encode($user);
	} else {
		//echo "user_not_logged";
		_e("To view user settings you must login", "plugin-projectimer");
		/*$user['user_first_name'] = "Anonymous ";
		$user['user_last_name'] = "User";
		$user['focus_time'] = 25;
		$user['bgcolor'] = "#666";
		$user['secondsRemaing'] = 15000;
		#$user['active_cycle_date_gmt'] = $d;
		$user['site_url'] = basename(get_bloginfo('url'));
		//
		echo json_encode($user);*/
	}
	die();
}

function projectimer_update_user_meta() {
	//var_dump($_POST);die();
	$site_url = basename(get_bloginfo('url'));
	if($_POST["value"]=="now") {
		$update_meta = update_user_meta(get_current_user_id(), $site_url."-".$_POST["property"], date("Y-m-d H:i:s"));
	} else {
		$update_meta = update_user_meta(get_current_user_id(), $site_url."-".$_POST["property"], $_POST["value"]);
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

function projectimer_remove_user() {
	$target = $_POST['target_user_id'];
	$removed = remove_user_from_blog($target, get_current_blog_id());
	return $removed;
	die;
}

function projectimer_make_user_admin() {
	$target = $_POST['target_user_id'];
	// NOTE: Of course change 3 to the appropriate user ID
	//$u = new WP_User( $target );
	// Remove role
	//$u->remove_role( 'subscriber' );
	// Add role
	//$adminezed = $u->add_role( 'admin' );
	//$user_id = 3;
    //$new_role = 'admin';

    //$adminezed = wp_update_user(array('ID'=>$target, 'role'=>$new_role));
	//$adminezed = remove_user_from_blog($target, get_current_blog_id());
	//echo $target;
	return $adminezed;
	die;
}
//
function projectimer_schedule_cycle() {
	checkLogin();
	//
	$site_url = basename(get_bloginfo('url'));
	//date_default_timezone_set('America/Sao_Paulo');
	$user_focus_time = get_user_meta(get_current_user_id(), $site_url."-focus_time", true);
	if($user_focus_time==0 || $user_focus_time==NULL || $user_focus_time=="") 
		$user_focus_time=25;
	//$user_focus_time*=60;
	$postdate = date("Y-m-d H:i:s", strtotime("now +$user_focus_time minutes"));
	//$postdate = date("Y-m-d H:i:s", strtotime("now + 30 seconds"));
	//var_dump($postdate);die;
	//
	//$tagsinput = explode(" ", $_POST['post_tags']);
	$my_post = array(
		'post_type' => $_POST['posttype'],
		'post_title' => $_POST['post_titulo'],
		'post_content' => $_POST['post_descri'],
		//'post_category' => array(1/*, $_POST['post_cat']*/),
		//'post_author' => $current_user->ID,
		'post_author' => get_current_user_id(),
		//'tags_input' => array($_POST['post_tags']),
		'tags_input' => $_POST['post_tags'],
		//'post_category' => array(0),
		'post_date_gmt' => $postdate,
		//'post_date' => $postdate,
		'post_status' => 'future',
	);
	
	//echo "user_focus_time:".$user_focus_time." server time:";
	//var_dump($postdate);die;
	$post = wp_insert_post( $my_post );
	//$resp = "id:$post";
	//var_dump($post);die;
	if($post) {
		//++LOCATION - LANGUAGE - DEVICE
		$postip = update_post_meta( $post, 'post_ip', $_SERVER['REMOTE_ADDR'] );
		$post_duration = update_post_meta( $post, 'post_duration', $user_focus_time );
		$meta1 = update_post_meta($post, "type", $_POST["post_type_type"]);
		$meta2 = update_post_meta($post, "planned", $_POST["post_planned"]);
		$meta3 = update_post_meta($post, "virtual", $_POST["post_virtual"]);
		//	
		$site_url = basename(get_bloginfo('url'));
		update_user_meta(get_current_user_id(), $site_url."-active_cycle_id", $post);
		//
		$data['active_cycle_id'] = $post;
		if($meta1 && $meta2 && $meta3 && $postip && $post_duration) {
			$data['response'] = "post_inserted_meta_ok";
		} else {
			$data['response'] = "post_inserted_BUT_no_meta";
		}
		$data["post"] = $post;
		//header('Content-type: application/json');//CRUCIAL
		echo json_encode($data);
	} else {
		_e("Error publishing cycle. ", "projectimer-plugin");
	}
	die(); 
}

function projectimer_make_lost_time () {
	//
	ob_start();
	$my_post = array(
		'post_type' => "projectimer_lost",
	  	//'ID'           => $current_post_scheduled_id,
	  	//'post_status' => "publish",
	  	//'post_title'
	);
	$update = wp_update_post( $my_post );
	ob_end_clean();

	if($update) {
		echo 0;
	} else {
		echo "error updating...";
	}
}

function projectimer_update_cycle_status() {
	checkLogin();
	//echo $_POST["cycle_id"];
	$id = get_current_postid_for_user(get_current_user_id());
	//echo $_POST["newstatus"];
	/*if($_POST["newstatus"]=="trash") {
		$delete = wp_delete_post($id, true);
		/*ob_start();
		$my_post = array(
			'post_type' => "projectimer_lost",
	      	//'ID'           => $current_post_scheduled_id,
	      	//'post_status' => "publish",
	      	//'post_title'
		);
		$update = wp_update_post( $my_post );
		ob_end_clean();*

		if($delete) {
			echo 0;
		} else {
			echo "error updating...";
		}
	} else {*/
	
		$secondsRemaining_from_server=get_seconds_remaining();

		if($secondsRemaining_from_server<=2 || $_POST["newstatus"]=="trash") {
			$current_post_scheduled_id = get_current_postid_for_user(get_current_user_id());
			ob_start();//NEED IT BECAUSE IT GENERATE AN BP ACTIVITY THAT ECHOS SHIT
			$my_post = array(
				'post_type' => $_POST['posttype'],
		      	'ID'           => $current_post_scheduled_id,
		      	'post_status' => $_POST["newstatus"],
			);
			$update = wp_update_post( $my_post );
			ob_end_clean();

			if($update) {
				echo 0;
			} else {
				echo "error updating...";
			}
		} else
		echo $secondsRemaining_from_server;
	/*} else {*/
		//THE USER WAS NOT ON FOCUS PAGE WHEN PUBLISHING
	//}
	
	die();
}

function projectimer_add_activie($post_desc, $act_type, $userid) {
	if(isset($_POST["description"])) {
		$post_desc = $_POST["description"];
	} else {
		if(!isset($post_desc)) {
			return _e("Error updating activitie, no data received.", "projectimer-plugin");
			die;
		} else {
			$completedByCallback = true;
		}
	}
	//else
		//$completedByCallback = true;
	if(!isset($act_type)) {
		if(isset($_POST['type']))
			$act_type = $_POST['type'];
		else
			$act_type = "projectimer_generic";
	}
	//if($post_desc) {
		//$activity_inserted=$_POST["description"];
		//var_dump($activity_inserted);die;
		//if($activity_inserted!==NULL) {
		$id = get_current_postid_for_user(get_current_user_id());
		//$type = get_post_type($id);
		
		
		//$type_get = substr(($post_desc), 0, 7);

		//$started = $type_get  == "Started";
		
		//
		global $current_user;
		if(isset($userid)) {
			//$args['author'] = $userid;
			
			$current_user = get_userdata($userid);
			//$current_user = get_currentuserinfo();
		} else {
			//get_current_user_id();
			$current_user = get_currentuserinfo();
			
		}
		
		//$current_user = get_currentuserinfo();
		$namefull = $current_user->user_firstname." ".$current_user->user_lastname;
		$user_id = $current_user->ID;
		$act_args = array(
			'user_id' => $user_id,
			//'action' => "Francisco Matelli ".$_POST["description"],
			//'action' => "<a href='".get_bloginfo("url")."/members/' >".$current_user->user_firstname." ".$current_user->user_lastname."</a><p>".$_POST["description"]."<span class=hidden id=$id>$id</span></p>",
			'item_id' => $id,
			'component' => 'projectimer',
			
			//'hide_sitewide' => true
			);

		if($_POST["hidden"]=="hidden")
			$act_args['hide_sitewide'] = true;

		if( $act_type == "projectimer_start" ) {
			//$act_args['action'] = "<span><a href='".get_bloginfo("url")."/members/$current_user->user_login' >".$namefull."</span><p><a href='#myModal' class='addclick' data-toggle=modal><span class=hidden>$id</span>".$post_desc."</a></p>";
			$dur = get_post_meta($id, "post_duration", true);
			$act_args['action'] = "<span class=activity_title>".$namefull."</span><a href='#myModal' class='addclick' data-toggle=modal><span class=hidden>$id</span>$post_desc ($dur min)</a></p>";
			$act_args['type'] = 'projectimer_start';
		} else {
			//addclick class it the way jquery make all happens
			$act_args['action'] = "<span class=activity_title>".$namefull."</span><p>".$post_desc."<span class=hidden id=$id>$id</span></p>";
			$act_args['type'] = $act_type;
			
			//if($act_type == "projectimer_complete" && !$completedByCallback)
			//	die;

			//$act_args['type'] = 'new_blog_post';
			//if($type_get=="Complet" and !$completedByCallback) {
			//	return _e("Completed actitity is added by callback", "projectimer-plugin");
			//	die;
			/*} else {
				if($type_get=="Stopped")
					$act_args['type'] = 'projectimer_stop';
				elseif ($type_get=="Complet")
					$act_args['type'] = 'projectimer_complete';
				else
					$act_args['type'] = 'projectimer_generic';
			}*/			
		}
		/*$completedByCallback = false;
		if($act_type=="projectimer_complete" && !$completedByCallback) {
			return _e("Error adding activitie, need to be by call back", "projectimer-plugin");
			die;
		}*/
		$ac = bp_activity_add( $act_args );
		update_user_meta( $user_id, 'last_activity', bp_core_current_time() );
		if($ac) { 
			
			return _e("Activitie successfully added (type_get: $type_get)", "projectimer-plugin");
		} else
			return _e("Error adding activitie", "projectimer-plugin");
	//} else {
		
	//}
	die();
}
/**/

function projectimer_update_cycle() {
	checkLogin();
	if(isset($_POST['post_para_deletar'])) {
		wp_delete_post($_POST['post_para_deletar']);
	} else {
		//date_default_timezone_set("Brazil/East");
		$tagsinput = explode(",", $_POST['post_tags']);	
		$my_post = array(
			'post_type' => "projectimer_focus",
			'post_title' => $_POST['post_titulo'],
			'post_content' => $_POST['post_descri'],
			'post_status' => "pending",
			'post_author' => $current_user->ID,
			'tags_input' => $tagsinput
		);
		//var_dump($_POST['post_tags']);
		$idofpost = wp_insert_post( $my_post );
		if($update) {
			echo "Cycle updated successfully";
		} else {
			echo "Cycle not updated";
		}
		die();
	}
}

function projectimer_update_recent_activities() {
	checkLogin();
	//projectimer_display_recent_activities();
	//checkCredential();
	//
	$site_url = basename(get_bloginfo('url'));
	$user_actual_page = update_user_meta(get_current_user_id(), $site_url."-user_actual_page", $_POST['user_last_active_page']);
	$user_last_heartbeat = update_user_meta(get_current_user_id(), $site_url."-user_last_heartbeat", strtotime("now"));

	if(!$user_actual_page)
	$user_actual_page = add_user_meta(get_current_user_id(), $site_url."-user_actual_page", $_POST['user_last_active_page']);

	if(!ob_start("ob_gzhandler")) ob_start();

	projectimer_display_recent_activities();
	$recent_activity_html = ob_get_contents();
	ob_end_clean();
	//ob_flush();
	if(!ob_start("ob_gzhandler")) ob_start();
	/*$argsm = array(
      'title'        => __( 'Memberssss', 'buddypress' ),
      'max_members'    => 15,
      'member_default' => 'active',
      'link_title'    => false
    );*/
    the_widget("BP_Core_Members_Widget", "title=Membros&max_members=99");
	//the_widget("BP_Core_Members_Widget", "Team's Members");
	
	//
	echo "<hr /><br />&nbsp;<br />";
	the_widget("InviteAnyoneWidget", "title=Convites&instruction_text=Insira email de colegas");
	$recent_members = ob_get_contents();
	//$recent_members = bp_core_ajax_widget_members();
	ob_end_clean();
	//$response['recent_activity_html'] = "ASDASD";
	$response['user_credentied'] = checkCredentials();
	$response['user_actual_page'] = $user_actual_page;
	$response['user_last_heartbeat'] = $user_last_heartbeat;
	$response['recent_activity_html'] = $recent_activity_html;
	$response['recent_members_html'] = $recent_members;
	$response['secondsRemaining_from_server'] = get_seconds_remaining();
	//
	//$response_zipped = gzencode($response);
	//ob_end_flush();
	header('Content-type: application/json');//CRUCIAL
	echo json_encode($response);
	//header("Content-type: text/javascript");
	//header('Content-Encoding: gzip');
	//echo $response_zipped;
	die();
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


function get_projectimer_tags($excludeTags) {
	$args = array(
		'post_type' => array("projectimer_focus"),
		//'author'        =>  get_current_user_id(),
		'post_status' => array("publish", "future", "pending", "draft"),
		'posts_per_page' => -1,

	);
	$all_projectimer_tags = get_posts( $args );
	$terms = array();
	foreach ($all_projectimer_tags as $post) {
		$tags = get_the_terms($post->ID, 'post_tag');
		if($tags) {
			foreach ($tags as $tag) {
				//array_
				//$t = "<option>".$tag->slug."</option>";
				$t = $tag->slug;
				if(!in_array($t, $terms)) {
					if($excludeTags) {
						if(!in_array($t, $excludeTags))
						$terms[] = $t;
					} else {
						$terms[] = $t;
					}

				}
			}
		}
		//$terms[] = $tags;
	}
	return $terms;
}

function get_task_id($status) {
	$args = array(
		'post_type' => array("projectimer_focus"),
		'author'        =>  get_current_user_id(),
		//'post_status' => array("draft"),
		'posts_per_page' => 1,

	);
	if($status)
		$args["post_status"] = $status;
	$current_user_task = get_posts( $args );
	if($current_user_task)
	return $current_user_task[0]->ID;
	else
	return 0;
}

function projectimer_load_currentask_clipboard() {
	header("Content-type: application/json");

	if(isset($_POST['id']) or $_POST["id"]>0)
		$currentaskid = $_POST["id"];
	else
		$currentaskid = get_task_id("draft");

	//$currentask_post = get_post_projectimer_focus($currentaskid);
	//$currentask_post = get_posts(array(
			//'post_type' => 'projectimer_focus',
			//'ID' => $currentaskid
	//));

	//$args = array(
		//'post_type' => array("projectimer_focus"),//, "projectimer_rest"),
		//'author'        =>  get_current_user_id(),
		//'post_status' => array("draft"),
		//'posts_per_page' => 1,
		//'ID' => $currentaskid

	//);
	//$currentask_post = get_posts( $args );
	//$currentask_post = $currentask_post;
	if($currentaskid) {
		$tags = get_the_terms( $currentaskid, 'post_tag');
		if($tags) {
			foreach ($tags as $tag) {
				//array_
				$t = $tag->slug;
				//if(!in_array($t, $terms))
				$tags_ar[] = $t;
			}
		}
		//var_dump($currentask_post);die;
		//if($currentask_post) {
		$type = get_post_meta($currentaskid, "type", true);
		$planned = get_post_meta($currentaskid, "planned", true);
		$virtual = get_post_meta($currentaskid, "virtual", true);
		$task = array(
			"title" => get_post_field('post_title', $currentaskid),//$currentask_post[0]->post_title,
			"tags" => $tags_ar,
			"tags_teams" => get_projectimer_tags($tags_ar),
			"desc" => get_post_field('post_content', $currentaskid),//$currentask_post[0]->post_content,
			"type" => $type,
			"planned" => $planned,
			"virtual" => $virtual,
			"id" => $currentaskid,
		);
		//var_dump($task); die;
		echo json_encode($task);	
		//}
	} else {
		return false;
	}
	
	die();
}

function projectimer_update_currentask_clipboard() {
	checkLogin();
	header("Content-type: application/json");
	$currentaskid = get_task_id("draft");
	$my_post = array(
		'post_type' => "projectimer_focus",
		'post_title' => $_POST['post_titulo'],
		'post_content' => $_POST['post_descri'],
		//'post_category' => array(1, $_POST['post_cat']),
		'post_author' => get_current_user_id(),
		'tags_input' => $_POST['post_tags'],
		'post_status' => "draft",
		//'edit_date' => true,
		//'post_date' => $agora
		//'post_date' => $_POST["post_data"]
		//'post_category' => array(0)
	);
	//var_dump($_POST['post_tags']);die;
	//echo "currentaskid".$currentaskid;
	if($currentaskid) {
		//$my_post[];
		//echo "update_progress".$currentaskid;
		//array_push($my_post["ID"], $currentaskid);
		$my_post["ID"] = $currentaskid;
		//var_dump($my_post);die;
		$update_progress = wp_update_post( $my_post );
		if($update_progress) {
			$meta1 = update_post_meta($currentaskid, "type", $_POST["post_type_type"]);
			$meta2 = update_post_meta($currentaskid, "planned", $_POST["post_planned"]);
			$meta3 = update_post_meta($currentaskid, "virtual", $_POST["post_virtual"]);
			if($meta1 && $meta2 && $meta3) {
				echo json_encode(array('response' => "curretask_updated" ));
			} else {
				echo json_encode(array('response' => "curretask_updated_BUT_no_post_meta" ));
			}
		} else {
			echo json_encode(array('response' => "no_curretask_updated" ));
		}

	} else {
		//echo "save_progress".$currentaskid;
		$save_progress = wp_insert_post( $my_post );
		if($save_progress) {
			$meta1 = update_post_meta($currentaskid, "type", $_POST["post_type_type"]);
			$meta2 = update_post_meta($currentaskid, "planned", $_POST["post_planned"])	;
			$meta3 = update_post_meta($currentaskid, "virtual", $_POST["post_virtual"]);
			if($meta1 && $meta2 && $meta3) {
				echo json_encode(array('response' => "new_currentask_inserted" ));
			} else {
				echo json_encode(array('response' => "new_currentask_inserted_BUT_no_meta" ));
			}
		} else {
			echo json_encode(array('response' => "no_new_currentask_inserted" ));
		}
	}
	die();
}