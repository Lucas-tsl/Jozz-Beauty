add_action('woocommerce_after_shop_loop_item', 'display_variation_swatches_in_shop', 15);

function display_variation_swatches_in_shop() {
    if (is_shop() || is_product_category()) {
        global $product;

        if ($product->is_type('variable')) {
            $attributes = $product->get_variation_attributes();
            $available_variations = $product->get_available_variations();

            // CSS intégré
            echo '<style>
                .custom-variation-swatches {
                    display: flex;
                    flex-wrap: nowrap; /* Pas de retour à la ligne */
                    overflow-x: auto; /* Défilement horizontal */
                    gap: 10px; /* Espacement entre les éléments */
                    padding: 10px 0;
                    scrollbar-width: thin; /* Réduction de la taille de la barre de défilement */
                    scrollbar-color: #ccc transparent; /* Couleur de la barre de défilement */
                }
                .custom-variation-swatches::-webkit-scrollbar {
                    height: 6px; /* Hauteur de la barre de défilement */
                }
                .custom-variation-swatches::-webkit-scrollbar-thumb {
                    background: #ccc;
                    border-radius: 10px;
                }
                .custom-variation-swatches::-webkit-scrollbar-track {
                    background: #f5f5f5;
                }
                .swatch-item {
                    flex-shrink: 0; /* Empêche les éléments de se rétrécir */
                    width: 80px; /* Largeur de chaque item */
                    height: 80px; /* Hauteur de chaque item */
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    overflow: hidden;
                    text-align: center;
                    transition: all 0.3s ease;
                    cursor: pointer;
                    background-color: #f9f9f9;
                }
                .swatch-image {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
                .swatch-label {
                    font-size: 12px;
                    text-align: center;
                    margin-top: 5px;
                    color: #333;
                }
                .swatch-item:hover {
                    border-color: #888;
                    transform: scale(1.05);
                }
            </style>';

            // Début du HTML pour les swatches
            echo '<ul class="custom-variation-swatches">';

            foreach ($available_variations as $variation) {
                $variation_label = $variation['attributes']['attribute_teinte']; // Nom de l'attribut de variation
                $variation_image = $variation['image']['src']; // Image associée à la variation
                $variation_name = $variation_label;

                echo '<li class="swatch-item" aria-label="' . esc_attr($variation_label) . '" data-value="' . esc_attr($variation_label) . '" role="button" aria-pressed="false">';
                echo '<img class="swatch-image" src="' . esc_url($variation_image) . '" alt="' . esc_attr($variation_name) . '">';
                echo '<span class="swatch-label">' . esc_html($variation_name) . '</span>';
                echo '</li>';
            }

            // Fin du HTML
            echo '</ul>';
        }
    }
}
