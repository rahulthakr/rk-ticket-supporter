<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<link rel="stylesheet" type="text/css" href="<?= PRD_PLUGIN_URL  ?>/admin/datatable/jquery.dataTables.css">
<script type="text/javascript" src="<?= PRD_PLUGIN_URL  ?>/admin/datatable/jquery-2.1.3.min.js"></script>
<script type="text/javascript" src="<?= PRD_PLUGIN_URL  ?>/admin/datatable/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?= PRD_PLUGIN_URL  ?>/admin/js/ts-admin.js"></script>

<div class="your-tickets">     
<?php $this->load_view_admin('customer/view/notifications.php'); ?>
<h1 class="h3 mb-3 font-weight-normal">Ticket History </h1>
    <div class="ticket-system-content text-left">
        
        <div class="row mb-3">
            <div class="col-sm-12">
                <div class="col-6">
                    <label for="search_status">Search with status</label>
                    <select class="form-control" id="search_status">
                        <option value="">All</option>
                        <option value="0">Open</option>
                        <option value="1">Close</option>
                    </select>
                </div>
                
            </div>
        </div>  
        
        <div class="row">
            <table id="reportList" class='ticket-users' cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Re-Open</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="row mt-3">
            <a href="<?= site_url() ?>/customer-ticket/" class="btn btn-primary submit-ticket-btn">Tickets</a>  &nbsp;
        </div>
       
    </div>
</div>


<script>
$(document).ready(function() {
   
        var table = $("#reportList").DataTable({ 
            "bDestroy": true,   
            "responsive": true,
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": '<?= site_url("wp-admin/admin-ajax.php") ?>',
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "action", "value": "ticketinfocustomerhistory" } );
                aoData.push( { "name": "search_status", "value": $("#search_status").val() } );
            },
            "pageLength": 50,
            "order": [[ 0, "desc" ],[ 2, "desc" ]],  
            "columnDefs": [
                { "orderable": false, "targets": [1,3,4] }
              ],
             "language": {
                "emptyTable":"Record not found",
                "search": '',
                "searchPlaceholder": 'Search..',  
            },
            "scrollX": true,
            "bFilter": false,
            "bInfo": false
        }); 
        
        $('#search_status').on('change', function(){
            table.draw();
        });
    
 });
</script>



