
.cart-icon {
    position: fixed;
    bottom: 15px;
    right: 15px;
    background: #ffffff00;
    padding: 5px;
    z-index: 999;
    width: 50px;
}

.cart-count {
    background: red;
    color: white;
    font-size: 14px;
    font-weight: bold;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: -5px;
    right: -5px;
}


function autoriser_svg($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'autoriser_svg');


function custom_cart_icon_with_count() {
    $cart_count = WC()->cart->get_cart_contents_count();
    $cart_url = wc_get_cart_url();
    $icon_url = 'https://env-jozzbeautycom-jozzppdprem.kinsta.cloud/wp-content/uploads/2025/02/pannier.svg';
    ?>
    <a class="cart-icon" href="<?php echo esc_url($cart_url); ?>">
        <img src="<?php echo esc_url($icon_url); ?>" alt="Panier">
        <span class="cart-count"><?php echo esc_html($cart_count); ?></span>
    </a>
    <?php
}
add_action('wp_footer', 'custom_cart_icon_with_count');



