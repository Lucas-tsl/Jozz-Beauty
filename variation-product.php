// Fonction pour ajouter une classe 'has-variations' au body si le produit contient des variations
function add_product_variation_class($classes) {
    if (is_product()) {
        global $product;
        if ($product && $product->is_type('variable')) {
            $classes[] = 'has-variations';
        }
    }
    return $classes;
}
add_filter('body_class', 'add_product_variation_class');

// Fonction pour ajouter le CSS conditionnellement pour les produits avec variations
function add_custom_css_for_variations() {
    if (is_product()) {
        global $product;
        if ($product && $product->is_type('variable')) {
            echo '<style>
                /* Appliquer les styles uniquement aux produits avec variations */
                form.variations_form {
                    display: flex;
                    flex-direction: column;
                }

                @media (min-width: 1025px) and (hover: hover) {
                    .woocommerce div.product form.variations_form div.quantity,
                    .woocommerce button.single_add_to_cart_button {
                        margin-top: 0 !important;
                    }
                }
            </style>';
        }
    }
}
add_action('wp_head', 'add_custom_css_for_variations');
