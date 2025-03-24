function add_back_to_parent_category() {
    if (is_product_category()) {
        $term = get_queried_object();
        
        // Liste des catégories concernées
        $target_slugs = ['le-teint', 'les-levres', 'les-yeux', 'le-teint-maquillage', 'les-levres-maquillage', 'les-yeux-maquillage', 'les_yeux'];
        
        if ($term && $term->parent && in_array($term->slug, $target_slugs)) {
            $parent_category = get_term($term->parent, 'product_cat');

            // Forcer un affichage correct des caractères spéciaux
            $parent_name = apply_filters('the_title', $parent_category->name);

            echo '<div class="return-to-parent">
                    <a href="' . esc_url(get_term_link($parent_category)) . '" class="categ-back">
                        ⬅ Retour à ' . esc_html($parent_name) . '
                    </a>
                  </div>';
        }
    }
}
add_action('woocommerce_before_main_content', 'add_back_to_parent_category', 20);
