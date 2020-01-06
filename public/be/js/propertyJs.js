(function ($) {
    'use strict';

    $(function () {
        var
            propertyContainer = '.property-items',
            propertyItem = '.property-item',
            propertyEachItem = '.property-each-item',

            btnPropertyContainer = '.property-operation-container',
            addProperty = '.property-operation-add',
            deleteProperty = '.property-operation-delete',

            successClass = 'bg-success-400',
            dangerClass = 'bg-danger-400',

            addIcon = 'icon-plus2',
            deleteIcon = 'icon-trash',

            inputNameStart = 'option_group[',
            titleNameEnd = '][title]',
            radioNameEnd = '][radio]',
            nameNameEnd = '][name][]',
            descNameEnd = '][desc][]',
            priceNameEnd = '][price][]',

            animateTime = 400;

        var addPropertyGroupFn = function (selector, e) {
            var container, item, deleteBtn, addBtn;

            container = $(propertyContainer);
            item = container.find(propertyItem).first().clone();

            item.find(propertyEachItem).not(item.find(propertyEachItem).first()).remove();
            item.find('.select2').remove();
            item.find('.bootstrap-tagsinput').remove();
            item.find('.p-item-select').find(':selected').removeAttr('selected')
                .end().children('option').first().attr('selected', 'selected');
            item.find('.p-item-input').val('');

            var lastIndex = container.find(propertyItem).last().index() + 1;
            item.find('input[type=text][name$="' + titleNameEnd + '"]').attr('name', inputNameStart + lastIndex + titleNameEnd);
            item.find('input[type=radio][name$="' + radioNameEnd + '"]').attr('name', inputNameStart + lastIndex + radioNameEnd)
                .prop('checked', false).attr('checked', false).first().prop('checked', 'checked').attr('checked', 'checked');
            item.find('input[type=text][name$="' + nameNameEnd + '"]').attr('name', inputNameStart + lastIndex + nameNameEnd);
            item.find('input[type=text][name$="' + descNameEnd + '"]').attr('name', inputNameStart + lastIndex + descNameEnd);
            item.find('input[type=text][name$="' + priceNameEnd + '"]').attr('name', inputNameStart + lastIndex + priceNameEnd);

            // Group remove button
            deleteBtn = item.children(btnPropertyContainer).find(addProperty);
            deleteBtn.removeClass(addProperty.substr(1) + ' ' + successClass)
                .addClass(deleteProperty.substr(1) + ' ' + dangerClass)
                .attr('title', 'حذف گروه‌بندی');
            deleteBtn.find('i').removeClass(addIcon).addClass(deleteIcon);

            // Group remove button event
            deleteBtn.on('click', function (e) {
                deletePropertyGroupFn(this, e);
            });

            // Property add button
            addBtn = item.find(propertyEachItem).first().find(addProperty);

            // Property add button event
            addBtn.on('click', function (e) {
                addPropertyFn(this, e);
            });

            // Append copied item to container
            container.append(item);

            // re initiate plugins
            reInitiatingPlugins();
        };

        var deletePropertyGroupFn = function (selector, e) {
            $(selector).closest(propertyItem).stop().fadeOut(animateTime, function () {
                $(this).remove();
            });
        };

        var addPropertyFn = function (selector, e) {
            var container, item, deleteBtn;

            container = $(selector).closest(propertyItem);
            item = container.find(propertyEachItem).first().clone();

            item.find('.select2').remove();
            item.find('.bootstrap-tagsinput').remove();
            item.find('.p-item-select').removeAttr('selected').first().attr('selected', 'selected');
            item.find('.p-item-input').val('');

            // Remove button
            deleteBtn = item.children(btnPropertyContainer).find(addProperty);
            deleteBtn.removeClass(addProperty.substr(1))
                .removeClass('bg-blue')
                .addClass(deleteProperty.substr(1))
                .addClass('btn-default')
                .attr('title', 'حذف آپشن');
            deleteBtn.find('i').removeClass(addIcon).addClass(deleteIcon);

            // Remove button event
            deleteBtn.on('click', function (e) {
                deletePropertyFn(this, e);
            });

            // Append copied item to container
            container.append(item);

            // re initiate plugins
            reInitiatingPlugins();
        };

        var deletePropertyFn = function (selector, e) {
            $(selector).closest(propertyEachItem).stop().slideUp(animateTime, function () {
                $(this).remove();
            });
        };

        var reInitiatingPlugins = function () {
            // re initiate select2 plugin
            $('.select').each(function () {
                var $this = $(this);
                if ($this.data('select2')) $this.select2("destroy");
                $this.select2({
                    // escapeMarkup: function (m) { return m; }
                });
            });

            // re initiate tagsinput plugin
            $('[data-role="tagsinput"]').each(function () {
                var $this = $(this);
                if ($this.data('tagsinput')) $this.tagsinput("destroy");
                $this.tagsinput();
            });

            // re initiate ripple plugin
            // $('.btn').each(function () {
            //     var $this = $(this);
            //     if($this.data('ripple')) $this.ripple("destroy");
            //     $this.ripple();
            // });
        };

        $(addProperty).on('click', function (e) {
            e.preventDefault();

            // Check clicked button parent to see what add to container
            if ($(this).closest(btnPropertyContainer).parent().hasClass(propertyItem.substr(1))) {
                addPropertyGroupFn(this, e);
            } else if ($(this).closest(btnPropertyContainer).parent().hasClass(propertyEachItem.substr(1))) {
                addPropertyFn(this, e);
            }
        });

        $(deleteProperty).on('click', function (e) {
            e.preventDefault();

            // Check clicked button parent to see what add to container
            if ($(this).closest(btnPropertyContainer).parent().hasClass(propertyItem.substr(1))) {
                deletePropertyGroupFn(this, e);
            } else if ($(this).closest(btnPropertyContainer).parent().hasClass(propertyEachItem.substr(1))) {
                deletePropertyFn(this, e);
            }
        });
    });
})(jQuery);