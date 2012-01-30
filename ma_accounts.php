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
    public $plugin_info = array(
        'available_roles' => array('student', 'promoter')
    );

    public $options = array(
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

        register_activation_hook(__FILE__, array(&$this, 'activate_plugin'));

        add_action('admin_menu', array(&$this, 'add_admin_menu'));
        add_action('admin_init', array(&$this, 'admin_settings'));

        $this->update_roles();
        $this->update_edit_profile();
        $this->queue_view();
    } //End __construct

    public function activate_plugin() {
        update_option('ma_accounts_settings', $this->options);
        foreach (get_users() as $user) {
            add_user_meta($user->ID, 'ma_accounts_belt', '');
            add_user_meta($user->ID, 'ma_accounts_programs', '');
        }
    } //End activate_plugin

    public function add_admin_menu() {
        add_plugins_page('Manage account options, belts and special programs.', 'Martial Arts Accounts Manager', 'administrator', 'ma_accounts', array(&$this, 'render_backend'));
    } //End add_admin_menu

    public function admin_settings() {
        $this->options = (!get_option('ma_accounts_settings')) ? $this->options : get_option('ma_accounts_settings');
        if (current_user_can('administrator')) {
            register_setting('ma_accounts_settings', 'ma_accounts_settings', array(&$this, 'validate_settings'));
        }
    } //End admin_settings

    public function validate_settings($input) {
        $temp = '';
        $count = array(
            'belts' => count($this->options['belts']),
            'programs' => count($this->options['programs'])
        );
        foreach ($count as $key => &$value) {
            $value = (empty($this->options[$key])) ? 0 : $value;
        }
        //print_r($this->options['programs']);
        //die();

        $valid_options = array(
            'login_page' => trim($input['login_page']),
            'roles' => array(
                'remove' => str_replace(' ', '', $input['roles']['remove']),
                'add' => str_replace(' ', '', $input['roles']['add']),
                'default' => trim($input['roles']['default'])
            ),
            'belts' => $this->options['belts'],
            'programs' => $this->options['programs']
        );

        foreach($valid_options as $key => &$value) {
            $value = (!array_key_exists($key, $input)) ? $this->options[$key] : $value;
        }

        foreach ($valid_options as $key => &$value) {
            switch ($key) {
                case 'login_page':
                    if (!get_page_by_title($value)) {
                        $value = $this->options[$key];
                    }
                    break;
                case 'roles':
                    //Check roles to add
                    $temp = (is_array($value['add'])) ? $value['add'] : explode(',', $value['add']);
                    foreach ($temp as $role_key => &$role_value) {
                        if (!in_array($role_value, $this->plugin_info['available_roles'])) {
                            unset($temp[$role_key]);
                        }
                    }

                    //Check default role
                    if (!get_role($value['default']) && !in_array($value['default'], $temp)) {
                        $value['default'] = $this->options[$key]['default'];
                    }

                    //Had to move this under default role checking.
                    $value['add'] = $temp;

                    //Check roles to remove
                    $temp = (is_array($value['remove'])) ? $value['remove'] : explode(',', $value['remove']);
                    foreach ($temp as $role_key => &$role_value) {
                        if (!get_role($role_value) || $role_value === $value['default']) {
                            unset($temp[$role_key]);
                        }
                    }
                    $value['remove'] = $temp;
                    break;
                case 'belts':
                    if (array_key_exists('update_account', $input)) {
                        $total_users = count_users();
                        $total_users = $total_users['total_users'];

                        if ($input['update_account'] <= $total_users && $input['update_account'] > 0) {
                            $temp = (count($valid_options['belts']) > $input['belts']) ? $valid_options['belts'][$input['belts']]['id'] : '0';
                            update_user_meta($input['update_account'], 'ma_accounts_belt', $temp);
                        }
                    } else {
                        if (array_key_exists('belts', $input)) {
                            $valid_options['belts'][] = array('id' => $count['belts'], 'name' => trim($input['belts']));
                        }

                        if (array_key_exists('new_order', $input)) {
                            $temp = $valid_options['belts'];
                            $valid_options['belts'] = '';
                            $int = 0;
                            $input['new_order'] = explode(',', $input['new_order']);
                            array_pop($input['new_order']);

                            foreach ($input['new_order'] as $id) {
                                $valid_options['belts'][] = array('id' => $int, 'name' => $temp[$id]['name']);
                                $int++;
                            }
                        }

                        if (array_key_exists('belt_id', $input)) {
                            unset($valid_options['belts'][$input['belt_id']]);
                            $temp = $valid_options['belts'];
                            $int = 0;
                            $valid_options['belts'] = '';

                            foreach ($temp as $belt_key => $belt_value) {
                                $valid_options['belts'][] = array('id' => $int, 'name' => $belt_value['name']);
                                $int++;
                            }
                        }
                    }
                    break;
                case 'programs':
                    if (array_key_exists('update_account', $input)) {
                        $total_users = count_users();
                        $total_users = $total_users['total_users'];

                        if ($input['update_account'] <= $total_users && $input['update_account'] > 0) {
                            $temp = '';
                            foreach ($input['programs'] as $program_key => $program_value) {
                                if (is_array($valid_options['programs'][$program_key])) {
                                    $temp .= $program_key . ',';
                                }
                            }
                            $temp = substr($temp, 0, -1);
                            update_user_meta($input['update_account'], 'ma_accounts_programs', $temp);
                        }
                    } else {
                        if (array_key_exists('programs', $input)) {
                            $valid_options['programs'][$count['programs']] = array('id' => $count['programs'], 'name' => trim($input['programs']));
                        }
                        if (array_key_exists('program_id', $input)) {
                            unset($valid_options['programs'][$input['program_id']]);
                        }
                    }
                    break;
                default:
                    break;
            }
        }

        return $valid_options;
    } //End validate_settings

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
        if (current_user_can('administrator')) {
            wp_enqueue_style('black-tie');
            wp_enqueue_style('maAccountsStylesheet');
            wp_enqueue_script('maAccountsScript');
            include dirname(__FILE__) . '/application/view/options.php';
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
} //End ma_accounts

$ma_accounts = new ma_accounts;
?>
