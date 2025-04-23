<?php include plugin_dir_path(__FILE__) . 'header-single.php'; ?>

<main class="sd-single">
    <section class="sd-container">

        <div class="sd-single-page-container">

            <div class="sd-single-wrapper">

                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <?php include plugin_dir_path(__FILE__) . 'content-single.php'; ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <p>No Business found.</p>
                <?php endif; ?>

            </div> <!-- sd-single-wrapper -->

        </div> <!-- sd-single-page-container -->

    </section>
</main>        

<?php include plugin_dir_path(__FILE__) . 'footer.php'; ?>
