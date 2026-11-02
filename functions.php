<?php

add_action( 'wp_enqueue_scripts', 'wpm_enqueue_styles' );

function wpm_enqueue_styles() {
	wp_enqueue_style( 
		'enfant-style', 
		get_stylesheet_uri()
	);
}

/**
 * Fonction utilitaire pour mutualiser la navigation circulaire
 */
function get_circular_post_link($output, $order, $rel, $label) {
    if (!empty($output)) {
        return $output;
    }

    $posts = get_posts(array(
        'posts_per_page' => 1,
        'orderby'        => 'date',
        'order'          => $order,
        'post_type'      => get_post_type(),
    ));

    if (empty($posts)) {
        return $output;
    }

    return sprintf(
        '<a href="%s" rel="%s">%s</a>',
        esc_url(get_permalink($posts[0]->ID)),
        $rel,
        $label
    );
}

// 1. Filtre Précédent (cherche le dernier article si vide)
add_filter('previous_post_link', function($output) {
    return get_circular_post_link($output, 'DESC', 'prev', 'Projet précédent');
});

// 2. Filtre Suivant (cherche le premier article si vide)
add_filter('next_post_link', function($output) {
    return get_circular_post_link($output, 'ASC', 'next', 'Projet suivant');
});

/**
 * Style pour boîte info Ultimate Maps
 */
function kadence_child_ultimate_maps_customizations() {
    ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) {
                        const popup = node.classList.contains('leaflet-popup-content-wrapper') 
                            ? node 
                            : node.querySelector('.leaflet-popup-content-wrapper');
                        
                        if (popup && !popup.classList.contains('style-traite')) {
                            
                            // 1. Nettoyage des antislashs intempestifs
                            let htmlContenu = popup.innerHTML;
                            while (/\\+(['"])/.test(htmlContenu)) {
                                htmlContenu = htmlContenu.replace(/\\+(['"])/g, '$1');
                            }
                            popup.innerHTML = htmlContenu;
                            
                            // 2. Analyse du texte pour appliquer le bon style
                            const texte = popup.innerText.toLowerCase();
                            
                            if (texte.includes('installation') || texte.includes('urbaine')) {
                                popup.classList.add('dispositif-installation-urbaine');
                            } else if (texte.includes('usage') || texte.includes('agricole')) {
                                popup.classList.add('dispositif-usage-agricole');
                            }
                            
                            popup.classList.add('style-traite');
                        }
                    }
                });
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'kadence_child_ultimate_maps_customizations');