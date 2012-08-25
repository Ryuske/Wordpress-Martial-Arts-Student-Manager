<?php
class ma_accounts_update_settings extends ma_accounts {
    function __construct() {
        add_action('admin_init', array($this, 'admin_settings'));
    } //End __construct

    public function admin_settings() {
        parent::$options = (!get_option('ma_accounts_settings')) ? parent::$options : get_option('ma_accounts_settings');
        if (current_user_can('administrator')) {
            register_setting('ma_accounts_settings', 'ma_accounts_settings', array(&$this, 'validate_settings'));
        }
        $this->update_roles();
    } //End admin_settings

    private function update_roles() {
        foreach (parent::$options['roles']['remove'] as $role_key => $role_value) {
            remove_role($role_value);
        }

        /**********
         * Need to find out if there is a way to make it so they can add users without being able to
         * update roles (should only be able to add students)
         * Also need to figure out if I can make it so they can't promote themselves.
         */
        if (in_array('promoter', parent::$options['roles']['add'])) {
            add_role('promoter', 'Promoter', array(
                'read' => True,
                'list_users' => True,
                'edit_users' => True
            ));
        }

        if (in_array('student', parent::$options['roles']['add'])) {
            add_role('student', 'Student', array(
                'read' => True
            ));
        }

        update_option('default_role', parent::$options['roles']['default']);
    } //End update_roles

    public function validate_settings($input) {
        $temp = '';
        $parent_options = &parent::$options;
        $count = array(
            'belts' => count($parent_options['belts']),
            'programs' => count($parent_options['programs'])
        );
        foreach ($count as $count_key => $count_value) {
            $count[$count_key] = (empty($parent_options[$count_key])) ? 0 : $count_value;
        }

        $valid_options = array(
            'login_page' => trim($input['login_page']),
            'roles' => array(
                'remove' => str_replace(' ', '', $input['roles']['remove']),
                'add' => str_replace(' ', '', $input['roles']['add']),
                'default' => trim($input['roles']['default'])
            ),
            'belts' => $parent_options['belts'],
            'programs' => $parent_options['programs']
        );

        foreach ($valid_options as $vo_key => $vo_value) {
            $valid_options[$vo_key] = (!array_key_exists($vo_key, $input)) ? $parent_options[$vo_key] : $vo_value;
        }

        foreach ($valid_options as $vo_key => $vo_value) {
            switch ($vo_key) {
            case 'login_page':
                if (!get_page_by_title($vo_value)) {
                    $vo_value = $parent_options[$vo_key];
                }
                break;
            case 'roles':
                //Check roles to add
                $temp = (is_array($vo_value['add'])) ? $vo_value['add'] : explode(',', $vo_value['add']);
                foreach ($temp as $role_key => $role_value) {
                    if (!in_array($role_value, parent::$plugin_info['available_roles'])) {
                        unset($temp[$role_key]);
                    }
                }

                //Check default role
                if (!get_role($vo_value['default']) && !in_array($vo_value['default'], $temp)) {
                    $valid_options['roles']['default'] = $parent_options[$vo_key]['default'];
                }

                //Had to move this under default role checking.
                $valid_options['roles']['add'] = $temp;

                //Check roles to remove
                $temp = (is_array($vo_value['remove'])) ? $vo_value['remove'] : explode(',', $vo_value['remove']);
                foreach ($temp as $role_key => $role_value) {
                    if (!get_role($role_value) || $role_value === $vo_value['default']) {
                        unset($temp[$role_key]);
                    }
                }
                $valid_options['roles']['remove'] = $temp;
                break;
            case 'belts':
                //Update account with regards to belt
                if (array_key_exists('update_account', $input)) {
                    $account = get_userdata($input['update_account']);
                    if (!empty($account)) {
                        $temp = (count($vo_value) > $input['belts']) ? $vo_value[$input['belts']]['id'] : '0';
                        update_user_meta($input['update_account'], 'ma_accounts_belt', $temp);
                    }
                } else {
                    //Add belt
                    if (array_key_exists('belts', $input)) {
                        $valid_options['belts'][] = array('id' => $count['belts'], 'name' => trim($input['belts']));
                    }

                    //Re-order belts
                    if (array_key_exists('new_order', $input)) {
                        $temp = $vo_value;
                        $valid_options['belts'] = '';
                        $int = 0;
                        $input['new_order'] = explode(',', $input['new_order']);
                        array_pop($input['new_order']);

                        foreach ($input['new_order'] as $order_key => $order_value) {
                            $valid_options['belts'][] = array('id' => $int, 'name' => $temp[$order_value]['name']);
                            $int++;
                        }

                        foreach (get_users() as $user) {
                            foreach ($vo_value as $belt_key => $belt_value) {
                                if ($temp[get_user_meta($user->ID, 'ma_accounts_belt', true)]['name'] == $belt_value['name']) {
                                    update_user_meta($user->ID, 'ma_accounts_belt', $belt_value['id']);
                                }
                            }
                        }
                    }

                    //Delete belt
                    if (array_key_exists('belt_id', $input)) {
                        unset($valid_options['belts'][$input['belt_id']]);
                        $temp = $valid_options['belts'];
                        $int = 0;
                        $valid_options['belts'] = '';

                        foreach ($temp as $belt_key => $belt_value) {
                            $valid_options['belts'][] = array('id' => $int, 'name' => $belt_value['name']);
                            $int++;
                        }

                        foreach (get_users() as $user) {
                            if (get_user_meta($user->ID, 'ma_accounts_belt', true) === $input['belt_id']) {
                                update_user_meta($user->ID, 'ma_accounts_belt', '');
                            }
                        }
                    }
                }
                break;
            case 'programs':
                if (array_key_exists('update_account', $input)) {
                    //Update account with program
                    $account = $input['update_account'];
                    if (!empty($account) && !empty($input['programs'])) {
                        $temp = '';
                        foreach ($input['programs'] as $program_key => $program_value) {
                            if (is_array($valid_options['programs'][$program_key])) {
                                $temp .= $program_key . ',';
                            }
                        }
                        $temp = substr($temp, 0, -1);
                        update_user_meta($input['update_account'], 'ma_accounts_programs', $temp);
                    } else if (!empty($account) && $input['programs'] === NULL) {
                        update_user_meta($input['update_account'], 'ma_accounts_programs', '');
                    }
                } else {
                    //Add program
                    if (array_key_exists('programs', $input)) {
                        $valid_options['programs'][$count['programs']] = array('id' => $count['programs'], 'name' => trim($input['programs']));
                    }

                    //Delete program
                    if (array_key_exists('program_id', $input)) {
                        $temp = '';
                        unset($valid_options['programs'][$input['program_id']]);

                        foreach (get_users() as $user) {
                            $temp = explode(',', get_user_meta($user->ID, 'ma_accounts_programs', true));
                            if ((!empty($temp) || $temp[0] !== '') && in_array($input['program_id'], $temp)) {
                                unset($temp);
                            }
                            update_user_meta($user->ID, 'ma_accounts_programs', implode(',', $temp));
                        }
                    }
                }
                break;
            default:
                break;
            }
        }
        return $valid_options;
    } //End validate_settings
} //End update_settings
?>
