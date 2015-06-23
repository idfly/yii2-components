IdFly.Components.AdminDatePicker = {

    bind: function(element) {
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

        display.datepicker({
            altField: element,
            altFormat: 'yy-mm-dd',
            dateFormat: 'dd.mm.yy',
        });
    },

};