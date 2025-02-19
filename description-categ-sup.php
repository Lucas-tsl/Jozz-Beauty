
// Ajouter un champ de texte personnalisé à la page d'édition des catégories de produits
function custom_block_category_field($taxonomy) {
    ?>
    <div class="form-field">
        <label for="custom_block_content"><?php _e('Description supplementaire', 'textdomain'); ?></label>
        <textarea name="custom_block_content" id="custom_block_content" rows="5" cols="50"></textarea>
        <p class="description"><?php _e('Entrez la description supplementaire pour cette categorie.', 'textdomain'); ?></p>
    </div>
    <?php
}
add_action('product_cat_add_form_fields', 'custom_block_category_field', 10, 2);

// Enregistrer la valeur du champ personnalisé
function save_custom_block_category_field($term_id, $tt_id) {
    if (isset($_POST['custom_block_content']) && '' !== $_POST['custom_block_content']) {
        $custom_block_content = sanitize_textarea_field($_POST['custom_block_content']);
        add_term_meta($term_id, 'custom_block_content', $custom_block_content, true);
    }
}
add_action('created_product_cat', 'save_custom_block_category_field', 10, 2);

// Afficher le champ personnalisé existant lors de l'édition de la catégorie
function edit_custom_block_category_field($term, $taxonomy) {
    $custom_block_content = get_term_meta($term->term_id, 'custom_block_content', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="custom_block_content"><?php _e('Description supplementaire', 'textdomain'); ?></label></th>
        <td>
            <textarea name="custom_block_content" id="custom_block_content" rows="5" cols="50"><?php echo esc_textarea($custom_block_content); ?></textarea>
            <p class="description"><?php _e('Entrez la description supplementaire pour cette categorie.', 'textdomain'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('product_cat_edit_form_fields', 'edit_custom_block_category_field', 10, 2);

// Enregistrer la valeur du champ personnalisé mis à jour
function update_custom_block_category_field($term_id, $tt_id) {
    if (isset($_POST['custom_block_content']) && '' !== $_POST['custom_block_content']) {
        $custom_block_content = sanitize_textarea_field($_POST['custom_block_content']);
        update_term_meta($term_id, 'custom_block_content', $custom_block_content);
    }
}
add_action('edited_product_cat', 'update_custom_block_category_field', 10, 2);

// Afficher le bloc personnalisé juste avant le header spécifique
function display_custom_block_before_header() {
    if (is_product_category()) {
        // Obtenir la catégorie actuelle
        $current_category = get_queried_object();
        $category_id = $current_category->term_id;

        // Récupérer le contenu du bloc personnalisé pour cette catégorie
        $custom_block_content = get_term_meta($category_id, 'custom_block_content', true);

        // Afficher le bloc personnalisé avant le header spécifique
        if ($custom_block_content) {
            echo '<div class="custom-block">' . wp_kses_post($custom_block_content) . '</div>';
        }
    }
}
add_action('woocommerce_before_main_content', 'display_custom_block_before_header', 9);


