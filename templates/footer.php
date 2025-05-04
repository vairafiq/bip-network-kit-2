<?php
/**
 * Plugin Footer Template
 */
?>
<footer class="sd-footer">
    <div class="sd-footer-inner">

        <div class="sd-footer-main">
            <div class="sd-footer-bio">
                <span class="site-name"><?php bloginfo('name'); ?></span>
                <p><?php bloginfo('name'); ?> is a top-rated directory connecting users to trusted local businesses quickly and easily. - Powered by <a href="bippermedia.com">Bipper Media</a></p>
            </div>
            <nav class="sd-footer-nav">
                <span class="footer-header">Quick links</span>
                <ul>
                    <li><a href="<?php echo site_url(); ?>/">Home</a></li>
                    <li><a href="<?php echo site_url('/biz/'); ?>">Businesses</a></li>
                </ul>
            </nav>
            <div class="sd-footer-contacts">
                <span class="footer-header" style="text-transform:none !important;">Decided to be included here?</span>
                <div>
                    <a href="https://bippermedia.com/add-network-business/" class="sd-btn-secondary" aria-label="Add your business">
                        <i class="fa-solid fa-plus" aria-hiiden="true"></i> Add Your Business
                    </a>
                </div>
            </div>
        </div>
    </div>

</footer>
<div class='sd-footer-bottom'>
    <p class='sd-footer-copyright'>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
</div>

<?php wp_footer(); // This is important to include any necessary scripts or resources ?>
</body>
</html>
