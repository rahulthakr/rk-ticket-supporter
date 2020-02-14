 
<div class="wrap">

    <div class="container">
        <h2>Merge Ticket</h2>   
    	<div class="row mt-3">
            
            <div class="col-md-12">
            	<div class="your-tickets-reply">      
                  
                  
                  <div class="text-center">
                      <form class="form-ticketsubmit" enctype="multipart/form-data" method="post">
                          <h1 class="h3 mb-3 font-weight-normal">Merge Ticket </h1>
                            <p>Merge the ticket with other ticket</p>
                            
                            <div class="ticket-system-content text-left">                             
                                <div class="form-group">
                                    <label for="category">Please select customer*</label>
                                    <select class="form-control" id="users" name="user" >
                                        <option value="">--Choose--</option>
                                        <?php if(!empty($users_list)){ ?>
                                        <?php foreach($users_list as $user): ?>
                                            <option value="<?=$user->ID?>"><?=$user->display_name?></option>
                                        <?php endforeach; ?>
                                        <?php } ?>
                                    </select>
                                </div> 
                                <div class="form-group">
                                    <label for="category">Ticket*</label>
                                    <select class="form-control tickets" id="from_ticket" name="from_ticket" >
                                        <option value="">--Choose--</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="category">Merge To*</label>
                                    <select class="form-control tickets" id="to_ticket" name="to_ticket" >
                                        <option value="">--Choose--</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="ticket_merge_action" >
                            <button class="btn btn-primary" type="submit" >Merge</button> &nbsp;
                            <a href="<?= admin_url('admin.php?page=ts_ticket') ?>" class="btn btn-primary">BACK</a>
                      </form>
                  </div>
                </div>
            </div>
        </div>
    </div>

</div>
  