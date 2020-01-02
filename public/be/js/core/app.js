/* ------------------------------------------------------------------------------
 *
 *  # Template JS core
 *
 *  Core JS file with default functionality configuration
 *
 *  Version: 1.2
 *  Latest update: Dec 11, 2015
 *
 * ---------------------------------------------------------------------------- */


// Allow CSS transitions when page is loaded
$(window).on('load', function () {
    $('body').removeClass('no-transitions');
});


$(function () {

    // Disable CSS transitions on page load
    $('body').addClass('no-transitions');


    // ========================================
    //
    // Activate sidebar url
    //
    // ========================================

    var sidebar = $('.navigation');

    //Add active class to nav-link based on url dynamically
    //Active class can be hard coded directly in html file also as required
    var current = window.location.href;//.split("/").slice(-1)[0].replace(/^\/|\/$/g, '');

    current = current.replace(baseUrl + 'admin/', "").split("/").slice(0)[0].replace(/^\/|\/$/g, '');
    current = current.replace(/#+$/, "");

    $(sidebar).find('li a').each(function () {
        var $this = $(this), href = $this.attr('href');

        if (current === "" || current === 'http:' || current === 'https:') {
            //for root url
            if (href) {
                if (href.indexOf("index") !== -1) {
                    $this.closest('li').addClass('active');
                }
            }
        } else {
            //for other url
            if (href) {
                if (href.indexOf(current) !== -1) {
                    $this.closest('li').addClass('active');
                }
            }
        }
    });

    // ========================================
    //
    // Content area height
    //
    // ========================================


    // Calculate min height
    function containerHeight() {
        var availableHeight = $(window).height() - $('.page-container').offset().top - $('.navbar-fixed-bottom').outerHeight();

        $('.page-container').attr('style', 'min-height:' + availableHeight + 'px');
    }

    // Initialize
    containerHeight();


    // ========================================
    //
    // Heading elements
    //
    // ========================================


    // Heading elements toggler
    // -------------------------

    // Add control button toggler to page and panel headers if have heading elements
    $('.panel-footer').has('> .heading-elements:not(.not-collapsible)').prepend('<a class="heading-elements-toggle"><i class="icon-more"></i></a>');
    $('.page-title, .panel-title').parent().has('> .heading-elements:not(.not-collapsible)').children('.page-title, .panel-title').append('<a class="heading-elements-toggle"><i class="icon-more"></i></a>');


    // Toggle visible state of heading elements
    $('.page-title .heading-elements-toggle, .panel-title .heading-elements-toggle').on('click', function () {
        $(this).parent().parent().toggleClass('has-visible-elements').children('.heading-elements').toggleClass('visible-elements');
    });
    $('.panel-footer .heading-elements-toggle').on('click', function () {
        $(this).parent().toggleClass('has-visible-elements').children('.heading-elements').toggleClass('visible-elements');
    });


    // Breadcrumb elements toggler
    // -------------------------

    // Add control button toggler to breadcrumbs if has elements
    $('.breadcrumb-line').has('.breadcrumb-elements').prepend('<a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>');


    // Toggle visible state of breadcrumb elements
    $('.breadcrumb-elements-toggle').on('click', function () {
        $(this).parent().children('.breadcrumb-elements').toggleClass('visible-elements');
    });


    // ========================================
    //
    // Navbar
    //
    // ========================================


    // Navbar navigation
    // -------------------------

    // Prevent dropdown from closing on click
    $(document).on('click', '.dropdown-content', function (e) {
        e.stopPropagation();
    });

    // Disabled links
    $('.navbar-nav .disabled a').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
    });

    // Show tabs inside dropdowns
    $('.dropdown-content a[data-toggle="tab"]').on('click', function (e) {
        $(this).tab('show');
    });


    // ========================================
    //
    // Element controls
    //
    // ========================================


    // Reload elements
    // -------------------------

    // Panels
    $('.panel [data-action=reload]').click(function (e) {
        e.preventDefault();
        var block = $(this).parent().parent().parent().parent().parent();
        $(block).block({
            message: '<i class="icon-spinner2 spinner"></i>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait',
                'box-shadow': '0 0 0 1px #ddd'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none'
            }
        });

        // For demo purposes
        window.setTimeout(function () {
            $(block).unblock();
        }, 2000);
    });


    // Sidebar categories
    $('.category-title [data-action=reload]').click(function (e) {
        e.preventDefault();
        var block = $(this).parent().parent().parent().parent();
        $(block).block({
            message: '<i class="icon-spinner2 spinner"></i>',
            overlayCSS: {
                backgroundColor: '#000',
                opacity: 0.5,
                cursor: 'wait',
                'box-shadow': '0 0 0 1px #000'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none',
                color: '#fff'
            }
        });

        // For demo purposes
        window.setTimeout(function () {
            $(block).unblock();
        }, 2000);
    });


    // Light sidebar categories
    $('.sidebar-default .category-title [data-action=reload]').click(function (e) {
        e.preventDefault();
        var block = $(this).parent().parent().parent().parent();
        $(block).block({
            message: '<i class="icon-spinner2 spinner"></i>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait',
                'box-shadow': '0 0 0 1px #ddd'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none'
            }
        });

        // For demo purposes
        window.setTimeout(function () {
            $(block).unblock();
        }, 2000);
    });


    // Collapse elements
    // -------------------------

    //
    // Sidebar categories
    //

    // Hide if collapsed by default
    $('.category-collapsed').children('.category-content').hide();


    // Rotate icon if collapsed by default
    $('.category-collapsed').find('[data-action=collapse]').addClass('rotate-180');


    // Collapse on click
    $('.category-title [data-action=collapse]').click(function (e) {
        e.preventDefault();
        var $categoryCollapse = $(this).parent().parent().parent().nextAll();
        $(this).parents('.category-title').toggleClass('category-collapsed');
        $(this).toggleClass('rotate-180');

        containerHeight(); // adjust page height

        $categoryCollapse.slideToggle(150);
    });


    //
    // Panels
    //

    // Hide if collapsed by default
    $('.panel-collapsed').children('.panel-heading').nextAll().hide();


    // Rotate icon if collapsed by default
    $('.panel-collapsed').find('[data-action=collapse]').addClass('rotate-180');


    // Collapse on click
    $('.panel [data-action=collapse]').click(function (e) {
        e.preventDefault();
        var $panelCollapse = $(this).parent().parent().parent().parent().nextAll();
        $(this).parents('.panel').toggleClass('panel-collapsed');
        $(this).toggleClass('rotate-180');

        containerHeight(); // recalculate page height

        $panelCollapse.slideToggle(150);
    });


    // Remove elements
    // -------------------------

    // Panels
    $('.panel [data-action=close]').click(function (e) {
        e.preventDefault();
        var $panelClose = $(this).parent().parent().parent().parent().parent();

        containerHeight(); // recalculate page height

        $panelClose.slideUp(150, function () {
            $(this).remove();
        });
    });


    // Sidebar categories
    $('.category-title [data-action=close]').click(function (e) {
        e.preventDefault();
        var $categoryClose = $(this).parent().parent().parent().parent();

        containerHeight(); // recalculate page height

        $categoryClose.slideUp(150, function () {
            $(this).remove();
        });
    });


    // ========================================
    //
    // Main navigation
    //
    // ========================================


    // Main navigation
    // -------------------------

    // Add 'active' class to parent list item in all levels
    $('.navigation').find('li.active').parents('li').addClass('active');

    // Hide all nested lists
    $('.navigation').find('li').not('.active, .category-title').has('ul').children('ul').addClass('hidden-ul');

    // Highlight children links
    $('.navigation').find('li').has('ul').children('a').addClass('has-ul');

    // Add active state to all dropdown parent levels
    $('.dropdown-menu:not(.dropdown-content), .dropdown-menu:not(.dropdown-content) .dropdown-submenu').has('li.active').addClass('active').parents('.navbar-nav .dropdown:not(.language-switch), .navbar-nav .dropup:not(.language-switch)').addClass('active');


    // Main navigation tooltips positioning
    // -------------------------

    // Left sidebar
    $('.navigation-main > .navigation-header > i').tooltip({
        placement: 'right',
        container: 'body'
    });


    // Collapsible functionality
    // -------------------------

    // Main navigation
    $('.navigation-main').find('li').has('ul').children('a').on('click', function (e) {
        e.preventDefault();

        // Collapsible
        $(this).parent('li').not('.disabled').not($('.sidebar-xs').not('.sidebar-xs-indicator').find('.navigation-main').children('li')).toggleClass('active').children('ul').slideToggle(250);

        // Accordion
        if ($('.navigation-main').hasClass('navigation-accordion')) {
            $(this).parent('li').not('.disabled').not($('.sidebar-xs').not('.sidebar-xs-indicator').find('.navigation-main').children('li')).siblings(':has(.has-ul)').removeClass('active').children('ul').slideUp(250);
        }
    });


    // Alternate navigation
    $('.navigation-alt').find('li').has('ul').children('a').on('click', function (e) {
        e.preventDefault();

        // Collapsible
        $(this).parent('li').not('.disabled').toggleClass('active').children('ul').slideToggle(200);

        // Accordion
        if ($('.navigation-alt').hasClass('navigation-accordion')) {
            $(this).parent('li').not('.disabled').siblings(':has(.has-ul)').removeClass('active').children('ul').slideUp(200);
        }
    });


    // ========================================
    //
    // Sidebars
    //
    // ========================================


    // Mini sidebar
    // -------------------------

    // Toggle mini sidebar
    $('.sidebar-main-toggle').on('click', function (e) {
        e.preventDefault();

        // Toggle min sidebar class
        $('body').toggleClass('sidebar-xs');
    });


    // Sidebar controls
    // -------------------------

    // Disable click in disabled navigation items
    $(document).on('click', '.navigation .disabled a', function (e) {
        e.preventDefault();
    });


    // Adjust page height on sidebar control button click
    $(document).on('click', '.sidebar-control', function (e) {
        containerHeight();
    });


    // Hide main sidebar in Dual Sidebar
    $(document).on('click', '.sidebar-main-hide', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-main-hidden');
    });


    // Toggle second sidebar in Dual Sidebar
    $(document).on('click', '.sidebar-secondary-hide', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-secondary-hidden');
    });


    // Hide detached sidebar
    $(document).on('click', '.sidebar-detached-hide', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-detached-hidden');
    });


    // Hide all sidebars
    $(document).on('click', '.sidebar-all-hide', function (e) {
        e.preventDefault();

        $('body').toggleClass('sidebar-all-hidden');
    });


    //
    // Opposite sidebar
    //

    // Collapse main sidebar if opposite sidebar is visible
    $(document).on('click', '.sidebar-opposite-toggle', function (e) {
        e.preventDefault();

        // Opposite sidebar visibility
        $('body').toggleClass('sidebar-opposite-visible');

        // If visible
        if ($('body').hasClass('sidebar-opposite-visible')) {

            // Make main sidebar mini
            $('body').addClass('sidebar-xs');

            // Hide children lists
            $('.navigation-main').children('li').children('ul').css('display', '');
        } else {

            // Make main sidebar default
            $('body').removeClass('sidebar-xs');
        }
    });


    // Hide main sidebar if opposite sidebar is shown
    $(document).on('click', '.sidebar-opposite-main-hide', function (e) {
        e.preventDefault();

        // Opposite sidebar visibility
        $('body').toggleClass('sidebar-opposite-visible');

        // If visible
        if ($('body').hasClass('sidebar-opposite-visible')) {

            // Hide main sidebar
            $('body').addClass('sidebar-main-hidden');
        } else {

            // Show main sidebar
            $('body').removeClass('sidebar-main-hidden');
        }
    });


    // Hide secondary sidebar if opposite sidebar is shown
    $(document).on('click', '.sidebar-opposite-secondary-hide', function (e) {
        e.preventDefault();

        // Opposite sidebar visibility
        $('body').toggleClass('sidebar-opposite-visible');

        // If visible
        if ($('body').hasClass('sidebar-opposite-visible')) {

            // Hide secondary
            $('body').addClass('sidebar-secondary-hidden');

        } else {

            // Show secondary
            $('body').removeClass('sidebar-secondary-hidden');
        }
    });


    // Hide all sidebars if opposite sidebar is shown
    $(document).on('click', '.sidebar-opposite-hide', function (e) {
        e.preventDefault();

        // Toggle sidebars visibility
        $('body').toggleClass('sidebar-all-hidden');

        // If hidden
        if ($('body').hasClass('sidebar-all-hidden')) {

            // Show opposite
            $('body').addClass('sidebar-opposite-visible');

            // Hide children lists
            $('.navigation-main').children('li').children('ul').css('display', '');
        } else {

            // Hide opposite
            $('body').removeClass('sidebar-opposite-visible');
        }
    });


    // Keep the width of the main sidebar if opposite sidebar is visible
    $(document).on('click', '.sidebar-opposite-fix', function (e) {
        e.preventDefault();

        // Toggle opposite sidebar visibility
        $('body').toggleClass('sidebar-opposite-visible');
    });


    // Mobile sidebar controls
    // -------------------------

    // Toggle main sidebar
    $('.sidebar-mobile-main-toggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mobile-main').removeClass('sidebar-mobile-secondary sidebar-mobile-opposite sidebar-mobile-detached');
    });


    // Toggle secondary sidebar
    $('.sidebar-mobile-secondary-toggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mobile-secondary').removeClass('sidebar-mobile-main sidebar-mobile-opposite sidebar-mobile-detached');
    });


    // Toggle opposite sidebar
    $('.sidebar-mobile-opposite-toggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mobile-opposite').removeClass('sidebar-mobile-main sidebar-mobile-secondary sidebar-mobile-detached');
    });


    // Toggle detached sidebar
    $('.sidebar-mobile-detached-toggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mobile-detached').removeClass('sidebar-mobile-main sidebar-mobile-secondary sidebar-mobile-opposite');
    });


    // Mobile sidebar setup
    // -------------------------

    $(window).on('resize', function () {
        setTimeout(function () {
            containerHeight();

            if ($(window).width() <= 768) {

                // Add mini sidebar indicator
                $('body').addClass('sidebar-xs-indicator');

                // Place right sidebar before content
                $('.sidebar-opposite').insertBefore('.content-wrapper');

                // Place detached sidebar before content
                $('.sidebar-detached').insertBefore('.content-wrapper');

                // Add mouse events for dropdown submenus
                $('.dropdown-submenu').on('mouseenter', function () {
                    $(this).children('.dropdown-menu').addClass('show');
                }).on('mouseleave', function () {
                    $(this).children('.dropdown-menu').removeClass('show');
                });
            } else {

                // Remove mini sidebar indicator
                $('body').removeClass('sidebar-xs-indicator');

                // Revert back right sidebar
                $('.sidebar-opposite').insertAfter('.content-wrapper');

                // Remove all mobile sidebar classes
                $('body').removeClass('sidebar-mobile-main sidebar-mobile-secondary sidebar-mobile-detached sidebar-mobile-opposite');

                // Revert left detached position
                if ($('body').hasClass('has-detached-left')) {
                    $('.sidebar-detached').insertBefore('.container-detached');
                }

                // Revert right detached position
                else if ($('body').hasClass('has-detached-right')) {
                    $('.sidebar-detached').insertAfter('.container-detached');
                }

                // Remove visibility of heading elements on desktop
                $('.page-header-content, .panel-heading, .panel-footer').removeClass('has-visible-elements');
                $('.heading-elements').removeClass('visible-elements');

                // Disable appearance of dropdown submenus
                $('.dropdown-submenu').children('.dropdown-menu').removeClass('show');
            }
        }, 100);
    }).resize();


    // ========================================
    //
    // Other code
    //
    // ========================================


    // Plugins
    // -------------------------

    // Popover
    $('[data-popup="popover"]').popover({
        container: 'body'
    });

    // Tooltip
    $('[data-popup="tooltip"]').tooltip({
        container: 'body'
    });

    // Lazy loader (pictures, videos, etc.)
    if ($.fn.lazy) {
        $('.lazy').lazy({
            effect: "fadeIn",
            effectTime: 800,
            threshold: 50,
            // callback
            afterLoad: function (element) {
                $(element).css({'background': 'none'});
            }
        });
    }

    if ($.fn.persianDatepicker) {
        $('.myDatepickerWithEn').persianDatepicker({
            "inline": false,
            "format": "L",
            "viewMode": "day",
            "initialValue": true,
            "minDate": 0,
            "maxDate": 0,
            "autoClose": false,
            "position": "auto",
            "onlyTimePicker": false,
            "onlySelectOnDate": false,
            "calendarType": "persian",
            "inputDelay": 800,
            "observer": true,
            "calendar": {
                "persian": {
                    "locale": "fa",
                    "showHint": true,
                    "leapYearMode": "algorithmic"
                },
                "gregorian": {
                    "locale": "en",
                    "showHint": true
                }
            },
            "navigator": {
                "enabled": true,
                "scroll": {
                    "enabled": true
                },
                "text": {
                    "btnNextText": "<",
                    "btnPrevText": ">"
                }
            },
            "toolbox": {
                "enabled": true,
                "calendarSwitch": {
                    "enabled": true,
                    "format": "MMMM"
                },
                "todayButton": {
                    "enabled": true,
                    "text": {
                        "fa": "امروز",
                        "en": "Today"
                    }
                },
                "submitButton": {
                    "enabled": true,
                    "text": {
                        "fa": "تایید",
                        "en": "Submit"
                    }
                },
                "text": {
                    "btnToday": "امروز"
                }
            },
            "timePicker": {
                "enabled": false,
                "step": 1,
                "hour": {
                    "enabled": true,
                    "step": null
                },
                "minute": {
                    "enabled": true,
                    "step": null
                },
                "second": {
                    "enabled": true,
                    "step": null
                },
                "meridian": {
                    "enabled": true
                }
            },
            "dayPicker": {
                "enabled": true,
                "titleFormat": "YYYY MMMM"
            },
            "monthPicker": {
                "enabled": true,
                "titleFormat": "YYYY"
            },
            "yearPicker": {
                "enabled": true,
                "titleFormat": "YYYY"
            },
            "responsive": true
        });

        $('.myDatepicker').persianDatepicker({
            "inline": false,
            "format": "L",
            "viewMode": "day",
            "initialValue": true,
            "minDate": 0,
            "maxDate": 0,
            "autoClose": false,
            "position": "auto",
            "onlyTimePicker": false,
            "onlySelectOnDate": false,
            "calendarType": "persian",
            "inputDelay": 800,
            "observer": true,
            "calendar": {
                "persian": {
                    "locale": "fa",
                    "showHint": true,
                    "leapYearMode": "algorithmic"
                },
                "gregorian": {
                    "locale": "en",
                    "showHint": true
                }
            },
            "navigator": {
                "enabled": true,
                "scroll": {
                    "enabled": true
                },
                "text": {
                    "btnNextText": "<",
                    "btnPrevText": ">"
                }
            },
            "toolbox": {
                "enabled": true,
                "calendarSwitch": {
                    "enabled": false,
                    "format": "MMMM"
                },
                "todayButton": {
                    "enabled": true,
                    "text": {
                        "fa": "امروز",
                        "en": "Today"
                    }
                },
                "submitButton": {
                    "enabled": true,
                    "text": {
                        "fa": "تایید",
                        "en": "Submit"
                    }
                },
                "text": {
                    "btnToday": "امروز"
                }
            },
            "timePicker": {
                "enabled": false,
                "step": 1,
                "hour": {
                    "enabled": true,
                    "step": null
                },
                "minute": {
                    "enabled": true,
                    "step": null
                },
                "second": {
                    "enabled": true,
                    "step": null
                },
                "meridian": {
                    "enabled": true
                }
            },
            "dayPicker": {
                "enabled": true,
                "titleFormat": "YYYY MMMM"
            },
            "monthPicker": {
                "enabled": true,
                "titleFormat": "YYYY"
            },
            "yearPicker": {
                "enabled": true,
                "titleFormat": "YYYY"
            },
            "responsive": true
        });

        var altDP = $('.myAltDatepicker');
        $(altDP).each(function () {
            var $this = $(this);
            $this.persianDatepicker({
                "inline": false,
                "format": !!$this.attr('data-format') ? $this.attr('data-format') : 'L',
                "viewMode": "day",
                "initialValue": true,
                "minDate": 0,
                "maxDate": 0,
                "autoClose": false,
                "position": "auto",
                "onlyTimePicker": false,
                "onlySelectOnDate": false,
                "calendarType": "persian",
                "altFormat": 'X',
                "altField": $this.attr('data-alt-field'),
                "inputDelay": 800,
                "observer": true,
                "calendar": {
                    "persian": {
                        "locale": "fa",
                        "showHint": true,
                        "leapYearMode": "algorithmic"
                    },
                    "gregorian": {
                        "locale": "en",
                        "showHint": true
                    }
                },
                "navigator": {
                    "enabled": true,
                    "scroll": {
                        "enabled": true
                    },
                    "text": {
                        "btnNextText": "<",
                        "btnPrevText": ">"
                    }
                },
                "toolbox": {
                    "enabled": true,
                    "calendarSwitch": {
                        "enabled": false,
                        "format": "MMMM"
                    },
                    "todayButton": {
                        "enabled": true,
                        "text": {
                            "fa": "امروز",
                            "en": "Today"
                        }
                    },
                    "submitButton": {
                        "enabled": true,
                        "text": {
                            "fa": "تایید",
                            "en": "Submit"
                        }
                    },
                    "text": {
                        "btnToday": "امروز"
                    }
                },
                "timePicker": {
                    "enabled": !!$this.attr('data-time'),
                    "step": 1,
                    "hour": {
                        "enabled": true,
                        "step": null
                    },
                    "minute": {
                        "enabled": true,
                        "step": null
                    },
                    "second": {
                        "enabled": false,
                        "step": null
                    },
                    "meridian": {
                        "enabled": false
                    }
                },
                "dayPicker": {
                    "enabled": true,
                    "titleFormat": "YYYY MMMM"
                },
                "monthPicker": {
                    "enabled": true,
                    "titleFormat": "YYYY"
                },
                "yearPicker": {
                    "enabled": true,
                    "titleFormat": "YYYY"
                },
                "responsive": true
            });
        });

        var rtAll, rfAll;

        rtAll = $(".range-to");
        rfAll = $(".range-from");

        rfAll.each(function (i) {
            var to, from;
            var rt, rf;

            rt = rtAll.eq(i);
            rf = $(this);

            to = rt.persianDatepicker({
                inline: false,
                altField: rt.attr('data-alt-field'),
                format: !!rt.attr('data-format') ? rt.attr('data-format') : 'L',
                initialValue: true,
                onSelect: function (unix) {
                    to.touched = true;
                    if (from && from.options && from.options.maxDate != unix) {
                        var cachedValue = from.getState().selected.unixDate;
                        from.options = {maxDate: unix};
                        if (from.touched) {
                            from.setDate(cachedValue);
                        }
                    }
                },
                "minDate": 0,
                "maxDate": 0,
                "autoClose": true,
                "position": "auto",
                "onlyTimePicker": false,
                "onlySelectOnDate": false,
                "calendarType": "persian",
                "altFormat": 'X',
                "inputDelay": 800,
                "observer": true,
                "calendar": {
                    "persian": {
                        "locale": "fa",
                        "showHint": true,
                        "leapYearMode": "algorithmic"
                    },
                    "gregorian": {
                        "locale": "en",
                        "showHint": true
                    }
                },
                "timePicker": {
                    "enabled": !!rt.attr('data-time'),
                    "step": 1,
                    "hour": {
                        "enabled": true,
                        "step": null
                    },
                    "minute": {
                        "enabled": true,
                        "step": null
                    },
                    "second": {
                        "enabled": false,
                        "step": null
                    },
                    "meridian": {
                        "enabled": false
                    }
                }
            });
            from = rf.persianDatepicker({
                inline: false,
                altField: rf.attr('data-alt-field'),
                format: !!rf.attr('data-format') ? rf.attr('data-format') : 'L',
                initialValue: true,
                onSelect: function (unix) {
                    from.touched = true;
                    if (to && to.options && to.options.minDate != unix) {
                        var cachedValue = to.getState().selected.unixDate;
                        to.options = {minDate: unix};
                        if (to.touched) {
                            to.setDate(cachedValue);
                        }
                    }
                },
                "minDate": 0,
                "maxDate": 0,
                "autoClose": true,
                "position": "auto",
                "onlyTimePicker": false,
                "onlySelectOnDate": false,
                "calendarType": "persian",
                "altFormat": 'X',
                "inputDelay": 800,
                "observer": true,
                "calendar": {
                    "persian": {
                        "locale": "fa",
                        "showHint": true,
                        "leapYearMode": "algorithmic"
                    },
                    "gregorian": {
                        "locale": "en",
                        "showHint": true
                    }
                },
                "timePicker": {
                    "enabled": !!rf.attr('data-time'),
                    "step": 1,
                    "hour": {
                        "enabled": true,
                        "step": null
                    },
                    "minute": {
                        "enabled": true,
                        "step": null
                    },
                    "second": {
                        "enabled": false,
                        "step": null
                    },
                    "meridian": {
                        "enabled": false
                    }
                }
            });
        });


        $('.myTimepicker').persianDatepicker({
            "inline": false,
            "format": "H:m:s",
            "initialValue": true,
            "autoClose": false,
            "position": "auto",
            "onlyTimePicker": true,
            "onlySelectOnDate": false,
            "calendarType": "persian",
            "inputDelay": 800,
            "observer": true,
            "toolbox": {
                "enabled": false
            },
            "timePicker": {
                "enabled": false,
                "step": "1",
                "hour": {
                    "enabled": true,
                    "step": null
                },
                "minute": {
                    "enabled": true,
                    "step": null
                },
                "second": {
                    "enabled": true,
                    "step": null
                },
                "meridian": {
                    "enabled": false
                }
            },
            "responsive": true
        });
    }

    if ($.fn.fancybox) {
        $('[data-popup="lightbox"]').off('click').on('click', function (e) {
            e.preventDefault();
        }).each(function () {
            $(this).fancybox({
                href: $(this).attr('data-url')
            });
        });
    }

    if ($.fn.spectrum) {
        var cps = $(".color-picker");
        cps.each(function () {
            var $this = $(this);

            $this.spectrum({
                color: $this.attr('data-color') ? $this.attr('data-color') : "#2196f3",
                cancelText: "لغو",
                chooseText: "انتخاب",
                preferredFormat: "hex",
                showInput: true
            })
        });
    }
});
