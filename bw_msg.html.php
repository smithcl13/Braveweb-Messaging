<?php

require_once 'bw.lib.inc';
require_once 'menu.class.php';

if (! empty($message)) {
    echo "<h3><font color=\"red\">$message</font></h3>";
    exit();
}
?>

<style>
.navbar-brand {
    padding:0px;
 }

body {font-size:16px};
</style>

<h5 title="Upcoming, Current, and Past Messages">Message History</h5>
<div id="message_info"></div>
<div id="message_table">
	<table id="messageData" class="table table-striped table-bordered table-hover table-condensed table-hover">
		<thead>
			<tr>
				<th>Actions</th>
				<th>Note Code</th>
				<th>Start Date</th>
				<th>End Date</th>
				<th>Message Title</th>
			</tr>
		</thead>
		<tbody id="tblData">
			<tr></tr>
		</tbody>
	</table>
</div>

<!-- Message Info Modal -->
<div class="modal hide fade" id="messageInfoModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Message Information</h4>
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div id="rowID1" class="modal-body">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Note Code</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Active</th>
							<th>Activity Date</th>
							<th>Message Key</th>
							<th>Period Key</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td id="noteCode"></td>
							<td id="startDate"></td>
							<td id="endDate"></td>
							<td id="active"></td>
							<td id="activityDate"></td>
							<td id="messageKey"></td>
							<td id="periodKey"></td>
						</tr>
					</tbody>
				</table>
				<h5>
					<b>Message text</b>
				</h5>
				<p style="font-size: 16px" id="messageText"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Response Modal -->
<div class="modal hide fade" id="responseModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Response List</h4>
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div id="rowID2" class="modal-body">
				<table id='responseData' class="table table-bordered">
					<thead>
						<tr>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Response</th>
						</tr>
					</thead>
					<tbody id="responseTblData"></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

