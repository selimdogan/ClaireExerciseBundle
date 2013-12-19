(function () {

    $(function() {
        $('.js-items-list').itemsList();


        $('.js-claire-tabs').each(function (n, el) {
            var $radios = $('[name*=resourceOrigin]', el),
                index = $('input[name*=resourceOrigin]:checked', el).index();
            $radios.parent().addClass('hidden');
            $(el).tabs({
                active: index,
                activate: function (event, ui) {
                    var selectedTabIndex = $(this).tabs('option', 'active');
                    $radios.eq(selectedTabIndex).prop('checked', true);
                }
            });
        });

        $('.js-resource-item').resourceItems();

    });

})();
