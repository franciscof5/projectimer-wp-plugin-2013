<?php
/**
 * Plugin Name: Projectimer
 * Plugin URI: http://projectimer.com
 * Description: A timer management focused on projects
 * Version: 0.2
 * Author: Francisco Matelli Matulovic
 * Author URI: http://franciscomat.com
 * License: Not licensed at all
 */

#date_default_timezone_set('America/Sao_Paulo');

#require_once( dirname( __FILE__ ) . '/required_plugins/required_plugins.php' );
require_once( dirname( __FILE__ ) . '/projectimer-ajax.php' );
require_once( dirname( __FILE__ ) . '/projectimer-template-tags.php' );

/* INIT */
if ( ! is_admin() ) {
	//FRONT-END ONLY, not wp-admin screen
	add_action('init', 'projectimer_load_scripts');
	
	//prevent multiple sessions from same user
	//add_action('wp_login', 'add_usermeta_to_prevent_multiple_sessions', 10, 2);
}

#add_filter("get_comment", "use_f5sites_comments");
#function use_f5sites_comments() {
	#global $wpdb;
	#$wpdb->comments="f5sites_comments";
	#$wpdb->commentmeta="f5sites_commentmeta";
#}

/*
function use_f5sites_post_table_to_insert(  ) {

	// If this is a revision, don't send the email.
	#if ( wp_is_post_revision( $post_id ) )
	#	return;

	#$post_url = get_permalink( $post_id );
	#$subject = 'A post has been updated';

	#$message = "A post has been updated on your website:\n\n";
	#if($post->post_type=="post") {
		#global $wpdb;
		#var_dump($wpdb);
		#$wpdb->posts="f5sites_posts";
		#$wpdb->postmeta="f5sites_postmeta";
	#}
	#$message .= $post->post_title . ": " . $post_url;

	// Send email to admin.
	#wp_mail( 'admin@example.com', $subject, $message );
}
add_action( 'wp_insert_post_data', 'use_f5sites_post_table_to_insert', 10, 3 );
*/
#add_filter( 'wp_insert_post_data' , 'use_f5sites_post_table_to_insert' , '99', 2 );
/*
function use_f5sites_post_table_to_insert( $data , $postarr ) {
    // Change post title
    if($postarr["post_type"]=="post") {
    	var_dump($postarr["post_type"]);
    }
    
    #global $wpdb;
	#	$wpdb->posts="f5sites_posts";
	#	$wpdb->postmeta="f5sites_postmeta";
}*/

function modify_blog_directory_item_for_projectimer($q) {
	//global $wp_the_query;
	//revert_database_schema("focalizador");
	//DA PARA LISTAR USUARIOS... tipo 3.... tipo versao do sistema... timezone... idioma
	//Tempo de trabalho:Descanso: Rendimento:
	//SITE PUBLICO: Aparece numeros
	//Plano
	//TEAM ADMIN
	//Total hours:
	//echo "b_blog_id(): ".bp_get_blog_id()."<br/>";
	$blog_id = bp_get_blog_id();
	switch_to_blog(bp_get_blog_id());
	$blod_date_registered = get_blog_details( bp_get_blog_id())->registered;
	$phpdate = strtotime( $blod_date_registered );
	//
	echo "Type: ".get_option("teamType")." | ";
	//echo "Registrado: ".$blod_date_registered." | ";
	echo "Registrado: ".human_time_diff( date("U", $phpdate), current_time('timestamp') )." | ";
	
	//
	//echo "Assinatura: ".get_option("subscription_id")." | ";
	//
	$users = get_users();
	echo "Tamanho do time: ".$team_size=count($users)."  ";

	/*
	//Display 10 users
	$limit=10;
	$i=0;
	foreach ($users as $key) {
		# code...
		echo "key:".$key->ID;
		if(function_exists('bp_core_fetch_avatar'))
			echo bp_core_fetch_avatar(array(
					"item_id="=>$key,
					"width"=>16,
					"height"=>16));
   		else
			echo get_avatar(1, 12 );
			
		if($i==$limit)
			return;
		$i++;
	}*/
	//SHARED POSTS PLUGIN BROKE THAT
	/*global $wpdb;
	$wpdb->posts="focalizador_20_posts";
	$wpdb->postmeta="focalizador_20_postmeta";
	//
	$args = array(
		"post_type"	=> "projectimer_focus",
		"posts_per_page"		=> -1,
		//'date_query'    => array(
        //	'column'  => 'post_date',
        //	'after'   => '- 30 days'
    	//)
    	);
	$lp = get_posts($args);
	//var_dump($lp);
	//
	$args = array(
		"post_type"	=> "projectimer_rest",
		"posts_per_page"		=> -1,
		'date_query'    => array(
        	'column'  => 'post_date',
        	'after'   => '- 30 days'
    	));
	$lr = get_posts($args);*/
	//game_date between '2012-03-11 00:00:00' and '2012-05-11 23:59:00' 
	//DATE(dErstellt) > (NOW() - INTERVAL 7 DAY)
	global $wpdb;
	//$team_focus = $wpdb->get_results( "SELECT ID,post_type FROM focalizador_".$blog_id."_posts WHERE post_type='projectimer_focus'" );
	$team_rest = $wpdb->get_results( "SELECT ID,post_type FROM focalizador_".$blog_id."_posts WHERE post_type='projectimer_rest'" );

	$team_focus = $wpdb->get_results( "SELECT ID,post_type,post_date FROM focalizador_".$blog_id."_posts WHERE post_type='projectimer_focus' AND DATE(post_date) > (NOW() - INTERVAL 7 DAY) " );

	//
	echo "<br />";
	echo "Ciclos de trabalho: ".$total_work_cycles=count($team_focus);
	echo ", descanso: ".$total_rest_cycles=count($lr);
	//
	$productivity_per_capita = number_format((float)($total_work_cycles/$team_size/7), 2, '.', '');
	echo " | Produtivide per capita:".$productivity_per_capita." (7 dias)";

	//echo 
	//echo " bp_blog_latest_post_title:".bp_blog_latest_post_title();
	//echo ", bp_total_blog_count";
	//echo bp_total_blog_count();
	//var_dump($wp_the_query);*/
	//bp_blog_name();
	//restore_current_blog();
}
function redirect_non_admin_user(){
    if ( !defined( 'DOING_AJAX' ) && !current_user_can('super_admin') ){
        wp_redirect( site_url() );  exit;
    } 
}

add_action( 'admin_init', 'redirect_non_admin_user' );
//FOR THEME PROJECTIMER-MAIN
do_action( 'bp_before_directory_blogs' );

//FOR THEME PROJECTIMER-APP
add_action( 'init', 'createPostType' );
add_action( 'init', 'registerSidebar' );
add_action( 'admin-init', 'wpcodex_set_capabilities' );
add_action( 'publish_projectimer_focus', 'publish_projectimer_focus_callback' );
//add_action( 'trash_projectimer_focus', 'trash_post_callback' );
//add_action( 'future_to_publish_projectimer_focus', 'publish_projectimer_focus_callback');
//add_action( 'future_to_publish', 'publish_projectimer_focus_callback');
//add_action( 'wp_logout','logout_redirect');
//add_filter( 'logout_url', 'my_logout_page', 10, 2 )
//add_action('wp_logout', 'logout_redirect');
add_action( 'wp_logout', 'auto_redirect_external_after_logout');
add_action( 'admin_menu', 'plugin_admin_add_page');
add_action( 'show_user_profile', 'my_extra_user_fields' );
add_action( 'edit_user_profile', 'my_extra_user_fields' );
add_action( 'personal_options_update', 'save_my_extra_user_fields' );
add_action( 'edit_user_profile_update', 'save_my_extra_user_fields' );
add_action( 'bp_directory_blogs_item', 'modify_blog_directory_item_for_projectimer', 10, 2 );

//AJAX
add_action( 'wp_ajax_projectimer_update_user_meta', 'projectimer_update_user_meta');
add_action( 'wp_ajax_projectimer_load_user_settings', 'projectimer_load_user_settings');
add_action( 'wp_ajax_projectimer_add_activie', 'projectimer_add_activie');
add_action( 'wp_ajax_projectimer_update_cycle', 'projectimer_update_cycle');
add_action( 'wp_ajax_projectimer_schedule_cycle', 'projectimer_schedule_cycle');
add_action( 'wp_ajax_projectimer_update_cycle_status', 'projectimer_update_cycle_status');

add_action( 'wp_ajax_projectimer_update_recent_activities', 'projectimer_update_recent_activities');
add_action( 'wp_ajax_nopriv_projectimer_update_recent_activities', 'projectimer_update_recent_activities');

add_action( 'wp_ajax_projectimer_load_currentask_clipboard', 'projectimer_load_currentask_clipboard');

add_action( 'wp_ajax_projectimer_update_currentask_clipboard', 'projectimer_update_currentask_clipboard');
add_action( 'wp_ajax_projectimer_remove_user', 'projectimer_remove_user');
add_action( 'wp_ajax_projectimer_make_user_admin', 'projectimer_make_user_admin');

/* TEMPLATE TAGS */
add_action( 'projectimer_show_clock_simplist', 'projectimer_show_clock_simplist' );
add_action( 'projectimer_show_clock_futuristic', 'projectimer_show_clock_futuristic' );
add_action( 'projectimer_show_task_form', 'projectimer_show_task_form' );
add_action( 'projectimer_display_settings_modal', 'projectimer_display_settings_modal');
add_action( 'projectimer_display_teams', 'projectimer_display_teams');
add_action( 'projectimer_show_task_model_form', 'projectimer_show_task_model_form');
add_action( 'projectimer_display_recent_activities', 'projectimer_display_recent_activities');
add_action( 'projectimer_display_recent_activities_load_task_modal', 'projectimer_display_recent_activities_load_task_modal');
add_action( 'projectimer_display_task_tabs', 'projectimer_display_task_tabs' );
remove_filter('template_redirect', 'redirect_canonical');
add_action( 'projectimer_show_header_navbar', 'projectimer_show_header_navbar');
add_action( 'projectimer_display_login_modal', 'projectimer_display_login_modal' );
add_action( 'projectimer_display_remove_user_modal', 'projectimer_display_remove_user_modal' );
add_action( 'projectimer_display_make_user_admin_modal', 'projectimer_display_make_user_admin_modal' );
add_action( 'projectimer_display_team_settings', 'projectimer_display_team_settings' );
add_action( 'projectimer_main_show_header_popups', 'projectimer_main_show_header_popups');
add_action( 'projectimer_main_show_header_buttons', 'projectimer_main_show_header_buttons' );
add_action('wp_head', 'myplugin_ajaxurl');

function myplugin_ajaxurl() {
   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}
/* INITS */
function projectimer_load_scripts() {	
	//LOADED AFTER CHECK ACTIVIVY

	//wp_register_script( 'tips', get_template_directory_uri() . '/pomodoro/tips.js', array(), '0.1', true );
	//wp_register_script('projectimer-ajax-js', plugins_url('/js/projectimer-ajax.js', __FILE__) );
	//wp_register_style('clockcss', plugins_url('/css/clock.css', __FILE__) );
	//3rd	

	//
	//wp_register_script('soundmanager', plugins_url('/js/soundmanager2-nodebug-jsmin.js', __FILE__) );
	// Localize the script with new data
	//$dir = get_bloginfo("url");
	//$dir = plugin_dir_path(__FILE__);
	$dir = plugins_url("projectimer-plugin");
	//load_plugin_textdomain( 'projectimer-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	//wp_localize_script( 'soundmanager', 'pluginDir', $dir );
	//
	wp_register_script('canvas-draw', plugins_url('/js/canvas-draw.js', __FILE__) );
		
	//
	wp_register_script('select2js', plugins_url('/js/select2.full.min.js', __FILE__) );
	wp_register_style('select2css', plugins_url('/css/select2.min.css', __FILE__) );
	
	//
	wp_enqueue_script("jquery");
	
	//TODO: test 	jStorage http://stackoverflow.com/questions/2010892/storing-objects-in-html5-localstorage
	wp_register_script('jstorage-js', plugins_url('/js/jstorage.js', __FILE__) );

	//
	wp_enqueue_script('alertify-js', plugins_url('/js/alertify.min.js', __FILE__) );
	wp_enqueue_style('alertify-css', plugins_url('/css/alertify.core_and_default_merged.css', __FILE__) );
	
	//wp_enqueue_script('moment', plugins_url('/js/moment-timezone-with-data-2010-2020.min.js', __FILE__) );
	
	wp_register_style( 'wppb_stylesheetCOPY', plugins_url('/css/style-front-end.css', __FILE__) );
	//
	wp_register_script('projectimer-js', plugins_url('/js/projectimer.js', __FILE__) );
	#wp_enqueue_script('projectimer-user-settings', plugins_url('/js/projectimer-user-settings.js', __FILE__) );
	wp_enqueue_style('projectimer-css', plugins_url('/css/projectimer-plugin.css', __FILE__) );
	wp_localize_script( 'projectimer-js', 'pluginDir', $dir );

	//trello
	//wp_register_script('trello-js', plugins_url('/js/trello.client.coffee', __FILE__) );
	wp_register_style('trello-css', plugins_url('/css/trello.css', __FILE__) );
	wp_register_script('trello-js', plugins_url('/js/trello.client.js', __FILE__) );
	wp_register_script('trello-projectimer', plugins_url('/js/trello.projectimer.js', __FILE__) );
	
	//enqueue the Heartbeat API
	//TODO use that concept showed bellow, setting on user_meta
	//update_user_meta(get_current_user_id(), "focusTime", 150000);
	//echo  dirname( __FILE__  ) . '/locale';
	//var_dump(load_plugin_textdomain('plugin-projectimer', false, dirname(plugin_basename(__FILE__). '/locale')));die;
	//var_dump(load_plugin_textdomain('projectimer', false, dirname( __FILE__ ) . '/locale' ));die;
	
    
}
#configureSMTP();
function configureSMTP() {
	$swpsmtp_options_default_projectimer = array(
        'from_email_field' => 'sistema@focalizador.com.br',
        'from_name_field' => 'Focalizador - Foca no trabalho!',
        'smtp_settings' => array(
            'host' => 'smtp.gmail.com',
            'type_encryption' => 'ssl',
            'port' => 465,
            'autentication' => 'yes',
            'username' => 'fmatelli@gmail.com',
            'password' => '$167G943O'
        )
    );
	/* install the default plugin options */
	update_option('swpsmtp_options', $swpsmtp_options_default_projectimer, '', 'yes');
	//delete_option("swpsmtp_options");
}


function auto_redirect_external_after_logout(){
		wp_redirect( '/teams' );
		exit();
}

function publish_projectimer_focus_callback($post_id){
	global $post;
	//var_dump($post);
	//echo "<hr />";
	//var_dump();die;
	//if ($post->post_type == 'projectimer_cycle') {
	//update_post_meta($post_id, "post_duration", 200);
	//projectimer_add_activie("Completed a Task", $post_author);
	//file_put_contents('php://stderr', print_r("*************************************12312312312312", TRUE));
	$site_url = basename(get_bloginfo('url'));
	$post_author = get_post_field( 'post_author', $post_id );
	$user_actual_page = get_user_meta($post_author, $site_url."-user_actual_page", true);
	$user_last_heartbeat = get_user_meta($post_author, $site_url."-user_last_heartbeat", true);
	
	//apply_filters( 'the_date_gmt', $current_user_scheduled_cycle->post_date_gmt );
	$d = get_post_field( 'post_date_gmt', $post_id );
	$timeFirst  = $user_last_heartbeat;
	$timeSecond = strtotime($d);
	$seconds_last_hearbeat = $timeSecond - $timeFirst;
	
	//
	//------------------->>>>>>>>>>>>>>>$last_post_published<user_focus_time
	//
	$vardump = " post_author: $post_author, user_actual_page: $user_actual_page, user_last_heartbeat: $user_last_heartbeat, seconds_last_hearbeat: $seconds_last_hearbeat, timeFirst: $timeFirst, timeSecond: $timeSecond, site_url: $site_url";
	file_put_contents('php://stderr', print_r("*************************************".$vardump, TRUE));
	if($user_actual_page=="focus" and $seconds_last_hearbeat<16) {
		//if($user_actual_page=="focus") {
		$titletask = get_post_field('post_title', $post_id);//get_the_title($post_id);
		
		if($titletask=="")
			$titletask="Completed a task";
		
		$durationtask = get_post_meta($post_id, "post_duration", true);
		$desc = "Completed ".$titletask." (".$durationtask." min)";
		//$_POST["description"] = $desc;
		if($durationtask>0 && $post_author) {
			//$ps = get_post_field( 'post_status', $post_id );
			//if($ps!="publish")
				projectimer_add_activie($desc, "projectimer_complete", $post_author);
			//file_put_contents('php://stderr', print_r("*************************************".$vardump, TRUE));
			
		}
	} else {
		$ps = get_post_field( 'post_status', $post_id );
		if($ps!="trash")
		$t = wp_trash_post($post_id);
		if($t)
		projectimer_add_activie("Left Projectimer focus", "projectimer_stop", $post_author);
	}
	//}
}

function trash_projectimer_focus_callback($post_id){
	global $post;
	//$user_actual_page = get_user_meta($post_author, "user_actual_page", true);
	$post_author = get_post_field( 'post_author', $post_id );
	$user_last_heartbeat = get_user_meta($post_author, "user_last_heartbeat", true);
	$d = get_post_field( 'post_date_gmt', $post_id );
	$timeFirst  = $user_last_heartbeat;
	$timeSecond = strtotime($d);
	$seconds_last_hearbeat = $timeSecond - $timeFirst;
	if($seconds_last_hearbeat<0) {
		projectimer_add_activie("Left Projectimer Focus", "projectimer_stop", $post_author);
	}
}

function terms_clauses($clauses, $taxonomy, $args) {
    global $wpdb;

    if (isset($args['post_type']))
    {
        $clauses['join'] .= " INNER JOIN $wpdb->term_relationships AS r ON r.term_taxonomy_id = tt.term_taxonomy_id INNER JOIN $wpdb->posts AS p ON p.ID = r.object_id";
        $clauses['where'] .= " AND p.post_type='{$args['post_type']}'"; 
    }
    return $clauses;
}

add_filter('terms_clauses', 'terms_clauses', 10, 3);

function lo(){wp_redirect( network_home_url( "/teams") );exit();}

function my_extra_user_fields( $user ) { ?>
    <h3>Projectimer user settings</h3>
    <?php 
    $focus_time = get_the_author_meta( 'focus_time', $user->ID );
    if(!$focus_time) $focus_time = 25;
    ?>
    <table class="form-table">
   <tr>
    <th><label for="projectimer_user_focus_time">Cycle time</label></th>
    <td>
   <input id="focus_time" name="focus_time" type="text" value="<?php echo $focus_time ? $focus_time : '25';?>" />
   <span class="description"><?php _e("Duration of work/study cycle."); ?></span>
    </td>
   </tr>
    </table>
<?php }


function wpcodex_set_capabilities() {

    // Get the role object.
    $administrator = get_role( 'administrator' );
    
    $caps_to_add = array(
    	'edit_dashboard',
    	'edit_pages',
    	'edit_posts',
        'list_users',
        'publish_posts',
        'remove_users',
        #'read'
    );
    foreach ( $caps_to_add as $cap ) {
    
        // Remove the capability.
        $administrator->add_cap( $cap );
    }

	// A list of capabilities to remove from editors.
    $caps_to_remove = array(
        'moderate_comments',
        'manage_categories',
        'manage_links',
        'edit_others_posts',
        'edit_others_pages',
        'delete_posts',
        'activate_plugins',
        'delete_others_pages',
        'delete_others_posts',
        'delete_pages',
        'delete_posts',
        'delete_private_pages',
        'delete_private_posts',
        'delete_published_pages',
        'delete_published_posts',
        #'edit_dashboard',
        'edit_others_pages',
        'edit_others_posts',
        #'edit_pages',
        #'edit_posts',
        'edit_private_pages',
        'edit_private_posts',
        'edit_published_pages',
        'edit_published_posts',
        'edit_theme_options',
        'export',
        'import',
        #'list_users',
        'manage_categories',
        'manage_links',
        'manage_options',
        'moderate_comments',
        'promote_users',
        'publish_pages',
        #'publish_posts',
        'read_private_pages',
        'read_private_posts',
        #'read',
        #'remove_users',
        'switch_themes',
        'upload_files',
        'customize',
        'delete_site',
        'delete_posts',
        'delete_published_posts',
        'edit_posts',
        'dit_published_posts ',
        'delete_others_pages',
        'delete_others_posts',
        'delete_pages',
        'delete_posts',
        'delete_private_pages',
        'delete_private_posts',
        'delete_published_pages',
        'delete_published_posts',
        'edit_others_pages',
        'edit_others_posts',
        'edit_pages',
        'edit_posts',
        'edit_private_pages',
        'edit_private_posts',
        'edit_published_pages',
        'edit_published_posts',
        'manage_categories',
        'manage_links',
        'moderate_comments',
        'publish_pages',
        'publish_posts',
        'read',
        'read_private_pages',
        'read_private_posts',
        'unfiltered_html',
        'upload_files'
    );

    foreach ( $caps_to_remove as $cap ) {
    
        // Remove the capability.
        $administrator->remove_cap( $cap );
    }

}

function registerSidebar() {
	for ($i=1; $i<=6; $i++) {
		register_sidebar( array(
	        'name' => __( 'TV Colunm '.$i, 'projectimer-plugin' ),
	        'id' => 'tv-colunm-'.$i,
	        'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>',
	    ) );
	}

}

function createPostType() {
	
	if ( ! post_type_exists( "projectimer_focus" ) ) {
		$labelFocus = array(
			'name'  => __( 'Focus',' projectimer-plugin' ), 
			'singular_name' => __( 'Focus',    ' projectimer-plugin' ),
			'add_new'    => __( 'Add New', ' projectimer-plugin' ),
			'add_new_item'  => __( 'Add New Focus',    ' projectimer-plugin' ),
			'edit'  => __( 'Edit', ' projectimer-plugin' ),
			'edit_item'  => __( 'Edit Focus', ' projectimer-plugin' ),
			'new_item'   => __( 'New Focus', ' projectimer-plugin' ),
			'view'  => __( 'View Focus', ' projectimer-plugin' ),
			'view_item'  => __( 'View Focus', ' projectimer-plugin' ),
			'search_items'  => __( 'Search Focus', ' projectimer-plugin' ),
			'not_found'  => __( 'No Focus found', ' projectimer-plugin' ),
			'not_found_in_trash' => __( 'No Focus found in Trash', ' projectimer-plugin' ),
			'parent'     => __( 'Parent Focus',     ' projectimer-plugin' ),
		);
		
		$postTypeFocusParams = array(
			'labels'  => $labelFocus,
			'singular_label'  => __( 'Focus', ' projectimer-plugin' ),
			'public'  => true,
			//'show_ui' => true,
			'menu_icon' => WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . '/images/projectimer-focus-icon.png',
			'description' => 'Post type for Projectimer Plugin',
			'menu_position'   => 20,
			'can_export' => true,
			'hierarchical'    => false,
			'capability_type' => 'post',
			'rewrite' => array( 'slug' => 'cycle', 'with_front' => false ),
			'query_var'  => true,
			'taxonomies' => array('post_tag'),
			'supports'   => array( 'title', 'content', 'editor', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields' )
		);

		register_post_type("projectimer_focus", $postTypeFocusParams);
	}
	if ( ! post_type_exists( "projectimer_rest" ) ) {
		$labelsRest = array(
			'name'  => __( 'Rest',' projectimer-plugin' ), 
			'singular_name' => __( 'Rest',    ' projectimer-plugin' ),
			'add_new'    => __( 'Add New', ' projectimer-plugin' ),
			'add_new_item'  => __( 'Add New Rest',    ' projectimer-plugin' ),
			'edit'  => __( 'Edit', ' projectimer-plugin' ),
			'edit_item'  => __( 'Edit Rest', ' projectimer-plugin' ),
			'new_item'   => __( 'New Rest', ' projectimer-plugin' ),
			'view'  => __( 'View Rest', ' projectimer-plugin' ),
			'view_item'  => __( 'View Rest', ' projectimer-plugin' ),
			'search_items'  => __( 'Search Rest', ' projectimer-plugin' ),
			'not_found'  => __( 'No Rest found', ' projectimer-plugin' ),
			'not_found_in_trash' => __( 'No Rest found in Trash', ' projectimer-plugin' ),
			'parent'     => __( 'Parent Rest',     ' projectimer-plugin' ),
		);
		
		$postTypeRestParams = array(
			'labels'  => $labelsRest,
			'singular_label'  => __( 'Rest', ' projectimer-plugin' ),
			'public'  => true,
			//'show_ui' => true,
			'menu_icon' => WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . '/images/projectimer-rest-icon.png',
			'description' => 'Post type for Projectimer Plugin',
			'menu_position'   => 20,
			'can_export' => true,
			'hierarchical'    => false,
			'capability_type' => 'post',
			'rewrite' => array( 'slug' => 'cycle', 'with_front' => false ),
			'query_var'  => true,
			'taxonomies' => array('post_tag'),
			'supports'   => array( 'title', 'content', 'editor', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields' )
		);

		register_post_type("projectimer_rest", $postTypeRestParams);
	}
	if ( ! post_type_exists( "projectimer_lost" ) ) {
		$labelsLost = array(
			'name'  => __( 'Lost',' projectimer-plugin' ), 
			'singular_name' => __( 'Lost',    ' projectimer-plugin' ),
			'add_new'    => __( 'Add New', ' projectimer-plugin' ),
			'add_new_item'  => __( 'Add New Lost',    ' projectimer-plugin' ),
			'edit'  => __( 'Edit', ' projectimer-plugin' ),
			'edit_item'  => __( 'Edit Lost', ' projectimer-plugin' ),
			'new_item'   => __( 'New Lost', ' projectimer-plugin' ),
			'view'  => __( 'View Lost', ' projectimer-plugin' ),
			'view_item'  => __( 'View Lost', ' projectimer-plugin' ),
			'search_items'  => __( 'Search Lost', ' projectimer-plugin' ),
			'not_found'  => __( 'No Lost found', ' projectimer-plugin' ),
			'not_found_in_trash' => __( 'No Lost found in Trash', ' projectimer-plugin' ),
			'parent'     => __( 'Parent Lost',     ' projectimer-plugin' ),
		);
		
		$postTypeLostParams = array(
			'labels'  => $labelsLost,
			'singular_label'  => __( 'Lost', ' projectimer-plugin' ),
			'public'  => true,
			//'show_ui' => true,
			'menu_icon' => WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . '/images/projectimer-focus-icon.png',
			'description' => 'Post type for Projectimer Plugin',
			'menu_position'   => 20,
			'can_export' => true,
			'hierarchical'    => false,
			'capability_type' => 'post',
			'rewrite' => array( 'slug' => 'cycle', 'with_front' => false ),
			'query_var'  => true,
			'taxonomies' => array('post_tag'),
			'supports'   => array( 'title', 'content', 'editor', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields' )
		);

		register_post_type("projectimer_lost", $postTypeLostParams);  
	}
}     



function save_my_extra_user_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) { return false; } else {
   if(isset($_POST['cycle_time']) && $_POST['cycle_time'] != ""){
    update_usermeta( $user_id, 'cycle_time', $_POST['cycle_time'] );
   }
    }
}

function plugin_admin_add_page() {
	add_options_page('Projectimer', 'Settings', 'manage_options', 'plugin-projectimer', 'plugin_projectimer_options_page');
	//
}

// display the admin options page
function plugin_projectimer_options_page() {
?>
	<div>
	<h2>My custom plugin</h2>
	Options relating to the Custom Plugin.
	<form action="options.php" method="post">
	<?php settings_fields('plugin_options'); ?>
	<?php do_settings_sections('plugin'); ?>
	 
	<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
	</form></div>
<?php 
}




/*TODO: better security solution to prevent user from access wp-admin*/
//Rename POSTS to CYCLE
function projectimer_edit_admin_menus() {  
    global $menu;  
    $menu[5][0] = 'Ciclos'; // Change Posts to Pomodoros
} 





function projectimer_remover_menu_pages() {
	//is_author() if (!is_admin() ) { - if(!current_user_can('administrator')) { if ($user_level < 5) {
	get_currentuserinfo();
	if(!current_user_can('administrator')) {
		remove_menu_page('link-manager.php');
		remove_menu_page('themes.php');
		remove_menu_page('index.php');
		remove_menu_page('tools.php');
		remove_menu_page('profile.php');
		remove_menu_page('upload.php');
		remove_menu_page('post.php');
		remove_menu_page('post-new.php');
		remove_menu_page('edit-comments.php');
		remove_menu_page('admin.php');
		remove_menu_page('edit-comments.php');
		remove_submenu_page( 'edit.php', 'post-new.php' );
		remove_submenu_page( 'tools.php', 'wp-cumulus.php' );
		
		 remove_meta_box('linktargetdiv', 'link', 'normal');
		  remove_meta_box('linkxfndiv', 'link', 'normal');
		  remove_meta_box('linkadvanceddiv', 'link', 'normal');
		  remove_meta_box('postexcerpt', 'post', 'normal');
		  remove_meta_box('trackbacksdiv', 'post', 'normal');
		  remove_meta_box('commentstatusdiv', 'post', 'normal');
		  remove_meta_box('postcustom', 'post', 'normal');
		  remove_meta_box('commentstatusdiv', 'post', 'normal');
		  remove_meta_box('commentsdiv', 'post', 'normal');
		  remove_meta_box('revisionsdiv', 'post', 'normal');
		  remove_meta_box('authordiv', 'post', 'normal');
		  remove_meta_box('sqpt-meta-tags', 'post', 'normal');
		  remove_meta_box('submitdiv', 'post', 'normal');
		  remove_meta_box('avhec_catgroupdiv', 'post', 'normal');
		  remove_meta_box('categorydiv', 'post', 'normal');
	}
}
//TA CONFLITANDO COM TUDO QUE EH TEMA ESSA PORRA
//add_action( 'admin_menu', 'projectimer_edit_admin_menus' ); 
//add_action( 'admin_menu', 'projectimer_remover_menu_pages' );


/*
//What about declate functions up here?
$pomodoros_to_big_rest=4;

$focusTime = 60*25;
$restTime = 60*5;
$intervalTime = 60*20;

//Session control vars
$actualCicle = 1;
$is_focus_time = true;
$secondsRemaing = focusTime;
$is_countdown_active = false;

//Vars used for development pouposes, temporary
//$intervalMiliseconds = 10;
/*
//function update_status_message() {};
//function projectimer_CheckActivity(){};
function has_active_cycle(){};
function action_button(){};
//function start_cycle(){};
//function count(){};
function complete_cycle(){};
function stop_cycle(){};
function interrupt_cycle(){};
function update_status_message(){};
function update_canvas(){};

function update_status_message() {

}
//to check if the timmer is running*/
//function projectimer_check_activity() {
	//update_status_message("projectimer_CheckActivity");
	//Check if user has future posts and load if yes
	//'post_date_gmt'  => $postdate $postdate = date('2014-06-11 00:30:00');
	
	
//}
/*
function start_cycle(){
	$tagsinput = explode(" ", $_POST['post_tags']);	
	$my_post = array(
		'post_title' => $_POST['post_titulo'],
		'post_content' => $_POST['post_descri'],
		'post_date_gmt'=> strtotime('+25 minutes'),
		//'post_status' => $_POST['post_priv'],
		'post_status' => 'draft',
		'post_category' => array(1),
		'post_author' => $current_user->ID,
		'tags_input' => array($_POST['post_tags'])
		//'post_category' => array(0)
	);
	wp_insert_post( $my_post );
};*/
