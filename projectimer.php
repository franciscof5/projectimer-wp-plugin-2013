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
if ( ! is_admin() ) {
	add_action('init', 'projectimer_loadScripts');
	//add_action('init', 'projectimer_CheckActivity');
	//add_action('init', 'projectimer_loadScripts');
	
	/* TEMPLATE TAGS */
	add_action( 'projectimer_show_clock_simplist', 'projectimer_show_clock_simplist' );
	add_action( 'projectimer_show_task_form', 'projectimer_show_task_form' );
	add_action( 'projectimer_show_setting_box', 'projectimer_show_setting_box');
	//create more painels, widgets!!!! like graphics and control related things, to user make their own custom page

	//prevent multiple sessions from same user
	//add_action('wp_login', 'add_usermeta_to_prevent_multiple_sessions', 10, 2);
}

/* */
add_action( 'show_user_profile', 'my_extra_user_fields' );
add_action( 'edit_user_profile', 'my_extra_user_fields' );

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
add_action( 'personal_options_update', 'save_my_extra_user_fields' );
add_action( 'edit_user_profile_update', 'save_my_extra_user_fields' );

function save_my_extra_user_fields( $user_id ) 
{
    if ( !current_user_can( 'edit_user', $user_id ) ) { return false; } else {
        if(isset($_POST['cycle_time']) && $_POST['cycle_time'] != ""){
            update_usermeta( $user_id, 'cycle_time', $_POST['cycle_time'] );
        }
    }
}
/* OPTIONS PAGE ON WP-ADMIN */
add_action('admin_menu', 'plugin_admin_add_page');
function plugin_admin_add_page() {
	add_options_page('Projectimer', 'Settings', 'manage_options', 'plugin-projectimer', 'plugin_projectimer_options_page');
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
require( dirname( __FILE__ ) . '/projectimer-ajax.php' );
require( dirname( __FILE__ ) . '/projectimer-template-tags.php' );
/* INITS */
function projectimer_loadScripts() {	
	//LOADED AFTER CHECK ACTIVIVY
	//wp_register_script( 'tips', get_template_directory_uri() . '/pomodoro/tips.js', array(), '0.1', true );
	wp_register_script('soundmanager', plugins_url('/js/soundmanager2-nodebug-jsmin.js', __FILE__) );
	wp_register_script('canvas-draw', plugins_url('/js/canvas-draw.js', __FILE__) );
	wp_register_script('projectimer-js', plugins_url('/projectimer.js', __FILE__) );
	wp_register_script('select2js', plugins_url('/js/select2.js', __FILE__) );
	wp_register_style('select2css', plugins_url('/css/select2.css', __FILE__) );
	wp_register_style('projectimer-css', plugins_url('/projectimer.css', __FILE__) );
	
	//wp_register_script('projectimer-ajax-js', plugins_url('/js/projectimer-ajax.js', __FILE__) );
	//wp_register_style('clockcss', plugins_url('/css/clock.css', __FILE__) );
	wp_enqueue_script("jquery");
	wp_enqueue_script('heartbeat'); 
	wp_enqueue_style('projectimer-css');
	wp_enqueue_style('alertify-css', plugins_url('/css/alertify.core_and_default_merged.css', __FILE__) );
	//wp_enqueue_script('moment', plugins_url('/js/moment-timezone-with-data-2010-2020.min.js', __FILE__) );
	wp_enqueue_script('alertify-js', plugins_url('/js/alertify.min.js', __FILE__) );
	wp_enqueue_script('projectimer-user-settings', plugins_url('/projectimer-user-settings.js', __FILE__) );
	//enqueue the Heartbeat API
	//TODO use that concept showed bellow, setting on user_meta
	//update_user_meta(get_current_user_id(), "focusTime", 150000);
	//echo  dirname( __FILE__  ) . '/locale';
	//var_dump(load_plugin_textdomain('plugin-projectimer', false, dirname(plugin_basename(__FILE__). '/locale')));die;
	//var_dump(load_plugin_textdomain('projectimer', false, dirname( __FILE__ ) . '/locale' ));die;
}
/* AJAX */
add_action('wp_ajax_projectimer_save_modelnow', 'projectimer_save_modelnow');
add_action('wp_ajax_projectimer_update_user_meta', 'projectimer_update_user_meta');
add_action('wp_ajax_projectimer_load_user_settings', 'projectimer_load_user_settings');
add_action('wp_ajax_projectimer_add_activie', 'projectimer_add_activie');
add_action('wp_ajax_projectimer_update_cycle_status', 'projectimer_update_cycle_status');
add_action('wp_ajax_projectimer_schedule_cycle', 'projectimer_schedule_cycle');

/*TODO: better security solution to prevent user from access wp-admin*/
//Rename POSTS to CYCLE
function edit_admin_menus() {  
    global $menu;  
    $menu[5][0] = 'Ciclos'; // Change Posts to Pomodoros
} 

function my_remove_menu_pages() {
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
//add_action( 'admin_menu', 'edit_admin_menus' ); 
//add_action( 'admin_menu', 'my_remove_menu_pages' );


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
