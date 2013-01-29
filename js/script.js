//student join function
$(document).ready(function() { 
	$('#join_btn').click(function(e){
		e.preventDefault();
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

//change location function
$(document).ready(function(){
	$("#change_loc").click(function(e){
		e.preventDefault();
		$('#input_modal .modal-header #label').text("Change Location");
		$('#input_modal .modal-body').html('<input class="input-xlarge" id="new_location" type="text" placeholder="New Location...">');
		$('#input_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button class="btn btn-success change_btn">Change</button>');
		$('#input_modal').modal('show');
		
		$(".change_btn").click(function(e){
			e.preventDefault();
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
		});
	});
});

//change help function
$(document).ready(function(){
	$("#change_help").click(function(e){
		e.preventDefault();
		$('#input_modal .modal-header #label').text("Change Help");
		$('#input_modal .modal-body').html('<input class="input-xlarge" id="new_help" type="text" placeholder="New Help...">');
		$('#input_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button class="btn btn-success change_btn" >Change</button>');
		$('#input_modal').modal('show');

		$(".change_btn").click(function(e){
			e.preventDefault();
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
		});
	});
});

//ta clear function
$(document).ready(function(){
	$("#ta_clear").click(function(e){
		e.preventDefault();
		$('#confirm_modal .modal-header #label').text("Clear Queue?");
		$('#confirm_modal .modal-body').html("<p>Are you sure you want to clear the queue?</p>");
		$('#confirm_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button class="btn btn-danger" id="ta_clear_confirm">Yes</button>');
		$('#confirm_modal').modal('show');
		
		$("#ta_clear_confirm").click(function(e){
			e.preventDefault();
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
		});
	});
});

//student remove function
$(document).ready(function(){
	$("#student_remove").click(function(e){
		e.preventDefault();
		$('#confirm_modal .modal-header #label').text("Are You Sure?");
		$('#confirm_modal .modal-body').html('<p>Are you sure you want to leave the queue?</p>');
		$('#confirm_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button class="btn btn-danger" id="student_remove_confirm">Yes</button>');
		$('#confirm_modal').modal('show');
		
		$("#student_remove_confirm").click(function(e){
			e.preventDefault();
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
		});	
	});
});

//ta remove function
$(document).ready(function(){
	$(".ta_remove").click(function(e){
		e.preventDefault();
		var comp_id = $(this).attr("name");
		$('#confirm_modal').attr("name", comp_id);
		$('#confirm_modal .modal-header #label').text("Remove " + comp_id + "?");
		$('#confirm_modal .modal-body').html('<p>Are you sure you want to remove <strong>' + comp_id + '</strong> from the queue?</p>');
		$('#confirm_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button class="btn btn-danger" id="ta_remove_confirm">Yes</button>');
		$('#confirm_modal').modal('show');
		
		$("#ta_remove_confirm").click(function(e){
			e.preventDefault();
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
		});
	});
});



//toggle function
$(document).ready(function(){
	$("#toggle_btn").click(function(e){
		e.preventDefault();
		$('#confirm_modal .modal-header #label').text("Toggle Queue");
		$('#confirm_modal .modal-body').html("<p>Are you sure you want to toggle the queue?</p>");
		$('#confirm_modal .modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button><button class="btn btn-danger" id="toggle_confirm">Yes</button>');
		$('#confirm_modal').modal('show');
		
		$("#toggle_confirm").click(function(e){
			e.preventDefault();
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
		});
	});
});



//refresh hack
var infoInterval = '';
$(document).ready(function(){
	setInterval(function(){
		$('#student_table').load('index.php #student_table');
		$.ajax({
			type: "GET",
		 	url: 'queue_count.php',
		 	success: function(data) {
				document.title = data + " Office Hours"; 
			}
		});
	},15000);
	infoInterval = setInterval(function(){
		$('#student_info').load('index.php #student_info');
	},60000);
});

//close alert function
$(document).ready(function(){
	$("#close_alert").on("click", function(e){
		e.preventDefault();
		$('#top-alert').fadeOut('slow');
	});
});



