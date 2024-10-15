<?php
/*
Plugin Name: WooCommerce Product Video
Plugin URI: http://yourwebsite.com
Description: Ajoute une vidéo à la galerie de produits WooCommerce avec support des URL YouTube, Vimeo et des vidéos locales.
Version: 1.1
Author: Troteseil Lucas
Author URI: Jozz 
License: GPL2
*/

// Ajout de la métabox pour insérer une URL de vidéo ou télécharger une vidéo locale
function wp_add_video_url_metabox() {
    add_meta_box(
        'product_video_url',
        'Vidéo du produit',
        'wp_display_video_url_metabox',
        'product',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'wp_add_video_url_metabox' );

// Affichage de la métabox pour saisir l'URL ou télécharger une vidéo
function wp_display_video_url_metabox( $post ) {
    // URL de la vidéo
    $video_url = get_post_meta( $post->ID, '_product_video_url', true );
    // Fichier vidéo local
    $video_file = get_post_meta( $post->ID, '_product_video_file', true );
    ?>
    <label for="product_video_url">URL de la vidéo (YouTube, Vimeo ou MP4) :</label>
    <input type="text" id="product_video_url" name="product_video_url" value="<?php echo esc_attr( $video_url ); ?>" style="width: 100%;"><br><br>

    <label for="product_video_file">Télécharger une vidéo :</label><br>
    <input type="button" class="button" id="upload_video_button" value="Télécharger une vidéo" />
    <input type="hidden" id="product_video_file" name="product_video_file" value="<?php echo esc_attr( $video_file ); ?>" />
    <p class="description">Choisissez un fichier vidéo MP4 à télécharger.</p>

    <?php
    if ( $video_file ) {
        echo '<p><strong>Vidéo actuelle :</strong> <a href="' . esc_url( $video_file ) . '" target="_blank">Voir la vidéo</a></p>';
    }
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($){
        $('#upload_video_button').click(function(e) {
            e.preventDefault();
            var video_frame;

            if ( video_frame ) {
                video_frame.open();
                return;
            }

            video_frame = wp.media({
                title: 'Choisissez une vidéo',
                button: {
                    text: 'Utiliser cette vidéo'
                },
                multiple: false
            });

            video_frame.on('select', function() {
                var attachment = video_frame.state().get('selection').first().toJSON();
                $('#product_video_file').val(attachment.url);
            });

            video_frame.open();
        });
    });
    </script>
    <?php
}

// Validation de l'URL vidéo
function wp_validate_video_url( $url ) {
    // Vérifie si l'URL provient de YouTube, Vimeo ou est un fichier MP4
    $youtube_pattern = "/^https?:\/\/(?:www\.)?(youtube\.com|youtu\.be)\/.+$/";
    $vimeo_pattern = "/^https?:\/\/(?:www\.)?vimeo\.com\/.+$/";
    $mp4_pattern = "/^https?:\/\/.+\.(mp4)$/";

    if ( preg_match( $youtube_pattern, $url ) || preg_match( $vimeo_pattern, $url ) || preg_match( $mp4_pattern, $url ) ) {
        return true;
    }

    return false;
}

// Sauvegarde de l'URL de la vidéo ou du fichier vidéo local
function wp_save_video_url( $post_id ) {
    if ( isset( $_POST['product_video_url'] ) ) {
        $video_url = sanitize_text_field( $_POST['product_video_url'] );

        // Valider l'URL vidéo avant de l'enregistrer
        if ( wp_validate_video_url( $video_url ) ) {
            update_post_meta( $post_id, '_product_video_url', $video_url );
        } else {
            add_filter('redirect_post_location', function( $location ) {
                return add_query_arg( 'video_url_error', 'invalid', $location );
            });
        }
    }

    // Sauvegarder le fichier vidéo local
    if ( isset( $_POST['product_video_file'] ) ) {
        $video_file = sanitize_text_field( $_POST['product_video_file'] );
        update_post_meta( $post_id, '_product_video_file', $video_file );
    }
}
add_action( 'save_post', 'wp_save_video_url' );

// Afficher un message d'erreur si l'URL vidéo est invalide
function wp_video_url_error_notice() {
    if ( isset( $_GET['video_url_error'] ) && $_GET['video_url_error'] == 'invalid' ) {
        echo '<div class="error notice"><p>L\'URL de la vidéo est invalide. Veuillez utiliser une URL de YouTube, Vimeo ou un fichier MP4.</p></div>';
    }
}
add_action( 'admin_notices', 'wp_video_url_error_notice' );

// Ajouter la vidéo dans la galerie de produit
function wp_add_video_to_gallery( $html, $attachment_id ) {
    global $post;

    // Récupérer l'URL de la vidéo ou le fichier vidéo local
    $video_url = get_post_meta( $post->ID, '_product_video_url', true );
    $video_file = get_post_meta( $post->ID, '_product_video_file', true );

    if ( $video_file || $video_url ) {
        // Insérer la vidéo comme un élément dans la galerie
        $video_html = '<li data-thumb="' . plugin_dir_url( __FILE__ ) . 'assets/video-thumb.png" class="woocommerce-product-gallery__image">';
        if ( $video_file ) {
            $video_html .= '<video controls><source src="' . esc_url( $video_file ) . '" type="video/mp4"></video>';
        } elseif ( $video_url ) {
            $video_html .= '<iframe width="560" height="315" src="' . esc_url( $video_url ) . '" frameborder="0" allowfullscreen></iframe>';
        }
        $video_html .= '</li>';

        // Ajouter la vidéo au début de la galerie
        return $video_html . $html;
    }

    return $html;
}
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'wp_add_video_to_gallery', 10, 2 );

// Enqueue le fichier CSS du plugin
function wp_enqueue_video_plugin_styles() {
    wp_enqueue_style( 'product-video-style', plugin_dir_url( __FILE__ ) . 'style.css' );
}
add_action( 'wp_enqueue_scripts', 'wp_enqueue_video_plugin_styles' );
