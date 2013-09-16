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

function load_my_scripts() {  
	//
	wp_register_script('soundmanager', plugins_url('/js/soundmanager2-nodebug-jsmin.js', __FILE__) );
	wp_register_script('clockscript', plugins_url('/js/clock.js', __FILE__) );
	wp_register_script('clockAjax', plugins_url('/js/clock_ajax.js', __FILE__) );
	wp_enqueue_script( 'soundmanager' );
	wp_enqueue_script( 'clockscript' );  
	wp_enqueue_script( 'clockAjax' );  
	//
	wp_register_style('clockcss', plugins_url('/css/clock.css', __FILE__) );
	wp_enqueue_style('clockcss');
}  
add_action('init', 'load_my_scripts');  

wp_register_script( 'tips', get_template_directory_uri() . '/pomodoro/tips.js', array(), '0.1', true );
wp_enqueue_script( 'jquery' );

function projectimer_showclock () {
 	echo 
 	'<div id="shadow"></div>
    <canvas width="200" height="200" id="myCanvas"></canvas>
    <div id="shine"></div>
    <input type="button" value="focus" onclick="action_button()" id="action_button_id" />
    <div id="div_status" style="background:#FFF;">Teste</div>';
 }
 add_action( 'projectimer_showclock', 'projectimer_showclock' );
