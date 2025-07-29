<?php
register_activation_hook(__FILE__, 'category_filter_activate');
register_deactivation_hook(__FILE__, 'category_filter_deactivate');

function category_filter_activate() {
    update_option('cfp_enabled', 'yes');
    update_option('cfp_redirect_to_settings', true);
}

function category_filter_deactivate() {
    delete_transient('cfp_temp');
}
