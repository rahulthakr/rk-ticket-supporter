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
class TS_Admin
{

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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    use TS_Roles;
    use TS_Authorized;
    use TS_Common;
    private $current_user_role;
    private $current_user_id;
    private $ticket_manage_loader;
    private $ticket_manage_loader_manager;
    private $customer;
    private $ticket_manage_loader_canned;
    private $table_canned = '';

    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        /*Set user roles variables*/
        $this->set_user_role();
        $this->current_user_role = $this->get_user_role_information('role');
        $this->current_user_id = $this->get_user_role_information('user_id');

        /*Create the the admin menu */
        add_action("admin_menu", array(
            $this,
            "menu_generation"
        ));

        /*Canned fetch ajax*/
        add_action('wp_ajax_cannedfetchlist', array(&$this,
            'cannedfetchlist'
        ));
        add_action('wp_ajax_nopriv_cannedfetchlist', array(&$this,
            'cannedfetchlist'
        ));

        /*Methods Loading*/
        $this->methods_loading();

        /*Catch all request by form submit*/
        $this->catch_request();

        /*Permission to shop manager*/
        add_action('admin_init', array(
            $this,
            'add_capability'
        ));

        /*Customer account*/
        add_shortcode('ts-customer-ticket-dashboard', array(&$this,
            'account_customer_load'
        ));

        /*Rewrite URL for customer*/
        add_action('init', array(&$this,
            'custom_rewrite_rule'
        ));

        /*Table*/
        global $wpdb;
        $this->table_canned = $wpdb->prefix . "ts_canned";

    }

    public function custom_rewrite_rule()
    {

        add_rewrite_rule('customer-ticket/?([^/]*)/([^/]*)', 'index.php?pagename=customer-ticket&node_first=$matches[1]&node_second=$matches[2]', 'top');
        add_filter('query_vars', function ($vars)
        {
            $vars[] = 'node_first';
            $vars[] = 'node_second';
            return $vars;
        });
    }

    /**
     * Register the stylesheets for the admin area.
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
        global $pagenow;
        if (( $pagenow == 'admin.php' ) && ($_GET['page'] == 'ts_ticket' || $_GET['page'] == 'ts_ticket_discussion' || $_GET['page'] == 'ts_manage_user_access' || $_GET['page'] == 'ts_ticket_merge_ticket' || $_GET['page'] == 'ts_ticket_history' || $_GET['page'] == 'ts_ticket_canned'  || $_GET['page'] == 'ts_ticket_settings'  || $_GET['page'] == 'ts_ticket_canned' || $_GET['page'] == 'ts_ticket_canned_new' || $_GET['page'] == 'ts_ticket_documentation' )) {

           
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ts-admin.css', array() , $this->version, 'all');
		  wp_enqueue_style('admin-styles_bootstrap', PRD_PLUGIN_URL_ADMIN ."assets/css/bootstrap.min.css");
          wp_enqueue_style('admin-styles-font-awesome', PRD_PLUGIN_URL_ADMIN ."assets/css/font-awesome.min.css");
          wp_enqueue_style('admin-styles-line-awesome', PRD_PLUGIN_URL_ADMIN ."assets/css/line-awesome.min.css");
          wp_enqueue_style('admin-styles-bootstrap4', PRD_PLUGIN_URL_ADMIN ."assets/css/dataTables.bootstrap4.min.css");
          wp_enqueue_style('admin-styles-select2', PRD_PLUGIN_URL_ADMIN ."assets/css/select2.min.css");
         // wp_enqueue_style('admin-styles', PRD_PLUGIN_URL_ADMIN ."assets/css/bootstrap-datetimepicker.min.css");
          ///wp_enqueue_style('admin-styles-summernote', PRD_PLUGIN_URL_ADMIN ."assets/css/summernote-bs4.css");
          wp_enqueue_style('admin-styles-style', PRD_PLUGIN_URL_ADMIN ."assets/css/style.css");
         // wp_enqueue_style('admin-styles-multiselect', PRD_PLUGIN_URL_ADMIN ."assets/css/jquery.multiselect.css");
          
          /*================== Admin Pages CSS====================*/
          wp_enqueue_style('admin-font-awesome',"https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
          wp_enqueue_style('admin-style',PRD_PLUGIN_URL_ADMIN ."css/style.css");
          wp_enqueue_style('bootstrap-min-css',PRD_PLUGIN_URL_ADMIN ."css/bootstrap.min.css");
          wp_enqueue_style('bootstrap-min-css',PRD_PLUGIN_URL_ADMIN ."/admin/datatable/jquery.dataTables.css");
           
    }

         
    }
 
///add_action('admin_enqueue_scripts', 'admin_style');
    /**
     * Register the JavaScript for the admin area.
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

        
        /*==============ADMIN JS=====================================*/
         
      	wp_enqueue_script('jquery',PRD_PLUGIN_URL_ADMIN ."assets/js/jquery-3.2.1.min.js", array(  'jquery' ) , $this->version, true);
		 wp_enqueue_script('multiselect',PRD_PLUGIN_URL_ADMIN."assets/js/jquery.multiselect.js", array(  'jquery' ) , $this->version, true);
		wp_enqueue_script('popper',PRD_PLUGIN_URL_ADMIN ."assets/js/popper.min.js", array(  'jquery' ) , $this->version, true);
		wp_enqueue_script('bootstrap',PRD_PLUGIN_URL_ADMIN ."assets/js/bootstrap.min.js", array(  'jquery' ) , $this->version, true);
		wp_enqueue_script('slimscroll',PRD_PLUGIN_URL_ADMIN ."assets/js/jquery.slimscroll.min.js", array(  'jquery' ) , $this->version, true);
		wp_enqueue_script('select2',PRD_PLUGIN_URL_ADMIN ."assets/js/select2.min.js", array(  'jquery' ) , $this->version, true); 
		 
		wp_enqueue_script('dataTables',PRD_PLUGIN_URL_ADMIN ."assets/js/jquery.dataTables.min.js", array(  'jquery' ) , $this->version, true);
		wp_enqueue_script('bootstrap4',PRD_PLUGIN_URL_ADMIN ."assets/js/dataTables.bootstrap4.min.js", array(  'jquery' ) , $this->version, true); 
		wp_enqueue_script('app',PRD_PLUGIN_URL_ADMIN ."assets/js/app.js", array(  'jquery' ) , $this->version, true);  
        
        wp_enqueue_script($this->plugin_name .'-header', plugin_dir_url(__FILE__) . 'js/ts-admin.js', array(
            'jquery'
        ) , $this->version, false);
        wp_enqueue_script($this->plugin_name .'-footer', plugin_dir_url(__FILE__) . 'js/ts-admin-js.js', array(
            'jquery'
        ) , $this->version, true);
		
		   
     
    }

    function menu_generation()
    {
        /*Admin menu links*/
        global $current_user;
        if (in_array('administrator', $current_user->roles))
        {
            add_menu_page("Ticket Supporter", "Ticket Supporter", "manage_options", "ts_ticket", array(&$this,
                "manage_ticket"
            ) , 'dashicons-admin-comments');

            add_submenu_page('ts_ticket', "User Access", "User Access", "manage_options", "ts_manage_user_access", array(&$this,
                "manage_user_access"
            ));
            add_submenu_page(null, "Discussion", "", "manage_options", "ts_ticket_discussion", array(&$this,
                "manage_ticket_discussion_admin"
            ));
            add_submenu_page("ts_ticket", "Merge Ticket", "Merge Ticket", "manage_options", "ts_ticket_merge_ticket", array(&$this,
                "manage_ticket_merge_ticket"
            ));
            add_submenu_page("ts_ticket", "History", "History", "manage_options", "ts_ticket_history", array(&$this,
                "manage_ticket_history"
            ));

            add_submenu_page("ts_ticket", "Canned", "Canned", "manage_options", "ts_ticket_canned", array(&$this,
                "canned"
            ));
            add_submenu_page(null, "Canned - Add new", "", "manage_options", "ts_ticket_canned_new", array(&$this,
                "canned_create"
            ));
            add_submenu_page(null, "Canned - Add new", "", "manage_options", "ts_ticket_canned_edit", array(&$this,
                "canned_edit"
            ));
            add_submenu_page(null, "Canned - Add new", "", "manage_options", "ts_ticket_canned_delete", array(&$this,
                "canned_delete"
            ));

            add_submenu_page("ts_ticket", "Settings", "Settings", "manage_options", "ts_ticket_settings", array(&$this,
                "settings"
            ));
            add_submenu_page("ts_ticket", "Fetch Email Tickets", "Fetch Email Tickets", "manage_options", "ts_ticket_emails", array(&$this,
                "emails_ticket"
            ));
            add_submenu_page("ts_ticket", "Documentation", "Documentation", "manage_options", "ts_ticket_documentation", array(&$this,
                "documentation"
            ));
        }

        if (in_array('ticket_manager', $current_user->roles))
        {
            /*Manager menu links*/
            add_menu_page("Ticket", "Ticket", "manager_access_only", "ts_ticket_customer", array(&$this,
                "manage_ticket_manager"
            ) , 'dashicons-admin-comments');
            add_submenu_page(null, "Discussion", "", "manager_access_only", "ts_ticket_customer_discussion", array(&$this,
                "manage_ticket_discussion"
            ));
            add_submenu_page("ts_ticket_customer", "History", "History", "manager_access_only", "ts_ticket_history_customer", array(&$this,
                "manage_ticket_history"
            ));
        }
    }
    function add_capability()
    {
        $shop_manager_role = get_role('ticket_manager');
        $shop_manager_role->add_cap('manager_access_only');
    }

    function methods_loading()
    {

        /*For admin*/
        require plugin_dir_path(dirname(__FILE__)) . 'admin/manage_admin/class-ts-manage_ticket.php';
        $this->ticket_manage_loader = new TS_Admin_Manage_Ticket($this->current_user_id, $this->current_user_role);

        require plugin_dir_path(dirname(__FILE__)) . 'admin/manage_admin/class-ts-manage_canned.php';
        $this->ticket_manage_loader_canned = new TS_Admin_Manage_Canned($this->current_user_id, $this->current_user_role);

        /*For shop manager*/
        require plugin_dir_path(dirname(__FILE__)) . 'admin/manage_shop_manager/class-ts-manage_ticket.php';
        $this->ticket_manage_loader_manager = new TS_Manager_Manage_Ticket($this->current_user_id, $this->current_user_role);

        /*For customer account*/
        if ($this->role == "customer")
        {
            require plugin_dir_path(dirname(__FILE__)) . 'admin/customer/class-ts-customer.php';
            $this->customer = new TS_Customer_Account($this->current_user_id, $this->current_user_role);
        }
    }

    /*Admin*/
    function manage_ticket()
    {
        $this
            ->ticket_manage_loader
            ->show();
    }
    /* function manage_user_access(){
            $this->ticket_manage_loader->user_access_list_show();
        }*/
    function manage_user_access()
    {
        $this
            ->ticket_manage_loader
            ->ticket_delete_access_show();
    }
    function manage_ticket_discussion_admin()
    {
        $this
            ->ticket_manage_loader
            ->discussion();
    }
    function manage_ticket_merge_ticket()
    {
        $this
            ->ticket_manage_loader
            ->merge_ticket_show();
    }
    function documentation()
    {
        $this
            ->ticket_manage_loader
            ->documentation();
    }

    function settings()
    {
        $this
            ->ticket_manage_loader
            ->settings();
    }
    function emails_ticket()
    {
        $this
            ->ticket_manage_loader
            ->emails_ticket();
    }
    function canned()
    {
        $this
            ->ticket_manage_loader_canned
            ->show();
    }
    function canned_create()
    {
        $this
            ->ticket_manage_loader_canned
            ->create();
    }
    function canned_edit()
    {
        $this
            ->ticket_manage_loader_canned
            ->edit();
    }
    function canned_delete()
    {
        $this
            ->ticket_manage_loader_canned
            ->delete();
    }

    /*Shop Manager*/
    function manage_ticket_manager()
    {
        $this
            ->ticket_manage_loader_manager
            ->show();
    }
    function manage_ticket_discussion()
    {
        $this
            ->ticket_manage_loader_manager
            ->discussion();
    }

    function manage_ticket_history()
    {

        if ($this->current_user_role == "administrator")
        {
            $this
                ->ticket_manage_loader
                ->history_show();
        }
        else if ($this->current_user_role == "ticket_manager")
        {
            $this
                ->ticket_manage_loader_manager
                ->history_show();
        }
    }

    function catch_request()
    {
        if (isset($_POST['assign_ticket_user']))
        {
            $this
                ->ticket_manage_loader
                ->assign_ticket();
        }
        if (isset($_POST['ticket_create_authorized']))
        {
            $this->is_customer_authorized($this->current_user_role);
            $this
                ->customer
                ->generate_ticket();
        }
        if (isset($_POST['ticket_create_authorized_customer']))
        {
            $this->is_customer_authorized($this->current_user_role);
            $this
                ->customer
                ->ticket_reply();
        }
        if (isset($_POST['ticket_close_authorized_customer']))
        {
            $this->is_customer_authorized($this->current_user_role);
            $this
                ->customer
                ->ticket_close();
        }

        if (isset($_POST['ticket_create_authorized_executive']))
        {
            $this->is_customer_executive($this->current_user_role);
            $this
                ->ticket_manage_loader_manager
                ->ticket_reply();
        }
        if (isset($_POST['ticket_close_authorized_executive']))
        {
            $this->is_customer_executive($this->current_user_role);
            $this
                ->ticket_manage_loader_manager
                ->ticket_close();
        }

        if (isset($_POST['ticket_delete_authorized_executive']))
        {
            // $this->is_admin_authorized($this->current_user_role);
            $this
                ->ticket_manage_loader_manager
                ->ticket_delete();
        }
        /*Admin action*/
        if (isset($_POST['ticket_create_authorized_admin']))
        {
            //  $this->is_admin_authorized($this->current_user_role);
            $this
                ->ticket_manage_loader
                ->ticket_reply_byajax();
        }
        if (isset($_POST['ticket_close_authorized_admin']))
        {
            $this->is_admin_authorized($this->current_user_role);
            $this
                ->ticket_manage_loader
                ->ticket_close();
        }

        if (isset($_POST['ticket_delete_authorized_admin']))
        {
            // $this->is_admin_authorized($this->current_user_role);
            $this
                ->ticket_manage_loader
                ->ticket_delete();
        }
        if (isset($_POST['ticket_priority_authorized_admin']))
        {
            $this->is_admin_authorized($this->current_user_role);
            $this
                ->ticket_manage_loader
                ->priority_update();
        }

        if (isset($_POST['ticket_merge_action']))
        {
            $this->is_admin_authorized($this->current_user_role);
            $this
                ->ticket_manage_loader
                ->ticket_merge();
        }
        if (isset($_POST['settings_admin']))
        {
            $this->is_admin_authorized($this->current_user_role);
            $this
                ->ticket_manage_loader
                ->settings_save();
        }
        if (isset($_POST['settings_ticket_close']))
        {
            // $this->is_admin_authorized($this->current_user_role);
            $this
                ->ticket_manage_loader
                ->settings_ticket_close_save();
        }

        if (isset($_POST['canned_new']))
        {
            $this->is_admin_authorized($this->current_user_role);
            $this
                ->ticket_manage_loader_canned
                ->save();
        }
        if (isset($_POST['canned_edit']))
        {
            $this->is_admin_authorized($this->current_user_role);
            $this
                ->ticket_manage_loader_canned
                ->edit_save();
        }

        if (isset($_POST['ticket_subject_change_authorized_admin']))
        {
            //   $this->is_admin_authorized($this->current_user_role);
            $this
                ->ticket_manage_loader
                ->subject_update();
        }

        if (isset($_POST['assign_delete_ticket_user']))
        {
            //   $this->is_admin_authorized($this->current_user_role);
            $this
                ->ticket_manage_loader
                ->delete_user_access_update();
        }
        if (isset($_POST['ticket_reopen']))
        {
            $this->is_authorized($this->current_user_role);
            $this->ticket_reopen();
        }

    }

    /*Common Re-open*/
    function ticket_reopen()
    {
        global $wpdb;
        $data['status'] = 0;
        $table = $wpdb->prefix . "ts_tickets";
        $update = $this->update_record($table, $data, array(
            'id' => $_POST['ticket_id']
        ));

        $ticket_information = $this->get_record_by_id($table, array(
            'id' => $_POST['ticket_id']
        ));

        $to = $this->get_user_field($ticket_information->customer_id, 'user_email');
        $executive_to = $this->get_user_field($ticket_information->staff_id, 'user_email');

        if ($update)
        {

            if ($this->current_user_role == "customer")
            {
                $this->notfication_ticket_action($executive_to, "Ticket Re-Open - #" . $_POST['ticket_id'], "<h3>Ticket has been re-open.</h3>");
                $this->notice_front('Ticket has been re-open successfully!');
            }
            else
            {
                $this->notfication_ticket_action($to, "Ticket Re-Open - #" . $_POST['ticket_id'], "<h3>Ticket has been re-open.</h3>");
                $this->notice('Ticket has been re-open successfully!');
            }

        }
        else
        {

            if ($this->current_user_role == "customer")
            {

                $this->notice_front('Please try again!', 'danger');
            }
            else
            {
                $this->notice('Please try again!', 'error');
            }

        }
    }

    /*Customer account short code*/
    function account_customer_load()
    {
        if (is_admin())
        {
            return false;
        }
        $this->is_customer_authorized($this->current_user_role);
        $this
            ->customer
            ->load_module();
    }

    /*Canned fetch*/
    function cannedfetchlist()
    {
        if (isset($_POST['term']) && $_POST['term'] != "")
        {
            $data = array();
            $result = $this->get_records_wildcard($this->table_canned, " title LIKE '%" . $_POST['term'] . "%' ");
            if (!empty($result))
            {
                $cnt = 0;
                foreach ($result as $r)
                {
                    $data[$cnt]['value'] = $r->title;
                    $data[$cnt]['id'] = $r->id;
                    $cnt++;
                }
                echo json_encode($data);
                die();
            }
        }
    }

}

