(function () {

    $.widget('OC.resourceItems', {
        _create: function() {
//            this.elements = {};
//            this.elements.$tpl = this._getTemplateElement();
            this.element.addClass('claire-exercise-resource-item');
            this.element.draggable({
               appendTo: 'body',
               helper: 'clone',
               scope: 'resources'
            });
            //this._addEvents();
        }//,

//        _addEvents: function () {
////            var self = this;
////
////            this.element
////                .on('click', '.js-items-list-add', function () {
////                        self.addItem();
////                    })
////                .on('click', '.js-items-list-remove', function () {
////                        self.removeItem($(this));
////                    });
//        }
    });

})();
