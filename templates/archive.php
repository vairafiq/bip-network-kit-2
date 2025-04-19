<?php include plugin_dir_path(__FILE__) . '/header.php'; ?>

<main class="sd-archive">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php include plugin_dir_path(__FILE__) . '/content-archive.php'; ?>
        <?php endwhile; ?>
    <?php else : ?>
        <p>No items found.</p>
    <?php endif; ?>
</main>

<?php include plugin_dir_path(__FILE__) . '/footer.php'; ?>
