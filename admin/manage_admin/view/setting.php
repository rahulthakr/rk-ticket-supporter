 
<div class="wrap">

    <div class="container">
        <h2>Settings</h2>   
    	<div class="row mt-3">
            
            <div class="col-md-12">                
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#email_server" role="tab" aria-controls="email_server" aria-selected="false">E-Mail Server</a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" id="cron-schedules-tab" data-toggle="tab" href="#cron-schedules" role="tab" aria-controls="cron-schedules" aria-selected="false">Cron Schedules</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                         <div class="your-tickets-reply"> 
                            <div class="text-center">
                                <form class="form-ticketsubmit" enctype="multipart/form-data" method="post">
                                    <h1 class="h3 mb-3 font-weight-normal">General Settings </h1>
                                      <p>General settings</p>
                                      <?php $info_general = "";  
                                          if(!empty($general_setting)){
                                              $info_general = json_decode($general_setting->information);
                                          }

                                      ?>
                                      <div class="ticket-system-content text-left">                             
                                          <div class="form-group">
                                              <label for="category">Google Captcha Key</label>
                                              <input type="text" class="form-control" name="g_captcha_code" required="" value="<?php if(isset($info_general->g_captcha_code)) {echo $info_general->g_captcha_code;} ?>">
                                          </div>
                                      </div>
                                      <input type="hidden" name="settings_admin" >
                                      <input type="hidden" name="service" value="general">
                                      <button class="btn btn-primary" type="submit" >Save</button> &nbsp;
                                      <a href="<?= admin_url('admin.php?page=ts_ticket') ?>" class="btn btn-primary">BACK</a>
                                </form>
                            </div>
                         </div>
                    </div>
                    <div class="tab-pane fade" id="email_server" role="tabpanel" aria-labelledby="email_server-tab">
                        <div class="your-tickets-reply"> 
                            <div class="text-center">
                                <form class="form-ticketsubmit" enctype="multipart/form-data" method="post">
                                    <h1 class="h3 mb-3 font-weight-normal">Email server Details </h1>
                                      <p>Email server settings</p>
                                      <?php $info = "";  
                                          if(!empty($setting)){
                                              $info = json_decode($setting->information);
                                          }

                                      ?>
                                      <div class="ticket-system-content text-left">     
                                          <div class="form-group">
                                              <h3>Incoming Server Details:</h3>
                                          </div>
                                          <div class="form-group">
                                              <label for="category">IMAP Hostname</label>
                                              <input type="text" class="form-control" name="host_imap" required="" value="<?php if(isset($info->host_imap)) {echo $info->host_imap;} ?>">
                                          </div>
                                          <div class="form-group">
                                              <label for="category">Port</label>
                                              <input type="text" class="form-control" name="port" required="" value="<?php if(isset($info->port)) {echo $info->port;} ?>">
                                          </div>
                                          
                                          <div class="form-group">
                                              <h3>Outgoing Server Details:</h3>
                                          </div>
                                          <div class="form-group">
                                              <label for="category">SMTP Hostname</label>
                                              <input type="text" class="form-control" name="hostname" required="" value="<?php if(isset($info->hostname)) {echo $info->hostname;} ?>">
                                          </div>
                                          
                                          <div class="form-group">
                                              <label for="category">Port</label>
                                              <input type="text" class="form-control" name="smtp_port" required="" value="<?php if(isset($info->smtp_port)) {echo $info->smtp_port;} ?>">
                                          </div>
                                          
                                          <div class="form-group">
                                              <h3>Common Server Details:</h3>
                                          </div>
                                          <div class="form-group">
                                              <label for="category">Username</label>
                                              <input type="text" class="form-control" name="username" required="" value="<?php if(isset($info->username)) {echo $info->username;} ?>">
                                          </div>
                                          <div class="form-group">
                                              <label for="category">Password</label>
                                              <input type="text" class="form-control" name="password" required="" value="<?php if(isset($info->password)) {echo $info->password;} ?>">
                                          </div>
                                          <div class="form-group">
                                              <label for="category">Security </label>
                                              <select class="form-control" name="security" required="">
                                                  <option value="ssl" <?php if(isset($info->security) && $info->security =="ssl") {echo "selected";} ?>>SSL</option>
                                                  <option value="tls" <?php if(isset($info->security) && $info->security =="tls") {echo "selected";} ?>>TLS</option>
                                              </select>
                                          </div>
                                          <div class="form-group">
                                              <label for="category">From Name</label>
                                              <input type="text" class="form-control" name="from_name" required="" value="<?php if(isset($info->from_name)) {echo $info->from_name;} ?>">
                                          </div>
                                          <div class="form-group">
                                              <label for="category">From Email</label>
                                              <input type="text" class="form-control" name="from_email" required="" value="<?php if(isset($info->from_email)) {echo $info->from_email;} ?>">
                                          </div>

                                          <div class="form-group">
                                              <label for="category">Reply Name</label>
                                              <input type="text" class="form-control" name="reply_name" required="" value="<?php if(isset($info->reply_name)) {echo $info->reply_name;} ?>">
                                          </div>
                                          <div class="form-group">
                                              <label for="category">Reply Email</label>
                                              <input type="text" class="form-control" name="reply_email" required="" value="<?php if(isset($info->reply_email)) {echo $info->reply_email;} ?>">
                                          </div>
                                          <div class="form-group">
                                              <label for="category">Email Type</label>

                                              <select class="form-control" name="type_mail" required="">
                                                  <option value="smtp" <?php if(isset($info->type_mail) && $info->type_mail =="smtp") {echo "selected";} ?>>SMTP</option>
                                                  <option value="mail" <?php if(isset($info->type_mail) && $info->type_mail =="mail") {echo "selected";} ?>>MAIL</option>
                                              </select>
                                          </div>

                                      </div>
                                      <input type="hidden" name="settings_admin" >
                                      <input type="hidden" name="service" value="email">
                                      <button class="btn btn-primary" type="submit" >Save</button> &nbsp;
                                      <a href="<?= admin_url('admin.php?page=ts_ticket') ?>" class="btn btn-primary">BACK</a>
                                </form>
                            </div>
                          </div>
                    </div>
                       <div class="tab-pane fade" id="cron-schedules" role="tabpanel" aria-labelledby="cron-schedules-tab">
                        <div class="your-tickets-reply"> 
                            <div class="text-center">
                                <form class="form-ticketsubmit" enctype="multipart/form-data" method="post">
                                    <h1 class="h3 mb-3 font-weight-normal">How many  x days if the customer hasn't responded.then close ticket</h1>
                                      <p>Ticket Close settings</p>
                                   <?php  
                                
                                  // print_r($setting_ticket_close);
                                   if(!empty($setting_ticket_close)){
                                              $info_setting_ticket_close = $setting_ticket_close ;
                                          }

                                      ?>
                                      <div class="ticket-system-content text-left">     
                                          <div class="form-group">
                                              <h3>Details:</h3>
                                          </div>
                                          
                                          <div class="form-group">
                                              <label for="category">How many days after ticket closed</label>
                                              <input type="number"  min="1" class="form-control" name="ticket_close" required="" value="<?php if(isset($info_setting_ticket_close->information)) {echo $info_setting_ticket_close->information;} ?>">
                                          </div>
                                          
                                            
 
                                          

                                      </div>
                                      <input type="hidden" name="settings_ticket_close" >
                                      <input type="hidden" name="service" value="ticket_close">
                                      <button class="btn btn-primary" type="submit" >Save</button> &nbsp;
                                      <a href="<?= admin_url('admin.php?page=ts_ticket') ?>" class="btn btn-primary">BACK</a>
                                </form>
                            </div>
                          </div>
                    </div>
                </div>
                
                
                
            	
            </div>
        </div>
    </div>
</div>
 