<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://planetwebzone.com
 * @since      1.0.0
 *
 * @package    TS
 * @subpackage TS/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    TS
 * @subpackage TS/admin
 * @author     Planetwebzone<info@planetwebzone.com>
 */
class TS_Admin_Manage_Ticket
{

    use TS_Common;
    use TS_Roles;

    private $role;
    private $userid;
    private $table_ticket = '';
    private $table_ticket_assign = '';
    private $ticket_delete_assigned = '';
    private $table_users = '';
    private $table_usermeta = '';
    private $table_comments = '';
    private $table_settings = '';

    public static function get_instance()
    {

        NULL === self::$instance and self::$instance = new self;
        return self::$instance;
    }
    public function __construct($user_id, $role)
    {
        $this->role = $role;
        $this->userid = $user_id;

        /*Ticket manage ajax*/
        add_action('wp_ajax_ticketmanage', array(&$this,
            'ticketmanage_show_list'
        ));
        add_action('wp_ajax_nopriv_ticketmanage', array(&$this,
            'ticketmanage_show_list'
        ));
        add_action('wp_ajax_ticketmanage_access', array(&$this,
            'ticketmanage_access_show_list'
        ));
        add_action('wp_ajax_nopriv_ticketmanage_access', array(&$this,
            'ticketmanage_access_show_list'
        ));

        /*Ticket History manage ajax*/
        add_action('wp_ajax_ticketmanagehistory', array(&$this,
            'ticketmanage_history_show_list'
        ));
        add_action('wp_ajax_nopriv_ticketmanagehistory', array(&$this,
            'ticketmanage_history_show_list'
        ));

        /*Ticket Merge - Tickets list*/
        add_action('wp_ajax_usersticket', array(&$this,
            'usersticket_tickets'
        ));
        add_action('wp_ajax_nopriv_usersticket', array(&$this,
            'usersticket_tickets'
        ));
        
        // add_action( 'wp_footer', 'myscript' );

        /*Table name defined*/
        global $wpdb;
        $this->table_users = $wpdb->prefix . "users";
        $this->table_usermeta = $wpdb->prefix . "usermeta";
        $this->table_ticket = $wpdb->prefix . "ts_tickets";
        $this->table_ticket_assign = $wpdb->prefix . "ts_ticket_assigned";
        $this->ticket_delete_assigned = $wpdb->prefix . "ts_ticket_delete_assigned";
        $this->table_comments = $wpdb->prefix . "ts_tickets_comments";
        $this->table_settings = $wpdb->prefix . "ts_settings";

    }

    function show()
    {

       /*  $this->load_view_admin('manage_admin/view/list2.php');  */        $this->load_view_admin('manage_admin/view/ticket-listing.php');
    }

    function ticketmanage_show_list()
    {

        $aColumns = array(
            "id",
            "last_name",
            "email",
            "subject",
            "category",
            "status",
            "",
            ""
        );
        $condition = array();

        if (sanitize_email($_POST['email_search']) != "")
        {  
            $sanitized_email = sanitize_email($_POST['email_search']);
            $condition[] = " email LIKE '%" . $sanitized_email . "%' ";
        }
        if (sanitize_text_field($_POST['last_name']) != "")
        {
            $condition[] = " last_name LIKE '%" . sanitize_text_field($_POST['last_name']) . "%' ";
        }
        if (!empty($condition))
        {
            $condition = implode(" AND ", $condition);
        }

        if (!empty($condition))
        {
            $info = $this->set_data_table_condition($aColumns, $condition);
        }
        else
        {
            $info = $this->set_data_table($aColumns);
        }

        $details = $this->get_lists_request($info['sLimit'], $onlyCount = false, $info['sOrder'], $info['sWhere']);
        $counts = $this->get_lists_request('', $onlyCount = true, 'status', $info['sWhere']);
        $output = array(
            "sEcho" => intval($_REQUEST['sEcho']) ,
            "iTotalRecords" => count($details) ,
            "iTotalDisplayRecords" => $counts,
            "aaData" => array()
        );

        /*Users list*/
        $usersAll = $this->get_users_list(['ticket_manager']);
        if (!empty($details)):
            foreach ($details as $k => $v)
            {
                $user_exist = '';
                $status = '<span class="btn btn-primarye"><i class="fa fa-dot-circle-o text-success"></i> Open</span>';
                if ($v['status'] == "1")
                {
                    $status = '<span class="btn btn-primarye ticket-close"><i class="fa fa-dot-circle-o text-danger"></i> Close</span>';
                }

                $select = '';        
                $user_exist_icon = '';
                if (!empty($usersAll))
                {
                    $select .= "<form method='post' action='' class='ts_short_form'>";
                    $select .= "<select name='assign_ticket_user' class='form-control ts_form_submit'>";                  $user_exist_icon = "<i class='fa fa-dot-circle-o text-danger'></i>";                      
                    $select .= "<option>--Choose--</option>";
                    foreach ($usersAll as $user)
                    {
                        $user_exist = '';                      
                        if ($this->is_ticket_assigned($user->ID, $v['id']))
                        {
                            $user_exist = "selected";                            
                        }                         
                        $select .= "<option value='" . $user->ID . "'  " . $user_exist . ">" .$user->display_name . "</option>";
                    }
                    $select .= "</select>";
                    $select .= "<input type='hidden' name='ticket_id' value='" . $v['id'] . "'>";
                    $select .= "</form>";
                }
                $button = '<a href="admin.php?page=ts_ticket_discussion&id=' . $v['id'] . '">Discussion</a>';

                $output['aaData'][] = array(
                    $v['id'],
                    $v['last_name'],
                    $v['email'],
                    $v['subject'],
                    $v['category'],
                    $status,
                    $select,
                    $button
                );
            }
        endif;
        echo json_encode($output);
        die();
    }
    function user_access_list_show()
    {

        $this->load_view_admin('manage_admin/view/user_access_list.php');

    }
     function myscript() {
    ?>
    <script type="text/javascript">
    alert('Hello World!');
      if ( undefined !== window.jQuery ) {
        // script dependent on jQuery
      }
    </script>
    <?php
    }
    function ticketmanage_access_show_list()
    {

        $aColumns = array(
            "id",
            "store_manager_id",
            "access_type",
            "status",
            ""
        );
        $condition = array();

        if (sanitize_email($_POST['email_search']) != "")
        {
            $condition[] = " email LIKE '%" . sanitize_email($_POST['email_search']) . "%' ";
        }
        if (sanitize_text_field($_POST['last_name']) != "")
        {
            $condition[] = " last_name LIKE '%" . sanitize_text_field($_POST['last_name']) . "%' ";
        }
        if (!empty($condition))
        {
            $condition = implode(" AND ", $condition);
        }

        if (!empty($condition))
        {
            $info = $this->set_data_table_condition($aColumns, $condition);
        }
        else
        {
            $info = $this->set_data_table($aColumns);
        }

        $details = $this->get_lists_delete_access_request($info['sLimit'], $onlyCount = false, $info['sOrder'], $info['sWhere']);
        $counts = $this->get_lists_delete_access_request('', $onlyCount = true, 'status', $info['sWhere']);
        $output = array(
            "sEcho" => intval($_REQUEST['sEcho']) ,
            "iTotalRecords" => count($details) ,
            "iTotalDisplayRecords" => $counts,
            "aaData" => array()
        );

        /*Users list*/
        $usersAll = $this->get_users_list(['ticket_manager']);
        if (!empty($details)):
            foreach ($details as $k => $v)
            {
                $user_exist = '';
                $status = '<span class="btn btn-primary">Enable</span>';
                if ($v['status'] == "1")
                {
                    $status = '<span class="btn btn-primary ticket-close">Disable</span>';
                }

                $select = '';
                if (!empty($usersAll))
                {
                    $select .= "<form method='post' action='' class='ts_short_form'>";
                    $select .= "<select name='assign_ticket_user' class='form-control ts_form_submit'>";
                    $select .= "<option value=''>--Choose--</option>";
                    foreach ($usersAll as $user)
                    {
                        $user_exist = '';
                        if ($this->is_ticket_delete_assigned($user->ID, $v['id']))
                        {
                            $user_exist = "selected";
                        }
                        $select .= "<option value='" . $user->ID . "'  " . $user_exist . ">" . $user->display_name . "</option>";
                    }
                    $select .= "</select>";
                    $select .= "<input type='hidden' name='ticket_id' value='" . $v['id'] . "'>";
                    $select .= "</form>";
                }
                $button = '<a href="admin.php?page=ts_ticket_discussion&id=' . $v['id'] . '">Discussion</a>';
                // $output['aaData'][] = array($v['id'], $v['store_manager_id'],$status ,$select,$button );
                
            }
        endif;
        $output['aaData'][] = array(
            'fff',
            'dfdf',
            'status',
            'select',
            'button'
        );
        // $this->notice(json_encode($output),'danger');
        echo json_encode($output);
        die();
    }
    function get_lists_request($sLimit, $onlyCount = false, $sOrder, $sWhere)
    {
        global $wpdb;
        $table = $this->table_ticket;
        if ($onlyCount == true)
        {
            $query = "SELECT *FROM $table  $sWhere  $sOrder ";
            $sql = $wpdb->get_results($query);
            return count($sql);
        }
        else
        {
            $query = "SELECT *FROM $table  $sWhere  $sOrder $sLimit";
            $get = $wpdb->get_results($query);
            if (count($get) > 0)
            {
                $i = 0;
                foreach ($get as $r)
                {
                    $data[$i]['id'] = $r->id;
                    $data[$i]['category'] = $r->category;
                    $data[$i]['last_name'] = $r->last_name;
                    $data[$i]['email'] = $r->email;
                    $data[$i]['subject'] = $r->subject;
                    $data[$i]['status'] = $r->status;
                    $i += 1;
                }
                return $data;
            }
        }
    }
    function get_lists_delete_access_request($sLimit, $onlyCount = false, $sOrder, $sWhere)
    {
        global $wpdb;
        $table = $this->ticket_delete_assigned;
        if ($onlyCount == true)
        {
            $query = "SELECT *FROM $table  $sWhere  $sOrder ";
            $sql = $wpdb->get_results($query);
            return count($sql);
        }
        else
        {
            $query = "SELECT *FROM $table  $sWhere  $sOrder $sLimit";
            $get = $wpdb->get_results($query);
            if (count($get) > 0)
            {
                $i = 0;
                foreach ($get as $r)
                {
                    $data[$i]['id'] = $r->id;
                    $data[$i]['store_manager_id'] = $r->store_manager_id;
                    $data[$i]['access_type'] = $r->access_type;
                    $data[$i]['status'] = $r->status;
                    $i += 1;
                }
                return $data;
            }
        }
    }
    function assign_ticket()
    {
        /*Insert to new record*/
        global $wpdb;
        /* Ticket assign */
        $ticket_assign = $wpdb->update($this->table_ticket, array(
            'staff_id' => $_POST['assign_ticket_user'],
        ) , array(
            'id' => $_POST['ticket_id']
        ));

        /*History of tickets assigned*/
        $status_update = $wpdb->update($this->table_ticket_assign, array(
            'status' => 0,
        ) , array(
            'ticket_id' => $_POST['ticket_id']
        ));

        $insert = $wpdb->insert($this->table_ticket_assign, array(
            'store_manager_id' => $_POST['assign_ticket_user'],
            'ticket_id' => $_POST['ticket_id']
        ));
        /*End History of tickets assigned*/

        if ($ticket_assign)
        {
            $to = $this->get_user_field($_POST['assign_ticket_user'], 'user_email');
            $this->notfication_ticket_action($to, "Ticket assigned you - #" . $_POST['ticket_id'], "<p>Ticket has been assigned you. Please check.</p>");
            $this->notice('Ticket has been assigned successfully!');
        }
        else
        {
            $this->notice('Please try again!');
        }

    }

    function is_ticket_assigned($store_manager_id = 0, $ticket = 0)
    {
        global $wpdb;
        return $wpdb->get_var("SELECT COUNT(*) FROM {$this->table_ticket} WHERE staff_id = {$store_manager_id} AND  id = {$ticket} ");
    }
    function is_ticket_delete_assigned($store_manager_id = 0)
    {
        global $wpdb;
        return $wpdb->get_var("SELECT COUNT(*) FROM {$this->ticket_delete_assigned} WHERE staff_id = {$store_manager_id}  ");
    }

    /*Ticket Discussion*/
    function user_id()
    {
        return $this->userid;
    }
    function discussion()
    {
        $id = 0;
        if (isset($_GET['id']))
        {
            $id = $_GET['id'];
        }
        $record = $this->get_record_by_id($this->table_ticket, array(
            'id' => $id
        ));
        if (empty($record))
        {
            $this->notice('Invalid ticket id.Please try again!', 'danger');
            @wp_redirect(admin_url('admin.php?page=ts_ticket'));
            return false;
        }

        $discussion = $this->get_records($this->table_comments, array(
            'ticket_id' => $id
        ));

        $content['ticket_record'] = $record;
        $content['user'] = $this->get_user_by_id($record->customer_id);
        $content['discussion'] = $discussion;
        $content['call'] = $this;

        $this->load_view_admin('manage_admin/view/discussion.php', $content);
    }
 function chat()
    {
	   $discussion = $this->get_records($this->table_comments, array(
            'ticket_id' => $id
        ));	
	}
    function ticket_reply()
    {

        if ($_POST['ticket'] == "")
        {
            $this->notice('Invalid request, Please try again!', 'error');
            return false;
        }
        if ($_POST['comment'] == "")
        {
            $this->notice('Reply field required', 'error');
            return false;
        }
        $ticket_belong_securiety_check = $this->get_record_exist($this->table_ticket, array(
            'id' => $_POST['ticket']
        ));
        if (!$ticket_belong_securiety_check)
        {
            $this->notice('Invalid request, Please try again!', 'error');
            return false;
        }
        $ticket_information = $this->get_record_by_id($this->table_ticket, array(
            'id' => $_POST['ticket']
        ));

        $data['ticket_id'] = $_POST['ticket'];
        $data['from_id'] = $this->userid;
        $data['to_id'] = $ticket_information->customer_id;
        $data['comment'] = $_POST['comment'];
        $data['attachment'] = $this->upload_file();
        $data['private_ticket'] = 0;
        if (isset($_POST['private_note']))
        {
            $data['private_ticket'] = 1;
        }

        $insert = $this->insert_record($this->table_comments, $data);
        if ($insert)
        {

            if ($data['private_ticket'])
            {
                $executive_to = $this->get_user_field($ticket_information->staff_id, 'user_email');
                $this->notfication_ticket_action($executive_to, "Ticket - Private note: #" . $_POST['ticket'], "<h3>Private Note.</h3><p>" . $_POST['comment'] . "</p>");
            }
            else
            {
                $to = $this->get_user_field($ticket_information->customer_id, 'user_email');
                $this->notfication_ticket_action($to, "Ticket new comment - #" . $_POST['ticket'], "<h3>New Message.</h3><p>" . $_POST['comment'] . "</p><p>You can track your ticket status on our dashboard pannel please <a href='" . site_url('wp-login.php') . "'>click here for login</a>  </p>");

            }
            $this->notice('Reply posted successfully!');
        }
        else
        {
            $this->notice('Please try again!', 'error');
        }
    }
	

    function ticket_reply_byajax()
    {

        if ($_POST['ticket'] == "")
        {
            $this->notice('Invalid request, Please try again!', 'error');
            return false;
        }
        if ($_POST['comment'] == "")
        {
            $this->notice('Reply field required', 'error');
            return false;
        }
        $ticket_belong_securiety_check = $this->get_record_exist($this->table_ticket, array(
            'id' => $_POST['ticket']
        ));
        if (!$ticket_belong_securiety_check)
        {
            $this->notice('Invalid request, Please try again!', 'error');
            return false;
        }
        $ticket_information = $this->get_record_by_id($this->table_ticket, array(
            'id' => $_POST['ticket']
        ));

        $data['ticket_id'] = $_POST['ticket'];
        $data['from_id'] = $this->userid;
        $data['to_id'] = $ticket_information->customer_id;
        $data['comment'] = $_POST['comment'];
        $data['attachment'] = $this->upload_file();
        $data['private_ticket'] = 0;
        if (isset($_POST['private_note']))
        {
            $data['private_ticket'] = 1;
        }

        $insert = $this->insert_record($this->table_comments, $data);
        if ($insert)
        {

            if ($data['private_ticket'])
            {
                $executive_to = $this->get_user_field($ticket_information->staff_id, 'user_email');
                $this->notfication_ticket_action($executive_to, "Ticket - Private note: #" . $_POST['ticket'], "<h3>Private Note.</h3><p>" . $_POST['comment'] . "</p>");
            }
            else
            {
                $to = $this->get_user_field($ticket_information->customer_id, 'user_email');
                $this->notfication_ticket_action($to, "Ticket new comment - #" . $_POST['ticket'], "<h3>New Message.</h3><p>" . $_POST['comment'] . "</p><p>You can track your ticket status on our dashboard pannel please <a href='" . site_url('wp-login.php') . "'>click here for login</a>  </p>");

            }
            $this->notice('Reply posted successfully!');
        }
        else
        {
            $this->notice('Please try again!', 'error');
        }
    }
    function priority_update()
    {
        $data['priority'] = $_POST['priority'];
        $this->update_record($this->table_ticket, $data, array(
            'id' => $_POST['ticket']
        ));
    }

    function subject_update()
    {
        if (!empty($_POST['subject']))
        {
            $data['subject'] = $_POST['subject'];
            $this->update_record($this->table_ticket, $data, array(
                'id' => $_POST['ticket']
            ));
            $this->notice('Subject update successfully!');
        }
        else
        {
            $this->notice('Subject field is empty !');
        }

    }
    function ticket_close()
    {
        if ($_POST['ticket'] == "")
        {
            $this->notice('Invalid request, Please try again!', 'error');
            return false;
        }
        $ticket_belong_securiety_check = $this->get_record_exist($this->table_ticket, array(
            'id' => $_POST['ticket']
        ));
        if (!$ticket_belong_securiety_check)
        {
            $this->notice('Invalid request, Please try again!', 'error');
            return false;
        }

        $data['status'] = 1;
        $update = $this->update_record($this->table_ticket, $data, array(
            'id' => $_POST['ticket']
        ));
        if ($update)
        {

            $ticket_information = $this->get_record_by_id($this->table_ticket, array(
                'id' => $_POST['ticket']
            ));

            $to = $this->get_user_field($ticket_information->customer_id, 'user_email');
            $executive_to = $this->get_user_field($ticket_information->staff_id, 'user_email');

            $this->notfication_ticket_action($to, "Ticket Closed - #" . $_POST['ticket'], "<h3>Ticket has been closed.</h3>");
            $this->notfication_ticket_action($executive_to, "Ticket Closed - #" . $_POST['ticket'], "<h3>Ticket has been closed.</h3>");

            $this->notice('Ticket has been closed successfully!');
        }
        else
        {
            $this->notice('Please try again!', 'error');
        }
    }
    function ticket_delete()
    {

        if ($_POST['ticket'] == "")
        {
            $this->notice('Invalid request, Please try again!', 'error');
            return false;
        }
        $select = 'id,ticket_id,attachment';
        $ticket_comment_attachments = $this->get_records_by_expression($select, $this->table_comments, array(
            'ticket_id =' => $_POST['ticket'],
            'attachment !=' => null
        ));
        $ticket_attachments = $this->get_records_by_expression($select, $this->table_comments, array(
            'id =' => $_POST['ticket'],
            'attachment !=' => null
        ));

        foreach ($ticket_attachments as $ticket_attachment)
        {
            unlink('' . wp_get_upload_dir() ["basedir"] . '/' . $ticket_attachment->attachment);

        }
        foreach ($ticket_comment_attachments as $ticket_comment_attachment)
        {
            unlink('' . wp_get_upload_dir() ["basedir"] . '/' . $ticket_comment_attachment->attachment);
        }

        $this->delete_record($this->table_comments, array(
            'ticket_id' => $_POST['ticket']
        ));
        $this->delete_record($this->table_ticket, array(
            'id' => $_POST['ticket']
        ));
        $this->delete_record($this->table_ticket_assign, array(
            'ticket_id' => $_POST['ticket']
        ));
        $this->notice('Ticket has Deleted successfully!');

        //  $myJSON = json_encode($ticket_comment_attachments);
        //  $this->notice($a,'error');
        
    }

    /*Merge Ticket Show*/
    function merge_ticket_show()
    {
        $content['users_list'] = $this->get_users_list();
        $this->load_view_admin('manage_admin/view/merge_ticket.php', $content);
    }
    /*Ajax return*/
    function usersticket_tickets()
    {
        global $wpdb;
        $result = $wpdb->get_results("SELECT id,subject,category FROM {$this->table_ticket} WHERE customer_id = {$_POST['id']} AND status=0");
        if (!empty($result))
        {
            echo json_encode($result);
            die();
        }
    }
    function ticket_merge()
    {
        $staff_id = 0;
        if ($_POST['user'] == "")
        {
            $this->notice('Please choose atleast one user, Please try again!', 'error');
            return false;
        }
        if ($_POST['from_ticket'] == "")
        {
            $this->notice('From ticket field is required, Please try again!', 'error');
            return false;
        }
        if ($_POST['to_ticket'] == "")
        {
            $this->notice('To ticket field is required, Please try again!', 'error');
            return false;
        }
        if ($_POST['from_ticket'] == $_POST['to_ticket'])
        {
            $this->notice('From ticket and To ticket should be different, Please try again!', 'error');
            return false;
        }
        $staff_id = $this->get_staff_id($_POST['to_ticket']);
        $action = $this->update_record($this->table_comments, array(
            'ticket_id' => $_POST['to_ticket']
        ) , array(
            'ticket_id' => $_POST['from_ticket']
        ));
        if ($action)
        {
            $this->delete_record($this->table_ticket, array(
                'id' => $_POST['from_ticket']
            ));
            $this->notice('Ticket has merged successfully!');
        }
        else
        {
            $this->notice('Something went wrong.Please try again!', 'error');
        }
    }
    function get_staff_id($id)
    {
        return $this->get_record_by_row($this->table_ticket, array(
            "id" => $id
        ) , 'staff_id');

    }
    /*documentation*/
    function documentation()
    {
        //$this->sendSMTPSystem("harrujasson@yopmail.com", 'Subject', 'Content'); die();
        $content['setting'] = $this->get_record_by_row($this->table_settings, array(
            "type" => "admin",
            'service' => "email"
        ));
        $content['general_setting'] = $this->get_record_by_row($this->table_settings, array(
            "type" => "admin",
            'service' => "general"
        ));
        $this->load_view_admin('manage_admin/view/documentation.php', $content);
    }
    /*Settings*/
    function settings()
    {
        //$this->sendSMTPSystem("harrujasson@yopmail.com", 'Subject', 'Content'); die();
        $content['setting'] = $this->get_record_by_row($this->table_settings, array(
            "type" => "admin",
            'service' => "email"
        ));
        $content['setting_ticket_close'] = $this->get_record_by_row($this->table_settings, array(
            "type" => "admin",
            'service' => "ticket_close"
        ));
        $content['general_setting'] = $this->get_record_by_row($this->table_settings, array(
            "type" => "admin",
            'service' => "general"
        ));
        /*========================================*/

        /*========================================*/
        $this->load_view_admin('manage_admin/view/setting.php', $content);
    }

    function settings_save()
    {
        $service = 'email';
        if (isset($_POST['service']) && $_POST['service'] != "")
        {
            $service = $_POST['service'];
        }
        unset($_POST['settings_admin']);
        unset($_POST['service']);

        $save_data = $_POST;

        $exist_status = $this->get_record_exist($this->table_settings, array(
            "type" => "admin",
            'service' => $service
        ));

        $data['type'] = 'admin';
        $data['service'] = $service;
        $data['information'] = json_encode($save_data);
        $data['user_id'] = $this->userid;

        if ($exist_status)
        {
            $update = $this->update_record($this->table_settings, $data, array(
                "type" => "admin",
                "service" => $service
            ));
            if ($update)
            {
                $this->notice('Setting has been saved successfully!');
            }
            else
            {
                $this->notice('Something went wrong.Please try again!', 'error');
            }
        }
        else
        {
            $insert = $this->insert_record($this->table_settings, $data);
            if ($insert)
            {
                $this->notice('Setting has been saved successfully!');
            }
            else
            {
                $this->notice('Something went wrong.Please try again!', 'error');
            }
        }
    }

    function settings_ticket_close_save()
    {
        $service = 'ticket_close';
        if (isset($_POST['service']) && $_POST['service'] != "")
        {
            $service = $_POST['service'];
        }
        unset($_POST['settings_admin']);
        unset($_POST['service']);

        $save_data = $_POST['ticket_close'];

        $exist_status = $this->get_record_exist($this->table_settings, array(
            "type" => "admin",
            'service' => $service
        ));

        $data['type'] = 'admin';
        $data['service'] = $service;
        $data['information'] = $save_data;
        $data['user_id'] = $this->userid;

        if ($exist_status)
        {
            $update = $this->update_record($this->table_settings, $data, array(
                "type" => "admin",
                "service" => $service
            ));
            if ($update)
            {
                $this->notice('Setting has been saved successfully!');
            }
            else
            {
                $this->notice('Something went wrong.Please try again!', 'error');
            }
        }
        else
        {
            $insert = $this->insert_record($this->table_settings, $data);
            if ($insert)
            {
                $this->notice('Setting has been saved successfully!');
            }
            else
            {
                $this->notice('Something went wrong.Please try again!', 'error');
            }
        }
    }

    /*History*/
    function history_show()
    {
        $this->load_view_admin('manage_admin/view/history/list.php');
    }
    function ticketmanage_history_show_list()
    {
        $aColumns = array(
            "id",
            "last_name",
            "email",
            "subject",
            "category",
            "status",
            "",
            ""
        );
        $condition = array();

        if ($_POST['email_search'] != "")
        {
            $condition[] = " email LIKE '%" . $_POST['email_search'] . "%' ";
        }
        if ($_POST['last_name'] != "")
        {
            $condition[] = " last_name LIKE '%" . $_POST['last_name'] . "%' ";
        }
        if (!empty($condition))
        {
            $condition = implode(" AND ", $condition);
        }

        if (!empty($condition))
        {
            $info = $this->set_data_table_condition($aColumns, $condition);
        }
        else
        {
            $info = $this->set_data_table($aColumns);
        }

        $details = $this->get_lists_request($info['sLimit'], $onlyCount = false, $info['sOrder'], $info['sWhere']);
        $counts = $this->get_lists_request('', $onlyCount = true, $info['sOrder'], $info['sWhere']);
        $output = array(
            "sEcho" => intval($_REQUEST['sEcho']) ,
            "iTotalRecords" => count($details) ,
            "iTotalDisplayRecords" => $counts,
            "aaData" => array()
        );

        /*Users list*/
        $usersAll = $this->get_users_list(['ticket_manager']);
        if (!empty($details)):
            foreach ($details as $k => $v)
            {
                $user_exist = '';
                $status = '<span class="btn btn-primary">Open</span>';
                if ($v['status'] == "1")
                {
                    $status = '<span class="btn btn-primary ticket-close">Close</span>';
                }

                $select = '';
                if ($v['status'] == "1")
                {
                    $select .= "<form method='post' action='' class='ts_short_form'>";
                    $select .= "<select name='ticket_reopen' class='form-control ts_form_submit'>";
                    $select .= "<option value=''>--Choose--</option>";
                    $select .= "<option value='0'>Re-open</option>";
                    $select .= "</select>";
                    $select .= "<input type='hidden' name='ticket_id' value='" . $v['id'] . "'>";
                    $select .= "</form>";
                }

                $button = '<a href="admin.php?page=ts_ticket_discussion&id=' . $v['id'] . '">Discussion</a>';

                $output['aaData'][] = array(
                    $v['id'],
                    $v['last_name'],
                    $v['email'],
                    $v['subject'],
                    $v['category'],
                    $status,
                    $select,
                    $button
                );
            }
        endif;
        echo json_encode($output);
        die();
    }

    /*Canned*/
    function canned_show()
    {
        $this->load_view_admin('manage_admin/view/canned/list.php');
    }

    /*Via Emails*/
    function emails_ticket()
    {

        $server_details = get_email_settings_all();
        if (empty($server_details))
        {
            $this->notice('Please add email server details in Ticket->setting->E-Maile Server tab.', 'error');
            return false;
        }
        if (!isset($server_details->host_imap) || empty($server_details->host_imap))
        {
            $this->notice('Please add IMAP Hostname in IMAP Hostname text field  Ticket->setting->E-Maile Server tab.', 'error');
            return false;
        }
        if (!isset($server_details->port) || empty($server_details->port))
        {
            $this->notice('Please add Port number in Port text field Ticket->setting->E-Maile Server tab.', 'error');
            return false;
        }
        if (!isset($server_details->username) || empty($server_details->username))
        {
            $this->notice('Please add username/Email in Username text field Ticket->setting->E-Maile Server tab.', 'error');
            return false;
        }
        if (!isset($server_details->password) || empty($server_details->password))
        {
            $this->notice('Please add password in Password text field Ticket->setting->E-Maile Server tab.', 'error');
            return false;
        }
        if (!isset($server_details->security) || empty($server_details->security) || $server_details->security == "tls")
        {
            $this->notice('Please choose SSL in Security option Ticket->setting->E-Maile Server tab.', 'error');
            return false;
        }

        $param['host'] = '{' . $server_details->host_imap . ':' . $server_details->port . '/' . $server_details->security . '/novalidate-cert}INBOX';
        $param['username'] = $server_details->username;
        $param['password'] = $server_details->password;

        $imap = new ImapFetch($param);

        if ($imap->connect())
        {
            $emails = $imap->information(); 
		
            if (!empty($emails) || $emails != NULL)
            {
                $cnt = 0;
				
                foreach ($emails as $email)
                {
					//$this->notice('attachments ' . print_r($email['attachments']));
					//$structure = $imap->fetchstructure($connection, $email);
					// $this->notice('Total  ' . print_r($structure));
                    $customer_email = $email['header']->from[0]->mailbox . '@' . $email['header']->from[0]->host;
					 /* iterate through each attachment and save it */
					 $attachment_url=array();
						foreach($email['attachments'] as $attachment)
						{
							if($attachment['is_attachment'] == 1)
							{
								     $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
								 
								$filename = $attachment['name'];
								if(empty($filename)) $filename = $attachment['filename'];

								if(empty($filename)) $filename = time() . ".dat";
								$folder = "attachment";
								if(!is_dir($folder))
								{
									 mkdir($folder);
									
								}
								 $url= "admin/". $folder ."/". date("d-m-Y-h:i:sA") . "-" . $filename;
								$fp = fopen(PRD_ROOT_PATH .$url , "w");
								 fwrite($fp, $attachment['attachment']);
								//WP_Filesystem_Direct::put_contents($fp, $attachment['attachment']);
								fclose($fp);
							 $attachment_url[] =$url;
							}
						}
                    if ($customer_email != "")
                    { 
				$attachment_url_json=json_encode($attachment_url);
                       $insert_status = $this->email_ticket_generate($customer_email, $email['subject'], $email['content'], $email['header']->fromaddress,$attachment_url_json);
                          if ($insert_status)
                        {
                            $cnt++;
                        }  
                    }

                }
				 
                if ($cnt)
                {
                    $this->notice('Total ticket has been generated: ' . $cnt);
                }
            }
            else
            {   
		   
                $this->notice('No new email found!', 'error');
            }
        }
        else
        {
            $this->notice('Can not connect with email server.Please try after some time!', 'error');
        }
    }
    /* Delete Ticket Access list*/
    function ticket_delete_access_show()
    {
        ///$content['UserAccessDetails'] = $this->get_lists_delete_access_request($info['sLimit'], $onlyCount = false, '', '');
        //   $content['users_list'] =  $this->get_users_list(['ticket_manager']); ;
        global $wpdb;

        $content['users_list'] = $wpdb->get_results("SELECT user.id ,user.display_name,access_del.status
                FROM {$this->table_users} as user INNER JOIN {$this->table_usermeta} as usermeta 
                ON user.ID = usermeta.user_id 
                LEFT JOIN {$this->ticket_delete_assigned} as access_del on user.id= access_del.store_manager_id
                WHERE usermeta.meta_key = '{$wpdb->prefix}capabilities' 
                AND usermeta.meta_value LIKE '%ticket_manager%' ", OBJECT);
        $this->load_view_admin('manage_admin/view/add_user_access.php', $content);
    }
    /* Delete Ticket Access*/
    function delete_user_access_update()
    {
        if ($_POST['assign_delete_ticket_user'] == '1' || $_POST['assign_delete_ticket_user'] == '0')
        {

            $exist_status = $this->get_record_exist($this->ticket_delete_assigned, array(
                'store_manager_id' => $_POST['store_manager_id']
            ));
            $data['status'] = $_POST['assign_delete_ticket_user'];
            if ($exist_status)
            {

                $this->update_record($this->ticket_delete_assigned, $data, array(
                    'store_manager_id' => $_POST['store_manager_id']
                ));
                $this->notice('Tickets Delete Access update successfully!');
            }
            else
            {

                $data['store_manager_id'] = $_POST['store_manager_id'];
                $insert = $this->insert_record($this->ticket_delete_assigned, $data);
                if ($insert)
                {
                    $this->notice('Tickets Delete Access has been saved successfully!');
                }
                else
                {
                    $this->notice('Something went wrong.Please try again!', 'error');
                }

            }
        }
        else
        {
            $this->notice('Subject field is empty !');
        }

    }
    /*Corn Job For Close ticket */
  /*  function corn_ticketClose()
    {
        $this->notfication_ticket_action_close('rahul.planet317@gmail.com', "Ticket Closed 1", 'Hello ');
        return;
        global $wpdb;
        $recepients = 'rahul.planet317@gmail.com';

        $no_of_days_result = $wpdb->get_row("SELECT *FROM " . $wpdb->prefix . "ts_settings  WHERE  type='admin' AND  service='ticket_close'  ", OBJECT);

        if (!empty($no_of_days_result))
        {
            $no_of_days = $no_of_days_result->information;
        }
        else
        {
            $no_of_days = 11;
        }

        $results = $wpdb->get_results("SELECT tk.first_name , tk.status ,com.* FROM " . $wpdb->prefix . "ts_tickets as tk join  " . $wpdb->prefix . "ts_tickets_comments as com on tk.id=com.ticket_id where com.created_at < SUBDATE(DATE(NOW())," . $no_of_days . ") and tk.status =0  GROUP BY com.ticket_id   ORDER BY com.created_at ASC ", OBJECT);

        if (!empty($results))
        {
            foreach ($results as $row)
            {
                $data['status'] = 1;
                $data['auto_close_status'] = 1;

                $update = $wpdb->update($wpdb->prefix . "ts_tickets", $data, array(
                    'id' => $row->ticket_id
                ));
                if ($update)
                {

                    mail($recepients, $subject, $message);
                    $ticket_information = $wpdb->get_row("SELECT *FROM " . $wpdb->prefix . "ts_tickets  WHERE  id=" . $row->ticket_id . " ", OBJECT);
                    // $to1 = $this->get_user_field($ticket_information->customer_id,'user_email');
                    $to = 'rahul.planet317@gmail.com';
                    // $executive_to = $this->get_user_field($ticket_information->staff_id,'user_email');
                    $content = "<h3>Dear " . $ticket_information->first_name . " " . $ticket_information->last_name . " ,</h3>  <p>Your ticket - " . $ticket_information->subject . " -  has been closed.</p><p>you have not responded so many days</p> <p>We hope that the ticket was resolved to your satisfaction. If you feel that the ticket should not be closed or if the ticket has not been resolved.</p>" . $to;
                    /// $this->notfication_ticket_action_close($to, "Ticket Closed - #".$row->ticket_id,  $content);
                    //$this->notfication_ticket_action_close($executive_to, "Ticket Closed - #".$row->ticket_id, "<h3>Ticket has been closed.</h3>");
                    // $ids[] =$row->ticket_id;
                    mail($recepients, $subject, $content);

                }

            }

        }

    }
  */

}

