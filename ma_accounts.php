<?php
/*
Plugin Name: Martial Arts Student Manager
Description: Allows easy management of Martial Arts students.
Version: 1.0
Author: Kenyon Haliwell
License: GPL2
 */

require_once(dirname(__FILE__) . '/application/view/profile.php');
require_once(dirname(__FILE__) . '/application/view/students.php');

class ma_accounts {
    function __construct() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('jquery-ui-dialog');
        wp_register_style('black-tie', plugins_url('application/view/css/jquery-ui.css', __FILE__));
        wp_register_style('maAccountsStylesheet', plugins_url('application/view/css/ma_accounts.css', __FILE__));
        wp_register_script('maAccountsScript', plugins_url('application/view/js/admin.js', __FILE__));

        add_action('admin_menu', array(&$this, 'add_admin_menu'));
        add_action('admin_init', array(&$this, 'admin_settings'));

        $this->update_roles();
        $this->update_edit_profile();
        $this->queue_view();
    } //End __construct

    public function add_admin_menu() {
        add_plugins_page('Manage account options, belts and special programs.', 'Martial Arts Acconts Manager', 'administrator', 'ma_accounts', array(&$this, 'render_backend'));
    } //End add_admin_menu

    public function admin_settings() {
        register_setting('quiz_manager_settings', 'quiz_manager_settings', array(&$this, 'validate_settings'));

        if (isset($_POST['action'])) {
            update_option('quiz_manager_settings', $this->validate_settings($_POST['quiz_manager_settings']));
        }
    } //End admin_settings

    private function update_roles() {
        remove_role('editor'); //Make removing these optional
        remove_role('author');
        remove_role('contributor');
        remove_role('subscriber');

        if (NULL !== get_role('promoter')) {
            remove_role('promoter');
        }
        if (NULL !== get_role('student')) {
            remove_role('student');
        }

        /**********
         * Need to find out if there is a way to make it so they can add users without being able to
         * update roles (should only be able to add students)
         * Also need to figure out if I can make it so they can't promote themselves.
         */
        add_role('promoter', 'Promoter', array(
            'read' => True,
            'list_users' => True,
            'edit_users' => True
        ));

        add_role('student', 'Student', array(
            'read' => True
        ));

        update_option('default_role', 'student'); //Make this optional
    } //End update_roles

    private function update_admin_options() {
    } //End update_admin_options

    private function update_edit_profile() { //Update to object-oriented style
        add_action('show_user_profile', 'ma_accounts_profile_html');
        add_action('edit_user_profile', 'ma_accounts_edit_profile_html');
    } //End update_edit_profile

    public function render_backend() {
        wp_enqueue_style('black-tie');
        wp_enqueue_style('maAccountsStylesheet');
        wp_enqueue_script('maAccountsScript');
        include dirname(__FILE__) . '/application/view/options.php';
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
} //End ma_accounts

$ma_accounts = new ma_accounts;
?>
