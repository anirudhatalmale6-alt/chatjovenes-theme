<footer class="site-footer">
    <div class="container">
        <div class="footer-columns">
            <div class="footer-col">
                <h4><?php bloginfo('name'); ?></h4>
                <p><?php bloginfo('description'); ?></p>
                <div class="social-icons">
                    <?php
                    $socials = array(
                        'facebook'  => 'F',
                        'twitter'   => 'X',
                        'instagram' => 'IG',
                        'youtube'   => 'YT',
                    );
                    foreach ($socials as $key => $icon) :
                        $url = get_theme_mod("social_$key", '');
                        if ($url) :
                    ?>
                        <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener" title="<?php echo esc_attr(ucfirst($key)); ?>"><?php echo $icon; ?></a>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>
            <div class="footer-col">
                <?php if (is_active_sidebar('footer-2')) : ?>
                    <?php dynamic_sidebar('footer-2'); ?>
                <?php else : ?>
                    <h4>Top Canales</h4>
                    <ul>
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
            <div class="footer-col">
                <?php if (is_active_sidebar('footer-3')) : ?>
                    <?php dynamic_sidebar('footer-3'); ?>
                <?php else : ?>
                    <h4>Ultimos Canales</h4>
                    <ul>
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
            <div class="footer-col">
                <?php if (is_active_sidebar('footer-4')) : ?>
                    <?php dynamic_sidebar('footer-4'); ?>
                <?php else : ?>
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="<?php echo esc_url(get_privacy_policy_url()); ?>">Politica de Privacidad</a></li>
                        <li><a href="#">Aviso Legal</a></li>
                        <li><a href="#">Politica de Cookies</a></li>
                        <li><a href="#">Contacto</a></li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Todos los derechos reservados.
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
