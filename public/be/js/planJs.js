(function ($) {
    'use strict';

    function status_switch(val) {
        switch (val) {
            case status_activate:
                return 'فعال';
            case status_deactivate:
                return 'غیر فعال';
            case status_full:
                return 'ظرفیت تکمیل';
            case status_closed:
                return 'بسته شده';
        }
        return '';
    }

    $(function () {
        // Define element
        var statusSlider, $this, label, status;

        statusSlider = $('.status-slider');
        statusSlider.each(function () {
            $this = $(this);
            label = $this.parent().find('.status-slider-label');
            status = $this.data('plan-status');

            // Create slider
            noUiSlider.create(this, {
                range: {
                    'min': status_activate,
                    'max': status_closed,
                },
                step: 1,
                start: status,
                // tooltips: {
                //     to(value) {
                //         return status_switch(value);
                //     }
                // },
                // pips: {
                //     mode: 'steps',
                //     format: {
                //         to(value) {
                //             return status_switch(value);
                //         }
                //     },
                //     density: 100
                // },
            });

            var dateValues = [
                label.get(0)
            ];

            this.noUiSlider.on('update', function (values, handle) {
                var lbl, val;

                val = parseInt(values, 10);
                lbl = status_switch(val);

                dateValues[handle].innerHTML = lbl;
            });
            this.noUiSlider.on('end', function (values, handle) {
                var val;
                val = parseInt(values, 10);
                $.change_plan_status($this, val);
            });
        });
    });
})(jQuery);