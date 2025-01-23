function custom_hide_empty_category_list() {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoryList = document.querySelector('.wp-block-group.alignfull'); // Sélectionnez le bloc global
            const categoryNames = categoryList.querySelectorAll('.wc-block-product-categories-list-item__name');
            
            // Vérifiez s'il y a des catégories visibles
            if (!categoryNames || categoryNames.length === 0) {
                categoryList.style.display = 'none'; // Masque le bloc si aucune catégorie n'existe
            }
        });
    </script>
    <?php
}
add_action('wp_footer', 'custom_hide_empty_category_list');
