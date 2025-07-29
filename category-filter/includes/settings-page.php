<?php
add_action('admin_menu', 'cfp_add_settings_menu');

function cfp_add_settings_menu() {
    add_menu_page(
        'Category Filter Settings',
        'Category Filter',
        'manage_options',
        'cfp-settings',
        'cfp_render_settings_page',
        'dashicons-filter',
        60
    );

}

function cfp_render_settings_page() {
    // If form submitted, store selected post type
    $selected_post_type = isset($_POST['cfp_post_type']) ? sanitize_text_field($_POST['cfp_post_type']) : '';
    $generated_shortcode = '';

    if (!empty($_POST['generate_shortcode']) && $selected_post_type) {
        // Get the first hierarchical taxonomy
        $taxonomies = get_object_taxonomies($selected_post_type, 'objects');
        $selected_taxonomy = '';

        foreach ($taxonomies as $taxonomy) {
            if ($taxonomy->hierarchical) {
                $selected_taxonomy = $taxonomy->name;
                break;
            }
        }

        if ($selected_taxonomy) {
            $generated_shortcode = '[category_filter post_type="' . esc_attr($selected_post_type) . '" taxonomy="' . esc_attr($selected_taxonomy) . '"]';
        } else {
            $generated_shortcode = 'No hierarchical taxonomy found for this post type.';
        }
    }

    $post_types = get_post_types(['public' => true], 'objects');
    ?>

    <div class="wrap">
        <h1>Category Filter Settings</h1>

        <form method="post">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="cfp_post_type">Select Post Type</label></th>
                    <td>
                        <select name="cfp_post_type" id="cfp_post_type">
                            <option value="">-- Select Post Type --</option>
                            <?php foreach ($post_types as $key => $pt): ?>
                                <option value="<?php echo esc_attr($key); ?>" <?php selected($selected_post_type, $key); ?>>
                                    <?php echo esc_html($pt->label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row"></th>
                    <td>
                        <input type="submit" name="generate_shortcode" class="button button-primary" value="Generate Shortcode">
                    </td>
                </tr>

                <?php if (!empty($generated_shortcode)): ?>
                <tr>
                    <th scope="row">Shortcode</th>
                    <td>
                        <input type="text" readonly style="width: 100%;" value="<?php echo esc_attr($generated_shortcode); ?>">
                    </td>
                </tr>
                <?php endif; ?>
            </table>
        </form>
    </div>

    <?php
}

add_action('admin_init', 'cfp_register_settings');

function cfp_register_settings() {
    register_setting('cfp_settings_group', 'cfp_enabled');

    add_settings_section('cfp_main_section', '', null, 'cfp-settings');

    add_settings_field(
        'cfp_enabled',
        'Enable Filter',
        'cfp_enabled_field',
        'cfp-settings',
        'cfp_main_section'
    );
}

function cfp_enabled_field() {
    $value = get_option('cfp_enabled', 'yes');
    ?>
    <select name="cfp_enabled">
        <option value="yes" <?php selected($value, 'yes'); ?>>Yes</option>
        <option value="no" <?php selected($value, 'no'); ?>>No</option>
    </select>
    <?php
}
