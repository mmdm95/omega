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

        //********** ManageBlockedUser Action
        $('.deleteBlockedUserBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteBlockedUser', function () {
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

        //********** ManageFeedback Action
        $('.deleteFeedbackBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteFeedback', function () {
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

        //********** ManageNewsletter Action
        $('.deleteNewsletterBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteNewsletter', function () {
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

        //********** ManageStaticPage Action
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

        //********** ManagePlan Action
        $('.deletePlanBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deletePlan', function () {
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

        //********** ManageUsefulLink Action
        $('.deleteUsefulLinkBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteUsefulLink', function () {
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

        //********** ManageFAQ Action
        $('.deleteFAQBtn').on('click', function (e) {
            e.preventDefault();
            var del_btn = $(this);

            delete_something_action(this, 'deleteFAQ', function () {
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
                            if (typeof callback === typeof function () {
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

        //********** Publish change for Feedback
        $('.feedback-publish').on('change', function (e) {
            e.preventDefault();
            var btn = $(this);
            var stat = btn.is(':checked') ? 1 : 0;

            active_deactive_action(this, 'publishFeedback', {stat: stat});
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

        //********** Publish change for Plans
        $('.plan-publish').on('change', function (e) {
            e.preventDefault();
            var btn = $(this);
            var stat = btn.is(':checked') ? 1 : 0;

            active_deactive_action(this, 'publishPlan', {stat: stat});
        });
        //**********

        //********** Change plan status for ManagePlans
        $.change_plan_status = function (selector, stat) {
            active_deactive_action(selector, 'changePlanStatus', {stat: stat});
        };
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