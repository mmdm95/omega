(function ($) {
    $(function () {

        'use strict';

        var
            name = 'myCustom';

        var
            clsBrD = 'border-danger',
            clsBrW = 'border-warning',
            clsBrS = 'border-success',

            clsBgD = 'bg-danger',
            clsBgW = 'bg-warning',
            clsBgS = 'bg-success';

        var validateType = {
            email: 'email',
            date: 'date',
            passMatch: 'passMatch'
        };

        // User Formats
        $('.phone-format').formatter({
            pattern: '{{999}}-{{9999}} {{9999}}'
        });
        $('.mobile-format').formatter({
            pattern: '{{9999}} {{999}} {{9999}}'
        });
        $('.nCode-format').formatter({
            pattern: '{{9999999999}}'
        });
        $('.cardNum-format').formatter({
            pattern: '{{9999}} {{9999}} {{9999}} {{9999}}'
        });
        $('.accountNum-format').formatter({
            pattern: '{{99999999999999999999999999999999999999999999999999}}'
        });
        $('.date-format').formatter({
            pattern: '{{9999}}/{{99}}/{{99}}',
            persistent: true
        });

        var passInp = $('.pass-format');
        // User password
        if ($.passy) {
            // Requirements a inserted password should meet
            $.passy.requirements = {

                // Character types a password should contain
                characters: [
                    $.passy.character.DIGIT,
                    $.passy.character.LOWERCASE
                ],

                // A minimum and maximum length
                length: {
                    min: 8,
                    max: Infinity
                }
            };

            passInp.passy(function (strength, valid) {
                var color, noColor;
                // Set color basd on validness and strength
                if (valid) {
                    if (strength < $.passy.strength.HIGH) {
                        color = clsBrS;
                        noColor = clsBrD + ' ' + clsBrW;
                        $(this).attr('data-content', 'هنوز می‌تواند بهتر شود').removeClass('invalid').popover('show');
                    } else {
                        color = clsBrS;
                        noColor = clsBrD + ' ' + clsBrW;
                        $(this).attr('data-content', 'کلمه عبور مناسب می‌باشد').removeClass('invalid').popover('show');
                    }
                } else {
                    color = clsBrD;
                    noColor = clsBrW + ' ' + clsBrS;
                    $(this).attr('data-content', 'حداقل ۸ کاراکتر و شامل اعداد و حروف کوچک').addClass('invalid').popover('show');
                }

                $(this).removeClass(noColor).addClass(color);
            });
        }

        var emailInp = $('.email-format');
        validator(emailInp, validateType.email, function (self) {
            $(self).attr('data-content', 'ایمیل معتبر است').popover('show');
        }, function (self) {
            $(self).attr('data-content', 'ایمیل نامعتبر').popover('show');
        });

        var dateInp = $('.date-format');
        validator(dateInp, validateType.date, function (self) {
            $(self).attr('data-content', 'تاریخ معتبر است').popover('show');
        }, function (self) {
            $(self).attr('data-content', 'تاریخ نامعتبر').popover('show');
        });

        var passMatchInp = $('.pass-match');
        validator(passMatchInp, validateType.passMatch, function(self) {
            $(self).popover('hide');
        }, function (self) {
            $(self).attr('data-content', 'کلمه عبور با تکرار آن مغایرت دارد').popover('show');
        });

        var submitBtn = $('.submit-button');
        submitBtn.on('click.' + name, function (e) {
            var closestFrm = $(this).closest('.validation-form');
            var invalidInps = closestFrm.find('.invalid.required');
            if(invalidInps.length) {
                e.preventDefault();
                invalidInps.popover('show');
            }
        });

        function validator(selector, type, validateCallback, invalidateCallback) {
            if (selector) {
                selector = $(selector);
                selector.each(function () {
                    var self = $(this), validate;
                    self.off('focus.' + name).on('focus.' + name, function () {
                        if (self.val().trim() != '') {
                            validate = validateFunc(self.val(), type);
                            if (validate) {
                                if (typeof validateCallback == typeof function () {
                                    }) {
                                    validateCallback.apply(null, [self]);
                                }
                                self.removeClass(clsBrD + ' ' + clsBrW).addClass(clsBrS).removeClass('invalid');
                            } else {
                                if (typeof invalidateCallback == typeof function () {
                                    }) {
                                    invalidateCallback.apply(null, [self]);
                                }
                                self.removeClass(clsBrW + ' ' + clsBrS).addClass(clsBrD);
                                if(type != validateType.date) self.addClass('invalid');
                            }
                        } else {
                            self.removeClass(clsBrW + ' ' + clsBrS + ' ' + clsBrD);
                        }
                    }).off('keyup.' + name).on('keyup.' + name, function () {
                        if (self.val().trim() != '') {
                            validate = validateFunc(self.val(), type);
                            if (validate) {
                                if (typeof validateCallback == typeof function () {
                                    }) {
                                    validateCallback.apply(null, [self]);
                                }
                                self.removeClass(clsBrD + ' ' + clsBrW).addClass(clsBrS).removeClass('invalid');
                            } else {
                                if (typeof invalidateCallback == typeof function () {
                                    }) {
                                    invalidateCallback.apply(null, [self]);
                                }
                                self.removeClass(clsBrW + ' ' + clsBrS).addClass(clsBrD);
                                if(type != validateType.date) self.addClass('invalid');
                            }
                        } else {
                            self.removeClass(clsBrW + ' ' + clsBrS + ' ' + clsBrD);
                        }
                    });
                });
            }
        }

        function validateFunc(str, type) {
            var emailExp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                dateExp = /(13|14)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])/g;
            if (type) {
                switch (type) {
                    case validateType.email:
                        return emailExp.test(String(str).toLowerCase());
                        break;
                    case validateType.date:
                        return dateExp.test(String(str).toLowerCase());
                        break;
                    case validateType.passMatch:
                        if(passInp.length) {
                            return passInp.val() === str;
                        }
                        return false;
                        break;
                }
            }
            return false;
        }
    });
})(jQuery);