<?php
// Configuration de base du thème
function nathalie_mota_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    
    register_nav_menus(array(
        'main-menu' => __('Main Menu', 'nathalie-mota'),
        'footer-menu' => __('Footer Menu', 'nathalie-mota')
    ));
}
add_action('after_setup_theme', 'nathalie_mota_setup');

// Enregistrement des scripts et styles
function nathalie_mota_enqueue_scripts() {
    // Enregistrement des styles et scripts nécessaires
    wp_enqueue_style('normalize-css', get_template_directory_uri() . '/assets/css/normalize.css');

    wp_enqueue_style('main-css', get_template_directory_uri() . '/assets/css/styles.css');
    wp_enqueue_style('lightbox-css', get_template_directory_uri() . '/assets/css/lightbox.css');
    wp_enqueue_style('header-css', get_template_directory_uri() . '/assets/css/header.css'); 
    wp_enqueue_style('footer-css', get_template_directory_uri() . '/assets/css/footer.css'); 
    wp_enqueue_style('front-page-css', get_template_directory_uri() . '/assets/css/front-page.css'); 
    wp_enqueue_style('gallery-css', get_template_directory_uri() . '/assets/css/gallery.css'); 
    wp_enqueue_style('filters-css', get_template_directory_uri() . '/assets/css/filters.css'); 
    wp_enqueue_style('single-photo-css', get_template_directory_uri() . '/assets/css/single-photo.css'); 
    wp_enqueue_style('contact-css', get_template_directory_uri() . '/assets/css/contact.css'); 
    wp_enqueue_style('animations-css', get_template_directory_uri() . '/assets/css/animations.css'); 

    wp_enqueue_style('404-css', get_template_directory_uri() . '/assets/css/404.css'); 
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');

    wp_enqueue_script('jquery'); 
    wp_enqueue_script('custom-js', get_template_directory_uri() . '/assets/js/custom.js', array('jquery'), null, true);
    wp_enqueue_script('header-js', get_template_directory_uri() . '/assets/js/header.js', array('jquery'), null, true);
    wp_enqueue_script('contact-js', get_template_directory_uri() . '/assets/js/contact.js', array('jquery'), null, true);
    wp_enqueue_script('filters-js', get_template_directory_uri() . '/assets/js/filters.js', array(), null, true);
    wp_enqueue_script('single-photo-js', get_template_directory_uri() . '/assets/js/single-photo.js', array(), null, true);
    wp_enqueue_script('lightbox-js', get_template_directory_uri() . '/assets/js/lightbox.js', array(), null, true);

    // Localiser le script pour passer des données de PHP à JS
    wp_localize_script('contact-js', 'nathalie_mota_ajax', array(
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('submit_contact_form_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'nathalie_mota_enqueue_scripts');



// Enregistrement du Custom Post Type pour les photos et les taxonomies personnalisées
function nathalie_mota_custom_post_types() {
    register_post_type('photo', array(
        'label' => __('Photos', 'nathalie-mota'),
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'excerpt'),
        'taxonomies' => array('category', 'post_tag', 'format'),
        'rewrite' => array('slug' => 'photos'),
        'show_in_rest' => false, // Désactive Gutenberg
        'labels' => array(
            'name' => __('Photos', 'nathalie-mota'),
            'singular_name' => __('Photo', 'nathalie-mota'),
            'add_new' => __('Ajouter Nouvelle', 'nathalie-mota'),
            'add_new_item' => __('Ajouter Nouvelle Photo', 'nathalie-mota'),
            'edit_item' => __('Modifier Photo', 'nathalie-mota'),
            'new_item' => __('Nouvelle Photo', 'nathalie-mota'),
            'view_item' => __('Voir Photo', 'nathalie-mota'),
            'search_items' => __('Rechercher Photos', 'nathalie-mota'),
            'not_found' => __('Pas de Photos trouvées', 'nathalie-mota'),
            'not_found_in_trash' => __('Pas de Photos dans la corbeille', 'nathalie-mota'),
            'all_items' => __('Toutes les Photos', 'nathalie-mota'),
            'archives' => __('Archives des Photos', 'nathalie-mota'),
        ),
    ));

    register_taxonomy('format', 'photo', array(
        'label' => __('Formats', 'nathalie-mota'),
        'rewrite' => array('slug' => 'formats'),
        'hierarchical' => true,
    ));
}
add_action('init', 'nathalie_mota_custom_post_types');

// Désactiver Gutenberg pour le Custom Post Type 'photo'
function nathalie_mota_disable_gutenberg($current_status, $post_type) {
    if ($post_type === 'photo') return false;
    return $current_status;
}
add_filter('use_block_editor_for_post_type', 'nathalie_mota_disable_gutenberg', 10, 2);

// Ajouter une métabox pour les détails de la photo
function add_custom_meta_boxes() {
    add_meta_box(
        'photo_details',
        __('Photo Details', 'nathalie-mota'),
        'render_photo_details_meta_box',
        'photo',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_custom_meta_boxes');

function render_photo_details_meta_box($post) {
    wp_nonce_field('save_photo_details', 'photo_details_nonce');
    $date = get_post_meta($post->ID, '_photo_date', true);
    $reference = get_post_meta($post->ID, '_photo_reference', true);
    $type = get_post_meta($post->ID, '_photo_type', true); // Ajouter le champ personnalisé "type"
    ?>
    <p>
        <label for="photo_date"><?php _e('Date de Prise de Vue', 'nathalie-mota'); ?></label>
        <input type="date" id="photo_date" name="photo_date" value="<?php echo esc_attr($date); ?>" />
    </p>
    <p>
        <label for="photo_reference"><?php _e('Référence Photo', 'nathalie-mota'); ?></label>
        <input type="text" id="photo_reference" name="photo_reference" value="<?php echo esc_attr($reference); ?>" />
    </p>
    <p>
        <label for="photo_type"><?php _e('Type', 'nathalie-mota'); ?></label>
        <input type="text" id="photo_type" name="photo_type" value="<?php echo esc_attr($type); ?>" />
    </p>
    <?php
}

// Fonction pour afficher les photos
function render_photo_html($photos) {
    while ($photos->have_posts()) : $photos->the_post();
        $categories = get_the_terms(get_the_ID(), 'category');
        $category_names = wp_list_pluck($categories, 'name');
        $full_image_url = wp_get_attachment_url(get_post_thumbnail_id());
        $image_meta = wp_get_attachment_metadata(get_post_thumbnail_id());
        $original_width = $image_meta['width'];
        $original_height = $image_meta['height'];
        $reference = get_post_meta(get_the_ID(), '_photo_reference', true);
        ?>
        <div class="photo-item" 
             data-full-image="<?php echo esc_attr($full_image_url); ?>" 
             data-reference="<?php echo esc_attr($reference); ?>" 
             data-category="<?php echo esc_attr(implode(', ', $category_names)); ?>"
             data-original-width="<?php echo esc_attr($original_width); ?>"
             data-original-height="<?php echo esc_attr($original_height); ?>">
            <a href="<?php the_permalink(); ?>" class="photo-link">
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('medium_large'); ?>
                <?php else : ?>
                    <?php _e('No image', 'nathalie-mota'); ?>
                <?php endif; ?>
            </a>
            <div class="photo-overlay">
                <a href="<?php the_permalink(); ?>" class="icon eye"><i class="fa fa-eye"></i></a>
                <div class="icon fullscreen"><i class="fa fa-expand"></i></div>
                <div class="photo-info">
                    <span class="photo-reference"><?php echo esc_attr($reference); ?></span>
                    <span class="photo-category"><?php echo esc_attr(implode(', ', $category_names)); ?></span>
                </div>
            </div>
        </div>
        <?php
    endwhile;
    wp_reset_postdata();
}

 
function save_photo_details($post_id) {
    if (!isset($_POST['photo_details_nonce']) || !wp_verify_nonce($_POST['photo_details_nonce'], 'save_photo_details')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['photo_date'])) {
        update_post_meta($post_id, '_photo_date', sanitize_text_field($_POST['photo_date']));
    }
    if (isset($_POST['photo_reference'])) {
        update_post_meta($post_id, '_photo_reference', sanitize_text_field($_POST['photo_reference']));
    }
    if (isset($_POST['photo_type'])) { // Sauvegarder le champ personnalisé "type"
        update_post_meta($post_id, 'Type', sanitize_text_field($_POST['photo_type']));
    }
}

add_action('save_post', 'save_photo_details');


/// Enregistrement des scripts AJAX pour les filtres et la pagination
function nathalie_mota_ajax_scripts() {
    wp_localize_script('custom-js', 'nathalie_mota_ajax', array(
        'url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'nathalie_mota_ajax_scripts');

// Filtrer les photos via AJAX
function filter_photos() {
    error_log('Executing filter_photos');
    try {
        $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
        $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
        $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'DESC';

        $args = array(
            'post_type' => 'photo',
            'posts_per_page' => 8,
            'orderby' => 'date',
            'order' => $order,
            'tax_query' => array(
                'relation' => 'AND',
            ),
        );

        if ($category && $category != 'all') {
            $args['tax_query'][] = array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $category,
            );
        }

        if ($format && $format != 'all') {
            $args['tax_query'][] = array(
                'taxonomy' => 'format',
                'field' => 'slug',
                'terms' => $format,
            );
        }

        $photos = new WP_Query($args);
        $total_photos = $photos->found_posts;

        ob_start();
        render_photo_html($photos);
        $html = ob_get_clean();

        echo json_encode(array(
            'html' => $html,
            'total' => $total_photos,
        ));
    } catch (Exception $e) {
        echo json_encode(array(
            'error' => $e->getMessage(),
        ));
    }

    wp_die();
}

add_action('wp_ajax_filter_photos', 'filter_photos');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos');

// Charger plus de photos via AJAX
function load_more_photos() {
    error_log('Executing load_more_photos');
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
    $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'DESC';

    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 8,
        'offset' => $offset,
        'orderby' => 'date',
        'order' => $order,
        'tax_query' => array(
            'relation' => 'AND',
        ),
    );

    if ($category && $category != 'all') {
        $args['tax_query'][] = array(
            'taxonomy' => 'category',
            'field' => 'slug',
            'terms' => $category,
        );
    }

    if ($format && $format != 'all') {
        $args['tax_query'][] = array(
            'taxonomy' => 'format',
            'field' => 'slug',
            'terms' => $format,
        );
    }

    $photos = new WP_Query($args);
    $loaded_photos = $photos->post_count;

    ob_start();
    render_photo_html($photos);
    $html = ob_get_clean();

    echo json_encode(array(
        'html' => $html,
        'loaded' => $loaded_photos,
    ));

    wp_die();
}

add_action('wp_ajax_load_more_photos', 'load_more_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');




// Supprimer la catégorie "Uncategorized" et exclure la catégorie "General" des sélecteurs personnalisés
function remove_uncategorized_category() {
    $uncategorized_id = get_cat_ID('Uncategorized');
    if ($uncategorized_id) {
        wp_delete_term($uncategorized_id, 'category');
    }
}
add_action('init', 'remove_uncategorized_category');

// Exclure "Uncategorized" et "General" des sélecteurs personnalisés
function exclude_uncategorized_and_general_term($terms, $taxonomies, $args) {
    if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
        foreach ($terms as $key => $term) {
            if (is_object($term) && ($term->slug == 'uncategorized' || $term->slug == 'general')) {
                unset($terms[$key]);
            }
        }
    }
    return $terms;
}
add_filter('get_terms', 'exclude_uncategorized_and_general_term', 10, 3);

// Permettre la suppression des termes de taxonomie dans les Custom Post Types
function allow_term_deletion() {
    global $wp_taxonomies;
    foreach ($wp_taxonomies as $taxonomy => $object) {
        if (in_array('photo', $object->object_type)) {
            $wp_taxonomies[$taxonomy]->public = true;
        }
    }
}
add_action('init', 'allow_term_deletion');

// Page d'options pour gérer les termes personnalisés
function add_taxonomy_management_page() {
    add_menu_page(
        __('Gestion des Taxonomies', 'nathalie-mota'),
        __('Gestion des Taxonomies', 'nathalie-mota'),
        'manage_options',
        'taxonomy-management',
        'render_taxonomy_management_page'
    );
}
add_action('admin_menu', 'add_taxonomy_management_page');

function render_taxonomy_management_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Gestion des Taxonomies', 'nathalie-mota'); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('delete_terms_nonce', 'delete_terms_nonce_field'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Supprimer un terme de catégorie', 'nathalie-mota'); ?></th>
                    <td>
                        <select name="term_id">
                            <?php
                            $terms = get_terms(array('taxonomy' => 'category', 'hide_empty' => false));
                            foreach ($terms as $term) {
                                if ($term->slug != 'uncategorized' && $term->slug != 'general') {
                                    echo '<option value="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(__('Supprimer', 'nathalie-mota')); ?>
        </form>
        <?php
        if (isset($_POST['delete_terms_nonce_field']) && wp_verify_nonce($_POST['delete_terms_nonce_field'], 'delete_terms_nonce')) {
            $term_id = intval($_POST['term_id']);
            wp_delete_term($term_id, 'category');
            echo '<div class="updated"><p>' . __('Terme supprimé.', 'nathalie-mota') . '</p></div>';
        }
        ?>
    </div>
    <?php
}

// Customizer pour ajouter une image dans la section hero
function nathalie_mota_customizer_register($wp_customize) {
    // Ajouter une section pour la photo du hero
    $wp_customize->add_section('hero_section', array(
        'title'    => __('Hero Image', 'nathalie-mota'),
        'priority' => 30,
    ));

    // Ajouter un paramètre pour l'image
    $wp_customize->add_setting('hero_image', array(
        'default'   => '',
        'transport' => 'refresh',
    ));

    // Ajouter un contrôle pour l'image
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_image', array(
        'label'    => __('Upload Hero Image', 'nathalie-mota'),
        'section'  => 'hero_section',
        'settings' => 'hero_image',
    )));
}
add_action('customize_register', 'nathalie_mota_customizer_register');

// Récupèrer les articles précédents et suivants en fonction de la date de prise de vue
function get_all_photos_sorted_by_date() {
    global $wpdb;

    $query = "
        SELECT p.ID
        FROM {$wpdb->posts} p
        JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
        WHERE p.post_type = 'photo'
          AND p.post_status = 'publish'
          AND pm.meta_key = '_photo_date'
        ORDER BY pm.meta_value ASC, p.ID ASC";

    return $wpdb->get_results($query);
}

// Obtenir l'index de la photo actuelle
function get_current_photo_index($current_post_id, $photos) {
    foreach ($photos as $index => $photo) {
        if ($photo->ID == $current_post_id) {
            return $index;
        }
    }
    return -1; // Indice non trouvé
}


// Formulaire de contact
function submit_contact_form() {
    // Vérifier les permissions
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'submit_contact_form_nonce')) {
        error_log('Nonce verification failed.');
        wp_send_json_error(array('message' => 'Nonce verification failed.'));
        return;
    }

    // Récupérer les données du formulaire
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $photo_reference = isset($_POST['photo_reference']) ? sanitize_text_field($_POST['photo_reference']) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

    error_log('Name: ' . $name);
    error_log('Email: ' . $email);
    error_log('Photo Reference: ' . $photo_reference);
    error_log('Message: ' . $message);

    // Valider les données
    if (empty($name) || empty($email) || empty($message)) {
        error_log('Missing required fields.');
        wp_send_json_error(array('message' => 'Veuillez remplir tous les champs obligatoires.'));
        return;
    }

    // Valider la référence de la photo si elle est fournie
    if (!empty($photo_reference)) {
        $photo_query = new WP_Query(array(
            'post_type' => 'photo',
            'meta_query' => array(
                array(
                    'key' => '_photo_reference',
                    'value' => $photo_reference,
                    'compare' => '='
                )
            )
        ));

        if (!$photo_query->have_posts()) {
            error_log('Invalid photo reference.');
            wp_send_json_error(array('message' => 'La référence de la photo est invalide.'));
            return;
        }
    }

    // Envoyer un e-mail à l'administrateur
    $to_admin = get_option('admin_email');
    $subject_admin = "Nouveau message de $name";
    $body_admin = "Nom: $name\nEmail: $email\nRéférence Photo: $photo_reference\nMessage: $message";
    $headers = array('Content-Type: text/plain; charset=UTF-8');

    $admin_email_sent = wp_mail($to_admin, $subject_admin, $body_admin, $headers);

    // Envoyer un e-mail de confirmation à l'utilisateur
    $to_user = $email;
    $subject_user = "Confirmation de réception de votre message";
    $body_user = "Bonjour $name,\n\nMerci pour votre message. Nous avons bien reçu votre demande et nous vous recontacterons sous peu.\n\nCordialement,\nNathalie Mota.";
    $user_email_sent = wp_mail($to_user, $subject_user, $body_user, $headers);

    if ($admin_email_sent && $user_email_sent) {
        error_log('Emails sent successfully.');
        wp_send_json_success(array('message' => 'Votre message a bien été envoyé. Vous allez recevoir un e-mail de confirmation.'));
    } else {
        error_log('Failed to send email.');
        wp_send_json_error(array('message' => 'Une erreur est survenue lors de l\'envoi de votre message.'));
    }
}
add_action('wp_ajax_submit_contact_form', 'submit_contact_form');
add_action('wp_ajax_nopriv_submit_contact_form', 'submit_contact_form');

// Permettre le téléchargement de fichiers SVG
function add_svg_to_upload_mimes($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'add_svg_to_upload_mimes');



?>
