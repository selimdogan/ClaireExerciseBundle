(function () {

    $.widget('OC.itemsList', {
        _create: function() {
            this.elements = {};
            this.elements.$tpl = this._getTemplateElement();
            this.element.addClass('claire-exercise-items-list');

            this._initDroppable();

            this._addEvents();

        },

        _initDroppable: function () {
            var self = this;
            this.element.droppable({
                scope: 'resources',
                tolerance: 'pointer',
                over: function( event, ui ) {
                    self.element.addClass('drop-over');
                },
                out: function () {
                    self.element.removeClass('drop-over');
                },
                drop: function (event, ui) {
                    self.element.removeClass('drop-over');
                    self.addItem(ui.draggable.data().dragConfig);
                }
            });
        },

        _addEvents: function () {
            var self = this;

            this.element
                .on('click', '.js-items-list-add', function () {
                    self.addItem();
                })
                .on('click', '.js-items-list-remove', function () {
                    self.removeItem($(this));
                });
        },

        _getTemplateElement: function () {
            var $tpl = $('.js-items-list-item', this.element).eq(0).clone();

            // reset element
            $('input', $tpl).val('');

            return $tpl;
        },

        getItemCount: function () {
            return $('.js-items-list-item', this.element).length;
        },

        addItem: function (item) {
            var $elm,
                $existingElement = this.itemExists(item);

            if ($existingElement) {
                alert('cet élement existe déjà');
            } else {
                $elm = this._findEmptyItem(item) || this.elements.$tpl.clone();

                if (item) {
                    _(item).forOwn(function (num, key) {
                        $('[name^=' + key + '\\[]', $elm).val(item[key]);
                    });
                }

                $('.js-items-list-container', this.element).append($elm);
            }
        },

        _findEmptyItem: function (item) {
            return this.findItemElement(item, false);
        },

        itemExists: function (item) {
            return !!this.findItemElement(item);
        },

        findItemElement: function (item, compareValue) {
            var $items,
                self = this,
                exists = false;

            if (typeof compareValue === 'undefined') {
                compareValue = true;
            }

            _(item).forOwn(function (num, key) {
                $items = $('[name^=' + key + '\\[]', self.element);
                $items.each(function (n, elm) {
                    if (!exists) {
                        var $elm = $(elm);
                        if ((compareValue && (String($elm.val()) === String(item[key]))) || (!compareValue && (String($elm.val()) === ''))) {
                            exists = $elm.parents('.js-items-list-item').first();
                        }
                    }
                });
            });

            return exists;
        },

        removeItem: function (item) {
            var currentCount = this.getItemCount();
            if (item instanceof jQuery && currentCount > 1) {
                item.parent('.js-items-list-item').remove();
            }
        }
    });

})();
