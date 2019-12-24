(function ($) {
    'use strict';
    $(function () {
        var colorClassName = '.color-item';

        var showColor = function (color) {
            var originalOption = color.element;
            if(!color.id) return color.text;
            var $color = "<span class='display-inline-block img-xxs img-rounded shadow-depth2 pull-right'" +
                " style='background-color: " + $(originalOption).attr('data-color') + "'></span>" + color.text;

            return $color;
        };

        function delete_color_item(selector, e) {
            e.stopPropagation();

            var container = $('#color-items');
            container.addClass('overflow-hidden');
            $(selector).closest(colorClassName).animate({
                'position': 'absolute',
                'right': '-100%'
            }, 400, function () {
                $(this).remove();
                container.removeClass('overflow-hidden');
            });
        }

        $('.delete-new-color').on('click', function (e) {
            delete_color_item(this, e);
        });

        $('.add-color').on('click', function (e) {
            e.stopPropagation();

            var container = $('#color-items');
            var item = container.find(colorClassName).first().clone();

            var deleteBtn = $('<small class="delete-new-color btn btn-danger" title="حذف">&times;</small>');

            item.addClass('border-top border-grey-300 border-top-dashed');
            item.find('.select2').remove();
            item.find('.color-item-select').find(':selected').removeAttr('selected')
                .end().children('option').first().attr('selected', 'selected');
            item.find('.color-item-input').val('');

            deleteBtn.on('click', function (e) {
                delete_color_item(this, e);
            });
            item.find('.color-item-clear').append(deleteBtn);

            container.append(item);

            $('.my-custom-select').each(function () {
                var $this = $(this);
                if($this.data('select2')) $this.select2("destroy");
                $this.select2({
                    containerCssClass: 'rtl',
                    dropdownCssClass: 'rtl',
                    templateResult: showColor,
                    templateSelection: showColor,
                    escapeMarkup: function (m) { return m; }
                });
            });
        });
    });
})(jQuery);