jQuery(document).ready(function($) {


    // Toggle mobile menu
    const handleMobileMenu = function () {
        const menuOpen = $('.sd-menu-toggle');
        const menuClose = $('.sd-menu-toggle-close');
        const mainMenu = $('.sd-menu');

        menuOpen.on('click', function () {
            console.log('open');
            mainMenu.addClass('open');
        });
        menuClose.on('click', function () {
            mainMenu.removeClass('open');
            console.log('close');

        });
    }
    



    // Submenu toggle on mobile
    const handleMobileSubmenu = function () {
        if (window.innerWidth <= 768) {
            $('.sd-submenu-toggle').on('click', function (e) {
                e.preventDefault();
                let parent = $(this).closest('.sd-has-submenu');
                console.log(parent);
                    
                // Toggle open class
                parent.toggleClass('open');

                // close others
                $('.sd-has-submenu').not(parent).removeClass('open');
            });
        } else {
            $('.sd-has-submenu').removeClass('open'); // Reset on desktop
        }
    }


    const handleActiveItem = function () {
        const items = $('.sd-menu-item');

        items.each(function () {
            const item = $(this);
            const link = item.find('a');

            if (link.attr('href') === window.location.href) {
                item.addClass('active');
            }
        });
    }




    const handleHeaderForAdminBar = function () {
        const adminBar = $('#wpadminbar');
        const header = $('.sd-header-container');

        if (adminBar.length) {
            header.css('top', adminBar.outerHeight() + 'px');
        } else {
            header.css('top', '0');
        }
    }




    const adjustHeaderBackgroundOnScroll = function () {
        const header = $('.sd-header-container');

        window.addEventListener('scroll', function () {
            const scrollTop = window.scrollY || document.documentElement.scrollTop;

            if (scrollTop > 0) {
                header.addClass('scrolled');
            } else {
                header.removeClass('scrolled');
            }
        });
    }
    
    
    
    
    // Run on load and resize
    handleMobileMenu();
    handleMobileSubmenu();
    handleActiveItem();
    handleHeaderForAdminBar();
    adjustHeaderBackgroundOnScroll();
    $(window).on('resize', function () {
        handleMobileMenu();
        handleMobileSubmenu();
        handleActiveItem();
        handleHeaderForAdminBar();
        adjustHeaderBackgroundOnScroll();
    });






});
