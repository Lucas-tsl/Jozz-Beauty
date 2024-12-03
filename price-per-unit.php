<?php


    add_action('woocommerce_single_product_summary', function () {
    global $product;

    // Récupérer la contenance (ml) et le poids (g) depuis les champs ACF
    $capacity = get_field('product_capacity', $product->get_id()); // Contenance en ml
    $weight = get_field('product_weight', $product->get_id()); // Poids en grammes
    $price = wc_get_price_to_display($product); // Récupérer le prix affiché

    // Calculer et afficher le prix au 100 ml si la contenance est définie
    if ($capacity && $capacity > 0) {
        $price_per_100ml = ($price / $capacity) * 100;
        echo '<p class="price-per-unit"><strong>' . sprintf(__(' %.2f euros / 100 ml', 'woocommerce'), $price_per_100ml) . '</strong></p>';

    }

    // Calculer et afficher le prix au 100 g si le poids est défini
    if ($weight && $weight > 0) {
        $price_per_100g = ($price / $weight) * 100;
        echo '<p class="price-per-unit"><strong>' . sprintf(__('%.2f euros / 100 g', 'woocommerce'), $price_per_100g) . '</strong></p>';
    }
}, 12);

?>
