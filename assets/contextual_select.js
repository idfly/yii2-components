IdFly.Components.ContextualSelect = {

    bind: function(element, options) {
        $(element).get(0)._contextualSelectOptions = options;
        this._bindContext(element, options);
    },

    _bindContext: function(element, options) {
        var self = this;

        if(!options.context) {
            return ;
        }

        var update = function() {
            var data = {};

            if(options.dataCallback) {
                data = options.dataCallback($(this));
            } else {
                if(element.val()) {
                    data[element.attr('name')] = element.val();
                } else {
                    data = null;
                }
            }

            self.update(element, data);
        };

        $(options.context).on('change keyup', update);
        var firstContextElement = $(options.context).get(0);
        if(firstContextElement) {
            update.call(firstContextElement);
        }
    },

    update: function(element, data) {
        console.log(element.val());
        element = $(element);
        element.get(0)._contextualSelectOldValue = element.val();
        element.attr('disabled', 'disabled');

        var options = element.get(0)._contextualSelectOptions;
        if(!data) {
            element.html(
                $('<option value=""></option>').
                    text(options.noDataTitle || 'Выберите элемент')
            );
            return ;
        }

        element.html('<option value="">Загрузка...</option>');

        var prcessAjaxResult = function(result) {
            if(result.startsWith('[')) {
                result = JSON.parse(result);
                element.html('<option value=""></option>');
                for(var recordIndex in result) {
                    var record = result[recordIndex];
                    var option = $('<option></option>').
                        attr('value', record[options.fieldValue || 'id']).
                        text(record[options.fieldLabel || 'name']);

                    if(options.optionCallback) {
                        option = options.optionCallback(option, record);
                    }

                    element.append(option);
                }
            } else {
                element.html(result);
            }

            if(element.find('option').length === 0) {
                element.html(
                    $('<option value=""></option>').
                        text(options.noListTitle || 'Список пуст')
                );
            }

            var value = element.find('option[value!=""]');
            if(value.length !== 0) {
                element.removeAttr('disabled');
                element.
                    find('option[value="' + parseInt(element.get(0).
                        _contextualSelectOldValue) + '"]').
                    attr('selected', 'selected');
            }
        };

        var ajax = {
            method: 'get',
            url: options.url,
            data: data,
            success: prcessAjaxResult,
        };

        ajax = $.extend({}, ajax, options.ajax);

        $.ajax(ajax);
    },

};