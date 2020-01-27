(function ($) {
    'use strict';

    $(function () {
        var
            namespace = 'omega',
            //-----
            tableSingle,
            tableMulti,
            //-----
            inpForcedRadio,
            inpRadio,
            inpCheckbox,
            inpRadioClass = '.event-radio-input',
            inpCheckboxClass = '.event-checkbox-input',
            //-----
            pricePlaceholder,
            totalPrice,
            //-----
            dataOptionIdx;

        /*************************
         *************************
         *************************/
        function radioChange() {
            inpRadio.on('change.' + namespace, function () {
                priceCalculation();
            });
        }

        function checkboxChange() {
            inpCheckbox.on('change.' + namespace, function () {
                priceCalculation();
            });
        }

        function priceCalculation() {
            var $this, optionIdx, idxArr;
            var radio, checkbox;
            //-----
            totalPrice = 0;
            //-----
            tableSingle.each(function () {
                $this = $(this);
                radio = $this.find(inpRadioClass + ':checked').last();
                optionIdx = radio.data(dataOptionIdx);
                if (optionIdx) {
                    idxArr = optionIdx.split('-');
                    if (2 === idxArr.length) {
                        if (options[idxArr[0]]['price'][idxArr[1]]) {
                            totalPrice += parseInt(options[idxArr[0]]['price'][idxArr[1]], 10);
                        }
                    }
                }
            });
            //-----
            tableMulti.each(function () {
                $this = $(this);
                checkbox = $this.find(inpCheckboxClass + ':checked');
                checkbox.each(function () {
                    $this = $(this);
                    optionIdx = $this.data(dataOptionIdx);
                    if (optionIdx) {
                        idxArr = optionIdx.split('-');
                        if (2 === idxArr.length) {
                            if (options[idxArr[0]]['price'][idxArr[1]]) {
                                totalPrice += parseInt(options[idxArr[0]]['price'][idxArr[1]], 10);
                            }
                        }
                    }
                });
            });
            //-----
            setPrice(totalPrice);
        }

        function setPrice(price) {
            price = !isNaN(parseInt(price, 10)) ? (parseInt(baseEventPrice, 10) + parseInt(price, 10)) : 0;
            pricePlaceholder.html(numberFormat(price, 0));
        }

        function numberFormat(n, c, t, d) {
            c = isNaN(c = Math.abs(c)) ? 2 : c;
            d = d === undefined ? "." : d;
            t = t === undefined ? "," : t;
            var s = n < 0 ? "-" : "",
                i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
                j = (j = i.length) > 3 ? j % 3 : 0;

            return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
        }

        function prepare() {
            tableSingle = $('.table-single-select');
            tableMulti = $('.table-multi-select');
            //-----
            inpForcedRadio = $('[data-is-forced="forced"]');
            inpRadio = $(inpRadioClass);
            inpCheckbox = $(inpCheckboxClass);
            //-----
            pricePlaceholder = $('#selected-items-price');
            totalPrice = 0;
            //-----
            dataOptionIdx = 'option-idx';
            //-----
            radioChange();
            checkboxChange();
        }

        function log(context, ...parameters) {
            console.log(context, parameters)
        }

        /*************************
         *************************
         *************************/
        prepare();
        priceCalculation();
    });

    $(function () {
        var
            namespace = 'omega',
            omega = $.omega(),
            //-----
            load_more_cmt_btn,
            comments_container,
            //-----
            page_num = 2,
            //-----
            comment_url = baseUrl + 'event/ajaxLoadMoreComments';

        function moreCommentBtnClick() {
            var $this;
            load_more_cmt_btn.on('click.' + namespace, function () {
                $this = $(this);
                //-----
                $this.find('i').addClass('la-spin');
                //-----
                var d = {
                    planId: plan_id,
                    page: page_num
                };
                //-----
                omega.ajaxRequest({
                    method: 'POST',
                    url: comment_url,
                    data: d,
                }, function (response) {
                    // console.log(response);
                    // console.log(JSON.parse(response));
                    var res = JSON.parse(response);
                    if (res.success) {
                        if(res.success.msg) {
                            page_num++;
                            //-----
                            if (res.success.msg[0]) {
                                comments_container.append(res.success.msg[0]);
                            }
                            if (!res.success.msg[1]) {
                                load_more_cmt_btn.remove();
                            }
                        } else {
                            omega.showMessage('خطا در واکشی اطلاعات!', 'خطا', 'error', omega.messageIcon.danger);
                        }
                    } else {
                        if(res.error.msg) {
                            omega.showMessage(res.error.msg, 'خطا', 'error', omega.messageIcon.danger);
                        }
                    }
                    //-----
                    $this.find('i').removeClass('la-spin');
                });
            });
        }

        function prepare() {
            load_more_cmt_btn = $('#loadMoreComment');
            comments_container = $('#commentsContainer');
        }

        prepare();
        moreCommentBtnClick();
    });
})(jQuery);