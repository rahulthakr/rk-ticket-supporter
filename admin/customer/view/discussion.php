 <div class="container">
 
    	<div class="row">
       <div class="col-lg-6 message-view task-chat-view task-right-sidebar" id="task_window">	 <div class="row">  		 	  <div class="col-lg-4">         <a href="<?= site_url() ?>/customer-ticket" class="btn btn-primary mb-3">BACK</a>              </div>		  <h1 class="h3 mb-3 col-lg-6 font-weight-normal text-left">Reply  </h1>          </div>        <div class="chat-window">          <div class="fixed-header">            <div class="navbar">                            </div>          </div>          <div class="chat-contents task-chat-contents">            <div class="chat-content-wrap">              <div class="chat-wrap-inner">                <div class="chat-box">                  <div class="chats">                                                              <?php if($discussion){     									  									       foreach($discussion as $chat): 		 					$chatPostion='left';								$username='';									if($chat->from_id == $call->user_id()) {						$chatPostion='right';								$username="You";								}else{										$chatPostion='left';												$username=  $this->get_user_field($chat->from_id).'('.$this->get_user_role($chat->from_id).')';						}  										   ?>                                                           <div class="chat chat-<?=$chatPostion?>">                      <div class="chat-avatar">                                             <a href="#" class="avatar">                                             <img alt="" src="<?php echo esc_url( get_avatar_url( $call->user_id() ) ); ?>">                                             </a>                                          </div>                      <div class="chat-body">                        <div class="chat-bubble">                          <div class="chat-content">                            <?php if($chat->private_ticket): ?>			 												                            <h4 class="text-danger">Private Note</h4>                            <?php endif; ?>                                                   <span class="task-chat-user"><?=$username;?></span> <span class="chat-time"><?=date('M d Y h:i A', strtotime($chat->created_at))?></span>                                                   <?php  if($chat->comment!=""){   ?>													                               <p><?=$chat->comment?> </p>                            <?php }												   if($chat->attachment!=""){   ?>													                              <ul class="attach-list">                              <li class="pdf-file"><i class="fa fa-file-pdf-o"></i> <?php $call->get_upload_dir_path(); ?> <a href="<?=$call->get_upload_dir_path().$chat->attachment?>" download="">Download  </a>													  				                               </li>                                                                                </ul>                            <?php }  ?>                                                                                                                             </div>                        </div>                      </div>                    </div>                    <?php endforeach; ?>        					<?php } ?>                                     					                  </div>                </div>              </div>            </div>          </div>          <div class="chat-footer">            <form class="form-chat-message" name='form-chat-message' enctype="multipart/form-data" method="post">              <div class="message-bar">                <div class="message-inner">				  <?php if($ticket_record->status == 0 || $ticket_record->auto_close_status == 1): ?>                  <input type="file" name="myfile" id="uploadchatImage" style='display:none'/>     <i class="showonhover fa fa-paperclip"></i>                                                    <div class="message-area">                    <div class="input-group">     					<textarea id="description" name="comment" class="form-control" placeholder="Type message..."></textarea>						<span class="input-group-append">						<input type="hidden" name="ticket_create_authorized_customer" >						<input type="hidden" name="ticket" value="<?= $ticket_record->id ?>">						<?php if($ticket_record->auto_close_status == 1): ?>							<input type="hidden" name="auto_close_status" value="0">					   <?php endif; ?>						<button class="btn btn-primary" type="submit"  id='form-chat-buttonId'  >						<i class="fa fa-send"></i>						</button>  						       						</span>       						</div>                  </div>				  <?php endif; ?>                </div>            </form>            </div>                                                           </div>        </div>      </div>
            <div class="col-md-6">
            	<div class="your-tickets-reply">      
                  <h1 class="h3 mb-3 font-weight-normal text-right">Ticket <?=$ticket_record->id?></h1>
                  <div class="ticket-system-content text-left">
                    <table class="ticket-users reply-tickets">                       
                        <tbody>
                            <tr>
                                <td>Start Date</td>
                                <td><?=date('M d Y h:i A', strtotime($ticket_record->created_at))?></td>
                            </tr>
                            <tr>
                                <td>Subject</td>
                                <td><?=$ticket_record->subject?></td>
                            </tr>
                            <tr>
                                <td>Category</td>
                                <td><?=$ticket_record->category?></td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td><?=$ticket_record->description?></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td class="ticket-open">
                                    <?php if($ticket_record->status) {echo "Closed";}else{echo "Open";} ?>
                                </td>                                
                            </tr>
                            <tr>
                                <td>Priority</td>
                                <td class="ticket-open">
                                   <?=$call->get_ticket_priority($ticket_record->priority)?>
                                </td>                                
                            </tr>
                            <tr>
                                <td>Request Assigned To:</td>
                                <td>
                                    <?=  $this->get_user_field($ticket_record->staff_id) ?>
                                </td>                                
                            </tr>
                            <tr>
                                <td>Attachment</td>
                                <td>
                                    <?php if($ticket_record->attachment!=""): ?>
                                        <a href="<?=$call->get_upload_dir_path().$ticket_record->attachment?>" download="">Download</a>
                                    <?php else: ?>
                                        No attachment.
                                    <?php endif; ?>
                                </td>
                            </tr>
                            
                        </tbody>                        
                    </table>          
                  </div> 
                  
                  <?php if($ticket_record->status == 0): ?>
                  <h1 class="h3 mb-3 font-weight-normal text-right">MY PROBLEM IS SOLVED</h1>
                  <form class="form-ticketreply" enctype="multipart/form-data" method="post">
                    <div class="text-right">
                        <input type="hidden" name="ticket_close_authorized_customer" >
                        <input type="hidden" name="ticket" value="<?= $ticket_record->id ?>">
                        <button class="btn btn-primary close-ticket" type="submit">CLOSE TICKET</button>
                    </div>     
                  </form>
                  <?php endif; ?>
                  
                </div>
            </div>
        </div>
</div><script>			jQuery(document).ready(function(e) {    			jQuery(".showonhover").click(function(){						jQuery("#uploadchatImage").trigger('click');				});   		});		</script>			