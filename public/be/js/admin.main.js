(function ($) {

    'use strict';

    $(function () {
        var default_rout = $('#BASE_URL').val() + 'admin/';
        var dataTable = $('.datatable-highlight');

        //********** ManageUser Action
        $('.deleteUserBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteUser', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });
        //**********

        //********** ManageBrand Action
        $('.deleteBrandBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteBrand', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });
        //**********

        //********** ManageGuarantee Action
        $('.deleteGuaranteeBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteGuarantee', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });
        //**********

        //********** ManageShipping Action
        $('.deleteShippingBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteShipping', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });
        //**********

        //********** ManagePayment Action
        $('.deletePaymentBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deletePayment', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });
        //**********

        //********** ManageCoupon Action
        $('.deleteCouponBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteCoupon', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });
        //**********

        //********** ManageColor Action
        $('.deleteColorBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteColor', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });
        //**********

        //********** ManageCategory Action
        $('.deleteStaticPageBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteStaticPage', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });
        //**********

        //********** ManageCategory Action
        $('.deleteCategoryBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteCategory', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });
        //**********

        //********** ManageTitle Action
        $('.deleteTitleBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteTitle', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });
        //**********

        //********** ManageProperty Action
        $('.deletePropertyBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteProperty', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });
        //**********

        //********** ManageProducts Action
        $('.deleteProductBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteProduct', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });
        //**********

        //********** ManageFestival Action
        $('.deleteFestivalProductBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteFestivalProduct', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });
        //**********

        //********** ManageFestival Action
        $('.deleteFestivalBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteFestival', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });
        //**********

        //********** ManageComments Action
        $('.deleteCommentBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteComment', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });
        //**********

        //********** ManageComments Action (inside of viewComment)
        $('#delCommentBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteComment', function () {
                setTimeout(function () {
                    window.location.href = default_rout + 'manageComment';
                }, 2000);
            });
        });
        //**********

        //********** ManageComments Action (inside of viewComment)
        $('#acceptCommentBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'acceptComment', function () {
                setTimeout(function () {
                    window.location.reload();
                }, 2000);
            });
        });
        //**********

        //********** ManageComments Action (inside of viewComment)
        $('#declineCommentBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'declineComment', function () {
                setTimeout(function () {
                    window.location.reload();
                }, 2000);
            });
        });
        //**********

        //********** ManageSlider Action
        $('.deleteSliderBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteSlider', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });
        //**********

        //********** ManageContacts Action
        $('.deleteContactBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteContact', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });
        //**********

        //********** ManageContacts Action (inside of viewContact)
        $('#delContactBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteContact', function () {
                setTimeout(function () {
                    window.location.href = default_rout + 'manageContactUs';
                }, 2000);
            });
        });
        //**********

        //********** ManageSlider Action
        $('.deleteSlideBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteSlide', function () {
                $(del_btn).closest('tr').fadeOut(800, function () {
                    if ($.fn.DataTable) {
                        dataTable.DataTable().row($(this)).remove().draw();
                    } else {
                        $(this).remove();
                    }
                });
            });
        });

        //**********

        function delete_something_action(selector, sendUrl, callback) {
            var _this = this, del_btn = $(selector);
            var id = $(del_btn).find('input[type=hidden]').val();
            if ($.trim(id) != '') {
                var sure = confirm('آیا مطمئن هستید؟');
                if (sure) {
                    var url = default_rout + sendUrl;
                    $.post(url, {
                        postedId: id
                    }, function (data, status) {
                        var opts, action = false;

                        console.log(data);

                        data = JSON.parse(data);
                        if (status == 'success') {
                            if (data.success) {
                                opts = {
                                    title: "موفقیت",
                                    text: data.success.msg,
                                    icon: 'icon-checkmark-circle2',
                                    type: "success",
                                    addclass: 'border-success-600 bg-success-600 stack-top-left',
                                    buttons: {
                                        sticker: false
                                    }
                                };

                                action = true;
                            } else {
                                opts = {
                                    title: "خطا",
                                    text: data.error.msg,
                                    icon: 'icon-exclamation',
                                    type: "error",
                                    addclass: 'border-danger-400 bg-danger-400 stack-top-left',
                                    buttons: {
                                        sticker: false
                                    }
                                };
                            }
                        } else {
                            opts = {
                                title: "خطا",
                                text: "خطای پیش‌بینی نشده! وضعیت خطا:" + status,
                                icon: 'icon-exclamation',
                                type: "error",
                                addclass: 'border-danger-400 bg-danger-400 stack-top-left',
                                buttons: {
                                    sticker: false
                                }
                            };
                        }

                        new PNotify(opts);
                        if (action) {
                            if (typeof callback == typeof function () {
                            }) {
                                callback.apply(null);
                            }
                        }
                    });
                }
            }
        }

        //********** Active/Deactive user
        $('.uActiveDeactiveBtn').on('change', function (e) {
            e.preventDefault();
            var btn = $(this);
            var stat = btn.is(':checked') ? 1 : 0;

            active_deactive_action(this, 'activeDeactive', {stat: stat});
        });
        //**********

        //********** Show in pages for Brands
        $('.showBrandInPages').on('change', function (e) {
            e.preventDefault();
            var btn = $(this);
            var stat = btn.is(':checked') ? 1 : 0;

            active_deactive_action(this, 'showBrandInPages', {stat: stat});
        });
        //**********

        //********** Show in menu for Categories
        $('.showInMenuParts').on('change', function (e) {
            e.preventDefault();
            var btn = $(this);
            var stat = btn.is(':checked') ? 1 : 0;

            active_deactive_action(this, 'showInMenu', {stat: stat});
        });
        //**********

        //********** Product availability
        $('.productAvailability').on('change', function (e) {
            e.preventDefault();
            var btn = $(this);
            var stat = btn.is(':checked') ? 1 : 0;

            active_deactive_action(this, 'productAvailability', {stat: stat});
        });
        //**********

        //********** Set a festival to main festival
        $('.festivalIsMain').on('change', function (e) {
            e.preventDefault();
            var btn = $(this);
            var stat = btn.is(':checked') ? 1 : 0;

            active_deactive_action(this, 'setFestivalToMainOne', {stat: stat}, function () {
                // This is just a trick not a good solution
                // it just work!!
                $('.festivalIsMain').not(btn).prop('checked', '').removeAttr('checked')
                    .next('.switchery').css({
                    '-webkit-box-shadow': 'rgb(223, 223, 223) 0px 0px 0px 0px inset',
                    '-moz-box-shadow': 'rgb(223, 223, 223) 0px 0px 0px 0px inset',
                    'box-shadow': 'rgb(223, 223, 223) 0px 0px 0px 0px inset',
                    'border-color': 'rgb(223, 223, 223)',
                    'background-color': 'rgb(255, 255, 255)',
                    '-webkit-transition': 'border 0.4s ease 0s, box-shadow 0.4s ease 0s',
                    '-moz-transition': 'border 0.4s ease 0s, box-shadow 0.4s ease 0s',
                    '-ms-transition': 'border 0.4s ease 0s, box-shadow 0.4s ease 0s',
                    '-o-transition': 'border 0.4s ease 0s, box-shadow 0.4s ease 0s',
                    'transition': 'border 0.4s ease 0s, box-shadow 0.4s ease 0s',
                }).children('small').css({
                    'left': '0px',
                    '-webkit-transition': 'background-color 0.4s ease 0s, left 0.2s ease 0s',
                    '-moz-transition': 'background-color 0.4s ease 0s, left 0.2s ease 0s',
                    '-ms-transition': 'background-color 0.4s ease 0s, left 0.2s ease 0s',
                    '-o-transition': 'background-color 0.4s ease 0s, left 0.2s ease 0s',
                    'transition': 'background-color 0.4s ease 0s, left 0.2s ease 0s'
                });
            });
        });
        //**********

        //********** accept/Deaccept comment
        $('.showCommentInPageBtn').on('change', function (e) {
            e.preventDefault();
            var btn = $(this);
            var stat = btn.is(':checked') ? 1 : 0;

            active_deactive_action(this, 'showCommentInPage', {stat: stat});
        });

        //**********

        function active_deactive_action(selector, sendUrl, params, callback) {
            var _this = this, btn = $(selector);
            var id = $(btn).parent().find('input[type=hidden]').val();
            if ($.trim(id) != '') {
                var url = default_rout + sendUrl;
                params = $.extend({postedId: id}, params);
                $.post(url, params, function (data, status) {
                    var opts, action = false;

                    // console.log(data);
                    data = JSON.parse(data);
                    if (status == 'success') {
                        if (data.success) {
                            opts = {
                                text: data.success.msg,
                                icon: 'icon-checkmark-circle2',
                                type: "success",
                                addclass: 'border-success-600 bg-success-600 stack-top-left',
                                buttons: {
                                    sticker: false
                                }
                            };

                            action = true;
                        } else if (data.warning) {
                            opts = {
                                text: data.warning.msg,
                                icon: 'icon-checkmark-circle2',
                                type: "warning",
                                addclass: 'border-grey-700 bg-grey-700 stack-top-left',
                                buttons: {
                                    sticker: false
                                }
                            };

                            action = true;
                        } else {
                            opts = {
                                title: "خطا",
                                text: data.error.msg,
                                icon: 'icon-exclamation',
                                type: "error",
                                addclass: 'border-danger-400 bg-danger-400 stack-top-left',
                                buttons: {
                                    sticker: false
                                }
                            };
                        }
                    } else {
                        opts = {
                            title: "خطا",
                            text: "خطای پیش‌بینی نشده! وضعیت خطا:" + status,
                            icon: 'icon-exclamation',
                            type: "error",
                            addclass: 'border-danger-400 bg-danger-400 stack-top-left',
                            buttons: {
                                sticker: false
                            }
                        };
                    }

                    new PNotify(opts);
                    if (action) {
                        if (typeof callback === typeof function () {
                        }) {
                            callback.apply(null);
                        }
                    }
                });
            }
        }
    });
})(jQuery);