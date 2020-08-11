//------User table---

/*function usertable(){
    table = $('#usersData').DataTable({ 

        //"dom": 'lBrtip',
        //"buttons": ['copy','csv','excel','pdf','print'],
        "processing": true, //Feature control the processing indicator.
        "serverSide": true,
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('Admin/getUsers')?>",
            "type": "POST",
            "data": function ( d ) {
                d.extra_search = $('#extra').val();
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
            "searchable": false,
        },
        { 
            "targets": [ 1 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        { 
            "targets": [ 4 ], //first column / numbering column
            "orderable": false, //set not orderable
            "searchable": false,
        },
        { 
            "targets": [ 5 ], //first column / numbering column
            "orderable": false, //set not orderable
            "searchable": false,
        },
        { 
            "targets": [ 6 ], //first column / numbering column
            "orderable": false, //set not orderable
            "searchable": false,
        },
        ],
    });
}*/
