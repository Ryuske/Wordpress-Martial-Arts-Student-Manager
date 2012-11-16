<?php
require_once(dirname(__FILE__) . '/../application/view/profile.php');
require_once(dirname(__FILE__) . '/../application/view/students.php');

class ma_accounts_generate_views extends ma_accounts {
    function __construct() {
        add_action('admin_menu', array(&$this, 'add_admin_menu'));

        $this->update_edit_profile();
        $this->queue_view();
    } //End __construct

    public function add_admin_menu() {
        add_plugins_page('Manage account options, belts and special programs.', 'Martial Arts Accounts Manager', 'administrator', 'ma_accounts', array(&$this, 'render_backend'));
    } //End add_admin_menu

    private function update_edit_profile() { //Update to object-oriented style (i.e. array(&$this, 'funcName'))
        add_action('show_user_profile', array(&$this, 'profile_html'));
        add_action('edit_user_profile', array(&$this, 'edit_profile_html'));
    } //End update_edit_profile

    public function render_backend() {
        if (current_user_can('administrator')) {
            wp_enqueue_style('black-tie');
            wp_enqueue_style('maAccountsStylesheet');
            wp_enqueue_script('maAccountsScript');
            include dirname(__FILE__) . '/../application/view/options.php';
        }
    } //End render_backend

    private function queue_view() {
        add_action('template_redirect', array(&$this, 'show_view'));
    } //End queue_view

    public function show_view() {
        global $wp_query;

        switch ($wp_query->query_vars['name']) {
        case 'vip_students':
            if (isset($_POST['submit'])) {
                $login = array();
                $login['user_login'] = $_POST['user'];
                $login['user_password'] = $_POST['pass'];
                $user = wp_signon($login, True);
            }
            ma_accounts_students('vip', $user);
            exit;
            break;
        case 'students':
            ma_accounts_students('');
            exit;
            break;
        default:
            break;
        }
    } //End show_view
} //End generate_views
?>
