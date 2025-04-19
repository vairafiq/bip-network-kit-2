<article id="post-<?php the_ID(); ?>" <?php post_class('sd-archive-item'); ?>>
    
    <?php if (has_post_thumbnail()) : ?>
        <div class="sd-archive-thumbnail">
            <a href="<?php the_permalink(); ?>" rel="bookmark">
                <?php the_post_thumbnail('medium'); ?>
            </a>
        </div>
    <?php endif; ?>

    <header class="sd-archive-header">
        <h2 class="sd-archive-title">
            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h2>
    </header>

    <div class="sd-archive-excerpt">
        <?php the_excerpt(); ?>
    </div>

    <footer class="sd-archive-footer">
        <a href="<?php the_permalink(); ?>" class="sd-read-more">Read More</a>
    </footer>
</article>
