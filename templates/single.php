<?php include plugin_dir_path(__FILE__) . 'header.php'; ?>

<div class="sd-single-post">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            the_title('<h1 class="sd-title">', '</h1>');
            the_content();
        endwhile;
    endif;
    ?>
</div>

<?php include plugin_dir_path(__FILE__) . 'footer.php'; ?>
