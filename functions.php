<?php
/**
 * ChatJovenes Theme Functions
 */

if (!defined('ABSPATH')) exit;

// License validation
function chatjovenes_check_license() {
    $allowed_domains = get_option('chatjovenes_licensed_domains', array());
    $current_domain = str_replace('www.', '', parse_url(home_url(), PHP_URL_HOST));
    if (!empty($allowed_domains) && !in_array($current_domain, $allowed_domains)) {
        return false;
    }
    return true;
}

// Theme setup
function chatjovenes_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('automatic-feed-links');

    add_image_size('room-thumbnail', 400, 250, true);
    add_image_size('category-thumbnail', 300, 200, true);
    add_image_size('news-thumbnail', 400, 220, true);

    register_nav_menus(array(
        'primary' => 'Menu Principal',
        'footer'  => 'Menu Footer',
    ));
}
add_action('after_setup_theme', 'chatjovenes_setup');

// Enqueue styles and scripts
function chatjovenes_enqueue() {
    wp_enqueue_style('chatjovenes-style', get_stylesheet_uri(), array(), '1.0.0');
    wp_enqueue_script('chatjovenes-script', get_template_directory_uri() . '/js/main.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'chatjovenes_enqueue');

// Register sidebars
function chatjovenes_widgets() {
    register_sidebar(array(
        'name'          => 'Sidebar',
        'id'            => 'sidebar-1',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    register_sidebar(array(
        'name'          => 'Footer 1',
        'id'            => 'footer-1',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ));
    register_sidebar(array(
        'name'          => 'Footer 2',
        'id'            => 'footer-2',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ));
    register_sidebar(array(
        'name'          => 'Footer 3',
        'id'            => 'footer-3',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ));
    register_sidebar(array(
        'name'          => 'Footer 4',
        'id'            => 'footer-4',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'chatjovenes_widgets');

// Custom Post Type: Chat Rooms
function chatjovenes_register_cpt() {
    register_post_type('chat_room', array(
        'labels' => array(
            'name'               => 'Salas de Chat',
            'singular_name'      => 'Sala de Chat',
            'add_new'            => 'Agregar Sala',
            'add_new_item'       => 'Agregar Nueva Sala',
            'edit_item'          => 'Editar Sala',
            'new_item'           => 'Nueva Sala',
            'view_item'          => 'Ver Sala',
            'search_items'       => 'Buscar Salas',
            'not_found'          => 'No se encontraron salas',
            'not_found_in_trash' => 'No se encontraron salas en la papelera',
        ),
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'sala'),
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon'    => 'dashicons-format-chat',
        'show_in_rest' => true,
    ));

    register_taxonomy('room_category', 'chat_room', array(
        'labels' => array(
            'name'          => 'Categorias',
            'singular_name' => 'Categoria',
            'add_new_item'  => 'Agregar Categoria',
            'search_items'  => 'Buscar Categorias',
        ),
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => array('slug' => 'categoria'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'chatjovenes_register_cpt');

// Chat Room meta boxes
function chatjovenes_room_meta_boxes() {
    add_meta_box(
        'chatjovenes_room_details',
        'Detalles de la Sala',
        'chatjovenes_room_meta_callback',
        'chat_room',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'chatjovenes_room_meta_boxes');

function chatjovenes_room_meta_callback($post) {
    wp_nonce_field('chatjovenes_room_meta', 'chatjovenes_room_nonce');
    $xat_id = get_post_meta($post->ID, '_xat_chat_id', true);
    $users_online = get_post_meta($post->ID, '_users_online', true);
    $featured = get_post_meta($post->ID, '_featured_room', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="xat_chat_id">ID del Chat xat.com</label></th>
            <td>
                <input type="text" id="xat_chat_id" name="xat_chat_id" value="<?php echo esc_attr($xat_id); ?>" class="regular-text">
                <p class="description">El ID o nombre del grupo de xat.com para esta sala</p>
            </td>
        </tr>
        <tr>
            <th><label for="users_online">Usuarios en linea</label></th>
            <td>
                <input type="number" id="users_online" name="users_online" value="<?php echo esc_attr($users_online); ?>" class="small-text">
                <p class="description">Numero estimado de usuarios (se muestra en la tarjeta)</p>
            </td>
        </tr>
        <tr>
            <th><label for="featured_room">Sala Destacada</label></th>
            <td>
                <label>
                    <input type="checkbox" id="featured_room" name="featured_room" value="1" <?php checked($featured, '1'); ?>>
                    Mostrar en la pagina de inicio
                </label>
            </td>
        </tr>
    </table>
    <?php
}

function chatjovenes_save_room_meta($post_id) {
    if (!isset($_POST['chatjovenes_room_nonce']) || !wp_verify_nonce($_POST['chatjovenes_room_nonce'], 'chatjovenes_room_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['xat_chat_id'])) {
        update_post_meta($post_id, '_xat_chat_id', sanitize_text_field($_POST['xat_chat_id']));
    }
    if (isset($_POST['users_online'])) {
        update_post_meta($post_id, '_users_online', intval($_POST['users_online']));
    }
    update_post_meta($post_id, '_featured_room', isset($_POST['featured_room']) ? '1' : '0');
}
add_action('save_post_chat_room', 'chatjovenes_save_room_meta');

// Theme Customizer
function chatjovenes_customizer($wp_customize) {
    // Hero Section
    $wp_customize->add_section('chatjovenes_hero', array(
        'title'    => 'Seccion Hero',
        'priority' => 30,
    ));

    $wp_customize->add_setting('hero_title', array(
        'default'           => 'Bienvenido a ChatJovenes',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_title', array(
        'label'   => 'Titulo Hero',
        'section' => 'chatjovenes_hero',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('hero_subtitle', array(
        'default'           => 'Conecta con personas de todo el mundo hispano',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_subtitle', array(
        'label'   => 'Subtitulo Hero',
        'section' => 'chatjovenes_hero',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('hero_button_text', array(
        'default'           => 'Conectar',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_button_text', array(
        'label'   => 'Texto del Boton',
        'section' => 'chatjovenes_hero',
        'type'    => 'text',
    ));

    // Xat Chat Settings
    $wp_customize->add_section('chatjovenes_xat', array(
        'title'    => 'Chat xat.com',
        'priority' => 35,
    ));

    $wp_customize->add_setting('xat_group_id', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('xat_group_id', array(
        'label'       => 'ID del Grupo xat',
        'description' => 'El ID numerico de tu grupo en xat.com',
        'section'     => 'chatjovenes_xat',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('xat_show_homepage', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('xat_show_homepage', array(
        'label'   => 'Mostrar chat en la pagina de inicio',
        'section' => 'chatjovenes_xat',
        'type'    => 'checkbox',
    ));

    // Colors
    $wp_customize->add_setting('primary_color', array(
        'default'           => '#2563eb',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
        'label'   => 'Color Primario',
        'section' => 'colors',
    )));

    $wp_customize->add_setting('accent_color', array(
        'default'           => '#f97316',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color', array(
        'label'   => 'Color de Acento',
        'section' => 'colors',
    )));

    // Social Media
    $wp_customize->add_section('chatjovenes_social', array(
        'title'    => 'Redes Sociales',
        'priority' => 40,
    ));

    foreach (array('facebook' => 'Facebook', 'twitter' => 'Twitter/X', 'instagram' => 'Instagram', 'youtube' => 'YouTube') as $key => $label) {
        $wp_customize->add_setting("social_$key", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control("social_$key", array(
            'label'   => $label . ' URL',
            'section' => 'chatjovenes_social',
            'type'    => 'url',
        ));
    }

    // License
    $wp_customize->add_section('chatjovenes_license', array(
        'title'    => 'Licencia',
        'priority' => 200,
    ));

    $wp_customize->add_setting('license_domains', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('license_domains', array(
        'label'       => 'Dominios Autorizados',
        'description' => 'Un dominio por linea (sin www). Dejar vacio para sin restriccion.',
        'section'     => 'chatjovenes_license',
        'type'        => 'textarea',
    ));
}
add_action('customize_register', 'chatjovenes_customizer');

// Dynamic CSS from customizer
function chatjovenes_dynamic_css() {
    $primary = get_theme_mod('primary_color', '#2563eb');
    $accent = get_theme_mod('accent_color', '#f97316');
    ?>
    <style>
        :root {
            --primary: <?php echo esc_attr($primary); ?>;
            --accent: <?php echo esc_attr($accent); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'chatjovenes_dynamic_css');

// Excerpt length
function chatjovenes_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'chatjovenes_excerpt_length');

function chatjovenes_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'chatjovenes_excerpt_more');

// Save licensed domains from customizer
function chatjovenes_save_license_domains($value) {
    $domains = array_filter(array_map('trim', explode("\n", $value)));
    update_option('chatjovenes_licensed_domains', $domains);
    return $value;
}
add_filter('pre_set_theme_mod_license_domains', 'chatjovenes_save_license_domains');
