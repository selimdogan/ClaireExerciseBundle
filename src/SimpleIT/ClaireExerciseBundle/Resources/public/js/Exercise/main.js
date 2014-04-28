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

        $(document).on('submit', '.js-ajax-form', function (event) {
            event.preventDefault();

            var $form = $(event.target);

            $.post($form.attr('action'), $form.serialize())
            .done(function() {
                toastr.success('Exercice sauvegardé')
            })
            .fail(function() {
                toastr.error('Erreur durant la sauvegarde')
            });
        });
    });

})();
