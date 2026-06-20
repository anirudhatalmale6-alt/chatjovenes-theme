<?php get_header(); ?>

<section class="rooms-section">
    <div class="container">
        <h1 class="section-title">
            <?php
            if (is_tax('room_category')) {
                single_term_title();
            } else {
                echo 'Todas las Salas de Chat';
            }
            ?>
        </h1>
        <p class="section-subtitle">
            <?php
            if (is_tax('room_category')) {
                echo term_description();
            } else {
                echo 'Explora todas nuestras salas disponibles';
            }
            ?>
        </p>

        <!-- FEATURED ROOMS WITH IMAGES (4 columns) -->
        <?php
        $featured_args = array(
            'post_type'      => 'chat_room',
            'posts_per_page' => 4,
            'meta_query'     => array(
                array(
                    'key'   => '_featured_room',
                    'value' => '1',
                ),
            ),
        );

        if (is_tax('room_category')) {
            $current_term = get_queried_object();
            $featured_args['tax_query'] = array(
                array(
                    'taxonomy' => 'room_category',
                    'field'    => 'term_id',
                    'terms'    => $current_term->term_id,
                ),
            );
        }

        $featured = new WP_Query($featured_args);

        if (!$featured->have_posts()) {
            unset($featured_args['meta_query']);
            $featured = new WP_Query($featured_args);
        }

        if ($featured->have_posts()) :
        ?>
        <h2 class="section-title" style="font-size: 20px; margin-bottom: 20px;">Salas de Chat Recomendadas</h2>
        <div class="rooms-grid">
            <?php while ($featured->have_posts()) : $featured->the_post();
                $users = get_post_meta(get_the_ID(), '_users_online', true);
            ?>
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
                        <?php if ($users) : ?>
                            <span class="users-online"><?php echo intval($users); ?> en linea</span>
                        <?php endif; ?>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="room-card-btn" style="margin-top: 12px;">Entrar</a>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php endif; ?>

        <!-- ALL ROOMS AS TEXT LINKS -->
        <?php
        $all_args = array(
            'post_type'      => 'chat_room',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        );

        if (is_tax('room_category')) {
            $current_term = get_queried_object();
            $all_args['tax_query'] = array(
                array(
                    'taxonomy' => 'room_category',
                    'field'    => 'term_id',
                    'terms'    => $current_term->term_id,
                ),
            );
        }

        $all_rooms = new WP_Query($all_args);

        if ($all_rooms->have_posts()) :
        ?>
        <div class="all-rooms-section" style="margin-top: 40px;">
            <h2 class="section-title" style="font-size: 20px; margin-bottom: 20px;">Todas las Salas</h2>
            <div class="all-rooms-links">
                <?php while ($all_rooms->have_posts()) : $all_rooms->the_post(); ?>
                    <a href="<?php the_permalink(); ?>" class="room-link"><?php the_title(); ?></a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
