<?php
	$user_id = $_SERVER['PHP_AUTH_USER']; //get netbadge 
	//$user_id = 'hwc2d';

	//set up database connection
	require_once("dbconnect.php");
	$db = DbUtil::loginConnection();
	$stmt = $db -> stmt_init();


	//get the user's name from user_id
	if($stmt -> prepare('SELECT fname, lname, role FROM roster WHERE comp_id = ?') or die(mysqli_error($db))) {
		$stmt -> bind_param("s", $user_id);
		$stmt -> execute();
		$stmt -> bind_result($user_fname, $user_lname, $user_role);
		$stmt -> fetch();
	}
	
	if(empty($user_role)){
		echo "<script>location.href='error.php'</script>";
	}
	
	//get the user's location in the queue
	$position = 1;
	if($stmt -> prepare("SELECT comp_id, location, help FROM active_queue NATURAL JOIN roster ORDER BY enter_ts") or die(mysqli_error($db))) {
		$stmt -> execute();
		$stmt -> bind_result($comp_id, $location, $help);
		while($stmt -> fetch()){
			if($comp_id === $user_id){
				break;
			}else{
				$position++;
			}
		}	
	}
	
	function check_queue(){
		$fd = fopen('queuestatus.txt', 'r');
		$status = fgets($fd);
		fclose($fd);
		return $status;
	}	

	
?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Office Hours</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Office Hours">
		<meta name="author" content="HunterC">
		
		
		<!-- stylesheets -->
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/bootstrap-responsive.css" rel="stylesheet"> <!-- responsive bootstrap-->
		<link href="css/style.css" rel="stylesheet">
		
		<!-- js -->	
		<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>-->
		<script src="js/jquery-1.8.3.js"></script>
		<script src="js/bootstrap.min.js"></script>
		
	
		<script>
			
			//submit sutdent to queue
			$(document).ready(function() { 
				$('#join_btn').click(function(){
					$.ajax({
						type: "GET",
					 	url: 'enqueue.php',
					 	data: $('#join_queue').serialize(),
					 	success: function(data) {
							$('#your_location').html(data);
							$('#join_queue').replaceWith($('#your_location'));
							$('#your_location').fadeIn('fast');
					  	}
					});
					return false;
				});
			});
		
			function change_location(){
				$('#input_modal .modal-header #label').text("Change Location");
				$('#input_modal .modal-body').html('<input class="input-xlarge" id="new_location" type="text" placeholder="New Location...">');
				$('#input_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button id="foot_label" class="btn btn-success" onclick=change_location_confirm() >Change</button>');
				$('#input_modal').modal('show');
				
			}
			
			function change_help(){
				$('#input_modal .modal-header #label').text("Change Help");
				$('#input_modal .modal-body').html('<input class="input-xlarge" id="new_help" type="text" placeholder="New Help...">');
				$('#input_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button id="foot_label" class="btn btn-success" onclick=change_help_confirm() >Change</button>');
				$('#input_modal').modal('show');
			}
			
			function ta_clear(){
				$('#confirm_modal .modal-header #label').text("Clear Queue?");
				$('#confirm_modal .modal-body').html("<p>Are you sure you want to clear the queue?</p>");
				$('#confirm_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button class="btn btn-danger" onclick="ta_clear_confirm()">Yes</button>');
				$('#confirm_modal').modal('show');
				
			}
			
			function student_remove(){
				$('#confirm_modal .modal-header #label').text("Are You Sure?");
				$('#confirm_modal .modal-body').html('<p>Are you sure you want to leave the queue?</p>');
				$('#confirm_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button class="btn btn-danger" onclick="student_remove_confirm()">Yes</button>');
				$('#confirm_modal').modal('show');
			};
			
			
			function ta_remove(comp_id){
				$('#confirm_modal').attr("name", comp_id);
				$('#confirm_modal .modal-header #label').text("Remove " + comp_id + "?");
				$('#confirm_modal .modal-body').html('<p>Are you sure you want to remove <strong>' + comp_id + '</strong> from the queue?</p>');
				$('#confirm_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button class="btn btn-danger" onclick="ta_remove_confirm()">Yes</button>');
				$('#confirm_modal').modal('show');
			};
			
			function change_location_confirm(){
				$('#input_modal').modal('hide');
				$.ajax({
					type: "GET",
				 	url: 'change_loc.php',
				 	data: {loc: $('#new_location').val() },
				 	success: function(data) {
						$('#top-alert').html(data);
						$('#top-alert').fadeIn('slow');
				  }
				});
				$("#student_info").load("index.php #student_info");
			}
			
			function change_help_confirm(){
				$('#input_modal').modal('hide');
				$.ajax({
					type: "GET",
				 	url: 'change_help.php',
				 	data: {help: $('#new_help').val() },
				 	success: function(data) {
						$('#top-alert').html(data);
						$('#top-alert').fadeIn('slow');
				  }
				});
				$("#student_info").load("index.php #student_info");
			}
			
			function ta_clear_confirm(){
				$('#confirm_modal').modal('hide');
				$.ajax({
					type: "GET",
					url: 'ta_clear.php',
					success: function(data){
						$('#top-alert').html(data);
						$('#top-alert').fadeIn('slow');
					}
				});
				$('#student_table').load('index.php #student_table');
			}
			
			function student_remove_confirm(){
				$('#confirm_modal').modal('hide');
				$.ajax({
					type: "GET",
				 	url: 'dequeue.php',
				 	success: function(data) {
						$('#top-alert').html(data);
						$('#top-alert').fadeIn('slow');
				  }
				});
				$('#student_info').load('index.php #student_info');
			};
			
			function ta_remove_confirm(){
				$('#confirm_modal').modal('hide');
				$.ajax({
					type: "GET",
				 	url: 'ta_remove.php',
				 	data: {id: $('#confirm_modal').attr("name")},
				 	success: function(data) {
						$('#top-alert').html(data);
						$('#top-alert').fadeIn('slow');
					}
				});
				$('#student_table').load('index.php #student_table');
			};
			
			function toggle(){
				$('#confirm_modal .modal-header #label').text("Toggle Queue");
				$('#confirm_modal .modal-body').html("<p>Are you sure you want to toggle the queue?</p>");
				$('#confirm_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button class="btn btn-danger" onclick="toggle_confirm()">Yes</button>');
				$('#confirm_modal').modal('show');
			}
			
			function toggle_confirm(){
				$('#confirm_modal').modal('hide');
				$.ajax({
					url: 'toggle_queue.php',
					success: function(response) {
						if(response == "on"){
							$('#toggle_btn').text('Turn OFF Queue');
						}else if(response == "off"){
							$('#toggle_btn').text('Turn ON Queue');
						}
					}
				});
				$("#student_table").load("index.php #student_table");
			};
			

			var infoInterval = '';
			$(document).ready(function(){
				setInterval(function(){
					$('#student_table').load('index.php #student_table');
				},15000);
				infoInterval = setInterval(function(){
					$('#student_info').load('index.php #student_info');
				},60000);
			});

			
			function close_alert(){
				$('#top-alert').fadeOut('slow');
			}
			
			
		
		</script>
	</head>
	
	<body>
		<!-- navbar -->
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a style="color: white" class="brand">Office Hours</a>
					<ul class="nav pull-right">
						<li>
							<button class="btn">
								<strong><?php echo $user_role . ' : ' . $user_fname .' '. $user_lname; ?></strong>
							</button>
						</li>
					</ul>
					<div class="nav-collapse collapse">
							
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>
		<!-- end navbar -->
		
		<div class="container">
			<div class="row-fluid">  
				
					
				<!-- alert field at top of container -->
				<div id="top-alert" class="alert alert-success" style="display:none;">
					
				</div>
				<!-- end alert field -->
				
				<!-- start admin section -->
				<header>
					
					<?php if($user_role == 'Instructor' || $user_role == 'TA') {
						echo "<h1>Hello, " . $user_fname . "!</h1>";
						
						if(check_queue() == "on"){
							$button_status = "Turn OFF Queue";
						}else if(check_queue() == "off"){
							$button_status = "Turn ON Queue";
						}
					?>
					
					
					<button class="btn btn-primary" id="toggle_btn" onclick=toggle()><?php echo $button_status ?></button>
					
					<button class='btn btn-danger' onclick=ta_clear() id='ta_clear'>Clear Queue</button>
					
					
				</header>
				
				<div id="student_table">
					<?php
						//set up and display the contents of the queue
						$table = '<table class="table table-striped">
									<thead>
										<th>Name</th>
										<th>Comp ID</th>
										<th>Location</th>
										<th>Help With</th>
										<th>Remove</th>
									</thead>
									<tbody>';
							
						//get users' information
						if($stmt -> prepare("SELECT comp_id, fname, lname, location, help FROM active_queue NATURAL JOIN roster ORDER BY enter_ts") or die(mysqli_error($db))) {
							$stmt -> execute();
							$stmt -> bind_result($comp_id, $fname, $lname, $location, $help);
							while($stmt -> fetch()){
								$table = $table.'<tr><td>'.$fname.' '.$lname.'</td><td>'.$comp_id.'</td><td>'.$location.'</td><td>'. $help .'</td>
								<td><button type="button" class="btn btn-danger" id="'.$comp_id.'" onclick=ta_remove("'.$comp_id.'")>×</button></td></tr>';
							}
							$table = $table.'</tbody></table>';
						}
					
						if($stmt -> prepare("SELECT COUNT(*) FROM active_queue") or die(mysqli_error($db))) {
							$stmt -> execute();
							$stmt -> bind_result($queue_size);
							$stmt -> fetch();
						}
					
						if($queue_size > 0){
							echo $table;
						}else{
							echo "<div id='empty_table' class='alert'>";
								echo "<strong>EMPTY!</strong> The queue is currently empty. YAY!";
							echo "</div>";
						}
					?>
				</div> <!-- end student table -->
				
				<?php }else { ?> <!-- start student section -->
					
					<header>
					<!-- fread-->
						<h1>Hello, <?php echo $user_fname; ?>!</h1>
					</header>
					<div id="student_info"> <!-- if student isn't already a member of the queue -->
						
					<?php if($comp_id != $user_id) { 
							if(check_queue() == "off"){		
					?>
								<p>Office Hours are no longer in session. If you were already in the queue we will do our best to help you.</p>
								<p>See the calendar below to see when the next Office Hours session is.</p>
								<iframe src="https://www.google.com/calendar/embed?src=uvacs1110%40gmail.com&ctz=America/New_York" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
								<script>clearInterval(infoInterval);</script>
					<?php
							}else{
					?>	
						
							<!-- join queue form -->
							<form class="form-horizontal span-12" id="join_queue" name="join_queue">											
								<fieldset>
								<legend>Join Queue</legend>
									<div class="control-group">
										<label class="control-label" for="loc">Your Location:</label>
										<div class="controls">
									  		<input type="text" name="loc" placeholder="Location...">
										</div>
								  </div>
								 	<div class="control-group">
										<label class="control-label" for="help">Need help with:</label>
										<div class="controls">
								  		<input type="text" name="help" placeholder="Activity 4..." style="width:200px; height:40px;">
									  </div>
								  </div>
									<div class="control-group">
										<div class="controls">
											<button id="join_btn" type="submit" class="btn btn-success">Join</button>
										</div>
									</div>
								</legend>
							</form>
							
					<?php 
							}
						} else {
					?>		<!-- if student is already in queue -->
						
						<table>
							<tr>
								<td>Your spot in the queue: <strong><?php echo $position ?></strong></td><td><button type="button" class="btn btn-danger" onclick=student_remove()>Leave Queue</button></td>
							</tr>
							<tr>
								<td>You are at location: <strong><?php echo $location ?></strong></td><td><button type="button" class="btn btn-success" id="change_loc" onclick=change_location() >Change Location</button></td>	
							</tr>
							<tr>
								<td>You need help with: <strong><?php echo $help ?></strong></td><td><button type="button" class="btn btn-success" id="change_help" onclick=change_help() >Change Help</button></td>	
							</tr>
						</table>

					 <?php } ?> <!-- end student in/out queue logic -->
					</div>	
							
				<?php } ?> <!-- end of admin/student section -->
				
				<div id="your_location" style="display:none">
					
				</div>
			
				
			</div> <!-- end row div -->
		</div> <!-- end container div -->
		
		<!-- extra divs for modals and such -->
		
		<!-- modal yes/no -->
		<div id="confirm_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="label">Are you sure?</h3>
			</div>
		  	<div class="modal-body">
				<p></p>
		  	</div>
		  	<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
				<button class="btn btn-danger" onclick="remove_student_confirm()">Yes</button>
		  	</div>
		</div>
		<!-- end modal yes/no -->
		
		<!-- modal input -->
		<div id="input_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="label">Change</h3>
			</div>
		  	<div class="modal-body">
				<input class="small" id="smallInput" name="smallInput" type="text">		
		 	</div>
		  	<div class="modal-footer">
				
		  	</div>
		</div>
		<!-- end modal input -->
	</body>	
		
</html>