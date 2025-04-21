<?php include plugin_dir_path(__FILE__) . 'header.php'; ?>

<main class="sd-single">
    <section class="sd-container">

        <div class="sd-single-page-container">

            <div class="sd-single-wrapper">

                <!-- Single header -->
                <div class="sd-single-header">
                    <h1><?php the_title(); ?></h1>
                </div>

                <!-- Business details starts here -->
                <div class="sd-business-details">
                    <?php if (have_posts()) : ?>
                        <?php while (have_posts()) : the_post(); ?>
                            <?php include plugin_dir_path(__FILE__) . 'content-single.php'; ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <p>No Business found.</p>
                    <?php endif; ?>
                </div>

            </div> <!-- sd-single-wrapper -->

            <div class="sd-sidebar" style="border: 1px solid #ccc; padding: 20px; background-color: #f9f9f9;">
                Sidebar
            </div>

        </div> <!-- sd-single-page-container -->

    </section>
</main>        

<?php include plugin_dir_path(__FILE__) . 'footer.php'; ?>
