<?php


// Supprimer la description longue de l'onglet
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

// Ajouter la description longue après le résumé
add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 30 );


// Début fonction d'ajout de blocs dans les onglets (lucas.t)
add_filter( 'woocommerce_product_tabs', 'ajouter_onglet_personnalise' );

function ajouter_onglet_personnalise( $tabs ) {
    global $post, $product;

    // Récupérer la composition via un champ personnalisé
    $composition = get_post_meta( $post->ID, 'composition', true );

    // Récupérer la consigne de tri via un champ personnalisé ou un attribut produit
    $consigne_tri_custom_field = get_post_meta( $post->ID, 'consigne_tri', true );
    $consigne_tri_attribut = $product->get_attribute( 'consigne_de_tri' );

    // Création de l'onglet Composition
    if ( $composition ) {
        $tabs['composition'] = array(
            'title' => __( 'Composition', 'woocommerce' ),
            'priority' => 50,
            'callback' => 'afficher_composition'
        );
    }

    // Création de l'onglet Consigne de Tri
    if ( $consigne_tri_custom_field || $consigne_tri_attribut ) {
        $tabs['consigne_tri'] = array(
            'title' => __( 'Consigne de Tri', 'woocommerce' ),
            'priority' => 50,
            'callback' => 'afficher_consigne_tri'
        );
    }

    return $tabs;
}

function afficher_composition() {
    global $post;

    // Récupérer la composition
    $composition = get_post_meta( $post->ID, 'composition', true );

    if ( $composition ) {
        echo  esc_html( $composition );
    }
}

function afficher_consigne_tri() {
    global $post, $product;

    // Récupérer la consigne de tri, de préférence via get_field si ACF est utilisé
    $consigne_tri = get_field('consigne_tri', $post->ID) ?: $product->get_attribute('consigne_de_tri');

    if ($consigne_tri) {
        // Vérifier si la consigne de tri est une URL d'image
        echo '<p class="product-consigne-tri"><img src="' . esc_url($consigne_tri) . '" alt="Consigne de tri"></p>';
    } else {
        // Afficher le texte si ce n’est pas une URL ou si l'URL est absente
        echo '<p class="product-consigne-tri">Consigne de tri : ' . esc_html($consigne_tri) . '</p>';
    }
}
// Fin fonction d'ajout de blocs dans les onglets (lucas.t)

