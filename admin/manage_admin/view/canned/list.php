 
 
<!-- Bootstrap core CSS -->

<div class="wrap">
    <div class="container" >
        <h2>Canned List</h2>   
        
        <div class="row mb-3">
            <div class="col-sm-12">
                <div class="col-sm-6">
                    <a href="<?= admin_url('admin.php?page=ts_ticket_canned_new')?>" class="btn btn-primary">Add new</a>
                </div>
            </div>
        </div>
        
                
        <div class="row">
            <div class="col-sm-12">
                <table id="cannedList" class='ticket-users' cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Action</th>
                    
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function confirmdel(id){
    var x =confirm("Are you sure you want to delete?");
    if(x == true){
        window.location.href = "<?= admin_url('admin.php?page=ts_ticket_canned_delete&id=')?>"+id;
    }
    
}
 
</script>
<script type="text/javascript" src="<?=PRD_PLUGIN_URL_ADMIN?>js/bootstrap.min.js"></script>

