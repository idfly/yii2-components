IdFly.Components.AdminElementPicker = {

    bindOne: function(element, options) {
        var self = this;

        element.
            find('.picker-select').
            on('click', function() {
                var localOptions = {
                    callback: function(model) {
                        element.
                            find('.picker-value').
                            val(model.id).
                            change();

                        var display = '#' + model.id + ' ' +
                            model[options.displayField || 'name'];

                        element.
                            find('.picker-display').
                            val(display).
                            change();
                    }
                };

                localOptions = $.extend({}, localOptions, options);

                self.pickOne(localOptions);
            });

        element.
            find('.picker-clean').
            on('click', function() {
                element.
                    find('.picker-value').
                    val('').
                    change();

                element.
                    find('.picker-display').
                    val('').
                    change();
            });
    },

    pickOne: function(options) {
        var self = this;
        var url = options.listUrl || '/admin/' + options.controller;

        var modalOptions = {
            title: options.listTitle || 'Выбор элемента',
            ajax: {
                url: url,
            },
            callback: function(modal) {
                self._pickOneBindModal(options, modal);
                if(options.listCallback) {
                    options.listCallback();
                }
            },
        };

        modalOptions = $.extend({}, modalOptions, options.modal);

        IdFly.Components.Modal.create(modalOptions);
    },

    _pickOneBindModal: function(options, modal) {
        var self = this;

        var idKey =
            modal.
            find('.elements-list').
            attr('element-id-key');

        modal.
            find('.list-element').
            on('click', function() {
                var id = $(this).attr(idKey);
                self._pickOneResolveId(options, id, modal);
            });
    },

    _pickOneResolveId: function(options, id, modal) {
        var url = options.infoUrl || '/admin/' + options.controller +
            '/view-json';

        modal.modal('hide');
        $.ajax({
            method: 'get',
            url: url,
            data: {
                id: id,
            },
            success: function(result) {
                options.callback(JSON.parse(result));
            },
        });
    },

};