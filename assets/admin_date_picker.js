IdFly.Components.AdminDatePicker = {

    bind: function(element, options) {
        var display =
            element.
            clone().
            attr('name', '').
            attr('id', '').
            val('');

        element.
            attr('type', 'hidden').
            after(display);

        if(element.val() !== '') {
            var dateParts = element.val().split('-');
            display.val(dateParts.reverse().join('.'));
        }

        var localOptions = {
            altField: element,
            altFormat: 'yy-mm-dd',
            dateFormat: 'dd.mm.yy',
        };

        localOptions = $.extend({}, localOptions, options);

        display.datepicker(localOptions);
    },

};