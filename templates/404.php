<?php
// bip-network-kit/templates/404.php
get_header(); ?>
<main class="sd-404">
  <h1>Page Not Found</h1>
  <p>Sorry, we couldn’t find what you’re looking for.</p>
  <a href="<?php echo esc_url( home_url() ); ?>">Return home</a>
</main>
<?php
get_footer();
