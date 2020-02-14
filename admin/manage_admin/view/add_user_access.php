<!-- Bootstrap CSS -->        
<link rel="stylesheet" href="<?=PRD_PLUGIN_URL_ADMIN?>assets/css/bootstrap.min.css">
<!-- Fontawesome CSS -->        
<link rel="stylesheet" href="<?=PRD_PLUGIN_URL_ADMIN?>assets/css/font-awesome.min.css">
<!-- Lineawesome CSS -->        
<link rel="stylesheet" href="<?=PRD_PLUGIN_URL_ADMIN?>assets/css/line-awesome.min.css">
<!-- Datatable CSS -->		
<link rel="stylesheet" href="<?=PRD_PLUGIN_URL_ADMIN?>assets/css/dataTables.bootstrap4.min.css">
<!-- Select2 CSS -->		
<link rel="stylesheet" href="<?=PRD_PLUGIN_URL_ADMIN?>assets/css/select2.min.css">
<!-- Datetimepicker CSS -->		
<link rel="stylesheet" href="<?=PRD_PLUGIN_URL_ADMIN?>assets/css/bootstrap-datetimepicker.min.css">
<!-- Summernote CSS -->		
<link rel="stylesheet" href="<?=PRD_PLUGIN_URL_ADMIN?>assets/css/summernote-bs4.css">
<!-- Main CSS -->        
<link rel="stylesheet" href="<?=PRD_PLUGIN_URL_ADMIN?>assets/css/style.css">
<link href="<?=PRD_PLUGIN_URL_ADMIN?>assets/css/jquery.multiselect.css" rel="stylesheet" />
<!-- Bootstrap core CSS -->	<!-- Page Wrapper -->            
<div class="page-wrapper" style="min-height: 608px;">
   <!-- Page Content -->                
   <div class="content container-fluid">
      <!-- Page Header -->					
      <div class="page-header">
         <div class="row align-items-center">
            <div class="col">
               <h3 class="page-title">  Tickets Delete  Access List    </h3>
               <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#index.html">Dashboard</a></li>
                  <li class="breadcrumb-item active">Tickets Delete  Access List</li>
               </ul>
            </div>
         </div>
      </div>
      <!-- /Page Header -->					<!-- Search Filter -->					
      <div class="row filter-row">
         <div class="col-sm-6 col-md-3">
            <div class="form-group form-focus">							   <input type="text" id="search_email" class="form-control floating" placeholder="Search with email"> 								<label class="focus-label">Search with email</label>							</div>
         </div>
         <div class="col-sm-6 col-md-3">
            <div class="form-group form-focus">								<input type="text" id="search_last_name" class="form-control floating" placeholder="Search with last name">								<label class="focus-label">Search with last name</label>							</div>
         </div>
         <!--div class="col-sm-6 col-md-2">  							<a   class="btn btn-success btn-block"> Search </a>  						</div-->                         
      </div>
      <!-- /Search Filter -->						
      <div class="row">
         <div class="col-md-12">
            <div class="table-responsive2">
               <div class="row">
                  <div class="col-sm-12">
                     <table id="reportListAccess" class='table table-striped custom-table datatable dataTable no-footer ticket-users' cellspacing="0" width="100%">
                        <thead>
                           <tr>
                              <th>ID</th>
                              <th> Name</th>
                              <th>Status</th>
                           </tr>
                        </thead>
                        <tbody>				
	  					<?php
                                foreach ($users_list as $UserAccessDetail) {
                                    $user_status1 = '';
                                    $user_status  = '';
                                    $select       = '';
                                    $select .= "<form method='post' action='' class='ts_short_form'>";
                                    $select .= "<select name='assign_delete_ticket_user' class='form-control ts_form_submit'>";
                                    $select .= "<option value=''>--Choose--</option>";
                                    if ($UserAccessDetail->status == "0" || $UserAccessDetail->status == "") {
                                        $user_status = "selected";
                                    } else {
                                        $user_status1 = "selected";
                                    }
                                    $select .= "<option value='1' " . $user_status1 . " >Enable</option>";
                                    $select .= "<option value='0'  " . $user_status . " >Disable</option>";
                                    $select .= "</select>";
                                    $select .= "<input type='hidden' name='store_manager_id' value='" . $UserAccessDetail->id . "'>";
                                    $select .= "</form>";
                                    echo "  <tr>                            <td>ID</td>                            <td>" . $UserAccessDetail->display_name . "</td>                              <td>" . $select . " </td>                          </tr>";
                                }
                                ?>        
                          </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- jQuery -->       