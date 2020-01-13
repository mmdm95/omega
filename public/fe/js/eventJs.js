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
            pricePlaceholder.html(parseInt(price, 10));
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