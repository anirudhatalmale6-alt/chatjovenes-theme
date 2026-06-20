<!-- ABOUT TEXT ABOVE FOOTER (only on homepage) -->
<?php if (is_front_page()) : ?>
<section class="footer-about">
    <div class="container">
        <p>ChatJovenes es una comunidad de chat en español totalmente gratis, formada por usuarios de todas partes de España, Mexico, Argentina, Chile y toda Latinoamerica en busca de nuevas amistades.</p>
        <p>Chatear, conocer gente, ligar y hacer nuevos amigos, asi como los multiples servicios que te ofrecemos (registro de nicks, crear tu propia sala de chat, etc) son totalmente gratuitos.</p>
        <p>Nuestro webchat, en constante evolucion, te permitira chatear comodamente sin registro, de una forma facil y clara. Con solo registrarte podras subir tus fotos y videos favoritos, y crear una pagina totalmente personalizada sobre ti y tu comunidad.</p>
        <p>En resumen, ChatJovenes te ofrece un chat gratis para ti y para tus amigos, para que podais chatear de forma facil desde nuestra web, o incluir ese chat en la vuestra propia, para chatear en la red que mas ha crecido en los ultimos años, con el mejor ambiente, y la mejor gente.</p>
    </div>
</section>
<?php endif; ?>

<footer class="site-footer">
    <div class="container">
        <div class="footer-columns">
            <div class="footer-col footer-col-channels">
                <?php if (is_active_sidebar('footer-1')) : ?>
                    <?php dynamic_sidebar('footer-1'); ?>
                <?php else : ?>
                    <h4>Top Canales</h4>
                    <ul class="footer-icon-list">
                        <?php
                        $top_rooms = new WP_Query(array(
                            'post_type'      => 'chat_room',
                            'posts_per_page' => 6,
                            'orderby'        => 'comment_count',
                            'order'          => 'DESC',
                        ));
                        if ($top_rooms->have_posts()) :
                            while ($top_rooms->have_posts()) : $top_rooms->the_post();
                                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="footer-col footer-col-channels">
                <?php if (is_active_sidebar('footer-2')) : ?>
                    <?php dynamic_sidebar('footer-2'); ?>
                <?php else : ?>
                    <h4>Ultimos Canales</h4>
                    <ul class="footer-icon-list">
                        <?php
                        $latest_rooms = new WP_Query(array(
                            'post_type'      => 'chat_room',
                            'posts_per_page' => 6,
                            'orderby'        => 'date',
                            'order'          => 'DESC',
                        ));
                        if ($latest_rooms->have_posts()) :
                            while ($latest_rooms->have_posts()) : $latest_rooms->the_post();
                                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="footer-col footer-col-corp">
                <?php if (is_active_sidebar('footer-3')) : ?>
                    <?php dynamic_sidebar('footer-3'); ?>
                <?php else : ?>
                    <h4>Corporativo</h4>
                    <ul class="footer-check-list">
                        <li><a href="#">Historia</a></li>
                        <li><a href="#">Equipo</a></li>
                        <li><a href="#">Registro</a></li>
                        <li><a href="#">Crea tu chat</a></li>
                        <li><a href="#">Contacto</a></li>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="footer-col footer-col-legal">
                <?php if (is_active_sidebar('footer-4')) : ?>
                    <?php dynamic_sidebar('footer-4'); ?>
                <?php else : ?>
                    <h4>Legal</h4>
                    <ul class="footer-check-list">
                        <li><a href="#">Aviso Legal</a></li>
                        <li><a href="<?php echo esc_url(get_privacy_policy_url()); ?>">Politica de Privacidad</a></li>
                        <li><a href="#">Politica de Cookies</a></li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer-bottom">
            Copyright &copy; 2025-2026 Chatea desde <?php bloginfo('name'); ?> - Todos los derechos reservados.
        </div>
    </div>
</footer>

<?php if (get_theme_mod('enable_dark_toggle', true)) : ?>
<button class="dark-mode-toggle" id="darkModeToggle" aria-label="Cambiar modo oscuro" title="Modo Oscuro / Claro">&#9790;</button>
<script>
(function(){
    var btn = document.getElementById('darkModeToggle');
    var body = document.body;
    var defaultDark = <?php echo get_theme_mod('default_dark_mode', false) ? 'true' : 'false'; ?>;
    var saved = localStorage.getItem('chatjovenes_dark');
    if (saved === 'true' || (saved === null && defaultDark)) {
        body.classList.add('dark-mode');
        btn.innerHTML = '&#9788;';
    }
    btn.addEventListener('click', function(){
        body.classList.toggle('dark-mode');
        var isDark = body.classList.contains('dark-mode');
        localStorage.setItem('chatjovenes_dark', isDark);
        btn.innerHTML = isDark ? '&#9788;' : '&#9790;';
    });
})();
</script>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
