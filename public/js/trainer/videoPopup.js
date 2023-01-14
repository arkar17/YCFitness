(function ($, window, document, undefined) {

    'use strict';


    $(function () {
        app.init();
    });

})($, window, document);

var app = {
  init: function(){
    console.log('Ready');
    
    var player = new Plyr($('#player'));
    
    $('.bd-example-modal-lg').on('hide.bs.modal', function (e) {
        player.stop();

      })
  }
}