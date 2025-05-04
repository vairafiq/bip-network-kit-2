<?php get_header(); ?>
<main class="sd-404" style="height:100vh;width:100vw;margin:auto;display:flex;flex-direction:column;justify-content:center;align-items:center;background:#000;color:#fff">
  <h1>Page Not Found</h1>
  <p>Sorry, we couldn’t find what you’re looking for.</p>
  <a href="<?php echo esc_url( home_url() ); ?>" style="background:#2f2f2f;padding:10px 30px;border-radius:10px;display:block;color:#fff !important;">Return home</a>
</main>
<style>
    header,footer {
        display:none !important;
    }
</style>
<?php get_footer(); ?>
