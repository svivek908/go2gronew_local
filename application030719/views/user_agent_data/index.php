<!DOCTYPE html>
<html>
<head>
	<title>Go2gro</title>
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
	<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
</head>
<body>
	<div class="container">
		<table id="example" class="display" style="width:100%">
	        <thead>
	            <tr>
	            	<th>Sno.</th>
	            	<th>Date/Time</th>
	                <th>Username</th>
	                <th>Pincode</th>
	                <th>Country</th>
	                <th>IP Address</th>
	                <th>City</th>
	                <th>Region</th>
	                <th>Latitude</th>
	                <th>Longitude</th>
	                <th>continentName</th>
	                <th>Browser</th>
	                <!-- <th>user_agent_info</th> -->
	            </tr>
	        </thead>
	        <tfoot>
	            <tr>
	            	<th>Sno.</th>
	            	<th>Date/Time</th>
	                <th>Username</th>
	                <th>Pincode</th>
	                <th>Country</th>
	                <th>IP Address</th>
	                <th>City</th>
	                <th>Region</th>
	                <th>Latitude</th>
	                <th>Longitude</th>
	                <th>continentName</th>
	                <th>Browser</th>
	                <!-- <th>user_agent_info</th> -->
	            </tr>
	        </tfoot>
	    </table>
	</div>
	
    <script type="text/javascript">
    	$(document).ready(function() {
		    table = $('#example').DataTable({ 

		        "dom": 'lBrtip',
		        "buttons": ['copy','csv','excel','pdf','print'],
		        "processing": true, //Feature control the processing indicator.
		        "serverSide": true,
		        "order": [], //Initial no order.

		        // Load data for the table's content from an Ajax source
		        "ajax": {
		            "url": "<?php echo site_url('User_agent_details/get_data/')?>",
		            "type": "POST"
		        },
		        //Set column definition initialisation properties.
		        "columnDefs": [
		        { 
		            "targets": [ 0 ], //first column / numbering column
		            "orderable": false, //set not orderable
		            "searchable": false,
		        }
		        ],
		    });
		});
    </script>
</body>
</html>