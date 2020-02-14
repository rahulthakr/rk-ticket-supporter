 
<div class="your-tickets">
   <?php $this->load_view_admin('customer/view/notifications.php'); ?>
   <h1 class="h3 mb-3 font-weight-normal">Your Tickets </h1>
   <div class="ticket-system-content text-left">
      <div class="row mb-3">
         <div class="col-sm-12">
            <div class="row">
               <div class="col-6">
                  <label for="customerTicketsearch_status">Search with status</label>                    
                  <select class="form-control" id="customerTicketsearch_status">
                     <option value="">All</option>
                     <option value="0">Open</option>
                     <option value="1">Close</option>
                  </select>
               </div>
               <div class="col-6">				  <a href="<?= site_url() ?>/customer-ticket/?node_first=create" class="btn btn-primaryw submit-ticket-btn">Submit Ticket </a>  &nbsp;				 </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-12">
            <table id="customerTicketList" class='ticket-users ' cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th>ID</th>
                     <th>Subject</th>
                     <th>Status</th>
                     <th>Action</th>
                  </tr>
               </thead>
            </table>
         </div>
      </div>
      <div class="row mt-3">
         <a href="<?= site_url() ?>/customer-ticket/?node_first=history" class="btn btn-primary submit-ticket-btn">Ticket History</a>      
      </div>
   </div>
</div>
 