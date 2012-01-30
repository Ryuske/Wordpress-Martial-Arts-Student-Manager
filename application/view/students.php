<?php
function ma_accounts_students($type='', $user='') {
    $settings = get_option('ma_accounts_settings');
    if ('vip' === $type) {
        if (is_user_logged_in() || isset($user->ID)) {
            $page = get_page_by_title($settings['login_page']);
        } else {
            $page = get_page_by_title('VIP Students'); //Need to figure out how to make this dynamic...
            if (is_wp_error($user)) {
                $page->post_content .= $user->get_error_message();
            }
        }
        get_header();
        flush();
        ?>
        <div id="container">
            <div id="content" role="main">
                <?php echo strip_shortcodes(do_shortcode($page->post_content)); ?>
            </div>
        </div>
        <?php
        get_footer();
        flush();
    } else {
        if (is_user_logged_in() || isset($user->ID)) {
            $page = get_page_by_title($settings['login_page']);
        } else {
            $page = get_page_by_title('Students');
            if (is_wp_error($user)) {
                $page->post_content .= $user->get_error_message();
            }
        }
        get_header();
        flush();
        ?>
        <div id="container">
            <div id="content" role="main">
                <?php echo strip_shortcodes(do_shortcode($page->post_content)); ?>
            </div>
        </div>
        <?php
        get_footer();
        flush();
    }
}
?>
