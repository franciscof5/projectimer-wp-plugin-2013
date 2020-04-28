<?php
//francisco matelli: 2015-02-07
//francisco refactored: 2016-11-12

// ENVIROMENT (AJAX - HTML - JSON)
function projectimer_show_clock_simplist() {
	?>
	<div id="offline_sign" class="">
		<div id="offline_sign_triangle" class="triangle"></div>
		<span id="offline_sign_icon" class="glyphicon glyphicon-ban-circle" aria-hidden="true"  data-toggle="popover" data-title="No server connection..." data-content="<?php _e("You have no internet connection or are working offline, local data and cache will be used untill next time online syncronization.", "plugin-projectimer"); ?>" data-placement="bottom"></span>
	</div>
	<!--div id="lights">
		<button class="btn btn-default"><span class="glyphicon glyphicon-adjust" aria-hidden="true"></span></button>
	</div-->
	<div id="clock-container">
		<button id="action_button" tabindex="1" disabled="disabled" /></button>
	
		<div id="clock-label"><?php echo get_user_meta(get_current_user_id(),"focus_time", true); ?>:00</div>
	
		<div id="div_status" data-toggle="popover" data-content="Status" data-title="<?php _e("Status (click to hide)", "plugin-projectimer"); ?>" data-placement="bottom"><span id="div_status_label" class="label label-default" onclick="div_status_press()">loading...</span></div>
	
		<div id="update_status" >
			<span id="update_status_label" class="label label-default" onclick="communication_press()" data-toggle="popover" data-title="<?php _e("Status: Online (PRO)", "plugin-projectimer"); ?>" data-content="<?php _e("Click to become offline to prevent your actitivy from showing on the feed. Become a PRO user to unlock this feature", "plugin-projectimer"); ?>" data-alternative-title="<?php _e("Status: Offline (PRO)", "plugin-projectimer"); ?>" data-alternative-content="<?php _e("Click to stay online again", "plugin-projectimer"); ?>" data-placement="top">
				<span id="update_status_icon" class="glyphicon glyphicon-cloud-download download-alt" aria-hidden="true"></span>
			</span>
		</div>
		
		<div id="session_current_cycle">
			<span class="label label-default" data-toggle="popover" data-title="<?php _e("Consecutive Cycles (PRO)", "plugin-projectimer"); ?>" data-content="<?php _e("How many consecutive cycles without stop. After 4 cycles we set a big rest but PRO users can click anytime to take a big rest", "plugin-projectimer"); ?>" data-placement="top" >0</span>
		</div>
	</div>
	<br style="clear:both" >
	<?php
	// glyphicons: refresh, info-sign, cloud-download, transfer, floppy-disk, cloud, asterisk
}

// TASK FORM
function projectimer_display_task_tabs() {
	?>
	<div id="task_form">
	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist" id="task_tabsett">
		<li role="presentation" class="active"><a href="#tab-task" aria-controls="tab-task" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-play" aria-hidden="true"></span> Task</a></li>
		<li role="presentation"><a href="#tab-trello" aria-controls="tab-trello" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Trello</a></li>
		<!--li role="presentation"><a href="#tab-model" aria-controls="tab-model" role="tab" data-toggle="tab"  class="disabled"><span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span> Models</a></li-->
		<li role="presentation"><a href="#tab-completed" aria-controls="tab-completed" role="tab" data-toggle="tab"  class="disabled"><span class="glyphicon glyphicon-tags" aria-hidden="true"></span> Tags</a></li>

	  </ul>

	  <!-- Tab panes -->
	  <div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="tab-task"><?php projectimer_tab_task_form(); ?></div>
		<div role="tabpanel" class="tab-pane" id="tab-trello"><?php projectimer_tab_task_trello(); ?></div>
		<?php /* <!--div role="tabpanel" class="tab-pane" id="tab-model"><?php projectimer_tab_task_model_form(); ?></div-->*/ ?>
		<div role="tabpanel" class="tab-pane" id="tab-completed"><?php projectimer_tab_task_completed_form(); ?></div>
	  </div>
	</div>
	<?php
	#projectimer_show_task_trash
}

function projectimer_tab_task_form () {
	wp_enqueue_style("select2css");
	wp_enqueue_script("select2js");
	?>
	<div class="row">
		<h3 style="float:left;">
			<?php _e("Task Clipboard", "plugin-projectimer"); ?><span class="glyphicon glyphicon-info-sign glyinfo" aria-hidden="true" data-toggle="popover" data-title="<?php _e("The Projectimer Way", "plugin-projectimer"); ?>" data-content="<?php _e("The form below is optional, it was designed to help you boost you productivity by making you thinking before or while acting.", "plugin-projectimer"); ?>" data-placement="right"></span>
		</h3>
		<button id="save_task_model" class="btn btn-default" onclick="save_model()"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> <?php _e("SAVE", "plugin-projectimer"); ?></button>
	</div>
	<div class="row">
		<form name="task_form_current_task">
			<div class="form-group row">
				<label for="title_box" class="col-sm-4 col-form-label">
					<span class="glyphicon glyphicon-paste" aria-hidden="true"></span> 
					<?php _e("Title", "plugin-projectimer"); ?>
					<span class="glyphicon glyphicon-info-sign glyinfo" aria-hidden="true" data-toggle="popover" data-title="<?php _e("Task Title", "plugin-projectimer"); ?>" data-content="<?php _e("We recommend to use task title to better organize work, but you dont need to fill that field.", "plugin-projectimer"); ?>" data-placement="right"></span>
				</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="title_box" placeholder="task title">
				</div>
			</div>

			<div class="form-group row">
				<label for="tags_box" class="col-sm-4 col-form-label">
					<span class="glyphicon glyphicon-tags" aria-hidden="true"></span> 
					<?php _e("Tags", "plugin-projectimer"); ?>  
					<span class="glyphicon glyphicon-info-sign glyinfo" aria-hidden="true" data-toggle="popover" data-title="<?php _e("Project Tags (PRO)", "plugin-projectimer"); ?>" data-content="<?php _e("Use you creativity, tags can be used to track time for clients, projects, milestones, study, and whetever you need. All tags are shared with team members.", "plugin-projectimer"); ?>" data-placement="right"></span>
				</label>
				<div class="col-sm-8">
					<select id="tags_box" class="js-example-tags form-control" multiple="multiple" placeholder="Does not work, use data-placeholder with js trick"  data-placeholder="project1, project2..."></select>
				</div>
			</div>

			<div class="form-group row">
				<label for="description_box" class="col-sm-4 col-form-label">
					<span class="glyphicon glyphicon-text-background" aria-hidden="true"></span>
					<?php _e("Desc", "plugin-projectimer"); ?>
					<span class="glyphicon glyphicon-info-sign glyinfo" aria-hidden="true" data-toggle="popover" data-title="<?php _e("Task Description", "plugin-projectimer"); ?>" data-content="<?php _e("Add extra task details not covered by the form, e.g. give team detailed orientation or to take notes during task execution", "plugin-projectimer"); ?>" data-placement="right"></span>
				</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="description_box" placeholder="description...">
				</div>
			</div>

			<div class="form-group row">
				<label for="type_box" class="col-sm-4">
					<span class="glyphicon glyphicon-paperclip folder-open" aria-hidden="true"></span>
					<?php _e("Type", "plugin-projectimer"); ?>
					<span class="glyphicon glyphicon-info-sign glyinfo" aria-hidden="true" data-toggle="popover" data-title="<?php _e("Task Type", "plugin-projectimer"); ?>" data-content="<?php _e("This can be used to isolate and track different time usages for you projectimer", "plugin-projectimer"); ?>" data-placement="right"></span>
				</label>
				<div class="col-sm-8">
					<div class="btn-group btn-group-justified" data-toggle="buttons">
					  <label class="btn btn-default warning">
						<input type="radio" name="type_box" value="study" autocomplete="off"> <?php _e("Study", "plugin-projectimer"); ?>
					  </label>
					  <label class="btn btn-default info">
						<input type="radio" name="type_box" value="work" autocomplete="off"> <?php _e("Work", "plugin-projectimer"); ?>
					  </label>
					  <label class="btn btn-default success">
						<input type="radio" name="type_box" value="personal" autocomplete="off"> <?php _e("Personal", "plugin-projectimer"); ?>
					  </label>
					</div>
				</div>
			</div>

			<div class="form-group row">
				<label for="planned_box" class="col-sm-4">
					<span class="glyphicon glyphicon-sort-by-order" aria-hidden="true"></span>
					<?php _e("Plan", "plugin-projectimer"); ?>
					<span class="glyphicon glyphicon-info-sign glyinfo" aria-hidden="true" data-toggle="popover" data-title="<?php _e("Planned Task", "plugin-projectimer"); ?>" data-content="<?php _e("The task you are doing can be part of a bigger or formal plan, but if it was not planned early it's more like improvisation", "plugin-projectimer"); ?>" data-placement="right"></span>
				</label>
				<div class="col-sm-8">
					<div class="btn-group btn-group-justified" data-toggle="buttons">
						<label class="btn btn-default">
							<input type="radio" name="planned_box" value="planned" autocomplete="off"> <?php _e("Planned", "plugin-projectimer"); ?>
						</label>
						<label class="btn btn-default">
							<input type="radio" name="planned_box" value="improvisation" autocomplete="off"> <?php _e("Improvisation", "plugin-projectimer"); ?>
						</label>
					</div>
				</div>
			</div>

			<div class="form-group row">
				<label for="virtual_box" class="col-sm-4">
					<span class="glyphicon glyphicon-globe hand-right" aria-hidden="true"></span>
					<?php _e("Virtual", "plugin-projectimer"); ?>
					<span class="glyphicon glyphicon-info-sign glyinfo" aria-hidden="true" data-toggle="popover" data-title="<?php _e("Outside Computer", "plugin-projectimer"); ?>" data-content="<?php _e("If you are using Projectimer to run tasks outside computer, with no use of mouse, keyboard or screen at all, like folding clothes or cleaning office select Real", "plugin-projectimer"); ?>" data-placement="right"></span>
				</label>
				<div class="col-sm-8">
					<div class="btn-group btn-group-justified" data-toggle="buttons">
						<label class="btn btn-default">
							<input type="radio" name="virtual_box" value="virtual" autocomplete="off"> <?php _e("Virtual", "plugin-projectimer"); ?>
						</label>
						<label class="btn btn-default">
							<input type="radio" name="virtual_box" value="real" autocomplete="off"> <?php _e("Real", "plugin-projectimer"); ?>
						</label>
					</div>
				</div>

				<!--ADD MOOD: HAPPY, PRESSIONATED, TENSUS, CALM, CONFIDENT, ANGRY-->

			</form>
		</div>
	</div>
	<?php 
}

function projectimer_tab_task_model_form () {
	?>
	<h3>Model Task</h3>
	<p>Need to swicth quicly between tasks? save a model task and use it anytime you want.</p>
	
	<div>
	<table  id="table-task-models" class="table table-striped">
		<thead> 
		<tr> 
			<th>Task</th> 
			<th>Projects</th> 
			<th>Use</th> 
			<th>Complete</th> 
		</tr> 
		</thead>
	<?php
	$args = array(
			  "post_type" => "projectimer_focus",
			  "post_status" => "pending",
			  "author"   => get_current_user_id(),
			  //"orderby"   => "title",
			  "order"     => "ASC",
			  "posts_per_page" => -1,
			);
	$the_query = new WP_Query( $args );
	
	while ( $the_query->have_posts() ) : $the_query->the_post();
		$counter = $post->ID;
		#echo '<ul id="modelo-carregado-'.$counter.'">';
		echo "<tr>";
		echo "<td>";
		#the_title("<input type='text' value='","' disabled=disabled id=bxtitle$counter /><br />");
		the_title();
		echo "</td>";
		echo "<td>";
		#echo "<input type='text' value='".get_the_content()."' disabled=disabled id=bxcontent$counter /><br />";
		//http://stackoverflow.com/questions/2809567/showing-tags-in-wordpress-without-link
		$posttags = get_the_tags();
		  if ($posttags) {
			foreach($posttags as $tag) {
			#echo "<input type='text' value='".$tag->name."' disabled=disabled  id=bxtag$counter />";
			echo $tag->name;
			}
		}
		echo "</td>";
		echo "<td>";
		echo "<input type='button' class='btn' value='use' onclick='load_model($counter)'>";
		echo "</td>";
		echo "<td>";
		echo "<input type='button' class='btn' value='complete' onclick='delete_model($counter)'></p>";
		echo "</td>";
		echo '</tr>';
		
	endwhile;
	// Reset Post Data
	wp_reset_postdata();
	?>
	
	
	<?php
	#wp_get_current_user();
	#if(current_user_can('administrator')) {
	#echo '</div>';
	#}
	?>
	</table>
	</div>

	<?php
}

function projectimer_tab_task_trello () {
	//wp_enqueue_script("trello-client");
	wp_enqueue_script("trello-projectimer");
	//wp_enqueue_style('trello-css');
	//<script src="https://api.trello.com/1/client.js?key=7d619e7c1a348e16048608ab36f0c083"></script>
	//<script src="https://trello.com/1/client.js?key=7d619e7c1a348e16048608ab36f0c083"></script>
	?> 
	<!--TRELLO-->
	<script src="https://trello.com/1/client.js?key=7d619e7c1a348e16048608ab36f0c083"></script>
	
	<style type="text/css">
		/*#accordion-boards {
			max-height: 440px;
			overflow-y: scroll;
		}*/
		#trello-container {
			max-height: 270px;
			overflow-y: scroll;
		}
		.panel-group {
			margin: 0 !important;
			padding: 0 !important;
		}
		.accordion-wrapper {
			display: block;
			height: 20rem;
			max-height: 100%;
		}

		#accordion {
			max-width: 20rem;/*36.125rem;*/
			/*max-width: 36.125rem;
			width: 100%*/
			margin-left: 15px;
		}

		.panel-g-horizonta {
			-webkit-backface-visibility: hidden;
			-webkit-transform: translateX(-98%) rotate(-90deg);
			-webkit-transform-origin: right top;
			-moz-transform: translateX(-98%) rotate(-90deg);
			-moz-transform-origin: right top;
			-o-transform: translateX(-98%) rotate(-90deg);
			-o-transform-origin: right top;
			transform: translateX(-98%) rotate(-90deg);
			transform-origin: right top;
			/*margin-bottom: 15px;*/
		}

		.panel-b-horizontal {
			-webkit-backface-visibility: hidden;
			-webkit-transform: translateX(0%) rotate(90deg);
			/*-webkit-transform-origin: left top;*/
			-moz-transform: translateX(0%) rotate(90deg);
			/*-moz-transform-origin: left top;*/
			-o-transform: translateX(0%) rotate(90deg);
			/*-o-transform-origin: left top;*/
			
			transform: translateX(0%) rotate(90deg);
			transform-origin: left top;
			/*width: 26rem;
			/*height: 86.2rem;
			overflow: scroll;*/
			overflow: scroll;
			/*margin-left: -80px;/*f5sites*/
			position: relative;
			left: 20rem;
			/*width: 26rem;*/
			min-height: 20rem;
		}
		.hresquema {
			width: 16rem;

		}
		.pbginsideListList {
			width: 18rem;
			/*background: #093;*/
			max-height: 18rem;
    		max-width: 12rem;
		}
		.pbginsideListList li {
			white-space: nowrap;
		}

		#accordion .panel-collapse {
			/*width: 23rem;
			/*background: #093;*/
		}
	</style>
	<div class="row">
		<h3 class="col-md-6">
			<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Trello
		</h3>
		<?php /*
		<span class="col-md-8" style="line-height: 60px;text-align: right;">
			<strong>Tags</strong>
			<input type="checkbox" name="title_filter" value="boards">Boards
			<input type="checkbox" name="title_filter" value="lists">Lists
		</span> */ ?>
		<div id="trelloLoadOptions" class="col-md-6"> 
			<a href="#tab-trello-settings" class="open_settings_modal" data-toggle="modal" data-target="#projectimer_settingsbox_modal"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Trello importing settings</a>
			<a href="#tab-teams" class="open_settings_modal" data-toggle="modal" data-target="#projectimer_settingsbox_modal"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php _e("Teams", "projectimer-plugin"); ?></a></li>
		</div>
	</div>

	<div id="trello-container">
		receiving information from Trello server....
	</div>
	<br />
	<span id="trello-status" style="padding: 0">
		<span class="label label-default">starting Projectimer Trello...</span>
	</span>
	<br /><small>Trello is a third-party integration provides by its API in a safe way. We use ',' as separator for tags, avoid it on titles.</small>
	<?php /*
	<div class="container-fluid" style="padding: 0">
	<div class="row">
		<div class="col-lg-12" style="padding: 0">
			<div class="accordion-wrapper" style="margin: 0">
				<div class="panel-g-horizonta" id="accordion" role="tablist" aria-multiselectable="true" style="margin: 0">
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Featured Story
								</a>
							</h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
							<div class="panel-b-horizontal">
								<h4>Slide Title</h4>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingTwo">
							<h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">About The Reins Act
								</a>
							</h4>
						</div>
						<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
							<div class="panel-b-horizontal">
								<h4>Slide Title</h4>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingThree">
							<h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Video
								</a>
							</h4>
						</div>
						<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
							<div class="panel-b-horizontal">
								<h4>Slide Title</h4>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingFour">
							<h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseFour">Photos
								</a>
							</h4>
						</div>
						<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
							<div class="panel-b-horizontal">
								<h4>Slide Title</h4>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
    */ ?>
	
	<!--br style="clear: both" /-->
	<?php //echo projectimer_trello_status(); 

}

function projectimer_tab_task_completed_form() {
	?>
	<h3>Completed Models</h3>
	<p>Se what you did and maybe restore an old task back to live.</p>
	
	<div id="contem-modelos">
	<table class="table table-striped">
		<thead> 
		<tr> 
			<th>Task</th> 
			<th>Projects</th> 
			<th>Restore</th> 
			<th>Delete</th> 
		</tr> 
	</table>
	</div>

	<?php
	$args = array(
		'post_type' => "projectimer_focus",
		'author'        =>  get_current_user_id(),
		'post_status' => array("publish"),
		'taxonomy' => 'post_tag',
		'posts_per_page' => -1,

	);
	$terms = get_terms( $args );
	
	//var_dump($terms);
	echo '<ul>';
	 
	foreach ( $terms as $term ) {
	 
		// The $term is an object, so we don't need to specify the $taxonomy.
		$term_link = get_term_link( $term );
		
		// If there was an error, continue to the next term.
		if ( is_wp_error( $term_link ) ) {
			continue;
		}
	 
		// We successfully got a link. Print it out.
		echo '<li><a href="' . esc_url( $term_link ) . '">' . $term->name . '</a></li>';
	}
	 
	echo '</ul>';
}

function checkCredentials() {
	return is_user_member_of_blog(get_current_user_id());
}

function projectimer_display_active_projectimers() {
	$args = array(
		"post_type"=>"projectimer_focus",
		"post_status"=>"future");
	$act_cycles = get_posts($args);
	foreach ($act_cycles as $cycle) {
		echo get_the_author_meta("display_name", $cycle->post_author);
		echo ", ";
		echo $cycle->post_name;
		echo ", ";
		$phpdate = strtotime( $cycle->post_date_gmt );
		echo human_time_diff( date("U", $phpdate), current_time('timestamp') );
		echo ", Itapetininga, PC";
	}
	//var_dump($act_cycles);
	?>
	<!--ul id="active-projectimers">
		<li>Francisco - Tarefa 1 (2x), 3/4 ciclo, 22min</li>
		<li>Mathias - Tarefa 1 (2x), 3/4 ciclo, 22min</li>
	</ul-->
	<?php
}

// used in ajax either
function projectimer_display_recent_activities() { 
	//
	projectimer_display_active_projectimers();
	//
	if (function_exists('bp_has_activities')) { ?>
	<?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) ) ) : ?>
		<ul id="recent-activities">
		<?php 
		$i=0;
		while ( bp_activities() ) : bp_the_activity(); ?>
		<?php 
		$i++;
		if($i<20){ 
		do_action( 'bp_before_activity_entry' ); ?>

		<li class="<?php bp_activity_css_class(); ?>" id="activity-<?php bp_activity_id(); ?>">
			<div class="activity-avatar">
				<a href="<?php bp_activity_user_link(); ?>">

					<?php bp_activity_avatar(); ?>

				</a>
			</div>

			<div class="activity-content">
				<div class="activity-header">
					<?php bp_activity_action(); ?>
				</div>
				<div class="activity-inner">
					<?php bp_activity_content_body(); ?>
				</div>
			</div>
		</li>
		<?php } #locate_template( array( 'activity/entry.php' ), true, false ); ?>
	 
		<?php endwhile; ?>
		</ul>
	<?php endif; ?>	
	<?php } ?>
<?php } 


// MODALS 
function projectimer_display_login_modal() { ?>
	<div class="modal fade" tabindex="-1" role="dialog" id="login_modal">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h3 class="modal-title"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> User Details</h3>
	      </div>
	      <div class="modal-body">
	        	<?php if(is_user_logged_in()) {  ?>
					
					<?php
					//<h3>Teams</h3>
					do_action('projectimer_display_teams');

					?>
				<?php } else { ?>
					<center>
					<?php do_action( 'wordpress_social_login' ); ?> 
					

					<?php wp_login_form(); ?>
					<a href="<?php echo wp_lostpassword_url( get_bloginfo('url') ); ?>" title="Lost Password">Recuperar senha</a>
					</center>
					<div style="margin-top:-10px;">
						<?php do_action( 'bp_after_sidebar_login_form' ); ?>
					</div>
				<?php } ?>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<?php } 

function projectimer_display_remove_user_modal() {
	?>
	<div class="modal fade" tabindex="-1" role="dialog" id="remove_user_modal">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Remove a user from <?php bloginfo('blogname'); ?></h4>
	      </div>
	      <div class="modal-body">
	        <p>You are about to remove user</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
	        <button type="button" class="btn btn-danger" id="button_remove_user"><span class="glyphicon glyphicon-remove" aria-hidden="true" data-dismiss="modal"><?php _e("REMOVE", "plugin-projectimer"); ?></button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<?php
}

function projectimer_display_make_user_admin_modal() {
	?>
	<div class="modal fade" tabindex="-1" role="dialog" id="make_user_admin_modal">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Make a user an Administrator of <?php bloginfo('blogname'); ?></h4>
	      </div>
	      <div class="modal-body">
	        <p>You are about to make a user administrator of the team, caution it cannot be undone</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
	        <button type="button" class="btn btn-success" id="button_make_user_admin"><span class="glyphicon glyphicon-star" aria-hidden="true" data-dismiss="modal"><?php _e("MAKE ADMIN", "plugin-projectimer"); ?></button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<?php
}

function projectimer_display_recent_activities_load_task_modal() {
	?>
	<div class="modal fade" tabindex="-1" role="dialog" id="loadtask_modal">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Load a user task (PRO)</h4>
	      </div>
	      <div class="modal-body">
	        <p>You are about to load a task from activity feed, it will replace your current task, if you want to preserve your current task just hit save model button before&hellip;</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
	        <button type="button" class="btn btn-primary"  onclick="save_model()"><span class="glyphicon glyphicon-save" aria-hidden="true" data-dismiss="modal"><?php _e("SAVE", "plugin-projectimer"); ?></button>
	        <button type="button" class="btn btn-danger" id="erase_and_load"><span class="glyphicon glyphicon-save-file duplicate" aria-hidden="true" data-dismiss="modal"><?php _e("LOAD", "plugin-projectimer"); ?></button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<?php
}

function projectimer_display_settings_modal() { 
	if(is_user_logged_in()) {
	?>
	<script type="text/javascript">
	/*jQuery('#myTabs a').click(function (e) {
		e.preventDefault()
		jQuery(this).tab('show')
	});*/
	//jQuery("#projectimer_settingsbox_modal").on('shown.bs.modal', function(e) {
		//alert(e.relatedTarget + "asdasd");
		//jQuery('#tab-settings').tab('show');
	    //var tab = e.relatedTarget.hash;
	    //alert(tab);
	    //jQuery('.nav-tabs a[href="'+tab+'"]').tab('show')
	//});
	jQuery(".open_settings_modal").click(function(){
		//jQuery("#projectimer_settingsbox_modal").modal('toggle');
		var tab = jQuery(this).attr("href");
		//alert(tab);
		jQuery('.nav-tabs a[href="'+tab+'"]').tab('show')
		//var tab = e.relatedTarget.hash;
	    //alert(tab);
		//jQuery("#projectimer_settingsbox_modal")
	});
	</script>

	<!-- Modal -->
	<div class="modal fade" id="projectimer_settingsbox_modal" tabindex="-1" role="dialog" aria-labelledby="projectimer_settingsbox_modalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <!--h4 class="modal-title" id="projectimer_settingsbox_modalLabel">Settings</h4-->
		        <div>
				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs" role="tablist">
					<li role="presentation">
						<a href="#tab-settings" data-toggle="tab"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>  <?php _e("Settings", "projectimer-plugin"); ?></a></li>
					<li role="presentation">
						<a href="#tab-profile" data-toggle="tab"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>  <?php _e("Profile", "projectimer-plugin"); ?></a></li>  
					<li role="presentation">
						<a href="#tab-teams" data-toggle="tab"><span class="glyphicon glyphicon-th" aria-hidden="true"></span> <?php _e("Teams", "projectimer-plugin"); ?></a></li>
					<li role="presentation">
						<a href="#tab-notifications" data-toggle="tab"><span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span> <?php _e("Notifications", "projectimer-plugin"); ?></a></li>
					<li role="presentation">
						<a href="#tab-survey" data-toggle="tab"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> <?php _e("Survey", "projectimer-plugin"); ?></a></li>    
					<li role="presentation">
						<a href="#tab-trello-settings" data-toggle="tab"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <?php _e("Trello", "projectimer-plugin"); ?></a></li> 
					<li role="presentation">
						<a href="#tab-gagenda-settings" data-toggle="tab"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> <?php _e("Google Agenda", "projectimer-plugin"); ?></a></li> 
					<li role="presentation">
						<a href="#tab-help" data-toggle="tab"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> <?php _e("Help", "projectimer-plugin"); ?></a></li>
				  </ul>

				  <!-- Tab panes -->
				  <div class="tab-content">
					<div role="tabpanel" class="tab-pane" id="tab-settings">
						<?php projectimer_display_projectimer_settings_form(); ?>
					</div>
					<div role="tabpanel" class="tab-pane" id="tab-profile">
						<h3><?php _e("Profile", "plugin-projectimer"); ?></h3>
						<?php #bppp_progression_block(); ?>
						<p><?php
						wp_enqueue_style( 'wppb_stylesheetCOPY' );
						echo do_shortcode("[wppb-edit-profile]");
						?></p>
					</div>
					<div role="tabpanel" class="tab-pane" id="tab-teams">
						<?php do_action('projectimer_display_teams'); ?>
						<h3><span class="glyphicon glyphicon-user" aria-hidden="true"></span>Teams</h3>
						<p>Convide membros</p>
						
						
						<?php 
							the_widget("InviteAnyoneWidget");
							//<iframe src="http://projectimer.com/f5sites/members/francisco/invite-anyone/" style="width: 100%; height: 200px;"></iframe>
							//$wrapperPage = file_get_contents('http://projectimer.com/f5sites/members/francisco/invite-anyone/'); 
							//@include("http://projectimer.com/f5sites/members/francisco/invite-anyone/");
							//var_dump($wrapperPage);
							//echo $wrapperPage;//the_widget('[invite-anyone]');
							/*if ( function_exists( 'bp_update_option' ) ) {
								$options = bp_get_option( 'invite_anyone' );
							} else {
								$options = get_option( 'invite_anyone' );
							}apply_filters( 'invite_anyone_options', $options );*/
						?>
						<?php 
							//$ra_network_privacy = new RA_Network_Privacy();
							//$ra_network_privacy->network_privacy_options_page();
						?>
						</p>
					</div>
					<div role="tabpanel" class="tab-pane" id="tab-notifications">
						<h3>Notifications</h3>
						<p>
						<?php 
							echo do_shortcode('[bc_notifications]');
						?>
						</p>
					</div>

					<div role="tabpanel" class="tab-pane" id="tab-survey">
						<h3>Tell us...</h3>
						<p>how is your personal experience</p>
						<p>We are implementing our first surveys... please come back in few hours</p>
					</div>

					<div role="tabpanel" class="tab-pane" id="tab-trello-settings">
						<?php projectimer_display_trello_options(); ?>
					</div>
					<div role="tabpanel" class="tab-pane" id="tab-gagenda-settings">
						<?php projectimer_show_help(); ?>
					</div>

					<div role="tabpanel" class="tab-pane" id="tab-help">
						<?php projectimer_show_help(); ?>
					</div>
				</div>

			</div>
	      </div>
	      <!--div class="modal-body">
	        <?php //do_action( 'projectimer_display_settings_modal' ); ?>
	      </div-->
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <!--button type="button" class="btn btn-primary">Save changes</button-->
	      </div>
	    </div>
	  </div>
	</div>

	

	
	
	<!--p id="setting_message" style="background-color:#093;color:#FFF;font-weight:600;padding:3px 6px;">
		<?php 
		/*
		+++VOLUME
		+++HIDE EVERTHING AND SHOW TIMER
		if($focus_time==25) {
			_e("You are using Pomodoro Technique oficial time", "plugin-projectimer");
		} else {
			_e("You are using custom settings.", "plugin-projectimer");
		}*/ ?>
	</p-->
	<!--div id="projectimer_settingsbox">
		<span class="button_close"><a href="#">X</a></span>
	</div-->
	<!--script type="text/javascript">
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

function projectimer_display_team_settings() {
	?>
	<div class="modal fade" tabindex="-1" role="dialog" id="team_settings_modal">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Team Settings: <?php bloginfo('blogname'); ?></h4>
	      </div>
	      <div class="modal-body">
	        <p><?php 
				//switch_to_blog(1);
				//echo get_blog_option(get_current_blog_id(), "teamType");
	        	if(function_exists("teamTypeSelect"))
					teamTypeSelect(get_blog_option(get_current_blog_id(), "teamType"));
				else
					echo " please active f5sites-woocommerce-wpmu-subscriptio-check plugin";
				echo "<hr />";
				listSubscriptios(get_blog_option(get_current_blog_id(), "subscription_id"));
				echo "<hr />";
				echo "Timezone: GMT -3";
				echo " | Privacidade: Fechado";
				//echo get_blog_option(get_current_blog_id(), "subscription_name");
				//echo $curSubs = get_blog_option(get_current_blog_id(), "subscription_id");
				//echo "<hr />AAAA";
				//echo get_post_meta($curSubs, "blog_id", true);
				//restore_current_blog();
				/*if(function_exists("listSubscriptios")) {
				listSubscriptios();
	        }*/ ?></p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<?php
}

// MODAL inners parts 
function projectimer_display_projectimer_settings_form() { ?>
	<h3><?php _e("Team Subscription", "plugin-projectimer"); ?></h3>
	<p>Current subscription: Focalizador - Time Aberto</p>
	<p>Change the current subscription</p>
	<select>
		<option>Time Aberto</option>
		<option>Team 2</option>
	</select>

	<?php  #echo "<p>'".bp_profile_field_data( 'field=User Bio' )."'</p>"; ?>
	<?php
	$focus_time = get_user_meta(get_current_user_id(), "focus_time", true);
	if(!$focus_time) $focus_time=25;
	$rest_time = round($focus_time/5);
	$cycle_time = $focus_time + $rest_time;
	?>
	<h3><?php _e("Clock", "plugin-projectimer"); ?></h3>
	<p><?php _e("You can adjust your focus time and the rest time will be proportional", "plugin-projectimer"); ?></p>
	<div id="countdown_box" class="setting_panel">
	<table class="table table-striped">
		<thead>
			<tr>
				<td><?php _e("Adjust", "projectimer-plugin"); ?></td>
				<td><?php _e("Focus", "projectimer-plugin"); ?></td>
				<td><?php _e("Rest", "projectimer-plugin"); ?></td>
				<td><?php _e("Total", "projectimer-plugin"); ?></td>
			</tr> 
		</thead>
		<tbody>
			<tr> 
				<td>
					<button id="down_minutes" onclick="change_minutes(-1)">-</button>
					<button id="up_minutes" onclick="change_minutes(+1)">+</button>
				</td>
				<td>
					<button id="" disabled="disabled"><span id="focus_time_minutes"><?php echo $focus_time; ?></span>:00</button>
				</td>
				<td>
					<button id="" disabled="disabled"><span id="rest_time_minutes"><?php echo $rest_time; ?></span>:00</button>
				</td>
				<td>
					<button id="" disabled="disabled"><span id="cycle_time_minutes"><?php echo $cycle_time; ?></span>:00</button>
				</td>
			</tr>
		</tbody>
	</table>
	<p><?php _e("Time direction", "projectimer-plugin"); ?>:<?php _e("Counterclockwise only for now", "projectimer-plugin"); ?></p>
	</div>
	<!--h3>Version:</h3>
	<p>For old users and project history porpouses we kept the old versions:</p!-->
	
	<?php #echo do_shortcode("[mdc_theme_swicher]"); ?>
	<h3><?php _e("Language", "projectimer-plugin"); ?></h3>
	<p>Language</p>
	<h3><?php _e("Download Timesheet (PRO)", "projectimer-plugin"); ?></h3>
	<p><?php _e("Download a .csv detailed timesheet compatible with LibreOffice Calc and Microsoft Excel (select a template)", "projectimer-plugin"); ?></p>
	<button id="download_csv" onclick="download_csv()" class="btn btn-primary"><span class="glyphicon glyphicon-download" aria-hidden="true"></span> <?php _e("DOWNLOAD TIMESHEET", "projectimer-plugin"); ?></button>
	<?php //echo do_shortcode("[wpcsv_export_form]"); ?>
	<h3><?php _e("Erase site data (PRO)", "projectimer-plugin"); ?></h3>
	<p><?php _e("Simple and fast way to delete all time and reset site", "projectimer-plugin"); ?></p>
	<button id="erase_site_data" onclick="erase_site_data()" class="btn btn-danger"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span> <?php _e("ERASE SITE DATA", "projectimer-plugin"); ?></button>
	<?php //_e("Direction", "plugin-projectimer"); Clockwise Counterclockwise ?>
<?php }

function projectimer_display_teams() {
	if(is_user_logged_in()) { ?>
		<?php
		$blogs = get_blogs_of_user(get_current_user_id());#updated 4.7 get_blogs_of_user #get_sites

		if ( !empty($blogs) ) { ?>
			<h3><span class="glyphicon glyphicon-th" aria-hidden="true"></span><?php _e( 'My Teams' ) ?></h3>
			<?php
				if(count($blogs)<=1) {
					#_e('You are member of no teams, try join one', 'projectimer-root');
					?>
					<h3>Joining Teams</h3>
					<p>To enter in a private team you must have the link or get an invite by email.</p>
					<!--p>You can ask to join in <a href="/teams/" alt="See public teams">public teams</a-->
					<h3>Creating Teams</h3>
					<!--p>Every user can have one free public Team and we also offer a Team PRO plan for private teams. Go on and <a href="/wp-signup.php">create you own team</a>.</p-->
					<p>Please contact our team</p>
				<?php } else { ?>
					<ul>
						<?php 
						//
						foreach ( $blogs as $blog ) {
							//var_dump($blog);
							$home_url = get_home_url( $blog->userblog_id );
							//
							if($blog->userblog_id>1) {
								echo '<li><a href="' . esc_url( $home_url ) . '">' . $blog->blogname . '</a></li>';
							}
						} ?>
					</ul>
					<?php #_e('You are member of:', 'projectimer-root');
				}
			?>
		<?php } ?>
		<!--hr /-->
		
	<?php } else { ?>
		<?php wp_login_form(); ?>
		<div style="margin-top:-10px;">
			<?php do_action( 'bp_after_sidebar_login_form' ); ?>
		</div>
	<?php }
}

function projectimer_display_trello_options() { 
	?>
	<div class="col-md-4" style="padding-top:20px;font-weight:600;text-align: right;">autotag</div>
		<div class="col-md-8" style="padding-top:14px">
			<div class="btn-group btn-group-justified" data-toggle="buttons">
				<!--form id="trello-title-tags"></form-->
					<label class="btn btn-default active">
						<input name="auto_tag_trello" value="boards" type="checkbox" checked id="check-boards"> Boards
					</label>
					<label class="btn btn-default active">
						<input name="auto_tag_trello" value="lists" type="checkbox"  checked id="check-lists"> Lists
					</label>
					<label class="btn btn-default active">
						<input name="auto_tag_trello" value="labels" type="checkbox"  checked id="check-labels"> Labels
					</label>
			</div>
		</div>
	<?php
}

function projectimer_show_help() { ?>
	<h1>Help</h1>
	<p>We are implementing a FAQ section and a billing support system.</p>
	<h3>Thanks to/Credits</h3>
	<p>Open Source Community, Internet people, Collaborative and Shared Knowledge accrrooss World!</p>
	<h4>Sponsors</h4>
	<p>Many thanks to Sponsors F5 Sites for hosting and Francisco Mat for development, f5source, cursoWP.</p>
	<h4>Technology</h4>
	<p>Our services and products run open based on open source</p>
	<p>Server</p>
	<p>Linux, Ubuntu, CentOS RHEL7, Apache, MySQL, MariaDB, WordPress, Firefox</p>
	<h4>Manufactures</h4>
	<p>Samsung</p>
	<h4>Languages</h4>
	<p>PHP, HTML, CSS, JavaSript, jQuery (jQuery.colors, jQuery.offline, jquery-tmpl)</p>
	<p>Composer libraries, Google Calendar API</p>
	<p>Javascripts Libraries (select2, json.js)</p>
	<h4>WordPress and Buddypress</h4>
	<p>Plugins: Buddypress, Bp Multi Activi</p> 
	Invite Anyone,
	<p>CEO and Lead Developer: Francisco Matelli Matulovic</p>
<?php }


// THEME ELEMENTS (app (projectimer-theme, sistema-pomodoros...))
function projectimer_show_header_navbar() { ?>
	<nav class="navbar navbar-inverse">
	  <div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="<?php bloginfo('url'); ?>">
	      	<?php bloginfo("name"); ?>
		  </a>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	    <?php if ( is_user_logged_in() ) { ?> 
	      <ul class="nav navbar-nav">
	        <li class="NOactive">
	        	<a title="<?php _e("Focus", "projectimer-theme"); ?>" href="<?php bloginfo('url'); ?>/focus/" alt="Focalizador">
					<span id="icone-foc">&nbsp;</span>
					<?php _e("Focus", "projectimer-theme"); ?>
				</a>
	        </li>
	        <li>
	        	<a href="<?php echo get_bloginfo('url'); ?>/calendar"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Calendar</a>
	        </li>
	        <li>
	        	<a href="<?php echo get_bloginfo('url'); ?>/ranking"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Ranking</a>
	        </li>
	        <li>
	        	<a href="<?php echo get_bloginfo('url'); ?>/tv"><span class="glyphicon glyphicon-expand" aria-hidden="true"></span> TV</a>
	        </li>
	        <!--li class="dropdown">IT ACTUALLY WORKS VERY WELL FOR NEW IMPLEMENTATIONS
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
	          <ul class="dropdown-menu">
	            <li><a href="#">Action</a></li>
	            <li><a href="#">Another action</a></li>
	            <li><a href="#">Something else here</a></li>
	            <li role="separator" class="divider"></li>
	            <li><a href="#">Separated link</a></li>
	            <li role="separator" class="divider"></li>
	            <li><a href="#">One more separated link</a></li>
	          </ul>
	        </li-->
	      </ul>
	      <?php } ?>
	      <!--form class="navbar-form navbar-left">
	        <div class="form-group">
	          <input type="text" class="form-control" placeholder="Search">
	        </div>
	        <button type="submit" class="btn btn-default">Submit</button>
	      </form-->

	      <ul class="nav navbar-nav navbar-right" id="account-menu">
	      
	        <!--li><a href="/teams" alt="Team's Ranking"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Ranking</a></li-->
	        <?php if(current_user_can('administrator')) { ?> 
	        	<li><a href="#" alt="Team's Settings" id="button_team_settings"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Team Settings</a></li>
	        <?php } ?>
	        <?php if ( is_user_logged_in() ) { ?> 
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
	          		<?php 
	          		global $current_user;
	          		$user_id = $current_user->ID;
	          		if(function_exists('bp_activity_avatar'))
	          		bp_activity_avatar( 'user_id=' . $user_id, 30 ); 
	          		//get_avatar($user_id, 30);
	          		?> 
	          		<?php  wp_get_current_user(); echo $current_user->user_firstname;  ?> <span class="caret"></span></a>

	          <ul class="dropdown-menu">
	            <li>
	           		<a href="#tab-settings" title="<?php _e("Settings", "projectimer-plugin"); ?>" class="open_settings_modal" data-toggle="modal" data-target="#projectimer_settingsbox_modal"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>  <?php _e("Settings", "projectimer-plugin"); ?> </a></li>
	            <li>
	            	<a href="#tab-teams" class="open_settings_modal" data-toggle="modal" data-target="#projectimer_settingsbox_modal"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php _e("Teams", "projectimer-plugin"); ?></a></li>
	            <li>
	            	<a href="#tab-notifications" class="open_settings_modal" data-toggle="modal" data-target="#projectimer_settingsbox_modal"><span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span> <?php _e("Notifications", "projectimer-plugin"); ?></a></li>
	            <li>
	            	<a href="#tab-help" class="open_settings_modal" data-toggle="modal" data-target="#projectimer_settingsbox_modal"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> <?php _e("Help", "projectimer-plugin"); ?></a></li>
	            <li role="separator" class="divider"></li>
	            <li>
	            	<a title="<?php _e("Logout", "projectimer-plugin"); ?>" href="<?php echo wp_logout_url(); ?>" ><span class="glyphicon glyphicon-off" aria-hidden="true"></span> <?php _e("Logout", "projectimer-plugin"); ?></a>
	            </li>
	          </ul>
	        </li>
	        <?php } else { ?> 
	        	<li>
	            	
	            	<a title="<?php _e("Create an account", "projectimer-plugin"); ?>" href="/register" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> <?php _e("Sign-up", "projectimer-plugin"); ?></a>
	            <li>
	            <li>
	            	<a title="<?php _e("Login", "projectimer-plugin"); ?>" id="button_login" class="open_settings_modal" data-toggle="modal" data-target="#login_modal" tabindex="1" role="button" href="#"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php _e("Login", "projectimer-plugin"); ?></a>
	            <li>
	        <?php } ?>
	      </ul>
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>
	<?php
}


// THEME ELEMENTS (projectimer-main)
function projectimer_main_show_header_buttons() { ?>
	<script type="text/javascript">
	jQuery().ready(function() {
		jQuery("#settings_button").click(function(){
			jQuery("#projectimer_settingsbox").toggle("slow");
		});
		jQuery( ".button_close" ).click(function() {
			jQuery( this ).parent().hide("slow");
		});
		jQuery( "#button_login" ).click(function() {
			//jQuery( "#loginlogbox" ).toggle("slow");
			jQuery('#login_modal').modal('show');
		});
	});
	</script>
	<div id="header-right-buttons">
		<?php do_action('icl_language_selector'); ?>
		<?php if ( !is_user_logged_in() ) { ?> 
			<a title="<?php _e("Create an account", "projectimer-plugin"); ?>" href="/register" class="btn btn-default" role="button"><!--span class="glyphicon glyphicon-plus" aria-hidden="true"--><?php _e("Sign-up", "projectimer-plugin"); ?></a>
			
			<a title="<?php _e("Login", "projectimer-plugin"); ?>" id="button_login" tabindex="1" class="btn btn-default" role="button" href="#"><!--span class="glyphicon glyphicon-log-in" aria-hidden="true"--><?php _e("Login", "projectimer-plugin"); ?></a>
		<?php } else { ?> 
			<a title="<?php _e("Logout", "projectimer-plugin"); ?>" href="<?php echo wp_logout_url(); ?>" class="btn btn-default" role="button"><!--span class="glyphicon glyphicon-log-out" aria-hidden="true"--><?php _e("Logout", "projectimer-plugin"); ?></a>
			
			<button id="button_login"  class="btn btn-default"><?php global $current_user; wp_get_current_user(); //echo $current_user->user_firstname;  ?><!--span class="glyphicon glyphicon-th" aria-hidden="true"--><?php _e("Teams", "projectimer-plugin"); ?></button>
			
		<?php } 
		//TODO: TOUR IDEIA - VOCE SABIA QUE O POMODOROS.COM.BR É FEITO COM POMODOROS.COM.BR - Aqui todos os colaboradores e fornecedores utilizam o sistema. Nós fazemos o pomodoros usando o pomodoros. Perguntamos para Francisco Matelli, programador do sistema, como era usar a ferramenta. "Do ponto de vista técnico é muito interessante, levando em conta que é uma aplicaćão na nuvem, enquanto estamos programando melhorias para a nova versão, usamos a versão antiga. Depois que a versão na nuvem é atualizada, basta atualizar o navegador e comećamos a trabalhar com a última versão do sistema. O grande segredo, e também grande dificuldade, é fazer essa transićão ser imperceptível para o usuário, não se pode perder nenhuma informaćão durante essas atualizaćões. Por isso que temos sempre duas versões do sistema rolando. Temos até uma terceira versão, porém não posso falar sobre esse projeto nesse momento."
		//<!--a title="Como usar o site Pomodoros.com.br!" href="#"><button>Tour</button></a-->
		?>
	</div>
<?php }

function projectimer_main_show_header_popups() { ?>
	<div id="loginlogbox">
		<span class="button_close"><a href="#">X</a></span>
		<?php
		if(is_user_logged_in()) { 
		
		do_action('projectimer_display_teams');

		?>
	<?php } else { ?>
		<?php wp_login_form(); ?>
		<div style="margin-top:-10px;">
			<?php do_action( 'bp_after_sidebar_login_form' ); ?>
		</div>
	<?php } ?>
	</div>
	<?php 
}

/* UNDER DEVELOPMENT (currently in tests or not stable) */
function projectimer_show_clock_pro() {
	?>
	<div id="clock-container">
		<button onclick="action_button_press()" id="action_button" tabindex="1" disabled="disabled" /></button>
	
		<div id="clock-label"><?php echo get_user_meta(get_current_user_id(),"focus_time", true); ?>:00</div>
	
		<div id="div_status" onclick="action_button_press()"><span id="div_status_label" class="label label-default">loading...</span></div>
	
		<div id="update_status" onclick="communication_press()">
			<span class="label label-default">
				<span id="update_status_icon" class="glyphicon glyphicon-cloud-download download-alt" aria-hidden="true"></span>
			</span>
		</div>
		
		<div id="session_current_cycle">
			<span class="label label-default">0</span>
		</div>
		
		<div id="session_info">
			<!--span id="cyclesn">0</span> 
			<span id="restsn">0</span--> 
			<span class="label label-default" id="focus_timetotaln_color">
				<span class="glyphicon glyphicon-time" id="focus_timetotaln_icon"></span><span id="focus_timetotaln">00:00</span>
			</span>
			<span class="label label-default" id="restlost_timetotaln_color">
				<span class="glyphicon glyphicon-time" id="restlost_timetotaln_icon"></span><span id="restlost_timetotaln">00:00</span>
			</span>
		</div>
	
		<div id="community_info" onclick="action_button_press()">
			<span class="label label-default">
				<span id="community_timetotaln"><span class="glyphicon glyphicon-globe"></span>12:50</span>
			</span>
			<br />
			<span class="label label-default">
				<span id="daily_ranking"><span class="glyphicon glyphicon-object-align-right" style="transform:rotate 180 deg;"></span>5/8</span>
			</span>
		</div>
	
		<span id="session_button_container" class="btn"><span id="ponto_button" onclick="ponto_button_press()" class="label label-default"><span class="glyphicon glyphicon-play"></span></span></span>
	</div>
	<br style="clear:both" >
	<?php
	// glyphicons: refresh, info-sign, cloud-download, transfer, floppy-disk, cloud, asterisk
}

function projectimer_show_clock_futuristic() {
	//TODO: passar width e height por parametro
	echo 
	'<div id="shadow"></div>
	<canvas width="50%" height="50%" id="myCanvas"></canvas>
	<div id="shine"></div>';
	#echo '<input type="button" value="focus" onclick="action_button()" id="action_button_id" /><div id="div_status" style="background:#FFF;">Teste</div>';
	wp_enqueue_script("canvas-draw");
}