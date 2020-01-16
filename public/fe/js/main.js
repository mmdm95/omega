(function ($) {
    "use strict";

    //single listing
    var single_listing = $(".atbd_single_listing");
    var slWidth = single_listing.width();
    if (slWidth <= 300) {
        single_listing.addClass("rs_fix");
    }

    // initialize select 2
    $(document).ready(function () {
        $("#at_biz_dir-category").select2({
            placeholder: "Select a category",
            width: "100%",
            containerCssClass: "form-control"
        });

        $("#at_biz_dir-location").select2({
            multiple: false,
            width: "100%",
            placeholder: "Select a location",
            containerCssClass: "form-control"
        });

        $(".ad_search_category").select2({
            placeholder: "Select Category",
            width: "100%",
            containerCssClass: "form-control"
        });
        $("#atbd_tags").select2({
            tags: true,
            tokenSeparators: [','],
            placeholder: "Select or insert new tags",
            width: "100%",
            containerCssClass: "form-control form-control-tags"
        });
    });

    // enable bootstrap tooltip
    $('[data-toggle="tooltip"]').tooltip();

    // replace all SVG images with inline SVG
    $("img.svg").each(function () {
        var $img = $(this);
        var imgID = $img.attr("id");
        var imgClass = $img.attr("class");
        var imgURL = $img.attr("src");

        $.get(imgURL, function (data) {
            //get the svg tag, ignore the rest
            var $svg = jQuery(data).find("svg");

            //Add replaced image's ID to the new SVG
            if (typeof imgID !== "undefined") {
                $svg = $svg.attr("id", imgID);
            }

            //Add replaced image's classes to the new SVG
            if (typeof imgClass !== "undefined") {
                $svg = $svg.attr('class', imgClass + " replaced-svg");
            }

            // remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');

            //replace image width new SVG
            $img.replaceWith($svg);

        }, 'xml');
    });

    // testimonial-carousel
    $(".testimonial-carousel").owlCarousel({
        items: 1,
        dots: false,
        nav: true,
        navText: ['<span class="i la la-long-arrow-right"></span>', '<span class="i la la-long-arrow-left"></span>']
    });

    // logo carousel
    $(".logo-carousel").owlCarousel({
        items: 5,
        nav: false,
        dots: false,
        margin: 100,
        responsive: {
            0: {
                items: 2
            },
            575: {
                items: 3
            },
            767: {
                items: 3
            },
            991: {
                items: 5
            }
        }
    });

    //setting css bg image as inline in html
    $(".bg_image_holder").each(function () {
        var $this = $(this);
        var imgLink = $this.children().attr("src");
        $this.css({
            "background-image": "url(" + imgLink + ")",
            "opacity": "1"
        }).children().attr('alt', imgLink)
    });

    // listing details gallery
    $(".gallery-images").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        prevArrow: '<span class="slick-arrow prev-arrow"><i class="la la-angle-right"></i></span>',
        nextArrow: '<span class="slick-arrow next-arrow"><i class="la la-angle-left"></i></span>',
        fade: true,
        asNavFor: ".gallery-thumbs"
    });
    $(".gallery-thumbs").slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        asNavFor: ".gallery-images",
        dots: false,
        arrows: false,
        focusOnSelect: true
    });

    /* bar rating plugin installation */
    $('.give_rating').barrating({
        theme: 'fontawesome-stars',
        showSelectedRating: true
    });

    /* FAQ Accordion */
    var allPanels = $('p.ac-body').hide();
    var selectedPanel = $(".accordion-single.selected p.ac-body").show();
    $('.accordion-single > .faq-title > a').on("click", function (e) {
        allPanels.slideUp();
        $(this).parent().next().slideDown();
        $(this).parent().parent().addClass('selected').siblings().removeClass('selected');
        e.preventDefault();
    });

    /* review reply form */
    $(".review_content .reply").on("click", function (e) {
        e.preventDefault();
        if ($(this).parent().next().hasClass("active")) {
            $(this).parent().next().removeClass("active")
        } else {
            $(this).parent().next().addClass("active");
        }
    });

    /* custom upload file name */
    $("#atbd_review_attachment").on("change", function () {
        var file = $("#atbd_review_attachment")[0].files[0].name;
        $("#file_name").html(file);
    });

    $(".atbdp_child_category").hide();
    $(".atbdp_parent_category > li > span").on("click", function () {
        $(this).siblings(".atbdp_child_category").slideToggle();
        if ($(this).hasClass("active")) {
            $(this).removeClass("active")
        } else {
            $(this).addClass("active");
        }
    });

    //custom scrollbar
    $(".tags-checklist, .showContent").mCustomScrollbar({
        axis: "y",
        scrollInertia: 300,
        scrollEasing: "easeIn"
    });

    //show more / less js for features in sidebar search
    $(".filter-checklist .show-link").on("click", function (e) {
        e.preventDefault();
        var $this = $(this);
        var $content = $this.prev(".feature-checklist");
        var linkText = $this.text().toUpperCase();

        if (linkText === "نمایش بیشتر") {
            linkText = "نمایش کمتر";
            $content.toggleClass("hideContent").addClass("showContent");
        } else {
            linkText = "نمایش بیشتر";
            $content.toggleClass("showContent").addClass("hideContent");
        }
        $this.text(linkText);
    });


    // Price Range Slider
    var slider_range = $(".slider-range");
    slider_range.each(function () {
        $(this).slider({
            range: true,
            min: 0,
            max: 5000,
            values: [150, 2000],
            slide: function (event, ui) {
                $(".amount").text("$" + ui.values[0] + " - $" + ui.values[1]);
            }
        });
    });
    $(".amount").text("$" + slider_range.slider("values", 0) + " - $" + slider_range.slider("values", 1));

    //add listing pricing options
    var priceOne = $("#price-input");
    var priceTwo = $("#price-input-range").hide();
    $("#price_one").on("click", function (e) {
        if ($(this).is(":checked")) {
            priceOne.show();
            $("#price_two").prop("checked", false);
            priceTwo.hide();
        } else {
            e.preventDefault();
        }
    });
    $("#price_two").on("click", function (e) {
        if ($(this).is(":checked")) {
            priceTwo.show();
            $("#price_one").prop("checked", false);
            priceOne.hide();
        } else {
            e.preventDefault();
        }
    });

    // social information in add listing page
    $(".copy-btn").on("click", function (e) {
        var $el = $("#social-form-fields");
        $el.clone(true).appendTo(".atbdp_social_field_wrapper");
        e.preventDefault();
    });
    $("#removeSocial").on("click", function () {
        $(this).parents("#social-form-fields:not(:first-child)").remove();
    });

    //map coordinate
    var menual_cor = $("#hide_if_no_manual_cor").hide();
    $("#manual_coordinate").on("click", function () {
        if ($(this).is(":checked")) {
            menual_cor.show();
        } else {
            menual_cor.hide();
        }
    });

    //counter
    $(".count_up").counterUp({
        time: 1000
    });

    //search categories
    var search_field = $(".top-search-field");
    search_field.on("click", function (e) {
        $(this).parents(".search_module").addClass("active");
        e.stopPropagation();
    });
    $(document).on("click", function () {
        $(".search_module").removeClass("active");
    });

    /* offcanvas menu */
    var oc_menu = $(".offcanvas-menu__contents");
    $(".offcanvas-menu__user").on("click", function (e) {
        oc_menu.addClass("active");
        e.preventDefault();
    });
    $(".offcanvas-menu__close").on("click", function (e) {
        oc_menu.removeClass("active");
        e.preventDefault();
    });

    //Video Popup
    $('.video-iframe').magnificPopup({
        type: 'iframe',
        iframe: {
            markup: '<div class="mfp-iframe-scaler">' +
                '<div class="mfp-close"></div>' +
                '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
                '</div>',
            patterns: {
                youtube: {
                    index: 'youtube.com/',
                    id: function (url) {
                        var m = url.match(/[\\?\\&]v=([^\\?\\&]+)/);
                        if (!m || !m[1]) return null;
                        return m[1];
                    },
                    src: '//www.youtube.com/embed/%id%?rel=0&autoplay=1'
                },
                vimeo: {
                    index: 'vimeo.com/',
                    id: function (url) {
                        var m = url.match(/(https?:\/\/)?(www.)?(player.)?vimeo.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/);
                        if (!m || !m[5]) return null;
                        return m[5];
                    },
                    src: '//player.vimeo.com/video/%id%?autoplay=1'
                }
            },
            srcAction: 'iframe_src'
        },
        mainClass: 'mfp-fade'
    });

    // Reload captcha
    $('.form-captcha').on('click', function () {
        var action, sameAction, sameImgSrc;
        action = $(this).closest('.form-account-captcha').data('captcha-url');
        sameAction = $('[data-captcha-url="' + action + '"]');
        sameAction.first().find('img').attr('src', baseUrl + 'captcha/' + action + '?' + Date.now());
        sameImgSrc = sameAction.first().find('img').attr('src');
        sameAction.each(function () {
            $(this).find('img').attr('src', sameImgSrc);
        });
    }).trigger('click');

    $('.smooth-scroll').on('click', function (e) {
        e.preventDefault();
        var target = $(this).attr('href');
        $.omega().scrollToElement(target, -30);
    });

    /**
     * Super global shop object that have required functionality
     * @constructor
     */
    var Omega = function () {
        //************************************************************
        //********************* Local Operations *********************
        //************************************************************

        //------------------------------
        //---- Variables & Objects -----
        //------------------------------
        var
            _this = this,
            body = $('body'),
            //-----
            is_connect = false,
            is_local = true, // Change this after development (for release it must be false)
            //-----
            info_icon = 'la la-info-circle',
            warning_icon = 'la la-bell',
            success_icon = 'la la-check',
            danger_icon = 'la la-times';

        var
            loader = "<div class='custom-loader-modal'>\
                         <div class='custom-loader-contents'>\
                            <img class='custom-loader-img' src='" + baseUrl + siteLogo + "' alt=''/>\
                            <div class='custom-loader-loader'>\
                                <svg class=\"circular\" viewBox=\"25 25 50 50\">\
                                    <circle class=\"base-path\" cx=\"50\" cy=\"50\" r=\"20\" fill=\"none\" stroke-width=\"1\" stroke-miterlimit=\"10\"/>\
                                    <circle class=\"path\" cx=\"50\" cy=\"50\" r=\"20\" fill=\"none\" stroke-width=\"2\" stroke-miterlimit=\"10\"/>\
                                </svg>\
                            </div>\
                         </div>\
                       </div>",
            loader_relative = "<div class='custom-loader-modal is-relative'>\
                         <div class='custom-loader-contents'>\
                            <img class='custom-loader-img' src='" + baseUrl + siteLogo + "' alt=''/>\
                            <div class='custom-loader-loader'>\
                                <svg class=\"circular\" viewBox=\"25 25 50 50\">\
                                    <circle class=\"base-path\" cx=\"50\" cy=\"50\" r=\"20\" fill=\"none\" stroke-width=\"1\" stroke-miterlimit=\"10\"/>\
                                    <circle class=\"path\" cx=\"50\" cy=\"50\" r=\"20\" fill=\"none\" stroke-width=\"2\" stroke-miterlimit=\"10\"/>\
                                </svg>\
                            </div>\
                         </div>\
                       </div>",
            loader_limited = "<div class='custom-loader-modal limited'>\
                                 <div class='custom-loader-contents limited'>\
                                    <div class='custom-loader-loader'>\
                                        <svg class=\"circular\" viewBox=\"25 25 50 50\">\
                                            <circle class=\"base-path\" cx=\"50\" cy=\"50\" r=\"20\" fill=\"none\" stroke-width=\"1\" stroke-miterlimit=\"10\"/>\
                                            <circle class=\"path\" cx=\"50\" cy=\"50\" r=\"20\" fill=\"none\" stroke-width=\"2\" stroke-miterlimit=\"10\"/>\
                                        </svg>\
                                    </div>\
                                 </div>\
                               </div>",
            loader_limited_type2 = "<div class='custom-loader-modal limited type-2'>\
                                 <div class='custom-loader-contents limited type-2'>\
                                    <div class='custom-loader-loader'>\
                                        <svg class=\"circular\" viewBox=\"25 25 50 50\">\
                                            <circle class=\"base-path\" cx=\"50\" cy=\"50\" r=\"20\" fill=\"none\" stroke-width=\"1\" stroke-miterlimit=\"10\"/>\
                                            <circle class=\"path\" cx=\"50\" cy=\"50\" r=\"20\" fill=\"none\" stroke-width=\"2\" stroke-miterlimit=\"10\"/>\
                                        </svg>\
                                    </div>\
                                 </div>\
                               </div>";

        var defDoneFn, defFailFn, defAlwaysFn;

        defDoneFn = function () {
        };
        defFailFn = function (jqXHR, textStatus) {
            _this.showMessage('خطا در ارسال/دریافت اطلاعات، لطفا دوباره تلاش نمایید!', 'خطا', 'error', danger_icon);
        };
        defAlwaysFn = function () {
            _this.isInProgress = false;

            // Remove loader
            _this.removeLoader();
        };

        //************************************************************
        //******************** Public Operations *********************
        //************************************************************

        //------------------------------
        //---- Variables & Objects -----
        //------------------------------
        _this.isInProgress = false;
        _this.messageIcon = {
            info: info_icon,
            warning: warning_icon,
            danger: danger_icon,
            success: success_icon
        };

        //------------------------------
        //--------- Functions ----------
        //------------------------------
        _this.ajaxRequest = function (ajaxObj, doneFn, failFn, alwaysFn) {
            if (_this.hasInternetConnection) {
                if (_this.isInProgress) return;
                _this.isInProgress = true;

                doneFn = typeof doneFn === typeof function () {
                } ? doneFn : defDoneFn;
                failFn = typeof failFn === typeof function () {
                } ? failFn : defFailFn;
                alwaysFn = typeof alwaysFn === typeof function () {
                } ? alwaysFn : defAlwaysFn;

                if (_this.showLoader) {
                    // Add loader
                    _this.addLoader();
                }

                $.ajax(ajaxObj).done(doneFn).fail(failFn).always(alwaysFn);
            } else {
                _this.showMessage('ابتدا اتصال اینترنت را برقرار کنید و سپس تلاش نمایید!', 'اخطار', 'warning', _this.messageIcon.warning);
            }
        };
        _this.processAjaxData = function (response, callback) {
            var content, msg;
            if (response.success) {
                msg = Array.isArray(response.success.msg) ? response.success.msg[0] : (response.success.msg ? response.success.msg : '');
                content = Array.isArray(response.success.msg) && typeof response.success.msg[1] !== typeof undefined ? response.success.msg[1] : undefined;
                if (msg != '') {
                    _this.showMessage(msg, 'موفقیت', 'success', _this.messageIcon.success);
                }
            } else if (response.info) {
                msg = Array.isArray(response.info.msg) ? response.info.msg[0] : (response.info.msg ? response.info.msg : '');
                content = Array.isArray(response.info.msg) && typeof response.info.msg[1] !== typeof undefined ? response.info.msg[1] : undefined;
                if (msg != '') {
                    _this.showMessage(msg, 'اطلاع', 'info', _this.messageIcon.info);
                }
            } else if (response.warning) {
                msg = Array.isArray(response.warning.msg) ? response.warning.msg[0] : (response.warning.msg ? response.warning.msg : '');
                content = Array.isArray(response.warning.msg) && typeof response.warning.msg[1] !== typeof undefined ? response.warning.msg[1] : undefined;
                if (msg != '') {
                    _this.showMessage(msg, 'اخطار', 'warning', _this.messageIcon.warning);
                }
            } else if (response.error) {
                msg = Array.isArray(response.error.msg) ? response.error.msg[0] : (response.error.msg ? response.error.msg : '');
                content = Array.isArray(response.error.msg) && typeof response.error.msg[1] !== typeof undefined ? response.error.msg[1] : undefined;
                if (msg != '') {
                    _this.showMessage(msg, 'خطا', 'error', _this.messageIcon.danger);
                }
            } else {
                console.error('داده دریافتی دچار مشکل شده است! لطفا صفحه را دوباره بارگذاری کنید.');
                return;
            }

            if (typeof callback === typeof function () {
            }) {
                callback.call(this, content);
            }
        };

        _this.showLoader = true;
        _this.addLoader = function (to, limited, type) {
            var which;
            if (typeof to !== typeof undefined && $(to).length) {
                to = to && $(to).length ? $(to) : body;
                limited = limited === true;
                which = limited ? (type == 2 ? loader_limited_type2 : loader_limited) : loader_relative;
            } else {
                to = body;
                which = loader;
            }

            to.append(which).find('.custom-loader-modal').css('visibility', 'visible')
                .animate({
                    'opacity': 1
                }, 300);
        };
        _this.removeLoader = function () {
            body.find('.custom-loader-modal').fadeOut(300, function () {
                $(this).remove()
            });
        };
        _this.scrollToElement = function (el, distance) {
            var top;
            el = el && $(el).length ? $(el) : 'html, body';
            top = el === 'html, body' ? 0 : el.offset().top;
            top += typeof distance === typeof 1 ? distance : 0;
            $('html, body').stop().animate({
                scrollTop: top
            }, 300);
        };
        _this.showMessage = function (message, title, type, icon, theme, overlay, position, draggable) {
            message = message ? message : '';
            title = title ? title : '';
            type = type ? type : 'dark';
            icon = icon ? icon : '';
            theme = theme ? theme : 'light';
            position = position ? position : 'topRight';
            draggable = draggable ? draggable : true;

            // For iziToast
            overlay = overlay === true;

            if (iziToast) {
                var iziObj = {
                    theme: type == 'dark' ? 'dark' : theme,
                    icon: icon,
                    title: title,
                    message: message,
                    rtl: true,
                    close: false,
                    displayMode: 'replace',
                    overlay: overlay,
                    drag: draggable,
                    position: position
                };

                switch (type) {
                    case 'info':
                        iziToast.info(iziObj);
                        break;
                    case 'success':
                        iziToast.success(iziObj);
                        break;
                    case 'warning':
                        iziToast.warning(iziObj);
                        break;
                    case 'error':
                        iziToast.error(iziObj);
                        break;
                    default:
                        iziToast.show(iziObj);
                        break;
                }
            } else if ($.alert) {
                $.alert({
                    theme: theme,
                    icon: icon,
                    title: title,
                    content: message,
                    type: type,
                    rtl: true,
                    backgroundDismiss: true,
                    animationSpeed: 240,
                    // closeIcon: true
                });
            } else {
                alert(message);
            }
        };
        _this.question = function (message, okCallback) {
            message = message ? message : 'آیا مطمئن هستید؟';
            if (iziToast) {
                iziToast.show({
                    theme: 'dark',
                    timeout: 30000,
                    close: false,
                    overlay: true,
                    displayMode: 'once',
                    position: 'center',
                    message: message,
                    buttons: [
                        ['<button><b>بله</b></button>', function (instance, toast) {
                            instance.hide({transitionOut: 'fadeOut'}, toast, 'button');
                            // -----
                            if (typeof okCallback === 'function') {
                                okCallback.call(this);
                            }
                        }, true],
                        ['<button>خیر</button>', function (instance, toast) {
                            instance.hide({transitionOut: 'fadeOut'}, toast, 'button');
                        }],
                    ],
                });
            } else {
                var sure = confirm(message);
                if (sure) {
                    if (typeof okCallback === 'function') {
                        okCallback.call(this);
                    }
                }
            }
        };
        _this.updateStatus = function () {
            if (navigator.onLine || is_local) {
                is_connect = true;
                _this.hasInternetConnection = is_connect;
            }
        };
        _this.log = function (context, ...parameters) {
            console.log(context, parameters)
        };

        //------------------------------
        //----------- Events -----------
        //------------------------------
        /* Update the online status icon based on connectivity */
        window.addEventListener('online', _this.updateStatus);
        window.addEventListener('offline', _this.updateStatus);

        //------------------------------
        //------- Call Functions -------
        //------------------------------
        _this.updateStatus();
    };
    $.omega = function () {
        return new Omega();
    };
})(jQuery);