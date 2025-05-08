;(function($, window, document, undefined) {

  "use strict";

  /**
   * Plugin constructor
   *
   * @param {Object} element
   */
  function Plugin(element) {
    this.$el = $(element);
    this.attachListeners();
  }

  $.extend(Plugin.prototype, {

    /**
     * Attach event listeners to the event handlers.
     *
     * @return {void}
     */
    attachListeners: function() {
      this.$el.on('keydown', function(event) {
        var ctrlKey = (event.metaKey || event.ctrlKey) == true;

        if ((ctrlKey && event.which == 65) || // ctrl+a
            (ctrlKey && event.which == 67) || // ctrl+c
            (ctrlKey && event.which == 86) || // ctrl+v
            (ctrlKey && event.which == 88)) { // ctrl+x
              return true;
        }

        return (
            $.inArray(event.which, [8, 9, 13, 16, 27, 37, 39, 46, 91, 110, 144, 190]) !== -1 ||
            (event.which >= 35 && event.which <= 40) ||
            (event.which >= 48 && event.which <= 57) ||
            (event.which >= 96 && event.which <= 105)
        );
      });

      this.$el.on('paste', event => {
        setTimeout(() => {
          this.$el.val(this.$el.val().replace(/\D/g, ''))
        }, 0);
      });
    }
  });

  $.fn.numericOnly = function() {
    return this.each(function() {
      if (! $.data(this, 'plugin_numericOnly' )) {
        $.data(this, 'plugin_numericOnly', new Plugin(this));
      }
    });
  };

})(jQuery, window, document);
