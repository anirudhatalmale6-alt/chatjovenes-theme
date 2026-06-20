<?php get_header(); ?>

<div class="container" style="padding: 40px 20px;">
    <?php if (have_posts()) : while (have_posts()) : the_post();
        $xat_id = get_post_meta(get_the_ID(), '_xat_chat_id', true);
        $users = get_post_meta(get_the_ID(), '_users_online', true);
        $room_cats = get_the_terms(get_the_ID(), 'room_category');
    ?>

    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 32px; margin-bottom: 8px;"><?php the_title(); ?></h1>
        <div class="post-meta">
            <?php if ($room_cats && !is_wp_error($room_cats)) : ?>
                <?php foreach ($room_cats as $i => $rc) : ?>
                    <?php if ($i > 0) echo ', '; ?>
                    <a href="<?php echo esc_url(get_term_link($rc)); ?>"><?php echo esc_html($rc->name); ?></a>
                <?php endforeach; ?>
                &bull;
            <?php endif; ?>
            <?php if ($users) : ?>
                <span class="users-online"><?php echo intval($users); ?> usuarios en linea</span>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($xat_id) : ?>
    <div class="chat-embed-wrapper" style="margin-bottom: 30px;">
        <?php
        $global_embed = get_theme_mod('xat_embed_code', '');
        if ($global_embed) :
            echo $global_embed;
        else :
        ?>
            <iframe src="https://xat.com/web_gear/chat/go_large.php?id=<?php echo esc_attr($xat_id); ?>" allowfullscreen scrolling="no"></iframe>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (get_the_content()) : ?>
    <div class="post-content" style="max-width: 800px;">
        <?php the_content(); ?>
    </div>
    <?php endif; ?>

    <!-- RELATED ROOMS -->
    <?php
    if ($room_cats && !is_wp_error($room_cats)) :
        $cat_ids = wp_list_pluck($room_cats, 'term_id');
        $related = new WP_Query(array(
            'post_type'      => 'chat_room',
            'posts_per_page' => 4,
            'post__not_in'   => array(get_the_ID()),
            'tax_query'      => array(
                array(
                    'taxonomy' => 'room_category',
                    'field'    => 'term_id',
                    'terms'    => $cat_ids,
                ),
            ),
        ));
        if ($related->have_posts()) :
    ?>
    <section style="margin-top: 50px;">
        <h2 class="section-title">Salas Relacionadas</h2>
        <div class="rooms-grid">
            <?php while ($related->have_posts()) : $related->the_post(); ?>
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
                    <a href="<?php the_permalink(); ?>" class="room-card-btn" style="margin-top: 12px;">Entrar</a>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </section>
    <?php
        endif;
    endif;
    ?>

    <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>
