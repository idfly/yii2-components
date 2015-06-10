UtilityModal = {
  create: function(options) {
      options = options || {};
      var className = (options.class || 'modal-full');
      var modal =
          '<div class="modal">' +
            '<div class="modal-dialog ' + className + '">' +
              '<div class="modal-content">' +
                '<div class="modal-header">' +
                  '<button type="button" class="close" data-dismiss=' +
                      '"modal" aria-label="Close"><span aria-hidden="true">' +
                      '&times;</span></button>' +
                  '<h4 class="modal-title">' + options.title + '</h4>' +
                '</div>' +
                '<div class="modal-body">' +
                '</div>' +
                '<div class="modal-footer">' +
                  '<button type="button" class="btn btn-default close-modal" ' +
                      'data-dismiss="modal">Закрыть</button>' +
                '</div>' +
              '</div>' +
            '</div>' +
          '</div>';

      var element =
          $(modal).
              appendTo('body').
              modal().
              find('.modal-body').
              append(options.contents || '<p>loading...</p>').
              end();

      element.on('hidden.bs.modal', function() {
          element.remove();
      });

      if(options.ajax) {
          $.ajax(options.ajax).then(function(html) {
              element.find('.modal-body').html(html);
              if(options.callback) {
                  options.callback(element);
              }
          });
      }

      return element;
  }
};