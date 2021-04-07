let debounce = (function() {
    let timers = {};
    return function (callback, ms, uniqueId) {
      if (!uniqueId) {
        uniqueId = "Don't call this twice without a uniqueId";
      }
      if (timers[uniqueId]) {
        clearTimeout (timers[uniqueId]);
      }
      timers[uniqueId] = setTimeout(callback, ms);
    };
})();

function checkIfNavbarWidthIsCorrect(){
    if (jQuery('.side-nav').width() === 0 && jQuery('.side-nav').css('z-index') === '10'){
        jQuery('.side-nav').width('200');
    }
    else if (jQuery('.side-nav').width() === 200 && jQuery('.side-nav').css('z-index') === '15') {
        jQuery('.side-nav').width('0');
    }
}

jQuery(document).ready(function( $ ) {
    $('#side-nav__close-btn').click(function() {
        $('.side-nav').width('0');
    });

    $(window).resize(function() {
        debounce(function() {
            checkIfNavbarWidthIsCorrect();
        }, 500, 'sidenav')
    });
});
