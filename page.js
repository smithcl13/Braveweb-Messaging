if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
  var msViewportStyle = document.createElement('style')
  msViewportStyle.appendChild(
    document.createTextNode(
      '@-ms-viewport{width:auto!important}'
    )
  )
  document.querySelector('head').appendChild(msViewportStyle)
}

var allFields, tips;

$( document ).ready(function() {
	$("#message_table").on( "click", ".message_info",  function() {
		getRecord($(this).data('id'));
	});
	
	$("#message_table").on( "click", ".response",  function() {
		getResponse($(this).data('id'));
	});
	
	var form,
	    noteCode = $( "#note_code" ), startDate = $( "#start_date" ), endDate = $( "#end_date" ), active = $('#active'), 
	    messageText = $( "#message_text" ), activityDate = $( "#activity_date" ), periodKey = $('#period_key');
	
	allFields = $( [] ).add(noteCode).add(startDate).add(endDate).add(active).add(messageText).add(activityDate).add(periodKey);
	tips = $( ".validateTips" );

	loadTable(null);
});

//get all records and load history table
function loadTable(msgKey=null) {
	$('#tblData').find('tr:gt(0)').remove();
	var parameters = 'action=get_all_records';
	var row;
	
	$.ajax({
	    type: "POST",
		url: "actions.php",
		data: parameters,
		success: function(results) {
			if (results.success) {
				if (results.records.length > 0) {
					for (var i=0; i<results.records.length; i++) {
						row += '<tr id="row_' + results.records[i].message_key + '">'
							+ '<td><a href="#" onclick="endMsgPeriod(' + results.records[i].period_key 
							+ ')"><span class="glyphicon glyphicon-stop" title="End message"></span></a>'
							+ '&nbsp;&nbsp;&nbsp;<a href="#messageInfoModal" data-toggle="modal" class="message_info" data-id="row_' + results.records[i].period_key
							+ '"><span class="glyphicon glyphicon-list-alt" title="Mesage Info"></span></a>'
							+ '&nbsp;&nbsp;&nbsp;<a href="#responseModal" data-toggle="modal" class="response" data-id="row_' + results.records[i].period_key
							+ '"><span class="glyphicon glyphicon-list" title="Response List"></span></a></td>'
							+ '<td id="td_note_code_' + results.records[i].period_key + '">' + results.records[i].note_code + '</td>'
							+ '<td id="td_start_date_' + results.records[i].period_key + '">' + results.records[i].start_date + '</td>'
							+ '<td id="td_end_date_' + results.records[i].period_key + '">' + results.records[i].end_date + '</td>'
							+ '<td id="td_message_title' + results.records[i].period_key + '">' + results.records[i].message_title + '</td>'
							+ '</tr>';
					}
					$('#tblData').html(row);
					$('#messageData').DataTable();
					
					if (msgKey !== null) {
					    $('#row_' + msgKey).addClass('success');
					    window.setTimeout(function() {$('#row_' + msgKey).removeClass('success') }, 3000);
					}
				} else {
				    $('#tblData tr:last').after('<tr><td colspan=8>There were no records found</td></tr>');
				}
			} else {
			    $("#user_message").html('<div class="alert alert-danger" role="alert">' + results.message + '</div>');
			    updateTips('error');
			}
		},
		error: function() {
			updateTips('Internal error.'); //TODO: Create error message
		}
	});
}

function getRecord (periodKey) {
	var period_key = periodKey.substr(4);
	var parameters = 'period_key=' + period_key + '&action=get_record';
	var row;
	
	//ajax call to get the record for msgKey
	$.ajax({
		type: "POST",
		    url: "actions.php",
		    data: parameters,
		    success: function(results){
		    if (results.success) {
		    	$('#noteCode').html(results.record[0].note_code);
				$('#startDate').html(results.record[0].start_date);
				$('#endDate').html(results.record[0].end_date);
				$('#active').html(results.record[0].active);
				$('#activityDate').html(results.record[0].activity_date);
				$('#messageKey').html(results.record[0].message_key);
				$('#periodKey').html(results.record[0].period_key);
				$('#messageTitle').html(results.record[0].message_title);
				$('#messageText').html(results.record[0].message_text);
				
		    } else {
			   	$("#rowID1").html('<div class="alert alert-danger" role="alert">' + results.message + '</div>');
		    }
		},
		    error: function() {
		    updateTips('Internal error.');//todo: Create error message
		}
    });
}

function getResponse (periodKey) {
	var row = '';
	var period_key = periodKey.substr(4);
//	period_key= 1;
	var parameters = 'period_key=' + period_key + '&action=response';
	
	//ajax call to get the response record
	$.ajax({
		type: "POST",
		    url: "actions.php",
		    data: parameters,
		    success: function(results){
		    	if (results.success) {
		    		for (var i=0; i<results.records.length; i++) {
			    		row += '<tr id="row_' + results.records[i].period_key + '">'
			    			+ '<td>' + results.records[i].first_name + '</td>'
			    			+ '<td>' + results.records[i].last_name + '</td>'
			    			+ '<td>' + results.records[i].response + '</td>'
			    			+ '</tr>';
		    		}
		    		$('#responseTblData').html(row);
					$('#responseData').DataTable();
			
		    	} else {
				   	$("#rowID2").html('<div class="alert alert-danger" role="alert">' + results.message + '</div>');
			    }
			},
			error: function() {
			updateTips('Internal error.');//todo: Create error message
			    }
	});
}

function endMsgPeriod(period_key) {
	var parameters = 'period_key=' + period_key + '&action=end';
		if(confirm('Are you sure you want to stop the message for period ' +period_key)){
		// ajax call to get the record for msgKey
		$.ajax({
			type: "POST",
			url: "actions.php",
			data: parameters,
			success: function(results){
				if (results.success) {
					alert(results.message);
				} 
			},
			error: function() {
			}
		})
		}
}

function getPermissions(){
	//var parameters = 'pidm=' + pidm + '&action=permissions';
	var parameters = '&action=permissions';
	$.ajax({
		type: "POST",
		url: "actions.php",
		data: parameters,
		success: function(results){
			if (results.success) {
				alert(results.message);
			} 
		},
		error: function() {
		}
	})
}