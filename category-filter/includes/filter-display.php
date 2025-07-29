<?php
// Display on frontend with shortcode [category_filter]
add_shortcode('category_filter', 'cfp_category_filter_shortcode');
function cfp_category_filter_shortcode($atts) {
    $atts = shortcode_atts([
        'post_type' => 'post',
        'taxonomy'  => 'category',
    ], $atts, 'category_filter');

    // Get terms for dropdown
    $terms = get_terms([
        'taxonomy'   => $atts['taxonomy'],
        'hide_empty' => true,
    ]);

    if (empty($terms) || is_wp_error($terms)) {
        return '<p>No terms found.</p>';
    }

    ob_start();

    echo '<div class="cfp-wrapper">';
    
    // Dropdown filter
    echo '<form method="GET" class="cfp-filter-form">';
    echo '<select name="cfp_term" onchange="this.form.submit()">';
    echo '<option value="">All ' . esc_html(ucfirst($atts['taxonomy'])) . '</option>';

    foreach ($terms as $term) {
        $selected = (isset($_GET['cfp_term']) && $_GET['cfp_term'] == $term->term_id) ? 'selected' : '';
        echo '<option value="' . esc_attr($term->term_id) . '" ' . $selected . '>' . esc_html($term->name) . '</option>';
    }

    echo '</select>';
    echo '</form>';

    // Query args
    $query_args = [
        'post_type'      => $atts['post_type'],
        'posts_per_page' => -1,
    ];

    if (!empty($_GET['cfp_term'])) {
        $query_args['tax_query'] = [[
            'taxonomy' => $atts['taxonomy'],
            'field'    => 'term_id',
            'terms'    => intval($_GET['cfp_term']),
        ]];
    }

    $query = new WP_Query($query_args);

    if ($query->have_posts()) {
        echo '<div class="cfp-posts">';
        while ($query->have_posts()) {
            $query->the_post();

            // Use filter to allow customizing the post output
            $post_html = '<div class="cfp-post">';
            $post_html .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
            $post_html .= '</div>';

            echo apply_filters('cfp_post_output', $post_html, get_post());
        }
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p class="cfp-no-posts">No posts found.</p>';
    }

    echo '</div>'; // .cfp-wrapper

    return ob_get_clean();
}


