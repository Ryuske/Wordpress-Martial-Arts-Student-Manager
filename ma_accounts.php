<?php
/*
Plugin Name: Martial Arts Student Manager
Description: Allows easy management of Martial Arts students. (This version is to be used with PHP >=5.3)
Version: 1.5
Author: Kenyon Haliwell
License: GPL2
 */

require_once(dirname(__FILE__) . '/controller/ma_accounts_generate_views.php');
require_once(dirname(__FILE__) . '/controller/ma_accounts_update_settings.php');

class ma_accounts {
    static public $plugin_info = array(
        'available_roles' => array('student', 'promoter')
    );

    static public $options = array(
        'login_page' => 'Students-LoggedIn',
        'roles' => array(
            'remove' => array('editor', 'author', 'contributor', 'subscriber'),
            'add' => array('student', 'promoter'),
            'default' => 'student'
        ),
        'belts' => array(/*Example: array('id' => '', 'name' => '')*/),
        'programs' => array(/*Example: array('id' => '', 'name' => '')*/)
    );

    function __construct() {

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('jquery-ui-sortable');
        wp_register_style('black-tie', plugins_url('application/view/css/jquery-ui.css', __FILE__));
        wp_register_style('maAccountsStylesheet', plugins_url('application/view/css/ma_accounts.css', __FILE__));
        wp_register_script('maAccountsScript', plugins_url('application/view/js/admin.js', __FILE__));

        wp_register_script('jquery-cookie', plugins_url('/application/view/js/jquery-cookie/jquery.cookie.js', __FILE__));
        wp_enqueue_script('jquery-cookie');

        register_activation_hook(__FILE__, array(&$this, 'activate_plugin'));
    } //End __construct

    public function activate_plugin() {
        update_option('ma_accounts_settings', $this->options);
        foreach (get_users() as $user) {
            add_user_meta($user->ID, 'ma_accounts_belt', '');
            add_user_meta($user->ID, 'ma_accounts_programs', '');
        }
    } //End activate_plugini

    public function sort_accounts($sort_by) {
        //Allow sorting by belt or program based on numerical IDs
    } //End sort_accounts
} //End ma_accounts

$ma_accounts = array('main' => '', 'generate_views' => '', 'update_settings' => '');
$ma_accounts['main'] = new ma_accounts;
$ma_accounts['generate_views'] = new ma_accounts_generate_views;
$ma_accounts['update_settings'] = new ma_accounts_update_settings;
