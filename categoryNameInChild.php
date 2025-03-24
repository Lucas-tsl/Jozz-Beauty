function add_back_to_parent_category() {
    if (is_product_category()) {
        $term = get_queried_object();
        if ($term && $term->parent) {
            $parent_category = get_term($term->parent, 'product_cat');

            // Forcer un affichage correct des caractères spéciaux
            $parent_name = apply_filters('the_title', $parent_category->name);

            echo '<div class="return-to-parent" style="margin-bottom: 20px;">
                    <a href="' . esc_url(get_term_link($parent_category)) . '"  class="categ-back"">
                        < Revenir au ' . esc_html($parent_name) . '
                    </a>
                  </div>';
        }
    }
}
add_action('woocommerce_before_main_content', 'add_back_to_parent_category', 20);
