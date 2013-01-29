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
}


function ta_remove(comp_id){
	$('#confirm_modal').attr("name", comp_id);
	$('#confirm_modal .modal-header #label').text("Remove " + comp_id + "?");
	$('#confirm_modal .modal-body').html('<p>Are you sure you want to remove <strong>' + comp_id + '</strong> from the queue?</p>');
	$('#confirm_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button class="btn btn-danger" onclick="ta_remove_confirm()">Yes</button>');
	$('#confirm_modal').modal('show');
}

function change_location_confirm(){
	$('#input_modal').modal('hide');
	$.ajax({
		type: "GET",
	 	url: 'change_loc.php',
	 	data: {loc: $('#new_location').val() },
	 	success: function(data) {
			$('#top-alert').html(data);
			$('#top-alert').fadeIn('slow');
			$("#student_info").load("index.php #student_info");
	  }
	});
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
			$("#student_info").load("index.php #student_info");
	  }
	});
	
}

function ta_clear_confirm(){
	$('#confirm_modal').modal('hide');
	$.ajax({
		type: "GET",
		url: 'ta_clear.php',
		success: function(data){
			$('#top-alert').html(data);
			$('#top-alert').fadeIn('slow');
			$('#student_table').load('index.php #student_table');
		}
	});
	
}

function student_remove_confirm(){
	$('#confirm_modal').modal('hide');
	$.ajax({
		type: "GET",
	 	url: 'dequeue.php',
	 	success: function(data) {
			$('#top-alert').html(data);
			$('#top-alert').fadeIn('slow');
			$('#student_info').load('index.php #student_info');
	  }
	});
	
}

function ta_remove_confirm(){
	$('#confirm_modal').modal('hide');
	$.ajax({
		type: "GET",
	 	url: 'ta_remove.php',
	 	data: {id: $('#confirm_modal').attr("name")},
	 	success: function(data) {
			$('#top-alert').html(data);
			$('#top-alert').fadeIn('slow');
			$('#student_table').load('index.php #student_table');
		}
	});
	
}

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
			$("#student_table").load("index.php #student_table");
		}
	});
	
}


var infoInterval = '';
$(document).ready(function(){
	setInterval(function(){
		$('#student_table').load('index.php #student_table');
		$.ajax({
			type: "GET",
		 	url: 'queue_count.php',
		 	success: function(data) {
			 	
				document.title = "Office Hours " + data; 
			}
		});
	},15000);
	infoInterval = setInterval(function(){
		$('#student_info').load('index.php #student_info');
	},60000);
});


function close_alert(){
	$('#top-alert').fadeOut('slow');
}



