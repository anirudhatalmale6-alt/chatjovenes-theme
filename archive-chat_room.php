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

        <?php if (have_posts()) : ?>
        <div class="rooms-grid">
            <?php while (have_posts()) : the_post();
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
            <p>No hay salas de chat disponibles todavia.</p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
