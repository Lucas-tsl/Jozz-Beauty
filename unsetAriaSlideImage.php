function enlever_aria_hidden() {
    ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Créer un observer qui surveille les changements dans le DOM
        const observer = new MutationObserver(() => {
            for (let i = 1; i <= 5; i++) {
                let slide = document.querySelector(`.slick-slide[data-slick-index="${i}"]`);
                if (slide && slide.getAttribute("aria-hidden") !== "false") {
                    slide.setAttribute("aria-hidden", "false");
                }
            }
        });

        // Configurer l'observateur pour surveiller les changements dans le DOM
        observer.observe(document.body, {
            childList: true, 
            subtree: true
        });

        // Optionnellement, vous pouvez arrêter l'observateur après un certain temps :
        setTimeout(() => observer.disconnect(), 5000); // Arrêter l'observateur après 5 secondes
    });
    </script>
    <?php
}
add_action('wp_footer', 'enlever_aria_hidden');
