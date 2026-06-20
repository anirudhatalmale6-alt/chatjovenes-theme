<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="header-inner">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
            <?php if (has_custom_logo()) : ?>
                <?php
                $logo_id = get_theme_mod('custom_logo');
                $logo_url = wp_get_attachment_image_url($logo_id, 'full');
                ?>
                <img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>">
            <?php else : ?>
                <span><?php bloginfo('name'); ?></span>
            <?php endif; ?>
        </a>

        <button class="menu-toggle" aria-label="Menu" onclick="document.querySelector('.main-nav').classList.toggle('active')">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="main-nav">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container'      => false,
                'fallback_cb'    => 'chatjovenes_fallback_menu',
            ));
            ?>
        </nav>
    </div>
</header>
<?php
function chatjovenes_fallback_menu() {
    $categories = get_terms(array(
        'taxonomy'   => 'room_category',
        'hide_empty' => false,
        'number'     => 8,
    ));
    echo '<ul>';
    echo '<li><a href="' . esc_url(home_url('/')) . '">Inicio</a></li>';
    if (!is_wp_error($categories) && !empty($categories)) {
        foreach ($categories as $cat) {
            echo '<li><a href="' . esc_url(get_term_link($cat)) . '">' . esc_html($cat->name) . '</a></li>';
        }
    }
    echo '</ul>';
}
?>
