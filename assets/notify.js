var IdFly = IdFly || {};
IdFly.Components = IdFly.Components || {};

IdFly.Components.Notify = {

  display: function(text) {
      var notification =
          $('<div class="notification"></div>').
          css('opacity', '0').
          css('zIndex', '9999').
          text(text).
          prependTo('body').
          animate({opacity: '0.75'}, 500);

      window.setTimeout(function() {
          notification.animate({opacity: '0'}, {
              duration: 500,
              complete: function() {$(this).remove();}
          });
      }, 2000);
  },

  ensure: function(result, text, error) {
      if(result) {
          this.display(text || 'Данные сохранены');
      } else {
          this.display(text || 'Ошибка! Данные не были сохранены');
      }
  },

};