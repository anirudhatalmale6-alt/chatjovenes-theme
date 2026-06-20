<?php get_header(); ?>

<main>
    <?php if (is_front_page()) : ?>

        <!-- HERO SECTION -->
        <section class="hero-section">
            <div class="container">
                <h1><?php echo esc_html(get_theme_mod('hero_title', 'Bienvenido a ' . get_bloginfo('name'))); ?></h1>
                <p><?php echo esc_html(get_theme_mod('hero_subtitle', 'Conecta con personas de todo el mundo hispano')); ?></p>
                <form class="connect-form" action="<?php echo esc_url(home_url('/')); ?>" method="get">
                    <input type="text" name="nickname" placeholder="Escribe tu nick..." required>
                    <button type="submit" class="btn-connect"><?php echo esc_html(get_theme_mod('hero_button_text', 'Conectar')); ?></button>
                </form>
            </div>
        </section>

        <!-- FEATURED CHAT ROOMS -->
        <?php
        $featured_rooms = new WP_Query(array(
            'post_type'      => 'chat_room',
            'posts_per_page' => 8,
            'meta_query'     => array(
                array(
                    'key'   => '_featured_room',
                    'value' => '1',
                ),
            ),
        ));

        if (!$featured_rooms->have_posts()) {
            $featured_rooms = new WP_Query(array(
                'post_type'      => 'chat_room',
                'posts_per_page' => 8,
            ));
        }

        if ($featured_rooms->have_posts()) :
        ?>
        <section class="rooms-section">
            <div class="container">
                <h2 class="section-title">Salas Recomendadas</h2>
                <p class="section-subtitle">Las salas mas populares de nuestra comunidad</p>
                <div class="rooms-grid">
                    <?php while ($featured_rooms->have_posts()) : $featured_rooms->the_post(); ?>
                    <article class="room-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('room-thumbnail', array('class' => 'room-card-image')); ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php the_permalink(); ?>">
                                <div class="room-card-image" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; font-weight: 700;"><?php echo esc_html(mb_substr(get_the_title(), 0, 2)); ?></div>
                            </a>
                        <?php endif; ?>
                        <div class="room-card-body">
                            <h3 class="room-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <?php if (has_excerpt()) : ?>
                                <p class="room-card-desc"><?php echo esc_html(get_the_excerpt()); ?></p>
                            <?php endif; ?>
                            <div class="room-card-meta">
                                <?php
                                $users = get_post_meta(get_the_ID(), '_users_online', true);
                                if ($users) :
                                ?>
                                    <span class="users-online"><?php echo intval($users); ?> en linea</span>
                                <?php endif; ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="room-card-btn" style="margin-top: 12px;">Entrar</a>
                        </div>
                    </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- CATEGORIES -->
        <?php
        $categories = get_terms(array(
            'taxonomy'   => 'room_category',
            'hide_empty' => false,
        ));
        if (!is_wp_error($categories) && !empty($categories)) :
        ?>
        <section class="categories-section">
            <div class="container">
                <h2 class="section-title">Nuestras Categorias</h2>
                <p class="section-subtitle">Encuentra la sala perfecta para ti</p>
                <div class="categories-grid">
                    <?php foreach ($categories as $cat) :
                        $cat_image = get_term_meta($cat->term_id, 'category_image', true);
                    ?>
                    <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="category-card">
                        <?php if ($cat_image) : ?>
                            <img src="<?php echo esc_url($cat_image); ?>" alt="<?php echo esc_attr($cat->name); ?>" class="category-card-image">
                        <?php else : ?>
                            <div class="category-card-image" style="background: linear-gradient(135deg, var(--primary-light), var(--primary)); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 28px; font-weight: 700;"><?php echo esc_html(mb_substr($cat->name, 0, 2)); ?></div>
                        <?php endif; ?>
                        <div class="category-card-body">
                            <h3 class="category-card-title"><?php echo esc_html($cat->name); ?></h3>
                            <p class="category-card-count"><?php echo $cat->count; ?> salas</p>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- XAT CHAT EMBED -->
        <?php
        $xat_embed = get_theme_mod('xat_embed_code', '');
        $xat_id = get_theme_mod('xat_group_id', '');
        $show_chat = get_theme_mod('xat_show_homepage', true);
        if (($xat_embed || $xat_id) && $show_chat) :
        ?>
        <section class="chat-embed-section">
            <div class="container">
                <h2 class="section-title">Chat en Vivo</h2>
                <p class="section-subtitle">Unete a la conversacion ahora mismo</p>
                <div class="chat-embed-wrapper">
                    <?php if ($xat_embed) : ?>
                        <?php echo $xat_embed; ?>
                    <?php else : ?>
                        <iframe src="https://xat.com/web_gear/chat/go_large.php?id=<?php echo esc_attr($xat_id); ?>" allowfullscreen scrolling="no"></iframe>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- TOP & LATEST CHANNELS -->
        <section class="channels-section">
            <div class="container">
                <div class="channels-columns">
                    <div class="channels-column">
                        <h3>Top Canales</h3>
                        <ul class="channels-list">
                            <?php
                            $top = new WP_Query(array(
                                'post_type'      => 'chat_room',
                                'posts_per_page' => 8,
                                'orderby'        => 'comment_count',
                                'order'          => 'DESC',
                            ));
                            if ($top->have_posts()) :
                                while ($top->have_posts()) : $top->the_post();
                                    $users = get_post_meta(get_the_ID(), '_users_online', true);
                            ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                        <?php if ($users) : ?>
                                            <span class="channel-badge"><?php echo intval($users); ?> online</span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            endif;
                            ?>
                        </ul>
                    </div>
                    <div class="channels-column">
                        <h3>Ultimos Canales</h3>
                        <ul class="channels-list">
                            <?php
                            $latest = new WP_Query(array(
                                'post_type'      => 'chat_room',
                                'posts_per_page' => 8,
                                'orderby'        => 'date',
                                'order'          => 'DESC',
                            ));
                            if ($latest->have_posts()) :
                                while ($latest->have_posts()) : $latest->the_post();
                            ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                        <span class="channel-badge">Nuevo</span>
                                    </a>
                                </li>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            endif;
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- NEWS / BLOG -->
        <?php
        $news = new WP_Query(array(
            'post_type'      => 'post',
            'posts_per_page' => 3,
        ));
        if ($news->have_posts()) :
        ?>
        <section class="news-section">
            <div class="container">
                <h2 class="section-title">Noticias</h2>
                <p class="section-subtitle">Las ultimas novedades de nuestra comunidad</p>
                <div class="news-grid">
                    <?php while ($news->have_posts()) : $news->the_post(); ?>
                    <article class="news-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('news-thumbnail', array('class' => 'news-card-image')); ?>
                            </a>
                        <?php endif; ?>
                        <div class="news-card-body">
                            <span class="news-card-date"><?php echo get_the_date(); ?></span>
                            <h3 class="news-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p class="news-card-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                        </div>
                    </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

    <?php else : ?>

        <!-- ARCHIVE / BLOG LISTING -->
        <div class="container">
            <div class="content-with-sidebar">
                <div class="main-content">
                    <?php if (have_posts()) : ?>
                        <div class="rooms-grid" style="grid-template-columns: repeat(2, 1fr);">
                            <?php while (have_posts()) : the_post(); ?>
                            <article class="news-card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('news-thumbnail', array('class' => 'news-card-image')); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="news-card-body">
                                    <span class="news-card-date"><?php echo get_the_date(); ?></span>
                                    <h3 class="news-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <p class="news-card-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                                </div>
                            </article>
                            <?php endwhile; ?>
                        </div>
                        <div class="pagination">
                            <?php
                            the_posts_pagination(array(
                                'mid_size'  => 2,
                                'prev_text' => '&laquo;',
                                'next_text' => '&raquo;',
                            ));
                            ?>
                        </div>
                    <?php else : ?>
                        <p>No se encontraron publicaciones.</p>
                    <?php endif; ?>
                </div>
                <aside class="sidebar">
                    <?php get_sidebar(); ?>
                </aside>
            </div>
        </div>

    <?php endif; ?>
</main>

<?php get_footer(); ?>
