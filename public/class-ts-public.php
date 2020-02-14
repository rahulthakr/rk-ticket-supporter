<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://planetwebzone.com
 * @since      1.0.0
 *
 * @package    TS
 * @subpackage TS/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    TS
 * @subpackage TS/public
 * @author     Planetwebzone <info@planetwebzone.com>
 */
class TS_Public
{
    use TS_Common;
    use TS_Roles;
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    private $table_ticket = '';
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action('template_include', array(
            $this,
            'customer_front_template_set'
        ));

        /*Catch request public*/
        $this->catch_request_public();
        /*Guest Ticket Form*/
        add_shortcode('ts-guest-ticket-form', array(&$this,
            'guest_ticket_form'
        ));

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in TS_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The TS_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ts-public.css', array() , $this->version, 'all');
         wp_enqueue_style('customer-font-awesome',"https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
        wp_enqueue_style($this->plugin_name.'customer-styles-bootstrap4', PRD_PLUGIN_URL_ADMIN ."assets/css/dataTables.bootstrap4.min.css");
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in TS_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The TS_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
       wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ts-public.js', array(
            'jquery'
        ) , $this->version, false);
             
		  
		wp_enqueue_script($this->plugin_name .'customer-dataTables',PRD_PLUGIN_URL_ADMIN ."assets/js/jquery.dataTables.min.js", array(  'jquery' ) , $this->version, true);
		  
	  
        wp_enqueue_script($this->plugin_name .'customer-footer', plugin_dir_url(__FILE__) . 'js/customer-frontend.js', array(
            'jquery'
        ) , $this->version, true);

    }
    function customer_front_template_set($template)
    {
        $plugindir = dirname(__FILE__);
        if (is_page_template('customer-dashboard.php'))
        {
            $template = $plugindir . '/templates/customer-dashboard.php';
        }
        return $template;
    }

    function catch_request_public()
    {
        if (isset($_POST['ticket_create_guest']))
        {
            $this->ticket_generate();
        }
    }
    function guest_ticket_form()
    {

        $this->load_view('partials/guest/form.php');
    }
    function ticket_generate()
    {
        global $wpdb;
        $this->table_ticket = $wpdb->prefix . "ts_tickets";
        if ($_POST['first_name'] == "")
        {
            $this->notice_front('First Name field is required', 'danger');
            return false;
        }
        if ($_POST['last_name'] == "")
        {
            $this->notice_front('Last Name field is required', 'danger');
            return false;
        }
        if ($_POST['email'] == "")
        {
            $this->notice_front('Email field is required', 'danger');
            return false;
        }
        if ($_POST['subject'] == "")
        {
            $this->notice_front('Subject field is required', 'danger');
            return false;
        }
        $customer_id = 0;
        $new_user = 0;
        $user = $this->get_user_by_info('email', $_POST['email']);

        if (!empty($user))
        {
            if (isset($user->first_name))
            {
                $data['first_name'] = $user->first_name;
            }
            if (isset($user->last_name))
            {
                $data['last_name'] = $user->last_name;
            }
            if (isset($user->user_email))
            {
                $data['email'] = $user->user_email;
            }
            if (isset($user->billing_phone))
            {
                $data['telephone'] = $user->billing_phone;
            }
            $customer_id = $user->ID;
        }
        else
        {
            if (email_exists($_POST['email']))
            {
                $this->notice_front('This email already exist. Please try with other email', 'danger');
                return false;
            }
            $customer_id = $this->create_customer_user($_POST['first_name'] . '-' . $_POST['last_name'], $_POST['email'], $_POST['first_name'], $_POST['last_name'], $_POST['telephone']);
            $data['first_name'] = $_POST['first_name'];
            $data['last_name'] = $_POST['last_name'];
            $data['email'] = $_POST['email'];
            $data['telephone'] = $_POST['telephone'];
            if (!$customer_id)
            {
                $this->notice_front('New user create problem. Please try after some time', 'danger');
                return false;
            }
            $new_user = 1;
        }

        $data['attachment'] = $this->upload_file();
        $data['subject'] = $_POST['subject'];
        $data['category'] = $_POST['category'];
        $data['description'] = $_POST['description'];
        $data['customer_id'] = $customer_id;

        $insert = $this->insert_record($this->table_ticket, $data, 1);
        $subject = "New Ticket Generate #" . $insert;
        if ($insert)
        {
            $this->notfication_ticket_action($data['email'], "New Ticket Generate #" . $insert, "<p>Your ticket has been generated successfully!</p>");
            if ($new_user)
            {
                $this->notice_front('Ticket has been generated successfully!, Please check you email, username and password has been send on registered email for login dashboard pannel.');
            }
            else
            {
                $this->notice_front('Ticket has been generated successfully!, Please login your dashboard and track your ticket.');
            }

        }
        else
        {
            $this->notice_front('Please try again!', 'danger');
        }
    }

    /*Corn Job For Close ticket */
    function corn_ticketClose()
    {
        // $result =  $this->get_record_by_row($table, array("type"=>"admin",'service'=>"email")) ;
        //  global $wpdb;
        $to = 'rahul.planet317@gmail.com';
        //  mail($recepients, 'test', 'hiu 1');
        global $wpdb;
        $recepients = 'rahul.planet317@gmail.com';
        $subject = 'rahul 23';

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

                    //    mail($recepients, 'test 1', 'hiu 1');
                    $ticket_information = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "ts_tickets  WHERE  id=" . $row->ticket_id . " ", OBJECT);
                      //$to  = $this->get_user_field($ticket_information->customer_id,'user_email');
                     $to  = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix ."users  WHERE   id=". $ticket_information->customer_id ."", OBJECT)->user_email;

                    // $executive_to = $this->get_user_field($ticket_information->staff_id,'user_email');
                    $content = "<h3>Dear " . $ticket_information->first_name . " " . $ticket_information->last_name . " ,</h3>  <p>Your ticket - " . $ticket_information->subject . " -  has been closed.</p><p>you have not responded so many days</p> <p>We hope that the ticket was resolved to your satisfaction. If you feel that the ticket should not be closed or if the ticket has not been resolved.</p>" ;
                    // $this->notfication_ticket_action($to, "Ticket Closed - #".$row->ticket_id,  $content);
                    // $this->notfication_ticket_action($to, "Ticket Closed - #".$row->ticket_id,  $content);
                    //$this->notfication_ticket_action_close($executive_to, "Ticket Closed - #".$row->ticket_id, "<h3>Ticket has been closed.</h3>");
                    // $ids[] =$row->ticket_id;
                    //mail($recepients, 'ts_settings', 'hiu 1');
                    $result = $wpdb->get_row("SELECT *FROM " . $wpdb->prefix ."ts_settings  WHERE  type='admin' AND  service='email'  ", OBJECT);
                    if (!empty($result))
                    {

                        if ($result->information != "")
                        {
                            $info = json_decode($result->information);

                            if (isset($info->hostname))
                            {
                                $hostname = $info->hostname;
                            }

                            if (isset($info->type_mail))
                            {
                                $mailtype = $info->type_mail;
                            }
                            if (isset($info->username))
                            {
                                $username = $info->username;
                            }
                            if (isset($info->password))
                            {
                                $password = $info->password;
                            }
                            if (isset($info->security))
                            {
                                $secure = $info->security;
                            }
                            if (isset($info->smtp_port))
                            {
                                $port = $info->smtp_port;
                            }
                            if (isset($info->from_email))
                            {
                                $fromemail = $info->from_email;
                            }
                            if (isset($info->from_name))
                            {
                                $from = $info->from_name;
                            }
                            if (isset($info->reply_name))
                            {
                                $reply = $info->reply_name;
                            }
                            if (isset($info->reply_email))
                            {
                                $replyemail = $info->reply_email;
                            }
                            if (isset($info->mailtype))
                            {
                                $mailtype = $info->mailtype;
                            }
                        }
                        //    mail($recepients, ' Before smtp', 'hiu 1');
                        if ($mailtype == "smtp")
                        {
                            //mail($recepients, ' After smtp', 'hiu 1');
                            $mail = new PHPMailerCst(true);
                            // $mail->isSMTP();                                      // Set mailer to use SMTP
                            $mail->Host = $hostname; // Specify main and backup SMTP servers
                            $mail->SMTPAuth = true; // Enable SMTP authentication
                            $mail->Username = $username; // SMTP username
                            $mail->Password = $password; // SMTP password
                            $mail->SMTPSecure = $secure; // Enable TLS encryption, `ssl` also accepted
                            $mail->Port = $port;
                            $mail->protocol = 'mail';

                            //Recipients
                            $mail->setFrom($fromemail, $from);
                            $mail->addAddress($to); // Name is optional
                            $mail->addReplyTo($replyemail, $reply);

                            //Content
                            $mail->isHTML(true); // Set email format to HTML
                            $mail->Subject = "Ticket Closed - #" . $row->ticket_id;
                            $mail->Body = $content;
                            $mail->AltBody = $content;
                            if ($attachment != "")
                            {
                                $mail->addAttachment($attachment, $attachment_name);
                            }
                            ///  echo "<pre>"; print_r($mail); echo "</pre>"; die();
                            $mail->send();

                        }
                        else
                        {

                            $header = "From: " . $this->fromemail . "\r\n";
                            $header .= "MIME-Version: 1.0\r\n";
                            $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                            $header .= "X-Priority: 1\r\n";
                            $status = mail($to, $subject, $content, $header);

                        }
                    }

                }

            }

        }

    }
}

