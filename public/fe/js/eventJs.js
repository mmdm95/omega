(function ($) {
    'use strict';

    $(function () {
        var
            namespace = 'omega',
            //-----
            tableSingle,
            tableMulti,
            //-----
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
})(jQuery);