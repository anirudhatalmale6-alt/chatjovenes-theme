<?php get_header(); ?>

<div class="container" style="padding: 30px 20px;">
    <?php if (have_posts()) : while (have_posts()) : the_post();
        $xat_embed_room = get_post_meta(get_the_ID(), '_xat_embed_code', true);
        $users = get_post_meta(get_the_ID(), '_users_online', true);
        $room_cats = get_the_terms(get_the_ID(), 'room_category');
        $hide_title = get_post_meta(get_the_ID(), '_hide_title', true);
    ?>

    <!-- BREADCRUMB -->
    <nav class="chat-breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Inicio</a>
        <?php if ($room_cats && !is_wp_error($room_cats)) : ?>
            <span>/</span>
            <a href="<?php echo esc_url(get_term_link($room_cats[0])); ?>"><?php echo esc_html($room_cats[0]->name); ?></a>
        <?php endif; ?>
        <span>/</span>
        <span class="current"><?php the_title(); ?></span>
    </nav>

    <div class="chat-room-layout">
        <!-- MAIN CONTENT -->
        <div class="chat-room-main">
            <div class="chat-room-header">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="chat-room-thumb">
                        <?php the_post_thumbnail('room-thumbnail'); ?>
                    </div>
                <?php endif; ?>
                <div class="chat-room-info">
                    <?php if ($hide_title !== '1') : ?>
                        <h1><?php the_title(); ?></h1>
                    <?php endif; ?>
                    <?php if (has_excerpt()) : ?>
                        <p style="color: var(--text-light); font-size: 15px; line-height: 1.6; margin-top: 8px;"><?php echo esc_html(get_the_excerpt()); ?></p>
                    <?php endif; ?>
                    <?php if ($users) : ?>
                        <div class="post-meta" style="margin-top: 6px;">
                            <span class="users-online"><?php echo intval($users); ?> usuarios en linea</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php
            $global_embed = get_theme_mod('xat_embed_code', '');
            $room_embed = $xat_embed_room ? $xat_embed_room : $global_embed;
            if ($room_embed) :
            ?>
            <div class="chat-embed-wrapper" style="margin: 20px 0 30px;">
                <?php echo $room_embed; ?>
            </div>
            <?php endif; ?>

            <?php if (get_the_content()) : ?>
            <div class="post-content">
                <?php the_content(); ?>
            </div>
            <?php endif; ?>

            <!-- RELATED ROOMS BELOW CHAT -->
            <?php
            if ($room_cats && !is_wp_error($room_cats)) :
                $cat_ids = wp_list_pluck($room_cats, 'term_id');
                $related = new WP_Query(array(
                    'post_type'      => 'chat_room',
                    'posts_per_page' => 12,
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
            <section class="related-rooms-section">
                <h2 class="section-title" style="font-size: 20px;">Salas Relacionadas:</h2>
                <div class="related-rooms-links">
                    <?php while ($related->have_posts()) : $related->the_post(); ?>
                        <a href="<?php the_permalink(); ?>" class="related-room-link"><?php the_title(); ?></a>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </section>
            <?php
                endif;
            endif;
            ?>
        </div>

        <!-- SIDEBAR -->
        <aside class="chat-room-sidebar">
            <?php if (is_active_sidebar('sidebar-1')) : ?>
                <?php dynamic_sidebar('sidebar-1'); ?>
            <?php else : ?>

            <!-- RELATED ROOMS WIDGET -->
            <?php
            if ($room_cats && !is_wp_error($room_cats)) :
                $cat_ids_sb = wp_list_pluck($room_cats, 'term_id');
                $sidebar_related = new WP_Query(array(
                    'post_type'      => 'chat_room',
                    'posts_per_page' => 8,
                    'post__not_in'   => array(get_the_ID()),
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'room_category',
                            'field'    => 'term_id',
                            'terms'    => $cat_ids_sb,
                        ),
                    ),
                ));
                if ($sidebar_related->have_posts()) :
            ?>
            <div class="sidebar-widget">
                <h3 class="sidebar-widget-title">Salas Relacionadas</h3>
                <ul class="sidebar-room-list">
                    <?php while ($sidebar_related->have_posts()) : $sidebar_related->the_post(); ?>
                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>
            </div>
            <?php endif; endif; ?>

            <!-- RECENT POSTS -->
            <?php
            $recent_posts = new WP_Query(array(
                'post_type'      => 'post',
                'posts_per_page' => 5,
            ));
            if ($recent_posts->have_posts()) :
            ?>
            <div class="sidebar-widget">
                <h3 class="sidebar-widget-title">Publicaciones Recientes</h3>
                <ul class="sidebar-room-list">
                    <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- RECENT COMMENTS -->
            <?php
            $recent_comments = get_comments(array(
                'number' => 5,
                'status' => 'approve',
            ));
            if (!empty($recent_comments)) :
            ?>
            <div class="sidebar-widget">
                <h3 class="sidebar-widget-title">Comentarios Recientes</h3>
                <ul class="sidebar-room-list">
                    <?php foreach ($recent_comments as $comment) : ?>
                    <li>
                        <a href="<?php echo esc_url(get_comment_link($comment)); ?>">
                            <?php echo esc_html($comment->comment_author); ?> en <?php echo esc_html(get_the_title($comment->comment_post_ID)); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- TOP CHANNELS -->
            <?php
            $top_channels = new WP_Query(array(
                'post_type'      => 'chat_room',
                'posts_per_page' => 8,
                'orderby'        => 'comment_count',
                'order'          => 'DESC',
            ));
            if ($top_channels->have_posts()) :
            ?>
            <div class="sidebar-widget">
                <h3 class="sidebar-widget-title">Top Canales</h3>
                <ul class="sidebar-room-list">
                    <?php while ($top_channels->have_posts()) : $top_channels->the_post(); ?>
                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- LATEST CHANNELS -->
            <?php
            $latest_channels = new WP_Query(array(
                'post_type'      => 'chat_room',
                'posts_per_page' => 8,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ));
            if ($latest_channels->have_posts()) :
            ?>
            <div class="sidebar-widget">
                <h3 class="sidebar-widget-title">Ultimos Canales</h3>
                <ul class="sidebar-room-list">
                    <?php while ($latest_channels->have_posts()) : $latest_channels->the_post(); ?>
                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- CATEGORIES -->
            <?php
            $room_categories = get_terms(array(
                'taxonomy'   => 'room_category',
                'hide_empty' => false,
            ));
            if (!is_wp_error($room_categories) && !empty($room_categories)) :
            ?>
            <div class="sidebar-widget">
                <h3 class="sidebar-widget-title">Categorias</h3>
                <ul class="sidebar-room-list">
                    <?php foreach ($room_categories as $cat) : ?>
                    <li><a href="<?php echo esc_url(get_term_link($cat)); ?>"><?php echo esc_html($cat->name); ?> <span class="sidebar-count">(<?php echo $cat->count; ?>)</span></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php endif; ?>
        </aside>
    </div>

    <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>
