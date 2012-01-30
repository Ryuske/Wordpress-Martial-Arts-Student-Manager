<?php
function ma_accounts_profile_html($user) {
    /*
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('h3').eq(0).html('');
            jQuery('.form-table:eq(0) tr').html('');
            jQuery('.form-table:eq(1) tr').eq(0).html('');
            jQuery('.form-table:eq(1) tr').eq(4).html('');
            jQuery('.form-table:eq(2) tr').eq(1).html('');
            jQuery('.form-table:eq(2) tr').eq(2).html('');
            jQuery('.form-table:eq(2) tr').eq(3).html('');
            jQuery('.form-table:eq(2) tr').eq(4).html('');
            jQuery('.form-table:eq(3) tr').eq(0).html('');
            //Hide fields with this. Also need to make this an option in the admin section (i.e. turn on/off)
        });
    </script>
     * Add this in some future version, maybe
     */
    ?>
    <h3>Rank Information<h3>
    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="belt">Belt</label></th>
                <td><span class="description"><?php echo esc_attr(get_the_author_meta('belt', $user->ID)); ?></span>
            </tr>
            <tr>
                <th><label for="vip">VIP Programs</label></th>
                <td><span class="description"><?php echo esc_attr(get_the_author_meta('vip', $user->ID)); ?></span>
            </tr>
        </tbody>
    </table>
    <?php
}

function ma_accounts_edit_profile_html($user) {
    if ('administrator' !== wp_get_current_user()->roles[0]) {
        /*
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('h3').eq(0).html('');
            jQuery('h3').eq(1).html('');
            jQuery('h3').eq(2).html('');
            jQuery('h3').eq(3).html('');
            jQuery('.form-table:eq(0)').html('');
            jQuery('.form-table:eq(1)').html('');
            jQuery('.form-table:eq(2)').html('');
            jQuery('.form-table:eq(3)').html('');
            jQuery('table').eq(5).html('');
        });
        </script>
         * Add this in some future version, maybe
         */
    }
    ?>
    <h3>Rank Information (Updated in MA Accounts options)<h3>
    <?php $settings = get_option('ma_accounts_settings'); ?>
    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="belt">Belt</label></th>
                <td>
                    <?php esc_html_e($settings['belts'][get_user_meta($user->ID, 'ma_accounts_belt', true)]['name']); ?>
                </td>
            </tr>
            <tr>
                <th><label for="vip">VIP Programs</label></th>
                <td>
                    <?php
                    $temp = '';
                    $programs_array = (get_user_meta($user->ID, 'ma_accounts_programs') !== '' ) ? explode(',', get_user_meta($user->ID, 'ma_accounts_programs', true)) : 'Not currently enrolled.';
                    if (is_array($programs_array)) {
                        foreach ($programs_array as $value) {
                            $temp .= $settings['programs'][$value]['name'] . ', ';
                        }
                        $temp = substr($temp, 0, -2);
                    }
                    ?>
                    <?php esc_html_e($temp); ?>
                </td>
            </tr>
        </tbody>
    </table>
    <?php
}
?>
